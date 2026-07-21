<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductRequisition;
use App\Models\ProductStock;
use App\Models\Stores;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ProductRequisitionController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $tenantId = $user->tenant_id;
        $storeId = $user->store_id;

        $requisitions = ProductRequisition::with(['product', 'orderStore', 'issueStore', 'tenant'])
            ->where('archived', 'No')
            ->where('tenant_id', $tenantId)
            ->orderBy('added_date', 'desc')
            ->get();

        $stores = Stores::where('archived', 'No')
            ->where('tenant_id', $tenantId)
            ->where('store_id', '!=', $storeId)
            ->orderBy('store_name', 'desc')
            ->get();

        $products = Product::with([
            'stock' => function ($query) {
                $query->where('archived', 'No');
            }
        ])
            ->where('archived', 'No')
            ->orderBy('product_name', 'asc')
            ->get();

        return view('requisitions.index', compact('requisitions', 'stores', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'issue_store_id' => 'required|string|exists:stores,store_id',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|string|exists:products,product_id',
            'products.*.quantity' => 'required|numeric|min:0.01',
            'products.*.unit_price' => 'nullable|numeric|min:0',
            'requisition_date' => 'required|date',
            'comments' => 'nullable|string',
        ]);

        $user = auth()->user();
        $userId = $user->user_id;
        $username = $user->firstname . ' ' . $user->othername;
        $tenantId = $user->tenant_id;
        $orderStoreId = $user->store_id;

        $issueStore = Stores::where('store_id', $request->issue_store_id)
            ->where('tenant_id', $tenantId)
            ->where('archived', 'No')
            ->first();

        if (!$issueStore) {
            return redirect()->route('requisitions.index')->with('error', 'Selected issue store is not in your tenant.');
        }

        // Validate stock for all products before creating any requisition
        foreach ($request->products as $product) {
            $issueStock = ProductStock::where('product_id', $product['product_id'])
                ->where('store_id', $request->issue_store_id)
                ->where('archived', 'No')
                ->first();

            if (!$issueStock || floatval($issueStock->stock_quantity) < floatval($product['quantity'])) {
                return redirect()->route('requisitions.index')->with('error', 'Selected issue store does not have enough stock for one or more products.');
            }
        }

        DB::beginTransaction();

        try {
            $createdCount = 0;

            foreach ($request->products as $product) {
                $quantity = floatval($product['quantity']);
                $unitPrice = floatval($product['unit_price'] ?? 0);
                $totalValue = $quantity * $unitPrice;

                ProductRequisition::create([
                    'requisition_id' => (string) Str::uuid(),
                    'requisition_no' => 'REQ-' . date('Ymd') . '-' . strtoupper(substr((string) Str::uuid(), 0, 6)),
                    'order_store_id' => $orderStoreId,
                    'issue_store_id' => $request->issue_store_id,
                    'requisition_date' => $request->requisition_date,
                    'unit_price' => $unitPrice,
                    'quantity' => $quantity,
                    'total_value' => $totalValue,
                    'product_id' => $product['product_id'],
                    'tenant_id' => $tenantId,
                    'requsition_status' => 'requested',
                    'comments' => $request->comments,
                    'store_id' => $orderStoreId,
                    'user_id' => $userId,
                    'added_date' => now(),
                    'added_by' => strtoupper($username),
                    'status' => 'Active',
                    'archived' => 'No',
                ]);

                $createdCount++;
            }

            DB::commit();

            return redirect()->route('requisitions.index')->with('success', $createdCount . ' requisition(s) submitted successfully.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('requisitions.index')->with('error', 'Failed to submit requisition(s): ' . $th->getMessage());
        }
    }

    public function approve(Request $request, $id)
    {
        $requisition = ProductRequisition::with(['product'])
            ->where('archived', 'No')
            ->findOrFail($id);

        if ($requisition->requsition_status === 'approved') {
            return redirect()->route('requisitions.index')->with('error', 'Requisition has already been approved.');
        }

        $user = auth()->user();
        $userId = $user ? $user->user_id : null;
        $username = $user ? ($user->firstname . ' ' . $user->othername) : 'System';

        DB::beginTransaction();

        try {
            $issueStock = ProductStock::where('product_id', $requisition->product_id)
                ->where('store_id', $requisition->issue_store_id)
                ->where('archived', 'No')
                ->first();

            if (!$issueStock || floatval($issueStock->stock_quantity) < floatval($requisition->quantity)) {
                DB::rollBack();
                return redirect()->route('requisitions.index')->with('error', 'Insufficient stock at issue store to approve this requisition.');
            }

            $issueStock->update([
                'stock_quantity' => floatval($issueStock->stock_quantity) - floatval($requisition->quantity),
                'updated_date' => now(),
                'updated_by' => $username,
            ]);

            $orderStock = ProductStock::where('product_id', $requisition->product_id)
                ->where('store_id', $requisition->order_store_id)
                ->where('archived', 'No')
                ->first();

            if ($orderStock) {
                $orderStock->update([
                    'stock_quantity' => floatval($orderStock->stock_quantity) + floatval($requisition->quantity),
                    'updated_date' => now(),
                    'updated_by' => $username,
                ]);
            } else {
                ProductStock::create([
                    'stock_id' => (string) Str::uuid(),
                    'product_id' => $requisition->product_id,
                    'stock_quantity' => floatval($requisition->quantity),
                    'stock_date' => now(),
                    'tenant_id' => $requisition->tenant_id,
                    'store_id' => $requisition->order_store_id,
                    'user_id' => $userId,
                    'added_date' => now(),
                    'added_by' => $username,
                    'status' => 'Active',
                    'archived' => 'No',
                ]);
            }

            $requisition->update([
                'requsition_status' => 'approved',
                'updated_date' => now(),
                'updated_by' => $username,
            ]);

            DB::commit();

            return redirect()->route('requisitions.index')->with('success', 'Requisition approved and stock updated.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('requisitions.index')->with('error', 'Failed to approve requisition: ' . $th->getMessage());
        }
    }

    public function destroy($id)
    {
        $requisition = ProductRequisition::where('archived', 'No')->findOrFail($id);

        $user = auth()->user();
        $username = $user ? ($user->firstname . ' ' . $user->othername) : 'System';

        $requisition->update([
            'archived' => 'Yes',
            'archived_by' => $username,
            'archived_date' => now(),
        ]);

        return redirect()->route('requisitions.index')->with('success', 'Requisition deleted successfully.');
    }
}

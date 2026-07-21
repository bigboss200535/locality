<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderDetails;
use App\Models\Supplier;
use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class PurchaseOrderController extends Controller
{
    // display all purchase orders with suppliers and products
    public function index()
    {
        $purchaseOrders = PurchaseOrder::with(['supplier', 'details.product'])
            ->where('archived', 'No')
            ->orderBy('added_date', 'desc')
            ->get();

        $suppliers = Supplier::where('archived', 'No')
            ->where('status', 'Active')
            ->get();

        $products = Product::with(['price'])
            ->where('archived', 'No')
             ->where('status', 'Active')
            ->orderBy('product_name', 'asc')
            ->get();

        return view('purchase-order.index', compact('purchaseOrders', 'suppliers', 'products'));
    }

    public function show($id)
    {
        $user = auth()->user();
        $tenantId = $user ? $user->tenant_id : null;

        $purchaseOrder = PurchaseOrder::with(['supplier', 'details.product'])
            ->where('purchase_order_id', $id)
            ->where('tenant_id', $tenantId)
            ->where('archived', 'No')
            ->firstOrFail();

        $summary = [
            'subtotal' => $purchaseOrder->details->sum('total'),
            'discount' => floatval($purchaseOrder->discount ?? 0),
            'vat' => floatval($purchaseOrder->vat ?? 0),
            'total' => floatval($purchaseOrder->total_value ?? 0),
        ];

        return view('purchase-order.show', compact('purchaseOrder', 'summary'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|string|max:50',
            'order_date' => 'required|date',
            'invoice_no' => 'nullable|string|max:50',
            'discount' => 'nullable|numeric|min:0',
            'vat' => 'nullable|numeric|min:0',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|string|max:50',
            'products.*.quantity' => 'required|numeric|min:0.01',
            'products.*.unit_price' => 'required|numeric|min:0',
        ]);

        $user = auth()->user();
        $userId = $user ? $user->user_id : null;
        $username = $user ? ($user->firstname . ' ' . $user->othername) : 'System';
        $tenantId = $user->tenant_id;
        $storeId = $user->store_id;

        DB::beginTransaction();

        try {
            $purchase_order_id = (string) Str::uuid();
            $gross_total = 0;

            foreach ($request->products as $product) {
                $gross_total += floatval($product['quantity']) * floatval($product['unit_price']);
            }

            $discount = floatval($request->discount ?? 0);
            $vat = floatval($request->vat ?? 0);
            $net_total = max(0, $gross_total - $discount + $vat);

            PurchaseOrder::create([
                'purchase_order_id' => $purchase_order_id,
                'order_no' => 'PO-' . date('Ymd') . '-' . strtoupper(substr($purchase_order_id, 0, 6)),
                'order_date' => $request->order_date,
                'invoice_no' => $request->invoice_no,
                'total_value' => $net_total,
                'discount' => $discount,
                'vat' => $vat,
                'supplier_id' => $request->supplier_id,
                'tenant_id' => $tenantId,
                'store_id' => $storeId,
                'user_id' => $userId,
                'added_date' => now(),
                'added_by' => $username,
                'status' => 'Active',
                'archived' => 'No',
            ]);

            foreach ($request->products as $product) {
                $lineTotal = floatval($product['quantity']) * floatval($product['unit_price']);

                PurchaseOrderDetails::create([
                    'purchase_order_details_id' => (string) Str::uuid(),
                    'purchase_order_id' => $purchase_order_id,
                    'product_id' => $product['product_id'],
                    'quantity' => $product['quantity'],
                    'unit_price' => $product['unit_price'],
                    'total' => $lineTotal,
                    'order_date' => $request->order_date,
                    'tenant_id' => $tenantId,
                    'store_id' => $storeId,
                    'user_id' => $userId,
                    'added_date' => now(),
                    'added_by' => $username,
                    'status' => 'Active',
                    'archived' => 'No',
                ]);
            }

            DB::commit();

            return redirect()->route('purchase-orders.index')->with('success', 'Purchase Order Created successfully.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('purchase-orders.index')->with('error', 'Failed to Create Purchase Order: ' . $th->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'supplier_id' => 'required|string|max:50',
            'order_date' => 'required|date',
            'invoice_no' => 'nullable|string|max:50',
            'discount' => 'nullable|numeric|min:0',
            'vat' => 'nullable|numeric|min:0',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|string|max:50',
            'products.*.quantity' => 'required|numeric|min:0.01',
            'products.*.unit_price' => 'required|numeric|min:0',
        ]);

        $purchaseOrder = PurchaseOrder::findOrFail($id);

        $user = auth()->user();
        $userId = $user ? $user->user_id : null;
        $username = $user ? ($user->firstname . ' ' . $user->othername) : 'System';
        $tenantId = $user->tenant_id;
        $storeId = $user->store_id;

        DB::beginTransaction();

        try {
            $grossTotal = 0;

            foreach ($request->products as $product) {
                $grossTotal += floatval($product['quantity']) * floatval($product['unit_price']);
            }

            $discount = floatval($request->discount ?? 0);
            $vat = floatval($request->vat ?? 0);
            $netTotal = max(0, $grossTotal - $discount + $vat);

            $purchaseOrder->update([
                'supplier_id' => $request->supplier_id,
                'order_date' => $request->order_date,
                'invoice_no' => $request->invoice_no,
                'total_value' => $netTotal,
                'discount' => $discount,
                'vat' => $vat,
                'updated_date' => now(),
                'updated_by' => $username,
            ]);

            // Replace existing details
            PurchaseOrderDetails::where('purchase_order_id', $purchaseOrder->purchase_order_id)->delete();

            foreach ($request->products as $product) {
                $lineTotal = floatval($product['quantity']) * floatval($product['unit_price']);

                PurchaseOrderDetails::create([
                    'purchase_order_details_id' => (string) Str::uuid(),
                    'purchase_order_id' => $purchaseOrder->purchase_order_id,
                    'product_id' => $product['product_id'],
                    'quantity' => $product['quantity'],
                    'unit_price' => $product['unit_price'],
                    'total' => $lineTotal,
                    'order_date' => $request->order_date,
                    'tenant_id' => $tenantId,
                    'store_id' => $storeId,
                    'user_id' => $userId,
                    'added_date' => now(),
                    'added_by' => $username,
                    'status' => 'Active',
                    'archived' => 'No',
                ]);
            }

            DB::commit();

            return redirect()->route('purchase-orders.index')->with('success', 'Purchase order updated successfully.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('purchase-orders.index')->with('error', 'Failed to update purchase order: ' . $th->getMessage());
        }
    }

    public function destroy($id)
    {
        $purchase_order = PurchaseOrder::findOrFail($id);

        $user = auth()->user();
        $username = $user ? ($user->firstname . ' ' . $user->othername) : 'System';

        DB::beginTransaction();

        try {
            $purchase_order->update([
                'archived' => 'Yes',
                'archived_by' => $username,
                'archived_date' => now(),
            ]);

            PurchaseOrderDetails::where('purchase_order_id', $purchase_order->purchase_order_id)
                ->update([
                    'archived' => 'Yes',
                    'archived_by' => $username,
                    'archived_date' => now(),
                ]);

            DB::commit();

            return redirect()->route('purchase-orders.index')->with('success', 'Purchase Order deleted successfully.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('purchase-orders.index')->with('error', 'Failed to delete purchase Order.');
        }
    }
}

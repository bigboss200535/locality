<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderDetails;
use App\Models\ProductStock;
use App\Models\ProductPrice;
use App\Models\StockMovement;
use App\Models\Supplier;
use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

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
        $userId = $user->user_id ?? null;
        $username = $user->firstname . ' ' . $user->othername;
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
                // $line_total = floatval($product['quantity']) * floatval($product['unit_price']);
                $productId = $product['product_id'];
                $quantity = floatval($product['quantity']);
                $unit_Price = floatval($product['unit_price']);
                $line_total = $quantity * $unit_Price;

                PurchaseOrderDetails::create([
                    'purchase_order_details_id' => (string) Str::uuid(),
                    'purchase_order_id' => $purchase_order_id,
                    'product_id' => $product['product_id'],
                    'quantity' => $product['quantity'],
                    'unit_price' => $product['unit_price'],
                    'total' => $line_total,
                    'order_date' => $request->order_date,
                    'tenant_id' => $tenantId,
                    'store_id' => $storeId,
                    'user_id' => $userId,
                    'added_date' => now(),
                    'added_by' => $username,
                    'status' => 'Active',
                    'archived' => 'No',
                ]);

            // Update Product Stock - Add new stock to existing
            $product_stock = ProductStock::where('product_id', $productId)
                ->where('store_id', $storeId)
                ->where('tenant_id', $tenantId)
                ->first();
              
            if ($product_stock) {
                // Add to existing stock
                $product_stock->update([
                    'stock_quantity' => $product_stock->stock_quantity + $quantity,
                    'updated_date' => now(),
                    'updated_by' => $username,
                ]);
            } else {
                // Create new stock record if doesn't exist
                ProductStock::create([
                    'product_stock_id' => (string) Str::uuid(),
                    'product_id' => $productId,
                    'store_id' => $storeId,
                    'tenant_id' => $tenantId,
                    'stock_quantity' => $quantity,
                    'updated_date' => now(),
                    'updated_by' => $username,
                    'archived' => 'No',
                ]);
            }
           
            // Always update with latest unit price by searching for product by id
            $product_price = ProductPrice::where('product_id', $productId)
                ->where('store_id', $storeId)
                ->where('tenant_id', $tenantId)
                ->first();

            if ($product_price) {
                // Update existing price
                $product_price->update([
                    'unit_cost' => $unit_Price,
                    'updated_date' => now(),
                    'updated_by' => $username,
                ]);
            } else {
                // Create new price record
                ProductPrice::create([
                    'product_price_id' => (string) Str::uuid(),
                    'product_id' => $productId,
                    'store_id' => $storeId,
                    'tenant_id' => $tenantId,
                    'unit_cost' => $unit_Price,
                    'updated_date' => now(),
                    'updated_by' => $username,
                    'archived' => 'No',
                ]);

            }
            // add stock log
            StockMovement::create([
                'product_id' => $productId,
                'store_id' => $storeId,
                'stock_quantity' => $quantity,
                'stocked_type' => 'PURCHASE ORDER',
                 'tenant_id' => $tenantId,
                // 'unit_cost' => $unitPrice,
                'added_by' => $username,
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


    public function pdf($id)
    {
        $purchaseOrder = PurchaseOrder::with(['supplier', 'details.product'])
            ->where('archived', 'No')
            ->findOrFail($id);

        $pdf = Pdf::loadView('purchase-order.pdf', compact('purchaseOrder'));

        return $pdf->stream('purchase-order-' . $purchaseOrder->order_no . '.pdf');
    }

 // display all purchase orders with suppliers and product
    public function purchase_orders_pending_approvals()
    {
        $purchaseOrders = PurchaseOrder::with(['supplier', 'details.product'])
            ->where('archived', 'No')
             ->where('order_status', 'Pending')
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

        return view('purchase-order.purchase-order-approval', compact('purchaseOrders', 'suppliers', 'products'));
    }

//     public function getDetails($id)
// {
//     try {
//         $order = PurchaseOrder::with(['supplier', 'details.product'])
//             ->findOrFail($id);
        
//         return response()->json([
//             'success' => true,
//             'order' => [
//                 'order_no' => $order->order_no,
//                 'supplier_name' => $order->supplier->supplier_name ?? 'N/A',
//                 'invoice_no' => $order->invoice_no,
//                 'status' => $order->status,
//                 'order_date_formatted' => $order->order_date ? 
//                     \Carbon\Carbon::parse($order->order_date)->format('d M Y') : 'N/A',
//                 'added_by' => $order->added_by ?? 'N/A',
//                 'discount' => number_format($order->discount ?? 0, 2),
//                 'vat' => number_format($order->vat ?? 0, 2),
//                 'total_value' => number_format($order->total_value, 2),
//                 'sub_total' => number_format($order->details->sum('total'), 2),
//                 'details' => $order->details->map(function($detail) {
//                     return [
//                         'product_name' => $detail->product->product_name ?? 'N/A',
//                         'quantity' => number_format($detail->quantity, 2),
//                         'unit_price' => number_format($detail->unit_price, 2),
//                         'total' => number_format($detail->total, 2),
//                     ];
//                 })
//             ]
//         ]);
//     } catch (\Exception $e) {
//         return response()->json([
//             'success' => false,
//             'message' => 'Order not found or error loading details'
//         ], 404);
//     }
// }

public function getDetails($id)
{
    $order = PurchaseOrder::with(['supplier', 'details.product'])->findOrFail($id);
    
    return response()->json([
        'order_no' => $order->order_no,
        'supplier' => $order->supplier?->supplier_name,
        'invoice_no' => $order->invoice_no,
        'order_date' => $order->order_date ? \Carbon\Carbon::parse($order->order_date)->format('d M Y') : 'N/A',
        'status' => $order->status,
        'added_by' => $order->added_by ?? 'N/A',
        'added_date' => $order->added_date ? \Carbon\Carbon::parse($order->added_date)->format('d M Y, h:i A') : 'N/A',
        'items' => $order->details->map(function($detail) {
            return [
                'product_name' => $detail->product?->product_name,
                'quantity' => $detail->quantity,
                'unit_price' => $detail->unit_price,
                'total' => $detail->total
            ];
        }),
        'subtotal' => $order->details->sum('total'),
        'discount' => $order->discount,
        'vat' => $order->vat,
        'total' => $order->total_value
    ]);
}
}

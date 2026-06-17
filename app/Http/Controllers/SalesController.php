<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductSales;
use App\Models\ProductStock;
use Illuminate\Support\Str;

class SalesController extends Controller
{
    public function index()
    {
        $products = Product::with([
            'category', 
            'price' => function($query) {
                $query->where('archived', 'No');
            }, 
            'stock' => function($query) {
                $query->where('archived', 'No');
            }, 
            'store'
        ])
        ->where('archived', 'No')
        ->orderBy('product_name', 'asc')
        ->get();

        return view('sales.index', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cart_data' => 'required|string',
            'payment_method' => 'required|string|max:50',
        ]);

        $cart = json_decode($request->cart_data, true);

        if (empty($cart)) {
            return redirect()->back()->withErrors(['cart' => 'Your cart is empty.']);
        }

        $user = auth()->user();
        $userId = $user ? $user->user_id : null;
        $username = $user ? ($user->firstname . ' ' . $user->othername) : 'System';
        $tenantId = $user->tenant_id;
        $storeId = $user->store_id ?? 'Default-Store';

        $paymentId = (string) Str::uuid();
        $subtotal = 0;
        $totalDiscount = 0;

        foreach ($cart as $item) {
            $productId = $item['product_id'];
            $quantity = intval($item['quantity']);
            $unitPrice = floatval($item['unit_price']);
            $discount = floatval($item['discount'] ?? 0);

            $lineSubtotal = $unitPrice * $quantity;
            $subtotal += $lineSubtotal;
            $totalDiscount += $discount;

            // Total price is (unit_price * quantity) - discount
            $total = $lineSubtotal - $discount;

            // Save sale record
            ProductSales::create([
                'sales_id' => (string) Str::uuid(),
                'product_id' => $productId,
                'payment_id' => $paymentId,
                'tenant_id' => $tenantId,
                'store_id' => $storeId,
                'quantity' => $quantity,
                'unit_cost' => $unitPrice,
                'total' => $total,
                'transaction_time' => now(),
                'user_id' => $userId,
                'added_date' => now(),
                'tenant_status' => 'Paid',
                'added_by' => $username,
                'archived' => 'No',
            ]);

            // Deduct stock
            $stock = ProductStock::where('product_id', $productId)->first();
            if ($stock) {
                $newQty = max(0, $stock->stock_quantity - $quantity);
                $stock->update([
                    'stock_quantity' => $newQty,
                ]);
            }
        }

        $grandTotal = $subtotal - $totalDiscount;

        return redirect()->route('sales.index')->with([
            'success' => 'Sale completed successfully!',
            'receipt' => [
                'invoice_no' => 'INV-' . strtoupper(substr($paymentId, 0, 8)),
                'payment_id' => $paymentId,
                'items' => $cart,
                'payment_method' => $request->payment_method,
                'subtotal' => $subtotal,
                'discount' => $totalDiscount,
                'grand_total' => $grandTotal,
                'date' => now()->format('d M Y, h:i A'),
                'added_by' => $username,
            ]
        ]);
    }
}

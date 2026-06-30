<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\BillPayment;
use App\Models\ProductSales;
use App\Models\ProductStock;
use Illuminate\Support\Str;

class SalesController extends Controller
{
    public function index()
    {
        $products = Product::with([
            'category',
            'price' => function ($query) {
                $query->where('archived', 'No');
            },
            'stock' => function ($query) {
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
            'cart_data'      => 'required|string',
            'payment_method' => 'required|string|max:50',
        ]);

        $cart = json_decode($request->cart_data, true);

        if (empty($cart)) {
            return redirect()->back()->withErrors(['cart' => 'Your cart is empty.']);
        }

        $user          = auth()->user();
        $userId        = $user ? $user->user_id : null;
        $username      = $user ? ($user->firstname . ' ' . $user->othername) : 'System';
        $tenantId      = $user->tenant_id;
        $storeId       = $user->store_id ?? 'Default-Store';
        $paymentId     = (string) Str::uuid();
        $receiptNumber = $tenantId . date('YmdHis') . rand(10, 99);

        // ── First pass: calculate totals ────────────────────────────────────
        $subtotal      = 0;
        $totalDiscount = 0;

        foreach ($cart as $item) {
            $quantity  = intval($item['quantity']);
            $unitPrice = floatval($item['unit_price']);
            $discount  = floatval($item['discount'] ?? 0);
            $subtotal      += $unitPrice * $quantity;
            $totalDiscount += $discount;
        }

        $grandTotal = $subtotal - $totalDiscount;

        // ── Persist inside a transaction ────────────────────────────────────
        DB::transaction(function () use (
            $cart, $paymentId, $receiptNumber, $grandTotal,
            $tenantId, $storeId, $userId, $username, $request
        ) {
            // 1. Save bill summary to bills_payment
            BillPayment::create([
                'payment_id'      => $paymentId,
                'total_payment'   => $grandTotal,
                'service_payment' => '0',
                'product_payment' => (string) $grandTotal,
                'total_levies'    => 0,
                'receipt_number'  => $receiptNumber,
                'payment_method'  => $request->payment_method,
                'tenant_id'       => $tenantId,
                'store_id'        => $storeId,
                'user_id'         => $userId,
                'transaction_time' => now(),
                'added_date'      => now(),
                'status'          => 'Paid',
                'added_by'        => $username,
                'archived'        => 'No',
            ]);

            // 2. Save each line to product_sales and deduct stock
            foreach ($cart as $item) {
                $productId = $item['product_id'];
                $quantity  = intval($item['quantity']);
                $unitPrice = floatval($item['unit_price']);
                $discount  = floatval($item['discount'] ?? 0);
                $total     = ($unitPrice * $quantity) - $discount;

                ProductSales::create([
                    'sales_id'         => (string) Str::uuid(),
                    'product_id'       => $productId,
                    'payment_id'       => $paymentId,
                    'receipt_number'   => $receiptNumber,
                    'tenant_id'        => $tenantId,
                    'store_id'         => $storeId,
                    'quantity'         => $quantity,
                    'unit_cost'        => $unitPrice,
                    'total'            => $total,
                    'transaction_time' => now(),
                    'user_id'          => $userId,
                    'added_date'       => now(),
                    'status'           => 'Paid',
                    'added_by'         => $username,
                    'archived'         => 'No',
                ]);

                // Deduct stock
                $stock = ProductStock::where('product_id', $productId)
                    ->where('store_id', $storeId)
                    ->where('archived', 'No')
                    ->first();

                if ($stock) {
                    $stock->update([
                        'stock_quantity' => max(0, $stock->stock_quantity - $quantity),
                    ]);
                }
            }
        });

        return redirect()->route('sales.index')->with([
            'success' => 'Sale completed successfully!',
            'receipt' => [
                'invoice_no'     => 'INV-' . strtoupper(substr($paymentId, 0, 8)),
                'payment_id'     => $paymentId,
                'receipt_number' => $receiptNumber,
                'items'          => $cart,
                'payment_method' => $request->payment_method,
                'subtotal'       => $subtotal,
                'discount'       => $totalDiscount,
                'grand_total'    => $grandTotal,
                'date'           => now()->format('d M Y, h:i A'),
                'added_by'       => $username,
            ],
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\BillPayment;
use App\Models\ProductSales;
use App\Models\ProductStock;
use Illuminate\Support\Str;
use Carbon\Carbon;

class SalesController extends Controller
{
    // public function index()
    // {
    //     $products = Product::with([
    //         'category',
    //         'price' => function ($query) {
    //             $query->where('archived', 'No');
    //         },
    //         'stock' => function ($query) {
    //             $query->where('archived', 'No');
    //         },
    //         'store'
    //     ])
    //         ->where('archived', 'No')
    //         ->orderBy('product_name', 'asc')
    //         ->whereHas('price', function ($query) {
    //             $query->where('archived', 'No')
    //              ->where('tenant_id', auth()->user()->tenant_id);
    //             })
    //         ->limit(4)
    //         ->get();

    //     return view('sales.index', compact('products'));
    // }

    public function index()
{
    $tenantId = auth()->user()->tenant_id;

    $products = Product::with([
            'category',
            'price',
            'stock',
            'store'
        ])
        ->where('tenant_id', $tenantId)
        ->where('archived', 'No')
        ->whereHas('price', function ($query) use ($tenantId) {
            $query->where('tenant_id', $tenantId)
                  ->where('archived', 'No')
                  ->where('status', 'Active');
        })
        ->orderBy('product_name')
        ->limit(4)
        ->get();

    return view('sales.index', compact('products'));
}
   
    public function reprint($paymentId)
    {
        $user = auth()->user();
        $tenantId = $user ? $user->tenant_id : null;

        $payment = BillPayment::with('store')
            ->where('receipt_number', $paymentId)
            ->where('tenant_id', $tenantId)
            ->where('archived', 'No')
            ->firstOrFail();

        $items = ProductSales::with('product')
            ->where('receipt_number', $paymentId)
            ->where('tenant_id', $tenantId)
            ->where('archived', 'No')
            ->get();

        $receiptData = [
            'invoice_no'         => 'INV-' . strtoupper(substr($payment->payment_id, 0, 8)),
            'payment_id'         => $payment->payment_id,
            'receipt_number'     => $payment->receipt_number,
            'items'              => $items,
            'payment_method'     => $payment->payment_method,
            'subtotal'           => $payment->subtotal,
            'item_discount'      => $payment->item_discount,
            'cart_discount'      => $payment->cart_discount,
            'cart_discount_type' => $payment->cart_discount_type,
            'cart_discount_value'=> $payment->cart_discount_value,
            'total_discount'     => $payment->total_discount,
            'grand_total'        => $payment->total_payment,
            'date'               => $payment->transaction_time ? Carbon::parse($payment->transaction_time)->format('d M Y, h:i A') : now()->format('d M Y, h:i A'),
            'added_by'           => $payment->added_by,
        ];

        return view('sales.reprint', compact('receiptData'));
    }

    public function getProducts(Request $request)
    {
            $query = Product::with([
                'category',
                'store',
                'price' => function ($query) {
                    $query->where('archived', 'No');
                },
                'stock' => function ($query) {
                    $query->where('archived', 'No');
                }
            ])
            ->where('archived', 'No')
            ->where('tenant_id', auth()->user()->tenant_id);

            // Apply search filter
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('product_name', 'like', "%{$search}%")
                    ->orWhere('product_type', 'like', "%{$search}%")
                    ->orWhereHas('category', function($cat) use ($search) {
                        $cat->where('category_name', 'like', "%{$search}%");
                    });
                });
            }

            // Paginate results
            $products = $query->orderBy('product_name', 'asc')
                            ->paginate(20);

            // Transform data for frontend
            $products->getCollection()->transform(function($product) {
                return [
                    'product_id' => $product->product_id,
                    'product_name' => $product->product_name,
                    'product_type' => $product->product_type,
                    'category_name' => $product->category ? $product->category->category_name : 'N/A',
                    'unit_price' => $product->price ? floatval($product->price->unit_price) : 0,
                    'unit_cost' => $product->price ? floatval($product->price->unit_cost) : 0,
                    'stock_quantity' => $product->stock ? intval($product->stock->stock_quantity) : 0
                ];
            });

            return response()->json($products);
    }

    public function store(Request $request)
    {
    $request->validate([
        'cart_data'      => 'required|string',
        'payment_method' => 'required|string|max:50',
        'cart_discount_amount' => 'nullable|numeric',
        'cart_discount_type' => 'nullable|string',
        'cart_discount_value' => 'nullable|numeric',
    ]);

    $cart = json_decode($request->cart_data, true);

    if (empty($cart)) {
        return redirect()->back()->withErrors(['cart' => 'Your cart is empty.']);
    }

    $user          = auth()->user();
    $userId        = $user->user_id;
    $username      = $user->firstname . ' ' . $user->othername;
    $tenantId      = $user->tenant_id;
    $storeId       = $user->store_id;
    $paymentId     = (string) Str::uuid();
    $receiptNumber = $tenantId . date('YmdHis') . rand(10, 99);

    // ── First pass: calculate totals ────────────────────────────────────
    $subtotal           = 0;
    $itemTotalDiscount  = 0;
    $cartDiscountAmount = floatval($request->cart_discount_amount ?? 0);
    $cartDiscountType   = $request->cart_discount_type ?? 'percentage';
    $cartDiscountValue  = floatval($request->cart_discount_value ?? 0);

    foreach ($cart as &$item) {
        $quantity  = intval($item['quantity']);
        $unitPrice = floatval($item['unit_price']);
        $discount  = floatval($item['discount'] ?? 0);
        
        // Ensure discount_type and discount_value are set
        $item['discount_type'] = $item['discount_type'] ?? 'percentage';
        $item['discount_value'] = floatval($item['discount_value'] ?? 0);
        
        $subtotal          += $unitPrice * $quantity;
        $itemTotalDiscount += $discount;
    }

    $totalDiscount = $itemTotalDiscount + $cartDiscountAmount;
    $grandTotal    = $subtotal - $totalDiscount;

    // ── Persist inside a transaction ────────────────────────────────────
    DB::transaction(function () use (
        $cart, $paymentId, $receiptNumber, $grandTotal, $totalDiscount, 
        $itemTotalDiscount, $cartDiscountAmount, $cartDiscountType, $cartDiscountValue,
        $tenantId, $storeId, $userId, $username, $request, $subtotal
    ) {
        // 1. Save bill summary to bills_payment
        BillPayment::create([
            'payment_id'        => $paymentId,
            'total_payment'     => $grandTotal,
            'service_payment'   => '0',
            'product_payment'   => (string) $grandTotal,
            'total_levies'      => 0,
            'subtotal'          => $subtotal,
            'item_discount'     => $itemTotalDiscount,
            'cart_discount'     => $cartDiscountAmount,
            'cart_discount_type' => $cartDiscountType,
            'cart_discount_value' => $cartDiscountValue,
            'total_discount'    => $totalDiscount,
            'receipt_number'    => $receiptNumber,
            'payment_method'    => $request->payment_method ?? 'CASH',
            'tenant_id'         => $tenantId,
            'store_id'          => $storeId,
            'user_id'           => $userId,
            'transaction_time'  => now(),
            'added_date'        => now(),
            'status'            => 'Paid',
            'added_by'          => $username,
            'archived'          => 'No',
        ]);

        // 2. Save each line to product_sales and deduct stock
        foreach ($cart as $item) {
            $productId = $item['product_id'];
            $quantity  = intval($item['quantity']);
            $unitPrice = floatval($item['unit_price']);
            $discount  = floatval($item['discount'] ?? 0);
            $total     = ($unitPrice * $quantity) - $discount;

            ProductSales::create([
                'sales_id'          => (string) Str::uuid(),
                'product_id'        => $productId,
                'payment_id'        => $paymentId,
                'receipt_number'    => $receiptNumber,
                'tenant_id'         => $tenantId,
                'store_id'          => $storeId,
                'quantity'          => $quantity,
                'unit_cost'         => $unitPrice,
                'discount'          => $discount,
                'discount_type'     => $item['discount_type'] ?? 'percentage',
                'discount_value'    => $item['discount_value'] ?? 0,
                'total'             => $total,
                'transaction_time'  => now(),
                'user_id'           => $userId,
                'added_date'        => now(),
                'status'            => 'Paid',
                'added_by'          => $username,
                'archived'          => 'No',
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

    // Prepare receipt data with all discount information
    $receiptData = [
        'invoice_no'        => 'INV-' . strtoupper(substr($paymentId, 0, 8)),
        'payment_id'        => $paymentId,
        'receipt_number'    => $receiptNumber,
        'items'             => $cart,
        'payment_method'    => $request->payment_method,
        'subtotal'          => $subtotal,
        'item_discount'     => $itemTotalDiscount,
        'cart_discount'     => $cartDiscountAmount,
        'cart_discount_type' => $cartDiscountType,
        'cart_discount_value' => $cartDiscountValue,
        'total_discount'    => $totalDiscount,
        'grand_total'       => $grandTotal,
        'date'              => now()->format('d M Y, h:i A'),
        'added_by'          => $username,
    ];

    return redirect()->route('sales.index')->with([
        'success' => 'Sale completed successfully!',
        'receipt' => $receiptData,
    ]);
}
}

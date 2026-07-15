<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductPrice;
use App\Models\ProductStock;
use Illuminate\Support\Str;

class ProductPriceController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $tenantId = $user->tenant_id;
        $storeId = $user->store_id;

        $products = Product::with([
            'category', 
            'price' => function($query) {
                $query->where('archived', 'No');
            }, 
            'stock', 
            'store'
        ])
        ->where('archived', 'No')
        ->where('tenant_id', auth()->user()->tenant_id)
        ->orderBy('added_date', 'desc')
        ->get();

        $categories = ProductCategory::where('archived', 'No')->get();

        return view('product-price.index', compact('products', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|string|exists:products,product_id',
            'unit_cost' => 'required|numeric|min:0',
            'unit_price' => 'required|numeric|min:0',
        ]);

        $user = auth()->user();
        $userId = $user ? $user->user_id : null;
        $username = $user ? ($user->firstname . ' ' . $user->othername) : 'System';
        $tenantId = $user->tenant_id;
        $storeId = $user->store_id ?? 'Default-Store';

        ProductPrice::updateOrCreate(
            ['product_id' => $request->product_id],
            [
                'unit_cost' => $request->unit_cost,
                'unit_price' => $request->unit_price,
                'tenant_id' => $tenantId,
                'store_id' => $storeId,
                'user_id' => $userId,
                'added_date' => now(),
                'status' => 'Active',
                'added_by' => $username,
                'archived' => 'No',
            ]
        );

        return redirect()->route('product-prices')->with('success', 'Product price created successfully.');
    }

    public function update(Request $request, $productId)
    {
        $request->validate([
            'unit_cost' => 'required|numeric|min:0',
            'unit_price' => 'required|numeric|min:0',
        ]);

        $user = auth()->user();
        $userId = $user->user_id;
        $username = $user->firstname . ' ' . $user->othername;
        $tenantId = $user->tenant_id;
        $storeId = $user->store_id;

        $price = ProductPrice::where('product_id', $productId)->first();

        if ($price) {
            $price->update([
                'unit_cost' => $request->unit_cost,
                'unit_price' => $request->unit_price,
                'updated_date' => now(),
                'updated_by' => $username,
                'archived' => 'No',
            ]);
        } else {
            ProductPrice::create([
                'product_id' => $productId,
                'unit_cost' => $request->unit_cost,
                'unit_price' => $request->unit_price,
                'tenant_id' => $tenantId,
                'store_id' => $storeId,
                'user_id' => $userId,
                'added_date' => now(),
                'status' => 'Active',
                'added_by' => $username,
                'archived' => 'No',
            ]);
        }

        return redirect()->route('product-prices')->with('success', 'Product price updated successfully.');
    }

    public function destroy($productId)
    {
        $price = ProductPrice::where('product_id', $productId)->firstOrFail();

        $user = auth()->user();
        $username = $user->firstname . ' ' . $user->othername;

        $price->update([
            'archived' => 'Yes',
            'archived_by' => strtoupper($username),
            'archived_date' => now(),
        ]);

        return redirect()->route('product-prices')->with('success', 'Product price deleted successfully.');
    }
}

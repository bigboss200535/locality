<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductPrice;
use App\Models\ProductStock;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['category', 'price', 'stock', 'store'])
            ->where('archived', 'No')
            ->orderBy('added_date', 'desc')
            ->get();

        $categories = ProductCategory::where('archived', 'No')->get();

        // If categories are empty, create default category to ensure dropdown works
        if ($categories->isEmpty()) {
            $user = auth()->user();
            $tenantId = $user->tenant_id ?? '04eb01b4-8348-4a61-be64-3790946de696';
            $storeId = $user->store_id ?? 'Default-Store';

            ProductCategory::create([
                'category_id' => 'Default-Category',
                'category_name' => 'General',
                'tenant_id' => $tenantId,
                'store_id' => $storeId,
                'status' => 'Active',
                'archived' => 'No',
            ]);

            $categories = ProductCategory::where('archived', 'No')->get();
        }

        return view('products.index', compact('products', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_name' => 'required|string|max:150',
            'product_type' => 'nullable|string|max:150',
            'category_id' => 'required|string|max:50',
            'cost_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
        ]);

        $user = auth()->user();
        $userId = $user ? $user->user_id : null;
        $username = $user ? ($user->firstname . ' ' . $user->othername) : 'System';
        $tenantId = $user->tenant_id;
        $storeId = $user->store_id ?? 'Default-Store';

        $productId = (string) Str::uuid();

        // 1. Create Product
        Product::create([
            'product_id' => $productId,
            'product_name' => $request->product_name,
            'product_type' => $request->product_type,
            'category_id' => $request->category_id,
            'tenant_id' => $tenantId,
            'store_id' => $storeId,
            'user_id' => $userId,
            'added_date' => now(),
            'status' => 'Active',
            'added_by' => $username,
            'archived' => 'No',
        ]);

        // 2. Create ProductPrice
        ProductPrice::create([
            'product_id' => $productId,
            'unit_cost' => $request->cost_price,
            'unit_price' => $request->selling_price,
            'tenant_id' => $tenantId,
            'store_id' => $storeId,
            'user_id' => $userId,
            'added_date' => now(),
            'status' => 'Active',
            'added_by' => $username,
            'archived' => 'No',
        ]);

        // 3. Create ProductStock
        ProductStock::create([
            'stock_id' => (string) Str::uuid(),
            'product_id' => $productId,
            'stock_quantity' => $request->stock_quantity,
            'stock_date' => now(),
            'stocked_by' => $username,
            'tenant_id' => $tenantId,
            'store_id' => $storeId,
            'user_id' => $userId,
            'added_date' => now(),
            'status' => 'Active',
            'added_by' => $username,
            'archived' => 'No',
        ]);

        return redirect()->route('products')->with('success', 'Product added successfully.');
    }
}

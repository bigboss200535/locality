<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductPrice;
use App\Models\ProductStock;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['category', 'price', 'stock', 'store', 'tenant'])
            ->where('archived', 'No')
            ->where('tenant_id', auth()->user()->tenant_id)
            ->orderBy('added_date', 'desc')
            ->paginate(25);

        $categories = ProductCategory::where('archived', 'No')
            ->where('status', 'Active')
            ->where('tenant_id', auth()->user()->tenant_id)
            // ->cursor();
            ->get();

        // If categories are empty, create default category to ensure dropdown works
        // if ($categories->isEmpty()) {
        //     $user = auth()->user();
        //     $tenantId = $user->tenant_id;
        //     // $storeId = $user->store_id;

        //     ProductCategory::create([
        //         'category_id' => (string) Str::uuid(),
        //         'category_name' => 'GENERAL',
        //         'tenant_id' => $tenantId,
        //         'user_id' => $user->user_id,
        //         'store_id' => $user->store_id,
        //         'added_by' => $user->firstname . ' ' . $user->othername,
        //         'added_date' => now(),
        //         'archived' => 'No',
        //         'status' => 'Active',
        //     ]);

        //     $categories = ProductCategory::where('archived', 'No')->get();
        // }

        return view('products.index', compact('products', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_name' => 'required|string|max:150',
            'product_type' => 'nullable|string|max:150',
            'category_id' => 'required|string|max:50',
            'store_id' => 'nullable|string|max:50',
            'tenant_id' => 'nullable|string|max:50',
            'cost_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'expirable' => 'required|string|max:20', 
            'stockable' => 'required|string|max:20',
        ]);

        $user = auth()->user();
        $userId = $user ? $user->user_id : null;
        $username = $user->firstname . ' ' . $user->othername;
        $tenantId = $request->tenant_id ?? $user->tenant_id;
        $storeId = $request->store_id ?? $user->store_id;

        $productId = (string) Str::uuid();

        try {
              DB::beginTransaction();
              
                            // 1. Create Product
                        Product::create([
                            'product_id' => $productId,
                            'product_name' => strtoupper($request->product_name),
                            'product_type' => strtoupper($request->product_type),
                            'category_id' => $request->category_id,
                            'expirable' => $request->expirable,
                            'stockable' => $request->stockable,
                            'tenant_id' => $tenantId,
                            'store_id' => $storeId,
                            'user_id' => $userId,
                            'added_date' => now(),
                            'status' => 'Active',
                            'added_by' => strtoupper($username),
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
                            'added_by' => strtoupper($username),
                            'archived' => 'No',
                        ]);

                        // 3. Create ProductStock
                        ProductStock::create([
                            'stock_id' => (string) Str::uuid(),
                            'product_id' => $productId,
                            'stock_quantity' => $request->stock_quantity,
                            // 'batch_number' => '',
                            // 'expiry_date' =>'',
                            'stock_date' => now(),
                            'stocked_by' => $username,
                            'tenant_id' => $tenantId,
                            'store_id' => $storeId,
                            'user_id' => $userId,
                            'added_date' => now(),
                            'status' => 'Active',
                            'added_by' => strtoupper($username),
                            'archived' => 'No',
                        ]);
            
             DB::commit();
             return redirect()->route('products')->with('success', 'Product added successfully.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('products')->with('error', 'Failed to add product.');
        }
        

        // return redirect()->route('products')->with('success', 'Product added successfully.');
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'product_name' => 'required|string|max:150',
            'product_type' => 'nullable|string|max:150',
            'category_id' => 'required|string|max:50',
            'store_id' => 'nullable|string|max:50',
            'tenant_id' => 'nullable|string|max:50',
            'expirable' => 'required|string|max:20', 
            'stockable' => 'required|string|max:20',
            'status' => 'nullable|string|max:20',
        ]);

        $product = Product::findOrFail($id);
        
        $user = auth()->user();
        $username = $user->firstname . ' ' . $user->othername;

        $product->update([
            'product_name' => strtoupper($request->product_name),
            'product_type' => strtoupper($request->product_type),
            'category_id' => $request->category_id,
            'expirable' => $request->expirable,
            'stockable' => $request->stockable,
            'store_id' => $request->store_id ?? $product->store_id,
            'tenant_id' => $request->tenant_id ?? $product->tenant_id,
            'status' => $request->status,
            'updated_date' => now(),
            'updated_by' => strtoupper($username),
        ]);

        return redirect()->route('products')->with('success', 'Product Updated successfully.');
    }


     public function destroy($id)
    {
        $product = Product::findOrFail($id);
        
        $user = auth()->user();
        $username = $user->firstname . ' ' . $user->othername;

        $product->update([
            'archived' => 'Yes',
            'archived_by' => $username,
            'archived_date' => now(),
        ]);

        return redirect()->route('products')->with('success', 'Product Deleted successfully.');
    }
}

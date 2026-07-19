<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductStock;
use App\Models\StockMovement;

use Illuminate\Support\Str;

class ProductStockController extends Controller
{
    // public function index()
    // {
    //     $user = auth()->user();
    //     $tenantId = $user->tenant_id;
    //     $storeId = $user->store_id;

    //     $products = Product::with([
    //         'category', 
    //         'price', 
    //         'stock' => function($query) {
    //             $query->where('archived', 'No');
    //         }, 
    //         'store'
    //     ])
    //     ->where('archived', 'No')
    //     ->orderBy('added_date', 'desc')
    //     ->paginate(50);

    //     $categories = ProductCategory::where('archived', 'No')->get();

    //     return view('inventory.index', compact('products', 'categories'));
    // }
    public function index(Request $request)
    {
            $query = Product::query()
                ->with([
                    'category:category_id,category_id,category_name',
                    'store:store_id,store_id,store_name',
                    'tenant:tenant_id,tenant_id,tenant_name',
                    'price:product_id,product_id,unit_price,unit_cost',
                    'stock:product_id,product_id,stock_quantity,stock_date',
                ])
                ->where('tenant_id', auth()->user()->tenant_id)
                ->where('archived', 'No');

            if ($request->filled('search')) {
                $search = $request->search;

                $query->where(function ($q) use ($search) {

                    $q->where('product_name','LIKE',"%{$search}%")
                    ->orWhere('product_type','LIKE',"%{$search}%")
                    // ->orWhere('product_id','LIKE',"%{$search}%")
                    ->orWhereHas('category',function($q) use($search){
                            $q->where('category_name','LIKE',"%{$search}%");
                    })

                    ->orWhereHas('store',function($q) use($search){
                            $q->where('store_name','LIKE',"%{$search}%");
                    });

                });

            }

            $products = $query
                    ->orderByDesc('added_date', 'asc')
                    ->paginate(25)
                    ->withQueryString();


           return view('inventory.index', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|string|exists:products,product_id',
            'stock_quantity' => 'required|integer|min:0',
            'batch_number' => 'nullable|string|max:50'
        ]);

        $user = auth()->user();
        $userId = $user->user_id;
        $username = $user->firstname . ' ' . $user->othername;
        $tenantId = $user->tenant_id;
        $storeId = $user->store_id;

         StockMovement::Create([
                'stock_movement _id' => (string) Str::uuid(),
                'product_id' => $request->product_id,
                'stock_quantity' => $request->stock_quantity, 
                'stock_date' => now(), 
                'batch_number' => $request->batch_number ?? '', 
                'stocked_type' => 'STOCK UPDATE',
                'stocked_by' => strtoupper( $username),
                'tenant_id' => $tenantId,
                'store_id' => $storeId,
                'user_id' => $userId,
                'added_date' => now(),
                'status' => 'Active',
                'archived' => 'No',
                'added_by' => strtoupper($username),
        ]);

        ProductStock::updateOrCreate(
            ['product_id' => $request->product_id],
            [
                'stock_id' => (string) Str::uuid(),
                'stock_quantity' => $request->stock_quantity,
                'stock_date' => now(),
                'batch_number' => $request->batch_number ?? '', 
                'added_by' => strtoupper($username),
                'tenant_id' => $tenantId,
                'store_id' => $storeId,
                'user_id' => $userId,
                'added_date' => now(),
                'status' => 'Active',
                // 'added_by' =>  $username,
                'archived' => 'No',
            ]
        );

        return redirect()->route('inventory.index')->with('success', 'Product stock added successfully.');
    }

    public function update(Request $request, $productId)
    {
        $request->validate([
            'stock_quantity' => 'required|integer|min:0',
        ]);

        $user = auth()->user();
        $userId = $user->user_id;
        $username = $user->firstname . ' ' . $user->othername;
        $tenantId = $user->tenant_id;
        $storeId = $user->store_id;

        $stock = ProductStock::where('product_id', $productId)->first();

        if ($stock) {
            // if stock is availbale, insert data into stock movement table for report sake
            StockMovement::Create([
                'stock_movement _id' => (string) Str::uuid(),
                'product_id' => $productId,
                'stock_quantity' => $request->stock_quantity, 
                'stock_date' => now(), 
                'stocked_type' => 'STOCK MODIFICATION',
                'stocked_by' => strtoupper( $username),
                'tenant_id' => $tenantId,
                'store_id' => $storeId,
                'user_id' => $userId,
                'added_date' => now(),
                'status' => 'Active',
                'archived' => 'No',
                'added_by' => strtoupper($username),
                ]);
            // then update the stock quantity 
            $stock->update([
                'stock_quantity' => $request->stock_quantity,
                'updated_date' => now(),
                'updated_by' => $username,
                'archived' => 'No',
            ]);
        } else {
            // IF no stock is available, create a new product stock data
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

            // Insert data into stock movement table for report sake
            StockMovement::Create([
                'stock_movement _id' => (string) Str::uuid(),
                'product_id' => $productId,
                'stock_quantity' => $request->stock_quantity, 
                'stock_date' => now(), 
                'stocked_type' => 'STOCK MODIFICATION',
                'stocked_by' => strtoupper( $username),
                'tenant_id' => $tenantId,
                'store_id' => $storeId,
                'user_id' => $userId,
                'added_date' => now(),
                'status' => 'Active',
                'archived' => 'No',
                'added_by' => strtoupper($username),
                ]);
        }

        return redirect()->route('inventory.index')->with('success', 'Product stock updated successfully.');
    }

    public function destroy($productId)
    {
        $stock = ProductStock::where('product_id', $productId)->firstOrFail();

        $user = auth()->user();
        $username = $user ? ($user->firstname . ' ' . $user->othername) : 'System';

        $stock->update([
            'archived' => 'Yes',
            'archived_by' => $username,
            'archived_date' => now(),
        ]);

        return redirect()->route('inventory.index')->with('success', 'Product stock deleted successfully.');
    }
}

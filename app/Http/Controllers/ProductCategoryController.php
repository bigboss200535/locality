<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductCategory;
use App\Models\Tenant;
use App\Models\Stores;
use Illuminate\Support\Str;

class ProductCategoryController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $tenantId = $user->tenant_id ?? '04eb01b4-8348-4a61-be64-3790946de696';
        $storeId = $user->store_id ?? 'default-store';

         $categories = ProductCategory::with(['store', 'tenant'])
            ->where('tenant_id', $tenantId)
            ->where('store_id', $storeId)
            ->get();

        // $categories = ProductCategory::where('archived', 'No')
        //     ->where('tenant_id', $tenantId)
        //     ->where('store_id', $storeId)
        //     ->get();

        $tenants = Tenant::all();
        $stores = Stores::all();

        return view('product-category.index', compact('categories', 'tenants', 'stores'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_name' => 'required|string|max:150',
            'tenant_name' => 'nullable|string|max:150',
            'store_name' => 'nullable|string|max:150',
        ]);

        $user = auth()->user();
        $userId = $user ? $user->user_id : null;
        $username = $user->firstname . ' ' . $user->othername;
        $tenantId = $user->tenant_id ?? '04eb01b4-8348-4a61-be64-3790946de696';
        $storeId = $user->store_id ?? 'default-store';

        ProductCategory::create([
            'category_id' => (string) Str::uuid(),
            'category_name' => $request->category_name,
            'tenant_id' => $request->tenant_name ?? $tenantId,
            'store_id' => $request->store_name ?? $storeId,
            'user_id' => $userId,
            'added_date' => now(),
            'added_by' => $username,
            'archived' => 'No',
            'status' => 'Active',
        ]);

        return redirect()->route('product-categories')->with('success', 'Product Category added successfully.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'category_name' => 'required|string|max:150',
        ]);

        $category = ProductCategory::findOrFail($id);
        
        $user = auth()->user();
        $username = $user ? ($user->firstname . ' ' . $user->othername) : 'System';

        $category->update([
            'category_name' => $request->category_name,
            'updated_date' => now(),
            'updated_by' => $username,
        ]);

        return redirect()->route('product-categories')->with('success', 'Product Category updated successfully.');
    }

    public function toggleStatus($id)
    {
        $category = ProductCategory::findOrFail($id);
        $newStatus = $category->status === 'Active' ? 'Inactive' : 'Active';
        
        $user = auth()->user();
        $username = $user ? ($user->firstname . ' ' . $user->othername) : 'System';

        $category->update([
            'status' => $newStatus,
            'updated_date' => now(),
            'updated_by' => $username,
        ]);

        return redirect()->route('product-categories')->with('success', "Product Category status changed to {$newStatus}.");
    }

    public function destroy($id)
    {
        $category = ProductCategory::findOrFail($id);
        
        $user = auth()->user();
        $username = $user ? ($user->firstname . ' ' . $user->othername) : 'System';

        $category->update([
            'archived' => 'Yes',
            'archived_by' => $username,
            'archived_date' => now(),
        ]);

        return redirect()->route('product-categories')->with('success', 'Product Category deleted successfully.');
    }
}

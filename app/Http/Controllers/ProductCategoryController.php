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
        $tenantId = $user->tenant_id;
        $storeId = $user->store_id;
         // query to get categories based on user role
        $categories_query = ProductCategory::with(['store', 'tenant']);
            
        // For developers, show all stores and tenant categories
        if ($user->role_id == '1001') {
            // No store filter for developers 
        } else {
            // For admins and others, filter by store
            $categories_query->where('store_id', $storeId);
        }
         $categories = $categories_query->get();

        //  FETCH TENANTS AND STORES FOR FORMS
        if($user->role_id === '1001') {
           
            $tenants = Tenant::where('archived', 'No')->get();
            $stores = Stores::where('archived', 'No')->get();
        } else if ($user->role_id === '1003' || $user->role_id === '1002') {
           
              $tenants = Tenant::where('archived', 'No')->where('tenant_id', $tenantId)->first();
              $stores = Stores::where('archived', 'No')->where('tenant_id', $tenantId)->get();
        } else {
            $stores = Stores::where('archived', 'No')->where('store_id', $storeId)->get();
        }
        // $categories = $categoriesQuery->get();
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
        $tenantId = $user->tenant_id;
        $storeId = $user->store_id;

        $check_category = ProductCategory::where('category_name', $request->category_name)
            ->where('tenant_id', $tenantId)
            ->where('store_id', $storeId)
            ->where('archived', 'No')
            ->first();

        if ($check_category) {
            return redirect()->back()->with('error', 'Product Category Already exists.');
        }

        ProductCategory::create([
            'category_id' => (string) Str::uuid(),
            'category_name' => strtoupper($request->category_name),
            'tenant_id' => $request->tenant_name ?? $tenantId,
            'store_id' => $request->store_name ?? $storeId,
            'user_id' => $userId,
            'added_date' => now(),
            'added_by' => strtoupper($username),
            'archived' => 'No',
            'status' => 'Active',
        ]);

        return redirect()->route('product-categories')->with('success', 'Product Category Added successfully.');
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
            'category_name' => strtoupper($request->category_name),
            'updated_date' => now(),
            'updated_by' => strtoupper($username),
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
            'updated_by' => strtoupper($username),
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

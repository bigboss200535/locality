<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductSpoilage;
use App\Models\ProductStock;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ProductManagementController extends Controller
{
    public function manage_expiry()
    {
        return view('item-managment.expiry-management');
    }

    public function spoilages()
    {
        $user = auth()->user();

     // Fetch all active products so the user can record spoilage for them based on access level
        if ($user->role_id === '1001') {
            $products = Product::with(['category', 'price', 'stock', 'store', 'tenant'])
                ->where('archived', 'No')
                // ->where('store_id', $user->store_id)
                // ->where('tenant_id', $user->tenant_id)
                ->orderBy('product_name', 'asc')
                ->get();

        } else if ($user->role_id === '1003' || $user->role_id === '1002') {
             $products = Product::with(['category', 'price', 'stock'])
                ->where('archived', 'No')
                ->where('store_id', $user->store_id)
                // ->where('tenant_id', $user->tenant_id)
                ->orderBy('product_name', 'asc')
                ->get();
        } else {
            $products = Product::with(['category', 'price', 'stock'])
                ->where('archived', 'No')
                ->where('store_id', $user->store_id)
                ->where('tenant_id', $user->tenant_id)
                ->orderBy('product_name', 'asc')
                ->get();
        }
        
        // Fetch recorded spoilages based on access levels
        $spoilages = ProductSpoilage::with(['product'])
            ->where('archived', 'No')
            ->orderBy('added_date', 'desc')
            ->get();

        return view('item-managment.spoilage-management', compact('products', 'spoilages'));
    }

    public function storeSpoilage(Request $request)
    {
        $request->validate([
            'product_id' => 'required|string|exists:products,product_id',
            'quantity' => 'required|integer|min:1',
            'unit_cost' => 'required|numeric|min:0',
            'reason' => 'required|string|max:50',
            'spoiled_date' => 'required|date',
            'comments' => 'nullable|string',
        ]);

        $user = auth()->user();
        $userId = $user->user_id;
        $username = $user ? ($user->firstname . ' ' . $user->othername) : 'System';
        $tenantId = $user->tenant_id;
        $storeId = $user->store_id;

        // Check stock balance first and lock for update
        $stock = ProductStock::where('product_id', $request->product_id)->lockForUpdate()->first();
        $currentStockQty = $stock ? $stock->stock_quantity : 0;

        if ($currentStockQty < $request->quantity) {
            return redirect()->back()->withErrors(['quantity' => 'Insufficient stock balance. Current stock is ' . $currentStockQty])->withInput();
        }

        // Create the spoilage record
        ProductSpoilage::create([
            'product_management_id' => (string) Str::uuid(),
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'unit_cost' => $request->unit_cost,
            'reason' => $request->reason,
            'spoiled_date' => $request->spoiled_date,
            'comments' => $request->comments,
            'tenant_id' => $tenantId,
            'store_id' => $storeId,
            'user_id' => $userId,
            'added_date' => now(),
            'status' => 'Confirmed',
            'added_by' => $username,
            'archived' => 'No',
        ]);

        // Subtract/Deduct Quantity from stock balances
        if ($stock) {
            $stock->update([
                'stock_quantity' => $stock->stock_quantity - $request->quantity,
                'updated_date' => now(),
                'updated_by' => $username,
            ]);
        }

        return redirect()->route('spoilages.index')->with('success', 'Spoilage recorded successfully and stock balance updated.');
    }

    public function destroySpoilage($id)
    {
        $spoilage = ProductSpoilage::findOrFail($id);
        $user = auth()->user();
        $username = $user ? ($user->firstname . ' ' . $user->othername) : 'System';

        // Restore stock when deleting spoilage
        $stock = ProductStock::where('product_id', $spoilage->product_id)->first();
        if ($stock) {
            $stock->update([
                'stock_quantity' => $stock->stock_quantity + $spoilage->quantity,
                'updated_date' => now(),
                'updated_by' => $username,
            ]);
        }

        $spoilage->update([
            'archived' => 'Yes',
            'archived_by' => $username,
            'archived_date' => now(),
        ]);

        return redirect()->route('spoilages.index')->with('success', 'Spoilage record removed and stock returned.');
    }
}

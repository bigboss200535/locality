<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;
use App\Models\Tenant;
use App\Models\Stores;

class SuppliersController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::with('tenant','store')
            ->where('archived', 'No')
            ->orderBy('added_date', 'desc')
            ->get();

        $tenants = Tenant::where('archived', 'No')->get();
        $stores = Stores::where('archived', 'No')->get();

        return view('supplier.index', compact('suppliers', 'tenants', 'stores'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_name' => 'required|string|max:150',
            'telephone' => 'nullable|string|max:150',
            'email' => 'nullable|email|max:150',
            'tenant_id' => 'nullable|string|max:150',
            'store_id' => 'nullable|string|max:50', 
            'status' => 'nullable|in:Active,Inactive',
        ]);

        $user = auth()->user();
        $userId = $user ? $user->user_id : null;
        $username = $user->firstname . ' ' . $user->othername;
        $tenantId = $user->tenant_id;
        $storeId = $user->store_id;
        
        $check_supplier = Supplier::where('supplier_name', $request->supplier_name)
                ->where('tenant_id', $request->tenant_id)
                ->where('archived', 'No')
                ->first();
          
        if ($check_supplier) {
            return redirect()->back()->with('error', 'Supplier Already exists.');
        }
        
        $supplier_count = Supplier::count();

        Supplier::create([
            'supplier_id' => date('mdHisY') . '-' . str_pad($supplier_count + 1, 3, '0', STR_PAD_LEFT),
            'supplier_name' => strtoupper($request->supplier_name),
            'telephone' => $request->telephone,
            'email' => $request->email,
            'tenant_id' => $request->tenant_id ?? $tenantId,
            'user_id' => $userId,
            'store_id' => $request->store_id ?? $storeId,
            'added_date' => now(),
            'added_by' => strtoupper($username),
            'archived' => 'No',
            'status' => 'Active',
           
        ]);

        return redirect()->route('suppliers.index')->with('success', 'Supplier Added successfully.');
    }

     public function update(Request $request, $id)
    {
        $request->validate([
            'supplier_name' => 'required|string|max:150',
            'telephone' => 'nullable|string|max:150',
            'email' => 'nullable|email|max:150',
            'status' => 'nullable|string|max:150',
            'store_id' => 'nullable|string|max:150',
        ]);

        $supplier = Supplier::findOrFail($id);
        
        $user = auth()->user();
        $username = $user->firstname . ' ' . $user->othername;

        $supplier->update([
            'supplier_name' => strtoupper($request->supplier_name),
            'telephone' => $request->telephone,
            'email' => $request->email,
            'status' => $request->status ?? 'Active',
            'updated_date' => now(),
            'updated_by' => strtoupper($username),
            'store_id' => $request->store_id,
        ]);

        return redirect()->route('suppliers.index')->with('success', 'Supplier Updated successfully.');
    }

     public function destroy($id)
    {
        $supplier = Supplier::findOrFail($id);
        
        $user = auth()->user();
        $username = $user ? ($user->firstname . ' ' . $user->othername) : 'System';

        $supplier->update([
            'archived' => 'Yes',
            'archived_by' => $username,
            'archived_date' => now(),
        ]);

        return redirect()->route('suppliers.index')->with('success', 'Supplier Deleted successfully.');
    }

}

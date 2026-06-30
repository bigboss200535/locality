<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Stores;
use App\Models\Tenant;
use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class StoresController extends Controller
{
    public function index()
    {
        $stores = Stores::with('tenant')
            ->where('archived', 'No')
            ->orderBy('added_date', 'desc')
            ->get();

        $tenants = Tenant::where('archived', 'No')->get();

        return view('store.index', compact('stores', 'tenants'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'store_name' => 'required|string|max:150',
            'type_of_business' => 'nullable|string|max:150',
            'telephone' => 'nullable|string|max:150',
            'tenant_id' => 'nullable|string|max:150',
        ]);

        $user = auth()->user();
        $userId = $user ? $user->user_id : null;
        $username = $user->firstname . ' ' . $user->othername;
        $tenantId = $user->tenant_id;
        $storeId = $user->store_id;
        
         if ($user->role_id === '1001') {
             $check_store = Stores::where('store_name', $request->store_name)
                     ->where('tenant_id', $request->tenant_id)
                     ->where('archived', 'No')
                    ->first();
          } else if ($user->role_id === '1003' || $user->role_id === '1002') {
             $check_store = Stores::where('store_name', $request->store_name)
                     ->where('tenant_id', $tenantId)
                     ->where('archived', 'No')
                    ->first();
          } else {
                $check_store = Stores::where('store_name', $request->store_name)
                        ->where('tenant_id', $tenantId)
                        ->where('store_id', $storeId)
                        ->where('archived', 'No')
                        ->first();
         }


        if ($check_store) {
            return redirect()->back()->with('error', 'Store already exists.');
        }
        
        $store_count = Stores::count();

        Stores::create([
            'store_id' => date('mdHisY') . '-' . str_pad($store_count + 1, 3, '0', STR_PAD_LEFT),
            'store_name' => strtoupper($request->store_name),
            'tenant_id' => $request->tenant_id ?? $tenantId,
            'type_of_business' => strtoupper($request->type_of_business),
            'telephone' => $request->telephone,
            'store_code' => 'S-' . date('Ymd'). date('Y'),
            'user_id' => $userId,
            'added_date' => now(),
            'added_by' => strtoupper($username),
            'archived' => 'No',
            'status' => 'Active',
        ]);

        return redirect()->route('stores.index')->with('success', 'Store added successfully.');
    }

     public function update(Request $request, $id)
    {
        $request->validate([
            'store_name' => 'required|string|max:150',
            'type_of_business' => 'nullable|string|max:150',
            'telephone' => 'nullable|string|max:150',
        ]);

        $store = Stores::findOrFail($id);
        
        $user = auth()->user();
        $username = $user ? ($user->firstname . ' ' . $user->othername) : 'System';

        $store->update([
            'store_name' => strtoupper($request->store_name),
            'type_of_business' => strtoupper($request->type_of_business),
            'telephone' => $request->telephone,
            'updated_date' => now(),
            'updated_by' => $username,
        ]);

        return redirect()->route('stores.index')->with('success', 'Store updated successfully.');
    }

     public function destroy($id)
    {
        $store = Stores::findOrFail($id);
        $check_store = Product::where('store_id', $id)->first();

        if($check_store){

             return redirect()->route('stores.index')->with('error', 'Unsuccessful, Store has data attached ');
        }

        $user = auth()->user();
        $username = $user->firstname . ' ' . $user->othername;

        $store->update([
            'archived' => 'Yes',
            'archived_by' => strtoupper($username),
            'archived_date' => now(),
        ]);

        return redirect()->route('stores.index')->with('success', 'Store deleted successfully.');
    }


}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use App\Models\Stores;
use App\Models\Tenant;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class TenantController extends Controller
{
     public function index()
    {
        $tenants = Tenant::where('archived', 'No')->get();

        return view('tenants.index', compact('tenants'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tenant_name' => 'required|string|max:150',
            'type_of_business' => 'nullable|string|max:150',
            'telephone' => 'nullable|string|max:150',
            'email' => 'nullable|string|max:150',
            'slogan' => 'nullable|string|max:150',
            'location' => 'nullable|string|max:150',
        ]);

        $user = auth()->user();
        $username = $user->firstname . ' ' . $user->othername;
       
         if($user->role_id === '1001') {
              $check_tenant = Tenant::where('tenant_name', $request->tenant_name)
                ->where('archived', 'No')
                ->first();
          } 

        if($check_tenant) {
            return redirect()->back()->with('error', 'Tenant Already Exists.');
        }
        
        $check_tenant = Tenant::count();

        Tenant::create([
            'tenant_id' => date('mdHisY') . '-' . str_pad($store_count + 1, 3, '0', STR_PAD_LEFT),
            'tenant_name' => strtoupper($request->tenant_name),
            'slogan' => strtoupper($request->slogan),
            'type_of_business' => strtoupper($request->type_of_business),
            'telephone' => $request->telephone,
            'email' => $request->email,
            'location' => strtoupper($request->location),
            'added_date' => now(),
            'added_by' => strtoupper($username),
            'archived' => 'No',
            'status' => 'Active',
        ]);

        return redirect()->route('tenants.index')->with('success', 'Tenant Added Successfully.');
    }
}

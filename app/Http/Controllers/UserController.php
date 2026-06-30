<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Tenant;
use App\Models\Stores;
use App\Models\Role;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with(['tenant', 'store', 'role'])
            ->where('archived', 'No')
            ->where('usage', '1')
            ->orderBy('added_date', 'desc')
            ->get();

        $tenants = Tenant::where('archived', 'No')->get();
        $stores = Stores::where('archived', 'No')->get();
        $roles = Role::where('archived', 'No')->where('usage', '1')->get();

        return view('users.index', compact('users', 'tenants', 'stores', 'roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'firstname' => 'required|string|max:150',
            'othername' => 'required|string|max:150',
            'email' => 'required|email|max:100|unique:users,email',
            'telephone' => 'nullable|string|max:50|unique:users,telephone',
            'tenant_id' => 'nullable|string|exists:tenants,tenant_id',
            'store_id' => 'required|string|exists:stores,store_id',
            'role_id' => 'required|string|exists:roles,role_id',
            'password' => 'required|string|min:6',
            'blocked' => 'required|string|in:Yes,No',
        ]);

        $current_user = auth()->user();
        $username = $current_user->firstname . ' ' . $current_user->othername;

        User::create([
            'user_id' => (string) Str::uuid(),
            'email' => $request->email,
            'firstname' => $request->firstname,
            'othername' => $request->othername,
            'telephone' => $request->telephone,
            'tenant_id' => $request->tenant_id ?? $current_user->tenant_id,
            'store_id' => $request->store_id,
            'role_id' => $request->role_id,
            'usage' => '1',
            'password' => Hash::make($request->password),
            'blocked' => $request->blocked,
            'status' => 'Active',
            'archived' => 'No',
            'added_date' => now(),
            'added_by' => strtoupper($username),
        ]);

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'firstname' => 'required|string|max:150',
            'othername' => 'required|string|max:150',
            'email' => 'required|email|max:100|unique:users,email,' . $id . ',user_id',
            'telephone' => 'nullable|string|max:50|unique:users,telephone,' . $id . ',user_id',
            'tenant_id' => 'nullable|string|exists:tenants,tenant_id',
            'store_id' => 'required|string|exists:stores,store_id',
            'role_id' => 'required|string|exists:roles,role_id',
            'password' => 'nullable|string|min:6',
            'blocked' => 'required|string|in:Yes,No',
        ]);

        $current_user = auth()->user();
        $username = $current_user->firstname . ' ' . $current_user->othername;

        $data = [
            'firstname' => strtoupper($request->firstname),
            'othername' => strtoupper($request->othername),
            'email' => $request->email,
            'telephone' => $request->telephone,
            'tenant_id' => $request->tenant_id,
            'store_id' => $request->store_id,
            'role_id' => $request->role_id,
            'blocked' => $request->blocked,
            'updated_by' => strtoupper($username),
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    public function destroy($id)
    {

        $user = User::findOrFail($id);

        $current_user = auth()->user();
        $username = $current_user->firstname . ' ' . $current_user->othername;

        $user->update([
            'archived' => 'Yes',
            'archived_by' => strtoupper($username),
            'archived_date' => now(),
        ]);

        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Stores;
use App\Models\Role;
use App\Models\Tenant;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Role::create([
            'role_id' => '1001',
            'usage' => '0',
            'role_name' => 'DEVELOPER',
            'role_description' => 'SYSTEM DEVELOPER',
            'added_date' =>  now(),
            'user_id' => 'e4c06f74-4b4e-4669-9137-1729876865a3'
        ]);

        Role::create([
            'role_id' => '1002',
            'usage' => '1',
            'role_name' => 'SUPER ADMINISTRATOR',
            'role_description' => 'SUPER ADMINISTRATOR',
            'added_date' =>  now(),
            'user_id' => 'e4c06f74-4b4e-4669-9137-1729876865a3'
        ]);

        Role::create([
            'role_id' => '1003',
            'usage' => '1',
            'role_name' => 'ADMINISTRATOR',
            'role_description' => 'ADMINISTRATOR',
            'added_date' =>  now(),
            'user_id' => 'e4c06f74-4b4e-4669-9137-1729876865a3'
        ]);

        Role::create([
            'role_id' => '1004',
            'usage' => '1',
            'role_name' => 'REVENUE OFFICER',
            'role_description' => 'SYSTEM USER',
            'added_date' =>  now(),
            'user_id' => 'e4c06f74-4b4e-4669-9137-1729876865a3'
        ]);

        Role::create([
            'role_id' => '1005',
            'usage' => '1',
            'role_name' => 'SALES OFFICER',
            'role_description' => 'SYSTEM USER',
            'added_date' =>  now(),
            'user_id' => 'e4c06f74-4b4e-4669-9137-1729876865a3'
        ]);

        Role::create([
            'role_id' => '1006',
            'usage' => '1',
            'role_name' => 'DATA ENTRY OFFICER',
            'role_description' => 'SYSTEM USER',
            'added_date' =>  now(),
            'user_id' => 'e4c06f74-4b4e-4669-9137-1729876865a3'
        ]);

        Tenant::create([
            'tenant_id' => '40401',
            'tenant_name' => 'JOSHUA ENTERPRISES',
            'tenant_description' => 'SALES',
            'type_of_business' => 'PRODUCT SALES',
            'added_date' =>  now(),
            'added_by' => 'MOHAMMED ALHASSAN',
            'user_id' => 'e4c06f74-4b4e-4669-9137-1729876865a3'
        ]);

        //  Tenant::create([
        //     'tenant_id' => '40402',
        //     'tenant_name' => 'MOHAMMED ENTERPRISES',
        //     'tenant_description' => 'SALES',
        //     'type_of_business' => 'PRODUCT SALES',
        //     'added_date' =>  now(),
        //     'added_by' => 'MOHAMMED ALHASSAN',
        //     'user_id' => 'e4c06f74-4b4e-4669-9137-1729876865a3'
        // ]);

        Stores::create([
            'store_id' => '200201',
            'tenant_id' => '40401',
            'store_name' => 'HERBAL SHOP',
            'store_description' => 'PRODUCT SALES',
            'added_date' =>  now(),
            'user_id' => 'e4c06f74-4b4e-4669-9137-1729876865a3'
        ]);

        User::factory()->create([
            'user_id' => Str::uuid(),
            'firstname' => 'Mohammed',
            'othername' => 'Alhassan',
            'role_id' => '1001',
            'usage' => '0',
            'email' => 'bigboss200535@gmail.com',
            'email_verified_at' => now(),
            'password' =>  Hash::make('password'),
            'telephone' => '233245340461',
            'telephone_verify' => 'Yes',
            'user_no' => 'f4c06f74-4b4e-4669-9137-1729876865a3',
            'added_date' => now(),
        ]);

         User::factory()->create([
            'user_id' => Str::uuid(),
            'firstname' => 'Joshua',
            'othername' => 'Awunasu',
            'role_id' => '1002',
            'usage' => '1',
            'email' => 'joshua@gmail.com',
            'email_verified_at' => now(),
            'password' =>  Hash::make('password'),
            'telephone' => '',
            'telephone_verify' => 'Yes',
            'user_no' => '',
            'added_date' => now(),
        ]);

        User::factory(100)->create();
        Supplier::factory(5)->create();
        ProductCategory::factory(10)->create();
        Product::factory(3300)->create();
    }
}

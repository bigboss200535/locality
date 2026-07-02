<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Tenant;
use App\Models\Stores;
use App\Models\User;
use App\Models\Supplier;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // $role = Role::inRandomOrder()->where('usage','=','1')->first();
        $tenant = Tenant::inRandomOrder()->where('archived', '=', 'No')->first();
        $store = Stores::inRandomOrder()->where('archived', '=', 'No')->first();
        $supplier = Supplier::inRandomOrder()->where('archived', '=', 'No')->first();
        $category = ProductCategory::inRandomOrder()->where('archived', '=', 'No')->first();
        $user = User::inRandomOrder()->where('archived', '=', 'No')->first();

        return [
            'product_id' => Str::uuid(),
            'product_name' => fake()->name(),
            'product_type' => fake()->word(),
            'barcode' => fake()->ean13(),
            'qr_code' => fake()->ean13(),
            'supplier_id' =>  $supplier->supplier_id,
            'category_id' => $category->category_id,
            'tenant_id' => $tenant->tenant_id,
            'user_id' => $user->user_id,
            'store_id' => $store->store_id,
            'added_date' => now(),
            'added_by' => 'MOHAMMED ALHASSAN',
           
        ];
    }
}

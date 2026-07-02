<?php

namespace Database\Factories;

use App\Models\ProductCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Tenant;
use App\Models\Stores;
use App\Models\User;
use Illuminate\Support\Str;

/**
 * @extends Factory<ProductCategory>
 */
class ProductCategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    // protected $model = ProductCategory::class;

    public function definition(): array
    {
        $user = User::inRandomOrder()->where('archived','No')->first();
        $tenant = Tenant::inRandomOrder()->where('archived', 'No')->first();
        $store = Stores::inRandomOrder()->where('archived', 'No')->first();

        return [
            'category_id' => Str::uuid(),
            'category_name' => fake()->name(),
            // 'product_type' => fake()->name(),
            'tenant_id' => $tenant->tenant_id,
            'user_id' => $user->user_id,
            'store_id' => $store->store_id,
            'added_date' => now(),
            'added_by' => 'MOHAMMED ALHASSAN',
        ];
    }
}

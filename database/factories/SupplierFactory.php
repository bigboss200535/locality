<?php

namespace Database\Factories;

use App\Models\Supplier;
use App\Models\Tenant;
use App\Models\Stores;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Supplier>
 */
class SupplierFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $tenant = Tenant::inRandomOrder()->where('archived', '=', 'No')->first();
        $store = Stores::inRandomOrder()->where('archived', '=', 'No')->first();
        $user = User::inRandomOrder()->where('archived', '=', 'No')->first();

        return [
            'supplier_id' => Str::uuid(),
            'supplier_name' => fake()->word(),
            'telephone' => fake()->phoneNumber(),
            'email' => fake()->email(),
            'tenant_id' => $tenant->tenant_id,
            'user_id' => $user->user_id,
            'store_id' => $store->store_id,
            'added_date' => now(),
            'added_by' => 'MOHAMMED ALHASSAN',
        ];
    }
}

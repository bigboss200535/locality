<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Role;
use App\Models\Tenant;
use App\Models\Stores;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $role = Role::inRandomOrder()->where('usage','=','1')->first();
        $tenant = Tenant::inRandomOrder()->where('archived', '=', 'No')->first();
        $store = Stores::inRandomOrder()->where('archived', '=', 'No')->first();

        return [
            'user_id' => 'e4c06f74-4b4e-4669-9137-1729876865a3',
            'firstname' => fake()->name(),
            'othername' => fake()->name(),
            'telephone' => fake()->unique()->phoneNumber(),
            'telephone_verify' => fake()->randomElement(['Yes', 'No']),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'role_id' => $role->role_id,
            'tenant_id' => $tenant->tenant_id,
            'user_no' => 'e4c06f74-4b4e-4669-9137-1729876865a3',
            'store_id' => $store->store_id,
            'password' => static::$password ??= Hash::make('password'),
           
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}

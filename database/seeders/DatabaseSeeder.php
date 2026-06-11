<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Tenant;
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
        User::factory(10)->create();
        Tenant::factory(10)->create();

        User::factory()->create([
            'user_id' => Str::uuid(),
            'firstname' => 'Mohammed',
            'othername' => 'Alhassan',
            'email' => 'bigboss200535@gmail.com',
            'email_verified_at' => now(),
            'password' =>  Hash::make('password'),
            'telephone' => '233245340461',
            'telephone_verify' => 'Yes',
        ]);
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        if (! User::whereEmail('admin@mail.com')->exists()) {
            User::factory()->create([
                'name' => 'Admin User',
                'email' => 'admin@mail.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'remember_token' => Str::random(10),
            ]);
        }

        User::factory(10)->create();
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name'     => 'Demo User',
            'email'    => 'demo@demo.com',
            'password' => Hash::make('password'),
        ]);

        $this->call(ProductSeeder::class);
    }
}
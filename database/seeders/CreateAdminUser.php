<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class CreateAdminUser extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
			'name' => 'Administrator',
			'email' => 'admin@admin.com',
			'email_verified_at' => now(),
			'password' => Hash::make('demo123'), // password
			'remember_token' => Str::random(10),
		]);
    }
}

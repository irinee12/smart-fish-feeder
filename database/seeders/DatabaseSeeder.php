<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'penjaga1@gmail.com'],
            [
                'name'     => 'Penjaga 1',
                'password' => 'password123',
                'role'     => 'penjaga1',
            ]
        );

        User::updateOrCreate(
            ['email' => 'penjaga2@gmail.com'],
            [
                'name'     => 'Penjaga 2',
                'password' => 'password123',
                'role'     => 'penjaga2',
            ]
        );
    }
}
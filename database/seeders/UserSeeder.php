<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@farmacia.com',
            'password' => Hash::make('password123'),
            'role_id' => 1, // admin
        ]);

        // Gerentes
        User::create([
            'name' => 'João Silva',
            'email' => 'joao@farmacia.com',
            'password' => Hash::make('password123'),
            'role_id' => 2, // gerente
        ]);

        User::create([
            'name' => 'Maria Santos',
            'email' => 'maria@farmacia.com',
            'password' => Hash::make('password123'),
            'role_id' => 2, // gerente
        ]);

        // Usuários comuns
        User::factory(5)->create(['role_id' => 3]); // usuario
    }
}

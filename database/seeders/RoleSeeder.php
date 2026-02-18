<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::create([
            'nome' => 'admin',
            'descricao' => 'Administrador do sistema com acesso total'
        ]);

        Role::create([
            'nome' => 'gerente',
            'descricao' => 'Gerente de farmácia'
        ]);

        Role::create([
            'nome' => 'usuario',
            'descricao' => 'Usuário comum'
        ]);
    }
}

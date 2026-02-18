<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Categoria;
use App\Models\Farmacia;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            CategoriaSeeder::class,
            ProdutoSeeder::class,
            FarmaciaSeeder::class,
            EstoqueSeeder::class,
            ServicoSeeder::class,
        ]);
    }
}


class FarmaciaSeeder extends Seeder
{
    public function run(): void
    {
        Farmacia::create([
            'nome' => 'Farmácia Central de Malanje',
            'localizacao' => 'Bairro Catepa, Malanje',
            'user_id' => 1
        ]);

        Farmacia::create([
            'nome' => 'Farmácia Saúde & Vida',
            'localizacao' => 'Próximo ao Hospital Provincial',
            'user_id' => 2
        ]);
   }
}

class CategoriaSeeder extends Seeder
{
    public function run(): void
    {
        $categorias = [
            'Analgésicos',
            'Antibióticos',
            'Antipiréticos',
            'Vitaminas',
            'Anti-inflamatórios',
            'Material Hospitalar'
        ];

        foreach ($categorias as $nome) {
            Categoria::create(['nome' => $nome]);
        }
    }
}
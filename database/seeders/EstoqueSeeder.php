<?php

namespace Database\Seeders;

use App\Models\Estoque;
use Illuminate\Database\Seeder;

class EstoqueSeeder extends Seeder
{
    public function run(): void
    {
        // Farmácia Central tem estoques dos produtos 1, 2, 3
        $estoques = [
            ['farmacia_id' => 1, 'produto_id' => 1, 'quantidade' => 100, 'stock_minimo' => 10],
            ['farmacia_id' => 1, 'produto_id' => 2, 'quantidade' => 50, 'stock_minimo' => 15],
            ['farmacia_id' => 1, 'produto_id' => 3, 'quantidade' => 75, 'stock_minimo' => 20],
            ['farmacia_id' => 1, 'produto_id' => 4, 'quantidade' => 8, 'stock_minimo' => 5], // em falta!
            ['farmacia_id' => 1, 'produto_id' => 5, 'quantidade' => 200, 'stock_minimo' => 50],
            
            // Farmácia Norte tem estoques dos produtos 1, 2, 4, 5
            ['farmacia_id' => 2, 'produto_id' => 1, 'quantidade' => 80, 'stock_minimo' => 10],
            ['farmacia_id' => 2, 'produto_id' => 2, 'quantidade' => 3, 'stock_minimo' => 15], // em falta!
            ['farmacia_id' => 2, 'produto_id' => 4, 'quantidade' => 120, 'stock_minimo' => 5],
            ['farmacia_id' => 2, 'produto_id' => 5, 'quantidade' => 150, 'stock_minimo' => 50],
        ];

        foreach ($estoques as $estoque) {
            Estoque::create($estoque);
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\Produto;
use Illuminate\Database\Seeder;

class ProdutoSeeder extends Seeder
{
    public function run(): void
    {
        $produtos = [
            [
                'nome' => 'Dipirona 500mg',
                'codigo' => 'DIP-500-001',
                'preco' => 12.50,
                'categoria_id' => 1,
                'fabricante' => 'Laboratório X',
                'descricao' => 'Analgésico e antitérmico'
            ],
            [
                'nome' => 'Amoxicilina 500mg',
                'codigo' => 'AMX-500-001',
                'preco' => 25.00,
                'categoria_id' => 2,
                'fabricante' => 'Laboratório Y',
                'descricao' => 'Antibiótico para infecções'
            ],
            [
                'nome' => 'Ibuprofeno 200mg',
                'codigo' => 'IBU-200-001',
                'preco' => 18.00,
                'categoria_id' => 3,
                'fabricante' => 'Laboratório Z',
                'descricao' => 'Antinflamatório e analgésico'
            ],
            [
                'nome' => 'Vitamina C 1000mg',
                'codigo' => 'VIT-C-1000',
                'preco' => 15.50,
                'categoria_id' => 4,
                'fabricante' => 'Laboratório W',
                'descricao' => 'Suplemento de vitamina C'
            ],
            [
                'nome' => 'Álcool Gel 70%',
                'codigo' => 'ALC-GEL-70',
                'preco' => 8.00,
                'categoria_id' => 5,
                'fabricante' => 'Laboratório V',
                'descricao' => 'Higienizador de mãos'
            ],
        ];

        foreach ($produtos as $produto) {
            Produto::create($produto);
        }
    }
}

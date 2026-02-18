<?php

namespace Database\Seeders;

use App\Models\Categoria;
use Illuminate\Database\Seeder;

class CategoriaSeeder extends Seeder
{
    public function run(): void
    {
        $categorias = [
            [
                'nome' => 'Analgésicos',
                'descricao' => 'Medicamentos para alívio de dor'
            ],
            [
                'nome' => 'Antibióticos',
                'descricao' => 'Medicamentos para infecções'
            ],
            [
                'nome' => 'Antinflamatórios',
                'descricao' => 'Medicamentos para inflamação'
            ],
            [
                'nome' => 'Vitaminas',
                'descricao' => 'Suplementos vitamínicos'
            ],
            [
                'nome' => 'Higiene Pessoal',
                'descricao' => 'Produtos de higiene e limpeza'
            ],
            [
                'nome' => 'Dermocosméticos',
                'descricao' => 'Produtos para cuidados da pele'
            ],
        ];

        foreach ($categorias as $categoria) {
            Categoria::create($categoria);
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\Servico;
use Illuminate\Database\Seeder;

class ServicoSeeder extends Seeder
{
    public function run(): void
    {
        $servicos = [
            // Serviços da Farmácia Central
            [
                'farmacia_id' => 1,
                'nome' => 'Teste de Pressão',
                'descricao' => 'Aferição de pressão arterial',
                'preco' => 10.00,
                'ativo' => true
            ],
            [
                'farmacia_id' => 1,
                'nome' => 'Teste de Glicose',
                'descricao' => 'Medição de glicose no sangue',
                'preco' => 15.00,
                'ativo' => true
            ],
            [
                'farmacia_id' => 1,
                'nome' => 'Aplicação de Injeção',
                'descricao' => 'Aplicação intramuscular ou endovenosa',
                'preco' => 25.00,
                'ativo' => true
            ],
            
            // Serviços da Farmácia Norte
            [
                'farmacia_id' => 2,
                'nome' => 'Teste de Pressão',
                'descricao' => 'Aferição de pressão arterial',
                'preco' => 10.00,
                'ativo' => true
            ],
            [
                'farmacia_id' => 2,
                'nome' => 'Consultoria Farmacêutica',
                'descricao' => 'Orientação sobre uso de medicamentos',
                'preco' => 30.00,
                'ativo' => true
            ],
        ];

        foreach ($servicos as $servico) {
            Servico::create($servico);
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\Farmacia;
use App\Models\Localizacao;
use Illuminate\Database\Seeder;

class FarmaciaSeeder extends Seeder
{
    public function run(): void
    {
        $farmacias = [
            [
                'nome' => 'Farmácia Central',
                'localizacao' => 'Rua Principal, 123',
                'descricao' => 'Farmácia central da rede',
                'telefone' => '11 3000-0000',
                'email' => 'central@farmacia.com',
                'user_id' => 2, // João Silva
                'localizacao_data' => [
                    'endereco' => 'Rua Principal, 123',
                    'latitude' => -23.5505,
                    'longitude' => -46.6333,
                    'cep' => '01310-100',
                    'cidade' => 'São Paulo',
                    'estado' => 'SP'
                ]
            ],
            [
                'nome' => 'Farmácia Norte',
                'localizacao' => 'Avenida Paulista, 1000',
                'descricao' => 'Filial na Zona Norte',
                'telefone' => '11 3001-0000',
                'email' => 'norte@farmacia.com',
                'user_id' => 3, // Maria Santos
                'localizacao_data' => [
                    'endereco' => 'Avenida Paulista, 1000',
                    'latitude' => -23.5614,
                    'longitude' => -46.6560,
                    'cep' => '01311-100',
                    'cidade' => 'São Paulo',
                    'estado' => 'SP'
                ]
            ],
        ];

        foreach ($farmacias as $farmaciaData) {
            $localizacaoData = $farmaciaData['localizacao_data'];
            unset($farmaciaData['localizacao_data']);

            $farmacia = Farmacia::create($farmaciaData);

            Localizacao::create([
                'farmacia_id' => $farmacia->id,
                ...$localizacaoData
            ]);
        }
    }
}

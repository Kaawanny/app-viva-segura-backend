<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LocalSeguroSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tb_local_seguro')->insert([
            // Delegacias da Mulher
            [
                'nome'      => '5ª Delegacia de Defesa da Mulher - Leste',
                'tipo'      => 'delegacia',
                'endereco'  => 'Rua Dr. Coryntho Baldoíno Costa, 400 - Tatuapé, São Paulo - SP',
                'latitude'  => -23.5416,
                'longitude' => -46.5732,
                'ativo'     => true,
            ],
            [
                'nome'      => '7ª Delegacia de Defesa da Mulher - Itaquera',
                'tipo'      => 'delegacia',
                'endereco'  => 'Rua Sabbado D Ângelo, 46 - Itaquera, São Paulo - SP',
                'latitude'  => -23.5435,
                'longitude' => -46.4623,
                'ativo'     => true,
            ],
            [
                'nome'      => '8ª Delegacia de Defesa da Mulher',
                'tipo'      => 'delegacia',
                'endereco'  => 'Avenida Osvaldo Valle Cordeiro, 190 - Jardim Marília, São Paulo - SP',
                'latitude'  => -23.5419,
                'longitude' => -46.4552,
                'ativo'     => true,
            ],

            // Centros de apoio
            [
                'nome'      => 'Casa da Mulher São Miguel Paulista',
                'tipo'      => 'apoio',
                'endereco'  => 'Rua Pedro Soares de Andrade, 34 - Vila Rosaria, São Paulo - SP',
                'latitude'  => -23.4957,
                'longitude' => -46.4375,
                'ativo'     => true,
            ],
            [
                'nome'      => 'Centro de Referência e Cidadania da Mulher - Parada XV',
                'tipo'      => 'apoio',
                'endereco'  => 'Rua Ibiajara, 495 - Parada XV de Novembro, São Paulo - SP',
                'latitude'  => -23.5339,
                'longitude' => -46.4555,
                'ativo'     => true,
            ],

            // Estações 
            [
                'nome'      => 'Estação Corinthians-Itaquera',
                'tipo'      => 'estacao',
                'endereco'  => 'Av. José Pinheiro Borges – Itaquera, São Paulo - SP',
                'latitude'  => -23.5423,
                'longitude' => -46.4712,
                'ativo'     => true,
            ],
            [
                'nome'      => 'Estação Tatuapé',
                'tipo'      => 'estacao',
                'endereco'  => 'Rua Melo Freire – Tatuapé, São Paulo - SP',
                'latitude'  => -23.5401,
                'longitude' => -46.5760,
                'ativo'     => true,
            ],
            [
                'nome'      => 'Estação Guaianases',
                'tipo'      => 'estacao',
                'endereco'  => 'Praça Presidente Getúlio Vargas – Guaianases, São Paulo - SP',
                'latitude'  => -23.5428,
                'longitude' => -46.4156,
                'ativo'     => true,
            ],
            [
                'nome'      => 'Estação Brás',
                'tipo'      => 'estacao',
                'endereco'  => 'Praça Agente Cícero – Brás, São Paulo - SP',
                'latitude'  => -23.5445,
                'longitude' => -46.6163,
                'ativo'     => true,
            ],

            // Saúde
            [
                'nome'      => 'Estação USP Leste',
                'tipo'      => 'estacao',
                'endereco'  => 'Av. Doutor Assis Ribeiro – Ermelino Matarazzo, São Paulo - SP',
                'latitude'  => -23.4974,
                'longitude' => -46.4847,
                'ativo'     => true,
            ],
            [
                'nome'      => 'UPA Zona Leste I – Penha',
                'tipo'      => 'saude',
                'endereco'  => 'R. Cel. Azevedo Marques, 1 – Penha, SP',
                'latitude'  => -23.5197,
                'longitude' => -46.5267,
                'ativo'     => true,
            ],
            [
                'nome'      => 'Hospital Tide Setubal',
                'tipo'      => 'saude',
                'endereco'  => 'R. São Bernardo do Campo, 92 – Itaquera, SP',
                'latitude'  => -23.5403,
                'longitude' => -46.4558,
                'ativo'     => true,
            ],

        ]);

        $this->command->info('✅ Locais seguros inseridos com sucesso!');
    }
}
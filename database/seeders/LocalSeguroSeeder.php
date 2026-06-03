<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LocalSeguroSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('tb_local_seguro')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::table('tb_local_seguro')->insert([
            // Delegacias da Mulher e Distritos
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
            [
                'nome'      => '52º Distrito Policial - Parque São Jorge',
                'tipo'      => 'policia',
                'endereco'  => 'Rua Sabbado D’Angelo, 46 - Itaquera, São Paulo - SP',
                'latitude'  => -23.5429,
                'longitude' => -46.4581,
                'ativo'     => true,
            ],
            [
                'nome'      => '38º Distrito Policial - Vila Matilde',
                'tipo'      => 'policia',
                'endereco'  => 'Rua Siqueira Bueno, 2090 - Vila Matilde, São Paulo - SP',
                'latitude'  => -23.5254,
                'longitude' => -46.5381,
                'ativo'     => true,
            ],

            // Centros de apoio
            [
                'nome'      => 'Centro de Referência e Cidadania da Mulher - Parada XV',
                'tipo'      => 'apoio',
                'endereco'  => 'Rua Ibiajara, 495 - Parada XV de Novembro, São Paulo - SP',
                'latitude'  => -23.5339,
                'longitude' => -46.4555,
                'ativo'     => true,
            ],
            [
                'nome'      => 'Casa da Mulher São Miguel Paulista',
                'tipo'      => 'apoio',
                'endereco'  => 'R. Pedro Soares de Andrade, 34 - Vila Rosaria, São Paulo - SP',
                'latitude'  => -23.4953,
                'longitude' => -46.4366,
                'ativo'     => true,
            ],
            [
                'nome'      => 'CRAS Itaim Paulista',
                'tipo'      => 'apoio',
                'endereco'  => 'R. Valente de Novais, 189 - Itaim Paulista, São Paulo - SP',
                'latitude'  => -23.5009,
                'longitude' => -46.3959,
                'ativo'     => true,
            ],
            [
                'nome'      => 'Centro de Referência da Mulher',
                'tipo'      => 'apoio',
                'endereco'  => 'R. Vieira Ravasco, 26 - Cambuci, São Paulo - SP',
                'latitude'  => -23.5572,
                'longitude' => -46.6231,
                'ativo'     => true,
            ],

            // Estações e Terminais
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
                'nome'      => 'Estação José Bonifácio',
                'tipo'      => 'estacao',
                'endereco'  => 'Av. Nagib Farah Maluf, 1500 - COHAB José Bonifácio, São Paulo - SP',
                'latitude'  => -23.5393,
                'longitude' => -46.4318,
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
            [
                'nome'      => 'Estação Penha',
                'tipo'      => 'estacao',
                'endereco'  => 'Av. Radial Leste - Penha, São Paulo - SP',
                'latitude'  => -23.5331,
                'longitude' => -46.5423,
                'ativo'     => true,
            ],
            [
                'nome'      => 'Estação Vila Matilde',
                'tipo'      => 'estacao',
                'endereco'  => 'Av. Radial Leste - Vila Matilde, São Paulo - SP',
                'latitude'  => -23.5318,
                'longitude' => -46.5307,
                'ativo'     => true,
            ],
            [
                'nome'      => 'Estação Artur Alvim',
                'tipo'      => 'estacao',
                'endereco'  => 'Av. Radial Leste - Artur Alvim, São Paulo - SP',
                'latitude'  => -23.5400,
                'longitude' => -46.4842,
                'ativo'     => true,
            ],
            [
                'nome'      => 'Terminal Cidade Tiradentes',
                'tipo'      => 'terminal',
                'endereco'  => 'Rua Sara Kubitscheck, 165 - Cidade Tiradentes, São Paulo - SP',
                'latitude'  => -23.5991,
                'longitude' => -46.3984,
                'ativo'     => true,
            ],
            [
                'nome'      => 'Terminal São Mateus',
                'tipo'      => 'terminal',
                'endereco'  => 'Av. Adélia Chohfi, 100 - São Mateus, São Paulo - SP',
                'latitude'  => -23.6040,
                'longitude' => -46.4802,
                'ativo'     => true,
            ],
        ]);

        $this->command->info('✅ Locais seguros inseridos com sucesso!');
    }
}
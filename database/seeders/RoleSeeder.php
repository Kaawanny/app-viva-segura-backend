<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tbRole')->insert([
            ['nomeRole' => 'Usuaria', 'created_at' => '2026-04-17', 'updated_at' => '2026-04-17'],
            ['nomeRole' => 'Guardião', 'created_at' => '2026-04-17', 'updated_at' => '2026-04-17'],
            ['nomeRole' => 'Admin', 'created_at' => '2026-04-17', 'updated_at' => '2026-04-17']
        ]);
    }
}

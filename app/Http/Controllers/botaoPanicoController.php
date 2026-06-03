<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class botaoPanicoController extends Controller
{
public function store(Request $request)
{
    try {
        DB::table('tbalerta')->insert([
            'statusAlerta' => 'ativo',
            'descricao' => 'teste',
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'dataHoraAlerta' => now(),
            'id_tipoAlerta' => 1,
            'id_usuaria' => $request->id_usuaria
        ]);

        return response()->json([
            'success' => true,
            'message' => 'SOS enviado com sucesso'
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}

    public function verificar()
    {
        $alertas = DB::table('tbalerta')
            ->where('statusAlerta', 'ativo')
            ->orderBy('dataHoraAlerta', 'desc')
            ->get();

        return response()->json($alertas);
    }

    public function ativos()
{
    $alertas = DB::table('tbalerta')
        ->join(
            'tbusuaria',
            'tbalerta.id_usuaria',
            '=',
            'tbusuaria.id_usuaria'
        )
        ->where('tbalerta.statusAlerta', 'ativo')
        ->orderBy('tbalerta.dataHoraAlerta', 'desc')
        ->select(
            'tbalerta.*',
            'tbusuaria.nome',
            'tbusuaria.foto',
            'tbusuaria.email'
        )
        ->get();

    return response()->json($alertas);
}
}
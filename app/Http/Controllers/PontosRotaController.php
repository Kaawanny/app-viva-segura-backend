<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RotaModel;
use App\Models\pontosRotaModel;

class PontosRotaController extends Controller
{
    
    public function historico(int $idUsuaria)
    {
        $rotas = RotaModel::where('id_usuaria', $idUsuaria)
            ->orderByDesc('dataCriacao')
            ->get([
                'id_rota',
                'origemLatitude',
                'origemLongitude',
                'destinoLatitude',
                'destinoLongitude',
                'tempoPrevisto',
                'dataCriacao',
            ]);

        return response()->json($rotas);
    }

    public function pontosDaRota(int $idRota)
    {
        $rota = RotaModel::find($idRota);

        if (!$rota) {
            return response()->json(['error' => 'Rota não encontrada.'], 404);
        }

        $pontos = pontosRotaModel::where('id_rota', $idRota)
            ->get(['latitude', 'longitude'])
            ->map(fn($p) => [
                'latitude'  => (float) $p->latitude,
                'longitude' => (float) $p->longitude,
            ]);

        return response()->json([
            'id_rota'       => $rota->id_rota,
            'tempoPrevisto' => $rota->tempoPrevisto,
            'dataCriacao'   => $rota->dataCriacao,
            'coordenadas'   => $pontos,
        ]);
    }
}
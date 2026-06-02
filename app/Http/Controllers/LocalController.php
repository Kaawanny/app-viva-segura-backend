<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\localModel;

class LocalController extends Controller
{
    public function atualizarLocalizacao(Request $request)
    {
        $local = localModel::updateOrCreate(
            ['id_usuaria' => $request->id_usuaria],
            [
                'latitude' => $request->latitude,
                'longitude' => $request->longitude
            ]
        );

        return response()->json([
            'success' => true,
            'data' => $local
        ]);
    }

    public function buscarLocalizacao($id)
    {
        $local = localModel::where('id_usuaria', $id)->first();

        if (!$local) {
            return response()->json([
                'success' => false,
                'message' => 'Localização não encontrada'
            ], 404);
        }

        return response()->json($local);
    }
}
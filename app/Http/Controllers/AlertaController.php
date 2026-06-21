<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\alertaModel;

class AlertaController extends Controller {
    public function index(Request $request) {
        $lat  = $request->query('latitude');
        $lng  = $request->query('longitude');
        $raio = $request->query('raio', 5); 
        if (!$lat || !$lng) {
            return response()->json(['error' => 'Latitude e longitude são obrigatórios.'], 422);
        }

        $alertas = DB::table('tbalerta')
            ->select(
                'id_alerta as id',
                'desc',
                'latitude',
                'longitude',
                'statusAlerta',
                'dataHoraAlerta',
                'id_tipoAlerta',
                DB::raw("
                    (6371 * ACOS(
                        COS(RADIANS(?)) * COS(RADIANS(latitude)) *
                        COS(RADIANS(longitude) - RADIANS(?)) +
                        SIN(RADIANS(?)) * SIN(RADIANS(latitude))
                    )) AS distancia_km
                ")
            )
            ->addBinding([$lat, $lng, $lat], 'select')
            ->where('statusAlerta', 1) 
            ->having('distancia_km', '<=', $raio)
            ->orderBy('distancia_km')
            ->get();

        $tiposLabel = [
            1 => 'Assédio',
            2 => 'Violência',
            3 => 'Perseguição',
            4 => 'Área de risco',
        ];

        $alertas = $alertas->map(function ($alerta) use ($tiposLabel) {
            $alerta->tipo_alerta = $tiposLabel[$alerta->id_tipoAlerta] ?? 'Alerta de segurança';
            return $alerta;
        });

        return response()->json($alertas);
    }

    /**  Cria um novo alerta acionado pela usuária. */
    public function store(Request $request) {
        $request->validate([
            'id_usuaria'    => 'required|integer',
            'id_tipoAlerta' => 'required|integer',
            'desc' => $request->desc ?? '',
            'latitude'      => 'required|numeric',
            'longitude'     => 'required|numeric',
        ]);

        $alerta = alertaModel::create([
            'id_usuaria'    => $request->id_usuaria,
            'id_tipoAlerta' => $request->id_tipoAlerta,
            'desc' => $request->desc ?? '',
            'latitude'      => $request->latitude,
            'longitude'     => $request->longitude,
            'statusAlerta'  => 1,
            'dataHoraAlerta' => now(),
        ]);

        return response()->json([
            'id' => $alerta->id_alerta,
            'message' => 'Alerta registrado com sucesso.',
        ], 201);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RotaModel;
use App\Models\pontosRotaModel;
use Illuminate\Support\Facades\Http;

class RotaController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'id_usuaria'        => 'nullable|integer',
            'origemLatitude'    => 'required|numeric',
            'origemLongitude'   => 'required|numeric',
            'destinoLatitude'   => 'required|numeric',
            'destinoLongitude'  => 'required|numeric',
        ]);

        $origemLat  = (float) $request->origemLatitude;
        $origemLng  = (float) $request->origemLongitude;
        $destinoLat = (float) $request->destinoLatitude;
        $destinoLng = (float) $request->destinoLongitude;

        $coordenadas = $this->rotaOSRM($origemLat, $origemLng, $destinoLat, $destinoLng);
        $fonte = 'osrm';

        if (!$coordenadas) {
            $ghKey = config('services.graphhopper.key');
            if ($ghKey) {
                $coordenadas = $this->rotaGraphHopper($origemLat, $origemLng, $destinoLat, $destinoLng, $ghKey);
                $fonte = 'graphhopper';
            }
        }
 
        if (!$coordenadas) {
            $coordenadas = [
                ['latitude' => $origemLat,  'longitude' => $origemLng],
                ['latitude' => $destinoLat, 'longitude' => $destinoLng],
            ];
            $fonte = 'linha_reta';
        }

        if ($request->id_usuaria) {
            $this->salvarRotaComPontos($request, $coordenadas);
        }

        return response()->json(['coordenadas' => $coordenadas, 'fonte' => $fonte]);
    }

    private function salvarRotaComPontos(Request $request, array $coordenadas): void
    {
        $rota = RotaModel::create([
            'id_usuaria'        => $request->id_usuaria,
            'origemLatitude'    => $request->origemLatitude,
            'origemLongitude'   => $request->origemLongitude,
            'destinoLatitude'   => $request->destinoLatitude,
            'destinoLongitude'  => $request->destinoLongitude,
            'tempoPrevisto'     => $this->calcularTempoPrevisto(count($coordenadas)),
            'dataCriacao'       => now(),
        ]);

        $pontos = array_map(fn($c) => [
            'id_rota'   => $rota->id_rota,
            'latitude'  => $c['latitude'],
            'longitude' => $c['longitude'],
        ], $coordenadas);

        pontosRotaModel::insert($pontos);
    }

    private function calcularTempoPrevisto(int $qtdPontos): int
    {
        $distanciaEstimadaM = $qtdPontos * 25; 
        $velocidadeMS = 1.4;            
        return (int) ceil(($distanciaEstimadaM / $velocidadeMS) / 60);
    }

    private function rotaOSRM(float $origemLat, float $origemLng, float $destinoLat, float $destinoLng): ?array
    {
        try {
            $url = "https://router.project-osrm.org/route/v1/foot"
                 . "/{$origemLng},{$origemLat};{$destinoLng},{$destinoLat}"
                 . "?overview=full&geometries=geojson&steps=false";

            $response = Http::timeout(8)->get($url);
            if (!$response->successful()) return null;

            $data = $response->json();
            if (empty($data['routes'][0]['geometry']['coordinates'])) return null;

            return array_map(fn($c) => [
                'latitude'  => $c[1],
                'longitude' => $c[0],
            ], $data['routes'][0]['geometry']['coordinates']);

        } catch (\Exception $e) {
            return null;
        }
    }

    private function rotaGraphHopper(float $origemLat, float $origemLng, float $destinoLat, float $destinoLng, string $key): ?array
    {
        try {
            $response = Http::timeout(8)->get('https://graphhopper.com/api/1/route', [
                'point'             => ["{$origemLat},{$origemLng}", "{$destinoLat},{$destinoLng}"],
                'profile'           => 'foot',
                'points_encoded'    => 'false',
                'locale'            => 'pt_BR',
                'key'               => $key,
            ]);

            if (!$response->successful()) return null;

            $data = $response->json();

            if (empty($data['paths'][0]['points']['coordinates'])) return null;

            return array_map(fn($c) => [
                'latitude'  => $c[1],
                'longitude' => $c[0],
            ], $data['paths'][0]['points']['coordinates']);

        } catch (\Exception $e) {
            return null;
        }
    }
}

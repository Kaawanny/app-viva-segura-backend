<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\rotaCompartilhadaModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class RotaCompartilhadaController extends Controller
{
    // POST /rota-compartilhada
    // Usuária compartilha rota com guardião → notifica guardião
    public function compartilhar(Request $request)
    {
        $request->validate([
            'id_usuaria'       => 'required|integer',
            'id_guardiao'      => 'required|integer',
            'origemLatitude'   => 'required|numeric',
            'origemLongitude'  => 'required|numeric',
            'destinoLatitude'  => 'required|numeric',
            'destinoLongitude' => 'required|numeric',
            'endereco_destino' => 'nullable|string',
        ]);

        // Encerra qualquer compartilhamento ativo anterior com esse guardião
        rotaCompartilhadaModel::where('id_usuaria', $request->id_usuaria)
            ->where('id_guardiao', $request->id_guardiao)
            ->where('status', 'ativa')
            ->update(['status' => 'encerrada']);

        // Cria novo compartilhamento
        $rota = rotaCompartilhadaModel::create([
            'id_usuaria'       => $request->id_usuaria,
            'id_guardiao'      => $request->id_guardiao,
            'origemLatitude'   => $request->origemLatitude,
            'origemLongitude'  => $request->origemLongitude,
            'destinoLatitude'  => $request->destinoLatitude,
            'destinoLongitude' => $request->destinoLongitude,
            'endereco_destino' => $request->endereco_destino,
            'status'           => 'ativa',
        ]);

        // Busca nome da usuária e push_token do guardião
        $usuaria  = DB::table('tbusuaria')->where('id_usuaria', $request->id_usuaria)->first();
        $guardiao = DB::table('tbusuaria')->where('id_usuaria', $request->id_guardiao)->first();

        $nomeUsuaria = $usuaria?->nome ?? 'Sua protegida';

        if ($guardiao && !empty($guardiao->push_token)) {
            Http::withHeaders([
                'Accept'          => 'application/json',
                'Accept-Encoding' => 'gzip, deflate',
                'Content-Type'    => 'application/json',
            ])->post('https://exp.host/--/api/v2/push/send', [
                'to'    => $guardiao->push_token,
                'title' => '📍 Rota compartilhada — Viva Segura',
                'body'  => "{$nomeUsuaria} está indo para {$request->endereco_destino} e compartilhou a rota com você.",
                'sound' => 'default',
                'data'  => [
                    'tipo'        => 'rota_compartilhada',
                    'id_usuaria'  => $request->id_usuaria,
                    'id_rota'     => $rota->id,
                ],
            ]);
        }

        return response()->json([
            'success' => true,
            'id_rota' => $rota->id,
            'message' => 'Rota compartilhada com sucesso!',
        ]);
    }

    // POST /rota-compartilhada/chegou
    // Usuária chegou ao destino → notifica guardião
    public function chegouAoDestino(Request $request)
    {
        $request->validate([
            'id_usuaria'  => 'required|integer',
            'id_guardiao' => 'required|integer',
        ]);

        rotaCompartilhadaModel::where('id_usuaria', $request->id_usuaria)
            ->where('id_guardiao', $request->id_guardiao)
            ->where('status', 'ativa')
            ->update(['status' => 'chegou']);

        $usuaria  = DB::table('tbusuaria')->where('id_usuaria', $request->id_usuaria)->first();
        $guardiao = DB::table('tbusuaria')->where('id_usuaria', $request->id_guardiao)->first();

        $nomeUsuaria = $usuaria?->nome ?? 'Sua protegida';

        if ($guardiao && !empty($guardiao->push_token)) {
            Http::withHeaders([
                'Accept'          => 'application/json',
                'Accept-Encoding' => 'gzip, deflate',
                'Content-Type'    => 'application/json',
            ])->post('https://exp.host/--/api/v2/push/send', [
                'to'    => $guardiao->push_token,
                'title' => '✅ Chegou em segurança — Viva Segura',
                'body'  => "{$nomeUsuaria} chegou ao destino!",
                'sound' => 'default',
                'data'  => [
                    'tipo'       => 'chegou_destino',
                    'id_usuaria' => $request->id_usuaria,
                ],
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Guardião notificado da chegada!',
        ]);
    }

    // POST /rota-compartilhada/encerrar
    // Usuária escolhe encerrar compartilhamento após chegada
    public function encerrar(Request $request)
    {
        $request->validate([
            'id_usuaria'  => 'required|integer',
            'id_guardiao' => 'required|integer',
        ]);

        rotaCompartilhadaModel::where('id_usuaria', $request->id_usuaria)
            ->where('id_guardiao', $request->id_guardiao)
            ->whereIn('status', ['ativa', 'chegou'])
            ->update(['status' => 'encerrada']);

        return response()->json([
            'success' => true,
            'message' => 'Compartilhamento encerrado.',
        ]);
    }

    // GET /rota-compartilhada/ativa/{id_guardiao}
    // Guardião busca se tem rota ativa pra acompanhar
    public function rotaAtiva($id_guardiao)
    {
        $rota = rotaCompartilhadaModel::where('id_guardiao', $id_guardiao)
            ->where('status', 'ativa')
            ->latest()
            ->first();

        if (!$rota) {
            return response()->json(['success' => false, 'message' => 'Nenhuma rota ativa.'], 404);
        }
        return response()->json(['success' => true, 'rota' => $rota]);
    }
}
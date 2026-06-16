<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\rotaCompartilhadaModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class RotaCompartilhadaController extends Controller
{
    // POST /rota-compartilhada -> rota da usuária compartilhada com o guardião 
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
            ->whereIn('status', ['pendente', 'ativa'])
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
            'status'           => 'pendente',
        ]);

        // Busca nome da usuária e push_token do guardião
        $usuaria  = DB::table('tbusuaria')->where('id_usuaria', $request->id_usuaria)->first();
        $guardiao = DB::table('tbusuaria')->where('id_usuaria', $request->id_guardiao)->first();
        $nomeUsuaria = $usuaria?->nome ?? 'Sua protegida';

        if ($guardiao && !empty($guardiao->push_token)) {
            Http::withHeaders([
                'Accept' => 'application/json',
                'Accept-Encoding' => 'gzip, deflate',
                'Content-Type' => 'application/json',
            ])->post('https://exp.host/--/api/v2/push/send', [
                'to'    => $guardiao->push_token,
                'title' => '🛡️ Solicitação de rota — Viva Segura',
                'body'  => "{$nomeUsuaria} quer compartilhar a rota com você. Aceite para acompanhar!",
                'sound' => 'default',
                'data'  => [
                    'tipo' => 'solicitacao_rota',
                    'id_rota' => $rota->id,
                    'id_usuaria' => $request->id_usuaria,
                ],
            ]);
        }

        return response()->json([
            'success' => true,
            'id_rota' => $rota->id,
            'message' => 'Solicitação enviada ao guardião!',
        ]);
    }

    // POST /rota-compartilhada/aceitar
    public function aceitar(Request $request)
    {
        $request->validate([
            'id_rota'     => 'required|integer',
            'id_guardiao' => 'required|integer',
        ]);

        $rota = rotaCompartilhadaModel::where('id', $request->id_rota)
            ->where('id_guardiao', $request->id_guardiao)
            ->where('status', 'pendente')
            ->first();

        if (!$rota) {
            return response()->json(['success' => false, 'message' => 'Solicitação não encontrada.'], 404);
        }

        $rota->update(['status' => 'ativa']);
        $usuaria  = DB::table('tbusuaria')->where('id_usuaria', $rota->id_usuaria)->first();
        $guardiao = DB::table('tbusuaria')->where('id_usuaria', $request->id_guardiao)->first();
        $nomeGuardiao = $guardiao?->nome ?? 'Seu guardião';

        if ($usuaria && !empty($usuaria->push_token)) {
            Http::withHeaders([
                'Accept' => 'application/json',
                'Accept-Encoding' => 'gzip, deflate',
                'Content-Type' => 'application/json',
            ])->post('https://exp.host/--/api/v2/push/send', [
                'to' => $usuaria->push_token,
                'title' => '✅ Solicitação aceita — Viva Segura',
                'body' => "{$nomeGuardiao} aceitou acompanhar sua rota!",
                'sound' => 'default',
                'data' => [
                    'tipo' => 'rota_aceita',
                    'id_rota' => $rota->id,
                ],
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Rota aceita com sucesso!',
            'rota'    => $rota,
        ]);
    }

    // GET /rota-compartilhada/pendentes/{id_guardiao}
    public function solicitacoesPendentes($id_guardiao)
    {
        $solicitacoes = rotaCompartilhadaModel::where('id_guardiao', $id_guardiao)
            ->where('status', 'pendente')
            ->get()
            ->map(function ($rota) {
                $usuaria = DB::table('tbusuaria')
                    ->where('id_usuaria', $rota->id_usuaria)
                    ->select('id_usuaria', 'nome', 'foto')
                    ->first();

                return [
                    'id_rota' => $rota->id,
                    'endereco_destino' => $rota->endereco_destino,
                    'created_at' => $rota->created_at,
                    'usuaria' => $usuaria,
                ];
            });

        return response()->json($solicitacoes);
    }

    // POST /rota-compartilhada/chegou -> usuária chegou ao destino → notifica guardião
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
                    'tipo' => 'chegou_destino',
                    'id_usuaria' => $request->id_usuaria,
                ],
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Guardião notificado da chegada!',
        ]);
    }

    // POST /rota-compartilhada/encerrar -> usuária escolhe encerrar compartilhamento após chegada
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

    // GET /rota-compartilhada/ativa/{id_guardiao} -> guardião busca se tem rota ativa pra acompanhar
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

    // GET /guardiao/home/{id} -> Rota compartilhada na Home do guardião
    public function rotaAtivaHome($idGuardiao)
    {
        try {
            $rotaAtiva = DB::table('tb_rota_compartilhada')
                ->where('id_guardiao', $idGuardiao)
                ->where('status', 'ativa')
                ->latest()
                ->first();

            if (!$rotaAtiva) {
                return response()->json(['data' => []]);
            }

            $usuaria = DB::table('tbusuaria')
                ->where('id_usuaria', $rotaAtiva->id_usuaria)
                ->select('id_usuaria', 'nome', 'foto')
                ->first();

            return response()->json([
                'data' => [[
                    'id' => $rotaAtiva->id,
                    'usuaria' => $usuaria,
                ]]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao buscar dados da home',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    // GET /rota-compartilhada/dados/{id_usuaria}
    public function dadosRota($id_usuaria)
    {
        $rota = rotaCompartilhadaModel::where('id_usuaria', $id_usuaria)
            ->where('status', 'ativa')
            ->latest()
            ->first();

        if (!$rota) {
            return response()->json(['success' => false, 'message' => 'Nenhuma rota ativa.'], 404);
        }

        return response()->json(['success' => true, 'rota' => $rota]);
    }
}
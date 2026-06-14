<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GuardiaoConvidado;
use Illuminate\Support\Facades\Http; 
use Illuminate\Support\Facades\DB; 

class GuardiaoController extends Controller
{
    // LISTAR CONVITES
    public function listar($id_usuaria)
    {
        $guardioes = DB::table('guardioes_convidados')
            ->leftJoin('tbusuaria', 'tbusuaria.email', '=', 'guardioes_convidados.email')
            ->where('guardioes_convidados.id_usuaria', $id_usuaria)
            ->select(
                'guardioes_convidados.id',
                'guardioes_convidados.id_usuaria',
                'guardioes_convidados.nome',
                'guardioes_convidados.email',
                'tbusuaria.foto',
                'tbusuaria.id_usuaria as id_guardiao'
            )
            ->get();

        return response()->json($guardioes);
    }

    // SALVAR CONVITE
    public function adicionar(Request $request)
    {
        return GuardiaoConvidado::create([
            'id_usuaria' => $request->id_usuaria,
            'nome' => $request->nome,
            'email' => $request->email,
        ]);
    }

    // ACEITAR CONVITE E NOTIFICAR USUÁRIA
    public function aceitarConvite(Request $request) 
    {
        $request->validate([
            'id_usuaria' => 'required',
            'id_guardiao' => 'required'
        ]);

        // 1. Atualiza o status do convite para "aceito" usando o seu Model original GuardiaoConvidado
        GuardiaoConvidado::where('id_usuaria', $request->id_usuaria)
            ->where('id_guardiao', $request->id_guardiao) 
            ->update(['status' => 'aceito']);

       // 2. Busca os dados da usuária na tabela 'tbusuaria' para capturar o push_token
       $usuaria = DB::table('tbusuaria')->where('id_usuaria', $request->id_usuaria)->first();

        // 3. Se a usuária existir e tiver o token registrado no aparelho, envia o Push Notification
        if ($usuaria && !empty($usuaria->push_token)) {
            Http::withHeaders([
                'Accept' => 'application/json',
                'Accept-Encoding' => 'gzip, deflate',
                'Content-Type' => 'application/json',
            ])->post('https://exp.host/--/api/v2/push/send', [
                'to' => $usuaria->push_token, 
                'title' => '🛡️ Guardião Confirmado! - Viva Segura',
                'body' => 'Boa notícia! Seu convite foi aceito e seu guardião agora está ativo.',
                'sound' => 'default',
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Convite aceito com sucesso e usuária notificada!'
        ]);
    }
}
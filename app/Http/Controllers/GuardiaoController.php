<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GuardiaoConvidado;
use Illuminate\Support\Facades\Http; 
use Illuminate\Support\Facades\DB; 


use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

use App\Models\UsuariaModel;
use App\Models\VinculoModel;

class GuardiaoController extends Controller {
    // SALVAR CONVITE
    public function adicionar(Request $request) {
        DB::beginTransaction();
        try {
            $idUsuaria = $request->input('id_usuaria');
            // GUARDIÃO
            $guardiao = new UsuariaModel();
            $guardiao->nome    = $request->input('nome');
            $guardiao->email   = $request->input('email');
            $guardiao->senha   = Hash::make(Str::random(10));
            $guardiao->cpf     = null;
            $guardiao->telefone = null;
            $guardiao->dataNasc = null;
            $guardiao->foto    = null;
            $guardiao->id_role = 2;
            do {
                $codigoGuardiao = strtoupper(Str::random(8));
            } while (UsuariaModel::where('codigo_convite', $codigoGuardiao)->exists());
            $guardiao->codigo_convite = $codigoGuardiao;
            $guardiao->save();

            \Log::info('GUARDIAO:', $guardiao->toArray());
            if (!$guardiao->id_usuaria) {
                throw new \Exception('Falha ao salvar guardião no banco');
            }
            $idGuardiao = $guardiao->id_usuaria;

            VinculoModel::create([
                'id_usuaria'       => $idUsuaria,
                'id_guardiao'      => $idGuardiao,
                'dataSolicitacao'  => now(),
                'dataResposta'     => now(),
                'statusVinculo'    => 'pendente'
            ]);
            DB::commit();

            // Email para o guardião
            Mail::html("
                <div style='font-family: Arial, sans-serif'>
                    <h2>Viva Segura 💜</h2>
                    <p>Olá {$guardiao->nome}, você foi cadastrado como guardião.</p>
                    <p>Seu código de convite:</p>
                    <h1>{$guardiao->codigo_convite}</h1>
                </div>", 
            function ($message) use ($guardiao) {
                $message->to($guardiao->email)
                ->subject('Convite para ser Guardião');
            });
            return response()->json([
                'message' => 'Guardião cadastrado com sucesso!'
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Erro ao cadastrar guardião',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    //cadastro do guardiao e autalizaçao e info guardiao 
    public function cadastroGuardiao(Request $request) {
        try {
            $request->validate([
                'nome' => 'required|string|max:255',
                'email' => 'required|email',
                'codigo_convite' => 'required',
                'telefone' => 'nullable|string',
                'senha' => 'nullable|string|min:6',
                'foto' => 'nullable|string',
            ]);
            DB::beginTransaction();

            // Usuária 
            $usuaria = UsuariaModel::where('codigo_convite', strtoupper(trim($request->codigo_convite)))
                ->where('id_role', 1)
                ->first();
            if (!$usuaria) {
                DB::rollBack();
                return response()->json([
                    'error' => true,
                    'message' => 'Código de convite inválido.'
                ], 404);
            }

            // Guardião por email
            $guardiao = UsuariaModel::where('email', $request->email)->first();
            if (!$guardiao) {
                DB::rollBack();
                return response()->json([
                    'error' => true,
                    'message' => 'Guardião não encontrado.'
                ], 404);
            }

            DB::table('tbUsuaria')
                ->where('email', $request->email)
                ->update([
                    'nome' => $request->nome,
                    'telefone' => $request->telefone,
                    'id_role' => 2,
                    'foto' => $request->foto ?? $guardiao->foto,
                    'senha' => $request->senha ? Hash::make($request->senha) : $guardiao->senha,
                    'updated_at' => now()
                ]);

            // Vínculo
            $vinculo = VinculoModel::where('id_usuaria', $usuaria->id_usuaria)
                ->where('id_guardiao', $guardiao->id_usuaria)
                ->first();
            if ($vinculo) {
                $vinculo->update([
                    'statusVinculo' => 'aceito',
                    'dataResposta' => now()
                ]);
            } else {
                VinculoModel::create([
                    'id_usuaria' => $usuaria->id_usuaria,
                    'id_guardiao' => $guardiao->id_usuaria,
                    'dataSolicitacao' => now(),
                    'dataResposta' => now(),
                    'statusVinculo' => 'aceito'
                ]);
            }
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Guardião atualizado com sucesso.',
                'user' => [
                    'id_usuaria' => $guardiao->id_usuaria,
                    'nome' => $request->nome,
                    'email' => $guardiao->email,
                    'telefone' => $request->telefone,
                    'foto' => $request->foto ?? $guardiao->foto,
                    'id_role' => 2
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'error' => true,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function listar($id_usuaria) {
        $guardioes = DB::table('tbVinculo')
            ->join('tbUsuaria', 'tbVinculo.id_guardiao', '=', 'tbUsuaria.id_usuaria')
            ->where('tbVinculo.id_usuaria', $id_usuaria)
            ->where('tbVinculo.statusVinculo', 'aceito')
            ->select(
                'tbUsuaria.id_usuaria as id_guardiao',
                'tbUsuaria.nome',
                'tbUsuaria.email',
                'tbUsuaria.foto',
                'tbUsuaria.telefone',
                'tbVinculo.statusVinculo'
            )
            ->get();

        return response()->json($guardioes);
    }

    // ACEITAR CONVITE E NOTIFICAR USUÁRIA
    public function aceitarConvite(Request $request) {
        $request->validate([
            'id_usuaria' => 'required',
            'id_guardiao' => 'required'
        ]);

        // 1. Atualiza o status do convite para "aceito" usando o seu Model original GuardiaoConvidado
        UsuariaModel::where('id_usuaria', $request->id_usuaria)
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

    public function pendentes($id_usuaria) {
        $pendentes = DB::table('guardioes_convidados')
            ->where('guardioes_convidados.id_usuaria', $id_usuaria)
            ->whereNotExists(function($query) use ($id_usuaria) {
                $query->select(DB::raw(1))
                    ->from('tbVinculo')
                    ->where('tbVinculo.id_usuaria', $id_usuaria);
            })
            ->get();

        return response()->json($pendentes);
    }

    public function chat($idGuardiao) {
        $usuarios = DB::table('tbvinculo')
            ->join('tbusuaria', 'tbusuaria.id_usuaria', '=', 'tbvinculo.id_usuaria')
            ->where('tbvinculo.id_guardiao', $idGuardiao)
            ->where('tbvinculo.statusVinculo', 'aceito')
            ->select(
                'tbusuaria.id_usuaria as id',
                'tbusuaria.nome',
                'tbusuaria.foto'
            )
            ->get();

        return response()->json([
            'data' => $usuarios
        ]);
    }
}
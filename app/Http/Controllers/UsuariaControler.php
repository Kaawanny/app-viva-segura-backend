<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\UsuariaModel;
use App\Models\VinculoModel;

class UsuariaControler extends Controller {
    public function indexApi() {
        $usuarios = UsuariaModel::all();
        return $usuarios;
    }

    public function storeApi(Request $request) {
        try {
            $data = $request->json()->all();
            $request->validate([
                'usuaria.nome' => 'required',
                'usuaria.cpf' => 'required',
                'usuaria.email' => 'required|email',
                'usuaria.senha' => 'required',
                'usuaria.telefone' => 'required',
                'usuaria.dataNasc' => 'required',
                'guardiao.nome' => 'required',
                'guardiao.email' => 'required|email',
            ]);
            DB::beginTransaction();
            $dadosUsuaria = $data['usuaria'];
            $dadosGuardiao = $data['guardiao'];

            // USUÁRIA
            $usuaria = new UsuariaModel();
            $usuaria->nome = $dadosUsuaria['nome'];
            $usuaria->cpf = preg_replace('/\D/', '', $dadosUsuaria['cpf']);
            $usuaria->email = $dadosUsuaria['email'];
            $usuaria->senha = Hash::make($dadosUsuaria['senha']);
            $usuaria->telefone = $dadosUsuaria['telefone'];
            $usuaria->dataNasc = $dadosUsuaria['dataNasc'];
            $usuaria->id_role = 1;
            $usuaria->foto = $dadosUsuaria['foto'] ?? null;

            do {
                $codigo = strtoupper(Str::random(8));
            } while (UsuariaModel::where('codigo_convite', $codigo)->exists());
            $usuaria->codigo_convite = $codigo;
            $usuaria->save();

            // GUARDIÃO
            $guardiao = new UsuariaModel();
            $guardiao->nome = $dadosGuardiao['nome'];
            $guardiao->email = $dadosGuardiao['email'];
            $guardiao->senha = Hash::make(Str::random(10));
            $guardiao->cpf = null;
            $guardiao->telefone = null  ;
            $guardiao->dataNasc = null;
            $guardiao->foto = null;
            $guardiao->id_role = 2;

            do {
                $codigoGuardiao = strtoupper(Str::random(8));
            } while (UsuariaModel::where('codigo_convite', $codigoGuardiao)->exists());
            $guardiao->codigo_convite = $codigoGuardiao;
            $guardiao->save();
            
            $idUsuaria = $usuaria->id_usuaria;
            $idGuardiao = $guardiao->id_usuaria;
            
            VinculoModel::create([
                'id_usuaria' => $idUsuaria,
                'id_guardiao' => $idGuardiao,
                'dataSolicitacao' => now(),
                'dataResposta' => now(),
                'statusVinculo' => 'pendente'
            ]);
            DB::commit();

            // Email enviado para a usuária
            Mail::html("
                <h2>Bem-vinda ao Viva Segura 💜</h2>
                <p>Seu cadastro foi realizado com sucesso.</p>
                <p>Seu código de convite é:</p>
                <h1>{$usuaria->codigo_convite}</h1>
                ", function ($message) use ($usuaria) {
                    $message->to($usuaria->email)
                    ->subject('Cadastro realizado com sucesso');
                }
            );

            // Email enviado para o guardião
            Mail::html("
                <div style='font-family: Arial, sans-serif'>
                    <h2>Viva Segura 💜</h2>
                    <p>Olá {$guardiao->nome}, você foi cadastrado como guardião.</p>
                    <p>Código de convite:</p>
                    <h1>{$usuaria->codigo_convite}</h1>
                </div>
                ", function ($message) use ($guardiao) {
                    $message->to($guardiao->email)
                    ->subject('Convite para ser Guardião');
                }
            );

            if (!empty($usuariaEncontrada->push_token)) {
                \Illuminate\Support\Facades\Http::withHeaders([
                    'Accept'       => 'application/json',
                    'Content-Type' => 'application/json',
                ])->post('https://exp.host/--/api/v2/push/send', [
                    'to'    => $usuariaEncontrada->push_token,
                    'title' => '🛡️ Guardião Confirmado!',
                    'body'  => "Seu guardião {$usuaria->nome} está ativo e te acompanhando.",
                    'sound' => 'default',
                ]);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => true,
                'message' => $e->getMessage(),
                'line' => $e->getLine()
            ], 500);
        }
    }

    public function salvarToken(Request $request, $id) {
        UsuariaModel::where('id_usuaria', $id)
            ->update(['push_token' => $request->push_token]);
        return response()->json(['success' => true]);
    }

    public function loginApi(Request $request) {
        $usuaria = UsuariaModel::where('email', $request->email)->first();
        if (!$usuaria || !Hash::check($request->senha, $usuaria->senha)) {
            return response()->json([
                'error' => 'Credenciais inválidas'
            ], 401);
        }
        return response()->json([
            'user' => $usuaria
        ]);
    }

    public function enviarConviteGuardiao(Request $request) {
        try {
            $usuaria = UsuariaModel::find($request->id_usuaria);
            if (!$usuaria) {
                return response()->json(['error' => 'Usuária não encontrada'], 404);
            }

            Mail::send([], [], function ($message) use ($request, $usuaria) {
                $message->to($request->email_guardiao, $request->nome_guardiao)
                ->subject('Você foi convidado como Guardião!')
                ->html("
                    <h2>Olá, {$request->nome_guardiao}!</h2>
                    <p>Você foi adicionado como guardião.</p>
                    <p>Use este código para se cadastrar no app:</p>
                    <h1 style='letter-spacing:8px; color:#6925b8;'>{$usuaria->codigo_convite}</h1>
                ");
            });
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao enviar',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    public function updateApi(Request $request, $id) {
        try {
            $usuaria = UsuariaModel::find($id);

            if (!$usuaria) {
                return response()->json([
                    'message' => 'Usuária não encontrada'
                ], 404);
            }

            $usuaria->nome = $request->nome ?? $usuaria->nome;
            $usuaria->email = $request->email ?? $usuaria->email;
            $usuaria->telefone = $request->telefone ?? $usuaria->telefone;
            $usuaria->foto = $request->foto ?? $usuaria->foto;

            $usuaria->save();

            return response()->json([
                'message' => 'Atualizado com sucesso!',
                'data' => $usuaria
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao atualizar',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    // Vínculo entre usuária e guardião
    public function homeGuardiao($idGuardiao) {
        try {

            $vinculos = VinculoModel::where('id_guardiao', $idGuardiao)
                ->with('usuaria')
                ->get();

            return response()->json([
                'data' => $vinculos
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao buscar dados da home',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    public function homeUsuaria($idUsuaria) {
        try {
            $vinculos = VinculoModel::where('id_usuaria', $idUsuaria)
                ->with('guardiao')
                ->get();

            return response()->json([
                'data' => $vinculos
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao buscar guardiões vinculados',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    // Alteração de senha
    public function alterarSenha(Request $request, $id)  {
        $usuaria = UsuariaModel::find($id);

        if (!$usuaria) {
            return response()->json([
                'message' => 'Usuária não encontrada.',
                'id_recebido' => $id
            ], 404);
        }

        if (!Hash::check($request->senha_atual, $usuaria->senha)) {
            return response()->json([
                'message' => 'Senha atual incorreta.'
            ], 422);
        }

        if ($request->senha_nova !== $request->senha_nova_confirmation) {
            return response()->json([
                'message' => 'A confirmação da nova senha não confere.'
            ], 422);
        }

        if ($request->senha_atual === $request->senha_nova) {
            return response()->json([
                'message' => 'A nova senha não pode ser igual à senha atual.'
            ], 422);
        }

        $usuaria->senha = Hash::make($request->senha_nova);
        $usuaria->save();
        return response()->json([
            'message' => 'Senha atualizada com sucesso.'
        ]);
    } 
}

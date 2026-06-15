<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UsuariaModel;

use App\Models\VinculoModel;

use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Str;

use Illuminate\Support\Facades\Mail;

class UsuariaControler extends Controller
{

    public function indexApi() {
        $usuarios = UsuariaModel::all();
        return $usuarios;
    }

    public function storeApi(Request $request)
{
    try {

        $rules = [
            'nome' => 'required',
            'email' => 'required|email',
            'senha' => 'required',
            'telefone' => 'required',
            'id_role' => 'required|in:1,2'
        ];

        if ($request->id_role == 1) {
            $rules['cpf'] = 'required';
            $rules['dataNasc'] = 'required';
        }

        $request->validate($rules);

        $usuaria = new UsuariaModel();

        $usuaria->nome = $request->nome;

        if ($request->id_role == 1) {
            $usuaria->cpf = preg_replace('/[^0-9]/', '', $request->cpf);
            $usuaria->dataNasc = $request->dataNasc;
        } else {
            $usuaria->cpf = null;
            $usuaria->dataNasc = null;
        }

        $usuaria->email = $request->email;
        $usuaria->senha = Hash::make($request->senha);
        $usuaria->telefone = $request->telefone;
        $usuaria->id_role = $request->id_role;

        do {
            $codigo = strtoupper(Str::random(8));
        } while (UsuariaModel::where('codigo_convite', $codigo)->exists());

        $usuaria->codigo_convite = $codigo;

        if ($request->id_role == 2) {

            $usuariaEncontrada = UsuariaModel::where(
                'codigo_convite',
                $request->codigo_convite
            )->first();

            if (!$usuariaEncontrada) {
                return response()->json([
                    'error' => 'Código de convite inválido'
                ], 400);
            }
        }

        $usuaria->foto = $request->foto ?? null;

        $usuaria->save();

        if ($request->id_role == 2) {
            VinculoModel::create([
                'id_usuaria' => $usuariaEncontrada->id_usuaria,
                'id_guardiao' => $usuaria->id_usuaria,
                'dataSolicitacao' => now(),
                'dataResposta' => now(),
                'statusVinculo' => 'aceito'
            ]);
        }

        return response()->json([
            'message' => 'Usuária cadastrada com sucesso!',
            'data' => $usuaria
        ], 201);

    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Erro ao cadastrar',
            'details' => $e->getMessage()
        ], 500);
    }
}

    public function loginApi(Request $request)
{
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


public function enviarConviteGuardiao(Request $request)
{
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


    public function updateApi(Request $request, $id)
    {
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

}

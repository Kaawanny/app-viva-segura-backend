<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GuardiaoConvidado;

class GuardiaoController extends Controller
{
    // LISTAR CONVITES
    public function listar($id_usuaria)
    {
        return GuardiaoConvidado::where('id_usuaria', $id_usuaria)->get();
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
}
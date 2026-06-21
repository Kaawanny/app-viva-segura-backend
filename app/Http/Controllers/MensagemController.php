<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mensagem;


class MensagemController extends Controller {
   
    public function store(Request $request) {
        $msg = Mensagem::create([
            'texto' => $request->texto,
            'usuario_id' => $request->usuario_id,
            'guardiao_id' => $request->guardiao_id,
            'enviado_por' => $request->enviado_por
        ]);

        return response()->json($msg);
    }

    public function conversa($usuarioId, $guardiaoId) {
        return Mensagem::where('usuario_id', $usuarioId)
            ->where('guardiao_id', $guardiaoId)
            ->orderBy('created_at', 'asc')
            ->get();
    }
}

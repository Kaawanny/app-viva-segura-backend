<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use  App\Models\EnderecoUsuariaModel;

class EnderecoUsuariaController extends Controller {
    
    public function index($id_usuaria)
    {
        try {
            $enderecos = EnderecoUsuariaModel::where('id_usuaria', $id_usuaria)->get();

            return response()->json([
                'data' => $enderecos
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao buscar endereço',
                'details' => $e->getMessage()
            ], 500);
        }
    }
    
    public function storeApi(Request $request)
    {
        try {
            $request->validate([
                'id_usuaria' => 'required',
                'endereco' => 'required',
                'descricao' => 'required',
            ]);

            $enderecoUsuaria = new EnderecoUsuariaModel();

            $enderecoUsuaria->id_usuaria = $request->id_usuaria;
            $enderecoUsuaria->endereco = $request->endereco;
            $enderecoUsuaria->complemento = $request->complemento;
            $enderecoUsuaria->descricao = $request->descricao;
            $enderecoUsuaria->latitude = $request->latitude;
            $enderecoUsuaria->longitude = $request->longitude;
            $enderecoUsuaria->save();

            return response()->json([
                'message' => 'Endereço salvo com sucesso',
                'data' => $enderecoUsuaria
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao salvar endereço',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $endereco = EnderecoUsuariaModel::findOrFail($id);
            $endereco->update([
                'endereco' => $request->endereco,
                'complemento' => $request->complemento,
                'descricao' => $request->descricao,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
            ]);

            return response()->json([
                'message' => 'Endereço atualizado com sucesso',
                'data' => $endereco
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao atualizar',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(string $id)
    {
        try {
        $endereco = EnderecoUsuariaModel::findOrFail($id);
        $endereco->delete();

        return response()->json([
            'message' => 'Endereço deletado com sucesso'
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Erro ao deletar',
            'details' => $e->getMessage()
        ], 500);
    }
    }
    
}

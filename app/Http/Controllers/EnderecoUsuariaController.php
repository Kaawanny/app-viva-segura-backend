<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use  App\Models\EnderecoUsuariaModel;
class EnderecoUsuariaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
public function index()
{
    try {
        $enderecos = EnderecoUsuariaModel::all();

        return response()->json([
            'data' => $enderecos
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Erro ao buscar endereços',
            'details' => $e->getMessage()
        ], 500);
    }
}
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
public function storeApi(Request $request)
{
    try {

        $enderecoUsuaria = new EnderecoUsuariaModel();

        $enderecoUsuaria->enderecoUsuaria = $request->enderecoUsuaria;
        $enderecoUsuaria->complementoEnderecoUsuaria = $request->complementoEnderecoUsuaria;
        $enderecoUsuaria->descricaoEnderecoUsuaria = $request->descricaoEnderecoUsuaria;

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
            'enderecoUsuaria' => $request->enderecoUsuaria,
            'complementoEnderecoUsuaria' => $request->complementoEnderecoUsuaria,
            'descricaoEnderecoUsuaria' => $request->descricaoEnderecoUsuaria
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

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
  

    /**
     * Remove the specified resource from storage.
     */
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

<?php

namespace App\Http\Controllers;
use  App\Models\EnderecoPesquisaModel;
use Illuminate\Http\Request;

class EnderecoPesquisaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
  public function index()
{
    try {
        $enderecoPesquisa = EnderecoPesquisaModel::all();

        return response()->json([
            'data' => $enderecoPesquisa
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Erro ao buscar endereços',
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
        $enderecoPesquisa = new EnderecoPesquisaModel();

        $enderecoPesquisa->enderecoPesquisa = $request->enderecoPesquisa;

        $enderecoPesquisa->save();

        return response()->json([
            'message' => 'Pesquisa realizada',
            'data' => $enderecoPesquisa
        ], 201);

    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Erro pesquisa',
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

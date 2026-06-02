<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LocalSeguroController extends Controller
{
    public function index()
    {
        $locais = DB::table('tb_local_seguro')
            ->select('id_localSeguro', 'nome', 'tipo', 'endereco', 'latitude', 'longitude')
            ->where('ativo', true)
            ->orderBy('nome')
            ->get();

        return response()->json($locais);
    }
}
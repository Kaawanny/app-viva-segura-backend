<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuariaControler;
use App\Http\Controllers\EnderecoPesquisaController;
use App\Http\Controllers\EnderecoUsuariaController;
use App\Http\Controllers\LocalController;
use App\Http\Controllers\BotaoPanicoController;
use App\Http\Controllers\GuardiaoController;

use App\Http\Controllers\LocalSeguroController; 
use App\Http\Controllers\AlertaController; 
use App\Http\Controllers\PontosRotaController;
 use App\Http\Controllers\RotaController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/usuarios', 'App\Http\Controllers\UsuariaControler@indexApi');
Route::post('/cadastrar', 'App\Http\Controllers\UsuariaControler@storeApi');
Route::post('/login', 'App\Http\Controllers\UsuariaControler@loginApi');

Route::post('/salvarPesquisaEndereco', 'App\Http\Controllers\EnderecoPesquisaController@storeApi');
Route::get('/exibirPesquisaEndereco', 'App\Http\Controllers\EnderecoPesquisaController@index');

Route::post('/salvarEndereco', 'App\Http\Controllers\EnderecoUsuariaController@storeApi');
Route::get('/exibirEndereco', 'App\Http\Controllers\EnderecoUsuariaController@index');

Route::put('/usuaria/{id}', [UsuariaControler::class, 'updateApi']);
Route::put('/salvarEnderecoAlterado/{idEnderecoUsuaria}', [EnderecoUsuariaController::class, 'update']);
Route::delete('/salvarEnderecoAlterado/{id}', [EnderecoUsuariaController::class, 'destroy']);

//guardiao
Route::get('/guardiao/home/{id}', [UsuariaControler::class, 'homeGuardiao']);

Route::post('/localizacao', [LocalController::class, 'atualizarLocalizacao']);
Route::get('/localizacao/{id}', [LocalController::class, 'buscarLocalizacao']);

//botao
Route::post('/botao-panico', [botaoPanicoController::class, 'store']);
Route::get('/botao-panico-ativos', [botaoPanicoController::class, 'ativos']);

//meus guardioes 
Route::post('/enviarConviteGuardiao', [UsuariaControler::class, 'enviarConviteGuardiao']);

// listar convites
Route::get('/guardioes/{id_usuaria}', [GuardiaoController::class, 'listar']);

// salvar convite
Route::post('/guardioes', [GuardiaoController::class, 'adicionar']);

// remover convite
Route::delete('/guardioes/{id_usuaria}/{id_guardiao}', [GuardiaoController::class, 'remover']);


Route::get('/pontos-rota',[LocalSeguroController::class, 'index']);
Route::get('/alertas', [AlertaController::class, 'index']);
Route::post('/alertas', [AlertaController::class, 'store']);
Route::post('/rota', [RotaController::class, 'store']);
Route::get('/rotas/{id_usuaria}', [PontosRotaController::class, 'historico']);
Route::get('/rotas/{id_rota}/pontos', [PontosRotaController::class, 'pontosDaRota']);
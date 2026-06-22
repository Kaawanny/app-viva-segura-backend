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
use App\Http\Controllers\RotaCompartilhadaController;
use App\Http\Controllers\MensagemController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/usuarios', 'App\Http\Controllers\UsuariaControler@indexApi');
Route::post('/cadastrar', 'App\Http\Controllers\UsuariaControler@storeApi');
Route::post('/login', 'App\Http\Controllers\UsuariaControler@loginApi');
// Cadastro  do guardiao
Route::post('/cadastrar-guardiao', [GuardiaoController::class, 'cadastroGuardiao']);
// Usuária
Route::put('/usuaria/{id}', [UsuariaControler::class, 'updateApi']);
Route::put('/usuaria/{id}/senha', [UsuariaControler::class, 'alterarSenha']);
// Geolocalização
Route::post('/salvarPesquisaEndereco', 'App\Http\Controllers\EnderecoPesquisaController@storeApi');
Route::get('/exibirPesquisaEndereco', 'App\Http\Controllers\EnderecoPesquisaController@index');
Route::post('/salvarEndereco', 'App\Http\Controllers\EnderecoUsuariaController@storeApi');
Route::get('/exibirEndereco/{id_usuaria}', [EnderecoUsuariaController::class, 'index']);
Route::put('/salvarEnderecoAlterado/{idEnderecoUsuaria}', [EnderecoUsuariaController::class, 'update']);
Route::delete('/salvarEnderecoAlterado/{id}', [EnderecoUsuariaController::class, 'destroy']);
// Geolocalização do Guardião
Route::post('/localizacao', [LocalController::class, 'atualizarLocalizacao']);
Route::get('/localizacao/{id}', [LocalController::class, 'buscarLocalizacao']);
// Botao SOS
Route::post('/botao-panico', [botaoPanicoController::class, 'store']);
Route::get('/botao-panico-ativos', [botaoPanicoController::class, 'ativos']);
// Convite - vínculo
Route::post('/enviarConviteGuardiao', [UsuariaControler::class, 'enviarConviteGuardiao']);
Route::post('/guardiao/aceitar-convite', [GuardiaoController::class, 'aceitarConvite']);
Route::get('/guardioes/{id_usuaria}', [GuardiaoController::class, 'listar']);
Route::post('/guardioes', [GuardiaoController::class, 'adicionar']);
Route::delete('/guardioes/{id_usuaria}/{id_guardiao}', [GuardiaoController::class, 'remover']);
// Mapa - Locais seguros, Rota, Compartilhamento de rota e Alertas
Route::get('/pontos-rota',[LocalSeguroController::class, 'index']);
Route::get('/alertas', [AlertaController::class, 'index']);
Route::post('/alertas', [AlertaController::class, 'store']);
Route::post('/rota', [RotaController::class, 'store']);
Route::get('/rotas/{id_usuaria}', [PontosRotaController::class, 'historico']);
Route::get('/rotas/{id_rota}/pontos', [PontosRotaController::class, 'pontosDaRota']);
Route::post('/rota-compartilhada', [RotaCompartilhadaController::class, 'compartilhar']);
Route::post('/rota-compartilhada/aceitar', [RotaCompartilhadaController::class, 'aceitar']);
Route::get('/rota-compartilhada/pendentes/{id_guardiao}', [RotaCompartilhadaController::class, 'solicitacoesPendentes']);
Route::post('/rota-compartilhada/chegou', [RotaCompartilhadaController::class, 'chegouAoDestino']);
Route::post('/rota-compartilhada/encerrar', [RotaCompartilhadaController::class, 'encerrar']);
Route::get('/rota-compartilhada/ativa/{id_guardiao}', [RotaCompartilhadaController::class, 'rotaAtiva']);
Route::get('/rota-compartilhada/dados/{id_usuaria}', [RotaCompartilhadaController::class, 'dadosRota']);
Route::get('/guardiao/home/{id}', [RotaCompartilhadaController::class, 'rotaAtivaHome']);
// Chats
Route::get('/mensagens/{usuario}/{guardiao}', [MensagemController::class, 'conversa']);
Route::post('/mensagens', [MensagemController::class, 'store']);
Route::get('/usuaria/home/{id}', [UsuariaControler::class, 'homeUsuaria']);
Route::get('/guardiao/chat/{id}', [GuardiaoController::class, 'chat']);
// Notificações
Route::post('/usuaria/{id}/salvar-token', [UsuariaControler::class, 'salvarToken']);
Route::get('/guardioes-pendentes/{id_usuaria}', [GuardiaoController::class, 'pendentes']);
Route::get('/usuaria/home/{id}', [UsuariaControler::class, 'homeUsuaria']);
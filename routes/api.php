<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::get("/{id_personalidade}/perguntar/{pergunta}", "RedeNeural@responder");
Route::get("/atualiza-pergunta/{id}/{condicao}", "RedeNeural@atualizaMemoriaNeural");


Route::post("/salva-dados", "LeadController@salvar");
Route::post("/atualiza-dados", "LeadController@atualiza");

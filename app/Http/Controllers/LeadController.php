<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Lead;
use Log;
use Validator;

class LeadController extends Controller
{
  public function salvar(Request $request){
    $validator = Validator::make($request->all(), [
      'nome' => 'required',
      'email' => 'required'
    ]);

    if ($validator->fails()) {
      Log::warning("Falha ao enviar parametros! : promotionUser => " . $validator->errors());

      return response()->json(['success' => '' , 'error' => 'true', 'description' => $validator->errors()], 400);
    }
    

    $register = Lead::create($request->all()); 

    Log::info("Novo registro cadastrado => " . $request->nome);
    
    return response()->json(['success' => 'Registro salvo' , 'error' => ''], 200);
  }
}

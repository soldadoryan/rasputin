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
      'email' => 'required',
      'id_personalidade' => 'required',
    ]);

    if ($validator->fails()) {
      Log::warning("Falha ao enviar parametros! : " . $validator->errors());

      return response()->json(['success' => '' , 'error' => 'true', 'description' => $validator->errors()], 400);
    }


    $register = Lead::create($request->all());

    Log::info("Novo registro cadastrado => " . $request->nome);

    return response()->json(['success' => 'Registro salvo' , 'id' => $register->id, 'error' => ''], 200);
  }

  public function atualiza(Request $request)
	{
		$lead = Lead::find($request->id);
		if(!$lead) {
			return response()->json(['success'   => '','error'   => 'Registro não encontrado',], 404);
		}
		$lead->telefone = $request->telefone;
		$lead->save();

		return response()->json(['success' => 'Registro atualizado' , 'error' => ''], 200);
	}


}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Lead;
use Log;
use Validator;
use App\Mail\SendMail;
use Illuminate\Support\Facades\Mail;

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

   $data = [
    'view' => 'mails.contato', 
    'senderName' => 'Taskinho', 
    'to' => 'suporte@task.com.br', 
    'receiverName' => 'Suporte', 
    'subject' => 'Contato Taskinho',
    'customerMail' => $lead->email,
    'customerTel' => $lead->telefone,
    'customerName' => $lead->nome
  ];

  $this->sendMail($data);

   return response()->json(['success' => 'Registro atualizado' , 'error' => ''], 200);
}

public function sendMail($data){

  try{
    Mail::send(
      $data['view'], 
      $data, 
      function($message) use ($data){         
        $message->from('falecom@task.com.br', $data['senderName']);
        $message->to($data['to'], $data['receiverName']);
        $message->subject($data['subject']);
      }
    );

    Log::info("E-mail enviado para: " . $data['to'] . " | Assunto: " . $data['subject']);

    return response()->json(['success' => true , 'error' => ''], 200);

  } catch(\Exception $e){
    Log::warning("Verifique o metodo sendMail: " . $e->getMessage());

    return response()->json(['success' => '' , 'error' => 'true'], 500);
  }
}


}

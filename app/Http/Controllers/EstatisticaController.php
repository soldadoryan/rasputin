<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Palavras;

class EstatisticaController extends Controller
{

  public function geraRelatorio()
  {
    
   
    $palavrasIgnoradas = [
      'a','e','o','ola','de','uma','boa','noite','dia','ex','etc','no','os','as','na','nos','onde','merda','eu','que','do','meu',
      'nao','sim','como','em','se','va','fiz','voce'
    ];

    $select = "SELECT 
    pergunta,
    created_at,
    DATE_FORMAT(CONVERT(created_at,DATE),'%d/%m/%Y') as dia 
    FROM memoria_neural";


    $listaPerguntas = collect(DB::select($select))->all();
    $arrayFinal = [];

    foreach ($listaPerguntas as $pergunta) {
      $perguntaTratada = $this->tratarPergunta($pergunta->pergunta);
      $arrayPalavras =  explode(' ', $perguntaTratada);

      foreach ($arrayPalavras as $palavra) {
        if(!in_array($palavra, $palavrasIgnoradas)){
          array_push($arrayFinal, $palavra);         
        }
      }
      
    }

    foreach ($arrayFinal as $palavra) {

      
      $palavraPesquisa = Palavras::where('palavra',$palavra)->first();
          

      if(sizeof($palavraPesquisa) > 0){

        $palavraPesquisa->quantidade = $palavraPesquisa->quantidade + 1;
        $palavraPesquisa->save();

      } else{

        $palavras = new Palavras();
        $palavras->palavra = $palavra;
        $palavras->data = date("Y-m-d");
        $palavras->save();
      }

      
    }

    
    return "Relatório Gerado!";
    
  }

  public function geraGrafico()
  {
    $palavras = Palavras::orderBy('quantidade','desc')->take(10)->get();

    if($palavras){
      return response()->json($palavras, 200);
    }

    return response()->json('Erro no retorno dos dados', 500);
  }

  public function verificaPalavra($arrayPergunta):array
  {
    $palavrasIgnoradas = ['ola','de'];
    $arrayPalavras = [];

    foreach ($arrayPergunta as $palavra) {
      if(!in_array($palavra, $palavrasIgnoradas)){
        array_push($arrayPalavras, $palavra);         
      }
    }


    return $arrayPalavras;
  }

  public function tratarPergunta($pergunta) 
  {

    $pergunta = $this->retirarAcentos($pergunta);    
    $pergunta = strtolower($pergunta);    
    $pergunta = trim($pergunta);
    $pergunta = str_replace(".", "", $pergunta);
    $pergunta = str_replace(",", "", $pergunta);
    $pergunta = str_replace("-", "", $pergunta);
    $pergunta = str_replace("/", "", $pergunta);
    $pergunta = str_replace("?", "", $pergunta);
    $pergunta = str_replace("!", "", $pergunta);
    
    return $pergunta;
  }

  public function retirarAcentos($pergunta) 
  {
    return preg_replace(array("/(á|à|ã|â|ä)/","/(Á|À|Ã|Â|Ä)/","/(é|è|ê|ë)/","/(É|È|Ê|Ë)/","/(í|ì|î|ï)/","/(Í|Ì|Î|Ï)/","/(ó|ò|õ|ô|ö)/","/(Ó|Ò|Õ|Ô|Ö)/","/(ú|ù|û|ü)/","/(Ú|Ù|Û|Ü)/","/(ñ)/","/(Ñ)/","/(ç)/","/(Ç)/"),explode(" ","a A e E i I o O u U n N c C"), $pergunta);
  }
}

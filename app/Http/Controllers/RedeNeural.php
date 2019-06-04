<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\MemoriaNeural;
use App\MemoriaCognitiva;
use App\Personalidade;

class RedeNeural extends Controller
{

  //PONTUACAO DAS TAGS
  CONST VTAGS = 1;
  CONST VSTAGS = 2;
  CONST VHTAGS = 4;


  public function responder($id_personalidade, $pergunta) {
    $id_rasputin = base64_decode($id_personalidade);
    $p = $this->tratarPergunta($pergunta);

    $VTAGS = 1;
    $VSTAGS = 2;
    $VHTAGS = 4;

    // $memoria_neural =
    $memoria_cognitiva = MemoriaCognitiva::where("id_rasputin", $id_rasputin)->get();

    $id_max_pont = 0;
    $max_pont = 0;
    foreach ($memoria_cognitiva as $mc) {
      $pont = 0;
      for ($i=0; $i < count($p); $i++) {

        $Tags = explode(',', $mc->tags);
        $STags = explode(',', $mc->stags);
        $HTags = explode(',', $mc->htags);

        if(in_array($p[$i],$Tags)){
          $pont += self::VTAGS;
        }

        if(in_array($p[$i],$STags)){
          $pont += self::VSTAGS;
        }

        if(in_array($p[$i],$HTags)){
          $pont += self::VHTAGS;
        } 

      }

      if($max_pont < $pont) {
        $id_max_pont = $mc->id;
        $max_pont = $pont;
      }
     
    }

    if($id_max_pont == 0) {
      return "nao entendi, repita por favor";
    }

    $resposta = MemoriaCognitiva::where("id", $id_max_pont)->get();

    return $resposta[0]->resposta;


  }

  public function tratarPergunta($pergunta) {
    $pergunta = $this->retirarAcentos($pergunta);
    $pergunta = strtolower($pergunta);

    $pergunta = trim($pergunta);
    $pergunta = str_replace(".", "", $pergunta);
    $pergunta = str_replace(",", "", $pergunta);
    $pergunta = str_replace("-", "", $pergunta);
    $pergunta = str_replace("/", "", $pergunta);

    return explode(' ', $pergunta);
  }

  public function retirarAcentos($pergunta) {
    return preg_replace(array("/(á|à|ã|â|ä)/","/(Á|À|Ã|Â|Ä)/","/(é|è|ê|ë)/","/(É|È|Ê|Ë)/","/(í|ì|î|ï)/","/(Í|Ì|Î|Ï)/","/(ó|ò|õ|ô|ö)/","/(Ó|Ò|Õ|Ô|Ö)/","/(ú|ù|û|ü)/","/(Ú|Ù|Û|Ü)/","/(ñ)/","/(Ñ)/"),explode(" ","a A e E i I o O u U n N"), $pergunta);
  }
}

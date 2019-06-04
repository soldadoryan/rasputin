<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\MemoriaNeural;
use App\MemoriaCognitiva;
use App\Personalidade;

class RedeNeural extends Controller
{

  public function responder($id_personalidade, $pergunta) {
    $id_rasputin = base64_decode($id_personalidade);
    $p = $this->tratarPergunta($pergunta);

    $VTAGS = 1;
    $VSTAGS = 2;
    $VHTAGS = 4;

    // $memoria_neural =
    $memoria_cognitiva = MemoriaCognitiva::where("id_rasputin", $id_rasputin)->get();

    $tag = $memoria_cognitiva[0]->tags;

    $array_pontuacao = [];

    $id_max_pont = 0;
    $max_pont = 0;
    foreach ($memoria_cognitiva as $mc) {
      $pont = 0;
      for ($i=0; $i < count($p); $i++) {

        $Tags = explode(',', $mc->tags);
        $STags = explode(',', $mc->stags);
        $HTags = explode(',', $mc->htags);


        for ($y=0; $y < count($Tags); $y++) {
          if($p[$i] == $Tags[$y]) {
            $pont += $VTAGS;
          }
        }

        for ($z=0; $z < count($STags); $z++) {
          if($p[$i] == $STags[$z]) {
            $pont += $VSTAGS;
          }
        }

        for ($t=0; $t < count($HTags); $t++) {
          if($p[$i] == $HTags[$t]) {
            $pont += $VHTAGS;
          }
        }
      }

      if($max_pont < $pont) {
        $id_max_pont = $mc->id;
        $max_pont = $pont;
      }

      array_push($array_pontuacao, [
        "id" => $mc->id,
        "pontuacao" => $pont,
      ]);
    }

    if($id_max_pont == -1) {
      return "blaaaum";
    }
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

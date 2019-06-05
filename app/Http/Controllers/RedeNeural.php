<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\MemoriaNeural;
use App\MemoriaCognitiva;
use App\Rasputin;

class RedeNeural extends Controller
{

  //PONTUACAO DAS TAGS
  CONST VTAGS = 1;
  CONST VSTAGS = 2;
  CONST VHTAGS = 4;

  private $memoriaCognitiva;
  private $rasputin;
  private $pergunta_max_pont = 0;
  private $max_pont = 0;

  public function __construct(MemoriaCognitiva $memoriaCognitiva, Rasputin $rasputin) {
    $this->memoriaCognitiva = $memoriaCognitiva;
    $this->rasputin = $rasputin;
  }


  public function responder($id_personalidade, $pergunta) {

    // pega id do rasputin
    $id_rasputin = base64_decode($id_personalidade);

    $rasputin = $this->rasputin->where("id", $id_rasputin)->get();

    // tratar pergunta
    $perguntaTratada = $this->tratarPergunta($pergunta);

    // busca dados da memória cognitiva
    $memoria_cognitiva = $this->memoriaCognitiva->where("id_rasputin", $id_rasputin)->get();

    $pontuacao = $this->neuronio($perguntaTratada,$memoria_cognitiva);

    if($this->max_pont < 4) {
      return "nao entendi, repita por favor";
    }

    $resposta = $this->pergunta_max_pont->resposta;

    return $resposta;

  }

  public function neuronio($pergunta,$memoria_cognitiva){
    foreach ($memoria_cognitiva as $mc) {

      $pont = 0;

      for ($i=0; $i < count($pergunta); $i++) {

        $Tags = explode(',', $mc->tags);
        $STags = explode(',', $mc->stags);
        $HTags = explode(',', $mc->htags);

        if(in_array($pergunta[$i],$Tags)){
          $pont += self::VTAGS;
        }

        if(in_array($pergunta[$i],$STags)){
          $pont += self::VSTAGS;
        }

        if(in_array($pergunta[$i],$HTags)){
          $pont += self::VHTAGS;
        }

      }

      if($this->max_pont < $pont) {
        $this->pergunta_max_pont = $mc;
        $this->max_pont = $pont;
      }

    }

    return $pont;
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

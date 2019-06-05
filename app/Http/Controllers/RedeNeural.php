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
  private $memoriaNeural;
  private $rasputin;
  private $pergunta_max_pont_mc;
  private $pergunta_max_pont_mn;
  private $max_pont = 0;
  private $MNTags = "";
  private $MNSTags = "";
  private $MNHTags = "";

  public function __construct(MemoriaCognitiva $memoriaCognitiva, Rasputin $rasputin, MemoriaNeural $memoriaNeural) {
    $this->memoriaCognitiva = $memoriaCognitiva;
    $this->memoriaNeural = $memoriaNeural;
    $this->rasputin = $rasputin;
  }

  public function responder($id_personalidade, $pergunta) {
    //Olá amigão, gostaria de configurar o meu email. Pode me ajudar?

    // pega id do rasputin
    $id_rasputin = base64_decode($id_personalidade);

    $rasputin = $this->rasputin->where("id", $id_rasputin)->get();

    // tratar pergunta
    $perguntaTratada = $this->tratarPergunta($pergunta);

    // busca dados da memória cognitiva
    $memoria_cognitiva = $this->memoriaCognitiva->where("id_rasputin", $id_rasputin)->get();
    $memoria_neural = $this->memoriaNeural->where("id_rasputin", $id_rasputin)->get();

    $pontuacao_mc = $this->neuronio($perguntaTratada,$memoria_cognitiva, "mc");
    $pontuacao_mn = $this->neuronio($perguntaTratada,$memoria_neural, "mn");

    $mn = new $this->memoriaNeural;
    $mn->tags = $this->MNTags;
    $mn->stags = $this->MNSTags;
    $mn->htags = $this->MNHTags;
    $mn->id_resposta =  $this->pergunta_max_pont_mc->id;
    $mn->id_rasputin =  $id_rasputin;

    $mn->save();
    // echo "VERFICANDO PONTUACOES <BR>";
    // echo 'PONTUAÇÃO MC => ' . $pontuacao_mc . '<BR>';
    // echo 'PONTUAÇÃO MC => ' . $pontuacao_mc . " PONTUAÇÃO MN =>" . $pontuacao_mn . '<BR>';
    if($pontuacao_mc < 4 && $pontuacao_mn < 4) {
      return "nao entendi, repita por favor";
    }
    //dd($this->pergunta_max_pont_mn->aprender);
    if($pontuacao_mc > $pontuacao_mn) {
      return $this->pergunta_max_pont_mc->resposta;
    } else {
      if($this->pergunta_max_pont_mn->aprender == true)
        return $this->pergunta_max_pont_mn->resposta;
      else
        return $this->pergunta_max_pont_mc->resposta;
    }
  }

  public function neuronio($pergunta,$memoria_cognitiva,$tipoMemoria){   
    // echo "PERGUNTA <BR>";
    // print_r($pergunta);
    // echo "<BR>";
    // echo "TIPO MEMORIA <BR>";
    // print_r($tipoMemoria);
    // echo "<BR>";
    if(count($memoria_cognitiva) > 0) {
      foreach ($memoria_cognitiva as $mc) {
         // echo "=====================================================<br>";
        //$this->max_pont = 0;
        $pont = 0;
        $mn_tags = "";
        $mn_stags = "";
        $mn_htags = "";

        // echo "MN_STAGS ANTES DO FOR <BR>";
        // print_r($mn_stags);
        // echo "<BR>";

        // echo "MN_HTAGS ANTES DO FOR <BR>";
        // print_r($mn_htags);
        // echo "<BR>";

        for ($i=0; $i < count($pergunta); $i++) {
          $Tags = explode(',', $mc->tags);
          $STags = explode(',', $mc->stags);
          $HTags = explode(',', $mc->htags);

          // echo "PONTUACAO ANTES DOS INARRAY <BR>";
          // print_r($pont);
          // echo "<BR>";

          if(in_array($pergunta[$i],$Tags)){
            $pont += self::VTAGS;
            if($mn_tags != "")
              $mn_tags .= "," . $pergunta[$i];
            else
              $mn_tags = $pergunta[$i];
          }

          // echo "PONTUACAO APOS TAGS <BR>";
          // print_r($pont);
          // echo "<BR>";

          if(in_array($pergunta[$i],$STags)){
            $pont += self::VSTAGS;
            if($mn_stags != "")
              $mn_stags .= "," . $pergunta[$i];
            else
              $mn_stags = $pergunta[$i];
          }

          // echo "MN_STAGS APOS STAGS <BR>";
          // print_r($mn_stags);
          // echo "<BR>";

          //print_r($HTags);
          if(in_array($pergunta[$i],$HTags)){
            $pont += self::VHTAGS;
            if($mn_htags != "")
              $mn_htags .= "," . $pergunta[$i];
            else
              $mn_htags = $pergunta[$i];
          }

        //   echo "MN_HTAGS APOS HTAGS <BR>";
        // print_r($mn_htags);
        // echo "<BR>";
        }

        //echo 'THIS MAX PONT => ' . $this->max_pont . " PONT =>" . $pont . '<BR>';
        if($this->max_pont <= $pont) {
          if($tipoMemoria == "mc"){
            $this->MNTags = $mn_tags;
            $this->MNSTags = $mn_stags;
            $this->MNHTags = $mn_htags;
            $this->pergunta_max_pont_mc = $mc;
          }
          else if($tipoMemoria == "mn") {
            $this->pergunta_max_pont_mn = $mc;
          }

          $this->max_pont = $pont;
        }

      //   echo "PONTUACAO DENTRO DO FOREACH <BR>";
      // print_r($pont);
      // echo "<BR>";

      }
      // echo "PONTUACAO DENTRO DO NEURONIO <BR>";
      // print_r($pont);
      // echo "<BR>";
      return $this->max_pont;
    }
    return 0;
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
    return preg_replace(array("/(á|à|ã|â|ä)/","/(Á|À|Ã|Â|Ä)/","/(é|è|ê|ë)/","/(É|È|Ê|Ë)/","/(í|ì|î|ï)/","/(Í|Ì|Î|Ï)/","/(ó|ò|õ|ô|ö)/","/(Ó|Ò|Õ|Ô|Ö)/","/(ú|ù|û|ü)/","/(Ú|Ù|Û|Ü)/","/(ñ)/","/(Ñ)/","/(ç)/","/(Ç)/"),explode(" ","a A e E i I o O u U n N c C"), $pergunta);
  }
}

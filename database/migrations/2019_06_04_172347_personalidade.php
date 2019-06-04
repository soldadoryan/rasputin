<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Personalidade extends Migration
{
    public function up()
    {
      Schema::create('personalidade', function (Blueprint $table) {
          $table->bigIncrements('id');
          $table->string('nome');
          $table->string('imagem');
          $table->timestamps();
      });
    }

    public function down()
    {
      Schema::drop('personalidade');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Rasputin extends Migration
{
    public function up()
    {
      Schema::create('rasputin', function (Blueprint $table) {
          $table->bigIncrements('id');
          $table->string('nome');
          $table->string('imagem');
          $table->timestamps();
      });
    }

    public function down()
    {
      Schema::drop('rasputin');
    }
}

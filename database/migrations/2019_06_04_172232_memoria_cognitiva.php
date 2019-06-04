<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MemoriaCognitiva extends Migration
{

    public function up()
    {
      Schema::create('memoria_cognitiva', function (Blueprint $table) {
          $table->bigIncrements('id');
          $table->string('descricao');
          $table->string('tags');
          $table->string('stags');
          $table->string('htags');
          $table->longText('resposta');
          $table->timestamps();
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}

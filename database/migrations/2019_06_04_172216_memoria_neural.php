<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MemoriaNeural extends Migration
{
    public function up()
    {
      Schema::create('memoria_neural', function (Blueprint $table) {
          $table->bigIncrements('id');
          $table->string('tags');
          $table->string('stags');
          $table->string('htags');
          $table->longText('id_resposta');
          $table->boolean('aprender');
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

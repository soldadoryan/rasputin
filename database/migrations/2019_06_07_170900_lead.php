<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Lead extends Migration
{
    
    public function up()
    {
        Schema::create('lead', function (Blueprint $table) {
          $table->bigIncrements('id');
          $table->string('nome');
          $table->string('email');
          $table->timestamps();
      });
    }

    
    public function down()
    {
        chema::drop('lead');
    }
}

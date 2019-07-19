<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Palavras extends Model
{
     protected $table = 'palavras';
     protected $fillable = ['palavra','quantidade'];
}

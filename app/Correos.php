<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Correos extends Model
{
    protected $fillable = [
        'nombre','email'
    ];
}

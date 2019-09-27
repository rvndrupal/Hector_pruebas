<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Clientes extends Model
{
    protected $fillable = [
        'nombre_cli','dir_cli'
    ];

    public function fotos()
    {
        return $this->hasMany('App\Fotos');
    }
}

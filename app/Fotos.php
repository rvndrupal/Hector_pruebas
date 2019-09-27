<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Fotos extends Model
{
    protected $fillable = [
        'imagen_fot'
    ];


    public function cliente()
    {
        return $this->belongsTo('App\Clientes');
    }
}

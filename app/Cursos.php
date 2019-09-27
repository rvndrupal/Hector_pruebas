<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cursos extends Model
{
    protected $fillable = [
        'nombre_curso','descripcion_curso'
    ];

     //Para el sofdeletes
     use SoftDeletes;
     protected $dates = ['deleted_at'];
     //Para el sofdeletes



    public function producto()
    {
        return $this->belongsTo('App\Products');
    }
}

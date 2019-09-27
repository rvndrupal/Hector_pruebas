<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Products extends Model
{
    protected $fillable = [
        'nombre_producto','ap_producto','slug_producto','imagen_producto'
    ];

    //Para el sofdeletes
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    //Para el sofdeletes


    //para el slug
    public function getRouteKeyName()
    {
        return 'slug_producto';
    }
    //para el slug

    public function cursos()
    {
        return $this->hasMany('App\Cursos');
    }
}

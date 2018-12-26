<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Devolucion extends Model
{
    protected $table = 'devoluciones';
    protected $fillable = [
        'idventa','idusuario','fecha_hora','total','estado'
    ];
}

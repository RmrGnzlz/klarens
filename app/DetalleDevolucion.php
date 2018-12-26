<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetalleDevolucion extends Model
{
    protected $table = 'detalle_devoluciones';
    protected $fillable = [
        'idventa','iddetalle','cantidad'
    ];
    public $timestamps = false;
}

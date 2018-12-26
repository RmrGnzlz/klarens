<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inventario extends Model
{
    protected $fillable = [
        'idingreso', 'idusuario', 'inicio','fin','estado', 'valor', 'vendido'
    ];

    public function usuario() {
        return $this->belongsTo('App\Users');
    }

    public function detalles() {
        return $this->hasMay('App\DetalleInventario');
    }
}

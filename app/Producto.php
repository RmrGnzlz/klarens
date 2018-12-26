<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $fillable =[
        'idcategoria','codigo','nombre','precio_venta','stock', 'impuesto', 'peso', 'descripcion','estado'
    ];

    public function categoria(){
        return $this->belongsTo('App\Categoria');
    }

    public function inventarios() {
        return $this->hasMany('App\DetalleInventario','idproducto');
    }
}
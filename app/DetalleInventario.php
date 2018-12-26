<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetalleInventario extends Model
{
    protected $table = 'detalle_inventarios';
    protected $fillable = [
        'idinventario', 'idproducto', 'cantidad_inicial', 'cantidad', 'precio_compra', 'precio'
    ];

    public function producto() {
        return $this->belongsTo('App\Producto');
    }
}

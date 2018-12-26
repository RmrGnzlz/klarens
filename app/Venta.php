<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    protected $fillable = [
        'idcliente','idvendedor','idusuario', 'tipo_comprobante','num_comprobante','fecha_hora','impuesto','total', 'pagado', 'estado'
    ];

    public function usuario() {
        return $this->belongsTo('App\Users');
    }

    public function vendedor() {
        return $this->belongsTo('App\Vendedor');
    }

    public function cliente() {
        return $this->belongsTo('App\Persona');
    }

    public function detalles() {
        return $this->hasMany('App\DetalleVenta');
    }
}

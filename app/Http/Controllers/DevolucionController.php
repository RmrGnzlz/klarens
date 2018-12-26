<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Devolucion;
use App\Venta;
use App\DevolucionDetalle;
use App\DetalleVenta;
use App\Persona;
use Carbon\Carbon;

class DevolucionController extends Controller
{
    public function store(Request $request) {
        if (!$request->ajax()) return redirect('/');

        try{
            DB::beginTransaction();

            $myTime = Carbon::now('America/Bogota');

            $venta2 = Venta::findOrFail($request->id);
            $venta2->estado = 'Anulado';
            $venta2->save();

            $devolucion = new Devolucion();
            $devolucion->idventa = $request->id;
            $devolucion->idusuario = \Auth::user()->id;
            $devolucion->fecha_hora = $myTime->toDateTimeString();
            $devolucion->total = $request->total_dev;
            $devolucion->save();

            $venta = new Venta();
            $venta->total = $request->total;
            $venta->idcliente = $venta2->idcliente;
            $venta->idvendedor = $venta2->idvendedor;
            $venta->idusuario = \Auth::user()->id;
            $venta->tipo_comprobante = $venta2->tipo_comprobante;
            $venta->num_comprobante = $venta2->num_comprobante;
            $venta->fecha_hora = $myTime->toDateTimeString();
            $venta->impuesto = $request->impuesto;
            $venta->total = $request->total;
            $venta->pagado = $request->pagado;
            $venta->estado = 'Registrado';
            $venta->save();

            $detalles = $request->data; //Array de detalles

            foreach($detalles as $ep=>$det) {
                $detalle = new DetalleVenta();
                $detalleDev = new DevolucionDetalle();

                $detalleDev->iddevolucion = $devolucion->id;
                $detalleDev->idarticulo = $det['idarticulo'];
                $detalleDev->cantidad = $det['cantidad'];
                $detalleDev->save();

                $detalle->idventa = $venta->id;
                $detalle->idarticulo = $det['idarticulo'];
                $detalle->cantidad = $det['cantidadfinal'];
                $detalle->precio = $det['precio'];
                $detalle->descuento = $det['descuento'];
                $detalle->save();
            }

            DB::commit();
            return ['id' => $venta->id];
        } catch (Exception $e){
            DB::rollBack();
        }
    }
}

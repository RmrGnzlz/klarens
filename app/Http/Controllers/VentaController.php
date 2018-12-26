<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Venta;
use App\DetalleVenta;
use App\Persona;
use Carbon\Carbon;

class VentaController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        
        if ($buscar==''){
            $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
            ->join('users','ventas.idusuario','=','users.id')
            ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante','ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.pagado','ventas.estado','personas.nombre','users.usuario')
            ->orderBy('ventas.id', 'desc')->paginate(10);
        }
        else {
            if ($criterio == 'nombre') {
                $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                ->join('users','ventas.idusuario','=','users.id')
                ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante','ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.pagado','ventas.estado','personas.nombre','users.usuario')
                ->where('personas.'.$criterio, 'like', '%'. $buscar . '%')->orderBy('ventas.id', 'desc')->paginate(10);
            } else {
                $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                ->join('users','ventas.idusuario','=','users.id')
                ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante','ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.pagado','ventas.estado','personas.nombre','users.usuario')
                ->where('ventas.'.$criterio, 'like', '%'. $buscar . '%')->orderBy('ventas.id', 'desc')->paginate(10);
            }
        }
        
        return [
            'pagination' => [
                'total'        => $ventas->total(),
                'current_page' => $ventas->currentPage(),
                'per_page'     => $ventas->perPage(),
                'last_page'    => $ventas->lastPage(),
                'from'         => $ventas->firstItem(),
                'to'           => $ventas->lastItem(),
            ],
            'ventas' => $ventas
        ];
    }

    public function obtenerCabecera(Request $request) {
        if (!$request->ajax()) return redirect('/');

        $id = $request->id;

        $venta = Venta::join('personas','ventas.idcliente','=','personas.id')
        ->join('users','ventas.idusuario','=','users.id')
        ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante','ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.pagado','ventas.estado','personas.nombre','users.usuario')
        ->where('ventas.id','=',$id)
        ->orderBy('ventas.id', 'desc')->take(1)->get();

        return [
            'venta' => $venta
        ];
    }

    public function obtenerDetalles(Request $request) {
        if (!$request->ajax()) return redirect('/');

        $id = $request->id;

        $detalles = DetalleVenta::join('detalle_inventarios','detalle_ventas.iddetalle','=','detalle_inventarios.id')
        ->join('productos','detalle_inventarios.idproducto','productos.id')
        ->select('detalle_ventas.cantidad','detalle_ventas.precio','detalle_ventas.descuento','productos.nombre as articulo','detalle_ventas.iddetalle','detalle_ventas.id')
        ->where('detalle_ventas.idventa','=',$id)
        ->orderBy('detalle_ventas.id', 'desc')->get();

        return [
            'detalles' => $detalles
        ];
    }

    public function pdf(Request $request, $id) {
        $venta = Venta::findOrFail($request->id);
        $idvendedor = $venta->idvendedor;
        $vendedor = Persona::findOrFail($idvendedor);
        $venta = Venta::join('personas','ventas.idcliente','=','personas.id')
        ->join('users','ventas.idusuario','=','users.id')
        ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante','ventas.created_at','ventas.impuesto','ventas.total', 'ventas.pagado','ventas.estado','personas.nombre','personas.tipo_documento','personas.num_documento','personas.direccion','personas.email','personas.telefono','users.usuario')
        ->where('ventas.id','=',$id)
        ->take(1)->get();
        
        $detalles = DetalleVenta::join('detalle_inventarios','detalle_ventas.iddetalle','=','detalle_inventarios.id')
        ->join('productos','productos.id','detalle_inventarios.idproducto')
        ->select('productos.id as articulo')
        ->select('productos.nombre as articulo')
        ->where('detalle_ventas.idventa','=',$id)
        ->groupBy('productos.id','productos.nombre')
        ->orderBy('detalle_ventas.id','desc')->get();

        return $detalles;
        $numVenta = Venta::select('num_comprobante', 'fecha_hora')->where('id',$id)->get();

        $pdf = \PDF::loadView('pdf.venta', ['vendedor' => $vendedor, 'venta' => $venta, 'detalles' => $detalles]);
        return $pdf->download('venta_'.$numVenta[0]->num_comprobante.'.pdf');
    }

    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        try{
            DB::beginTransaction();

            $myTime = Carbon::now('America/Bogota');

            $venta = new Venta();
            $venta->idcliente = $request->idcliente;
            if($request->idvendedor != 0) {
                $venta->idvendedor = $request->idvendedor;
            } else {
                $venta->idvendedor = \Auth::user()->id;
            }
            $venta->idusuario = \Auth::user()->id;
            $venta->tipo_comprobante = $request->tipo_comprobante;
            $venta->num_comprobante = $request->num_comprobante;
            $venta->fecha_hora = $myTime->toDateTimeString();
            $venta->impuesto = $request->impuesto;
            $venta->total = $request->total;
            $venta->pagado = $request->pagado;
            $venta->estado = '1';
            $venta->save();

            $detalles = $request->data; //Array de detalles

            foreach($detalles as $ep=>$det) {
                $detalle = new DetalleVenta();
                $detalle->idventa = $venta->id;
                $detalle->iddetalle = $det['idarticulo'];
                $detalle->cantidad = $det['cantidad'];
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

    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $venta = Venta::findOrFail($request->id);
        $venta->estado = '0';
        $venta->save();
    }
}

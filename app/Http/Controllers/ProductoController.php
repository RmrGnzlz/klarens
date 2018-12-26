<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Producto;

class ProductoController extends Controller
{
    public function index(Request $request)
    {
        // if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        
        if ($buscar==''){
            $productos = Producto::join('categorias','productos.idcategoria','=','categorias.id')
            ->select('productos.id','productos.idcategoria','productos.codigo','productos.nombre','categorias.nombre as nombre_categoria','productos.precio_venta','productos.stock','productos.descripcion','productos.estado')
            ->orderBy('productos.id', 'desc')->paginate(10);
        }
        else{
            $productos = Producto::join('categorias','productos.idcategoria','=','categorias.id')
            ->select('productos.id','productos.idcategoria','productos.codigo','productos.nombre','categorias.nombre as nombre_categoria','productos.precio_venta','productos.stock','productos.descripcion','productos.estado')
            ->where('productos.'.$criterio, 'like', '%'. $buscar . '%')
            ->orderBy('productos.id', 'desc')->paginate(10);
        }
        
        return [
            'pagination' => [
                'total'        => $productos->total(),
                'current_page' => $productos->currentPage(),
                'per_page'     => $productos->perPage(),
                'last_page'    => $productos->lastPage(),
                'from'         => $productos->firstItem(),
                'to'           => $productos->lastItem(),
            ],
            'productos' => $productos
        ];
    }

    public function listarProducto(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        
        if ($buscar==''){
            $productos = Producto::join('categorias','productos.idcategoria','=','categorias.id')
            ->select('productos.id','productos.idcategoria','productos.codigo','productos.nombre','categorias.nombre as nombre_categoria','productos.precio_venta','productos.stock','productos.descripcion','productos.estado')
            ->orderBy('productos.id', 'desc')->paginate(10);
        }
        else{
            $productos = Producto::join('categorias','productos.idcategoria','=','categorias.id')
            ->select('productos.id','productos.idcategoria','productos.codigo','productos.nombre','categorias.nombre as nombre_categoria','productos.precio_venta','productos.stock','productos.descripcion','productos.estado')
            ->where('productos.'.$criterio, 'like', '%'. $buscar . '%')
            ->orderBy('productos.id', 'desc')->paginate(10);
        }

        return [
            'articulos' => $productos
        ];
    }

    public function listarProductoVenta(Request $request)
    {
        // if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        
        $criterio = 'nombre';
        $buscar = 'leche';
        if ($buscar==''){
            $productos = Producto::join('detalle_inventarios','productos.id','=','detalle_inventarios.idproducto')
            ->join('inventarios','inventarios.id','=','detalle_inventarios.idinventario')
            ->join('categorias','categorias.id','=','productos.idcategoria')
            ->select('detalle_inventarios.id','productos.idcategoria','productos.codigo','productos.nombre','categorias.nombre as nombre_categoria','productos.precio_venta','productos.stock','productos.descripcion','productos.estado', 'detalle_inventarios.cantidad','inventarios.inicio')
            ->where('detalle_inventarios.cantidad','>',0)
            ->where('productos.estado','=','1')
            ->orderBy('productos.id', 'desc')->paginate(10);
        }
        else{
            $productos = Producto::join('detalle_inventarios','productos.id','=','detalle_inventarios.idproducto')
            ->join('inventarios','inventarios.id','=','detalle_inventarios.idinventario')
            ->join('categorias','categorias.id','=','productos.idcategoria')
            ->select('detalle_inventarios.id','productos.idcategoria','productos.codigo','productos.nombre','categorias.nombre as nombre_categoria','productos.precio_venta','productos.stock','productos.descripcion','productos.estado', 'detalle_inventarios.cantidad','inventarios.inicio')
            ->where('detalle_inventarios.cantidad','>',0)
            ->where('productos.estado','=','1')
            ->where('productos.'.$criterio, 'like', '%'. $buscar . '%')
            ->orderBy('productos.id', 'desc')->paginate(10);
        }

        return [
            'productos' => $productos
        ];
    }

    public function listarPdf() {
        $productos = Producto::join('categorias','productos.idcategoria','=','categorias.id')
        ->select('productos.id','productos.idcategoria','productos.codigo','productos.nombre','categorias.nombre as nombre_categoria','productos.precio_venta','productos.stock','productos.descripcion','productos.estado')
        ->orderBy('productos.nombre', 'desc')->get();
        $cont = Producto::count();

        $pdf = \PDF::loadView('pdf.productospdf', ['productos' => $productos, 'cont' => $cont]);
        return $pdf->download('productos.pdf');
    }

    public function buscarProducto(Request $request) {
        if (!$request->ajax()) return redirect('/');
        $filtro = $request->filtro;
        $articulos = Producto::where('codigo','=',$filtro)->select('id','nombre')->orderBy('nombre', 'asc')->take(1)->get();

        return ['articulos' => $articulos];
    }

    public function buscarProductoVenta(Request $request) {
        if (!$request->ajax()) return redirect('/');
        $filtro = $request->filtro;
        $articulos = Producto::where('codigo','=',$filtro)->select('id','nombre','stock','precio_venta')->where('stock','>','0')
        ->orderBy('nombre', 'asc')->take(1)->get();

        return ['articulos' => $articulos];
    }
    
    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $producto = new Producto();
        $producto->idcategoria = $request->idcategoria;
        $producto->codigo = $request->codigo;
        $producto->nombre = $request->nombre;
        $producto->precio_venta = $request->precio_venta;
        $producto->stock = $request->stock;
        $producto->descripcion = $request->descripcion;
        $producto->estado = '1';
        $producto->save();
    }
    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $producto = Producto::findOrFail($request->id);
        $producto->idcategoria = $request->idcategoria;
        $producto->codigo = $request->codigo;
        $producto->nombre = $request->nombre;
        $producto->precio_venta = $request->precio_venta;
        $producto->stock = $request->stock;
        $producto->descripcion = $request->descripcion;
        $producto->estado = '1';
        $producto->save();
    }

    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $producto = Producto::findOrFail($request->id);
        $producto->estado = '0';
        $producto->save();
    }

    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $producto = Producto::findOrFail($request->id);
        $producto->estado = '1';
        $producto->save();
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Persona;

class VendedorController extends Controller
{
    public function selectVendedor(Request $request) {
        // if (!$request->ajax()) return redirect('/');

        $filtro = $request->filtro;
        $vendedores = Persona::join('users','personas.id','=','users.id')
        ->select('personas.id','nombre','num_documento')
        ->where('nombre','like', '%'.$filtro.'%')
        ->orWhere('num_documento','like', '%'.$filtro.'%')
        ->orderBy('nombre','asc')->get();

        return ['vendedores' => $vendedores];
    }
}

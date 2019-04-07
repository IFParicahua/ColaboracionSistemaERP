<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NotaController extends Controller
{
    public function index()
    {
        $id = Session('emp-id');
        $notas = DB::table('notas')->where([['PkEmpresa', '=', $id],['Tipo','=', 1]])->orderBy('Fecha')->get();
        return view('compra', ['notas' => $notas]);
    }

    public function redictcompra($id){
        session()->put('nota-lote-id', $id);
        return redirect('ventaedit');
    }
    //Vista Nota-Venta

    public function vista()
    {
        $id = Session('emp-id');
        $notas = DB::table('notas')->where([['PkEmpresa', '=', $id],['Tipo','=', 2]])->orderBy('Fecha')->get();
        return view('venta', ['notas' => $notas]);
    }

    public function redirectVenta($id){
        session()->put('nota-detalle-id', $id);
        return redirect('nota-detalle');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LibroDiarioController extends Controller
{
    public function index()

    {
        $id = Session('emp-id');
        $gestiones = DB::table('gestion')->where('PkEmpresa','=',$id)->orderBy('Nombre')->get();
        return view('librodiario',['gestiones'=>$gestiones]);
    }

    public function fetch(Request $request)
    {

        if($request->get('query'))
        {
            $query = $request->get('query');
            $periodos = DB::table('periodo')->where('PkGestion', '=', $query)->orderBy('FechaInicio')->get();
            $output = '';
            foreach($periodos as $row)
            {
                $output .= '<option value='.$row->id.'>'.$row->Nombre.'';
            }
            echo json_encode($output);
        }

    }

    public function sumaysaldo()
    {
        $id = Session('emp-id');
        $gestiones = DB::table('gestion')->where('PkEmpresa', '=', $id)->orderBy('Nombre')->get();
        return view('sumsaldo',['gestiones' => $gestiones]);
    }

    public function estadoresultado()
    {
        $id = Session('emp-id');
        $gestiones = DB::table('gestion')->where('PkEmpresa', '=', $id)->orderBy('Nombre')->get();
        return view('resultado',['gestiones' => $gestiones]);
    }

    public function balancegeneral()
    {
        $id = Session('emp-id');
        $gestiones = DB::table('gestion')->where('PkEmpresa', '=', $id)->orderBy('Nombre')->get();
        return view('balancegeneral',['gestiones' => $gestiones]);
    }

    public function balanceinicial()
    {
        $id = Session('emp-id');
        $gestiones = DB::table('gestion')->where('PkEmpresa', '=', $id)->orderBy('Nombre')->get();

        return view('balanceinicial',['gestiones' => $gestiones]);
    }

    public function lbmayor(){
        $id = Session('emp-id');
        $gestiones = DB::table('gestion')->where('PkEmpresa', '=', $id)->orderBy('Nombre')->get();

        return view('libromayor',['gestiones' => $gestiones]);
    }


}

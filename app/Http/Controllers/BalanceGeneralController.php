<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BalanceGeneralController extends Controller
{
    public function index()

    {
        $id = Session('emp-id');
        $gestiones = DB::table('gestion')->where('PkEmpresa','=',$id)->orderBy('Nombre')->get();
        return view('balancegeneral',['gestiones'=>$gestiones]);
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
}

<?php

namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Faker\Provider\DateTime;
use Illuminate\Database\Seeder;
use Illuminate\Validation\Rule;

class MonedaController extends Controller
{

    public function index()
    {
        $id = Session('emp-id');
        $monedas=  DB::table('moneda')->get();
        $mnempresas= DB::select('SELECT  t1.Activo,t1.FechaRegistro,t1.Cambio, (t2.Nombre)AS principal, (t3.Nombre)AS alternativa, (t2.id)AS idprincipal,(t3.id)AS idalternativa FROM monedaempresa t1 INNER JOIN moneda t2 ON  t1.Principal=t2.id left JOIN moneda t3 ON  t1.Alternativa=t3.id WHERE t1.PkEmpresa=? ORDER BY t1.FechaRegistro desc ;',[Session('emp-id')]);
        return view('moneda', ['mnempresas' => $mnempresas],['monedas' => $monedas]);

    }

    public function create(Request $request){

        date_default_timezone_set('America/La_Paz');
        $fechaActual= date("Y-m-d H:i:s");
        DB::table('monedaempresa')->where([['Activo', 1],['PkEmpresa', '=',Session('emp-id')]])
            ->update([
                    'Activo' => 2,
                ]
            );
        DB::table('monedaempresa')->insert([
                'Activo'=>1,
                'FechaRegistro'=>$fechaActual,
                'PkEmpresa'=>Session('emp-id'),
                'Principal'=>$request->input('principal'),
                'Alternativa'=>$request->input('alternativa'),
                'Cambio'=>$request->input('cambio'),
                'user_id'=>Auth::user()->id
            ]
        );

        //echo "Fecha Actual=".$fechaActual;
        return back()->with('error_code', 0);
    }
}

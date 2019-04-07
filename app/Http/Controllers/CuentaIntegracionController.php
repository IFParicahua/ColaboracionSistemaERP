<?php

namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Faker\Provider\DateTime;
use Illuminate\Database\Seeder;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;


class CuentaIntegracionController extends Controller
{
    public function index()
    {
        $integraciones = DB::table ('integracion')->where('PkEmpresa', '=', Session('emp-id'))->get();
        $cuentas = DB::table ('cuenta')->get();
        return view('cuentaintegracion', ['integraciones' => $integraciones],['cuentas' =>$cuentas]);
    }

    public function create(Request $request)
    {
        $var = DB::table('integracion')->where('PkEmpresa', '=', Session('emp-id'))->value('id_integracion');
        if($var > 0){
            DB:: table ('integracion')->where('PkEmpresa' ,'=', Session('emp-id'))->update([
                'Caja'=>$request->input('country_name_n'),
                'CreditoFiscal'=>$request->input('credito_fiscal_m'),
                'DebitoFiscal'=>$request->input('dedito_fiscal_n'),
                'Compras'=>$request->input('Compra_m'),
                'Ventas'=>$request->input('Venta_m'),
                'IT'=>$request->input('IT_m'),
                'ITporPagar'=>$request->input('ITxP_m'),
                'PkEmpresa'=>Session('emp-id'),
                'Activo'=>1,
                'user_id' =>Auth::user()->id

            ]);
        }
        else{
            DB:: table ('integracion')->insert([
                'Caja'=>$request->input('country_name_n'),
                'CreditoFiscal'=>$request->input('credito_fiscal_m'),
                'DebitoFiscal'=>$request->input('dedito_fiscal_n'),
                'Compras'=>$request->input('Compra_m'),
                'Ventas'=>$request->input('Venta_m'),
                'IT'=>$request->input('IT_m'),
                'ITporPagar'=>$request->input('ITxP_m'),
                'PkEmpresa'=>Session('emp-id'),
                'Activo'=>1,
                'user_id' =>Auth::user()->id

            ]);
        }

        return back()->with('error_code', 0);
    }

    function fetch(Request $request)
    {
        if ($request->get('query')) {
            $query = $request->get('query');
            $data = DB::table('cuenta')
                ->join('empresa', 'empresa.id', '=', 'cuenta.PkEmpresa')
                ->where([['PkEmpresa', '=',Session('emp-id')],['Nombre', 'LIKE', "%{$query}%"],['cuenta.Nivel', '=', Session('emp-Nivel')]])
                ->orWhere([['PkEmpresa', '=',Session('emp-id')],['Codigo', 'like', '%'.$query.'%'],['cuenta.Nivel', '=', Session('emp-Nivel')]])
                ->get();
            $output = '<ul class="dropdown-menu" style="display:block; position:relative">';
            foreach ($data as $row) {
                $output .= '<li class="pl-1 caja" id="'. $row->id_cuenta .'"><a href="#" style="color: #1b1e21">' . $row->Codigo . ' - ' . $row->Nombre . '</a></li>';
            }
            $output .= '</ul>';
            echo $output;
        }
    }

    function fetch1(Request $request)
    {
        if ($request->get('query')) {
            $query = $request->get('query');
            $data = DB::table('cuenta')
                ->join('empresa', 'empresa.id', '=', 'cuenta.PkEmpresa')
                ->where([['PkEmpresa', '=',Session('emp-id')],['Nombre', 'LIKE', "%{$query}%"],['cuenta.Nivel', '=', Session('emp-Nivel')]])
                ->orWhere([['PkEmpresa', '=',Session('emp-id')],['Codigo', 'like', '%'.$query.'%'],['cuenta.Nivel', '=', Session('emp-Nivel')]])
                ->get();
            $output = '<ul class="dropdown-menu" style="display:block; position:relative">';
            foreach ($data as $row) {
                $output .= '<li class="pl-1 credito" id="'. $row->id_cuenta .'"><a href="#" style="color: #1b1e21">' . $row->Codigo . ' - ' . $row->Nombre . '</a></li>';
            }
            $output .= '</ul>';
            echo $output;
        }
    }
    function fetch2(Request $request)
    {
        if ($request->get('query')) {
            $query = $request->get('query');
            $data = DB::table('cuenta')
                ->join('empresa', 'empresa.id', '=', 'cuenta.PkEmpresa')
                ->where([['PkEmpresa', '=',Session('emp-id')],['Nombre', 'LIKE', "%{$query}%"],['cuenta.Nivel', '=', Session('emp-Nivel')]])
                ->orWhere([['PkEmpresa', '=',Session('emp-id')],['Codigo', 'like', '%'.$query.'%'],['cuenta.Nivel', '=', Session('emp-Nivel')]])
                ->get();
            $output = '<ul class="dropdown-menu" style="display:block; position:relative">';
            foreach ($data as $row) {
                $output .= '<li class="pl-1 dedito" id="'. $row->id_cuenta .'"><a href="#" style="color: #1b1e21">' . $row->Codigo . ' - ' . $row->Nombre . '</a></li>';
            }
            $output .= '</ul>';
            echo $output;
        }
    }

    function fetch3(Request $request)
    {
        if ($request->get('query')) {
            $query = $request->get('query');
            $data = DB::table('cuenta')
                ->join('empresa', 'empresa.id', '=', 'cuenta.PkEmpresa')
                ->where([['PkEmpresa', '=',Session('emp-id')],['Nombre', 'LIKE', "%{$query}%"],['cuenta.Nivel', '=', Session('emp-Nivel')]])
                ->orWhere([['PkEmpresa', '=',Session('emp-id')],['Codigo', 'like', '%'.$query.'%'],['cuenta.Nivel', '=', Session('emp-Nivel')]])
                ->get();
            $output = '<ul class="dropdown-menu" style="display:block; position:relative">';
            foreach ($data as $row) {
                $output .= '<li class="pl-1 Compra" id="'.$row->id_cuenta.'"><a href="#" style="color: #1b1e21">' . $row->Codigo . ' - ' . $row->Nombre . '</a></li>';
            }
            $output .= '</ul>';
            echo $output;
        }
    }
    function fetch4(Request $request)
    {
        if ($request->get('query')) {
            $query = $request->get('query');
            $data = DB::table('cuenta')
                ->join('empresa', 'empresa.id', '=', 'cuenta.PkEmpresa')
                ->where([['PkEmpresa', '=',Session('emp-id')],['Nombre', 'LIKE', "%{$query}%"],['cuenta.Nivel', '=', Session('emp-Nivel')]])
                ->orWhere([['PkEmpresa', '=',Session('emp-id')],['Codigo', 'like', '%'.$query.'%'],['cuenta.Nivel', '=', Session('emp-Nivel')]])
                ->get();
            $output = '<ul class="dropdown-menu" style="display:block; position:relative">';
            foreach ($data as $row) {
                $output .= '<li class="pl-1 Venta" id="'. $row->id_cuenta .'"><a href="#" style="color: #1b1e21">' . $row->Codigo . ' - ' . $row->Nombre . '</a></li>';
            }
            $output .= '</ul>';
            echo $output;
        }
    }
    function fetch5(Request $request)
    {
        if ($request->get('query')) {
            $query = $request->get('query');
            $data = DB::table('cuenta')
                ->join('empresa', 'empresa.id', '=', 'cuenta.PkEmpresa')
                ->where([['PkEmpresa', '=',Session('emp-id')],['Nombre', 'LIKE', "%{$query}%"],['cuenta.Nivel', '=', Session('emp-Nivel')]])
                ->orWhere([['PkEmpresa', '=',Session('emp-id')],['Codigo', 'like', '%'.$query.'%'],['cuenta.Nivel', '=', Session('emp-Nivel')]])
                ->get();
            $output = '<ul class="dropdown-menu" style="display:block; position:relative">';
            foreach ($data as $row) {
                $output .= '<li class="pl-1 IT" id="'. $row->id_cuenta .'"><a href="#" style="color: #1b1e21">' . $row->Codigo . ' - ' . $row->Nombre . '</a></li>';
            }
            $output .= '</ul>';
            echo $output;
        }
    }
    function fetch6(Request $request)
    {
        if ($request->get('query')) {
            $query = $request->get('query');
            $data = DB::table('cuenta')
                ->join('empresa', 'empresa.id', '=', 'cuenta.PkEmpresa')
                ->where([['PkEmpresa', '=',Session('emp-id')],['Nombre', 'LIKE', "%{$query}%"],['cuenta.Nivel', '=', Session('emp-Nivel')]])
                ->orWhere([['PkEmpresa', '=',Session('emp-id')],['Codigo', 'like', '%'.$query.'%'],['cuenta.Nivel', '=', Session('emp-Nivel')]])
                ->get();
            $output = '<ul class="dropdown-menu" style="display:block; position:relative">';
            foreach ($data as $row) {
                $output .= '<li class="pl-1 ITxP" id="'. $row->id_cuenta .'"><a href="#" style="color: #1b1e21">' . $row->Codigo . ' - ' . $row->Nombre . '</a></li>';
            }
            $output .= '</ul>';
            echo $output;
        }
    }

    public function actualizar(Request $request){
        $query = $request->get('query');
        if ($query == 'true'){
            $estado = 1;
        }else{
            $estado = 0;
        }
        DB:: table ('integracion')->where('PkEmpresa', Session('emp-id'))->update([
            'Activo'=>$estado,
        ]);
        echo $estado;
    }

    function fetchEdit(Request $request)
    {
        $IDcaja = DB::table ('integracion')->where('PkEmpresa', '=', Session('emp-id'))->value('Caja');
        $IDcredito = DB::table ('integracion')->where('PkEmpresa', '=', Session('emp-id'))->value('CreditoFiscal');
        $IDdebito = DB::table ('integracion')->where('PkEmpresa', '=', Session('emp-id'))->value('DebitoFiscal');
        $IDcompra = DB::table ('integracion')->where('PkEmpresa', '=', Session('emp-id'))->value('Compras');
        $IDventa = DB::table ('integracion')->where('PkEmpresa', '=', Session('emp-id'))->value('Ventas');
        $IDit = DB::table ('integracion')->where('PkEmpresa', '=', Session('emp-id'))->value('IT');
        $IDitxpagar = DB::table ('integracion')->where('PkEmpresa', '=', Session('emp-id'))->value('ITporPagar');

        $Ccaja = DB::table('integracion')->join('cuenta', 'cuenta.id_cuenta', '=', 'integracion.Caja')->where('integracion.PkEmpresa', '=', Session('emp-id'))->value('cuenta.Codigo');
        $Ccredito = DB::table('integracion')->join('cuenta', 'cuenta.id_cuenta', '=', 'integracion.CreditoFiscal')->where('integracion.PkEmpresa', '=', Session('emp-id'))->value('cuenta.Codigo');
        $Cdebito = DB::table('integracion')->join('cuenta', 'cuenta.id_cuenta', '=', 'integracion.DebitoFiscal')->where('integracion.PkEmpresa', '=', Session('emp-id'))->value('cuenta.Codigo');
        $Ccompra = DB::table('integracion')->join('cuenta', 'cuenta.id_cuenta', '=', 'integracion.Compras')->where('integracion.PkEmpresa', '=', Session('emp-id'))->value('cuenta.Codigo');
        $Cventa = DB::table('integracion')->join('cuenta', 'cuenta.id_cuenta', '=', 'integracion.Ventas')->where('integracion.PkEmpresa', '=', Session('emp-id'))->value('cuenta.Codigo');
        $Cit = DB::table('integracion')->join('cuenta', 'cuenta.id_cuenta', '=', 'integracion.IT')->where('integracion.PkEmpresa', '=', Session('emp-id'))->value('cuenta.Codigo');
        $Citxpagar = DB::table('integracion')->join('cuenta', 'cuenta.id_cuenta', '=', 'integracion.ITporPagar')->where('integracion.PkEmpresa', '=', Session('emp-id'))->value('cuenta.Codigo');

        $Ncaja = DB::table('integracion')->join('cuenta', 'cuenta.id_cuenta', '=', 'integracion.Caja')->where('integracion.PkEmpresa', '=', Session('emp-id'))->value('cuenta.Nombre');
        $Ncredito = DB::table('integracion')->join('cuenta', 'cuenta.id_cuenta', '=', 'integracion.CreditoFiscal')->where('integracion.PkEmpresa', '=', Session('emp-id'))->value('cuenta.Nombre');
        $Ndebito = DB::table('integracion')->join('cuenta', 'cuenta.id_cuenta', '=', 'integracion.DebitoFiscal')->where('integracion.PkEmpresa', '=', Session('emp-id'))->value('cuenta.Nombre');
        $Ncompra = DB::table('integracion')->join('cuenta', 'cuenta.id_cuenta', '=', 'integracion.Compras')->where('integracion.PkEmpresa', '=', Session('emp-id'))->value('cuenta.Nombre');
        $Nventa = DB::table('integracion')->join('cuenta', 'cuenta.id_cuenta', '=', 'integracion.Ventas')->where('integracion.PkEmpresa', '=', Session('emp-id'))->value('cuenta.Nombre');
        $Nit = DB::table('integracion')->join('cuenta', 'cuenta.id_cuenta', '=', 'integracion.IT')->where('integracion.PkEmpresa', '=', Session('emp-id'))->value('cuenta.Nombre');
        $Nitxpagar = DB::table('integracion')->join('cuenta', 'cuenta.id_cuenta', '=', 'integracion.ITporPagar')->where('integracion.PkEmpresa', '=', Session('emp-id'))->value('cuenta.Nombre');

        $output= $IDcaja.','.$IDcredito.','.$IDdebito.','.$IDcompra.','.$IDventa.','.$IDit.','.$IDitxpagar.','.$Ccaja.' - '.$Ncaja.','.$Ccredito.' - '. $Ncredito.','.$Cdebito.' - '.$Ndebito.','.$Ccompra.' - '.$Ncompra.','.$Cventa.' - '.$Nventa.','.$Cit.' - '.$Nit.','.$Citxpagar.' - '.$Nitxpagar;
        echo $output;
    }
}


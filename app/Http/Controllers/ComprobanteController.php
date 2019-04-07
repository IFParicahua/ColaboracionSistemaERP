<?php

namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;


class ComprobanteController extends Controller
{
    public function index()
    {

        return view('comprobante');

    }

    public function indexnuevo()
    {
        return view('comprobantenew');
    }


    function action(Request $request)
    {
        if($request->ajax())
        {
            $output = '';
            $query = $request->get('query');
            if($query != '')
            {
                $data = DB::table('comprobante')
                    ->join('concepto', 'concepto.id', '=', 'comprobante.Estado')
                    ->join('moneda', 'moneda.id', '=', 'comprobante.PkMoneda')
                    ->where([['PkEmpresa', '=',Session('emp-id')],['Serie', 'like', '%'.$query.'%']])
                    ->orWhere([['PkEmpresa', '=',Session('emp-id')],['Fecha', 'like', '%'.$query.'%']])
                    ->orWhere([['PkEmpresa', '=',Session('emp-id')],['TipoComprobante', 'like', '%'.$query.'%']])
                    ->orWhere([['PkEmpresa', '=',Session('emp-id')],['Nombres.concepto', 'like', '%'.$query.'%']])
                    ->orderBy('Serie', 'asc')
                    ->get();
            }
            else
            {
                $data = DB::table('comprobante')
                    ->join('concepto', 'concepto.id', '=', 'comprobante.Estado')
                    ->join('moneda', 'moneda.id', '=', 'comprobante.PkMoneda')
                    ->where('PkEmpresa', '=', Session('emp-id'))
                    ->orderBy('Serie', 'asc')
                    ->get();
            }
            $total_row = $data->count();
            if($total_row > 0)
            {
                foreach($data as $row)
                {
                    $output .= '<tr><td>'.$row->Serie.'</td><td>'.$row->Glosa.'</td><td>'.$row->TipoComprobante.'</td><td>'.date("d/m/Y",strtotime($row->Fecha)).'</td><td>'.$row->Nombres.'</td><td><a href="detalle/'.$row->id_comprobante.'" class="btn btn-primary btn-fill fa fa-pencil" ></a></td></tr>';
                }
            }
            else
            {
                $output = '<tr><td align="center" colspan="5">No se encontraron datos</td></tr>';
            }
            $data = array(
                'table_data'  => $output,
                'total_data'  => $total_row
            );

            echo json_encode($data);
        }
    }

    function crear(Request $request){
        $fecha = $request->input('fecha');
        $tipo = $request->input('tipocomprobante');

        if ($fecha != 0){
            if ($tipo == 'Apertura'){
                $fechaInicio = DB::table('gestion')->where([['FechaInicio','<',$fecha],['FechaFin','>',$fecha], ['PkEmpresa' ,'=',Session('emp-id')]])->value('FechaInicio');
                $fechaFin = DB::table('gestion')->where([['FechaInicio','<',$fecha],['FechaFin','>',$fecha], ['PkEmpresa' ,'=',Session('emp-id')]])->value('FechaFin');
                $data= DB::table('periodo')->where([['FechaInicio','<=',$fecha],['FechaFin','>=',$fecha], ['PkEmpresa' ,'=',Session('emp-id')]])->value('id');
                $comprobante = DB::table('comprobante')->where([['Fecha','>',$fechaInicio],['Fecha','<',$fechaFin], ['PkEmpresa' ,'=',Session('emp-id')], ['TipoComprobante' ,'=', 'Apertura']])->value('id_comprobante');
                $tp = 1;
            }else{
                $data= DB::table('periodo')->where([['FechaInicio','<=',$fecha],['FechaFin','>=',$fecha], ['PkEmpresa' ,'=',Session('emp-id')]])->value('id');
                $tp = 0;
            }
        }else{
            $data = 1;
            $tp = 0;
        }

        if ($tp == 1){
            if ($comprobante != 0){
                $validator = Validator::make($request->all(), [
                    'fecha' => 'required',
                    'tipocomprobante' => 'required|numeric',
                    'Glosa_comprobante' => 'required',
                    'det_cuenta' => 'required',
                ],[
                    'fecha.required' => 'La fecha es requerida',
                    'tipocomprobante.required' => 'El tipo de cambio es obligatorio.',
                    'tipocomprobante.numeric' => 'Ya existe un comprobante de apertura.',
                    'Glosa_comprobante.required' => 'El campo Glosa es obligatorio.',
                    'det_cuenta.required' => 'Debe agregar datos al  detalle',
                ]);
            }else{
                $validator = Validator::make($request->all(), [
                    'fecha' => 'required | after:'.$fecha,
                    'tipocomprobante' => 'required',
                    'Glosa_comprobante' => 'required',
                    'det_cuenta' => 'required',
                ],[
                    'fecha.required' => 'La fecha es requerida',
                    'fecha.after' => 'Fecha fuera de rango',
                    'tipocomprobante.required' => 'El tipo de cambio es obligatorio.',
                    'Glosa_comprobante.required' => 'El campo Glosa es obligatorio.',
                    'det_cuenta.required' => 'Debe agregar datos al  detalle',
                ]);
            }
        }else{
            if ($data == 0){
                $validator = Validator::make($request->all(), [
                    'fecha' => 'required | after:'.$fecha,
                    'tipocomprobante' => 'required',
                    'Glosa_comprobante' => 'required',
                    'det_cuenta' => 'required',
                ],[
                    'fecha.required' => 'La fecha es requerida',
                    'fecha.after' => 'Fecha fuera de rango',
                    'tipocomprobante.required' => 'El tipo de cambio es obligatorio.',
                    'Glosa_comprobante.required' => 'El campo Glosa es obligatorio.',
                    'det_cuenta.required' => 'Debe agregar datos al  detalle',
                ]);
            }else{
                $validator = Validator::make($request->all(), [
                    'fecha' => 'required',
                    'tipocomprobante' => 'required',
                    'Glosa_comprobante' => 'required',
                    'det_cuenta' => 'required',
                ],[
                    'fecha.required' => 'La fecha es requerida',
                    'tipocomprobante.required' => 'El tipo de cambio es obligatorio.',
                    'Glosa_comprobante.required' => 'El campo Glosa es obligatorio.',
                    'det_cuenta.required' => 'Debe agregar datos al  detalle',
                ]);
            }
        }

        if ($validator->fails()) {
            return redirect('/comprobantenew')
                ->with('error_code')
                ->withErrors($validator)
                ->withInput();
        }else {
            $resul = DB::table('comprobante')->where('PkEmpresa', '=', Session('emp-id'))->orderby('id_comprobante', 'DESC')->take(1)->value('Serie');
            $serie = $resul + 1;
            $id_moneda = $request->input('moneda');
            $tipo_cambio = $request->input('TC');

            DB::table('comprobante')->insert([
                    'Serie' => $serie,
                    'Glosa' => $request->input('Glosa_comprobante'),
                    'Fecha' => $request->input('fecha'),
                    'TC' => $request->input('TC'),
                    'TipoComprobante' => $request->input('tipocomprobante'),
                    'PkEmpresa' => Session('emp-id'),
                    'Estado' => '1',
                    'user_id' => Auth::user()->id,
                    'PkMoneda' => $request->input('moneda'),
                    'PkPeriodo' => Session('periodo-id')
                ]
            );
            $id_comprobante = DB::table('comprobante')->where([['PkEmpresa', '=', Session('emp-id')], ['Serie', '=', $serie]])->value('id_comprobante');
            $nuemeros = $request->input('det_nuemero');
            $cuentas = $request->input('det_cuenta');
            $glosas = $request->input('det_glosa');
            $debes = $request->input('det_debe');
            $haberes = $request->input('det_haber');
            $cant = $request->input('det_cant');
            //Separo en array
            $array_numero = explode(",", $nuemeros);
            $array_cuenta = explode(",", $cuentas);
            $array_glosa = explode(",", $glosas);
            $array_debe = explode(",", $debes);
            $array_haber = explode(",", $haberes);

            $moneda_alternativa = DB::table('monedaempresa')->where([['PkEmpresa', '=', Session('emp-id')], ['Principal', '=', $id_moneda]])->value('id');
            $moneda_principal = DB::table('monedaempresa')->where([['PkEmpresa', '=', Session('emp-id')], ['Alternativa', '=', $id_moneda]])->value('id');

            if ($moneda_principal > 0) {
                for ($i = 0; $i < $cant; $i++) {
                    $array_debe_alt[$i] = $array_debe[$i];
                    $array_haber_alt[$i] = $array_haber[$i];

                    $array_debe_pr[$i] = $array_debe[$i] * $tipo_cambio;
                    $array_haber_pr[$i] = $array_haber[$i] * $tipo_cambio;
                }
            } elseif ($moneda_alternativa > 0) {
                for ($i = 0; $i < $cant; $i++) {
                    $array_debe_pr[$i] = $array_debe[$i];
                    $array_haber_pr[$i] = $array_haber[$i];

                    $array_debe_alt[$i] = $array_debe[$i] / $tipo_cambio;
                    $array_haber_alt[$i] = $array_haber[$i] / $tipo_cambio;
                }
            }

            for ($i = 0; $i < $cant; $i++) {
                $id_cuenta = $array_cuenta[$i];
                $array_idcuenta = explode(" ", $id_cuenta);
                $res = $array_idcuenta[0];
                $IdArrayCuenta[$i] = DB::table('cuenta')->where([['PkEmpresa', '=', Session('emp-id')], ['Codigo', '=', $res]])->value('id_cuenta');
            }


            for ($i = 0; $i < $cant; $i++) {
                DB::table('detallecomprobante')->insert([
                        'Numero' => $array_numero[$i],
                        'Glosa' => $array_glosa[$i],
                        'MontoDebe' => $array_debe[$i],
                        'MontoHaber' => $array_haber[$i],
                        'MontoDebeAlt' => $array_debe_alt[$i],
                        'MontoHaberAlt' => $array_haber_alt[$i],
                        'user_id' => Auth::user()->id,
                        'PkComprobante' => $id_comprobante,
                        'PkCuenta' => $IdArrayCuenta[$i],
                    ]
                );
            }
            return redirect('detalle/' . $id_comprobante);
        }

    }








}

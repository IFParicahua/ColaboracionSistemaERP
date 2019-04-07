<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class ComprobanteDetalleController extends Controller
{
    public function index()
    {
        $Comprobrantes = DB::table('comprobante')->join('concepto', 'concepto.id', '=', 'comprobante.Estado')->join('moneda', 'moneda.id', '=', 'comprobante.PkMoneda')->where('comprobante.id_comprobante', '=',  Session('det-com-id'))->get();
        return view('editardetalle',['Comprobantes'=>$Comprobrantes]);
    }

    public function redirect($id){
        session()->put('det-com-id', $id);
        DB::table('reporte')->where('id', 5)->update([
            'report_id' => $id]);
        return redirect('comprobantedetalle');
    }

    function fetch(Request $request)
    {
        if($request->get('query'))
        {
            $query = $request->get('query');
            $data = DB::table('cuenta')
                ->join('empresa', 'empresa.id', '=', 'cuenta.PkEmpresa')
                ->where([['PkEmpresa', '=',Session('emp-id')],['Nombre', 'LIKE', "%{$query}%"],['cuenta.Nivel', '=', Session('emp-Nivel')]])
                ->orWhere([['PkEmpresa', '=',Session('emp-id')],['Codigo', 'like', '%'.$query.'%'],['cuenta.Nivel', '=', Session('emp-Nivel')]])
                ->get();
            $output = '<ul class="dropdown-menu" style="display:block; position:relative">';
            foreach($data as $row)
            {
                $output .= '<li class="pl-1"><a href="#" style="color: #1b1e21">'.$row->Codigo.' - '.$row->Nombre.'</a></li>';
            }
            $output .= '</ul>';
            echo $output;
        }
    }

    public function validation(Request $request){
        if($request->get('query'))
        {
            $query = $request->get('query');
            $tipo = $request->get('tipo');
            $data= DB::table('periodo')->where([['FechaInicio','<=',$query],['FechaFin','>=',$query], ['PkEmpresa' ,'=',Session('emp-id')]])->value('id');
            session()->put('periodo-id', $data);
            $fechaInicio = DB::table('gestion')->where([['FechaInicio','<',$query],['FechaFin','>',$query], ['PkEmpresa' ,'=',Session('emp-id')]])->value('FechaInicio');
            $fechaFin = DB::table('gestion')->where([['FechaInicio','<',$query],['FechaFin','>',$query], ['PkEmpresa' ,'=',Session('emp-id')]])->value('FechaFin');

            if ($data == ""){
                $output = '2';
            }else{
                if ($tipo == '0'){
                    $output= '3';
                }else{
                    if ($tipo == 'Apertura'){
                        $comprobante = DB::table('comprobante')->where([['Fecha','>',$fechaInicio],['Fecha','<',$fechaFin], ['PkEmpresa' ,'=',Session('emp-id')], ['TipoComprobante' ,'=', 'Apertura']])->value('id_comprobante');
                        if ($comprobante != ""){
                            $output = '1';
                        }else{
                            $output = '0';
                        }
                    }else{
                        $output = '0';
                    }
                }
            }
            echo json_encode($output);
        }
    }

    function edit(Request $request){
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
            return back()
                ->with('error_code')
                ->withErrors($validator)
                ->withInput();
        }else {
            $tipo_cambio = $request->input('TC');
            $id_moneda = $request->input('moneda');
            $id_comprobante = $request->input('pk_comprobante');
            //Eliminar detalles
            DB::table('detallecomprobante')->where('PkComprobante', '=', $id_comprobante)->delete();

            DB::table('comprobante')->where('id_comprobante', $id_comprobante)->update([
                    'Glosa' => $request->input('Glosa_comprobante'),
                    'Fecha' => $request->input('fecha'),
                    'TC' => $tipo_cambio,
                    'TipoComprobante' => $request->input('tipocomprobante'),
                    'PkMoneda' => $id_moneda,
                    'PkPeriodo' => Session('periodo-id'),
                ]
            );


            //Recuperar Campos para Detalle Comprobante

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

                    $array_debe_pr[$i] = $array_debe[$i] / $tipo_cambio;
                    $array_haber_pr[$i] = $array_haber[$i] / $tipo_cambio;
                }
            } elseif ($moneda_alternativa > 0) {
                for ($i = 0; $i < $cant; $i++) {
                    $array_debe_pr[$i] = $array_debe[$i];
                    $array_haber_pr[$i] = $array_haber[$i];

                    $array_debe_alt[$i] = $array_debe[$i] * $tipo_cambio;
                    $array_haber_alt[$i] = $array_haber[$i] * $tipo_cambio;
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
                        'MontoDebe' => $array_debe_pr[$i],
                        'MontoHaber' => $array_haber_pr[$i],
                        'MontoDebeAlt' => $array_debe_alt[$i],
                        'MontoHaberAlt' => $array_haber_alt[$i],
                        'user_id' => Auth::user()->id,
                        'PkComprobante' => $id_comprobante,
                        'PkCuenta' => $IdArrayCuenta[$i],
                    ]
                );
            }
            return redirect('comprobantedetalle');
        }
    }

    public function anular($id){
        try{
            DB::table('comprobante')->where('id_comprobante', $id)->update([
                    'Estado' => 3,
                ]
            );
            return back();
        }
        catch (QueryException $e){
            $notificacion = array(
                'message' => 'No se puede anular este comprobante',
                'alert-type' => 'error'
            );
            return redirect('/comprobantedetalle')->with($notificacion);
        }
    }
}

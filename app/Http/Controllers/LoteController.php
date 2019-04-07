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

class LoteController extends Controller
{
    public function viewNuevo(){
        return view('compranew');
    }

    function fetch(Request $request)
    {
        if($request->get('query'))
        {
            $query = $request->get('query');
            $data = DB::table('articulos')
                ->where([['PkEmpresa', '=',Session('emp-id')],['nombre', 'LIKE', "%{$query}%"]])
                ->get();
            $output = '<ul class="dropdown-menu" style="display:block; position:relative">';
            foreach($data as $row)
            {
                $output .= '<li class="pl-1 crear" id="'.$row->id.'"><a href="#" style="color: #1b1e21">'.$row->Nombre.'</a></li>';
            }
            $output .= '</ul>';
            echo $output;
        }
    }
    function fetchs(Request $request)
    {
        if($request->get('query'))
        {
            $query = $request->get('query');
            $data = DB::table('articulos')
                ->where([['PkEmpresa', '=',Session('emp-id')],['Nombre', 'LIKE', "%{$query}%"]])
                ->get();
            $output = '<ul class="dropdown-menu" style="display:block; position:relative">';
            foreach($data as $row)
            {
                $output .= '<li class="pl-1 editar" id="'.$row->id.'"><a href="#" style="color: #1b1e21">'.$row->Nombre.'</a></li>';
            }
            $output .= '</ul>';
            echo $output;
        }
    }

    function articuloval(Request $request)
    {
        if($request->get('query'))
        {
            $query = $request->get('query');
            $data = DB::table('articulos')
                ->where([['PkEmpresa', '=',Session('emp-id')],['Nombre', '=', $query]])
                ->value('id');
            if ($data > 0 ){
                $output = 1;
            }else{
                $output = 2;
            }
            echo $output;
        }
    }

    function validarfecha(Request $request)
    {
        if($request->get('query'))
        {
            $query = $request->get('query');
            $data= DB::table('periodo')->where([['FechaInicio','<=',$query],['FechaFin','>=',$query], ['PkEmpresa' ,'=',Session('emp-id')]])->value('id');
            $activo = DB::table('integracion')->where('PkEmpresa', '=', Session('emp-id'))->value('Activo');
            if ($activo == 1){
                if ($data == ""){
                    $output = '2';
                }else{
                    $output = '1';
                }
            }else{
                $output = '1';
            }
            echo json_encode($output);
        }
    }

    public function crear(Request $request){
        $val_periodo = $request->input('val_periodo');
        if ($val_periodo < 2 ){
            $validator = Validator::make($request->all(), [
                'fecha_nota' => 'required',
                'Glosa_nota' => 'required',
                'det_articulo' => 'required',
            ],[
                'fecha_nota.required' => 'La fecha es requerida',
                'Glosa_nota.required' => 'La Glosa es requerida.',
                'det_articulo.required' => 'Debe agregar datos al  detalle.',
            ]);
        }else{
            $validator = Validator::make($request->all(), [
                'fecha_nota' => 'required',
                'Glosa_nota' => 'required',
                'det_articulo' => 'required',
                'val_periodo' => 'email',
            ],[
                'fecha_nota.required' => 'La fecha es requerida',
                'Glosa_nota.required' => 'La Glosa es requerida.',
                'det_articulo.required' => 'Debe agregar datos al  detalle.',
                'val_periodo.email' => 'La Fecha introducida no existe dentro de un periodo activo',
            ]);
        }

        if ($validator->fails()) {
            return redirect('/compranew')
                ->with('error_code')
                ->withErrors($validator)
                ->withInput();
        }else {

            $resul = DB::table('notas')->where([['PkEmpresa', '=', Session('emp-id')], ['Tipo', '=', 1]])->orderby('id', 'DESC')->take(1)->value('NroNota');
            $serie = $resul + 1;
            $fechaIngreso = $request->input('fecha_nota');
            $Total_Nota = $request->input('det_total');

            DB::table('notas')->insert([
                    'NroNota' => $serie,
                    'Fecha' => $fechaIngreso,
                    'Detalle' => $request->input('Glosa_nota'),
                    'Total' => $Total_Nota,
                    'Tipo' => 1,
                    'PkEmpresa' => Session('emp-id'),
                    'user_id' => Auth::user()->id,
                    'Estado' => 9,
                ]
            );

            $id_nota = DB::table('notas')->where([['PkEmpresa', '=', Session('emp-id')], ['NroNota', '=', $serie], ['Tipo', '=', 1]])->value('id');
            $articulos = $request->input('det_articulo');
            $precios = $request->input('det_precio');
            $cantidades = $request->input('det_cantidad');
            $vencimientos = $request->input('det_vencimiento');
            $cant = $request->input('det_cant');

            //Separo en array
            $array_articulo = explode(",", $articulos);
            $array_precio = explode(",", $precios);
            $array_cantidad = explode(",", $cantidades);
            $array_vencimiento = explode(",", $vencimientos);

            //Buscamos id - alamacenamos id en array
            for ($i = 0; $i < $cant; $i++) {
                $res = $array_articulo[$i];
                $IdArrayArticulo[$i] = DB::table('articulos')->where([['PKEmpresa', '=', Session('emp-id')], ['Nombre', '=', $res]])->value('id');
            }
            //Actualizamos cantidad de articulo
            for ($i = 0; $i < $cant; $i++) {
                $CantArt = DB::table('articulos')->where([['PkEmpresa', '=', Session('emp-id')], ['id', '=', $IdArrayArticulo[$i]]])->value('Cantidad');
                $totalSum = $CantArt + $array_cantidad[$i];

                $Num = DB::table('lotes')->where([['PkArticulo', '=', $IdArrayArticulo[$i]]])->orderby('id', 'DESC')->take(1)->value('NroLote');;
                $NumArticulo[$i] = $Num + 1;

                DB::table('articulos')->where('id', $IdArrayArticulo[$i])->update([
                        'Cantidad' => $totalSum,
                    ]
                );
            }

            //Guardar Lote
            for ($i = 0; $i < $cant; $i++) {
                if ($array_vencimiento[$i] == 0) {
                    DB::table('lotes')->insert([
                            'PkArticulo' => $IdArrayArticulo[$i],
                            'PkNota' => $id_nota,
                            'NroLote' => $NumArticulo[$i],
                            'FechaIngreso' => $request->input('fecha_nota'),
                            'Cantidad' => $array_cantidad[$i],
                            'PrecioCompra' => $array_precio[$i],
                            'Stock' => $array_cantidad[$i],
                            'Estado' => 9,
                        ]
                    );
                } else {
                    DB::table('lotes')->insert([
                            'PkArticulo' => $IdArrayArticulo[$i],
                            'PkNota' => $id_nota,
                            'NroLote' => $NumArticulo[$i],
                            'FechaIngreso' => $request->input('fecha_nota'),
                            'Cantidad' => $array_cantidad[$i],
                            'PrecioCompra' => $array_precio[$i],
                            'Stock' => $array_cantidad[$i],
                            'FechaVencimiento' => $array_vencimiento[$i],
                            'Estado' => 9,
                        ]
                    );
                }
            }
            $activo = DB::table('integracion')->where('PkEmpresa', '=', Session('emp-id'))->value('Activo');

            if ($activo == 1) {

                $com_ser = DB::table('comprobante')->where('PkEmpresa', '=', Session('emp-id'))->orderby('id_comprobante', 'DESC')->take(1)->value('Serie');
                $new_ser = $com_ser + 1;
                $TC = DB::table('monedaempresa')->where([['Activo', 1], ['PkEmpresa', '=', Session('emp-id')]])->value('Cambio');
                $principal = DB::table('monedaempresa')->where([['Activo', 1], ['PkEmpresa', '=', Session('emp-id')]])->value('Principal');

                $periodo_id = DB::table('periodo')->where([['FechaInicio', '<=', $fechaIngreso], ['FechaFin', '>=', $fechaIngreso], ['PkEmpresa', '=', Session('emp-id')]])->value('id');

                DB::table('comprobante')->insert([
                        'Serie' => $new_ser,
                        'Glosa' => "Compra de Mercaderias",
                        'Fecha' => $fechaIngreso,
                        'TC' => $TC,
                        'TipoComprobante' => "Egreso",
                        'PkEmpresa' => Session('emp-id'),
                        'Estado' => '1',
                        'user_id' => Auth::user()->id,
                        'PkMoneda' => $principal,
                        'PkPeriodo' => $periodo_id,
                    ]
                );
                $id_comprobante = DB::table('comprobante')->where([['PkEmpresa', '=', Session('emp-id')], ['Serie', '=', $new_ser]])->value('id_comprobante');

                $id_Compra = DB::table('integracion')->where([['PkEmpresa', '=', Session('emp-id')], ['Activo', '=', 1]])->value('Compras');
                $id_Credito = DB::table('integracion')->where([['PkEmpresa', '=', Session('emp-id')], ['Activo', '=', 1]])->value('CreditoFiscal');
                $id_Caja = DB::table('integracion')->where([['PkEmpresa', '=', Session('emp-id')], ['Activo', '=', 1]])->value('Caja');
                $Tot_porciento = ($Total_Nota * 0.13);
                $Tot_Compra = ($Total_Nota - $Tot_porciento);

                $Alt_Total = $Total_Nota * $TC;
                $Alt_Tot_porciento = $Tot_porciento * $TC;
                $Alt_Tot_Compra = $Tot_Compra * $TC;


                DB:: table('notas')->where('id', '=', $id_nota)->update([
                    'PkComprobante' => $id_comprobante,
                ]);

                DB::table('detallecomprobante')->insert([
                        'Numero' => 1,
                        'Glosa' => "Compra de Mercaderias",
                        'MontoDebe' => "0",
                        'MontoHaber' => $Total_Nota,
                        'MontoDebeAlt' => "0",
                        'MontoHaberAlt' => $Alt_Total,
                        'user_id' => Auth::user()->id,
                        'PkComprobante' => $id_comprobante,
                        'PkCuenta' => $id_Caja,

                    ]
                );
                DB::table('detallecomprobante')->insert([
                        'Numero' => 2,
                        'Glosa' => "Compra de Mercaderias",
                        'MontoDebe' => $Tot_porciento,
                        'MontoHaber' => "0",
                        'MontoHaberAlt' => "0",
                        'MontoDebeAlt' => $Alt_Tot_porciento,
                        'user_id' => Auth::user()->id,
                        'PkComprobante' => $id_comprobante,
                        'PkCuenta' => $id_Credito,

                    ]
                );
                DB::table('detallecomprobante')->insert([
                        'Numero' => 2,
                        'Glosa' => "Compra de Mercaderias",
                        'MontoDebe' => $Tot_Compra,
                        'MontoHaber' => "0",
                        'MontoHaberAlt' => "0",
                        'MontoDebeAlt' => $Alt_Tot_Compra,
                        'user_id' => Auth::user()->id,
                        'PkComprobante' => $id_comprobante,
                        'PkCuenta' => $id_Compra,

                    ]
                );
                return redirect('notacompra/'.$id_nota);
            } else {
                return back();
            }
        }
    }

    public function vistaEdit(){
        $lotes = DB::select('SELECT lotes.NroLote,articulos.Nombre,lotes.PkArticulo, lotes.PrecioCompra, lotes.Cantidad,(lotes.PrecioCompra * lotes.Cantidad)as SubTotal ,lotes.FechaVencimiento FROM notas INNER JOIN lotes on lotes.PkNota = notas.id INNER JOIN articulos ON lotes.PkArticulo = articulos.id WHERE notas.id =? ;',[Session('nota-lote-id')]);
        $notas = DB::table('notas')->where([['PkEmpresa', '=', Session('emp-id')],['id', '=',Session('nota-lote-id')]])->get();
        return view('compraedit', ['lotes' => $lotes],['notas' =>$notas]);
    }

    public function anular(Request $request){
        $id_nota=$request->input('id_nota');
        $articulos=$request->input('det_articulo');
        $cantidades=$request->input('det_cantidad');
        $cant=$request->input('det_cant');

        $lote_cantidad = DB:: table ('lotes')->where('id','=',$id_nota)->max('Cantidad');
        $lote_stock = DB::table('lotes')->where('id','=',$id_nota)->max('Stock');

        if($lote_cantidad > $lote_stock ){
            $nroNota = DB::table('notas')->where('id','=',$id_nota)->value('NroNota');
            $notificacion = array(
                'message' => 'No se puede anular la nota:'.$nroNota.'.Uno de sus lotes ya realizo una venta',
                'alert-type' => 'error'
            );
            return back()->with($notificacion);
        }else{

            DB:: table ('notas')->where('id','=',$id_nota)->update([
                'Estado'=>3,
            ]);
            $id_com = DB:: table ('notas')->where('id','=',$id_nota)->value('PkComprobante');

            if ($id_com > 0){
                DB::table('comprobante')->where('id_comprobante','=',$id_com)->update([
                    'Estado'=>3,
                ]);
            }
            //Actualizar Lote
            for ($i = 0; $i <$cant; $i++){

                DB::table('lotes')->where('PkNota','=',$id_nota)->update([
                    'Estado'=>3,
                ]);
            }

            //Separo en array
            $array_articulo = explode(",",$articulos);
            $array_cantidad = explode(",",$cantidades);

            //Buscamos id - almacenamos id en array
            for ($i = 0; $i <$cant; $i++){
                $res=$array_articulo[$i];
                $IdArrayArticulo[$i] = DB::table('articulos')->where([['PkEmpresa', '=', Session('emp-id')],['Nombre', '=',$res]])->value('id');
            }
            //Actualizamos cantidad de articulo
            for ($i = 0; $i <$cant; $i++){
                $CantArt = DB::table('articulos')->where([['PkEmpresa', '=', Session('emp-id')],['id', '=',$IdArrayArticulo[$i]]])->value('Cantidad');
                $totalSum = $CantArt - $array_cantidad[$i];

                DB::table('articulos')->where('id', $IdArrayArticulo[$i])->update([
                        'Cantidad' => $totalSum,
                    ]
                );
            }


            return back();
        }
    }
}

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

class DetalleVentaController extends Controller
{
    public function viewNuevo(){
        return view('ventanew');
    }

    public function crear(Request $request){
        $resul = DB::table('notas')->where([['PkEmpresa', '=', Session('emp-id')],['Tipo','=', 2]])->orderby('id','DESC')->take(1)->value('NroNota');
        $serie=$resul+1;
        $fechaIngreso = $request->input('fecha_nota');
        $Total_Nota = $request->input('det_total');

        DB::table('notas')->insert([
                'NroNota' => $serie,
                'Fecha' => $fechaIngreso,
                'Detalle' => $request->input('Glosa_nota'),
                'Total' => $Total_Nota,
                'Tipo' => 2,
                'PkEmpresa' => Session('emp-id'),
                'user_id'=>Auth::user()->id,
                'Estado'=>9,
            ]
        );

        $id_nota = DB::table('notas')->where([['PkEmpresa', '=', Session('emp-id')],['NroNota', '=',$serie],['Tipo','=', 2]])->value('id');
        $articulos=$request->input('det_articulo');
        $precios=$request->input('det_precio');
        $cantidades=$request->input('det_cantidad');
        $lotes=$request->input('det_lote');
        $cant=$request->input('det_cant');

        //Separo en array
        $array_articulo = explode(",",$articulos);
        $array_precio = explode(",",$precios);
        $array_cantidad = explode(",",$cantidades);
        $array_lote = explode(",",$lotes);

        //Buscamos id - alamacenamos id en array
        for ($i = 0; $i <$cant; $i++){
            $res=$array_articulo[$i];
            $IdArrayArticulo[$i] = DB::table('articulos')->where([['PkEmpresa', '=', Session('emp-id')],['Nombre', '=',$res]])->value('id');
        }
        //Actualizamos cantidad de articulo
        for ($i = 0; $i <$cant; $i++){
            $CantArt = DB::table('articulos')->where([['PkEmpresa', '=', Session('emp-id')],['id', '=',$IdArrayArticulo[$i]]])->value('Cantidad');
            $CantLot = DB::table('lotes')->where('id', '=',$array_lote[$i])->value('Stock');
            $totalArt = $CantArt - $array_cantidad[$i];
            $totalLot = $CantLot - $array_cantidad[$i];
            DB::table('articulos')->where('id', $IdArrayArticulo[$i])->update([
                    'Cantidad' => $totalArt,
                ]
            );
            DB::table('lotes')->where('id', $array_lote[$i])->update([
                    'Stock' => $totalLot,
                ]
            );
        }

        //Guardar Lote
        for ($i = 0; $i <$cant; $i++){
            DB::table('detalleventas')->insert([
                    'PkNota' => $id_nota,
                    'NroLote' => $array_lote[$i],
                    'Cantidad'=>$array_cantidad[$i],
                    'PrecioVenta'=>$array_precio[$i],
                    'Estado'=>9,
                ]
            );
        }
        $activo = DB::table('integracion')->where('PkEmpresa', '=', Session('emp-id'))->value('Activo');

        if ($activo == 1){



            $com_ser = DB::table('comprobante')->where('PkEmpresa', '=', Session('emp-id'))->orderby('id_comprobante','DESC')->take(1)->value('Serie');
            $new_ser=$com_ser+1;
            $TC=DB::table('monedaempresa')->where([['Activo', 1],['PkEmpresa', '=',Session('emp-id')]])->value('Cambio');
            $principal=DB::table('monedaempresa')->where([['Activo', 1],['PkEmpresa', '=',Session('emp-id')]])->value('Principal');
            $periodo_id= DB::table('periodo')->where([['FechaInicio','<=',$fechaIngreso],['FechaFin','>=',$fechaIngreso], ['PkEmpresa' ,'=',Session('emp-id')]])->value('id');

            DB::table('comprobante')->insert([
                    'Serie' => $new_ser,
                    'Glosa' => "Venta de Mercaderias",
                    'Fecha' => $fechaIngreso,
                    'TC' => $TC,
                    'TipoComprobante' => "Ingreso",
                    'PkEmpresa' => Session('emp-id'),
                    'Estado' => '1',
                    'user_id'=>Auth::user()->id,
                    'PkMoneda' => $principal,
                    'PkPeriodo'=> $periodo_id,
                ]
            );

            $id_comprobante = DB::table('comprobante')->where([['PkEmpresa', '=', Session('emp-id')],['Serie', '=',$new_ser]])->value('id_comprobante');
            $id_caja= DB::table('integracion')->where([['PkEmpresa', '=', Session('emp-id')],['Activo', '=',1]])->value('Caja');
            $id_it= DB::table('integracion')->where([['PkEmpresa', '=', Session('emp-id')],['Activo', '=',1]])->value('IT');
            $id_debito_fiscal= DB::table('integracion')->where([['PkEmpresa', '=', Session('emp-id')],['Activo', '=',1]])->value('DebitoFiscal');
            $id_ventas= DB::table('integracion')->where([['PkEmpresa', '=', Session('emp-id')],['Activo', '=',1]])->value('Ventas');
            $id_it_por_pagar= DB::table('integracion')->where([['PkEmpresa', '=', Session('emp-id')],['Activo', '=',1]])->value('ITporPagar');



            $Tot_IT=($Total_Nota * 0.3);
            $Tot_debito_fiscal=($Total_Nota * 0.13);
            $Tot_IT_por_pagar=($Total_Nota * 0.3);
            $Tot_ventas=($Total_Nota - $Tot_debito_fiscal);

            $Alt_Total_Nota = $Total_Nota * $TC;
            $Alt_Tot_IT = $Tot_IT * $TC;
            $ATot_debito_fiscal = $Tot_debito_fiscal * $TC;
            $Alt_Tot_ventas =$Tot_ventas * $TC;
            $Tot_IT_por_pagares = $Tot_IT_por_pagar * $TC;

            DB:: table ('notas')->where('id','=',$id_nota)->update([
                'PkComprobante'=>$id_comprobante,
            ]);

            DB::table('detallecomprobante')->insert([
                    'Numero' => 1,
                    'Glosa' => "Venta de Mercaderias",
                    'MontoDebe' => $Total_Nota,
                    'MontoHaber' => "0",
                    'MontoDebeAlt' => $Alt_Total_Nota,
                    'MontoHaberAlt' => "0",
                    'user_id'=>Auth::user()->id,
                    'PkComprobante'=>$id_comprobante,
                    'PkCuenta'=>$id_caja,
                ]
            );

            DB::table('detallecomprobante')->insert([
                    'Numero' => 2,
                    'Glosa' => "Venta de Mercaderias",
                    'MontoDebe' => $Tot_IT,
                    'MontoHaber' => "0",
                    'MontoDebeAlt' => $Alt_Tot_IT,
                    'MontoHaberAlt' => "0",
                    'user_id'=>Auth::user()->id,
                    'PkComprobante'=>$id_comprobante,
                    'PkCuenta'=>$id_it,
                ]
            );

            DB::table('detallecomprobante')->insert([
                    'Numero' => 3,
                    'Glosa' => "Venta de Mercaderias",
                    'MontoDebe' => "0",
                    'MontoHaber' => $Tot_debito_fiscal,
                    'MontoDebeAlt' => "0",
                    'MontoHaberAlt' => $ATot_debito_fiscal,
                    'user_id'=>Auth::user()->id,
                    'PkComprobante'=>$id_comprobante,
                    'PkCuenta'=>$id_debito_fiscal,
                ]
            );

            DB::table('detallecomprobante')->insert([
                    'Numero' => 4,
                    'Glosa' => "Venta de Mercaderias",
                    'MontoDebe' => "0",
                    'MontoHaber' => $Tot_ventas,
                    'MontoDebeAlt' => "0",
                    'MontoHaberAlt' => $Alt_Tot_ventas,
                    'user_id'=>Auth::user()->id,
                    'PkComprobante'=>$id_comprobante,
                    'PkCuenta'=>$id_ventas,
                ]
            );

            DB::table('detallecomprobante')->insert([
                    'Numero' => 5,
                    'Glosa' => "Venta de Mercaderias",
                    'MontoDebe' => "0",
                    'MontoHaber' => $Tot_IT_por_pagar,
                    'MontoDebeAlt' => "0",
                    'MontoHaberAlt' => $Tot_IT_por_pagares,
                    'user_id'=>Auth::user()->id,
                    'PkComprobante'=>$id_comprobante,
                    'PkCuenta'=>$id_it_por_pagar,
                ]
            );

            return redirect('notaventa/'.$id_nota);
        }else{
            return back();
        }


    }

    public function fetch(Request $request)
    {
        if($request->get('query'))
        {
            $query = $request->get('query');
            $lotes = DB::table('lotes')->where('PkArticulo', '=', $query)->get();

            $output = '';
            $output .= '<option value=0>Seleccione';
            foreach($lotes as $row)
            {
                $output .= '<option value='.$row->id.'>'.$row->NroLote.'-Cant: '.$row->Cantidad;
            }
            echo json_encode($output);
        }
    }

    public function fetchEdit(Request $request)
    {
        if($request->get('query'))
        {
            $query = $request->get('query');
            $lotes = DB::table('lotes')->where('PkArticulo', '=', $query)->get();

            $output = '';
            $output .= '<option value=0>Seleccione';
            foreach($lotes as $row)
            {
                $output .= '<option value='.$row->id.'>'.$row->NroLote.'-Cant: '.$row->Cantidad;
            }
            echo json_encode($output);
        }
    }

    function buscar_precio(Request $request)
    {
        if($request->get('query'))
        {
            $query = $request->get('query');

            $output = DB::table('articulos')
                ->join('lotes', 'articulos.id', '=', 'lotes.PkArticulo')
                ->where('lotes.id', '=', $query)->value('articulos.PrecioVenta');

            echo json_encode($output);
        }
    }

    function cant_precio(Request $request)
    {
        if($request->get('query'))
        {
            $query = $request->get('query');

            $precio = DB::table('articulos')
                ->join('lotes', 'articulos.id', '=', 'lotes.PkArticulo')
                ->where('lotes.id', '=', $query)->value('articulos.PrecioVenta');


            echo json_encode($precio);
        }
    }

    public function LoteEdit(Request $request){
        if($request->get('query'))
        {
            $query = $request->get('query');
            $lote = DB::table('lotes')->where([['id', '=', $query],['Stock','>', 0]])->get();
            $idArticulo = DB::table('lotes')->where([['id', '=', $query],['Stock','>', 0]])->value('PkArticulo');
            $lotes = DB::table('lotes')->where([['PkArticulo', '=', $idArticulo],['Stock','>', 0],['id','!=', $query]])->get();

            $output = '';
            foreach($lote as $ro)
            {
                $output .= '<option value='.$ro->id.'>'.$ro->NroLote.'-Cant: '.$ro->Cantidad;;
            }
            foreach($lotes as $row)
            {
                $output .= '<option value='.$row->id.'>'.$row->NroLote.'-Cant: '.$row->Cantidad;
            }

            echo json_encode($output);
        }
    }
    public function vistaEdit(){
        $detalles = DB::select('SELECT articulos.Nombre, detalleventas.PrecioVenta, detalleventas.Cantidad,(detalleventas.PrecioVenta * detalleventas.Cantidad)as SubTotal, lotes.id FROM notas INNER JOIN detalleventas ON detalleventas.PkNota = notas.id INNER JOIN lotes on detalleventas.NroLote = lotes.id INNER JOIN articulos on lotes.PkArticulo = articulos.id WHERE notas.id =? ;',[Session('nota-detalle-id')]);
        $notas = DB::table('notas')->where([['PkEmpresa', '=', Session('emp-id')],['id', '=',Session('nota-detalle-id')]])->get();
        return view('ventaedit', ['detalles' => $detalles],['notas' =>$notas]);
    }

    public function anular(Request $request){
        $id_nota=$request->input('id_nota');
        DB:: table ('notas')->where('id','=',$id_nota)->update([
            'Estado'=>3,
        ]);
        $id_com = DB:: table ('notas')->where('id','=',$id_nota)->value('PkComprobante');

        if ($id_com > 0){
            DB:: table ('comprobante')->where('id_comprobante','=',$id_com)->update([
                'Estado'=>3,
            ]);
        }

        $articulos=$request->input('det_articulo');
        $cantidades=$request->input('det_cantidad');
        $lotes=$request->input('det_lote');
        $cant=$request->input('det_cant');

        $array_articulo = explode(",",$articulos);
        $array_cantidad = explode(",",$cantidades);
        $array_lote = explode(",",$lotes);

        for ($i = 0; $i <$cant; $i++){
            $res=$array_articulo[$i];
            $IdArrayArticulo[$i] = DB::table('articulos')->where([['PkEmpresa', '=', Session('emp-id')],['Nombre', '=',$res]])->value('id');
        }
        for ($i = 0; $i <$cant; $i++){
            $CantArt = DB::table('articulos')->where([['PkEmpresa', '=', Session('emp-id')],['id', '=',$IdArrayArticulo[$i]]])->value('Cantidad');
            $CantLot = DB::table('lotes')->where('id', '=',$array_lote[$i])->value('Stock');
            $totalArt = $CantArt + $array_cantidad[$i];
            $totalLot = $CantLot + $array_cantidad[$i];

            DB::table('articulos')->where('id', $IdArrayArticulo[$i])->update([
                    'Cantidad' => $totalArt,
                ]
            );
            DB::table('lotes')->where('id', $array_lote[$i])->update([
                    'Stock' => $totalLot,
                ]
            );
        }
        for ($i = 0; $i <$cant; $i++){
            DB:: table ('detalleventas')->where('PkNota','=',$id_nota)->update([
                'Estado'=>3,
            ]);
        }

        return back();
    }

    function buscar(Request $request)
    {
        if($request->get('query'))
        {
            $query = $request->get('query');
            $data = DB::table('articulos')
                ->where([['PkEmpresa', '=',Session('emp-id')],['Nombre', 'LIKE', "%{$query}%"],['Cantidad','>', 0]])
                ->get();
            $output = '<ul class="dropdown-menu" style="display:block; position:relative">';
            foreach($data as $row)
            {
                $output .= '<li class="pl-1 nuevo" id="'.$row->id.'"><a href="#" style="color: #1b1e21">'.$row->Nombre.'</a></li>';
            }
            $output .= '</ul>';
            echo $output;
        }
    }

    public function buscararticulo(Request $request)
    {
        if($request->get('query'))
        {
            $query = $request->get('query');
            $data = DB::table('articulos')
                ->where([['PkEmpresa', '=',Session('emp-id')],['Nombre', 'LIKE', "%{$query}%"],['Cantidad','>', 0]])
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

    public function validarstock (Request $request){
        if($request->get('query'))
        {
            $cantidad = $request->get('query');
            $lote = $request->get('lote');
            $stock= DB::table('lotes')->where('id','=',$lote)->value('Stock');
            if ($cantidad <= $stock){
                $output = '2';
            }else{
                $output = '1';
            }
            echo json_encode($output);
        }
    }
    public function valeditstock (Request $request){
        if($request->get('query'))
        {
            $cantidad = $request->get('query');
            $lote = $request->get('lote');
            $stock= DB::table('lotes')->where('id','=',$lote)->value('Stock');
            if ($cantidad <= $stock){
                $output = '2';
            }else{
                $output = '1';
            }
            echo json_encode($output);
        }
    }
}

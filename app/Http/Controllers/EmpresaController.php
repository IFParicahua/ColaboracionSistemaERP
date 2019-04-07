<?php

namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class EmpresaController extends Controller
{

    public function index()
    {
        $empresas = DB::table('empresa')->get();
        $moneda=DB::table('moneda')->get();
        return view('empresa',['empresa' => $empresas],['moneda' => $moneda]);

    }

    public function redirect (Request $request){
        $id = $request->input('id');
        $Nombre = $request->input('name');
        session()->put('emp-Nombre', $Nombre);
        session()->put('emp-id', $id);

        $Nivel = DB::table('empresa')->where('id',$id)->value('Niveles');
        session()->put('emp-Nivel', $Nivel);

        DB::table('reporte')->where('id', 2)->update([
            'report_id' => $request->input('id')]);
        DB::table('reporte')->where('id', 4)->update([
            'report_id' => $request->input('id')]);
        return redirect('/menus');

    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'razon_social' => 'unique:empresa',
            'nit' => 'unique:empresa',
            'sigla' => 'unique:empresa',
        ]);
        if ($validator->fails()) {
            return redirect('/empresa')
                ->with('error_code', 5)
                ->withErrors($validator)
                ->withInput();
        }else{
            DB::table('empresa')->insert([
                    'user_id'=>$request->input('id_us'),
                    'Razon_social'=>$request->input('razon_social'),
                    'Nit'=>$request->input('nit'),
                    'Sigla'=>$request->input('sigla'),
                    'Telefono'=>$request->input('telefono'),
                    'Correo'=>$request->input('correo'),
                    'Direccion'=>$request->input('direccion'),
                    'Niveles'=>$request->input('nivel')
                ]
            );
            $valor=$request->input('razon_social');
            date_default_timezone_set('America/La_Paz');
            $fechaActual=date('Y-m-d H:i:s');
            $idempresa=DB::table('empresa')->where('Razon_social','=',$valor)->value('id');
            DB::table('monedaempresa')->insert([
                'Activo'=>'1',
                'FechaRegistro'=>$fechaActual,
                'PkEmpresa'=>$idempresa,
                'Principal'=>$request->input('moneda'),
                'user_id'=>Auth::user()->id,
            ]);

            $niv = DB::table('empresa')->where('id','=', $idempresa)->value('Niveles');
            $Niveles = $niv - 1;
            $Nivel = $niv - 2;
            for($i=0; $i<$Niveles; $i++)
            {
                $array[$i]= 0;
            }
            for($i=0; $i<$Nivel; $i++)
            {
                $aray[$i]= 0;
            }

            //Unimos el array
            $resultado= implode(".",$array);
            $resultados= implode(".",$aray);
            DB::table('cuenta')->insert([
                    [
                        'Codigo' => "1.".$resultado,
                        'Nombre' => "Activo",
                        'Nivel' => 1,
                        'TipoCuenta' => 'cuenta',
                        'PkEmpresa' => $idempresa,
                        'idUser'=>Auth::user()->id,
                    ],[
                        'Codigo' => "2.".$resultado,
                        'Nombre' => "Pasivo",
                        'Nivel' => 1,
                        'TipoCuenta' => 'cuenta',
                        'PkEmpresa' => $idempresa,
                        'idUser'=>Auth::user()->id,
                    ],[
                        'Codigo' => "3.".$resultado,
                        'Nombre' => "Patrimonio",
                        'Nivel' => 1,
                        'TipoCuenta' => 'cuenta',
                        'PkEmpresa' => $idempresa,
                        'idUser'=>Auth::user()->id,
                    ],[
                        'Codigo' => "4.".$resultado,
                        'Nombre' => "Ingresos",
                        'Nivel' => 1,
                        'TipoCuenta' => 'cuenta',
                        'PkEmpresa' => $idempresa,
                        'idUser'=>Auth::user()->id,
                    ],[
                        'Codigo' => "5.".$resultado,
                        'Nombre' => "Egresos",
                        'Nivel' => 1,
                        'TipoCuenta' => 'cuenta',
                        'PkEmpresa' => $idempresa,
                        'idUser'=>Auth::user()->id,
                    ]
                ]
            );
            $pkCuenta = DB::table('cuenta')->where([['PkEmpresa','=', $idempresa],['Nombre','=','Egresos']])->value('id_cuenta');
            DB::table('cuenta')->insert([
                [
                    'CuentaPadre' => $pkCuenta,
                    'Codigo' => "5.1.".$resultados,
                    'Nombre' => "Costos",
                    'Nivel' => 1,
                    'TipoCuenta' => 'cuenta',
                    'PkEmpresa' => $idempresa,
                    'idUser'=>Auth::user()->id,
                ],[
                    'CuentaPadre' => $pkCuenta,
                    'Codigo' => "5.2.".$resultados,
                    'Nombre' => "Gastos",
                    'Nivel' => 1,
                    'TipoCuenta' => 'cuenta',
                    'PkEmpresa' => $idempresa,
                    'idUser'=>Auth::user()->id,
                ]
            ]);
            return back()->with('error_code', 0);
        }

    }

    public function update(Request $request)
    {
        $id = $request->input('pk-empresa');
        $validator = Validator::make($request->all(), [
            'razon' => 'unique:empresa,Razon_social,'.$id.',id',
            'nits' => 'unique:empresa,Nit,'.$id.',id',
            'siglas' => 'unique:empresa,Sigla,'.$id.',id',
        ]);

        if ($validator->fails()) {
            return redirect('/empresa')
                ->with('error_code', 4)
                ->withErrors($validator)
                ->withInput();
        } else {
            $id = $request->input('pk-empresa');
            DB::table('empresa')->where('id', $id)->update([
                    'Razon_social' => $request->input('razon'),
                    'Nit' => $request->input('nits'),
                    'Sigla' => $request->input('siglas'),
                    'Telefono' => $request->input('telefonos'),
                    'Correo' => $request->input('correos'),
                    'Direccion' => $request->input('direcciones'),
                    'Niveles'=>$request->input('niveles')
                ]
            );
            return back()->with('error_code', 0);
        }
    }

    public function delete($id)
    {
        $gestion = DB::table('gestion')->where('PkEmpresa','=',$id)->count();
        $cuenta = DB::table('cuenta')->where([['PkEmpresa','=',$id],['Nivel','=','2']])->count();
        $categoria = DB::table('categorias')->where('PkEmpresa','=',$id)->count();
        $empresa = 'nuevo'.$gestion.'+'.$cuenta.'+'.$categoria;

        if ($gestion ==0 &&  $cuenta == 0 && $categoria == 0 ){
            $hijos = DB::table('cuenta')->where([['PkEmpresa','=',$id],['Nombre','=','Costos']])->value('CuentaPadre');;

        DB::table('monedaempresa')->where('PkEmpresa', '=', $id)->delete();
        DB::table('cuenta')->where([['PkEmpresa', '=', $id],['CuentaPadre','=',$hijos]])->delete();

        DB::table('cuenta')->where('PkEmpresa', '=', $id)->delete();
        DB::table('empresa')->where('id', '=', $id)->delete();
            return redirect('/empresa');
        }else{
            Session::flash('danger', $empresa);
            return redirect('/empresa');
        }
    }

    public function reporte()
    {
        return view('inde');
    }
}

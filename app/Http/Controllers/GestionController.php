<?php

namespace App\Http\Controllers;

namespace App\Http\Controllers;
use Illuminate\Database\Seeder;
use Faker\Provider\DateTime;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class GestionController extends Controller
{
    public function index()
    {
        $id = Session('emp-id');
        $conceptos=DB::table('concepto')->get();
        $gestiones = DB::table('gestion')
                            ->join('concepto', 'concepto.id', '=', 'gestion.Estado')
                            ->where('PkEmpresa', '=',$id)
                            ->select('gestion.id','gestion.Nombre','gestion.FechaInicio','gestion.FechaFin','gestion.FechaFin','gestion.Estado','concepto.Nombres')
                            ->orderBy('Nombre')
                            ->get();
        return view('gestion',['gestiones' => $gestiones],['conceptos'=>$conceptos]);
    }

    public function redir(Request $request){
        $id = $request->input('id');
        session()->put('gest-id', $id);

        $nombre = $request->input('nom');
        session()->put('gest-nombre', $nombre);

        $inicio = $request->input('inicio');
        session()->put('gest-inicio', $inicio);

        $fin = $request->input('fin');
        session()->put('gest-fin', $fin);

        DB::table('reporte')->where('id', 3)->update([
            'report_id' => $request->input('id')]);

        return redirect('/periodo');
    }

    public function create(Request $request)
    {
        $hola = DB::table('gestion')->where([
            ['Estado', '=', '1'],
            ['PkEmpresa', '=', Session('emp-id')],
        ])->count();

        if ($hola == 2) {
            Session::flash('alert-gestion', 'Ya existen dos gestiones abiertas, cierre una para poder crear una nueva gestion');
            return redirect('/gestion');
        } else {
            $inicio = $request->input('fechas_inicio');
            $fin = $request->input('fechas_fin');

            $date_inicio = DB::table('gestion')->where([
                ['PkEmpresa', '=', Session('emp-id')],
                ['FechaInicio', '<=', $inicio],
                ['FechaFin', '>=', $inicio],
            ])->value('FechaFin');

            $date_fin = DB::table('gestion')->where([
                ['PkEmpresa', '=', Session('emp-id')],
                ['FechaInicio', '<=', $fin],
                ['FechaFin', '>=', $fin],
            ])->value('FechaInicio');

            $date_entre = DB::table('gestion')->where([
                ['PkEmpresa', '=', Session('emp-id')],
                ['FechaInicio', '>', $inicio],
                ['FechaFin', '<', $fin],
            ])->count();

            if ($date_inicio > 0) {
                if ($date_fin > 0) {
                    $validator = Validator::make($request->all(), [
                        'nombre' => [
                            'required', Rule::unique('gestion')->where(function ($query) {
                                $query->where('PkEmpresa', Session('emp-id'));
                            })],
                        'fechas_inicio' => 'after:' . $date_inicio,
                        'fechas_fin' => 'before:' . $date_fin
                    ], [
                        'fechas_inicio.after' => 'Esta fecha se encuentra dentro de un rango existente',
                        'fechas_fin.before' => 'Esta fecha se encuentra dentro de un rango existente',
                    ]);
                } else {
                    $validator = Validator::make($request->all(), [
                        'nombre' => [
                            'required', Rule::unique('gestion')->where(function ($query) {
                                $query->where('PkEmpresa', Session('emp-id'));
                            })],
                        'fechas_inicio' => 'after:' . $date_inicio,
                        'fechas_fin' => 'after:fechas_inicio'
                    ], [
                        'fechas_inicio.after' => 'Esta fecha se encuentra dentro de un rango existente'
                    ]);
                }
            } else {
                if ($date_fin > 0) {
                    $validator = Validator::make($request->all(), [
                        'nombre' => [
                            'required', Rule::unique('gestion')->where(function ($query) {
                                $query->where('PkEmpresa', Session('emp-id'));
                            })],
                        'fechas_inicio' => 'before:fechas_fin',
                        'fechas_fin' => 'before:' . $date_fin
                    ], [
                        'fechas_fin.before' => 'Esta fecha se encuentra dentro de un rango existente',
                    ]);
                } else {
                    if ($date_entre > 0) {
                        $validator = Validator::make($request->all(), [
                            'nombre' => [
                                'required', Rule::unique('gestion')->where(function ($query) {
                                    $query->where('PkEmpresa', Session('emp-id'));
                                })],
                            'fechas_inicio' => 'after:fechas_fin',
                        ], [
                            'fechas_inicio.after' => 'Existen Gestiones dentro del rango especificado',
                        ]);
                    } else {
                        $validator = Validator::make($request->all(), [
                            'nombre' => [
                                'required', Rule::unique('gestion')->where(function ($query) {
                                    $query->where('PkEmpresa', Session('emp-id'));
                                })],
                            'fechas_fin' => 'after:fechas_inicio'
                        ]);
                    }
                }
            }

            if ($validator->fails()) {
                return redirect('/gestion')
                    ->with('error_code', 5)
                    ->withErrors($validator)
                    ->withInput();
            } else {
                DB::table('gestion')->insert([
                        'Nombre' => $request->input('nombre'),
                        'FechaInicio' => $request->input('fechas_inicio'),
                        'FechaFin' => $request->input('fechas_fin'),
                        'Estado' => '1',
                        'PkEmpresa' => $request->input('empresa_id')
                    ]
                );
                return back()->with('error_code', 0);
            }

        }
    }

    public function update (Request $request)
    {
        $inicio = $request->input('fecha_inicio');
        $fin = $request->input('fecha_fin');
        $id = $request->input('pk-gestion');

        $date_inicio = DB::table('gestion')->where([
            ['PkEmpresa', '=', Session('emp-id')],
            ['FechaInicio', '<=', $inicio],
            ['FechaFin', '>=', $inicio],
            ['id', '!=', $id]
        ])->value('FechaFin');

        $date_fin = DB::table('gestion')->where([
            ['PkEmpresa', '=', Session('emp-id')],
            ['FechaInicio', '<=', $fin],
            ['FechaFin', '>=', $fin],
            ['id', '!=', $id],
        ])->value('FechaInicio');

        $date_entre = DB::table('gestion')->where([
            ['PkEmpresa', '=', Session('emp-id')],
            ['FechaInicio', '>', $inicio],
            ['FechaFin', '<', $fin],
        ])->count();

        if ($date_inicio > 0 ) {
            if ($date_fin > 0) {
                $validator = Validator::make($request->all(), [
                    'nombres' => [
                        'required', Rule::unique('gestion', 'Nombre')->where(function ($query) {
                            $query->where('PkEmpresa', Session('emp-id'));
                        })->ignore($id, 'id')],

                    'fecha_inicio' => 'after:' . $date_inicio,
                    'fecha_fin' => 'before:' . $date_fin
                ], [
                    'fecha_inicio.after' => 'Esta fecha se encuentra dentro de un rango existente',
                    'fecha_fin.before' => 'Esta fecha se encuentra dentro de un rango existente',
                ]);
            } else {
                $validator = Validator::make($request->all(), [
                    'nombres' => [
                        'required', Rule::unique('gestion', 'Nombre')->where(function ($query) {
                            $query->where('PkEmpresa', Session('emp-id'));
                        })->ignore($id, 'id')],
                    'fecha_inicio' => 'after:' . $date_inicio,
                    'fecha_fin' => 'after:fecha_inicio'
                ], [
                    'fecha_inicio.after' => 'Esta fecha se encuentra dentro de un rango existente'
                ]);
            }
        } elseif ($date_fin > 0){
            $validator = Validator::make($request->all(), [
                'nombres' => [
                    'required', Rule::unique('gestion','Nombre')->where(function ($query) {
                        $query->where('PkEmpresa', Session('emp-id'));
                    })->ignore($id, 'id')],
                'fecha_inicio' => 'before:fecha_fin',
                'fecha_fin' => 'before:' . $date_fin
            ], [
                'fecha_fin.before' => 'Esta fecha se encuentra dentro de un rango existente',
            ]);
        } elseif ($date_entre > 0) {
            $validator = Validator::make($request->all(), [
                'nombres' => [
                    'required', Rule::unique('gestion','Nombre')->where(function ($query) {
                        $query->where('PkEmpresa', Session('emp-id'));
                    })->ignore($id, 'id')],
                'fecha_inicio' => 'after:fecha_fin',
            ], [
                'fecha_inicio.after' => 'Existen Gestiones dentro del rango especificado',
            ]);
        } else {
            $validator = Validator::make($request->all(), [
                'nombres' => [
                    'required', Rule::unique('gestion','Nombre')->where(function ($query) {
                        $query->where('PkEmpresa', Session('emp-id'));
                    })->ignore($id, 'id')],
                'fecha_fin' => 'after:fecha_inicio'
            ]);
        }

        if ($validator->fails()) {
            return redirect('/gestion')
                ->with('error_code', 4) /*o 5*/
                ->withErrors($validator)
                ->withInput();
        } else {


            DB::table('gestion')->where('id', $id)->update([
                    'Nombre' => $request->input('nombres'),
                    'FechaInicio' => $request->input('fecha_inicio'),
                    'FechaFin' => $request->input('fecha_fin')
                ]
            );
            return back()->with('error_code', 0);
        }
    }

    public function delete($id)
    {
        try {
            DB::table('gestion')->where('id', '=', $id)->delete();
            return back();
        } catch (QueryException $e) {
            $gestion = DB::table('gestion')->where('id', '=', $id)->value('Nombre');

            Session::flash('danger-gestion', $gestion);
            return redirect('/gestion');
        }
    }
    public function cerrar($id)
    {
        $periodo = DB::table('periodo')->where('PkGestion', '=', $id)->count();
        if($periodo>0){
            DB::table('periodo')->where('PkGestion', $id)->update([
                    'Estado' => '2',
                ]
            );
            DB::table('gestion')->where('id', $id)->update([
                    'Estado' => '2',
                ]
            );
        }
        return redirect('/gestion');
    }
}

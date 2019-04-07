<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Database\QueryException;

class PeriodoController extends Controller
{
    public function index()
    {
        $id = Session('gest-id');
        $id2=Session('emp-id');
        $conceptos=DB::table('concepto')->get();
        $periodos = DB::table('periodo')
                        ->join('concepto', 'concepto.id', '=', 'periodo.Estado')
                        ->where([['PkGestion', '=', $id],['PkEmpresa','=',$id2]])
                        ->select('periodo.id','periodo.Nombre','periodo.FechaInicio','periodo.FechaFin','periodo.FechaFin','periodo.Estado','concepto.Nombres')
                        ->orderBy('FechaInicio')
                        ->get();
        return view('periodo', ['periodos' => $periodos],['conceptos'=>$conceptos]);
    }

    public function redir(Request $request)
    {
        //
    }


    public function create(Request $request)
    {
        $inicioGes = Session('gest-inicio');
        $finGes = Session('gest-fin');
        $inicio = $request->input('fecha_inicio');
        $fin = $request->input('fecha_fin');

        if ($inicio < $inicioGes || $inicio > $finGes) {
            if ($fin < $inicioGes || $fin > $finGes) {
                $validator = Validator::make($request->all(), [
                    'nombre' => [
                        'required', Rule::unique('periodo', 'Nombre')->where(function ($query) {
                            $query->where('PkGestion', Session('gest-id'));
                        })],
                    'fecha_inicio' => 'integer',
                ], [
                    'fecha_inicio.integer' => 'Fecha inicio y fecha fin estan fuera de rango',
                ]);
            } else {
                $validator = Validator::make($request->all(), [
                    'nombre' => [
                        'required', Rule::unique('periodo', 'Nombre')->where(function ($query) {
                            $query->where('PkGestion', Session('gest-id'));
                        })],
                    'fecha_inicio' => 'integer',
                ], [
                    'fecha_inicio.integer' => 'Fecha inicio esta fuera de Rango',
                ]);
            }
        } elseif ($fin < $inicioGes || $fin > $finGes) {
            $validator = Validator::make($request->all(), [
                'nombre' => [
                    'required', Rule::unique('periodo', 'Nombre')->where(function ($query) {
                        $query->where('PkGestion', Session('gest-id'));
                    })],
                'fecha_fin' => 'integer',
            ], [
                'fecha_fin.integer' => 'Fecha fin esta fuera de Rango',
            ]);
        } else {
            $date_inicio = DB::table('periodo')->where([
                ['PkGestion', '=', Session('gest-id')],
                ['FechaInicio', '<=', $inicio],
                ['FechaFin', '>=', $inicio],
            ])->value('FechaFin');

            $date_fin = DB::table('periodo')->where([
                ['PkGestion', '=', Session('gest-id')],
                ['FechaInicio', '<=', $fin],
                ['FechaFin', '>=', $fin],
            ])->value('FechaInicio');

            $date_entre = DB::table('periodo')->where([
                ['PkGestion', '=', Session('gest-id')],
                ['FechaInicio', '>', $inicio],
                ['FechaFin', '<', $fin],
            ])->count();


            if ($date_inicio > 0) {
                if ($date_fin > 0) {
                    $validator = Validator::make($request->all(), [
                        'nombre' => [
                            'required', Rule::unique('periodo', 'Nombre')->where(function ($query) {
                                $query->where('PkGestion', Session('gest-id'));
                            })],
                        'fecha_inicio' => 'after:' . $date_inicio,
                        'fecha_fin' => 'before:' . $date_fin
                    ], [
                        'fecha_inicio.after' => 'Fecha inicio se encuentra dentro de un rango existente',
                        'fecha_fin.before' => 'Fecha fin se encuentra dentro de un rango existente',
                    ]);
                } else {
                    $validator = Validator::make($request->all(), [
                        'nombre' => [
                            'required', Rule::unique('periodo', 'Nombre')->where(function ($query) {
                                $query->where('PkGestion', Session('gest-id'));
                            })],
                        'fecha_inicio' => 'after:' . $date_inicio
                    ], [
                        'fecha_inicio.after' => 'Fecha inicio se encuentra dentro de un rango existente'
                    ]);
                }
            } elseif ($date_fin > 0) {
                $validator = Validator::make($request->all(), [
                    'nombre' => [
                        'required', Rule::unique('periodo', 'Nombre')->where(function ($query) {
                            $query->where('PkGestion', Session('gest-id'));
                        })],
                    'fecha_fin' => 'before:' . $date_fin
                ], [
                    'fecha_fin.before' => 'Fecha fin se encuentra dentro de un rango existente',
                ]);
            } elseif ($date_entre) {
                $validator = Validator::make($request->all(), [
                    'nombre' => [
                        'required', Rule::unique('periodo', 'Nombre')->where(function ($query) {
                            $query->where('PkGestion', Session('gest-id'));
                        })],
                    'fecha_inicio' => 'after:fecha_fin',
                ], [
                    'fecha_inicio.after' => 'Existen Periodos dentro del rango especificado',
                ]);
            } else {
                $validator = Validator::make($request->all(), [
                    'nombre' => [
                        'required', Rule::unique('periodo', 'Nombre')->where(function ($query) {
                            $query->where('PkGestion', Session('gest-id'));
                        })],
                    'fecha_fin' => 'after:fecha_inicio'
                ]);
            }

        }

        if ($validator->fails()) {
            return redirect('/periodo')
                ->with('error_code', 5)
                ->withErrors($validator)
                ->withInput();
        } else {
            DB::table('periodo')->insert([
                    'Nombre' => $request->input('nombre'),
                    'FechaInicio' => $request->input('fecha_inicio'),
                    'FechaFin' => $request->input('fecha_fin'),
                    'Estado' => '1',
                    'PkGestion' => $request->input('gestion_id'),
                    'PkEmpresa' =>Session('emp-id')
                ]
            );
            return back()->with('error_code', 0);
        }

    }

    public function update (Request $request)
    {
        $id = $request->input('pk-periodo');
        $inicioGes = Session('gest-inicio');
        $finGes = Session('gest-fin');
        $inicio = $request->input('fechas_inicio');
        $fin = $request->input('fechas_fin');

        if ($inicio < $inicioGes || $inicio > $finGes) {
            if ($fin < $inicioGes || $fin > $finGes) {
                $validator = Validator::make($request->all(), [
                    'nombres' => [
                        'required', Rule::unique('periodo', 'Nombre')->where(function ($query) {
                            $query->where('PkGestion', Session('gest-id'));
                        })],
                    'fechas_inicio' => 'integer',
                ], [
                    'fechas_inicio.integer' => 'Fecha inicio y fecha fin estan fuera de rango',
                ]);
            } else {
                $validator = Validator::make($request->all(), [
                    'nombres' => [
                        'required', Rule::unique('periodo', 'Nombre')->where(function ($query) {
                            $query->where('PkGestion', Session('gest-id'));
                        })],
                    'fechas_inicio' => 'integer',
                ], [
                    'fechas_inicio.integer' => 'Fecha inicio esta fuera de Rango',
                ]);
            }
        } elseif ($fin < $inicioGes || $fin > $finGes) {
            $validator = Validator::make($request->all(), [
                'nombres' => [
                    'required', Rule::unique('periodo', 'Nombre')->where(function ($query) {
                        $query->where('PkGestion', Session('gest-id'));
                    })],
                'fechas_fin' => 'integer',
            ], [
                'fechas_fin.integer' => 'Fecha fin esta fuera de Rango',
            ]);
        } else {
            $date_inicio = DB::table('periodo')->where([
                ['PkGestion', '=', Session('gest-id')],
                ['FechaInicio', '<=', $inicio],
                ['FechaFin', '>=', $inicio],
                ['id', '!=', $id],
            ])->value('FechaFin');

            $date_fin = DB::table('periodo')->where([
                ['PkGestion', '=', Session('gest-id')],
                ['FechaInicio', '<=', $fin],
                ['FechaFin', '>=', $fin],
                ['id', '!=', $id],
            ])->value('FechaInicio');

            $date_entre = DB::table('periodo')->where([
                ['PkGestion', '=', Session('gest-id')],
                ['FechaInicio', '>', $inicio],
                ['FechaFin', '<', $fin],
            ])->count();

            if ($date_inicio > 0) {
                if ($date_fin > 0) {
                    $validator = Validator::make($request->all(), [
                        'nombres' => [
                            'required', Rule::unique('periodo', 'Nombre')->where(function ($query) {
                                $query->where('PkGestion', Session('gest-id'));
                            })->ignore($id, 'id')],
                        'fechas_inicio' => 'after:' . $date_inicio,
                        'fechas_fin' => 'before:' . $date_fin
                    ], [
                        'fechas_inicio.after' => 'Fecha inicio se encuentra dentro de un rango existente',
                        'fechas_fin.before' => 'Fecha fin se encuentra dentro de un rango existente',
                    ]);
                } else {
                    $validator = Validator::make($request->all(), [
                        'nombres' => [
                            'required', Rule::unique('periodo', 'Nombre')->where(function ($query) {
                                $query->where('PkGestion', Session('gest-id'));
                            })->ignore($id, 'id')],
                        'fechas_inicio' => 'after:' . $date_inicio
                    ], [
                        'fechas_inicio.after' => 'Fecha inicio se encuentra dentro de un rango existente'
                    ]);
                }
            } elseif ($date_fin > 0) {
                $validator = Validator::make($request->all(), [
                    'nombres' => [
                        'required', Rule::unique('periodo', 'Nombre')->where(function ($query) {
                            $query->where('PkGestion', Session('gest-id'));
                        })->ignore($id, 'id')],
                    'fechas_fin' => 'before:' . $date_fin
                ], [
                    'fechas_fin.before' => 'Fecha fin se encuentra dentro de un rango existente',
                ]);
            } elseif ($date_entre) {
                $validator = Validator::make($request->all(), [
                    'nombres' => [
                        'required', Rule::unique('periodo', 'Nombre')->where(function ($query) {
                            $query->where('PkGestion', Session('gest-id'));
                        })->ignore($id, 'id')],
                    'fechas_inicio' => 'after:fecha_fin',
                ], [
                    'fechas_inicio.after' => 'Existen Periodos dentro del rango especificado',
                ]);
            } else {
                $validator = Validator::make($request->all(), [
                    'nombres' => [
                        'required', Rule::unique('periodo', 'Nombre')->where(function ($query) {
                            $query->where('PkGestion', Session('gest-id'));
                        })->ignore($id, 'id')],
                    'fechas_fin' => 'after:fechas_inicio'
                ]);
            }

        }

        if ($validator->fails()) {
            return redirect('/periodo')
                ->with('error_code', 4)
                ->withErrors($validator)
                ->withInput();
        } else {
            DB::table('periodo')->where('id', $id)
                ->update([
                        'Nombre' => $request->input('nombres'),
                        'FechaInicio' => $request->input('fechas_inicio'),
                        'FechaFin' => $request->input('fechas_fin')
                    ]
                );
            return back()->with('error_code', 0);
        }

    }

    public function delete($id)
    {
        DB::table('periodo')->where('id', '=', $id)->delete();
        return back();
    }

    function fechaCastellano ($fecha) {
        $fecha = substr($fecha, 0, 10);
        $numeroDia = date('d', strtotime($fecha));
        $dia = date('l', strtotime($fecha));
        $mes = date('F', strtotime($fecha));
        $anio = date('Y', strtotime($fecha));
        $dias_ES = array("Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado", "Domingo");
        $dias_EN = array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday");
        $nombredia = str_replace($dias_EN, $dias_ES, $dia);
        $meses_ES = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
        $meses_EN = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
        $nombreMes = str_replace($meses_EN, $meses_ES, $mes);
        return $nombredia." ".$numeroDia." de ".$nombreMes." de ".$anio;
    }
}

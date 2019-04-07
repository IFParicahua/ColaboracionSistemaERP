<?php


namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use App\Plancuentas;

class CuentaController extends Controller
{
    public function treeView(){
        $Categorys = Plancuentas::where([
            ['CuentaPadre','=',null],
            ['PkEmpresa','=',Session('emp-id')]
        ])->get();

        $tree='<ul><li id="0">Raiz </li>';
        foreach ($Categorys as $Category) {
            $tree .='<li id="'.$Category->id_cuenta.'" value="hola" class="leaf"><a>'.$Category->Codigo." - ".$Category->Nombre.'</a>';
            if(count($Category->childs)) {
                $tree .=$this->childView($Category);
            }
        }
        $tree .='<ul>';
        // return $tree;
        return view('cuenta',compact('tree'));
    }
    public function childView($Category){
        $html ='<ul>';
        foreach ($Category->childs as $arr) {
            if(count($arr->childs)){
                $html .='<li id="'.$arr->id_cuenta.'" value="hola" class="leaf"><a>'.$arr->Codigo." - ".$arr->Nombre.'</a>';
                $html.= $this->childView($arr);
            }else{
                $html .='<li id="'.$arr->id_cuenta.'" value="hola" class="leaf"><a>'.$arr->Codigo." - ".$arr->Nombre.'</a>';
                $html .="</li>";
            }
        }
        $html .="</ul>";
        return $html;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create(Request $request){
        $nivel = $request->input('nivel');
        //Almacenar id padre
        $id = $request->input('idpadre');
        //Recuperar los niveles de empresa
        $niveles = DB::table('empresa')->where('id','=', Session('emp-id'))->value('Niveles');
        //si id es =0 entonses sera un nodo padre
        if ($id == 0){
            //Consulto el ultimo nodo padre
            $cuenta = DB::table('cuenta')->where([['CuentaPadre','=', null], ['PkEmpresa','=',Session('emp-id')]])
                ->orderby('id_cuenta','DESC')->take(1)->value('Codigo');
            //Si no existen nodos padre
            if ($cuenta == 0){
                //Entonse se crea un array para el nodo padre
                for($i=0; $i<$niveles; $i++)
                {
                    $array[$i]= 0;
                }
                //Remplazamos el $array[0] por 1
                $array[0]= 1;
                //Unimos el array
                $resultado= implode(".",$array);
                //La variable $level va a ser 1 por defecto ya que es el primer hijo
                $level = 1;
            }else{
                //Si existen nodos padre se toma el resultado
                //Separo en array
                $array = explode(".",$cuenta);
                //Sumo el nivel 1+1
                $suma = 1 + $array[0];
                //Replazo la posicion de array por el resultado de $suma
                $array[0]= $suma;
                //Uno el array
                $resultado= implode(".",$array);
                //La variable $level va a ser 1 por defecto ya que es el primer hijo
                $level = 1;
            }
        }else{
            //Nodo Hijo
            $cuenta = DB::table('cuenta')->where([['CuentaPadre','=', $id], ['PkEmpresa','=',Session('emp-id')]])
                ->orderby('id_cuenta','DESC')->take(1)->value('Codigo');
            //Verificar si existen hijos
            if ($cuenta == 0){
                //Si no existen, se verifica que la cantidad del nivel sea igual alnivel almacenado en la tabla empresa
                if ($nivel > $niveles){
                    Session::flash('casa', 'La empresa es de '.$niveles.' niveles.'.'No se puede agregar mas niveles');
                    return redirect('/cuenta');
                }else{
                    //Si el nivel es correcto pero no existen hijo, recuperar codigo de la id
                    $cuenta = DB::table('cuenta')->where([['id_cuenta','=', $id], ['PkEmpresa','=',Session('emp-id')]])->value('codigo');
                    //la variable $cuenta la volvemos array
                    $array = explode(".",$cuenta);
                    //Restamos 1 al nivel ya que el array empieza en 0 pero los niveles en 1 y la variable nivel es dinamica y no estatica
                    $niv = $nivel-1;
                    //El nivel nos indicara la posicion de array Ej.:
                    ////$array[0]=1,$array[1]=0,$array[2]=0
                    //  al que debemos aumentar pero como es el primero por defecto se pone 1
                    $array[$niv]= 1;
                    //Volvemos un string el array (unimos lo que separamos)
                    $resultado= implode(".",$array);
                    //Almacenamos $nivel en $level
                    $level = $nivel;
                }
            }else{
                //Si existen hijos
                //Para el array se resta 1
                $niv = $nivel-1;
                //Convertir en array
                $array = explode(".",$cuenta);
                //Como existen hijos se suma 1 al array Ej.:
                //1.2.0
                //$array[1]=2 entonses 2+1
                $suma = 1 + $array[$niv];
                //Remplazamos el array el cual su posicion es $niv por el resultado de la suma
                $array[$niv]= $suma;
                //Unimos el array
                $resultado= implode(".",$array);
                //Almacenamos $nivel en $level
                $level = $nivel;
            }
        }

        $validator = Validator::make($request->all(), [
            'nombres' => [
                'required', Rule::unique('cuenta', 'Nombre')->where(function ($query) {
                    $query->where('PkEmpresa', Session('emp-id'));
                })]
        ], [
            'nombres.unique' => 'Este nombre ya existe',
        ]);

        if ($validator->fails()) {
            return redirect('/cuenta')
                ->with('error_code', 5)
                ->withErrors($validator)
                ->withInput();
        } else {
            //$resultado= el la variable resultante del primer if
            //$level= el la variable resultante del primer if
            //$id= variable que almacena $request->input('idpadre');(linea 51)
            //Session('emp-id')= variable de sesion
            DB::table('cuenta')->insert([
                    'CuentaPadre' =>$id,
                    'Codigo' => $resultado,
                    'Nombre' => $request->input('nombres'),
                    'Nivel' => $level,
                    'TipoCuenta' => 'cuenta',
                    'idUser' => 1,
                    'PkEmpresa' => Session('emp-id')
                ]
            );
            return back()->with('error_code', 0);
        }
    }

    public function update(Request $request){
        $id = $request->input('id');
        $validator = Validator::make($request->all(), [
            'nombre' => [
                'required', Rule::unique('cuenta', 'Nombre')->where(function ($query) {
                    $query->where('PkEmpresa', Session('emp-id'));
                })->ignore($id, 'id_cuenta')]
        ], [
            'nombre.unique' => 'Este nombre ya existe',
        ]);
        if ($validator->fails()) {
            return redirect('/cuenta')
                ->with('error_code', 4)
                ->withErrors($validator)
                ->withInput();
        } else {
            DB::table('cuenta')->where('id_cuenta', $id)
                ->update([
                        'Nombre' => $request->input('nombre'),
                    ]
                );
            return back()->with('error_code', 0);
        }
    }
    public function delete($id){
        try {
            DB::table('cuenta')
                ->where('id_cuenta', '=', $id)
                ->delete();
            return back();
        } catch (QueryException $e) {
            $cuenta = DB::table('cuenta')
                ->where('id_cuenta', '=', $id)
                ->value('Nombre');
            Session::flash('casa', 'No se puedo eliminar la '.$cuenta);
            return redirect('/cuenta');
        }
    }

    public function bloquearbtn(Request $request)
    {
        if($request->get('query'))
        {
            $query = $request->get('query');
            $cuenta = DB::table('cuenta')
                ->where('CuentaPadre', '=', $query)
                ->count();
            echo json_encode($cuenta);
        }
    }

}
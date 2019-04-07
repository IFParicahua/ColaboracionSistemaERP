<?php

namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use App\Categorias;

class CategoriaController extends Controller
{
    public function treeView(){
        $Categorys = Categorias::where([
            ['CategoriaPadre_id', '=', null],
            ['PkEmpresa','=', Session('emp-id') ]
        ])->get();
        $tree='<ul><li id="0">Root</li>';
        foreach ($Categorys as $Category) {
            $tree .='<li id="'.$Category->id.'" value="hola" class="leaf"><a>'.$Category->Nombre.'</a>';
            if(count($Category->childs)) {
                $tree .=$this->childView($Category);
            }
        }
        $tree .='<ul>';
        return view('categorias',compact('tree'));
    }

    public function childView($Category){
        $html ='<ul>';
        foreach ($Category->childs as $arr) {
            if(count($arr->childs)){
                $html .='<li id="'.$arr->id.'" value="hola" class="leaf"><a>'.$arr->Nombre.'</a>';
                $html.= $this->childView($arr);
            }else{
                $html .='<li id="'.$arr->id.'" value="hola" class="leaf"><a>'.$arr->Nombre.'</a>';
                $html .="</li>";
            }
        }
        $html .="</ul>";
        return $html;
    }

    public function descripcion(Request $request){
        $id = $request->get('id');
        $data = DB::table('categorias')
            ->where([['PkEmpresa', '=',Session('emp-id')],['id', '=', $id]])
            ->value('Descripcion');
        echo $data;
    }
    public function guardar(Request $request)
    {
        $descripcion = $request->input('descripciones');
        $idpadre = $request->input('idpadre');
        if ($idpadre == " ") {
            $idpadre = 0;
        }
        $validator = Validator::make($request->all(), [
            'nombres' => [
                Rule::unique('categorias', 'Nombre')->where(function ($query) {
                    $query->where('PkEmpresa', Session('emp-id'));
                })]
        ], [
            'nombres.unique' => 'Este nombre ya existe',
        ]);

        if ($validator->fails()) {
            return redirect('/categorias')
                ->with('error_code', 5)
                ->withErrors($validator)
                ->withInput();
        } else {
            DB::table('categorias')->insert([
                    'CategoriaPadre_id' => $idpadre,
                    'Nombre' => $request->input('nombres'),
                    'Descripcion' => $descripcion,
                    'PkEmpresa' => Session('emp-id'),
                    'user_id' => Auth::user()->id,
                ]
            );
            return back()->with('error_code', 0);
        }
    }
    public function actualizar(Request $request)
    {
        $id = $request->input('idcategoria');
        $validator = Validator::make($request->all(), [
            'nombre' => [
                Rule::unique('categorias', 'Nombre')->where(function ($query) {
                    $query->where('PkEmpresa', Session('emp-id'));
                })->ignore($id, 'id')]
        ], [
            'nombre.unique' => 'Este nombre ya existe',
        ]);
        if ($validator->fails()) {
            return redirect('/categoria')
                ->with('error_code', 4)
                ->withErrors($validator)
                ->withInput();
        } else {
            DB::table('categorias')->where('id', $id)
                ->update([
                        'Nombre' => $request->input('nombre'),
                        'Descripcion' => $request->input('descripcion'),
                    ]
                );
            return back()->with('error_code', 0);
        }
    }
    public function eliminar($id){
        try {
            DB::table('categorias')->where('id', '=', $id)->delete();
            return back();
        } catch (QueryException $e) {
            return back()->with('error_code',3);
        }
    }
}

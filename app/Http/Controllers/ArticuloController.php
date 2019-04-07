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

class ArticuloController extends Controller
{
    public function index()
    {
        $categorias = DB::table ('categorias')->get();
        $articulos = DB::table('articulos')
            ->where('articulos.PkEmpresa', '=', Session('emp-id'))->get();
        return view('articulos', ['articulos' => $articulos],['categorias' =>$categorias]);
    }

    function fetch(Request $request)
    {
        if($request->get('query'))
        {
            $query = $request->get('query');
            $data = DB::table('categorias')
                ->where([['PkEmpresa', '=',Session('emp-id')],['Nombre', 'LIKE', "%{$query}%"]])
                ->get();
            $output = '<ul class="dropdown-menu inner show">';
            foreach($data as $row)
            {
                $output .= '<li class="pl-1" id="'.$row->id.'"><a href="#" style="color: #1b1e21">'.$row->Nombre.'</a></li>';
            }
            $output .= '</ul>';
            echo $output;
        }
    }

    public function recuperar(Request $request){
        $id = $request->get('id');
        $data = DB::table('articulos')
            ->where([['PkEmpresa', '=',Session('emp-id')],['id', '=', $id]])
            ->value('PkCategoria');
        echo $data;
    }

    public function create(Request $request){

        $validator = Validator::make($request->all(), [
            'nombre' => [
                Rule::unique('articulos', 'Nombre')->where(function ($query) {
                    $query->where('PkEmpresa', Session('emp-id'));
                })],
            'pkcategoria'=>'required'
        ], [
            'nombre.unique' => 'Este nombre ya existe',
            'pkcategoria.required'=>'Seleccionar elemento de la lista'
        ]);

        if ($validator->fails()) {
            return redirect('/articulos')
                ->with('error_code', 5)
                ->withErrors($validator)
                ->withInput();
        }else{
            DB::table('articulos')->insert([
                    'Nombre' => $request->input('nombre'),
                    'Descripcion' => $request->input('descripcion'),
                    'Cantidad' => 0,
                    'PrecioVenta' => $request->input('precio_venta'),
                    'PkCategoria'=> $request->input('pkcategoria'),
                    'PkEmpresa' => Session('emp-id'),
                    'user_id'=>Auth::user()->id,
                ]
            );
            return back()->with('error_code', 0);
        }

    }

    public function update(Request $request)
    {
        $id = $request->input('pkarticulo');
        $validator = Validator::make($request->all(), [
            'edit_nombres' => [
                Rule::unique('articulos', 'Nombre')->where(function ($query) {
                    $query->where('PkEmpresa', Session('emp-id'));
                })->ignore($id, 'id')],
            'pk_edit_categ'=>'required'
        ], [
            'nombres.unique' => 'Este nombre ya existe',
            'pk_edit_categ.required'=>'Seleccionar elemento de la lista'
        ]);

        if ($validator->fails()) {
            return redirect('/articulo')
                ->with('error_code', 4)
                ->withErrors($validator)
                ->withInput();
        } else {
            DB::table('articulos')->where('id', $id)
                ->update([
                        'Nombre' => $request->input('nombres'),
                        'Descripcion' => $request->input('descripciones'),
                        'PrecioVenta' => $request->input('precio'),
                        'PkCategoria' => $request->input('pkcategorias'),
                    ]
                );
            return back()->with('error_code', 0);
        }
    }

    public function delete($id)
    {
        try {
            DB::table('articulos')->where('id', '=', $id)->delete();
            return back();
        } catch (QueryException $e) {
            $notificacion = array(
                'message' => 'No se puede eliminar',
                'alert-type' => 'error'
            );
            return redirect('/articulos')->with($notificacion);
        }

    }

    public function Lote($id){
        $nombre = DB::table('articulos')
            ->where('id', '=', $id)->value('Nombre');
        session()->put('articulo-nombre-lote', $nombre);
        session()->put('articulo-id-lote', $id);
        return redirect('/lotes');
    }

    public function LoteVista()
    {
        $lotes = DB::table('lotes')
            ->where('PkArticulo', '=', Session('articulo-id-lote'))->get();
        return view('lotearticulo', ['lotes' => $lotes]);
    }

    function EditarFetch(Request $request)
    {
        if($request->get('query'))
        {
            $query = $request->get('query');
            $nombre = DB::table ('articulos')->where('id', '=', $query)->value('Nombre');
            $categoriaId = DB::table ('articulos')->where('id', '=', $query)->value('PkCategoria');
            $categoriaN = DB::table ('categorias')->where('id', '=', $categoriaId)->value('Nombre');
            $descricion = DB::table ('articulos')->where('id', '=', $query)->value('Descripcion');
            $precio = DB::table ('articulos')->where('id', '=', $query)->value('PrecioVenta');
            $output = $nombre.','.$categoriaId.','.$categoriaN.','.$descricion.','.$precio;
            echo $output;
        }
    }

    function Artfetch(Request $request)
    {
        if($request->get('query'))
        {
            $query = $request->get('query');
            $data = DB::table('categorias')
                ->where([['PkEmpresa', '=',Session('emp-id')],['Nombre', 'LIKE', "%{$query}%"]])
                ->get();
            $output = '<ul class="dropdown-menu inner show">';
            foreach($data as $row)
            {
                $output .= '<li class="pl-1 art" id="'.$row->id.'"><a href="#" style="color: #1b1e21">'.$row->Nombre.'</a></li>';
            }
            $output .= '</ul>';
            echo $output;
        }
    }


}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Categorias extends Model
{
    public $table="categorias";
    public function childs(){
        return $this->hasMany('App\Categorias','CategoriaPadre_id','id');
    }
}
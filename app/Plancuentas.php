<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Plancuentas extends Model
{
    public $table="cuenta";
    public function childs() {
        return $this->hasMany('App\Plancuentas','CuentaPadre','id_cuenta') ;
    }
}

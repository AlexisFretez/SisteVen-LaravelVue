<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    protected $fillable = [
        'nombre', 'tipo_documento','num_documento','direccion','telefono','email'
    ];

    //Funcion que indica que un perosna  esta relacionado con proveedor
    public function proveedor(){
        return $this->hasOne('App\Proveedor');
    }

    //Hace referencia al modelo User
    public function user(){
        return $this->hasOne('App\User');
    }
}

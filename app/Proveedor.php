<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    //Especificamos a que tabla va ir dirigida o hace referencia
    protected $table = 'proveedores';
    protected $fillable = [
        'id', 'contacto', 'telefono_contacto'
    ];
    //Esto es cuando timestamps no se agrega en la tabla
    public $timestamps = false;

    //Funcion que detemina que un proveedor pertenese a una Persona
    public function persona(){
        return $this->belongsTo('App\Persona');
    }
}

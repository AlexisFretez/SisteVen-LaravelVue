<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    protected $table = 'roles';
    protected $fillable = ['nombre', 'descripcion', 'condicion'];

    //para anular timestamps
    public $timestamps = false;

    //Hace referencia al modelo Users
    public function users(){
        return $this->hasMany('App\user');
    }


}

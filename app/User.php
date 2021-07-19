<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'usuario', 'password', 'condicion', 'idrol'
    ];
    public $timestamps = false;
    

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    //Hace referencia al modelo Rol
    public function rol(){
        return $this->belongs('App\Rol');
    }

    //Hace referencia al modelo Persona
    public function persona(){
        return $this->belongs('App\Persona');
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetalleVenta extends Model
{
    //Este es para poner el nombre en singular
    protected $table= 'detalle_ventas';
    protected $fillable = [
        'idventa',
        'idarticulo',
        'cantidad',
        'precio',
        'descuento'

    ];
    public $timestamps = false;
}

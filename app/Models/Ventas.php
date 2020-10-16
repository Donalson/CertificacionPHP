<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ventas extends Model
{
    use HasFactory;

    //Especificamos el nombre de la tabla a utilizar
    protected $table='ventas';

    //Especificamos lo campos que pueden ser llenados
    protected $fillable = [
        'id',
        'Vendedor',
        'Fecha',
        'Nombre',
        'Direccion',
        'Nit',
        'SubTotal',
        'Total',
        'Pago',
        'Cambio',
      ];

    //Desabilitamos los campos created_at y updates_ad
    public $timestamps = false;
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detalles extends Model
{
    use HasFactory;

    //Especificamos el nombre de la tabla a utilizar
    protected $table='detalleventa';

    //Especificamos lo campos que pueden ser llenados
    protected $fillable = [
        'Factura',
        'Cantidad',
        'Descripcion',
        'Precio',
        'Total',        
      ];

    //Desabilitamos los campos created_at y updates_ad
    public $timestamps = false;
}

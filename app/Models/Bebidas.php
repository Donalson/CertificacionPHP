<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bebidas extends Model
{
    use HasFactory;

    //Especificamos el nombre de la tabla a utilizar
    protected $table='bebidas';

    //Especificamos lo campos que pueden ser llenados
    protected $fillable = [
        'id',
        'Nombre',
        'Precio',
        'Foto'
      ];

    //Desabilitamos los campos created_at y updates_ad
    public $timestamps = false;
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usuarios extends Model
{
    use HasFactory;

    //Especificamos el nombre de la tabla a utilizar
    protected $table='users';

    //Especificamos lo campos que pueden ser llenados
    protected $fillable = [
        'id',
        'Nombre',
        'Apellido',
        'Categoria',
        'Telefono',
        'DPI',
        'Fnacimiento',
        'username',
        'password'
      ];
}

<?php

namespace App\Http\Controllers;

use App\Models\Comidas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ComidasController extends Controller
{

    public function index()
    {
        //Verificamos que el usuarios tengo la categoria adecuada para acceder al modulo
        if(Auth::user()->Categoria == 'Dueño' || Auth::user()->Categoria == 'Administrador'){
            return view('comidas.index');
        }else{
            return view('ventas.ventas');
        }
    }

    public function datos(Request $request)
    {
        //Verificacion de la solicitud de datos en Json(vue) para devolverlos
        if($request->wantsJson()){
            $Comidas = Comidas::all();
            return $Comidas;
        }else{
            return view('home')->with('Mensaje','No se realizo la peticion de manera adecuada');
        }
    }
    
    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //Verificamos que el usuarios tengo la categoria adecuada para crear registros
        if(Auth::user()->Categoria == 'Dueño' || Auth::user()->Categoria == 'Administrador'){

            //Instanciamos el nuevo registro
            $Comida = new Comidas();
            //Llenamos los campos necesarios para que el registro este completo
            $Comida->Nombre = $request->Nombre;
            $Comida->Precio = $request->Precio;
            //En caso que se reciba un foto se almacenara de lo contrario se mandara como campo vacio
            if($request->hasFile('Foto')){
                $Comida->Foto = $request->Foto->store('uploads','public');
            }else{
                $Comida->Foto = '';
            }
            //Guardamos el registro
            $Comida->save();

            //Devolvemos el registro para mostralo en pantalla
            return $Comida;
        }
        else{
            return view('home')->with('Mensaje','No tienes permisos suficientes');
        }
    }

    public function show(Comidas $comidas)
    {
        //
    }

    public function edit(Comidas $comidas)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //Verificamos que el usuarios tengo la categoria adecuada para editar registros
        if(Auth::user()->Categoria == 'Dueño' || Auth::user()->Categoria == 'Administrador'){

            //Encontramos el registro con el id que nos mando la vista
            $Comida = Comidas::findOrFail($id);
            //Acutalizamos la informacion anteior con la recibida
            $Comida->Nombre = $request->Nombre;
            $Comida->Precio = $request->Precio;
            //En caso de recibir foto 
            if($request->hasFile('Foto')){
                //Borramos la foto anterior en caso de que exista
                Storage::delete('/public/'.$Comida->Foto);
                //Subimos y guardamos la nueva foto
                $Comida->Foto = $request->Foto->store('uploads','public');
            }
            //Guardamos el registro
            $Comida->save();

            //Lo devolvemos para mostralo en pantalla
            return $Comida;
        }
        else{
            return view('home')->with('Mensaje','No tienes permisos suficientes');
        }
    }

    public function destroy($id)
    {
        //Verificamos que el usuarios tengo la categoria adecuada para eliminar registros
        if(Auth::user()->Categoria == 'Dueño' || Auth::user()->Categoria == 'Administrador'){
            //Buscamos el registro en la base
            $Comida = Comidas::findOrFail($id);
            //Borramos la foto de la base de datos
            Storage::delete('/public/'.$Comida->Foto);
            //y por ultimo borramos el registro
            $Comida->delete();
        }else{
            return view('home')->with('Mensaje','No tienes permisos suficientes');
        }
    }
}

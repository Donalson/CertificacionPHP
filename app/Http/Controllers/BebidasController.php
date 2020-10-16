<?php

namespace App\Http\Controllers;

use App\Models\Bebidas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BebidasController extends Controller
{

    public function index()
    {
        //Verificacion de la categoria del usuarios
        if(Auth::user()->Categoria == 'Dueño' || Auth::user()->Categoria == 'Administrador'){
            return view('bebidas.index');
        }else{
            return view('ventas.ventas');
        }
    }

    public function datos(Request $request)
    {
        //Verificacion de la solicitud de datos en Json(vue) para devolverlos
        if($request->wantsJson()){
            $bebidas = Bebidas::all();
            return $bebidas;
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
            $Bebida = new Bebidas();
            //Llenamos los campos necesarios para que el registro este completo
            $Bebida->Nombre = $request->Nombre;
            $Bebida->Precio = $request->Precio;
            //En caso que se reciba un foto se almacenara de lo contrario se mandara como campo vacio
            if($request->hasFile('Foto')){
                $Bebida->Foto = $request->Foto->store('uploads','public');
            }else{
                $Bebida->Foto = '';
            }
            //Guardamos el registro
            $Bebida->save();

            //Devolvemos el registro para mostrarlo en pantalla
            return $Bebida;
        }
        else{
            return view('home')->with('Mensaje','No tienes permisos suficientes');
        }
    }

    public function show(Bebidas $bebidas)
    {
        //
    }

    public function edit(Bebidas $bebidas)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //Verificamos que el usuarios tengo la categoria adecuada para editar registros
        if(Auth::user()->Categoria == 'Dueño' || Auth::user()->Categoria == 'Administrador'){

            //Encontramos el registro con el id que nos mando la vista
            $Bebida = Bebidas::findOrFail($id);
            //Acutalizamos la informacion anterior con la recibida
            $Bebida->Nombre = $request->Nombre;
            $Bebida->Precio = $request->Precio;
            //En caso de recibir foto 
            if($request->hasFile('Foto')){
                //Borramos la foto anterior en caso de que exista
                Storage::delete('/public/'.$Bebida->Foto);
                //Subimos y guardamos la nueva foto
                $Bebida->Foto = $request->Foto->store('uploads','public');
            }
            //Guardamos el registro
            $Bebida->save();

            //Lo devolvemos para mostralo en pantalla
            return $Bebida;
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
            $Bebida = Bebidas::findOrFail($id);
            //Borramos la foto de la base de datos
            Storage::delete('/public/'.$Bebida->Foto);
            //y por ultimo borramos el registro
            $Bebida->delete();
        }else{
            return view('home')->with('Mensaje','No tienes permisos suficientes');
        }
    }
}

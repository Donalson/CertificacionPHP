<?php

namespace App\Http\Controllers;

use App\Models\Usuarios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UsuariosController extends Controller
{

    public function index(Request $request)
    {
        //Verificacion de la categoria del usuarios para dejarlo acceder al modulo
        if(Auth::user()->Categoria == 'Dueño' || Auth::user()->Categoria == 'Administrador'){
            return view('usuarios.index');
        }else{
            return view('ventas.ventas');
        }
    }

    public function datos(Request $request)
    {
        //Verificacion de la solicitud de datos en Json(vue) para devolverlos
        if($request->wantsJson() && Auth::user()->Categoria != 'Empleado'){
            //Seleccionamos los datos a devolver para no mostrar nada mas de lo necesario
            $usuarios = Usuarios::select('id', 'Nombre', 'Apellido', 'Categoria', 'Telefono', 'DPI', 'Fnacimiento', 'username')->get();
            return $usuarios;
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
            $usuario = new Usuarios();
            //Llenamos los campos necesarios para que el registro este completo
            $usuario->Nombre = $request->Nombre;
            $usuario->Apellido = $request->Apellido;
            $usuario->Categoria = $request->Categoria;
            $usuario->Telefono = $request->Telefono;
            $usuario->DPI = $request->DPI;
            $usuario->Fnacimiento = $request->Fnacimiento;
            $usuario->username = $request->username;
            //Antes de almacenar la contraseña se encripta con el metodo propio de laravel
            $usuario->password = bcrypt($request->password);
            //Guardamos el registro
            $usuario->save();

            //Devolvemos el registro para mostrarlo en pantalla
            return $usuario;
        }
        else{
            return view('home')->with('Mensaje','No tienes permisos suficientes');
        }

    }

    public function show(Usuarios $usuarios)
    {
        //
    }

    public function edit($id)
    {

    }

    public function update(Request $request, $id)
    {
        //Verificamos que el usuarios tengo la categoria adecuada para editar registros
        if(Auth::user()->Categoria == 'Dueño' || Auth::user()->Categoria == 'Administrador'){
            //Verificamos si el usuarios va a actualizar su contraseña
            if(empty(request()->get('password'))){

                //Encontramos el registro con el id que nos mando la vista
                $usuario = Usuarios::find($id);
                //Acutalizamos la informacion anterior con la recibida
                $usuario->Nombre = $request->Nombre;
                $usuario->Apellido = $request->Apellido;
                $usuario->Categoria = $request->Categoria;
                $usuario->Telefono = $request->Telefono;
                $usuario->DPI = $request->DPI;
                $usuario->Fnacimiento = $request->Fnacimiento;
                $usuario->username = $request->username; 
                //Guardamos el registro           
                $usuario->save();            
    
                //Lo devolvemos apra mostralo en pantalla
                return $usuario;
            }else{
    
                //Encontramos el registro con el id que nos mando la vista
                $usuario = Usuarios::find($id);
                //Acutalizamos la informacion anterior con la recibida
                $usuario->Nombre = $request->Nombre;
                $usuario->Apellido = $request->Apellido;
                $usuario->Categoria = $request->Categoria;
                $usuario->Telefono = $request->Telefono;
                $usuario->DPI = $request->DPI;
                $usuario->Fnacimiento = $request->Fnacimiento;
                $usuario->username = $request->username;
                //Es este caso se quiere actualizar la clave, haci que recibimos la clave y la encriptamos
                $usuario->password = bcrypt($request->password);
                //Guardamos el registro           
                $usuario->save();
                
                //Lo devolvemos apra mostralo en pantalla
                return $usuario;
            }
        }else{
            return view('home')->with('Mensaje','No tienes permisos suficientes');
        }
       
    }

    public function destroy($id)
    {
        //Verificamos que el usuarios tengo la categoria adecuada para eliminar registros
        if(Auth::user()->Categoria == 'Dueño' || Auth::user()->Categoria == 'Administrador'){
            //Buscamos el registro en la base
            $usuario = Usuarios::find($id);
            //Borramos el registro
            $usuario->delete();
        }else{
            return view('home')->with('Mensaje','No tienes permisos suficientes');
        }
    }
}

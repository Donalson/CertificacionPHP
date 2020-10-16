<?php

namespace App\Http\Controllers;

use App\Models\Ventas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VentasController extends Controller
{
    public function index()
    {
        //En caso de ser admin o dueño redireccionamos a la vista de las facturas
        if(Auth::user()->Categoria == 'Dueño' || Auth::user()->Categoria == 'Administrador'){
            return view('ventas.index');
        }else{
            //Caso contrario lo llevamos al registro de ventas
            return view('ventas.ventas');
        };
    }

    public function datos(Request $request)
    {
        //Verificacion de la solicitud de datos en Json(vue) para devolverlos
        if($request->wantsJson()){
            //Devolvemos los datos haciendo un Join para ver los datos de manera correcta
            $ventas = DB::table('ventas')
            ->join('users', 'ventas.Vendedor', '=', 'users.id')
            ->select('ventas.*', 'users.Nombre as Vnombre', 'users.Apellido as Vapellido')
            ->get();
            return $ventas;
        }
    }

    public function create()
    {
        //               
    }

    public function store(Request $request)
    {
        //Instanciamos el nuevo registro
        $Venta = new Ventas();

        //Verificamos si hay datos para el registro, si no hay se pondra C/F por defecto
        $Venta->Vendedor = Auth::user()->id;
        if (empty($request->Nombre)) {
            $Venta->Nombre = 'C/F';
        }else{
            $Venta->Nombre = $request->Nombre;
        }
        
        //Verificamos si hay datos para el registro, si no hay se pondra Ciudad por defecto
        if (empty($request->Direccion)) {
            $Venta->Direccion = 'Ciudad';
        }else{
            $Venta->Direccion = $request->Direccion;
        }

        //Verificamos si hay datos para el registro, si no hay se pondra C/F por defecto
        if (empty($request->Nit)) {
            $Venta->Nit = 'C/F';
        }else{
            $Venta->Nit = $request->Nit;
        }
        //Llenamos los campos necesarios para que el registro este completo
        $Venta->SubTotal = $request->SubTotal;
        $Venta->Total = $request->Total;
        $Venta->Pago = $request->Pago;
        $Venta->Cambio = $request->Cambio;
        
        //Guardamos el registro
        $Venta->save();

        //Retornamos el id de la venta para utilizar como identificador de los detalles
        return $Venta;    
    }

    public function show(Ventas $ventas)
    {
        //
    }

    public function edit(Ventas $ventas)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //Las ventas no se podran modificar por seguridad
        /*
        if(Auth::user()->Categoria == 'Dueño' || Auth::user()->Categoria == 'Administrador'){
            $Venta = Ventas::findOrFail($id);

            $Venta->Vendedor = Auth::user()->id;
            $Venta->Nombre = $request->Nombre;
            $Venta->Direccion = $request->Direccion;
            $Venta->Nit = $request->Nit;
            $Venta->Subtotal = $request->Subtotal;
            $Venta->Total = $request->Total;
            $Venta->Pago = $request->Pago;
            $Venta->Cambio = $request->Cambio;
            
            $Venta->save();

            return $Venta;  
        }
        else{
            return view('home')->with('Mensaje','No tienes permisos suficientes');
        }*/
    }

    public function destroy($id)
    {
        //Las ventas no se podran borrar por seguridad
        /*
        if(Auth::user()->Categoria == 'Dueño' || Auth::user()->Categoria == 'Administrador'){
            $Venta = Ventas::findOrFail($id);
            $Venta->delete();
        }else{
            return view('home')->with('Mensaje','No tienes permisos suficientes');
        }*/
    }
}

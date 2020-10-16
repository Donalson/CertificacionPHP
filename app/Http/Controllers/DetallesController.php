<?php

namespace App\Http\Controllers;

use App\Models\Detalles;
use Illuminate\Http\Request;

class DetallesController extends Controller
{
    public function index()
    {
        //En caso de llamar al index redirigir a ventas
        return view('ventas.ventas');
    }

    public function datos(Request $request)
    {
            //Verificacion de la solicitud de datos en Json(vue) para devolverlos
            if($request->wantsJson()){
            $detalles = Detalles::all();
            return $detalles;
        }
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //Instanciamos el nuevo registro
        $Detalle = new Detalles();

        //Llenamos los campos necesarios para que el registro este completo
        $Detalle->Factura = $request->Factura;
        $Detalle->Cantidad = $request->Cantidad;
        $Detalle->Descripcion = $request->Descripcion;
        $Detalle->Precio = $request->Precio;
        $Detalle->Total = $request->Total;
        
        //Guardamos el registro
        $Detalle->save();

    }

    public function show(Detalles $detalles)
    {
        //
    }

    public function edit(Detalles $detalles)
    {
        //
    }

    public function update(Request $request, Detalles $detalles)
    {
        //
    }

    public function destroy(Detalles $detalles)
    {
        //
    }
}

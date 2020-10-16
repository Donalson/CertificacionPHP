@extends('layouts.app')

@section('content')
<div class="container">
<br><br>
    <div class="row" style="">
        <a href="/bebidas" class="linkinicio nav-link">
            <div class="divinicio">
                <img src="storage/uploads/bebidas.png" width="100%" height="100%"/> <center>Bebidas</center>            
            </div>
        </a>   
    </div>

    @if(Session::has('Mensaje'))

    <br>
    <div class="alert alert-danger" role="alert">
        {{ Session::get('Mensaje') }}
    </div>
    @endif

</div>
@endsection

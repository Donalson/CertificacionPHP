@extends('layouts.app')

@section('content')
<div class="container">
<br><br>
    <div class="row" style="">
        <a href="/comidas" class="linkinicio nav-link">
            <div class="divinicio">
                <img src="storage/uploads/comidas.png" width="100%" height="100%"/> <center>Comidas</center>            
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

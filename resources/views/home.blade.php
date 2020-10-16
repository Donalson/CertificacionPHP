@extends('layouts.app')

@section('content')
<div class="container">
<br><br>
    <div class="row" style="">
                  
    </div>

    @if(Session::has('Mensaje'))

    <br>
    <div class="alert alert-danger" role="alert">
        {{ Session::get('Mensaje') }}
    </div>
    @endif

</div>
@endsection

@extends('layouts.app')

@section('content')

    <div class="container-fluid">

        @if(Session::has('Mensaje'))

        <div class="alert alert-danger" role="alert">
            {{ Session::get('Mensaje') }}
        </div>
        @endif

        <bebidas></bebidas>

    </div>
@endsection
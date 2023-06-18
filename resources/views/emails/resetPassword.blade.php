@extends('emails.layout')

@section('css')
    <style>
        .code {
            letter-spacing: 1.2rem;
            font-weight: bold;

        }

        .text {
            text-align: justify;
            font-size: 16px;
            padding: 0.8rem;
        }
    </style>
@endsection
@section('content')
    <center>
        <h1>Restablecer clave</h1>
    </center>
    <p class="text">Si usted no ha solicitado el restablecimiento de su clave secreta, pongase en contacto con soporte, la
        seguridad de
        sus datos es importante para nosotros.</p>
    <br />
    <center>
        <h1 class="code">{{$data->code}}</h1>
    </center>
    <br
    <p class="text">
        El codigo proporcionado tiene un limite de vencimiento para mayor seguridad. Por favor restablezca su clave antes de 5 minutos
    </p>
@endsection

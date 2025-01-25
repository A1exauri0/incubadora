@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Verificación de correo electrónico</h1>
    <p>Por favor, verifica tu correo electrónico para continuar.</p>

    @if (session('resent'))
        <div class="alert alert-success" role="alert">
            Se ha enviado un nuevo enlace de verificación a tu correo electrónico.
        </div>
    @endif

    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit" class="btn btn-primary">Reenviar correo de verificación</button>
    </form>
</div>
@endsection

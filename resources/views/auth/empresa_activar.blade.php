@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h2 class="mb-4 text-center">Activar cuenta de empresa</h2>

    <div class="card shadow-sm p-4">
        <form method="POST" action="{{ route('empresa.activar.store', $solicitud->token) }}">
            @csrf

            <div class="mb-3">
                <label>Correo</label>
                <input type="email" class="form-control" value="{{ $solicitud->correo }}" disabled>
            </div>

            <div class="mb-3">
                <label>Nombre de empresa</label>
                <input type="text" class="form-control" value="{{ $solicitud->nombre }}" disabled>
            </div>

            <div class="mb-3">
                <label>Nueva contraseña</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Confirmar contraseña</label>
                <input type="password" name="password_confirmation" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-dark w-100">Activar cuenta</button>
        </form>

                @if ($errors->any())
            <div class="alert alert-danger mt-3">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
</div>
@endsection

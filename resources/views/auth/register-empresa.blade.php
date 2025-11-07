@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Registro de Empresa</h2>

    <form method="POST" action="{{ route('registro.empresa.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label>Nombre de la Empresa</label>
            <input type="text" name="nombre" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Correo</label>
            <input type="email" name="correo" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>NIT</label>
            <input type="text" name="nit" class="form-control">
        </div>
        <div class="mb-3">
            <label>Teléfono</label>
            <input type="text" name="telefono" class="form-control">
        </div>
        <div class="mb-3">
            <label>Dirección</label>
            <input type="text" name="direccion" class="form-control">
        </div>
        <div class="mb-3">
            <label>Descripción</label>
            <textarea name="descripcion" class="form-control" rows="3"></textarea>
        </div>
        <div class="mb-3">
            <label>Logo de la empresa</label>
            <input type="file" name="logo" class="form-control">
        </div>
        <div class="mb-3">
            <label>Foto del local</label>
            <input type="file" name="foto_local" class="form-control">
        </div>

        <button type="submit" class="btn btn-dark">Enviar Solicitud</button>
    </form>
</div>
@endsection

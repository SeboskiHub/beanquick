@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2 class="text-center mb-4">Agregar nuevo producto</h2>

    {{-- Agregamos enctype para permitir subida de archivos --}}
    <form action="{{ route('productos.store') }}" method="POST" enctype="multipart/form-data" class="w-75 mx-auto">
        @csrf
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre del producto</label>
            <input type="text" name="nombre" id="nombre" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea name="descripcion" id="descripcion" class="form-control" rows="3"></textarea>
        </div>

        <div class="mb-3">
            <label for="precio" class="form-label">Precio</label>
            <input type="number" name="precio" id="precio" step="0.01" class="form-control" required>
        </div>

        {{-- Campo para imagen --}}
        <div class="mb-3">
            <label for="imagen" class="form-label">Imagen del producto</label>
            <input type="file" name="imagen" id="imagen" class="form-control" accept="image/*" onchange="previewImage(event)">
        </div>

        {{-- Vista previa de la imagen --}}
        <div class="text-center mb-3">
            <img id="preview" src="#" alt="Vista previa" class="img-fluid rounded shadow-sm d-none" style="max-width: 200px;">
        </div>

        <div class="text-center">
            <button type="submit" class="btn btn-success">Guardar producto</button>
            <a href="{{ route('productos.index') }}" class="btn btn-secondary">Volver</a>
        </div>
    </form>
</div>

{{-- Script para vista previa --}}
<script>
    function previewImage(event) {
        const preview = document.getElementById('preview');
        const file = event.target.files[0];

        if (file) {
            const reader = new FileReader();
            reader.onload = e => {
                preview.src = e.target.result;
                preview.classList.remove('d-none');
            };
            reader.readAsDataURL(file);
        }
    }
</script>
@endsection

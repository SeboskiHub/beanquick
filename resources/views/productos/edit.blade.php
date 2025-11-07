@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2 class="text-center mb-4">Editar producto</h2>

    {{-- enctype necesario para subir archivos --}}
    <form action="{{ route('productos.update', $producto->id) }}" method="POST" enctype="multipart/form-data" class="w-75 mx-auto">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre del producto</label>
            <input type="text" name="nombre" id="nombre" class="form-control" value="{{ $producto->nombre }}" required>
        </div>

        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea name="descripcion" id="descripcion" class="form-control" rows="3">{{ $producto->descripcion }}</textarea>
        </div>

        <div class="mb-3">
            <label for="precio" class="form-label">Precio</label>
            <input type="number" name="precio" id="precio" step="0.01" class="form-control" value="{{ $producto->precio }}" required>
        </div>

        {{-- Imagen actual --}}
        @if ($producto->imagen)
            <div class="text-center mb-3">
                <p class="mb-1 fw-semibold">Imagen actual:</p>
                <img src="{{ asset('storage/' . $producto->imagen) }}" alt="Imagen actual" class="img-fluid rounded shadow-sm" style="max-width: 200px;">
            </div>
        @endif

        {{-- Campo para nueva imagen --}}
        <div class="mb-3">
            <label for="imagen" class="form-label">Cambiar imagen</label>
            <input type="file" name="imagen" id="imagen" class="form-control" accept="image/*" onchange="previewImage(event)">
        </div>

        {{-- Vista previa --}}
        <div class="text-center mb-3">
            <img id="preview" src="#" alt="Vista previa" class="img-fluid rounded shadow-sm d-none" style="max-width: 200px;">
        </div>

        <div class="text-center">
            <button type="submit" class="btn btn-primary">Actualizar</button>
            <a href="{{ route('productos.index') }}" class="btn btn-secondary">Volver</a>
        </div>
    </form>
</div>

{{-- Script para vista previa de imagen --}}
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

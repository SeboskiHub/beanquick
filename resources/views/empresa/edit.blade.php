@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4"><i class="bi bi-building"></i> Editar Empresa</h2>
    
    <form method="POST" action="{{ route('empresa.update', $empresa->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" value="{{ $empresa->nombre }}" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="telefono" class="form-label">Teléfono</label>
                    <input type="text" class="form-control" id="telefono" name="telefono" value="{{ $empresa->telefono }}">
                </div>
            </div>
        </div>

        <div class="mb-3">
            <label for="direccion" class="form-label">Dirección</label>
            <input type="text" class="form-control" id="direccion" name="direccion" value="{{ $empresa->direccion }}">
        </div>

        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea class="form-control" id="descripcion" name="descripcion" rows="3">{{ $empresa->descripcion }}</textarea>
        </div>

        <hr class="my-4">

        {{-- Logo --}}
        <div class="mb-3">
            <label for="logo" class="form-label">
                <i class="bi bi-image"></i> Logo de la Empresa
            </label>
            
            @if($empresa->logo)
                <div class="mb-2">
                    <p class="mb-1 small text-muted">Logo actual:</p>
                    <img src="{{ asset('storage/' . $empresa->logo) }}" 
                         alt="Logo actual" 
                         class="img-thumbnail" 
                         style="max-width: 150px; max-height: 150px; object-fit: contain;">
                </div>
            @endif
            
            <input type="file" 
                   class="form-control" 
                   id="logo" 
                   name="logo" 
                   accept="image/*"
                   onchange="previewImage(event, 'preview-logo')">
            <small class="form-text text-muted">Formatos: JPG, PNG, WEBP. Máximo 2MB.</small>
            
            {{-- Vista previa --}}
            <div class="mt-2">
                <img id="preview-logo" 
                     src="#" 
                     alt="Vista previa logo" 
                     class="img-thumbnail d-none" 
                     style="max-width: 150px; max-height: 150px; object-fit: contain;">
            </div>
        </div>

        {{-- Foto del Local --}}
        <div class="mb-4">
            <label for="foto_local" class="form-label">
                <i class="bi bi-shop"></i> Foto del Local
            </label>
            
            @if($empresa->foto_local)
                <div class="mb-2">
                    <p class="mb-1 small text-muted">Foto actual:</p>
                    <img src="{{ asset('storage/' . $empresa->foto_local) }}" 
                         alt="Foto local actual" 
                         class="img-thumbnail" 
                         style="max-width: 300px; max-height: 200px; object-fit: cover;">
                </div>
            @endif
            
            <input type="file" 
                   class="form-control" 
                   id="foto_local" 
                   name="foto_local" 
                   accept="image/*"
                   onchange="previewImage(event, 'preview-local')">
            <small class="form-text text-muted">Formatos: JPG, PNG, WEBP. Máximo 4MB. Recomendado: formato horizontal.</small>
            
            {{-- Vista previa --}}
            <div class="mt-2">
                <img id="preview-local" 
                     src="#" 
                     alt="Vista previa local" 
                     class="img-thumbnail d-none" 
                     style="max-width: 300px; max-height: 200px; object-fit: cover;">
            </div>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-circle"></i> Actualizar
            </button>
            <a href="{{ route('empresa.dashboard', $empresa->id) }}" class="btn btn-secondary">
                <i class="bi bi-x-circle"></i> Cancelar
            </a>
        </div>
    </form>
</div>

{{-- Script para vista previa --}}
<script>
    function previewImage(event, previewId) {
        const preview = document.getElementById(previewId);
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
@extends('layouts.app')

@section('content')
{{-- Header tipo portada con imagen de fondo --}}
<div class="position-relative" style="margin-top: -1rem;">
    {{-- Imagen de fondo del local --}}
    <div class="position-relative overflow-hidden" style="height: 350px;">
        @if($empresa->foto_local)
            <img src="{{ asset('storage/' . $empresa->foto_local) }}" 
                 alt="{{ $empresa->nombre }}" 
                 class="w-100 h-100" 
                 style="object-fit: cover; filter: brightness(0.5);">
        @else
            <div class="w-100 h-100" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);"></div>
        @endif
        
        {{-- Overlay oscuro --}}
        <div class="position-absolute top-0 start-0 w-100 h-100" 
             style="background: rgba(0,0,0,0.3);"></div>
        
        {{-- Contenido sobre la imagen --}}
        <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-auto">
                        {{-- Logo circular --}}
                        <div class="bg-white rounded-circle p-2 shadow-lg" 
                             style="width: 140px; height: 140px; display: flex; align-items: center; justify-content: center;">
                            @if($empresa->logo)
                                <img src="{{ asset('storage/' . $empresa->logo) }}" 
                                     alt="Logo {{ $empresa->nombre }}" 
                                     class="rounded-circle"
                                     style="width: 120px; height: 120px; object-fit: contain;">
                            @else
                                <div class="text-center">
                                    <i class="bi bi-building text-muted" style="font-size: 3rem;"></i>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="col">
                        <h1 class="text-white fw-bold mb-2">{{ $empresa->nombre }}</h1>
                        <div class="text-white">
                            @if($empresa->direccion)
                                <p class="mb-1">
                                    <i class="bi bi-geo-alt-fill"></i> {{ $empresa->direccion }}
                                </p>
                            @endif
                            @if($empresa->telefono)
                                <p class="mb-1">
                                    <i class="bi bi-telephone-fill"></i> {{ $empresa->telefono }}
                                </p>
                            @endif
                            @if($empresa->descripcion)
                                <p class="mb-0 opacity-75">
                                    <i class="bi bi-info-circle"></i> {{ $empresa->descripcion }}
                                </p>
                            @endif
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('empresa.edit', $empresa->id) }}" class="btn btn-light me-2">
                                <i class="bi bi-pencil"></i> Editar Información
                            </a>
                            <a href="{{ route('empresa.pedidos') }}" class="btn btn-info text-white">
                                <i class="bi bi-box-seam"></i> Ver Pedidos
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Sección de productos --}}
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0"><i class="bi bi-grid"></i> Productos</h3>
        <a href="{{ route('productos.create', $empresa->id) }}" class="btn btn-success">
            <i class="bi bi-plus-circle"></i> Agregar Producto
        </a>
    </div>

    @if($empresa->productos->count() > 0)
        <div class="row g-4">
            @foreach ($empresa->productos as $producto)
                <div class="col-md-6 col-lg-4 col-xl-3">
                    <div class="card h-100 shadow-sm">
                        {{-- Imagen del producto --}}
                        <div style="height: 200px; overflow: hidden; background-color: #f8f9fa;">
                            @if($producto->imagen)
                                <img src="{{ asset('storage/' . $producto->imagen) }}" 
                                     class="card-img-top" 
                                     alt="{{ $producto->nombre }}"
                                     style="width: 100%; height: 100%; object-fit: cover;">
                            @else
                                <div class="d-flex align-items-center justify-content-center h-100">
                                    <div class="text-center text-muted">
                                        <i class="bi bi-image" style="font-size: 3rem;"></i>
                                        <p class="mt-2 mb-0 small">Sin imagen</p>
                                    </div>
                                </div>
                            @endif
                        </div>

                        {{-- Contenido de la card --}}
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $producto->nombre }}</h5>
                            <p class="card-text text-muted small flex-grow-1">
                                {{ Str::limit($producto->descripcion, 80) ?? 'Sin descripción' }}
                            </p>
                            <div class="mb-3">
                                <span class="badge bg-success fs-6">
                                    ${{ number_format($producto->precio, 2) }}
                                </span>
                            </div>
                            
                            {{-- Botones de acción --}}
                            <div class="d-grid gap-2">
                                <a href="{{ route('productos.edit', $producto->id) }}" 
                                   class="btn btn-warning btn-sm">
                                    <i class="bi bi-pencil"></i> Editar
                                </a>
                                <form action="{{ route('productos.destroy', $producto->id) }}" 
                                      method="POST" 
                                      onsubmit="return confirm('¿Estás seguro de eliminar este producto?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm w-100">
                                        <i class="bi bi-trash"></i> Eliminar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="alert alert-info text-center">
            <i class="bi bi-box" style="font-size: 3rem;"></i>
            <p class="mt-3 mb-2">No hay productos registrados aún.</p>
            <a href="{{ route('productos.create', $empresa->id) }}" class="btn btn-success">
                <i class="bi bi-plus-circle"></i> Agregar el primero
            </a>
        </div>
    @endif
</div>

<style>
    /* Animación suave para hover en cards */
    .card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }
</style>
@endsection
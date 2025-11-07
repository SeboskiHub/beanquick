@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2 class="text-center mb-4"><i class="bi bi-shop"></i> Empresas Registradas</h2>

    @if($empresas->isEmpty())
        <div class="alert alert-info text-center">
            <i class="bi bi-inbox" style="font-size: 3rem;"></i>
            <p class="mt-3 mb-0">No hay empresas registradas aún.</p>
        </div>
    @else
        <div class="row g-4">
            @foreach($empresas as $empresa)
                <div class="col-sm-6 col-md-4 col-lg-3">
                    <div class="card h-100 shadow-sm hover-card">
                        {{-- Logo de la empresa --}}
                        <div class="d-flex justify-content-center align-items-center pt-4 pb-3" style="min-height: 180px;">
                            @if($empresa->logo)
                                <img src="{{ asset('storage/' . $empresa->logo) }}" 
                                     alt="Logo {{ $empresa->nombre }}" 
                                     class="img-fluid"
                                     style="max-width: 140px; max-height: 140px; object-fit: contain;">
                            @else
                                <div class="text-center text-muted">
                                    <i class="bi bi-building" style="font-size: 5rem;"></i>
                                </div>
                            @endif
                        </div>

                        {{-- Información de la empresa --}}
                        <div class="card-body d-flex flex-column text-center">
                            <h5 class="card-title mb-2">{{ $empresa->nombre }}</h5>
                            
                            @if($empresa->direccion)
                                <p class="text-muted small mb-1">
                                    <i class="bi bi-geo-alt"></i> {{ Str::limit($empresa->direccion, 40) }}
                                </p>
                            @endif
                            
                            @if($empresa->telefono)
                                <p class="text-muted small mb-2">
                                    <i class="bi bi-telephone"></i> {{ $empresa->telefono }}
                                </p>
                            @endif

                            <p class="card-text text-muted small flex-grow-1 mb-3">
                                {{ Str::limit($empresa->descripcion ?? 'Sin descripción disponible', 80) }}
                            </p>
                            
                            <a href="{{ route('cliente.empresa.productos', $empresa->id) }}" 
                               class="btn btn-primary w-100">
                                <i class="bi bi-bag"></i> Ver Productos
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<style>
    /* Animación hover para las cards */
    .hover-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .hover-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 0.75rem 1.5rem rgba(0, 0, 0, 0.15) !important;
    }

    /* Asegurar que las imágenes no se distorsionen */
    .hover-card img {
        transition: transform 0.3s ease;
    }
    .hover-card:hover img {
        transform: scale(1.05);
    }
</style>
@endsection
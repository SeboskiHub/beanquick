@extends('layouts.app')

@section('content')
<div class="container mt-5">
    {{-- Header de bienvenida --}}
    <div class="text-center mb-5">
        <h2 class="mb-3">¡Bienvenido, {{ $user->name }}! 👋</h2>
        <p class="text-muted">Explora nuestras empresas y productos, realiza tus pedidos fácilmente.</p>
        
        {{-- Sección de acceso rápido --}}
    <div class="row g-4 mt-4">
        <div class="col-md-4">
            <div class="card border-primary text-center h-100">
                <div class="card-body">
                    <i class="bi bi-shop text-primary" style="font-size: 3rem;"></i>
                    <h5 class="card-title mt-3">Explora Empresas</h5>
                    <p class="card-text text-muted">Descubre todas las empresas registradas y sus productos.</p>
                    <a href="{{ route('cliente.empresas') }}" class="btn btn-outline-primary">
                        Ver empresas
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-success text-center h-100">
                <div class="card-body">
                    <i class="bi bi-cart text-success" style="font-size: 3rem;"></i>
                    <h5 class="card-title mt-3">Tu Carrito</h5>
                    <p class="card-text text-muted">Revisa los productos que has agregado a tu carrito.</p>
                    <a href="{{ route('carrito.index') }}" class="btn btn-outline-success">
                        Ver carrito
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-info text-center h-100">
                <div class="card-body">
                    <i class="bi bi-clock-history text-info" style="font-size: 3rem;"></i>
                    <h5 class="card-title mt-3">Mis Pedidos</h5>
                    <p class="card-text text-muted">Consulta el estado de tus pedidos realizados.</p>
                    <a href="{{ route('cliente.pedidos') }}" class="btn btn-outline-info">
                        Ver pedidos
                    </a>
                </div>
            </div>
        </div>
    </div>
    </div>

    <hr class="my-5">

    {{-- Sección de productos destacados --}}
    <div class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="mb-0">
                <i class="bi bi-star-fill text-warning"></i> Productos Destacados
            </h3>
        </div>

        @if($productosDestacados->count() > 0)
            <div class="row g-4">
                @foreach($productosDestacados as $producto)
                    <div class="col-sm-6 col-md-4 col-lg-3">
                        <div class="card h-100 shadow-sm hover-card">
                            {{-- Imagen del producto --}}
                            <div style="height: 200px; overflow: hidden; background-color: #f8f9fa; position: relative;">
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
                                
                                {{-- Badge de más vendido --}}
                                @if($producto->total_vendido > 0)
                                    <div class="position-absolute top-0 start-0 m-2">
                                        <span class="badge bg-warning text-dark">
                                            <i class="bi bi-fire"></i> Popular
                                        </span>
                                    </div>
                                @endif

                                {{-- Badge de precio --}}
                                <div class="position-absolute top-0 end-0 m-2">
                                    <span class="badge bg-success fs-6 shadow-sm">
                                        ${{ number_format($producto->precio, 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>

                            {{-- Contenido de la card --}}
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title mb-2">{{ $producto->nombre }}</h5>
                                
                                {{-- Empresa --}}
                                @if($producto->empresa)
                                    <p class="text-muted small mb-2">
                                        <i class="bi bi-shop"></i> {{ $producto->empresa->nombre }}
                                    </p>
                                @endif

                                <p class="card-text text-muted small flex-grow-1">
                                    {{ Str::limit($producto->descripcion ?? 'Sin descripción', 70) }}
                                </p>

                                {{-- Información de ventas --}}
                                @if($producto->total_vendido > 0)
                                    <p class="text-muted small mb-2">
                                        <i class="bi bi-graph-up"></i> {{ $producto->total_vendido }} vendidos
                                    </p>
                                @endif

                                {{-- Formulario de agregar al carrito --}}
                                <form action="{{ route('carrito.agregar', $producto->id) }}" method="POST" class="mt-auto">
                                    @csrf
                                    <div class="input-group mb-2">
                                        <button class="btn btn-outline-secondary btn-sm" type="button" onclick="decrementarCantidad(this)">
                                            <i class="bi bi-dash"></i>
                                        </button>
                                        <input type="number" 
                                               name="cantidad" 
                                               value="1" 
                                               min="1" 
                                               max="99"
                                               class="form-control form-control-sm text-center cantidad-input" 
                                               style="max-width: 60px;">
                                        <button class="btn btn-outline-secondary btn-sm" type="button" onclick="incrementarCantidad(this)">
                                            <i class="bi bi-plus"></i>
                                        </button>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-sm w-100">
                                        <i class="bi bi-cart-plus"></i> Agregar al carrito
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="alert alert-info text-center">
                <i class="bi bi-info-circle"></i> Aún no hay productos disponibles. Sé el primero en realizar un pedido.
            </div>
        @endif
    </div>
</div>

{{-- Script para incrementar/decrementar cantidad --}}
<script>
    function incrementarCantidad(btn) {
        const input = btn.parentElement.querySelector('.cantidad-input');
        const valorActual = parseInt(input.value) || 1;
        const max = parseInt(input.max) || 99;
        if (valorActual < max) {
            input.value = valorActual + 1;
        }
    }

    function decrementarCantidad(btn) {
        const input = btn.parentElement.querySelector('.cantidad-input');
        const valorActual = parseInt(input.value) || 1;
        const min = parseInt(input.min) || 1;
        if (valorActual > min) {
            input.value = valorActual - 1;
        }
    }
</script>

<style>
    /* Animación hover para las cards */
    .hover-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .hover-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 0.75rem 1.5rem rgba(0, 0, 0, 0.15) !important;
    }
</style>
@endsection
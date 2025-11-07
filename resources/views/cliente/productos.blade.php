@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2 class="text-center mb-4">Productos de {{ $empresa->nombre }}</h2>

    <div class="text-center mb-4">
        <a href="{{ route('cliente.empresas') }}" class="btn btn-secondary">← Volver a empresas</a>
        <a href="{{ route('carrito.index') }}" class="btn btn-success ms-2">🛒 Ver carrito</a>
    </div>

    {{-- Mensaje de éxito --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    @endif

    @if($productos->isEmpty())
        <div class="alert alert-info text-center" role="alert">
            <i class="bi bi-box-seam"></i> Esta empresa aún no tiene productos disponibles.
        </div>
    @else
        <div class="row g-4">
            @foreach($productos as $producto)
                <div class="col-sm-6 col-md-4 col-lg-3">
                    <div class="card h-100 shadow-sm">
                        {{-- Imagen del producto --}}
                        <div style="height: 220px; overflow: hidden; background-color: #f8f9fa; position: relative;">
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
                            
                            {{-- Badge de precio flotante --}}
                            <div class="position-absolute top-0 end-0 m-2">
                                <span class="badge bg-success fs-6 shadow-sm">
                                    ${{ number_format($producto->precio, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>

                        {{-- Contenido de la card --}}
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title mb-2">{{ $producto->nombre }}</h5>
                            <p class="card-text text-muted small flex-grow-1">
                                {{ Str::limit($producto->descripcion ?? 'Sin descripción', 70) }}
                            </p>

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
    @endif
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

@endsection
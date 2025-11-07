@extends('layouts.app')

@section('content')
<div class="container mt-4">

    {{-- ✅ Confirmación de carga correcta --}}
    <div class="alert alert-info text-center">
        <strong>DEBUG:</strong> versión ajustada de <code>verProductos.blade.php</code> cargada correctamente.
    </div>

    {{-- Mensaje de éxito --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    @endif

    {{-- Encabezado --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Productos de {{ $empresa->nombre }}</h2>
        <div>
            <a href="{{ route('carrito.index') }}" class="btn btn-success me-2">
                🛒 Ver Carrito
            </a>
            <a href="{{ route('cliente.dashboard') }}" class="btn btn-secondary">
                ← Volver
            </a>
        </div>
    </div>

    {{-- Listado de productos --}}
    <div class="row">
        @forelse ($productos as $producto)
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body d-flex flex-column">
                        {{-- Información del producto --}}
                        <div class="mb-3">
                            <h5 class="card-title fw-semibold">{{ $producto->nombre }}</h5>
                            <p class="card-text text-muted small">{{ $producto->descripcion }}</p>
                        </div>

                        {{-- Precio y acción --}}
                        <div class="mt-auto">
                            <p class="fw-bold fs-5 mb-2">${{ number_format($producto->precio, 0, ',', '.') }}</p>

                            <form action="{{ route('carrito.agregar', $producto->id) }}" method="POST" class="d-flex align-items-center gap-2">
                                @csrf
                                <input 
                                    type="number" 
                                    name="cantidad" 
                                    value="1" 
                                    min="1" 
                                    class="form-control form-control-sm text-center" 
                                    style="width: 80px;"
                                >
                                <button type="submit" class="btn btn-primary btn-sm">
                                    Agregar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center text-muted mt-4">
                <p>Esta empresa aún no tiene productos disponibles.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection

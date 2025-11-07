@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0"><i class="bi bi-cart3"></i> Tu Carrito</h2>
        <div>
            <a href="{{ route('cliente.dashboard') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Seguir Comprando
            </a>
            <form action="{{ route('carrito.vaciar') }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" onclick="return confirm('¿Estás seguro de vaciar el carrito?')">
                    <i class="bi bi-trash"></i> Vaciar Carrito
                </button>
            </form>
        </div>
    </div>

    @if ($productos->isEmpty())
        <div class="alert alert-info text-center">
            <i class="bi bi-cart-x" style="font-size: 3rem;"></i>
            <p class="mt-3 mb-2">Tu carrito está vacío.</p>
            <a href="{{ route('cliente.dashboard') }}" class="btn btn-primary">Empieza a comprar aquí</a>
        </div>
    @else
        {{-- Vista Desktop: Tabla --}}
        <div class="d-none d-lg-block">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th style="width: 100px;">Imagen</th>
                        <th>Producto</th>
                        <th style="width: 150px;">Precio Unitario</th>
                        <th style="width: 200px;">Cantidad</th>
                        <th style="width: 150px;">Subtotal</th>
                        <th style="width: 120px;" class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @php $total = 0; @endphp
                    @foreach ($productos as $producto)
                        @php
                            $subtotal = $producto->precio * $producto->pivot->cantidad;
                            $total += $subtotal;
                        @endphp
                        <tr>
                            <td class="text-center">
                                @if($producto->imagen)
                                    <img src="{{ asset('storage/' . $producto->imagen) }}" 
                                         alt="{{ $producto->nombre }}" 
                                         class="img-thumbnail" 
                                         style="width: 80px; height: 80px; object-fit: cover;">
                                @else
                                    <div class="bg-light d-flex align-items-center justify-content-center" 
                                         style="width: 80px; height: 80px; border: 1px solid #dee2e6; border-radius: 0.25rem;">
                                        <i class="bi bi-image text-muted"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <strong>{{ $producto->nombre }}</strong>
                                @if($producto->descripcion)
                                    <br><small class="text-muted">{{ Str::limit($producto->descripcion, 50) }}</small>
                                @endif
                            </td>
                            <td class="text-center fw-bold">${{ number_format($producto->precio, 0, ',', '.') }}</td>
                            <td>
                                <form action="{{ route('carrito.actualizar', $producto->id) }}" method="POST" class="d-flex align-items-center">
                                    @csrf
                                    <div class="input-group" style="width: 140px;">
                                        <button class="btn btn-outline-secondary btn-sm" type="button" onclick="decrementarCantidad(this)">
                                            <i class="bi bi-dash"></i>
                                        </button>
                                        <input type="number" 
                                               name="cantidad" 
                                               value="{{ $producto->pivot->cantidad }}" 
                                               min="1" 
                                               max="99"
                                               class="form-control form-control-sm text-center cantidad-input">
                                        <button class="btn btn-outline-secondary btn-sm" type="button" onclick="incrementarCantidad(this)">
                                            <i class="bi bi-plus"></i>
                                        </button>
                                    </div>
                                    <button type="submit" class="btn btn-sm btn-primary ms-2">
                                        <i class="bi bi-check-lg"></i>
                                    </button>
                                </form>
                            </td>
                            <td class="text-center fw-bold text-success">${{ number_format($subtotal, 0, ',', '.') }}</td>
                            <td class="text-center">
                                <form action="{{ route('carrito.eliminar', $producto->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Eliminar este producto?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="table-light">
                    <tr>
                        <th colspan="4" class="text-end fs-5">Total:</th>
                        <th colspan="2" class="text-center fs-4 text-success">${{ number_format($total, 0, ',', '.') }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>

        {{-- Vista Mobile: Cards --}}
        <div class="d-lg-none">
            @php $total = 0; @endphp
            @foreach ($productos as $producto)
                @php
                    $subtotal = $producto->precio * $producto->pivot->cantidad;
                    $total += $subtotal;
                @endphp
                <div class="card mb-3 shadow-sm">
                    <div class="row g-0">
                        <div class="col-4">
                            @if($producto->imagen)
                                <img src="{{ asset('storage/' . $producto->imagen) }}" 
                                     alt="{{ $producto->nombre }}" 
                                     class="img-fluid h-100" 
                                     style="object-fit: cover; border-radius: 0.375rem 0 0 0.375rem;">
                            @else
                                <div class="bg-light d-flex align-items-center justify-content-center h-100">
                                    <i class="bi bi-image text-muted" style="font-size: 2rem;"></i>
                                </div>
                            @endif
                        </div>
                        <div class="col-8">
                            <div class="card-body p-3">
                                <h6 class="card-title mb-2">{{ $producto->nombre }}</h6>
                                <p class="mb-1 text-muted small">Precio: <strong>${{ number_format($producto->precio, 0, ',', '.') }}</strong></p>
                                
                                <form action="{{ route('carrito.actualizar', $producto->id) }}" method="POST" class="mb-2">
                                    @csrf
                                    <div class="d-flex align-items-center gap-2">
                                        <label class="small mb-0">Cantidad:</label>
                                        <div class="input-group" style="width: 120px;">
                                            <button class="btn btn-outline-secondary btn-sm" type="button" onclick="decrementarCantidad(this)">
                                                <i class="bi bi-dash"></i>
                                            </button>
                                            <input type="number" 
                                                   name="cantidad" 
                                                   value="{{ $producto->pivot->cantidad }}" 
                                                   min="1" 
                                                   class="form-control form-control-sm text-center cantidad-input">
                                            <button class="btn btn-outline-secondary btn-sm" type="button" onclick="incrementarCantidad(this)">
                                                <i class="bi bi-plus"></i>
                                            </button>
                                        </div>
                                        <button type="submit" class="btn btn-sm btn-primary">
                                            <i class="bi bi-check-lg"></i>
                                        </button>
                                    </div>
                                </form>

                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fw-bold text-success">Subtotal: ${{ number_format($subtotal, 0, ',', '.') }}</span>
                                    <form action="{{ route('carrito.eliminar', $producto->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Eliminar?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            {{-- Total en mobile --}}
            <div class="card bg-light shadow-sm mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Total:</h5>
                        <h4 class="mb-0 text-success">${{ number_format($total, 0, ',', '.') }}</h4>
                    </div>
                </div>
            </div>
        </div>

        @if($carrito->productos->count() > 0)
            <div class="d-grid gap-2 mt-4">
                <a href="{{ route('cliente.checkout') }}" class="btn btn-success btn-lg">
                    <i class="bi bi-check-circle"></i> Confirmar Pedido
                </a>
            </div>
        @endif

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
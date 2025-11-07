@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            {{-- Header --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0"><i class="bi bi-clipboard-check"></i> Confirmar Pedido</h2>
                <a href="{{ route('carrito.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Volver al carrito
                </a>
            </div>

            @if($carrito && $carrito->productos->count() > 0)
                {{-- Resumen del pedido --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="bi bi-list-ul"></i> Resumen del Pedido</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0 align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 80px;">Imagen</th>
                                        <th>Producto</th>
                                        <th class="text-center" style="width: 120px;">Precio</th>
                                        <th class="text-center" style="width: 100px;">Cantidad</th>
                                        <th class="text-end" style="width: 140px;">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $total = 0; @endphp
                                    @foreach($carrito->productos as $producto)
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
                                                         style="width: 60px; height: 60px; object-fit: cover;">
                                                @else
                                                    <div class="bg-light d-flex align-items-center justify-content-center" 
                                                         style="width: 60px; height: 60px; border: 1px solid #dee2e6; border-radius: 0.25rem;">
                                                        <i class="bi bi-image text-muted"></i>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                <strong>{{ $producto->nombre }}</strong>
                                                @if($producto->descripcion)
                                                    <br><small class="text-muted">{{ Str::limit($producto->descripcion, 40) }}</small>
                                                @endif
                                            </td>
                                            <td class="text-center">${{ number_format($producto->precio, 0, ',', '.') }}</td>
                                            <td class="text-center">
                                                <span class="badge bg-secondary">{{ $producto->pivot->cantidad }}</span>
                                            </td>
                                            <td class="text-end fw-bold text-success">${{ number_format($subtotal, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <td colspan="4" class="text-end fs-5 fw-bold">Total a pagar:</td>
                                        <td class="text-end fs-4 fw-bold text-success">${{ number_format($total, 0, ',', '.') }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Formulario de confirmación --}}
                <div class="card shadow-sm">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="bi bi-clock-history"></i> Información de Recogida</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('pedidos.store') }}" method="POST">
                            @csrf
                            
                            <div class="mb-4">
                                <label for="hora_recogida" class="form-label fw-bold">
                                    <i class="bi bi-alarm"></i> Hora estimada de recogida
                                </label>
                                <input type="time" 
                                       name="hora_recogida" 
                                       id="hora_recogida" 
                                       class="form-control form-control-lg" 
                                       required>
                                <small class="form-text text-muted">
                                    Selecciona la hora aproximada en la que recogerás tu pedido
                                </small>
                            </div>

                            {{-- Información adicional --}}
                            <div class="alert alert-info mb-4">
                                <i class="bi bi-info-circle"></i> 
                                <strong>Importante:</strong> Una vez confirmado el pedido, la empresa recibirá tu solicitud y podrás hacer seguimiento desde tu panel de pedidos.
                            </div>

                            {{-- Resumen final --}}
                            <div class="bg-light p-3 rounded mb-4">
                                <div class="row">
                                    <div class="col-6">
                                        <p class="mb-1 text-muted">Total de productos:</p>
                                        <h5 class="mb-0">{{ $carrito->productos->sum('pivot.cantidad') }} items</h5>
                                    </div>
                                    <div class="col-6 text-end">
                                        <p class="mb-1 text-muted">Monto total:</p>
                                        <h4 class="mb-0 text-success">${{ number_format($total, 0, ',', '.') }}</h4>
                                    </div>
                                </div>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-success btn-lg">
                                    <i class="bi bi-check-circle-fill"></i> Confirmar y Enviar Pedido
                                </button>
                                <a href="{{ route('carrito.index') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-pencil"></i> Modificar Carrito
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

            @else
                <div class="alert alert-warning text-center">
                    <i class="bi bi-cart-x" style="font-size: 3rem;"></i>
                    <p class="mt-3 mb-2">Tu carrito está vacío.</p>
                    <a href="{{ route('cliente.dashboard') }}" class="btn btn-primary">
                        <i class="bi bi-shop"></i> Ir a comprar
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

{{-- Validación de hora mínima (opcional) --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const horaInput = document.getElementById('hora_recogida');
        
        // Establecer hora mínima (actual + 30 minutos)
        const ahora = new Date();
        ahora.setMinutes(ahora.getMinutes() + 30);
        const horaMinima = ahora.toTimeString().slice(0, 5);
        
        horaInput.setAttribute('min', horaMinima);
        
        // Validación adicional
        horaInput.addEventListener('change', function() {
            const horaSeleccionada = this.value;
            if (horaSeleccionada < horaMinima) {
                alert('Por favor selecciona una hora al menos 30 minutos después de la hora actual.');
                this.value = '';
            }
        });
    });
</script>

@endsection
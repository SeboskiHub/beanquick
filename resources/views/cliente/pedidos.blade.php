@extends('layouts.app')

@section('content')
<div class="container mt-4">
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0"><i class="bi bi-clock-history"></i> Mis Pedidos</h2>
        <a href="{{ route('cliente.dashboard') }}" class="btn btn-outline-primary">
            <i class="bi bi-house"></i> Volver al inicio
        </a>
    </div>

    @if($pedidos->count() > 0)
        {{-- Vista Desktop: Tabla --}}
        <div class="d-none d-md-block">
            <div class="card shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center" style="width: 80px;">ID</th>
                                    <th>Fecha</th>
                                    <th class="text-center">Hora de recogida</th>
                                    <th class="text-center">Estado</th>
                                    <th class="text-end">Total</th>
                                    <th class="text-center" style="width: 150px;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pedidos as $pedido)
                                    <tr>
                                        <td class="text-center">
                                            <span class="badge bg-secondary">#{{ $pedido->id }}</span>
                                        </td>
                                        <td>
                                            <i class="bi bi-calendar3"></i>
                                            {{ $pedido->created_at->format('d/m/Y') }}
                                            <br>
                                            <small class="text-muted">{{ $pedido->created_at->format('H:i') }}</small>
                                        </td>
                                        <td class="text-center">
                                            <i class="bi bi-alarm"></i>
                                            <strong>{{ $pedido->hora_recogida }}</strong>
                                        </td>
                                        <td class="text-center">
                                            @if($pedido->estado == 'pendiente')
                                                <span class="badge bg-warning text-dark">
                                                    <i class="bi bi-hourglass-split"></i> Pendiente
                                                </span>
                                            @elseif($pedido->estado == 'listo')
                                                <span class="badge bg-info">
                                                    <i class="bi bi-check-circle"></i> Listo
                                                </span>
                                            @else
                                                <span class="badge bg-success">
                                                    <i class="bi bi-check-all"></i> {{ ucfirst($pedido->estado) }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-end fw-bold text-success">
                                            ${{ number_format($pedido->total, 0, ',', '.') }}
                                        </td>
                                        <td class="text-center">
                                            <button type="button" 
                                                    class="btn btn-sm btn-primary" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#modalPedido{{ $pedido->id }}">
                                                <i class="bi bi-eye"></i> Ver detalles
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Vista Mobile: Cards --}}
        <div class="d-md-none">
            @foreach($pedidos as $pedido)
                <div class="card mb-3 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="mb-0">Pedido #{{ $pedido->id }}</h5>
                            @if($pedido->estado == 'pendiente')
                                <span class="badge bg-warning text-dark">Pendiente</span>
                            @elseif($pedido->estado == 'listo')
                                <span class="badge bg-info">Listo</span>
                            @else
                                <span class="badge bg-success">{{ ucfirst($pedido->estado) }}</span>
                            @endif
                        </div>
                        <p class="mb-1 text-muted small">
                            <i class="bi bi-calendar3"></i> {{ $pedido->created_at->format('d/m/Y H:i') }}
                        </p>
                        <p class="mb-1 text-muted small">
                            <i class="bi bi-alarm"></i> Hora de recogida: <strong>{{ $pedido->hora_recogida }}</strong>
                        </p>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <h5 class="mb-0 text-success">${{ number_format($pedido->total, 0, ',', '.') }}</h5>
                            <button type="button" 
                                    class="btn btn-sm btn-primary" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#modalPedido{{ $pedido->id }}">
                                <i class="bi bi-eye"></i> Ver detalles
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Modales para cada pedido --}}
        @foreach($pedidos as $pedido)
            <div class="modal fade" id="modalPedido{{ $pedido->id }}" tabindex="-1" aria-labelledby="modalLabel{{ $pedido->id }}" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title" id="modalLabel{{ $pedido->id }}">
                                <i class="bi bi-receipt"></i> Detalles del Pedido #{{ $pedido->id }}
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            {{-- Información general --}}
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h6 class="card-subtitle mb-2 text-muted">Fecha del pedido</h6>
                                            <p class="card-text mb-0">
                                                <i class="bi bi-calendar3"></i> {{ $pedido->created_at->format('d/m/Y H:i') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h6 class="card-subtitle mb-2 text-muted">Hora de recogida</h6>
                                            <p class="card-text mb-0">
                                                <i class="bi bi-alarm"></i> <strong>{{ $pedido->hora_recogida }}</strong>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Estado --}}
                            <div class="alert 
                                @if($pedido->estado == 'pendiente') alert-warning
                                @elseif($pedido->estado == 'listo') alert-info
                                @else alert-success
                                @endif
                                mb-4">
                                <strong>Estado actual:</strong>
                                @if($pedido->estado == 'pendiente')
                                    <i class="bi bi-hourglass-split"></i> Tu pedido está siendo preparado
                                @elseif($pedido->estado == 'listo')
                                    <i class="bi bi-check-circle"></i> Tu pedido está listo para recoger
                                @else
                                    <i class="bi bi-check-all"></i> {{ ucfirst($pedido->estado) }}
                                @endif
                            </div>

                            {{-- Productos --}}
                            <h6 class="mb-3"><i class="bi bi-bag"></i> Productos</h6>
                            <div class="table-responsive">
                                <table class="table table-sm table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 80px;">Imagen</th>
                                            <th>Producto</th>
                                            <th class="text-center">Cant.</th>
                                            <th class="text-end">Precio</th>
                                            <th class="text-end">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($pedido->productos as $producto)
                                            <tr>
                                                <td class="text-center">
                                                    @if($producto->imagen)
                                                        <img src="{{ asset('storage/' . $producto->imagen) }}" 
                                                             alt="{{ $producto->nombre }}" 
                                                             class="img-thumbnail" 
                                                             style="width: 50px; height: 50px; object-fit: cover;">
                                                    @else
                                                        <div class="bg-light d-flex align-items-center justify-content-center" 
                                                             style="width: 50px; height: 50px; border: 1px solid #dee2e6; border-radius: 0.25rem;">
                                                            <i class="bi bi-image text-muted small"></i>
                                                        </div>
                                                    @endif
                                                </td>
                                                <td>
                                                    <strong>{{ $producto->nombre }}</strong>
                                                    @if($producto->descripcion)
                                                        <br><small class="text-muted">{{ Str::limit($producto->descripcion, 30) }}</small>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-secondary">{{ $producto->pivot->cantidad }}</span>
                                                </td>
                                                <td class="text-end">${{ number_format($producto->precio, 0, ',', '.') }}</td>
                                                <td class="text-end fw-bold">${{ number_format($producto->precio * $producto->pivot->cantidad, 0, ',', '.') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="table-light">
                                        <tr>
                                            <td colspan="4" class="text-end fw-bold">Total:</td>
                                            <td class="text-end fw-bold text-success fs-5">
                                                ${{ number_format($pedido->total, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="bi bi-x-circle"></i> Cerrar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

    @else
        <div class="alert alert-info text-center">
            <i class="bi bi-inbox" style="font-size: 3rem;"></i>
            <p class="mt-3 mb-2">Aún no has realizado ningún pedido.</p>
            <a href="{{ route('cliente.dashboard') }}" class="btn btn-primary">
                <i class="bi bi-shop"></i> Empezar a comprar
            </a>
        </div>
    @endif
</div>
@endsection
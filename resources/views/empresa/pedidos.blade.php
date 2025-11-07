@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0"><i class="bi bi-box-seam"></i> Pedidos Recibidos</h2>
        <a href="{{ url()->previous() }}" class="btn btn-outline-primary">
            <i class="bi bi-arrow-left"></i> Volver
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
                                    <th>Cliente</th>
                                    <th class="text-center">Fecha</th>
                                    <th class="text-center">Hora Recogida</th>
                                    <th class="text-center">Estado</th>
                                    <th class="text-end">Total</th>
                                    <th class="text-center" style="width: 250px;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pedidos as $pedido)
                                    <tr>
                                        <td class="text-center">
                                            <span class="badge bg-secondary">#{{ $pedido->id }}</span>
                                        </td>
                                        <td>
                                            <i class="bi bi-person"></i>
                                            <strong>{{ $pedido->user->name }}</strong>
                                        </td>
                                        <td class="text-center">
                                            <small class="text-muted">{{ $pedido->created_at->format('d/m/Y H:i') }}</small>
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
                                                    <i class="bi bi-check-all"></i> Entregado
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-end fw-bold text-success">
                                            ${{ number_format($pedido->total, 0, ',', '.') }}
                                        </td>
                                        <td class="text-center">
                                            <button type="button" 
                                                    class="btn btn-sm btn-primary me-1" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#modalPedido{{ $pedido->id }}">
                                                <i class="bi bi-eye"></i> Ver
                                            </button>
                                            
                                            @if($pedido->estado == 'pendiente')
                                                <form action="{{ route('pedidos.actualizarEstado', $pedido->id) }}" method="POST" class="d-inline">
                                                    @csrf @method('PUT')
                                                    <input type="hidden" name="estado" value="listo">
                                                    <button type="submit" class="btn btn-sm btn-info">
                                                        <i class="bi bi-check2"></i> Listo
                                                    </button>
                                                </form>
                                            @elseif($pedido->estado == 'listo')
                                                <form action="{{ route('pedidos.actualizarEstado', $pedido->id) }}" method="POST" class="d-inline">
                                                    @csrf @method('PUT')
                                                    <input type="hidden" name="estado" value="entregado">
                                                    <button type="submit" class="btn btn-sm btn-success">
                                                        <i class="bi bi-check-all"></i> Entregado
                                                    </button>
                                                </form>
                                            @else
                                                <span class="badge bg-success">
                                                    <i class="bi bi-check-circle-fill"></i> Completado
                                                </span>
                                            @endif
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
                            <div>
                                <h5 class="mb-0">Pedido #{{ $pedido->id }}</h5>
                                <p class="mb-0 text-muted small">
                                    <i class="bi bi-person"></i> {{ $pedido->user->name }}
                                </p>
                            </div>
                            @if($pedido->estado == 'pendiente')
                                <span class="badge bg-warning text-dark">Pendiente</span>
                            @elseif($pedido->estado == 'listo')
                                <span class="badge bg-info">Listo</span>
                            @else
                                <span class="badge bg-success">Entregado</span>
                            @endif
                        </div>
                        
                        <hr>
                        
                        <div class="row mb-2">
                            <div class="col-6">
                                <small class="text-muted">Fecha:</small>
                                <p class="mb-0 small">{{ $pedido->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                            <div class="col-6">
                                <small class="text-muted">Hora recogida:</small>
                                <p class="mb-0 small"><strong>{{ $pedido->hora_recogida }}</strong></p>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <h5 class="mb-0 text-success">${{ number_format($pedido->total, 0, ',', '.') }}</h5>
                            <div>
                                <button type="button" 
                                        class="btn btn-sm btn-primary me-1" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#modalPedido{{ $pedido->id }}">
                                    <i class="bi bi-eye"></i>
                                </button>
                                
                                @if($pedido->estado == 'pendiente')
                                    <form action="{{ route('pedidos.actualizarEstado', $pedido->id) }}" method="POST" class="d-inline">
                                        @csrf @method('PUT')
                                        <input type="hidden" name="estado" value="listo">
                                        <button type="submit" class="btn btn-sm btn-info">
                                            <i class="bi bi-check2"></i> Listo
                                        </button>
                                    </form>
                                @elseif($pedido->estado == 'listo')
                                    <form action="{{ route('pedidos.actualizarEstado', $pedido->id) }}" method="POST" class="d-inline">
                                        @csrf @method('PUT')
                                        <input type="hidden" name="estado" value="entregado">
                                        <button type="submit" class="btn btn-sm btn-success">
                                            <i class="bi bi-check-all"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
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
                                <i class="bi bi-receipt"></i> Detalle del Pedido #{{ $pedido->id }}
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            {{-- Información del cliente --}}
                            <div class="card bg-light mb-4">
                                <div class="card-body">
                                    <h6 class="card-subtitle mb-2 text-muted">
                                        <i class="bi bi-person-circle"></i> Información del Cliente
                                    </h6>
                                    <p class="card-text mb-1">
                                        <strong>Nombre:</strong> {{ $pedido->user->name }}
                                    </p>
                                    <p class="card-text mb-0">
                                        <strong>Email:</strong> {{ $pedido->user->email }}
                                    </p>
                                </div>
                            </div>

                            {{-- Información del pedido --}}
                            <div class="row mb-4">
                                <div class="col-md-4">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h6 class="card-subtitle mb-2 text-muted">Fecha de pedido</h6>
                                            <p class="card-text mb-0">
                                                <i class="bi bi-calendar3"></i> {{ $pedido->created_at->format('d/m/Y H:i') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h6 class="card-subtitle mb-2 text-muted">Hora de recogida</h6>
                                            <p class="card-text mb-0">
                                                <i class="bi bi-alarm"></i> <strong>{{ $pedido->hora_recogida }}</strong>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card 
                                        @if($pedido->estado == 'pendiente') bg-warning
                                        @elseif($pedido->estado == 'listo') bg-info
                                        @else bg-success
                                        @endif text-white">
                                        <div class="card-body">
                                            <h6 class="card-subtitle mb-2">Estado</h6>
                                            <p class="card-text mb-0 fw-bold">
                                                @if($pedido->estado == 'pendiente')
                                                    <i class="bi bi-hourglass-split"></i> Pendiente
                                                @elseif($pedido->estado == 'listo')
                                                    <i class="bi bi-check-circle"></i> Listo
                                                @else
                                                    <i class="bi bi-check-all"></i> Entregado
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Productos del pedido --}}
                            <h6 class="mb-3"><i class="bi bi-bag"></i> Productos del Pedido</h6>
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

                            {{-- Acciones rápidas en el modal --}}
                            @if($pedido->estado != 'entregado')
                                <div class="alert alert-info mt-4">
                                    <strong><i class="bi bi-info-circle"></i> Actualizar estado:</strong>
                                    <div class="mt-2">
                                        @if($pedido->estado == 'pendiente')
                                            <form action="{{ route('pedidos.actualizarEstado', $pedido->id) }}" method="POST" class="d-inline">
                                                @csrf @method('PUT')
                                                <input type="hidden" name="estado" value="listo">
                                                <button type="submit" class="btn btn-info" onclick="return confirm('¿Marcar este pedido como listo?')">
                                                    <i class="bi bi-check2"></i> Marcar como Listo
                                                </button>
                                            </form>
                                        @elseif($pedido->estado == 'listo')
                                            <form action="{{ route('pedidos.actualizarEstado', $pedido->id) }}" method="POST" class="d-inline">
                                                @csrf @method('PUT')
                                                <input type="hidden" name="estado" value="entregado">
                                                <button type="submit" class="btn btn-success" onclick="return confirm('¿Confirmar que el pedido fue entregado?')">
                                                    <i class="bi bi-check-all"></i> Marcar como Entregado
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            @endif
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
            <p class="mt-3 mb-0">Aún no hay pedidos recibidos.</p>
        </div>
    @endif
</div>
@endsection
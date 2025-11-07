@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Panel de Administración</h2>

    <div class="card mb-4">
        <div class="card-header bg-dark text-white">Solicitudes de empresa</div>
        <div class="card-body">
            @forelse($solicitudes as $s)
                <div class="d-flex justify-content-between border p-2 mb-2">
                    <div>
                        <strong>{{ $s->nombre }}</strong><br>
                        <small>{{ $s->correo }}</small>
                    </div>
                    <div>
                        <form method="POST" action="{{ route('admin.aprobar', $s->id) }}" class="d-inline">
                            @csrf
                            <button class="btn btn-success btn-sm">Aprobar</button>
                        </form>
                        <form method="POST" action="{{ route('admin.rechazar', $s->id) }}" class="d-inline">
                            @csrf
                            <button class="btn btn-danger btn-sm">Rechazar</button>
                        </form>
                    </div>
                </div>
            @empty
                <p>No hay solicitudes pendientes.</p>
            @endforelse
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header bg-dark text-white">Usuarios registrados</div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead><tr><th>Nombre</th><th>Email</th><th>Rol</th></tr></thead>
                <tbody>
                    @foreach($usuarios as $u)
                        <tr>
                            <td>{{ $u->name }}</td>
                            <td>{{ $u->email }}</td>
                            <td>{{ ucfirst($u->rol) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header bg-dark text-white">Empresas creadas</div>
        <div class="card-body">
            <ul>
                @foreach($empresas as $e)
                    <li>{{ $e->nombre }} — {{ $e->direccion }}</li>
                @endforeach
            </ul>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header bg-dark text-white">Historial de pedidos</div>
        <div class="card-body">
            <table class="table">
                <thead><tr><th>ID</th><th>Cliente</th><th>Empresa</th><th>Estado</th></tr></thead>
                <tbody>
                    @foreach($pedidos as $p)
                        <tr>
                            <td>{{ $p->id }}</td>
                            <td>{{ $p->cliente->user->name ?? '—' }}</td>
                            <td>{{ $p->empresa->nombre ?? '—' }}</td>
                            <td>{{ $p->estado }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

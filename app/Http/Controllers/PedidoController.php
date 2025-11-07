<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Pedido;
use App\Models\PedidoProducto;
use App\Models\Carrito;
use App\Models\Producto;
use App\Models\Empresa;

class PedidoController extends Controller
{
    /**
     * Mostrar el checkout del cliente con su carrito actual.
     */
    public function checkout()
    {
        $user = Auth::user();
        $carrito = Carrito::where('user_id', $user->id)->with('productos')->first();

        if (!$carrito || $carrito->productos->isEmpty()) {
            return redirect()->route('cliente.dashboard')->with('error', 'Tu carrito está vacío.');
        }

        return view('cliente.checkout', [
            'carrito' => $carrito,
            'productos' => $carrito->productos,
        ]);
    }

    /**
     * Crear un pedido a partir del carrito.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $carrito = Carrito::where('user_id', $user->id)->with('productos')->first();

        if (!$carrito || $carrito->productos->isEmpty()) {
            return redirect()->route('cliente.dashboard')->with('error', 'Tu carrito está vacío.');
        }

        $request->validate([
            'hora_recogida' => 'required|date_format:H:i'
        ]);

        // ✅ Verificar empresa asociada al primer producto
        $primerProducto = $carrito->productos->first();

        if (!$primerProducto || !$primerProducto->empresa_id) {
            return redirect()->route('cliente.dashboard')->with('error', 'No se pudo determinar la empresa del pedido.');
        }

        // ✅ Calcular total exacto del carrito
        $total = 0;
        foreach ($carrito->productos as $producto) {
            $cantidad = $producto->pivot->cantidad ?? 1;
            $precio = $producto->precio ?? 0;
            $total += $precio * $cantidad;
        }

        // ✅ Crear el pedido con total calculado
        $pedido = Pedido::create([
            'empresa_id' => $primerProducto->empresa_id,
            'user_id' => $user->id,
            'estado' => 'pendiente',
            'hora_recogida' => $request->hora_recogida,
            'total' => $total,
        ]);

        // ✅ Registrar los productos del carrito en pedido_productos
        foreach ($carrito->productos as $producto) {
            PedidoProducto::create([
                'pedido_id' => $pedido->id,
                'producto_id' => $producto->id,
                'cantidad' => $producto->pivot->cantidad ?? 1,
                'precio' => $producto->precio ?? 0,
            ]);
        }

        // ✅ Vaciar el carrito del usuario
        $carrito->productos()->detach();

        return redirect()->route('cliente.pedidos')->with('success', 'Pedido realizado con éxito.');
    }

    /**
     * Listar los pedidos del cliente.
     */
    public function indexCliente()
    {
        $user = Auth::user();
        $pedidos = Pedido::where('user_id', $user->id)
            ->with(['empresa', 'productos'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('cliente.pedidos', compact('pedidos'));
    }

    /**
     * Listar los pedidos de la empresa.
     */
    public function indexEmpresa()
    {
        $empresa = Empresa::where('user_id', Auth::id())->first();

        if (!$empresa) {
            return redirect()->route('dashboard')->with('error', 'No tienes una empresa asociada.');
        }

        $pedidos = Pedido::where('empresa_id', $empresa->id)
            ->with(['user', 'productos'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('empresa.pedidos', compact('pedidos'));
    }

    /**
     * Actualizar el estado de un pedido.
     */
    public function actualizarEstado($id, Request $request)
    {
        $pedido = Pedido::findOrFail($id);
        $nuevoEstado = $request->input('estado');

        if (!in_array($nuevoEstado, ['pendiente', 'listo', 'entregado'])) {
            return back()->with('error', 'Estado inválido.');
        }

        $pedido->update(['estado' => $nuevoEstado]);

        return back()->with('success', 'Estado del pedido actualizado correctamente.');
    }
}

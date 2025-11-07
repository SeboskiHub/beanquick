<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Carrito;
use App\Models\Producto;
use Illuminate\Support\Facades\Auth;

class CarritoController extends Controller
{
    /**
     * Mostrar el carrito del usuario autenticado.
     * Si no existe, se crea automáticamente.
     */
    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión para ver tu carrito.');
        }

        $carrito = Carrito::firstOrCreate(['user_id' => $user->id]);
        $productos = $carrito->productos()->withPivot('cantidad')->get();

        return view('carrito.index', compact('carrito', 'productos'));
    }

    /**
     * Agregar un producto al carrito.
     * Si el carrito no existe, se crea.
     * Si el producto ya está, incrementa la cantidad.
     */
    public function agregar(Request $request, $productoId)
    {
        $request->validate([
            'cantidad' => 'required|integer|min:1'
        ]);
    
        $user = Auth::user();
    
        // Crea el carrito si no existe
        $carrito = Carrito::firstOrCreate(['user_id' => $user->id]);
    
        // Busca el producto
        $producto = Producto::findOrFail($productoId);
    
        // Verifica si ya existe en el carrito
        $carritoProducto = $carrito->productos()->where('producto_id', $productoId)->first();
    
        if ($carritoProducto) {
            $nuevaCantidad = $carritoProducto->pivot->cantidad + $request->cantidad;
            $carrito->productos()->updateExistingPivot($productoId, ['cantidad' => $nuevaCantidad]);
        } else {
            $carrito->productos()->attach($productoId, ['cantidad' => $request->cantidad]);
        }
    
        return redirect()->back()->with('success', 'Producto agregado al carrito correctamente.');
    }


    /**
     * Actualizar cantidad de un producto en el carrito.
     */
    public function actualizar(Request $request, $productoId)
    {
        $request->validate(['cantidad' => 'required|integer|min:1']);

        $carrito = Carrito::where('user_id', Auth::id())->first();

        if ($carrito) {
            $carrito->productos()->updateExistingPivot($productoId, [
                'cantidad' => $request->cantidad,
            ]);
        }

        return redirect()->back()->with('success', 'Cantidad actualizada correctamente.');
    }

    /**
     * Eliminar un producto específico del carrito.
     */
    public function eliminar($productoId)
    {
        $carrito = Carrito::where('user_id', Auth::id())->first();

        if ($carrito) {
            $carrito->productos()->detach($productoId);
        }

        return redirect()->back()->with('success', 'Producto eliminado del carrito.');
    }

    /**
     * Vaciar completamente el carrito del usuario.
     */
    public function vaciar()
    {
        $carrito = Carrito::where('user_id', Auth::id())->first();

        if ($carrito) {
            $carrito->productos()->detach();
        }

        return redirect()->back()->with('success', 'Carrito vaciado correctamente.');
    }
}

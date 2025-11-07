<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Empresa;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductoController extends Controller
{
    // Mostrar todos los productos de la empresa actual
    public function index()
    {
        $empresa = Empresa::where('user_id', Auth::id())->first();

        if (!$empresa) {
            return redirect()->route('empresa.create')->with('error', 'Debes registrar tu empresa antes de agregar productos.');
        }

        $productos = Producto::where('empresa_id', $empresa->id)->get();

        return view('productos.index', compact('productos', 'empresa'));
    }

    // Mostrar formulario de creación de producto
    public function create()
    {
        $empresa = Empresa::where('user_id', Auth::id())->first();

        if (!$empresa) {
            return redirect()->route('empresa.create')->with('error', 'Debes registrar tu empresa antes de agregar productos.');
        }

        return view('productos.create', compact('empresa'));
    }

    // Guardar producto nuevo (ahora con imagen)
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048'
        ]);

        $empresa = Empresa::where('user_id', Auth::id())->firstOrFail();

        $producto = new Producto([
            'empresa_id' => $empresa->id,
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'precio' => $request->precio,
        ]);

        if ($request->hasFile('imagen')) {
            $rutaImagen = $request->file('imagen')->store('productos', 'public');
            $producto->imagen = $rutaImagen;
        }

        $producto->save();

        return redirect()->route('productos.index')->with('success', 'Producto creado correctamente.');
    }

    // Mostrar formulario de edición
    public function edit($id)
    {
        $producto = Producto::findOrFail($id);
        $empresa = Empresa::where('user_id', Auth::id())->first();

        // Asegurar que el producto pertenece a la empresa del usuario
        if ($producto->empresa_id !== $empresa->id) {
            return redirect()->route('productos.index')->with('error', 'No tienes permiso para editar este producto.');
        }

        return view('productos.edit', compact('producto'));
    }

    // Actualizar producto (con soporte de imagen)
    public function update(Request $request, $id)
    {
        $producto = Producto::findOrFail($id);
        $empresa = Empresa::where('user_id', Auth::id())->first();

        if ($producto->empresa_id !== $empresa->id) {
            return redirect()->route('productos.index')->with('error', 'No tienes permiso para actualizar este producto.');
        }

        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048'
        ]);

        $producto->update($request->only(['nombre', 'descripcion', 'precio']));

        if ($request->hasFile('imagen')) {
            // Borrar la imagen anterior si existe
            if ($producto->imagen && Storage::disk('public')->exists($producto->imagen)) {
                Storage::disk('public')->delete($producto->imagen);
            }

            $rutaImagen = $request->file('imagen')->store('productos', 'public');
            $producto->update(['imagen' => $rutaImagen]);
        }

        return redirect()->route('productos.index')->with('success', 'Producto actualizado correctamente.');
    }

    // Eliminar producto
    public function destroy($id)
    {
        $producto = Producto::findOrFail($id);
        $empresa = Empresa::where('user_id', Auth::id())->first();

        if ($producto->empresa_id !== $empresa->id) {
            return redirect()->route('productos.index')->with('error', 'No tienes permiso para eliminar este producto.');
        }

        // Eliminar imagen asociada si existe
        if ($producto->imagen && Storage::disk('public')->exists($producto->imagen)) {
            Storage::disk('public')->delete($producto->imagen);
        }

        $producto->delete();

        return redirect()->route('productos.index')->with('success', 'Producto eliminado correctamente.');
    }
}

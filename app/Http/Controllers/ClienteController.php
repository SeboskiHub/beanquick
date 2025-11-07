<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empresa;
use App\Models\Producto;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ClienteController extends Controller
{
    // Dashboard del cliente con productos destacados
    public function dashboard()
    {
        $user = Auth::user();
        
        // Obtener productos más vendidos (destacados)
        $productosDestacados = Producto::select('productos.*', DB::raw('COALESCE(SUM(pedido_productos.cantidad), 0) as total_vendido'))
            ->leftJoin('pedido_productos', 'productos.id', '=', 'pedido_productos.producto_id')
            ->with('empresa') // Cargar relación con empresa
            ->groupBy('productos.id', 'productos.empresa_id', 'productos.nombre', 'productos.descripcion', 'productos.precio', 'productos.imagen', 'productos.created_at', 'productos.updated_at')
            ->orderBy('total_vendido', 'DESC')
            ->limit(8) // Mostrar top 8 productos
            ->get();

        return view('cliente.dashboard', compact('user', 'productosDestacados'));
    }

    // Mostrar todas las empresas disponibles
    public function empresas()
    {
        $empresas = Empresa::all();
        return view('cliente.empresas', compact('empresas'));
    }

    // Mostrar productos de una empresa seleccionada
    public function productosPorEmpresa($id)
    {
        $empresa = Empresa::findOrFail($id);
        $productos = Producto::where('empresa_id', $empresa->id)->get();

        return view('cliente.productos', compact('empresa', 'productos'));
    }

    public function verProductos($id)
    {
        $empresa = Empresa::findOrFail($id);
        $productos = $empresa->productos; // relación 1:N en el modelo
    
        return view('cliente.verProductos', compact('empresa', 'productos'));
    }
}
<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\CarritoController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\RegistroEmpresaController;
use App\Http\Controllers\EmpresaActivationController;

// ===========================================================
// 🔐 CARGAR RUTAS DE AUTENTICACIÓN PRIMERO
// ===========================================================
require __DIR__.'/auth.php';

// ===========================================================
// 🏠 RUTA PRINCIPAL
// ===========================================================
Route::get('/', function () {
    // Si el usuario ya está autenticado, redirigir según su rol
    if (Auth::check()) {
        $user = Auth::user();
        
        if ($user->rol === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        
        if ($user->rol === 'empresa') {
            $empresa = \App\Models\Empresa::where('user_id', $user->id)->first();
            if ($empresa) {
                return redirect()->route('empresa.dashboard', ['id' => $empresa->id]);
            }
            return redirect()->route('empresa.create');
        }
        
        if ($user->rol === 'cliente') {
            return redirect()->route('cliente.dashboard');
        }
    }
    
    // Si no está autenticado, redirigir al registro
    return redirect()->route('register');
})->name('home');

// ===========================================================
// 📦 RUTAS REGISTRO DE EMPRESA (PÚBLICAS)
// ===========================================================
Route::get('/registro-empresa', [RegistroEmpresaController::class, 'create'])
    ->name('registro.empresa');
Route::post('/registro-empresa', [RegistroEmpresaController::class, 'store'])
    ->name('registro.empresa.store');

// ===========================================================
// ✅ RUTAS ACTIVACIÓN EMPRESA (DEBEN SER PÚBLICAS)
// ===========================================================
Route::get('/empresa/activar/{token}', [EmpresaActivationController::class, 'form'])
    ->name('empresa.activar');
Route::post('/empresa/activar/{token}', [EmpresaActivationController::class, 'store'])
    ->name('empresa.activar.store');

// ===========================================================
// 📦 RUTAS EMPRESA
// ===========================================================
Route::middleware('auth')->group(function () {

    Route::get('/empresa/{id}/dashboard', [EmpresaController::class, 'dashboard'])->name('empresa.dashboard');
    Route::get('/empresa/create', [EmpresaController::class, 'create'])->name('empresa.create');
    Route::post('/empresa', [EmpresaController::class, 'store'])->name('empresa.store');
    Route::get('/empresa/{id}/edit', [EmpresaController::class, 'edit'])->name('empresa.edit');
    Route::put('/empresa/{id}', [EmpresaController::class, 'update'])->name('empresa.update');

    // === Pedidos de la empresa ===
    Route::get('/empresa/pedidos', [PedidoController::class, 'indexEmpresa'])
        ->name('empresa.pedidos');

    Route::put('/pedidos/{id}/estado', [PedidoController::class, 'actualizarEstado'])
        ->name('pedidos.actualizarEstado');
});

// ===========================================================
// 🛍️ RUTAS PRODUCTOS
// ===========================================================
Route::middleware('auth')->group(function () {
    Route::get('/productos', [ProductoController::class, 'index'])->name('productos.index');
    Route::get('/productos/create', [ProductoController::class, 'create'])->name('productos.create');
    Route::post('/productos/store', [ProductoController::class, 'store'])->name('productos.store');
    Route::get('/productos/{id}/edit', [ProductoController::class, 'edit'])->name('productos.edit');
    Route::put('/productos/{id}/update', [ProductoController::class, 'update'])->name('productos.update');
    Route::delete('/productos/{id}/delete', [ProductoController::class, 'destroy'])->name('productos.destroy');
});

// ===========================================================
// 👤 RUTAS CLIENTE
// ===========================================================
Route::middleware('auth')->group(function () {

    Route::get('/cliente/dashboard', [ClienteController::class, 'dashboard'])->name('cliente.dashboard');
    Route::get('/cliente/empresas', [ClienteController::class, 'empresas'])->name('cliente.empresas');
    Route::get('/cliente/empresa/{id}/productos', [ClienteController::class, 'productosPorEmpresa'])
        ->name('cliente.empresa.productos');

    Route::get('/cliente/checkout', [PedidoController::class, 'checkout'])
        ->name('cliente.checkout');

    Route::post('/cliente/pedidos', [PedidoController::class, 'store'])
        ->name('pedidos.store');

    Route::get('/cliente/pedidos', [PedidoController::class, 'indexCliente'])
        ->name('cliente.pedidos');
});

// ===========================================================
// 🛒 RUTAS CARRITO
// ===========================================================
Route::middleware('auth')->group(function () {
    Route::get('/carrito', [CarritoController::class, 'index'])->name('carrito.index');
    Route::post('/carrito/agregar/{productoId}', [CarritoController::class, 'agregar'])->name('carrito.agregar');
    Route::post('/carrito/actualizar/{productoId}', [CarritoController::class, 'actualizar'])->name('carrito.actualizar');
    Route::delete('/carrito/eliminar/{productoId}', [CarritoController::class, 'eliminar'])->name('carrito.eliminar');
    Route::delete('/carrito/vaciar', [CarritoController::class, 'vaciar'])->name('carrito.vaciar');
    Route::get('/empresas/{id}/productos', [ClienteController::class, 'verProductos'])
        ->name('cliente.verProductos');
});

// ===========================================================
// 🧑‍💼 RUTAS ADMIN
// ===========================================================
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::post('/admin/aprobar/{id}', [AdminController::class, 'aprobar'])->name('admin.aprobar');
    Route::post('/admin/rechazar/{id}', [AdminController::class, 'rechazar'])->name('admin.rechazar');
});

// ===========================================================
// ⚙️ PERFIL DE USUARIO
// ===========================================================
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
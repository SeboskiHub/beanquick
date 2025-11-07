<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Empresa;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Si es una empresa
        if ($user->rol === 'empresa') {
            $empresa = Empresa::where('user_id', $user->id)->first();

            if (!$empresa) {
                // Si la empresa no existe, lo mandamos a crearla
                return redirect()->route('empresa.create');
            }

            // Si existe, al dashboard de esa empresa
            return redirect()->route('empresa.dashboard');
        }

        // Si es un cliente
        if ($user->rol === 'cliente') {
            return redirect()->route('cliente.dashboard');
        }

        if ($user->rol === 'admin') {
            return redirect()->route('admin.dashboard');
        }


        // En caso de que no tenga rol definido
        return redirect('/login')->withErrors(['rol' => 'No se encontró un rol válido para este usuario.']);
    }
}

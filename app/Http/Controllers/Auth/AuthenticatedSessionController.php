<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);
    
        if (Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            $request->session()->regenerate();
    
            $user = Auth::user();
    
            if ($user->rol === 'empresa') {
                // Buscar la empresa asociada a este usuario
                $empresa = \App\Models\Empresa::where('user_id', $user->id)->first();
    
                // Si no existe aún, lo enviamos a una vista para crearla
                if (!$empresa) {
                    return redirect()->route('empresa.create');
                }
    
                // Redirigir al dashboard específico de esa empresa
                return redirect()->route('empresa.dashboard', ['id' => $empresa->id]);
            }
    
            if ($user->rol === 'cliente') {
                return redirect()->route('cliente.dashboard');
            }

            if ($user->rol === 'admin') {
                return redirect()->route('admin.dashboard');
           }

    
            return redirect('/dashboard');
        }
    
        return back()->withErrors([
            'email' => 'Las credenciales no coinciden.',
        ]);
    }


    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}

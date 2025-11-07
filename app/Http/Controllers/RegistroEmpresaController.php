<?php
namespace App\Http\Controllers;

use App\Models\SolicitudEmpresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RegistroEmpresaController extends Controller
{
    public function create()
    {
        return view('auth.register-empresa');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'correo' => 'required|email|unique:solicitudes_empresas,correo',
            'nit' => 'nullable|string|max:50',
            'telefono' => 'nullable|string|max:50',
            'direccion' => 'nullable|string|max:255',
            'descripcion' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'foto_local' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
        ]);

        $data = $request->except(['logo', 'foto_local']);
        
        // Guardar logo en carpeta temporal de solicitudes
        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('solicitudes/logos', 'public');
        }
        
        // Guardar foto del local en carpeta temporal de solicitudes
        if ($request->hasFile('foto_local')) {
            $data['foto_local'] = $request->file('foto_local')->store('solicitudes/locales', 'public');
        }

        SolicitudEmpresa::create($data);

        return redirect()->route('login')->with('success', 'Tu solicitud fue enviada. Te contactaremos pronto.');
    }
}
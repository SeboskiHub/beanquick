<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Empresa;

class EmpresaController extends Controller
{
    // Mostrar dashboard de la empresa
    public function dashboard($id)
    {
        $empresa = Empresa::findOrFail($id);

        // Asegurar que la empresa pertenece al usuario actual
        if ($empresa->user_id !== Auth::id()) {
            abort(403, 'Acceso no autorizado');
        }

        return view('empresa.dashboard', compact('empresa'));
    }

    // Formulario para crear una nueva empresa
    public function create()
    {
        return view('empresa.create');
    }

    // Guardar nueva empresa
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'direccion' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:50',
            'descripcion' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'foto_local' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
        ]);

        $data = [
            'user_id' => Auth::id(),
            'nombre' => $request->nombre,
            'direccion' => $request->direccion,
            'telefono' => $request->telefono,
            'descripcion' => $request->descripcion,
        ];

        // Guardar logo
        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('empresas/logos', 'public');
        }

        // Guardar foto del local
        if ($request->hasFile('foto_local')) {
            $data['foto_local'] = $request->file('foto_local')->store('empresas/locales', 'public');
        }

        $empresa = Empresa::create($data);

        return redirect()->route('empresa.dashboard', ['id' => $empresa->id])
                         ->with('success', 'Empresa creada correctamente.');
    }

    // Formulario para editar datos de la empresa
    public function edit($id)
    {
        $empresa = Empresa::findOrFail($id);

        if ($empresa->user_id !== Auth::id()) {
            abort(403, 'Acceso no autorizado');
        }

        return view('empresa.edit', compact('empresa'));
    }

    // Actualizar datos de la empresa
    public function update(Request $request, $id)
    {
        $empresa = Empresa::findOrFail($id);

        if ($empresa->user_id !== Auth::id()) {
            abort(403, 'Acceso no autorizado');
        }

        $request->validate([
            'nombre' => 'required|string|max:255',
            'direccion' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:50',
            'descripcion' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'foto_local' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
        ]);

        $data = $request->only(['nombre', 'direccion', 'telefono', 'descripcion']);

        // Actualizar logo
        if ($request->hasFile('logo')) {
            // Eliminar logo anterior si existe
            if ($empresa->logo && Storage::disk('public')->exists($empresa->logo)) {
                Storage::disk('public')->delete($empresa->logo);
            }
            $data['logo'] = $request->file('logo')->store('empresas/logos', 'public');
        }

        // Actualizar foto del local
        if ($request->hasFile('foto_local')) {
            // Eliminar foto anterior si existe
            if ($empresa->foto_local && Storage::disk('public')->exists($empresa->foto_local)) {
                Storage::disk('public')->delete($empresa->foto_local);
            }
            $data['foto_local'] = $request->file('foto_local')->store('empresas/locales', 'public');
        }

        $empresa->update($data);

        return redirect()->route('empresa.dashboard', ['id' => $empresa->id])
                         ->with('success', 'Empresa actualizada correctamente.');
    }
}
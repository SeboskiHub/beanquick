<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Empresa;
use App\Models\SolicitudEmpresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class EmpresaActivationController extends Controller
{
    public function form($token)
    {
        // Cerrar cualquier sesión activa antes de continuar
        Auth::logout();

        $solicitud = SolicitudEmpresa::where('token', $token)
            ->where('estado', 'aprobado')
            ->firstOrFail();

        return view('auth.empresa_activar', compact('solicitud'));
    }

    public function store(Request $request, $token)
    {
        Auth::logout();

        $request->validate([
            'password' => 'required|confirmed|min:8',
        ]);

        $solicitud = SolicitudEmpresa::where('token', $token)
            ->where('estado', 'aprobado')
            ->first();

        if (!$solicitud) {
            return redirect()->route('login')->with('error', 'El enlace de activación no es válido o ya fue usado.');
        }

        try {
            // Crear usuario empresa
            $user = User::create([
                'name' => $solicitud->nombre,
                'email' => $solicitud->correo,
                'password' => Hash::make($request->password),
                'rol' => 'empresa',
            ]);

            // Preparar datos de la empresa
            $empresaData = [
                'user_id' => $user->id,
                'nombre' => $solicitud->nombre,
                'direccion' => $solicitud->direccion,
                'telefono' => $solicitud->telefono,
                'descripcion' => $solicitud->descripcion,
            ];

            // Copiar logo de solicitudes/ a empresas/logos/
            if ($solicitud->logo && Storage::disk('public')->exists($solicitud->logo)) {
                // Obtener el nombre del archivo
                $logoFileName = basename($solicitud->logo);
                // Nueva ruta en empresas/logos/
                $newLogoPath = 'empresas/logos/' . $logoFileName;
                
                // Copiar el archivo
                Storage::disk('public')->copy($solicitud->logo, $newLogoPath);
                $empresaData['logo'] = $newLogoPath;
            }

            // Copiar foto del local de solicitudes/ a empresas/locales/
            if ($solicitud->foto_local && Storage::disk('public')->exists($solicitud->foto_local)) {
                // Obtener el nombre del archivo
                $fotoFileName = basename($solicitud->foto_local);
                // Nueva ruta en empresas/locales/
                $newFotoPath = 'empresas/locales/' . $fotoFileName;
                
                // Copiar el archivo
                Storage::disk('public')->copy($solicitud->foto_local, $newFotoPath);
                $empresaData['foto_local'] = $newFotoPath;
            }

            // Crear empresa con las rutas actualizadas
            Empresa::create($empresaData);

            // Marcar solicitud como completada
            $solicitud->update([
                'estado' => 'completada',
                'token' => null,
            ]);

            // Opcional: Eliminar archivos temporales de solicitudes
            if ($solicitud->logo && Storage::disk('public')->exists($solicitud->logo)) {
                Storage::disk('public')->delete($solicitud->logo);
            }
            if ($solicitud->foto_local && Storage::disk('public')->exists($solicitud->foto_local)) {
                Storage::disk('public')->delete($solicitud->foto_local);
            }

            return redirect()->route('login')->with('success', 'Tu cuenta fue creada exitosamente. Ya puedes iniciar sesión.');
        } catch (\Exception $e) {
            return back()->with('error', 'Ocurrió un error al activar la cuenta: ' . $e->getMessage());
        }
    }
}
<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Empresa;
use App\Models\Pedido;
use App\Models\SolicitudEmpresa;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

use App\Mail\ActivacionEmpresaMail; // ✅ ESTE es el importante


class AdminController extends Controller
{
    public function dashboard()
    {
        $usuarios = User::all();
        $empresas = Empresa::all();
        $pedidos = Pedido::with('cliente', 'empresa')->get();
        $solicitudes = SolicitudEmpresa::where('estado', 'pendiente')->get();

        return view('admin.dashboard', compact('usuarios', 'empresas', 'pedidos', 'solicitudes'));
    }


    public function aprobar($id)
    {
        $solicitud = SolicitudEmpresa::findOrFail($id);
    
        // marcar aprobada y crear token
        $solicitud->estado = 'aprobado';
        $solicitud->token = Str::random(60);
        $solicitud->save();
    
        // crear link de activación
        $link = url('/empresa/activar/' . $solicitud->token);
    
        // enviar el correo usando el Mailable
        Mail::to($solicitud->correo)->send(new ActivacionEmpresaMail($solicitud, $link));
    
        return redirect()->back()->with('success', 'Solicitud aprobada. Correo de activación enviado.');
    }


    public function rechazar($id)
    {
        $solicitud = SolicitudEmpresa::findOrFail($id);
        $solicitud->estado = 'rechazado';
        $solicitud->save();

        return back()->with('error', 'Solicitud rechazada.');
    }
}

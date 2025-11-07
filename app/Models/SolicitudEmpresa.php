<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudEmpresa extends Model
{
    use HasFactory;


    protected $table = 'solicitudes_empresas'; // 👈 importante
    protected $fillable = [
        'nombre',
        'correo',
        'nit',
        'telefono',
        'direccion',
        'descripcion',
        'logo',
        'foto_local',
        'estado',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'empresa_id',
        'estado',
        'hora_recogida',
        'total'
    ];

    // 🔗 Relación: un pedido pertenece a un cliente
    public function cliente()
    {
        return $this->belongsTo(User::class, 'cliente_id');
    }

    // 🔗 Relación: un pedido pertenece a una empresa
    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }

    // 🔗 Relación: un pedido tiene muchos productos
    public function productos()
    {
        return $this->belongsToMany(Producto::class, 'pedido_productos')
                    ->withPivot('cantidad', 'precio_unitario')
                    ->withTimestamps();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}

<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'rol',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Relación 1:1 → Un usuario tiene un carrito
     */
    public function carrito()
    {
        return $this->hasOne(Carrito::class, 'user_id');
    }

    /**
     * Si el usuario es empresa, puede tener productos o sedes (si aplica en tu flujo futuro)
     */
    public function productos()
    {
        return $this->hasMany(Producto::class);
    }

    public function pedidos()
    {
        return $this->hasMany(Pedido::class, 'cliente_id');
    }

 
}

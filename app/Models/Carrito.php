<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carrito extends Model
{
    use HasFactory;

    protected $fillable = ['user_id'];

    /**
     * Relación: el carrito pertenece a un usuario
     */
    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relación: el carrito tiene muchos productos (a través de la tabla pivote)
     */
    public function productos()
    {
        return $this->belongsToMany(Producto::class, 'carrito_productos')
                    ->withPivot('cantidad')
                    ->withTimestamps();
    }

    public function items()
    {
        return $this->hasMany(CarritoProducto::class, 'carrito_id');
    }

}

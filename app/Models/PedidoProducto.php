<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PedidoProducto extends Model
{
    use HasFactory;

    protected $table = 'pedido_productos';

    protected $fillable = [
        'pedido_id',
        'producto_id',
        'cantidad',
        'precio_unitario'
    ];

    // 🔗 Relación con el pedido
    public function pedido()
    {
        return $this->belongsTo(Pedido::class);
    }

    // 🔗 Relación con el producto
    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}

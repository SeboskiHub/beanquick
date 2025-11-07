<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $fillable = [
        'empresa_id',
        'nombre',
        'descripcion',
        'precio',
        'imagen',
    ];

    /**
     * Relación: un producto puede estar en muchos carritos
     */
    public function carritos()
    {
        return $this->belongsToMany(Carrito::class, 'carrito_productos')
                    ->withPivot('cantidad')
                    ->withTimestamps();
    }

    /**
     * Relación opcional si los productos pertenecen a una empresa
     */
    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }
}

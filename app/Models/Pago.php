<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    use HasFactory;
    protected $fillable = [
        'pedido_id',
        'monto',
        'metodo',
        'fecha_pago',
        'estado',
        'referencia'
    ];
}

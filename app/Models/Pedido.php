<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;
    protected $fillable = [
        'cotizacion_id',
        'numero_pedido',
        'estado',
        'prioridad',
        'notas',
        'fecha_pedido'
        
    ];
}

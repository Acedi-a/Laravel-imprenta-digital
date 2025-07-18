<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistorialEstado extends Model
{
    use HasFactory;
    protected $fillable = [
        'pedido_id',
        'estado',
        'cambiado_por',
        'cambiado_en',
        'notas'
    ];
}

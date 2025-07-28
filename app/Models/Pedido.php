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
        'fecha_pedido',
        'pago_id',
        'direccion_id'
    ];

    public function pago()
    {
        return $this->belongsTo(\App\Models\Pago::class, 'pago_id');
    }

    public function cotizacion()
    {
        return $this->belongsTo(Cotizacion::class,'cotizacion_id');
    }

    public function envio()
    {
        return $this->hasOne(\App\Models\Envio::class, 'pedido_id');
    }

    public function direccion()
    {
        return $this->belongsTo(Direccion::class, 'direccion_id');
    }
}

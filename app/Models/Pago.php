<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    use HasFactory;
    protected $fillable = [
        'monto',
        'metodo',
        'fecha_pago',
        'estado',
        'referencia',
        'comprobante_url'
    ];

    public function pedido()
    {
        return $this->hasOne(\App\Models\Pedido::class, 'pago_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CotizacionOpcion extends Model
{
    use HasFactory;

    protected $fillable = [
        'cotizacion_id',
        'opcion_producto_id',
    ];
}
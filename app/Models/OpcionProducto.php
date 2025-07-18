<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpcionProducto extends Model
{
    use HasFactory;
    protected $fillable = [
        'producto_id',
        'nombre_opcion',
        'valor_opcion',
        'ajuste_precio',
        'orden'
    ];
}

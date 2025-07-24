<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cotizacion extends Model
{
    use HasFactory;
    protected $table = 'cotizaciones';

    protected $fillable = [
        'usuario_id',
        'producto_id',
        'archivo_id',
        'cantidad',
        'precio_total',
        'estado',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    public function archivo()
    {
        return $this->belongsTo(Archivo::class);
    }
}

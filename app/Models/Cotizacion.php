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
        'ancho',
        'alto',
        'precio_total',
        'estado'
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class, "producto_id");
    }

    public function archivo()
    {
        return $this->belongsTo(Archivo::class, "archivo_id");
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'tipo_impresion',
        'tipo_papel',
        'acabado',
        'color',
        'tamano_papel_id', // Relación con TamanoPapel
        'cantidad_minima',
        'precio_base',
        'descuento',
        'descripcion',
        'estado'
    ];

    public function tamanoPapel()
    {
        return $this->belongsTo(TamanoPapel::class);
    }
}

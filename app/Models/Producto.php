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
        'tamano_papel_id', 
        'cantidad_minima',
        'precio_base',
        'descuento',
        'imagen',
        'descripcion',
        'estado'
    ];


    public function tamanoPapel()
    {
        return $this->belongsTo(TamanoPapel::class, 'tamano_papel_id');
    }


}

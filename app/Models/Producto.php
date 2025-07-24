<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;
    protected $fillable = [
        'categoria',
        'nombre',
        'precio',
        'tipo_unidad',
        'ancho_max',
        'alto_max',
        'estado',
        'descripcion'
    ];

 


}

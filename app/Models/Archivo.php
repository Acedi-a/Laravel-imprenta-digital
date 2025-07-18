<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Archivo extends Model
{
    use HasFactory;
    protected $fillable = [
        'usuario_id',
        'nombre_original',
        'ruta_guardado',
        'tamaño_archivo',
        'tipo_mime'
    ];
}

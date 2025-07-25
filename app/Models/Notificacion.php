<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notificacion extends Model
{
    use HasFactory;
    
    protected $table = 'notificaciones';
    
    protected $fillable = [
        'usuario_id',
        'tipo',
        'titulo',
        'mensaje',
        'leido',
    ];
}

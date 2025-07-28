<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Direccion extends Model
{
    use HasFactory;
    
    protected $table = 'direcciones';
    
    protected $fillable = [
        'usuario_id',
        'linea1',
        'linea2',
        'ciudad',
        'codigo_postal',
        'pais',
        'defecto'
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }
}

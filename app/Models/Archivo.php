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

    // Accessor para mostrar el tamaño en formato legible
    public function getTamanoFormateadoAttribute()
    {
        $tamanoEnKB = $this->tamaño_archivo;

        if ($tamanoEnKB < 1024) {
            return number_format($tamanoEnKB, 2) . ' KB';
        } else {
            return number_format($tamanoEnKB / 1024, 2) . ' MB';
        }
    }
}

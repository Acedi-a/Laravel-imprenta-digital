<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TamanoPapel extends Model
{
    protected $table = 'tamano_papel';
    use HasFactory;
    protected $fillable = [
        'nombre',
        'alto',
        'ancho',
        'descripcion',
        'unidad_medida'
    ];

    public function fotosReferenciales()
    {
        return $this->hasMany(FotoReferencial::class, 'tamano_papel_id');
    }


}

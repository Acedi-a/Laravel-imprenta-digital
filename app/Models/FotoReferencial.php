<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FotoReferencial extends Model
{
    use HasFactory;

    protected $table = 'foto_referenciales'; 

    protected $fillable = [
        'tamano_papel_id',
        'url',
        'descripcion',
    ];

    public function tamanoPapel()
    {
        return $this->belongsTo(TamanoPapel::class);
    }
}

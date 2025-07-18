<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Envio extends Model
{
    use HasFactory;
    protected $fillable = [
        'pedido_id',           
        'direccion_id',        
        'transportista',       
        'codigo_seguimiento',  
        'fecha_envio',         
        'fecha_estimada_entrega'
    ];
}

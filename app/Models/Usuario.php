<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // <- importante
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'usuarios'; // obligatorio porque no se llama 'users'

    protected $fillable = [
        'email',
        'password',
        'nombre',
        'apellido',
        'rol',
        'telefono',
        'estado'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed', // si usas Laravel 10+ (opcional)
    ];

    public function notificaciones()
    {

        return $this->hasMany(Notificacion::class, 'usuario_id');
    }

     public function direcciones()
    {
        return $this->hasMany(\App\Models\Direccion::class, 'usuario_id');
    }
}

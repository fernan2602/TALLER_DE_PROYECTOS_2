<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; //  importante
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'usuarios';

    protected $fillable = [
        'nombre',
        'email',
        'contrasena',
        'id_rol',
        'fecha_nacimiento',
        'fecha_registro',
    ];

    protected $hidden = [
        'contrasena'
    ];

    // Relación: usuario pertenece a un rol
    public function rol()
    {
        return $this->belongsTo(Rol::class, 'id_rol');
    }

    // Relación: usuario tiene muchas preferencias asignadas
    public function preferencias()
    {
        return $this->belongsToMany(
            Preferencia::class,
            'asignacion_preferencia',
            'id_usuario',
            'id_preferencia'
        )->withTimestamps();
    }

    // Relacion medida 
    public function medidas()
    {
        return $this->hasMany(Medida::class, 'id_usuario');
    }

    // Obtener medida --> ultimo registro
    public function ultimaMedida()
    {
        return $this->medidas()
        ->orderBy('created_at','desc')
        ->first();
    }

    // Verificar usuario ya tiene registros 
    public function existeMedida()
    {
        return $this->medidas()->exists();
    }
}

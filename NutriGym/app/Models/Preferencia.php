<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Preferencia extends Model
{
    //
    use HasFactory;

    protected $table = 'preferencias';
    protected $fillable = ['tipo', 'descripcion'];

    // Relación: una preferencia puede estar en muchos usuarios
    public function usuarios()
    {
        return $this->belongsToMany(
            Usuario::class,
            'asignacion_preferencia',
            'id_preferencia',
            'id_usuario'
        )->withTimestamps();
    }
}

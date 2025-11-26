<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AsignacionMenu extends Model
{
    use HasFactory;

    protected $table = 'asignacion_menus';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id_usuario',
        'tipo',
        'calorias',
        'fecha_asignacion'
    ];

    // Relación con alimentos
    public function alimentos()
    {
        return $this->hasMany(MenuAlimento::class, 'asignacion_menu_id');
    }
}
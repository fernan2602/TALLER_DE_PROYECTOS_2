<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuAlimento extends Model
{
    use HasFactory;

    protected $table = 'menu_alimentos';
    
    protected $fillable = [
        'asignacion_menu_id',
        'id_alimento'
    ];

    // Relación con asignación
    public function asignacion()
    {
        return $this->belongsTo(AsignacionMenu::class, 'asignacion_menu_id');
    }

    // Relación con alimento
    public function alimento()
    {
        return $this->belongsTo(Alimento::class, 'id_alimento');
    }
}
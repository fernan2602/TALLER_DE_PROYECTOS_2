<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Progreso extends Model
{
    use HasFactory;

    /**
     * Tabla asociada al modelo
     */
    protected $table = 'progreso';

    /**
     * Clave principal del modelo
     */
    protected $primaryKey = 'id_progreso';

    /**
     * Incremento de ID 
     */
    public $incrementing = true;

    /**
     * Atributos de la tabla 
     */
    protected $fillable = [
        'id_medida',
        'fecha',
        'imc',
        'tmb',
        'porcentaje_grasa',
        'masa_grasa',
        'masa_magra',
        'masa_muscular',
        'porcentaje_musculo',
        'progreso'
    ];

    /**
     * Asignacion de atributos 
     */
    protected $casts = [
        'fecha' => 'date',
        'imc' => 'decimal:2',
        'tmb' => 'decimal:2',
        'porcentaje_grasa' => 'decimal:2',
        'masa_grasa' => 'decimal:2',
        'masa_magra' => 'decimal:2',
        'masa_muscular' => 'decimal:2',
        'porcentaje_musculo' => 'decimal:2',
        'progreso' => 'integer'
    ];

    /**
     * Relación con el modelo Medida
     */
    public function medida()
    {
        return $this->belongsTo(Medida::class, 'id_medida');
    }

    /**
     * Relación con Usuario a través de Medida
     */
    public function usuario()
    {
        return $this->hasOneThrough(
            Usuario::class,
            Medida::class,
            'id', // Clave foránea en la tabla medidas
            'id', // Clave foránea en la tabla usuarios  
            'id_medida', // Clave local en la tabla progreso
            'id_usuario' // Clave local en la tabla medidas
        );
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medida extends Model
{
    use HasFactory;

    /**
     * Tabla asociada al modelo
     */
    protected $table = 'medidas';

    /**
     * Clave principal del modelo
     */
    protected $primaryKey = 'id';

    /**
     * Incremento de ID 
     */
    public $incrementing = true;

    /**
     * Atributos de la tabla 
     */
    protected $fillable = [
        'id_usuario',
        'peso',
        'talla', 
        'edad',
        'genero',
        'circunferencia_brazo',
        'circunferencia_antebrazo',
        'circunferencia_cintura',
        'circunferencia_caderas',
        'circunferencia_muslos',
        'circunferencia_pantorrilla',
        'circunferencia_cuello',
        'fecha_registro'
    ];

    /**
     * Asignacion de atributos 
     */
    protected $casts = [
        'peso' => 'decimal:2',
        'talla' => 'decimal:2',
        'circunferencia_brazo' => 'decimal:2',
        'circunferencia_antebrazo' => 'decimal:2',
        'circunferencia_cintura' => 'decimal:2',
        'circunferencia_caderas' => 'decimal:2',
        'circunferencia_muslos' => 'decimal:2',
        'circunferencia_pantorrilla' => 'decimal:2',
        'circunferencia_cuello' => 'decimal:2',
        'fecha_registro' => 'datetime',
        'edad' => 'integer'
    ];

    /**
     * Relación con el modelo User (Usuario)
     */
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }
}
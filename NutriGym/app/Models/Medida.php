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
        'fecha_registro',
        'estado_fisico'
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
        'edad' => 'integer',
        'estado_fisico'=>'integer',
    ];

    /**
     * Relación con el modelo Usuario
     */
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }

    /**
     * Relación con el modelo Progreso (NUEVA RELACIÓN)
     * Una medida puede tener muchos registros de progreso
     */
    public function progresos()
    {
        return $this->hasMany(Progreso::class, 'id_medida');
    }

    /**
     * Obtener el progreso más reciente (NUEVA RELACIÓN)
     */
    public function progresoReciente()
    {
        return $this->hasOne(Progreso::class, 'id_medida')->latestOfMany();
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alimento extends Model
{
    use HasFactory;

    protected $table = 'alimentos';
    
    protected $fillable = [
        'nombre',
        'categoria',
        'proteina',
        'carbohidratos',
        'grasas',
        'fibra',
        'azucar',
        'calorias',
        'sodio',
        'potasio',
        'calcio',
        'hierro',
        'unidad_medida',
        'tamanio_porcion'
    ];

    protected $casts = [
        'proteina' => 'decimal:2',
        'carbohidratos' => 'decimal:2',
        'grasas' => 'decimal:2',
        'fibra' => 'decimal:2',
        'azucar' => 'decimal:2',
        'calorias' => 'decimal:2',
        'sodio' => 'decimal:2',
        'potasio' => 'decimal:2',
        'calcio' => 'decimal:2',
        'hierro' => 'decimal:2',
        'tamanio_porcion' => 'decimal:2'
    ];

    // Relación con menu_alimentos
    public function menuAlimentos()
    {
        return $this->hasMany(MenuAlimento::class, 'id_alimento');
    }

    // Método para calcular nutrientes por porción personalizada
    public function nutrientesPorPorcion($gramos = 100)
    {
        $factor = $gramos / 100;
        
        return [
            'calorias' => $this->calorias * $factor,
            'proteina' => $this->proteina * $factor,
            'carbohidratos' => $this->carbohidratos * $factor,
            'grasas' => $this->grasas * $factor,
            'fibra' => $this->fibra * $factor,
            'azucar' => $this->azucar * $factor,
            'sodio' => $this->sodio * $factor,
            'potasio' => $this->potasio * $factor,
            'calcio' => $this->calcio * $factor,
            'hierro' => $this->hierro * $factor,
        ];
    }

    // Accessor para el nombre en mayúsculas
    public function getNombreAttribute($value)
    {
        return ucwords(strtolower($value));
    }

    // Método para obtener resumen nutricional
    public function getResumenNutricionalAttribute()
    {
        return "{$this->calorias} kcal - P:{$this->proteina}g C:{$this->carbohidratos}g G:{$this->grasas}g";
    }
}
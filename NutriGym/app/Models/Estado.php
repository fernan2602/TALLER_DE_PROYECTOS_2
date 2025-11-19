<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Estado extends Model
{
    //
    use HasFactory;

    protected $table = 'estado_fisico';

    // Relación: relacion 1 --> todo usuario comienza con un estado y termina con otro
    public function usuarios()
    {
        return $this->hasMany(Medida::class, 'id_estado_fisico');
    }


}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AsignacionPreferencia extends Model
{
    //
    use HasFactory;

    protected $table = 'asignacion_preferencia';
    protected $fillable = ['id_usuario', 'id_preferencia'];
}

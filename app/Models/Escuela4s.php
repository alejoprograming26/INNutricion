<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Escuela4s extends Model
{
    protected $table = 'escuela4s';

    protected $fillable = [
        'observacion', 'responsable', 'fecha', 'nombre_escuela', 
        'sector_id', 'director_a', 'codigo_dea', 'codigo_cnae', 
        'tema_tratado', 'fase'
    ];

    public function sector()
    {
        return $this->belongsTo(Sector::class, 'sector_id');
    }
}

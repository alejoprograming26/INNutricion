<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiversidadDietaria extends Model
{
    protected $fillable = [
        'observacion', 'responsable', 'fecha', 
        'sector_id', 'cantidad'
    ];

    public function sector()
    {
        return $this->belongsTo(Sector::class, 'sector_id');
    }
}

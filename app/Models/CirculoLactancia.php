<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Observers\CirculoLactanciaObserver;

class CirculoLactancia extends Model
{
    protected static function booted(): void
    {
        static::observe(CirculoLactanciaObserver::class);
    }

    protected $fillable = [
        'observacion', 'responsable', 'fecha',
        'sector_id', 'nombre_grupo', 'cantidad'
    ];

    public function sector()
    {
        return $this->belongsTo(Sector::class, 'sector_id');
    }
}

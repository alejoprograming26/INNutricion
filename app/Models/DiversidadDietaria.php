<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Observers\DiversidadDietariaObserver;

class DiversidadDietaria extends Model
{
    protected static function booted(): void
    {
        static::observe(DiversidadDietariaObserver::class);
    }
    protected $fillable = [
        'observacion', 'responsable', 'fecha', 
        'sector_id', 'cantidad'
    ];

    public function sector()
    {
        return $this->belongsTo(Sector::class, 'sector_id');
    }
}

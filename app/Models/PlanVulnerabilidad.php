<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Observers\PlanVulnerabilidadObserver;

class PlanVulnerabilidad extends Model
{
    protected static function booted(): void
    {
        static::observe(PlanVulnerabilidadObserver::class);
    }

    protected $fillable = [
        'observacion', 'responsable', 'fecha',
        'sector_id', 'total_entregas', 'tipo'
    ];

    protected $casts = [
        'tipo' => 'array',
    ];

    public function sector()
    {
        return $this->belongsTo(Sector::class, 'sector_id');
    }
}

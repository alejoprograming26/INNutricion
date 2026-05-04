<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Observers\FeriaCampoObserver;

class FeriaCampo extends Model
{
    protected static function booted(): void
    {
        static::observe(FeriaCampoObserver::class);
    }

    protected $fillable = [
        'observacion', 'responsable', 'fecha',
        'sector_id',
        'venta_lina_nutrivida',
        'antrometria',
        'tipo_a',
        'tipo_b',
        'tipo_a_plus',
        'campana4s',
        'tema_tratado',
    ];

    protected $casts = [
        'fecha'                => 'date',
        'venta_lina_nutrivida' => 'boolean',
        'antrometria'          => 'boolean',
        'tipo_a'               => 'integer',
        'tipo_b'               => 'integer',
        'tipo_a_plus'          => 'integer',
        'campana4s'            => 'boolean',
    ];

    public function sector()
    {
        return $this->belongsTo(Sector::class, 'sector_id');
    }
}

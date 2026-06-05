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
        // Condición (desglose de antropometría)
        'embarazada',
        'mujer_lactante',
        'menor_72_meses',
        'escolar',
        'adolescente',
        'adulto',
        'adulto_mayor',
        'encamado',
        'discapacidad',
    ];

    protected $casts = [
        'fecha'                => 'date',
        'venta_lina_nutrivida' => 'boolean',
        'antrometria'          => 'boolean',
        'tipo_a'               => 'integer',
        'tipo_b'               => 'integer',
        'tipo_a_plus'          => 'integer',
        'campana4s'            => 'boolean',
        'embarazada'           => 'integer',
        'mujer_lactante'       => 'integer',
        'menor_72_meses'       => 'integer',
        'escolar'              => 'integer',
        'adolescente'          => 'integer',
        'adulto'               => 'integer',
        'adulto_mayor'         => 'integer',
        'encamado'             => 'integer',
        'discapacidad'         => 'integer',
    ];

    public function sector()
    {
        return $this->belongsTo(Sector::class, 'sector_id');
    }
}

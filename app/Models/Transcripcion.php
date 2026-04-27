<?php

namespace App\Models;

use App\Observers\TranscripcionObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transcripcion extends Model
{
    use HasFactory;

    protected $table = 'transcripciones';

    /**
     * Registrar el observer para invalidación automática de caché.
     */
    protected static function booted(): void
    {
        static::observe(TranscripcionObserver::class);
    }

    protected $fillable = [
        'observacion',
        'responsable',
        'fecha',
        'tipo',
        'sector_id',
        'cantidad',
        'ingreso',
        'egreso',
    ];

    protected $casts = [
        'fecha'    => 'date',
        'cantidad' => 'integer',
        'ingreso'  => 'integer',
        'egreso'   => 'integer',
    ];

    // Tipos disponibles
    public const TIPOS = [
        'VULNERABILIDAD',
        'CPLV',
        'LACTANCIA MATERNA',
        'ENCUESTA DIETARIA',
        'MONITOREO DE PRECIO',
        'SUGIMA',
        'PERINATAL',
        'PRIMER NIVEL DE ATENCION',
        'DESNUTRICION GRAVE',
        'CONSULTA',
    ];

    // Solo SUGIMA tiene ingreso/egreso
    public const TIPO_CON_INGRESOS_EGRESOS = 'SUGIMA';

    public function sector()
    {
        return $this->belongsTo(Sector::class, 'sector_id');
    }

    // Relaciones indirectas para conveniencia
    public function comuna()
    {
        return $this->sector->comuna();
    }

    public function parroquia()
    {
        return $this->sector->comuna->parroquia();
    }

    public function municipio()
    {
        return $this->sector->comuna->parroquia->municipio();
    }
}

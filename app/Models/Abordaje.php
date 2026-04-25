<?php

namespace App\Models;

use App\Observers\AbordajeObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Abordaje extends Model
{
    use HasFactory;

    protected $table = 'abordajes';

    /**
     * Registrar el observer para invalidación automática de caché.
     */
    protected static function booted(): void
    {
        static::observe(AbordajeObserver::class);
    }

    protected $fillable = [
        'observacion',
        'fecha',
        'municipio_id',
        'parroquia_id',
        'comuna_id',
        'sector_id',
        'cantidad',
    ];

    protected $casts = [
        'fecha'    => 'date',
        'cantidad' => 'integer',
    ];

    public function municipio()
    {
        return $this->belongsTo(Municipio::class, 'municipio_id');
    }

    public function parroquia()
    {
        return $this->belongsTo(Parroquia::class, 'parroquia_id');
    }

    public function comuna()
    {
        return $this->belongsTo(Comuna::class, 'comuna_id');
    }

    public function sector()
    {
        return $this->belongsTo(Sector::class, 'sector_id');
    }
}

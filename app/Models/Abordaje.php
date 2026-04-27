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
        'sector_id',
        'cantidad',
    ];

    protected $casts = [
        'fecha'    => 'date',
        'cantidad' => 'integer',
    ];

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

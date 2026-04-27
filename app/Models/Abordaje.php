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
        'responsable',
        'fecha',
        'sector_id',
        'cantidad',
        'total_a',
        'total_b',
        'total_a_plus',
    ];

    protected $casts = [
        'fecha'        => 'date',
        'cantidad'     => 'integer',
        'total_a'      => 'integer',
        'total_b'      => 'integer',
        'total_a_plus' => 'integer',
    ];

    public function sector()
    {
        return $this->belongsTo(Sector::class, 'sector_id');
    }




}

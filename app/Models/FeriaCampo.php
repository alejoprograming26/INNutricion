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
        'sector_id'
    ];

    public function sector()
    {
        return $this->belongsTo(Sector::class, 'sector_id');
    }
}

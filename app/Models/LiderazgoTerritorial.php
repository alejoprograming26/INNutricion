<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Observers\LiderazgoTerritorialObserver;

class LiderazgoTerritorial extends Model
{
    protected static function booted(): void
    {
        static::observe(LiderazgoTerritorialObserver::class);
    }
    protected $fillable = [
        'observacion', 'responsable', 'fecha', 
        'sector_id', 'cantidad', 'tema_tratado'
    ];

    public function sector()
    {
        return $this->belongsTo(Sector::class, 'sector_id');
    }
}

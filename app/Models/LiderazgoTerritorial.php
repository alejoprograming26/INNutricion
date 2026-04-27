<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LiderazgoTerritorial extends Model
{
    protected $fillable = [
        'observacion', 'responsable', 'fecha', 
        'sector_id', 'cantidad', 'tema_tratado'
    ];

    public function sector()
    {
        return $this->belongsTo(Sector::class, 'sector_id');
    }
}

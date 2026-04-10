<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comuna extends Model
{
    use HasFactory;

    protected $table = 'comunas';

    protected $fillable = [
        'sector_id',
        'nombre',
    ];

    /**
     * Una comuna pertenece a un sector.
     */
    public function sector()
    {
        return $this->belongsTo(Sector::class, 'sector_id');
    }
}

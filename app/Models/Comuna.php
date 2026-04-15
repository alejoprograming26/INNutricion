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

    /**
     * Una comuna pertenece a una parroquia (a través de sector).
     */
    public function parroquia()
    {
        return $this->hasOneThrough(
            Parroquia::class,
            Sector::class,
            'id', // FK en sectores (id del sector)
            'id', // FK en parroquias (id de la parroquia)
            'sector_id', // Local Key en comunas (sector_id)
            'parroquia_id' // Local Key en sectores (parroquia_id)
        );
    }
}

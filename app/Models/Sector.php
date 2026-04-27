<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sector extends Model
{
    use HasFactory;

    protected $table = 'sectores';

    protected $fillable = [
        'comuna_id',
        'nombre',
    ];

    /**
     * Un sector pertenece a una comuna.
     */
    public function comuna()
    {
        return $this->belongsTo(Comuna::class, 'comuna_id');
    }

    /**
     * Un sector pertenece a una parroquia (a través de comuna).
     */
    public function parroquia()
    {
        return $this->hasOneThrough(
            Parroquia::class,
            Comuna::class,
            'id', // FK en comunas (id de la comuna)
            'id', // FK en parroquias (id de la parroquia)
            'comuna_id', // Local Key en sectores (comuna_id)
            'parroquia_id' // Local Key en comunas (parroquia_id)
        );
    }



    /**
     * Un sector tiene muchas transcripciones.
     */
    public function transcripciones()
    {
        return $this->hasMany(Transcripcion::class, 'sector_id');
    }

    /**
     * Un sector tiene muchos abordajes.
     */
    public function abordajes()
    {
        return $this->hasMany(Abordaje::class, 'sector_id');
    }
}

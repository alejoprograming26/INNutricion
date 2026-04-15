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
     * Un sector pertenece a un municipio (a través de comuna).
     */
    public function municipio()
    {
        // Pasamos por Comuna y luego Parroquia es un poco complejo para hasOneThrough estándar de 2 niveles.
        // Pero podemos intentar llegar al municipio si la jerarquía es clara.
        // O simplemente usar:
        return $this->comuna->parroquia->municipio();
    }
}

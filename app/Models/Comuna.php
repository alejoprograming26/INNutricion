<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comuna extends Model
{
    use HasFactory;

    protected $table = 'comunas';

    protected $fillable = [
        'parroquia_id',
        'nombre',
    ];

    /**
     * Una comuna pertenece a una parroquia.
     */
    public function parroquia()
    {
        return $this->belongsTo(Parroquia::class, 'parroquia_id');
    }

    /**
     * Una comuna tiene muchos sectores.
     */
    public function sectores()
    {
        return $this->hasMany(Sector::class, 'comuna_id');
    }

    /**
     * Una comuna pertenece a un municipio (a través de parroquia).
     */
    public function municipio()
    {
        return $this->hasOneThrough(
            Municipio::class,
            Parroquia::class,
            'id', // FK en parroquias (id de la parroquia)
            'id', // FK en municipios (id del municipio)
            'parroquia_id', // Local Key en comunas (parroquia_id)
            'municipio_id' // Local Key en parroquias (municipio_id)
        );
    }
}

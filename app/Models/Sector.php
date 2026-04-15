<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sector extends Model
{
    use HasFactory;

    protected $table = 'sectores';

    protected $fillable = [
        'parroquia_id',
        'nombre',
    ];

    /**
     * Un sector pertenece a una parroquia.
     */
    public function parroquia()
    {
        return $this->belongsTo(Parroquia::class, 'parroquia_id');
    }

    /**
     * Un sector pertenece a un municipio (a través de parroquia).
     */
    public function municipio()
    {
        return $this->hasOneThrough(
            Municipio::class,
            Parroquia::class,
            'id', // FK en parroquias (id de la parroquia)
            'id', // FK en municipios (id del municipio)
            'parroquia_id', // Local Key en sectores (parroquia_id)
            'municipio_id' // Local Key en parroquias (municipio_id)
        );
    }

    /**
     * Un sector tiene muchas comunas.
     */
    public function comunas()
    {
        return $this->hasMany(Comuna::class, 'sector_id');
    }
}

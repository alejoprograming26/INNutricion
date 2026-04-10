<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Municipio extends Model
{
    use HasFactory;

    protected $table = 'municipios';

    protected $fillable = [
        'nombre',
    ];

    /**
     * Un municipio posee muchas parroquias.
     */
    public function parroquias()
    {
        return $this->hasMany(Parroquia::class, 'municipio_id');
    }

    /**
     * Un municipio puede tener muchos detalles de metas.
     */
    public function detalleMetas()
    {
        return $this->hasMany(DetalleMeta::class, 'municipio_id');
    }

    /**
     * Un municipio está asociado a múltiples transcripciones.
     */
    public function transcripciones()
    {
        return $this->hasMany(Transcripcion::class, 'municipio_id');
    }
}

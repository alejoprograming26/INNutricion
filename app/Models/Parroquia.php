<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parroquia extends Model
{
    use HasFactory;

    protected $table = 'parroquias';

    protected $fillable = [
        'municipio_id',
        'nombre',
    ];

    /**
     * Una parroquia pertenece a un municipio.
     */
    public function municipio()
    {
        return $this->belongsTo(Municipio::class, 'municipio_id');
    }

    /**
     * Una parroquia tiene muchos sectores.
     */
    public function sectores()
    {
        return $this->hasMany(Sector::class, 'parroquia_id');
    }
}

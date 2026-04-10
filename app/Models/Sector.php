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
     * Un sector tiene muchas comunas.
     */
    public function comunas()
    {
        return $this->hasMany(Comuna::class, 'sector_id');
    }
}

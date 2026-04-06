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
}

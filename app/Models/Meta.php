<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meta extends Model
{
    use HasFactory;

    protected $table = 'metas';

    protected $fillable = [
        'ano',
        'total',
    ];

    /**
     * Una meta tiene muchos detalles (uno por municipio).
     */
    public function detalles()
    {
        return $this->hasMany(DetalleMeta::class, 'meta_id');
    }
}

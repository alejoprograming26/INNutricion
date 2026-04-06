<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleMeta extends Model
{
    use HasFactory;

    protected $table = 'detalle_metas';

    protected $fillable = [
        'meta_id',
        'municipio_id',
        'meta_anual',
        'meta_mensual',
    ];

    /**
     * El detalle pertenece a una meta.
     */
    public function meta()
    {
        return $this->belongsTo(Meta::class, 'meta_id');
    }

    /**
     * El detalle pertenece a un municipio.
     */
    public function municipio()
    {
        return $this->belongsTo(Municipio::class, 'municipio_id');
    }
}

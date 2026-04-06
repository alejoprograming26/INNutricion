<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static \Illuminate\Database\Eloquent\Builder query()
 * @method static \App\Models\Ajuste|null first($columns = ['*'])
 * @method static \App\Models\Ajuste|null find($id, $columns = ['*'])
 * @method static \App\Models\Ajuste create(array $attributes = [])
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Ajuste extends Model
{
    use HasFactory;

    protected $table = 'ajustes';

    protected $fillable = [
        'nombre',
        'descripcion',
        'sucursal',
        'direccion',
        'telefonos',
        'logo',
        'imagen_login',
        'email',
        'pagina_web',
    ];
}

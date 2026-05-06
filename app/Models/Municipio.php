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
     * Colores representativos para cada municipio.
     */
    public static array $colors = [
        'ANDRÉS ELOY BLANCO' => ['hex' => '#f43f5e', 'bg' => 'bg-rose-500',    'light' => 'bg-rose-100 dark:bg-rose-500/10',  'text' => 'text-rose-600 dark:text-rose-400',    'border' => 'border-rose-200 dark:border-rose-500/20'],
        'CRESPO'             => ['hex' => '#3b82f6', 'bg' => 'bg-blue-500',    'light' => 'bg-blue-100 dark:bg-blue-500/10',  'text' => 'text-blue-600 dark:text-blue-400',    'border' => 'border-blue-200 dark:border-blue-500/20'],
        'IRIBARREN'          => ['hex' => '#8b5cf6', 'bg' => 'bg-violet-500',  'light' => 'bg-violet-100 dark:bg-violet-500/10', 'text' => 'text-violet-600 dark:text-violet-400', 'border' => 'border-violet-200 dark:border-violet-500/20'],
        'JIMÉNEZ'            => ['hex' => '#f59e0b', 'bg' => 'bg-amber-500',   'light' => 'bg-amber-100 dark:bg-amber-500/10', 'text' => 'text-amber-600 dark:text-amber-400',   'border' => 'border-amber-200 dark:border-amber-500/20'],
        'MORÁN'              => ['hex' => '#10b981', 'bg' => 'bg-emerald-500', 'light' => 'bg-emerald-100 dark:bg-emerald-500/10', 'text' => 'text-emerald-600 dark:text-emerald-400', 'border' => 'border-emerald-200 dark:border-emerald-500/20'],
        'PALAVECINO'         => ['hex' => '#ec4899', 'bg' => 'bg-pink-500',    'light' => 'bg-pink-100 dark:bg-pink-500/10',  'text' => 'text-pink-600 dark:text-pink-400',    'border' => 'border-pink-200 dark:border-pink-500/20'],
        'SIMÓN PLANAS'       => ['hex' => '#06b6d4', 'bg' => 'bg-cyan-500',    'light' => 'bg-cyan-100 dark:bg-cyan-500/10',  'text' => 'text-cyan-600 dark:text-cyan-400',    'border' => 'border-cyan-200 dark:border-cyan-500/20'],
        'TORRES'             => ['hex' => '#f97316', 'bg' => 'bg-orange-500',  'light' => 'bg-orange-100 dark:bg-orange-500/10', 'text' => 'text-orange-600 dark:text-orange-400', 'border' => 'border-orange-200 dark:border-orange-500/20'],
        'URDANETA'           => ['hex' => '#6366f1', 'bg' => 'bg-indigo-500',  'light' => 'bg-indigo-100 dark:bg-indigo-500/10', 'text' => 'text-indigo-600 dark:text-indigo-400', 'border' => 'border-indigo-200 dark:border-indigo-500/20'],
    ];

    public function getColorAttribute()
    {
        return self::$colors[$this->nombre] ?? ['hex' => '#84cc16', 'bg' => 'bg-lime-500', 'light' => 'bg-lime-100', 'text' => 'text-lime-600', 'border' => 'border-lime-200'];
    }
}

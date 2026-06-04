<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;

trait ClearsActivityCaches
{
    /**
     * Boot the trait and register the deleted event listener.
     */
    protected static function bootClearsActivityCaches(): void
    {
        static::deleted(function () {
            static::clearAllActivityCaches();
        });
    }

    /**
     * Clear all cached activity statistics.
     */
    public static function clearAllActivityCaches(): void
    {
        // Clear transcripcion caches for all types
        foreach (\App\Models\Transcripcion::TIPOS as $tipo) {
            Cache::forget('transcripcion_metrics_' . $tipo);
        }
        // Clear other activity metrics caches
        Cache::forget('plan_vulnerabilidad_metrics');
        Cache::forget('liderazgo_metrics');
        Cache::forget('feria_campo_metrics');
        Cache::forget('escuela4s_metrics');
        Cache::forget('diversidad_metrics');
        Cache::forget('circulo_lactancia_metrics');
        Cache::forget('abordaje_metrics');
    }
}

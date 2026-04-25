<?php

namespace App\Observers;

use App\Models\Transcripcion;
use Illuminate\Support\Facades\Cache;

class TranscripcionObserver
{
    /**
     * Limpia el caché de métricas del tipo afectado.
     * Se llama en cada write operation (create, update, delete, restore).
     */
    private function clearCache(Transcripcion $transcripcion): void
    {
        // Limpia el caché específico del tipo de esta transcripción
        Cache::forget('transcripcion_metrics_' . $transcripcion->tipo);
    }

    public function created(Transcripcion $transcripcion): void
    {
        $this->clearCache($transcripcion);
    }

    public function updated(Transcripcion $transcripcion): void
    {
        // Si el tipo cambió, limpiar ambos cachés (el viejo y el nuevo)
        if ($transcripcion->wasChanged('tipo')) {
            Cache::forget('transcripcion_metrics_' . $transcripcion->getOriginal('tipo'));
        }
        $this->clearCache($transcripcion);
    }

    public function deleted(Transcripcion $transcripcion): void
    {
        $this->clearCache($transcripcion);
    }
}

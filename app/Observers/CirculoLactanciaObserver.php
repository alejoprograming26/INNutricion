<?php

namespace App\Observers;

use App\Models\CirculoLactancia;
use Illuminate\Support\Facades\Cache;

class CirculoLactanciaObserver
{
    /**
     * Limpia el caché de métricas de círculo de lactancia.
     * Se llama en cada write operation (create, update, delete).
     */
    private function clearCache(): void
    {
        Cache::forget('circulo_lactancia_metrics');
    }

    public function created(CirculoLactancia $circuloLactancia): void
    {
        $this->clearCache();
    }

    public function updated(CirculoLactancia $circuloLactancia): void
    {
        $this->clearCache();
    }

    public function deleted(CirculoLactancia $circuloLactancia): void
    {
        $this->clearCache();
    }
}

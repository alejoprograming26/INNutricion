<?php

namespace App\Observers;

use App\Models\DiversidadDietaria;
use Illuminate\Support\Facades\Cache;

class DiversidadDietariaObserver
{
    /**
     * Limpia el caché de métricas de diversidad dietaria.
     * Se llama en cada write operation (create, update, delete).
     */
    private function clearCache(): void
    {
        Cache::forget('diversidad_metrics');
    }

    public function created(DiversidadDietaria $diversidadDietaria): void
    {
        $this->clearCache();
    }

    public function updated(DiversidadDietaria $diversidadDietaria): void
    {
        $this->clearCache();
    }

    public function deleted(DiversidadDietaria $diversidadDietaria): void
    {
        $this->clearCache();
    }
}

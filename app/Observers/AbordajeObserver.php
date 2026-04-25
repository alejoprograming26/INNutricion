<?php

namespace App\Observers;

use App\Models\Abordaje;
use Illuminate\Support\Facades\Cache;

class AbordajeObserver
{
    /**
     * Limpia el caché de métricas de abordajes.
     * Se llama en cada write operation (create, update, delete).
     */
    private function clearCache(): void
    {
        Cache::forget('abordaje_metrics');
    }

    public function created(Abordaje $abordaje): void
    {
        $this->clearCache();
    }

    public function updated(Abordaje $abordaje): void
    {
        $this->clearCache();
    }

    public function deleted(Abordaje $abordaje): void
    {
        $this->clearCache();
    }
}

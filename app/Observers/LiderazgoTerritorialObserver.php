<?php

namespace App\Observers;

use App\Models\LiderazgoTerritorial;
use Illuminate\Support\Facades\Cache;

class LiderazgoTerritorialObserver
{
    /**
     * Limpia el caché de métricas de liderazgo territorial.
     * Se llama en cada write operation (create, update, delete).
     */
    private function clearCache(): void
    {
        Cache::forget('liderazgo_metrics');
    }

    public function created(LiderazgoTerritorial $liderazgoTerritorial): void
    {
        $this->clearCache();
    }

    public function updated(LiderazgoTerritorial $liderazgoTerritorial): void
    {
        $this->clearCache();
    }

    public function deleted(LiderazgoTerritorial $liderazgoTerritorial): void
    {
        $this->clearCache();
    }
}

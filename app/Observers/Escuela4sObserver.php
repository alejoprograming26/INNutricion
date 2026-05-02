<?php

namespace App\Observers;

use App\Models\Escuela4s;
use Illuminate\Support\Facades\Cache;

class Escuela4sObserver
{
    /**
     * Limpia el caché de métricas de escuela 4s.
     * Se llama en cada write operation (create, update, delete).
     */
    private function clearCache(): void
    {
        Cache::forget('escuela4s_metrics');
    }

    public function created(Escuela4s $escuela4s): void
    {
        $this->clearCache();
    }

    public function updated(Escuela4s $escuela4s): void
    {
        $this->clearCache();
    }

    public function deleted(Escuela4s $escuela4s): void
    {
        $this->clearCache();
    }
}

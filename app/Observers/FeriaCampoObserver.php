<?php

namespace App\Observers;

use App\Models\FeriaCampo;
use Illuminate\Support\Facades\Cache;

class FeriaCampoObserver
{
    /**
     * Limpia el caché de métricas de feria de campo.
     * Se llama en cada write operation (create, update, delete).
     */
    private function clearCache(): void
    {
        Cache::forget('feria_campo_metrics');
    }

    public function created(FeriaCampo $feriaCampo): void
    {
        $this->clearCache();
    }

    public function updated(FeriaCampo $feriaCampo): void
    {
        $this->clearCache();
    }

    public function deleted(FeriaCampo $feriaCampo): void
    {
        $this->clearCache();
    }
}

<?php

namespace App\Observers;

use App\Models\PlanVulnerabilidad;
use Illuminate\Support\Facades\Cache;

class PlanVulnerabilidadObserver
{
    /**
     * Limpia el caché de métricas de plan de vulnerabilidad.
     * Se llama en cada write operation (create, update, delete).
     */
    private function clearCache(): void
    {
        Cache::forget('plan_vulnerabilidad_metrics');
    }

    public function created(PlanVulnerabilidad $planVulnerabilidad): void
    {
        $this->clearCache();
    }

    public function updated(PlanVulnerabilidad $planVulnerabilidad): void
    {
        $this->clearCache();
    }

    public function deleted(PlanVulnerabilidad $planVulnerabilidad): void
    {
        $this->clearCache();
    }
}

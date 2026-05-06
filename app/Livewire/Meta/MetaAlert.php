<?php

namespace App\Livewire\Meta;

use App\Models\Meta;
use App\Models\Municipio;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Component;

class MetaAlert extends Component
{
    public function render()
    {
        $this->checkAchievements();
        return view('livewire.meta.meta-alert');
    }

    #[On('check-goals')]
    public function checkAchievements()
    {
        $anoActual = now()->year;
        $meta = Meta::where('ano', $anoActual)->with('detalles')->first();

        if (!$meta) return;

        // Obtener progreso real por municipio
        $transcripcionesPorMunicipio = DB::table('transcripciones')
            ->join('sectores',   'transcripciones.sector_id',    '=', 'sectores.id')
            ->join('comunas',     'sectores.comuna_id',          '=', 'comunas.id')
            ->join('parroquias',  'comunas.parroquia_id',        '=', 'parroquias.id')
            ->whereYear('transcripciones.fecha', $anoActual)
            ->select(
                'parroquias.municipio_id',
                DB::raw('SUM(transcripciones.cantidad) as total')
            )
            ->groupBy('parroquias.municipio_id')
            ->pluck('total', 'municipio_id')
            ->toArray();

        $municipios = Municipio::all();

        foreach ($meta->detalles as $detalle) {
            $real = (int) ($transcripcionesPorMunicipio[$detalle->municipio_id] ?? 0);
            $metaAnual = $detalle->meta_anual;

            if ($metaAnual > 0 && $real >= $metaAnual) {
                $municipio = $municipios->firstWhere('id', $detalle->municipio_id);
                $cacheKey = "meta_celebrated_{$anoActual}_{$municipio->id}_user_" . auth()->id();

                if (!cache()->has($cacheKey)) {
                    cache()->forever($cacheKey, true);
                    
                    $this->dispatch('meta-achieved', [
                        'municipio' => $municipio->nombre,
                        'color' => $municipio->color['hex'],
                        'total' => $real
                    ]);
                }
            }
        }
    }
}

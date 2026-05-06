<?php

namespace App\Livewire;

use App\Models\Meta;
use App\Models\Municipio;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class MetaGraficosController extends Component
{
    public ?int $meta_id = null;
    public int  $ano     = 0;

    // Datos procesados para gráficos
    public array $kpis              = [];
    public array $municipios        = [];
    public array $progresoMensual   = [];
    public array $tipoDistribucion  = [];
    public array $municipioCards    = [];


    public function mount(int $id)
    {
        $meta = Meta::with(['detalles.municipio'])->findOrFail($id);
        $this->meta_id = $meta->id;
        $this->ano     = $meta->ano;
        $this->cargarDatos($meta);
    }

    private function cargarDatos(Meta $meta)
    {
        $ano = $meta->ano;

        // 1. Obtener metas por municipio
        $metasPorMunicipio = $meta->detalles->keyBy('municipio_id');

        // 2. Obtener municipios
        $municipios = Municipio::orderBy('nombre')->get();

        // 3. Transcripciones reales agrupadas por municipio para el año
        $transcripcionesPorMunicipio = DB::table('transcripciones')
            ->join('sectores',   'transcripciones.sector_id',    '=', 'sectores.id')
            ->join('comunas',     'sectores.comuna_id',          '=', 'comunas.id')
            ->join('parroquias',  'comunas.parroquia_id',        '=', 'parroquias.id')
            ->whereYear('transcripciones.fecha', $ano)
            ->select(
                'parroquias.municipio_id',
                DB::raw('SUM(transcripciones.cantidad) as total')
            )
            ->groupBy('parroquias.municipio_id')
            ->pluck('total', 'municipio_id')
            ->toArray();

        // 4. Transcripciones por municipio y mes
        $transcripcionesMensuales = DB::table('transcripciones')
            ->join('sectores',   'transcripciones.sector_id',    '=', 'sectores.id')
            ->join('comunas',     'sectores.comuna_id',          '=', 'comunas.id')
            ->join('parroquias',  'comunas.parroquia_id',        '=', 'parroquias.id')
            ->whereYear('transcripciones.fecha', $ano)
            ->select(
                'parroquias.municipio_id',
                DB::raw('MONTH(transcripciones.fecha) as mes'),
                DB::raw('SUM(transcripciones.cantidad) as total')
            )
            ->groupBy('parroquias.municipio_id', DB::raw('MONTH(transcripciones.fecha)'))
            ->get()
            ->groupBy('municipio_id');

        // 5. Distribución por tipo
        $tipoDist = DB::table('transcripciones')
            ->whereYear('fecha', $ano)
            ->select('tipo', DB::raw('SUM(cantidad) as total'))
            ->groupBy('tipo')
            ->orderByDesc('total')
            ->get();

        // 6. Transcripciones por tipo y municipio
        $tiposPorMunicipio = DB::table('transcripciones')
            ->join('sectores',   'transcripciones.sector_id',    '=', 'sectores.id')
            ->join('comunas',     'sectores.comuna_id',          '=', 'comunas.id')
            ->join('parroquias',  'comunas.parroquia_id',        '=', 'parroquias.id')
            ->whereYear('transcripciones.fecha', $ano)
            ->select(
                'parroquias.municipio_id',
                'transcripciones.tipo',
                DB::raw('SUM(transcripciones.cantidad) as total')
            )
            ->groupBy('parroquias.municipio_id', 'transcripciones.tipo')
            ->get()
            ->groupBy('municipio_id');

        // Procesar KPIs
        $metaTotal  = $meta->total;
        $realTotal  = array_sum($transcripcionesPorMunicipio);
        $porcentaje = $metaTotal > 0 ? round(($realTotal / $metaTotal) * 100, 1) : 0;
        
        // Contar municipios que cumplieron la meta
        $municipiosCompletados = 0;
        foreach ($municipios as $mun) {
            $det = $metasPorMunicipio->get($mun->id);
            $mAnual = $det ? $det->meta_anual : 0;
            $rReal  = (int) ($transcripcionesPorMunicipio[$mun->id] ?? 0);
            if ($mAnual > 0 && $rReal >= $mAnual) {
                $municipiosCompletados++;
            }
        }

        $this->kpis = [
            'meta_total'            => $metaTotal,
            'real_total'            => $realTotal,
            'porcentaje'            => $porcentaje,
            'faltante'              => max(0, $metaTotal - $realTotal),
            'municipios_completados' => $municipiosCompletados,
            'total_municipios'      => count($municipios),
        ];

        // Procesar Municipios
        foreach ($municipios as $mun) {
            $detalle   = $metasPorMunicipio->get($mun->id);
            $metaAnual = $detalle ? $detalle->meta_anual : 0;
            $real      = (int) ($transcripcionesPorMunicipio[$mun->id] ?? 0);
            $colorInfo = $mun->color;

            $this->municipios[] = [
                'nombre'     => $mun->nombre,
                'meta_anual' => $metaAnual,
                'real'       => $real,
                'color'      => $colorInfo['hex'],
            ];

            // Cards
            $tiposDelMun = $tiposPorMunicipio->get($mun->id, collect());
            
            // Datos mensuales para mini-chart individual
            $mensuales = $transcripcionesMensuales->get($mun->id, collect());
            $mapa      = $mensuales->pluck('total', 'mes')->toArray();
            $datosMes  = [];
            for ($m = 1; $m <= 12; $m++) {
                $datosMes[] = (int) ($mapa[$m] ?? 0);
            }

            $this->municipioCards[] = [
                'id'          => $mun->id,
                'nombre'      => $mun->nombre,
                'meta_anual'  => $metaAnual,
                'meta_mensual'=> $detalle ? $detalle->meta_mensual : 0,
                'real'        => $real,
                'porcentaje'  => $metaAnual > 0 ? round(($real / $metaAnual) * 100, 1) : 0,
                'faltante'    => max(0, $metaAnual - $real),
                'color'       => $colorInfo,
                'tipos'       => $tiposDelMun->map(fn($t) => ['tipo' => $t->tipo, 'total' => (int)$t->total])->sortByDesc('total')->values()->toArray(),
                'datos_mes'   => $datosMes,
            ];

            // Progreso Mensual
            $mensuales = $transcripcionesMensuales->get($mun->id, collect());
            $mapa      = $mensuales->pluck('total', 'mes')->toArray();
            $acumulado = [];
            $sum       = 0;
            for ($m = 1; $m <= 12; $m++) {
                $sum += (int) ($mapa[$m] ?? 0);
                $acumulado[] = $sum;
            }
            $this->progresoMensual[] = [
                'nombre'    => $mun->nombre,
                'color'     => $colorInfo['hex'],
                'acumulado' => $acumulado,
            ];
        }

        // Distribución Tipos
        $this->tipoDistribucion = $tipoDist->map(fn($t) => ['tipo' => $t->tipo, 'total' => (int)$t->total])->toArray();
    }

    public function render()
    {
        return view('livewire.meta.meta-graficos');
    }
}

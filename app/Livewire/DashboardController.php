<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

#[Layout('components.layouts.app')]
class DashboardController extends Component
{
    public $graphDistribution    = [];
    public $graphMonthly         = [];
    public $recentActivities     = [];
    public $totalAtendidos       = 0;
    public $totalRegistros       = 0;
    public $municipiosAbordados  = 0;
    public $promedioDiario       = 0;
    public $metaTrans            = 0;
    public $realTrans            = 0;

    // ── Mapa interactivo ────────────────────────────────────────────
    public $selectedMunicipioId     = null;
    public $selectedMunicipioNombre = null;
    public $municipiosStats         = [];

    public $year;

    // Colores por municipio (DB IDs 1-9)
    private array $muniColors = [
        1 => '#6366F1', // Iribarren   — Indigo vibrante
        2 => '#EF4444', // Torres      — Rojo brillante
        3 => '#F59E0B', // Jiménez     — Ámbar
        4 => '#3B82F6', // Morán       — Azul vivo
        5 => '#F97316', // Palavecino  — Naranja
        6 => '#10B981', // Andrés Eloy Blanco — Esmeralda
        7 => '#06B6D4', // Crespo      — Cian/Turquesa
        8 => '#A3E635', // Simón Planas — Lima
        9 => '#A78BFA', // Urdaneta    — Púrpura
    ];

    private array $muniNames = [
        1 => 'Iribarren',
        2 => 'Torres',
        3 => 'Jiménez',
        4 => 'Morán',
        5 => 'Palavecino',
        6 => 'Andrés Eloy Blanco',
        7 => 'Crespo',
        8 => 'Simón Planas',
        9 => 'Urdaneta',
    ];

    public function mount(): void
    {
        $this->year = date('Y');
        $this->loadData();
    }

    /**
     * Toggle municipality filter. Dispatches chart refresh after reload.
     */
    public function selectMunicipio(int $id): void
    {
        if ($this->selectedMunicipioId == $id) {
            $this->selectedMunicipioId     = null;
            $this->selectedMunicipioNombre = null;
        } else {
            $this->selectedMunicipioId     = $id;
            $this->selectedMunicipioNombre = $this->muniNames[$id] ?? 'Desconocido';
        }

        $this->loadData();
        $this->dispatch('refreshDashboardCharts');
        $this->dispatch('mapSelectionChanged', id: $this->selectedMunicipioId);
    }

    public function loadData(): void
    {
        $year                = $this->year;
        $selectedMunicipioId = $this->selectedMunicipioId;

        // ── Sectores válidos para el filtro de municipio ─────────────
        $validSectors = null;
        if ($selectedMunicipioId) {
            $validSectors = DB::table('sectores')
                ->join('comunas', 'sectores.comuna_id', '=', 'comunas.id')
                ->join('parroquias', 'comunas.parroquia_id', '=', 'parroquias.id')
                ->where('parroquias.municipio_id', $selectedMunicipioId)
                ->pluck('sectores.id');
        }

        $actividades = [
            'Abordaje'       => ['table' => 'abordajes',             'color' => '#84cc16', 'sum_col' => 'cantidad'],
            'Feria de Campo'  => ['table' => 'feria_campos',          'color' => '#6366f1', 'sum_col' => '(COALESCE(tipo_a,0)+COALESCE(tipo_b,0)+COALESCE(tipo_a_plus,0))'],
            'Vulnerabilidad'  => ['table' => 'plan_vulnerabilidads',  'color' => '#f43f5e', 'sum_col' => 'total_entregas'],
            'Lactancia'      => ['table' => 'circulo_lactancias',     'color' => '#14b8a6', 'sum_col' => 'cantidad'],
            'Escuela 4S'     => ['table' => 'escuela4s',              'color' => '#f59e0b', 'sum_col' => '1'],
            'Liderazgo'      => ['table' => 'liderazgo_territorials', 'color' => '#0ea5e9', 'sum_col' => 'cantidad'],
            'Div. Dietaria'  => ['table' => 'diversidad_dietarias',   'color' => '#d946ef', 'sum_col' => 'cantidad'],
            'Transcripción'  => ['table' => 'transcripciones',        'color' => '#8b5cf6', 'sum_col' => 'cantidad'],
        ];

        $distribution   = [];
        $monthly        = array_fill(1, 12, 0);
        $totalAtendidos = 0;
        $totalRegistros = 0;
        $queries        = [];
        $sectorQueries  = [];

        foreach ($actividades as $nombre => $cfg) {
            $table  = $cfg['table'];
            $sumCol = $cfg['sum_col'];

            $base = DB::table($table)->whereYear('fecha', $year);
            if ($validSectors !== null) {
                $base->whereIn('sector_id', $validSectors);
            }

            // 1. Registros totales
            $registrosCount  = (clone $base)->count();
            $totalRegistros += $registrosCount;

            // 2. Distribución por actividad
            $totalActividad = (clone $base)->selectRaw("SUM($sumCol) as total")->value('total') ?? 0;

            if ($registrosCount > 0) {
                $distribution[] = [
                    'nombre' => $nombre,
                    'total'  => (int) $totalActividad,
                    'color'  => $cfg['color'],
                ];
                $totalAtendidos += $totalActividad;
            }

            // 3. Evolución mensual
            $monthlyData = (clone $base)
                ->selectRaw("MONTH(fecha) as mes, SUM($sumCol) as total")
                ->groupBy('mes')
                ->get();
            foreach ($monthlyData as $row) {
                $monthly[$row->mes] += $row->total;
            }

            // 4. Feed reciente (sin filtro de municipio para no perder contexto)
            $subtipoRaw = ($table === 'transcripciones') ? 'tipo' : 'NULL';
            $queries[]  = DB::table($table)
                ->selectRaw("'$nombre' as tipo, $subtipoRaw as subtipo, fecha, created_at, '{$cfg['color']}' as color")
                ->whereYear('fecha', $year);

            // 5. Sectores (global, para municipios abordados)
            $sectorQueries[] = DB::table($table)->select('sector_id')->whereYear('fecha', $year);
        }

        usort($distribution, fn ($a, $b) => $b['total'] <=> $a['total']);
        $this->graphDistribution = $distribution;
        $this->totalAtendidos    = $totalAtendidos;
        $this->totalRegistros    = $totalRegistros;

        $diasTranscurridos    = ($year == date('Y')) ? (date('z') + 1) : 365;
        $this->promedioDiario = $diasTranscurridos > 0 ? round($totalAtendidos / $diasTranscurridos, 1) : 0;

        // ── Meta de Transcripciones Anuales ──────────────────────────
        $metaModel = \App\Models\Meta::where('ano', $year)->first();
        $this->metaTrans = 0;
        if ($metaModel) {
            if ($selectedMunicipioId) {
                $detalle = $metaModel->detalles()->where('municipio_id', $selectedMunicipioId)->first();
                $this->metaTrans = $detalle ? $detalle->meta_anual : 0;
            } else {
                $this->metaTrans = $metaModel->total;
            }
        }

        $realTransBase = DB::table('transcripciones')->whereYear('fecha', $year);
        if ($selectedMunicipioId && $validSectors !== null) {
            $realTransBase->whereIn('sector_id', $validSectors);
        }
        $this->realTrans = (int) ($realTransBase->sum('cantidad') ?? 0);

        // ── Municipios abordados (siempre global) ───────────────────
        $this->municipiosAbordados = 0;
        if (count($sectorQueries) > 0) {
            $unionS = array_shift($sectorQueries);
            foreach ($sectorQueries as $q) {
                $unionS->union($q);
            }
            $this->municipiosAbordados = DB::table('sectores')
                ->joinSub($unionS, 'activos', 'sectores.id', '=', 'activos.sector_id')
                ->join('comunas', 'sectores.comuna_id', '=', 'comunas.id')
                ->join('parroquias', 'comunas.parroquia_id', '=', 'parroquias.id')
                ->distinct('parroquias.municipio_id')
                ->count('parroquias.municipio_id');
        }

        // ── Gráfica mensual ─────────────────────────────────────────
        $this->graphMonthly = [];
        $meses = ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'];
        for ($i = 1; $i <= 12; $i++) {
            $this->graphMonthly[] = ['mes' => $meses[$i - 1], 'total' => (int) $monthly[$i]];
        }

        // ── Feed reciente ───────────────────────────────────────────
        if (count($queries) > 0) {
            $unionQ = array_shift($queries);
            foreach ($queries as $q) {
                $unionQ->unionAll($q);
            }
            $this->recentActivities = DB::query()
                ->fromSub($unionQ, 'unioned')
                ->orderByDesc('created_at')
                ->limit(6)
                ->get()
                ->toArray();
        }

        // ── Estadísticas por municipio (siempre globales – alimentan el mapa) ──
        $muniQueries = [];
        foreach ($actividades as $nombre => $cfg) {
            $table  = $cfg['table'];
            $sumCol = $cfg['sum_col'];
            $muniQueries[] = DB::table($table)
                ->join('sectores',   "$table.sector_id",        '=', 'sectores.id')
                ->join('comunas',    'sectores.comuna_id',       '=', 'comunas.id')
                ->join('parroquias', 'comunas.parroquia_id',     '=', 'parroquias.id')
                ->whereYear("$table.fecha", $year)
                ->selectRaw("parroquias.municipio_id, SUM($sumCol) as total, COUNT($table.id) as registros")
                ->groupBy('parroquias.municipio_id');
        }

        $rawStats = [];
        if (count($muniQueries) > 0) {
            $uMuni = array_shift($muniQueries);
            foreach ($muniQueries as $q) {
                $uMuni->unionAll($q);
            }
            $rawStats = DB::query()
                ->fromSub($uMuni, 'unioned')
                ->selectRaw('municipio_id, SUM(total) as total_atendidos, SUM(registros) as total_registros')
                ->groupBy('municipio_id')
                ->get()
                ->keyBy('municipio_id')
                ->toArray();
        }

        $totalGeneral    = max(1, array_sum(array_column($rawStats, 'total_atendidos')));
        $municipiosStats = [];
        for ($id = 1; $id <= 9; $id++) {
            $stat            = $rawStats[$id] ?? null;
            $municipiosStats[] = [
                'id'              => $id,
                'nombre'          => $this->muniNames[$id],
                'color'           => $this->muniColors[$id],
                'total_atendidos' => $stat ? (int) $stat->total_atendidos : 0,
                'total_registros' => $stat ? (int) $stat->total_registros : 0,
                'porcentaje'      => $stat ? round(($stat->total_atendidos / $totalGeneral) * 100, 1) : 0,
            ];
        }
        usort($municipiosStats, fn ($a, $b) => $b['total_atendidos'] <=> $a['total_atendidos']);
        $this->municipiosStats = $municipiosStats;
    }

    public function render()
    {
        return view('livewire.dashboard.dashboard-index')->title('Dashboard Principal');
    }
}

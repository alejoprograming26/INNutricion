<?php

namespace App\Livewire\Actividades;

use App\Models\PlanVulnerabilidad;
use App\Models\Comuna;
use App\Models\Municipio;
use App\Models\Parroquia;
use App\Models\Sector;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Ajuste;
use Barryvdh\DomPDF\Facade\Pdf;
use Livewire\Attributes\Url;

#[Layout('components.layouts.app')]
class PlanVulnerabilidadController extends Component
{
    use WithPagination;

    // ── Búsqueda y Filtros ────────────────────────────────────────────────────
    public string $search = '';
    public string $dateFrom = '';
    public string $dateTo = '';
    public string $sortDirection = 'desc';

    // ── Campos del formulario ─────────────────────────────────────────────────
    public ?int    $plan_id         = null;
    public ?string $observacion     = null;
    public ?string $responsable     = null;
    public string  $fecha           = '';
    public string  $municipio_id    = '';
    public string  $parroquia_id    = '';
    public string  $comuna_id       = '';
    public string  $sector_id       = '';
    public string  $total_entregas  = '';
    public array   $tipo            = [];

    // ── Filtros en cascada ────────────────────────────────────────────────────
    public $parroquiasFiltradas = [];
    public $comunasFiltradas    = [];
    public $sectoresFiltrados   = [];

    // ── Modales ───────────────────────────────────────────────────────────────
    public bool $isModalOpen     = false;
    public bool $isViewModalOpen = false;
    public bool $isReportModalOpen = false;
    public ?string $reportMonth = null;
    public ?string $reportYear = null;
    public ?int $reportMunicipioId = null;
    public ?string $reportMunicipioNombre = null;

    // ── Gráficas ──────────────────────────────────────────────────────────────
    public bool    $isGraphView      = false;
    public bool    $isGraphModalOpen = false;
    
    #[Url(as: 'mes')]
    public ?string $graphMonth       = null;
    
    #[Url(as: 'año')]
    public ?string $graphAno         = null;
    public ?int    $graphMunicipioId = null;
    public ?string $graphMunicipioNombre = null;
    public array   $graphKpis        = [];
    public array   $graphParroquias  = [];
    public array   $graphComunas     = [];
    public array   $graphSectores    = [];
    public array   $graphDias        = [];
    public array   $graphTipos       = [];
    public string  $colorHex         = '#f43f5e';
    public string  $colorTw          = 'rose';

    // ── Datos del modal "Ver" ─────────────────────────────────────────────────
    public ?string $view_observacion    = null;
    public ?string $view_responsable    = null;
    public string  $view_fecha          = '';
    public string  $view_municipio      = '';
    public string  $view_parroquia      = '';
    public string  $view_comuna         = '';
    public string  $view_sector         = '';
    public string  $view_total_entregas = '';
    public array   $view_tipo           = [];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatedDateFrom(): void
    {
        $this->resetPage();
    }

    public function updatedDateTo(): void
    {
        $this->resetPage();
    }

    public function toggleSort(): void
    {
        $this->sortDirection = $this->sortDirection === 'desc' ? 'asc' : 'desc';
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->reset(['search', 'dateFrom', 'dateTo']);
        $this->resetPage();
    }

    // ── Cascada de selects ────────────────────────────────────────────────────

    public function updatedMunicipioId($value): void
    {
        $this->parroquia_id = '';
        $this->comuna_id    = '';
        $this->sector_id    = '';
        $this->comunasFiltradas   = [];
        $this->sectoresFiltrados  = [];

        $this->parroquiasFiltradas = $value
            ? Parroquia::where('municipio_id', $value)->orderBy('nombre')->get()
            : [];
    }

    public function updatedParroquiaId($value): void
    {
        $this->comuna_id  = '';
        $this->sector_id  = '';
        $this->sectoresFiltrados = [];

        $this->comunasFiltradas = $value
            ? Comuna::where('parroquia_id', $value)->orderBy('nombre')->get()
            : [];
    }

    public function updatedComunaId($value): void
    {
        $this->sector_id = '';

        $this->sectoresFiltrados = $value
            ? Sector::where('comuna_id', $value)->orderBy('nombre')->get()
            : [];
    }

    // ── CRUD ──────────────────────────────────────────────────────────────────

    public function create(): void
    {
        $this->resetInputFields();
        $this->fecha = now()->format('Y-m-d');
        $this->isModalOpen = true;
    }

    public function store(): void
    {
        $this->validate([
            'observacion'     => 'nullable|string|max:255',
            'responsable'     => 'required|string|max:255',
            'fecha'           => 'required|date',
            'sector_id'       => 'required|exists:sectores,id',
            'total_entregas'  => 'required|integer|min:1',
            'tipo'            => 'required|array|min:1',
            'tipo.*'          => 'in:Suplemento,Proteina,Fruvet',
        ], [
            'responsable.required'    => 'El responsable es obligatorio.',
            'fecha.required'          => 'La fecha es obligatoria.',
            'sector_id.required'      => 'Selecciona un sector.',
            'total_entregas.required' => 'El total de entregas es obligatorio.',
            'tipo.required'           => 'Selecciona al menos un tipo.',
            'tipo.min'                => 'Selecciona al menos un tipo.',
        ]);

        $data = [
            'observacion'    => $this->observacion ? mb_strtoupper(trim($this->observacion), 'UTF-8') : null,
            'responsable'    => mb_strtoupper(trim($this->responsable), 'UTF-8'),
            'fecha'          => $this->fecha,
            'sector_id'      => $this->sector_id,
            'total_entregas' => (int) $this->total_entregas,
            'tipo'           => $this->tipo,
        ];

        if ($this->plan_id) {
            PlanVulnerabilidad::findOrFail($this->plan_id)->update($data);
            $this->dispatch('swal', ['icon' => 'success', 'title' => 'Registro actualizado exitosamente.']);
        } else {
            PlanVulnerabilidad::create($data);
            $this->dispatch('swal', ['icon' => 'success', 'title' => 'Registro creado exitosamente.']);
        }

        $this->closeModal();
    }

    public function edit(int $id): void
    {
        $this->resetInputFields();
        $p = PlanVulnerabilidad::with(['sector.comuna.parroquia.municipio'])->findOrFail($id);

        $this->plan_id         = $p->id;
        $this->observacion     = $p->observacion;
        $this->responsable     = $p->responsable;
        $this->fecha           = Carbon::parse($p->fecha)->format('Y-m-d');

        $this->sector_id       = (string) $p->sector_id;
        $this->comuna_id       = (string) $p->sector->comuna_id;
        $this->parroquia_id    = (string) $p->sector->comuna->parroquia_id;
        $this->municipio_id    = (string) $p->sector->comuna->parroquia->municipio_id;

        $this->total_entregas  = (string) $p->total_entregas;
        $this->tipo            = $p->tipo ?? [];

        // Cargar combos en cascada
        $this->parroquiasFiltradas = Parroquia::where('municipio_id', $this->municipio_id)->orderBy('nombre')->get();
        $this->comunasFiltradas    = Comuna::where('parroquia_id', $this->parroquia_id)->orderBy('nombre')->get();
        $this->sectoresFiltrados   = Sector::where('comuna_id', $this->comuna_id)->orderBy('nombre')->get();

        $this->isModalOpen = true;
    }

    public function show(int $id): void
    {
        $p = PlanVulnerabilidad::with(['sector.comuna.parroquia.municipio'])->findOrFail($id);

        $this->view_observacion    = $p->observacion;
        $this->view_responsable    = $p->responsable;
        $this->view_fecha          = Carbon::parse($p->fecha)->format('d/m/Y');
        $this->view_municipio      = $p->sector->comuna->parroquia->municipio->nombre;
        $this->view_parroquia      = $p->sector->comuna->parroquia->nombre;
        $this->view_comuna         = $p->sector->comuna->nombre;
        $this->view_sector         = $p->sector->nombre;
        $this->view_total_entregas = (string) $p->total_entregas;
        $this->view_tipo           = $p->tipo ?? [];

        $this->isViewModalOpen = true;
    }

    public function delete(int $id): void
    {
        PlanVulnerabilidad::findOrFail($id)->delete();
        $this->dispatch('swal', ['icon' => 'success', 'title' => 'Registro eliminado correctamente.']);
    }

    public function closeModal(): void
    {
        $this->isModalOpen     = false;
        $this->isViewModalOpen = false;
        $this->resetInputFields();
    }

    private function resetInputFields(): void
    {
        $this->plan_id            = null;
        $this->observacion        = null;
        $this->responsable        = null;
        $this->fecha              = '';
        $this->municipio_id       = '';
        $this->parroquia_id       = '';
        $this->comuna_id          = '';
        $this->sector_id          = '';
        $this->total_entregas     = '';
        $this->tipo               = [];
        $this->parroquiasFiltradas = [];
        $this->comunasFiltradas    = [];
        $this->sectoresFiltrados   = [];
        $this->resetValidation();
    }

    // ── Modal de Reporte ──────────────────────────────────────────────────────

    public function openReportModal(?int $municipioId = null): void
    {
        if ($municipioId) {
            $mun = Municipio::find($municipioId);
            $this->reportMunicipioId = $municipioId;
            $this->reportMunicipioNombre = $mun ? $mun->nombre : '';
        } else {
            $this->reportMunicipioId = null;
            $this->reportMunicipioNombre = null;
        }

        $this->reportMonth = (string) now()->month;
        $this->reportYear  = (string) now()->year;
        $this->isReportModalOpen = true;
    }

    public function closeReportModal(): void
    {
        $this->isReportModalOpen = false;
    }

    public function viewPdf()
    {
        $url = route('admin.actividades.vulnerabilidad.pdf', [
            'mes' => $this->reportMonth,
            'año' => $this->reportYear,
            'municipio_id' => $this->reportMunicipioId
        ]);

        $this->dispatch('open-url-in-new-tab', url: $url);
        $this->closeReportModal();
    }

    // ── Modal de Gráficas ─────────────────────────────────────────────────────

    public function openGraphModal(?int $municipioId = null): void
    {
        if ($municipioId) {
            $mun = Municipio::find($municipioId);
            $this->graphMunicipioId     = $municipioId;
            $this->graphMunicipioNombre = $mun ? $mun->nombre : '';
        } else {
            $this->graphMunicipioId     = null;
            $this->graphMunicipioNombre = null;
        }
        $this->graphMonth       = (string) now()->month;
        $this->graphAno         = (string) now()->year;
        $this->isGraphModalOpen = true;
    }

    public function closeGraphModal(): void
    {
        $this->isGraphModalOpen = false;
    }

    public function viewGraphs(): void
    {
        $url = route('admin.actividades.vulnerabilidad.graficos', [
            'mes'          => $this->graphMonth,
            'año'          => $this->graphAno,
            'municipio_id' => $this->graphMunicipioId,
        ]);

        $this->redirect($url, navigate: true);
    }

    public function cargarDatosGraficos(): void
    {
        $mes   = (int) ($this->graphMonth ?? now()->month);
        $año   = (int) ($this->graphAno ?? now()->year);
        $munId = $this->graphMunicipioId;

        $queryBase = PlanVulnerabilidad::query()
            ->join('sectores',  'plan_vulnerabilidads.sector_id', '=', 'sectores.id')
            ->join('comunas',   'sectores.comuna_id',             '=', 'comunas.id')
            ->join('parroquias','comunas.parroquia_id',           '=', 'parroquias.id')
            ->whereYear('plan_vulnerabilidads.fecha', $año)
            ->whereMonth('plan_vulnerabilidads.fecha', $mes)
            ->when($munId, fn($q) => $q->where('parroquias.municipio_id', $munId));

        $totales = (clone $queryBase)->selectRaw('
            COUNT(plan_vulnerabilidads.id) as total_registros,
            COALESCE(SUM(plan_vulnerabilidads.total_entregas), 0) as total_cantidad
        ')->first();

        // Distribución por tipos (Suplemento, Proteina, Fruvet)
        // Como 'tipo' es un array casteado, tenemos que contarlo manualmente
        $allRecords = (clone $queryBase)->select('tipo')->get();
        $tiposCounts = ['Suplemento' => 0, 'Proteina' => 0, 'Fruvet' => 0];
        foreach ($allRecords as $rec) {
            if (is_array($rec->tipo)) {
                foreach ($rec->tipo as $t) {
                    if (isset($tiposCounts[$t])) $tiposCounts[$t]++;
                }
            }
        }

        $this->graphKpis = [
            'total_registros' => $totales->total_registros,
            'total_cantidad'  => $totales->total_cantidad,
            'promedio_diario' => $totales->total_cantidad > 0
                ? round($totales->total_cantidad / max(1, now()->daysInMonth), 1)
                : 0,
            'tipos'           => [
                'Suplemento' => $tiposCounts['Suplemento'],
                'Proteína'   => $tiposCounts['Proteina'],
                'Fruvet'     => $tiposCounts['Fruvet'],
            ],
        ];

        $this->graphParroquias = (clone $queryBase)
            ->select('parroquias.nombre', DB::raw('SUM(plan_vulnerabilidads.total_entregas) as total'))
            ->groupBy('parroquias.id', 'parroquias.nombre')
            ->orderByDesc('total')->get()->toArray();

        $this->graphComunas = (clone $queryBase)
            ->select('comunas.nombre', DB::raw('SUM(plan_vulnerabilidads.total_entregas) as total'))
            ->groupBy('comunas.id', 'comunas.nombre')
            ->orderByDesc('total')->get()->toArray();

        $this->graphSectores = (clone $queryBase)
            ->select('sectores.nombre', DB::raw('SUM(plan_vulnerabilidads.total_entregas) as total'))
            ->groupBy('sectores.id', 'sectores.nombre')
            ->orderByDesc('total')->get()->toArray();

        $this->graphDias = (clone $queryBase)
            ->select(DB::raw('DAY(plan_vulnerabilidads.fecha) as dia'), DB::raw('SUM(plan_vulnerabilidads.total_entregas) as total'))
            ->groupBy(DB::raw('DAY(plan_vulnerabilidads.fecha)'))
            ->orderBy('dia')->get()->toArray();

        $this->graphTipos = [
            ['nombre' => 'Suplemento', 'total' => $tiposCounts['Suplemento']],
            ['nombre' => 'Proteína',   'total' => $tiposCounts['Proteina']],
            ['nombre' => 'Fruvet',     'total' => $tiposCounts['Fruvet']],
        ];
    }

    // ── Render ────────────────────────────────────────────────────────────────

    public function mount(): void
    {
        if (request()->routeIs('admin.actividades.vulnerabilidad.graficos')) {
            $this->isGraphView = true;
            
            $this->graphMonth = (string) request()->query('mes', $this->graphMonth ?? now()->month);
            $this->graphAno   = (string) request()->query('año', $this->graphAno   ?? now()->year);
            $this->graphMunicipioId = request()->query('municipio_id', $this->graphMunicipioId);

            $this->graphMunicipioNombre = $this->graphMunicipioId
                ? (Municipio::find($this->graphMunicipioId)?->nombre ?? 'Todos los Municipios')
                : 'Todos los Municipios';

            $this->cargarDatosGraficos();
        }
    }

    public function updatedGraphMonth(): void { $this->cargarDatosGraficos(); $this->dispatch('refreshCharts'); }
    public function updatedGraphAno(): void   { $this->cargarDatosGraficos(); $this->dispatch('refreshCharts'); }

    public function render()
    {
        $now = now();

        $metrics = Cache::rememberForever('plan_vulnerabilidad_metrics', function () use ($now) {
            $totalAnual  = PlanVulnerabilidad::whereYear('fecha', $now->year)->sum('total_entregas');
            $totalMes    = PlanVulnerabilidad::whereYear('fecha', $now->year)->whereMonth('fecha', $now->month)->sum('total_entregas');

            $startOfWeek = $now->copy()->startOfWeek();
            $endOfWeek   = $now->copy()->endOfWeek();
            $totalSemana = PlanVulnerabilidad::whereBetween('fecha', [$startOfWeek, $endOfWeek])->sum('total_entregas');

            $registrosMes = PlanVulnerabilidad::whereYear('fecha', $now->year)->whereMonth('fecha', $now->month)->count();

            $municipiosConTotales = Municipio::query()
                ->select('municipios.*')
                ->addSelect([
                    'total_anual' => DB::table('plan_vulnerabilidads')
                        ->join('sectores', 'plan_vulnerabilidads.sector_id', '=', 'sectores.id')
                        ->join('comunas', 'sectores.comuna_id', '=', 'comunas.id')
                        ->join('parroquias', 'comunas.parroquia_id', '=', 'parroquias.id')
                        ->whereColumn('parroquias.municipio_id', 'municipios.id')
                        ->whereYear('plan_vulnerabilidads.fecha', $now->year)
                        ->selectRaw('COALESCE(SUM(total_entregas), 0)'),

                    'total_mes' => DB::table('plan_vulnerabilidads')
                        ->join('sectores', 'plan_vulnerabilidads.sector_id', '=', 'sectores.id')
                        ->join('comunas', 'sectores.comuna_id', '=', 'comunas.id')
                        ->join('parroquias', 'comunas.parroquia_id', '=', 'parroquias.id')
                        ->whereColumn('parroquias.municipio_id', 'municipios.id')
                        ->whereYear('plan_vulnerabilidads.fecha', $now->year)
                        ->whereMonth('plan_vulnerabilidads.fecha', $now->month)
                        ->selectRaw('COALESCE(SUM(total_entregas), 0)'),

                    'total_semana' => DB::table('plan_vulnerabilidads')
                        ->join('sectores', 'plan_vulnerabilidads.sector_id', '=', 'sectores.id')
                        ->join('comunas', 'sectores.comuna_id', '=', 'comunas.id')
                        ->join('parroquias', 'comunas.parroquia_id', '=', 'parroquias.id')
                        ->whereColumn('parroquias.municipio_id', 'municipios.id')
                        ->whereBetween('plan_vulnerabilidads.fecha', [$startOfWeek, $endOfWeek])
                        ->selectRaw('COALESCE(SUM(total_entregas), 0)'),

                    'abordajes_mes_count' => DB::table('plan_vulnerabilidads')
                        ->join('sectores', 'plan_vulnerabilidads.sector_id', '=', 'sectores.id')
                        ->join('comunas', 'sectores.comuna_id', '=', 'comunas.id')
                        ->join('parroquias', 'comunas.parroquia_id', '=', 'parroquias.id')
                        ->whereColumn('parroquias.municipio_id', 'municipios.id')
                        ->whereYear('plan_vulnerabilidads.fecha', $now->year)
                        ->whereMonth('plan_vulnerabilidads.fecha', $now->month)
                        ->selectRaw('COUNT(*)')
                ])
                ->orderBy('nombre')
                ->get();

            return [
                'totalAnual'           => $totalAnual,
                'totalMes'             => $totalMes,
                'totalSemana'          => $totalSemana,
                'registrosMes'         => $registrosMes,
                'municipiosConTotales' => $municipiosConTotales,
            ];
        });

        $registros = PlanVulnerabilidad::query()
            ->select('plan_vulnerabilidads.*')
            ->join('sectores',   'plan_vulnerabilidads.sector_id',    '=', 'sectores.id')
            ->join('comunas',     'sectores.comuna_id',     '=', 'comunas.id')
            ->join('parroquias',  'comunas.parroquia_id',   '=', 'parroquias.id')
            ->join('municipios',  'parroquias.municipio_id', '=', 'municipios.id')
            ->when($this->search, function ($q) {
                $term = '%' . $this->search . '%';
                $q->where(function ($q1) use ($term) {
                    $q1->where('plan_vulnerabilidads.responsable', 'like', $term)
                       ->orWhere('plan_vulnerabilidads.observacion', 'like', $term)
                       ->orWhere('municipios.nombre',   'like', $term)
                       ->orWhere('parroquias.nombre',   'like', $term)
                       ->orWhere('comunas.nombre',      'like', $term)
                       ->orWhere('sectores.nombre',     'like', $term);
                });
            })
            ->when($this->dateFrom, fn($q) => $q->whereDate('plan_vulnerabilidads.fecha', '>=', $this->dateFrom))
            ->when($this->dateTo,   fn($q) => $q->whereDate('plan_vulnerabilidads.fecha', '<=', $this->dateTo))
            ->with(['sector.comuna.parroquia.municipio'])
            ->orderBy('plan_vulnerabilidads.fecha', $this->sortDirection)
            ->paginate(10);

        if ($this->isGraphView) {
            $mesesNombres = [
                1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
                5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
                9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
            ];
            return view('livewire.actividades.plan_vulnerabilidad.graficos-index', [
                'nombreMes'  => $mesesNombres[(int)$this->graphMonth] ?? 'Desconocido',
                'ano'        => $this->graphAno,
                'municipios' => Municipio::orderBy('nombre')->get(),
            ]);
        }

        return view('livewire.actividades.plan_vulnerabilidad.plan_vulnerabilidad-index', [
            'registros'            => $registros,
            'municipios'           => Municipio::orderBy('nombre')->get(),
            'municipiosConTotales' => $metrics['municipiosConTotales'],
            'totalAnual'           => $metrics['totalAnual'],
            'totalMes'             => $metrics['totalMes'],
            'totalSemana'          => $metrics['totalSemana'],
            'registrosMes'         => $metrics['registrosMes'],
        ]);
    }
}

<?php

namespace App\Livewire\Actividades;

use App\Models\LiderazgoTerritorial;
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

#[Layout('components.layouts.app')]
class LiderazgoTerritorialController extends Component
{
    use WithPagination;

    // ── Búsqueda y Filtros ────────────────────────────────────────────────────
    public string $search = '';
    public string $dateFrom = '';
    public string $dateTo = '';
    public string $sortDirection = 'desc';

    // ── Campos del formulario ─────────────────────────────────────────────────
    public ?int    $liderazgo_id = null;
    public ?string $observacion  = null;
    public ?string $responsable  = null;
    public string  $fecha        = '';
    public string  $municipio_id = '';
    public string  $parroquia_id = '';
    public string  $comuna_id    = '';
    public string  $sector_id    = '';
    public string  $cantidad     = '';
    public string  $tema_tratado = '';

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
    public array   $graphTemas       = [];
    public string  $colorHex         = '#0ea5e9';
    public string  $colorTw          = 'sky';

    // ── Datos del modal "Ver" ─────────────────────────────────────────────────
    public ?string $view_observacion = null;
    public ?string $view_responsable  = null;
    public string  $view_fecha       = '';
    public string  $view_municipio   = '';
    public string  $view_parroquia   = '';
    public string  $view_comuna      = '';
    public string  $view_sector      = '';
    public string  $view_cantidad    = '';
    public string  $view_tema_tratado = '';

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
            'observacion'  => 'nullable|string|max:255',
            'responsable'  => 'required|string|max:255',
            'fecha'        => 'required|date',
            'sector_id'    => 'required|exists:sectores,id',
            'cantidad'     => 'required|integer|min:1',
            'tema_tratado' => 'required|string|max:255',
        ], [
            'responsable.required' => 'El responsable es obligatorio.',
            'fecha.required'        => 'La fecha es obligatoria.',
            'sector_id.required'    => 'Selecciona un sector.',
            'cantidad.required'     => 'La cantidad es obligatoria.',
            'tema_tratado.required' => 'El tema tratado es obligatorio.',
        ]);

        $data = [
            'observacion'  => $this->observacion ? mb_strtoupper(trim($this->observacion), 'UTF-8') : null,
            'responsable'  => mb_strtoupper(trim($this->responsable), 'UTF-8'),
            'fecha'        => $this->fecha,
            'sector_id'    => $this->sector_id,
            'cantidad'     => (int) $this->cantidad,
            'tema_tratado' => mb_strtoupper(trim($this->tema_tratado), 'UTF-8'),
        ];

        if ($this->liderazgo_id) {
            LiderazgoTerritorial::findOrFail($this->liderazgo_id)->update($data);
            $this->dispatch('swal', ['icon' => 'success', 'title' => 'Registro actualizado exitosamente.']);
        } else {
            LiderazgoTerritorial::create($data);
            $this->dispatch('swal', ['icon' => 'success', 'title' => 'Registro creado exitosamente.']);
        }

        $this->closeModal();
    }

    public function edit(int $id): void
    {
        $this->resetInputFields();
        $l = LiderazgoTerritorial::with(['sector.comuna.parroquia.municipio'])->findOrFail($id);

        $this->liderazgo_id = $l->id;
        $this->observacion  = $l->observacion;
        $this->responsable  = $l->responsable;
        $this->fecha        = Carbon::parse($l->fecha)->format('Y-m-d');
        
        $this->sector_id    = (string) $l->sector_id;
        $this->comuna_id    = (string) $l->sector->comuna_id;
        $this->parroquia_id = (string) $l->sector->comuna->parroquia_id;
        $this->municipio_id = (string) $l->sector->comuna->parroquia->municipio_id;
        
        $this->cantidad     = (string) $l->cantidad;
        $this->tema_tratado = $l->tema_tratado;

        // Cargar combos en cascada
        $this->parroquiasFiltradas = Parroquia::where('municipio_id', $this->municipio_id)->orderBy('nombre')->get();
        $this->comunasFiltradas    = Comuna::where('parroquia_id', $this->parroquia_id)->orderBy('nombre')->get();
        $this->sectoresFiltrados   = Sector::where('comuna_id', $this->comuna_id)->orderBy('nombre')->get();

        $this->isModalOpen = true;
    }

    public function show(int $id): void
    {
        $l = LiderazgoTerritorial::with(['sector.comuna.parroquia.municipio'])->findOrFail($id);

        $this->view_observacion = $l->observacion;
        $this->view_responsable  = $l->responsable;
        $this->view_fecha       = Carbon::parse($l->fecha)->format('d/m/Y');
        $this->view_municipio   = $l->sector->comuna->parroquia->municipio->nombre;
        $this->view_parroquia   = $l->sector->comuna->parroquia->nombre;
        $this->view_comuna      = $l->sector->comuna->nombre;
        $this->view_sector      = $l->sector->nombre;
        $this->view_cantidad    = (string) $l->cantidad;
        $this->view_tema_tratado = $l->tema_tratado;

        $this->isViewModalOpen = true;
    }

    public function delete(int $id): void
    {
        LiderazgoTerritorial::findOrFail($id)->delete();
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
        $this->liderazgo_id       = null;
        $this->observacion        = null;
        $this->responsable        = null;
        $this->fecha              = '';
        $this->municipio_id       = '';
        $this->parroquia_id       = '';
        $this->comuna_id          = '';
        $this->sector_id          = '';
        $this->cantidad           = '';
        $this->tema_tratado       = '';
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
        $url = route('admin.actividades.liderazgo.pdf', [
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
        $url = route('admin.actividades.liderazgo.graficos', [
            'mes'          => $this->graphMonth,
            'año'          => $this->graphAno,
            'municipio_id' => $this->graphMunicipioId,
        ]);

        $this->redirect($url, navigate: true);
    }

    public function cargarDatosGraficos(): void
    {
        $mes   = (int) ($this->graphMonth ?? now()->month);
        $año   = (int) ($this->graphAno   ?? now()->year);
        $munId = $this->graphMunicipioId;

        $queryBase = LiderazgoTerritorial::query()
            ->join('sectores',  'liderazgo_territorials.sector_id', '=', 'sectores.id')
            ->join('comunas',   'sectores.comuna_id',               '=', 'comunas.id')
            ->join('parroquias','comunas.parroquia_id',             '=', 'parroquias.id')
            ->whereYear('liderazgo_territorials.fecha', $año)
            ->whereMonth('liderazgo_territorials.fecha', $mes)
            ->when($munId, fn($q) => $q->where('parroquias.municipio_id', $munId));

        $totales = (clone $queryBase)->selectRaw('
            COUNT(liderazgo_territorials.id) as total_registros,
            COALESCE(SUM(liderazgo_territorials.cantidad), 0) as total_cantidad
        ')->first();

        $this->graphKpis = [
            'total_registros' => $totales->total_registros,
            'total_cantidad'  => $totales->total_cantidad,
            'promedio_diario' => $totales->total_cantidad > 0
                ? round($totales->total_cantidad / max(1, now()->daysInMonth), 1)
                : 0,
        ];

        $this->graphParroquias = (clone $queryBase)
            ->select('parroquias.nombre', DB::raw('SUM(liderazgo_territorials.cantidad) as total'))
            ->groupBy('parroquias.id', 'parroquias.nombre')
            ->orderByDesc('total')->get()->toArray();

        $this->graphComunas = (clone $queryBase)
            ->select('comunas.nombre', DB::raw('SUM(liderazgo_territorials.cantidad) as total'))
            ->groupBy('comunas.id', 'comunas.nombre')
            ->orderByDesc('total')->get()->toArray();

        $this->graphSectores = (clone $queryBase)
            ->select('sectores.nombre', DB::raw('SUM(liderazgo_territorials.cantidad) as total'))
            ->groupBy('sectores.id', 'sectores.nombre')
            ->orderByDesc('total')->get()->toArray();

        $this->graphDias = (clone $queryBase)
            ->select(DB::raw('DAY(liderazgo_territorials.fecha) as dia'), DB::raw('SUM(liderazgo_territorials.cantidad) as total'))
            ->groupBy(DB::raw('DAY(liderazgo_territorials.fecha)'))
            ->orderBy('dia')->get()->toArray();

        $this->graphTemas = (clone $queryBase)
            ->select('tema_tratado as nombre', DB::raw('SUM(cantidad) as total'))
            ->groupBy('tema_tratado')
            ->orderByDesc('total')->limit(10)->get()->toArray();
    }

    // ── Render ────────────────────────────────────────────────────────────────

    public function mount(): void
    {
        if (request()->routeIs('admin.actividades.liderazgo.graficos')) {
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

        $metrics = Cache::rememberForever('liderazgo_metrics', function () use ($now) {
            $totalAnual  = LiderazgoTerritorial::whereYear('fecha', $now->year)->sum('cantidad');
            $totalMes    = LiderazgoTerritorial::whereYear('fecha', $now->year)->whereMonth('fecha', $now->month)->sum('cantidad');

            $startOfWeek = $now->copy()->startOfWeek();
            $endOfWeek   = $now->copy()->endOfWeek();
            $totalSemana = LiderazgoTerritorial::whereBetween('fecha', [$startOfWeek, $endOfWeek])->sum('cantidad');

            $registrosMes = LiderazgoTerritorial::whereYear('fecha', $now->year)->whereMonth('fecha', $now->month)->count();

            $municipiosConTotales = Municipio::query()
                ->select('municipios.*')
                ->addSelect([
                    'total_anual' => DB::table('liderazgo_territorials')
                        ->join('sectores', 'liderazgo_territorials.sector_id', '=', 'sectores.id')
                        ->join('comunas', 'sectores.comuna_id', '=', 'comunas.id')
                        ->join('parroquias', 'comunas.parroquia_id', '=', 'parroquias.id')
                        ->whereColumn('parroquias.municipio_id', 'municipios.id')
                        ->whereYear('liderazgo_territorials.fecha', $now->year)
                        ->selectRaw('COALESCE(SUM(cantidad), 0)'),
                    
                    'total_mes' => DB::table('liderazgo_territorials')
                        ->join('sectores', 'liderazgo_territorials.sector_id', '=', 'sectores.id')
                        ->join('comunas', 'sectores.comuna_id', '=', 'comunas.id')
                        ->join('parroquias', 'comunas.parroquia_id', '=', 'parroquias.id')
                        ->whereColumn('parroquias.municipio_id', 'municipios.id')
                        ->whereYear('liderazgo_territorials.fecha', $now->year)
                        ->whereMonth('liderazgo_territorials.fecha', $now->month)
                        ->selectRaw('COALESCE(SUM(cantidad), 0)'),
                        
                    'total_semana' => DB::table('liderazgo_territorials')
                        ->join('sectores', 'liderazgo_territorials.sector_id', '=', 'sectores.id')
                        ->join('comunas', 'sectores.comuna_id', '=', 'comunas.id')
                        ->join('parroquias', 'comunas.parroquia_id', '=', 'parroquias.id')
                        ->whereColumn('parroquias.municipio_id', 'municipios.id')
                        ->whereBetween('liderazgo_territorials.fecha', [$startOfWeek, $endOfWeek])
                        ->selectRaw('COALESCE(SUM(cantidad), 0)'),

                    'abordajes_mes_count' => DB::table('liderazgo_territorials')
                        ->join('sectores', 'liderazgo_territorials.sector_id', '=', 'sectores.id')
                        ->join('comunas', 'sectores.comuna_id', '=', 'comunas.id')
                        ->join('parroquias', 'comunas.parroquia_id', '=', 'parroquias.id')
                        ->whereColumn('parroquias.municipio_id', 'municipios.id')
                        ->whereYear('liderazgo_territorials.fecha', $now->year)
                        ->whereMonth('liderazgo_territorials.fecha', $now->month)
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

        $registros = LiderazgoTerritorial::query()
            ->select('liderazgo_territorials.*')
            ->join('sectores',   'liderazgo_territorials.sector_id',    '=', 'sectores.id')
            ->join('comunas',     'sectores.comuna_id',     '=', 'comunas.id')
            ->join('parroquias',  'comunas.parroquia_id',   '=', 'parroquias.id')
            ->join('municipios',  'parroquias.municipio_id', '=', 'municipios.id')
            ->when($this->search, function ($q) {
                $term = '%' . $this->search . '%';
                $q->where(function ($q1) use ($term) {
                    $q1->where('liderazgo_territorials.observacion', 'like', $term)
                       ->orWhere('liderazgo_territorials.responsable', 'like', $term)
                       ->orWhere('liderazgo_territorials.tema_tratado', 'like', $term)
                       ->orWhere('municipios.nombre',   'like', $term)
                       ->orWhere('parroquias.nombre',   'like', $term)
                       ->orWhere('comunas.nombre',      'like', $term)
                       ->orWhere('sectores.nombre',     'like', $term);
                });
            })
            ->when($this->dateFrom, fn($q) => $q->whereDate('liderazgo_territorials.fecha', '>=', $this->dateFrom))
            ->when($this->dateTo,   fn($q) => $q->whereDate('liderazgo_territorials.fecha', '<=', $this->dateTo))
            ->with(['sector.comuna.parroquia.municipio'])
            ->orderBy('liderazgo_territorials.fecha', $this->sortDirection)
            ->paginate(10);

        if ($this->isGraphView) {
            $mesesNombres = [
                1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
                5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
                9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
            ];
            return view('livewire.actividades.liderazgo_territorial.graficos-index', [
                'nombreMes'  => $mesesNombres[(int)$this->graphMonth] ?? 'Desconocido',
                'ano'        => $this->graphAno,
                'municipios' => Municipio::orderBy('nombre')->get(),
            ]);
        }

        return view('livewire.actividades.liderazgo_territorial.liderazgo_territorial-index', [
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

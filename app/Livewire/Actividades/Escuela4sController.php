<?php

namespace App\Livewire\Actividades;

use App\Models\Escuela4s;
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

#[Layout('components.layouts.app')]
class Escuela4sController extends Component
{
    use WithPagination;

    // ── Búsqueda y Filtros ────────────────────────────────────────────────────
    public string $search = '';
    public string $dateFrom = '';
    public string $dateTo = '';
    public string $sortDirection = 'desc';

    // ── Campos del formulario ─────────────────────────────────────────────────
    public ?int    $escuela_id   = null;
    public ?string $observacion  = null;
    public ?string $responsable  = null;
    public string  $fecha        = '';
    public string  $nombre_escuela = '';
    public string  $municipio_id = '';
    public string  $parroquia_id = '';
    public string  $comuna_id    = '';
    public string  $sector_id    = '';
    public string  $director_a   = '';
    public string  $codigo_dea   = '';
    public string  $codigo_cnae  = '';
    public string  $tema_tratado = '';
    public string  $fase         = 'FASE 1';

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
    public array   $graphFases       = [];
    public string  $colorHex         = '#f59e0b';
    public string  $colorTw          = 'amber';

    // ── Datos del modal "Ver" ─────────────────────────────────────────────────
    public ?string $view_observacion = null;
    public ?string $view_responsable  = null;
    public string  $view_fecha       = '';
    public string  $view_nombre_escuela = '';
    public string  $view_municipio   = '';
    public string  $view_parroquia   = '';
    public string  $view_comuna      = '';
    public string  $view_sector      = '';
    public string  $view_director_a  = '';
    public string  $view_codigo_dea  = '';
    public string  $view_codigo_cnae = '';
    public string  $view_tema_tratado = '';
    public string  $view_fase        = '';

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
            'nombre_escuela' => 'required|string|max:255',
            'sector_id'    => 'required|exists:sectores,id',
            'director_a'   => 'required|string|max:255',
            'codigo_dea'   => 'required|string|max:255',
            'codigo_cnae'  => 'required|string|max:255',
            'tema_tratado' => 'required|string|max:255',
            'fase'         => 'required|in:FASE 1,FASE 2,FASE 3',
        ], [
            'responsable.required' => 'El responsable es obligatorio.',
            'fecha.required'        => 'La fecha es obligatoria.',
            'nombre_escuela.required' => 'El nombre de la escuela es obligatorio.',
            'sector_id.required'    => 'Selecciona un sector.',
            'director_a.required'   => 'El(la) director(a) es obligatorio.',
            'codigo_dea.required'   => 'El código DEA es obligatorio.',
            'codigo_cnae.required'  => 'El código CNAE es obligatorio.',
            'tema_tratado.required' => 'El tema tratado es obligatorio.',
            'fase.required'         => 'La fase es obligatoria.',
        ]);

        $data = [
            'observacion'  => $this->observacion ? mb_strtoupper(trim($this->observacion), 'UTF-8') : null,
            'responsable'  => mb_strtoupper(trim($this->responsable), 'UTF-8'),
            'fecha'        => $this->fecha,
            'nombre_escuela' => mb_strtoupper(trim($this->nombre_escuela), 'UTF-8'),
            'sector_id'    => $this->sector_id,
            'director_a'   => mb_strtoupper(trim($this->director_a), 'UTF-8'),
            'codigo_dea'   => mb_strtoupper(trim($this->codigo_dea), 'UTF-8'),
            'codigo_cnae'  => mb_strtoupper(trim($this->codigo_cnae), 'UTF-8'),
            'tema_tratado' => mb_strtoupper(trim($this->tema_tratado), 'UTF-8'),
            'fase'         => $this->fase,
        ];

        if ($this->escuela_id) {
            Escuela4s::findOrFail($this->escuela_id)->update($data);
            $this->dispatch('swal', ['icon' => 'success', 'title' => 'Registro actualizado exitosamente.']);
        } else {
            Escuela4s::create($data);
            $this->dispatch('swal', ['icon' => 'success', 'title' => 'Registro creado exitosamente.']);
        }

        $this->closeModal();
    }

    public function edit(int $id): void
    {
        $this->resetInputFields();
        $e = Escuela4s::with(['sector.comuna.parroquia.municipio'])->findOrFail($id);

        $this->escuela_id   = $e->id;
        $this->observacion  = $e->observacion;
        $this->responsable  = $e->responsable;
        $this->fecha        = Carbon::parse($e->fecha)->format('Y-m-d');
        $this->nombre_escuela = $e->nombre_escuela;
        
        $this->sector_id    = (string) $e->sector_id;
        $this->comuna_id    = (string) $e->sector->comuna_id;
        $this->parroquia_id = (string) $e->sector->comuna->parroquia_id;
        $this->municipio_id = (string) $e->sector->comuna->parroquia->municipio_id;
        
        $this->director_a   = $e->director_a;
        $this->codigo_dea   = $e->codigo_dea;
        $this->codigo_cnae  = $e->codigo_cnae;
        $this->tema_tratado = $e->tema_tratado;
        $this->fase         = $e->fase;

        // Cargar combos en cascada
        $this->parroquiasFiltradas = Parroquia::where('municipio_id', $this->municipio_id)->orderBy('nombre')->get();
        $this->comunasFiltradas    = Comuna::where('parroquia_id', $this->parroquia_id)->orderBy('nombre')->get();
        $this->sectoresFiltrados   = Sector::where('comuna_id', $this->comuna_id)->orderBy('nombre')->get();

        $this->isModalOpen = true;
    }

    public function show(int $id): void
    {
        $e = Escuela4s::with(['sector.comuna.parroquia.municipio'])->findOrFail($id);

        $this->view_observacion = $e->observacion;
        $this->view_responsable  = $e->responsable;
        $this->view_fecha       = Carbon::parse($e->fecha)->format('d/m/Y');
        $this->view_nombre_escuela = $e->nombre_escuela;
        $this->view_municipio   = $e->sector->comuna->parroquia->municipio->nombre;
        $this->view_parroquia   = $e->sector->comuna->parroquia->nombre;
        $this->view_comuna      = $e->sector->comuna->nombre;
        $this->view_sector      = $e->sector->nombre;
        $this->view_director_a  = $e->director_a;
        $this->view_codigo_dea  = $e->codigo_dea;
        $this->view_codigo_cnae = $e->codigo_cnae;
        $this->view_tema_tratado = $e->tema_tratado;
        $this->view_fase        = $e->fase;

        $this->isViewModalOpen = true;
    }

    public function delete(int $id): void
    {
        Escuela4s::findOrFail($id)->delete();
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
        $this->escuela_id         = null;
        $this->observacion        = null;
        $this->responsable        = null;
        $this->fecha              = '';
        $this->nombre_escuela     = '';
        $this->municipio_id       = '';
        $this->parroquia_id       = '';
        $this->comuna_id          = '';
        $this->sector_id          = '';
        $this->director_a         = '';
        $this->codigo_dea         = '';
        $this->codigo_cnae        = '';
        $this->tema_tratado       = '';
        $this->fase               = 'FASE 1';
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
        $url = route('admin.actividades.escuela4s.pdf', [
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

    public function closeGraphModal(): void { $this->isGraphModalOpen = false; }

    public function viewGraphs(): void
    {
        $url = route('admin.actividades.escuela4s.graficos', [
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

        $queryBase = Escuela4s::query()
            ->join('sectores',  'escuela4s.sector_id',  '=', 'sectores.id')
            ->join('comunas',   'sectores.comuna_id',    '=', 'comunas.id')
            ->join('parroquias','comunas.parroquia_id',  '=', 'parroquias.id')
            ->whereYear('escuela4s.fecha', $año)
            ->whereMonth('escuela4s.fecha', $mes)
            ->when($munId, fn($q) => $q->where('parroquias.municipio_id', $munId));

        $totales = (clone $queryBase)->selectRaw('
            COUNT(escuela4s.id) as total_registros,
            SUM(CASE WHEN escuela4s.fase = "FASE 1" THEN 1 ELSE 0 END) as fase1,
            SUM(CASE WHEN escuela4s.fase = "FASE 2" THEN 1 ELSE 0 END) as fase2,
            SUM(CASE WHEN escuela4s.fase = "FASE 3" THEN 1 ELSE 0 END) as fase3
        ')->first();

        $this->graphKpis = [
            'total_registros' => $totales->total_registros,
            'fase1'           => $totales->fase1,
            'fase2'           => $totales->fase2,
            'fase3'           => $totales->fase3,
            // Alias for generic dashboard view
            'total_cantidad'  => $totales->total_registros,
            'promedio_diario' => $totales->total_registros > 0
                ? round($totales->total_registros / max(1, now()->daysInMonth), 1)
                : 0,
        ];

        $this->graphFases = [
            ['nombre' => 'FASE 1', 'total' => $totales->fase1],
            ['nombre' => 'FASE 2', 'total' => $totales->fase2],
            ['nombre' => 'FASE 3', 'total' => $totales->fase3],
        ];

        $this->graphParroquias = (clone $queryBase)
            ->select('parroquias.nombre', DB::raw('COUNT(escuela4s.id) as total'))
            ->groupBy('parroquias.id','parroquias.nombre')
            ->orderByDesc('total')->get()->toArray();

        $this->graphComunas = (clone $queryBase)
            ->select('comunas.nombre', DB::raw('COUNT(escuela4s.id) as total'))
            ->groupBy('comunas.id','comunas.nombre')
            ->orderByDesc('total')->get()->toArray();

        $this->graphSectores = (clone $queryBase)
            ->select('sectores.nombre', DB::raw('COUNT(escuela4s.id) as total'))
            ->groupBy('sectores.id','sectores.nombre')
            ->orderByDesc('total')->get()->toArray();

        $this->graphDias = (clone $queryBase)
            ->select(DB::raw('DAY(escuela4s.fecha) as dia'), DB::raw('COUNT(escuela4s.id) as total'))
            ->groupBy(DB::raw('DAY(escuela4s.fecha)'))
            ->orderBy('dia')->get()->toArray();
    }

    // ── Render ────────────────────────────────────────────────────────────────

    public function mount(): void
    {
        if (request()->routeIs('admin.actividades.escuela4s.graficos')) {
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

        $metrics = Cache::rememberForever('escuela4s_metrics', function () use ($now) {
            $totalAnual  = Escuela4s::whereYear('fecha', $now->year)->count();
            $totalMes    = Escuela4s::whereYear('fecha', $now->year)->whereMonth('fecha', $now->month)->count();

            $startOfWeek = $now->copy()->startOfWeek();
            $endOfWeek   = $now->copy()->endOfWeek();
            $totalSemana = Escuela4s::whereBetween('fecha', [$startOfWeek, $endOfWeek])->count();

            $registrosMes = Escuela4s::whereYear('fecha', $now->year)->whereMonth('fecha', $now->month)->count();

            $municipiosConTotales = Municipio::query()
                ->select('municipios.*')
                ->addSelect([
                    'total_anual' => DB::table('escuela4s')
                        ->join('sectores', 'escuela4s.sector_id', '=', 'sectores.id')
                        ->join('comunas', 'sectores.comuna_id', '=', 'comunas.id')
                        ->join('parroquias', 'comunas.parroquia_id', '=', 'parroquias.id')
                        ->whereColumn('parroquias.municipio_id', 'municipios.id')
                        ->whereYear('escuela4s.fecha', $now->year)
                        ->selectRaw('COUNT(*)'),
                    
                    'total_mes' => DB::table('escuela4s')
                        ->join('sectores', 'escuela4s.sector_id', '=', 'sectores.id')
                        ->join('comunas', 'sectores.comuna_id', '=', 'comunas.id')
                        ->join('parroquias', 'comunas.parroquia_id', '=', 'parroquias.id')
                        ->whereColumn('parroquias.municipio_id', 'municipios.id')
                        ->whereYear('escuela4s.fecha', $now->year)
                        ->whereMonth('escuela4s.fecha', $now->month)
                        ->selectRaw('COUNT(*)'),
                        
                    'total_semana' => DB::table('escuela4s')
                        ->join('sectores', 'escuela4s.sector_id', '=', 'sectores.id')
                        ->join('comunas', 'sectores.comuna_id', '=', 'comunas.id')
                        ->join('parroquias', 'comunas.parroquia_id', '=', 'parroquias.id')
                        ->whereColumn('parroquias.municipio_id', 'municipios.id')
                        ->whereBetween('escuela4s.fecha', [$startOfWeek, $endOfWeek])
                        ->selectRaw('COUNT(*)'),

                    'abordajes_mes_count' => DB::table('escuela4s')
                        ->join('sectores', 'escuela4s.sector_id', '=', 'sectores.id')
                        ->join('comunas', 'sectores.comuna_id', '=', 'comunas.id')
                        ->join('parroquias', 'comunas.parroquia_id', '=', 'parroquias.id')
                        ->whereColumn('parroquias.municipio_id', 'municipios.id')
                        ->whereYear('escuela4s.fecha', $now->year)
                        ->whereMonth('escuela4s.fecha', $now->month)
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

        $registros = Escuela4s::query()
            ->select('escuela4s.*')
            ->join('sectores',   'escuela4s.sector_id',    '=', 'sectores.id')
            ->join('comunas',     'sectores.comuna_id',     '=', 'comunas.id')
            ->join('parroquias',  'comunas.parroquia_id',   '=', 'parroquias.id')
            ->join('municipios',  'parroquias.municipio_id', '=', 'municipios.id')
            ->when($this->search, function ($q) {
                $term = '%' . $this->search . '%';
                $q->where(function ($q1) use ($term) {
                    $q1->where('escuela4s.observacion', 'like', $term)
                       ->orWhere('escuela4s.responsable', 'like', $term)
                       ->orWhere('escuela4s.nombre_escuela', 'like', $term)
                       ->orWhere('municipios.nombre',   'like', $term)
                       ->orWhere('parroquias.nombre',   'like', $term)
                       ->orWhere('comunas.nombre',      'like', $term)
                       ->orWhere('sectores.nombre',     'like', $term);
                });
            })
            ->when($this->dateFrom, fn($q) => $q->whereDate('escuela4s.fecha', '>=', $this->dateFrom))
            ->when($this->dateTo,   fn($q) => $q->whereDate('escuela4s.fecha', '<=', $this->dateTo))
            ->with(['sector.comuna.parroquia.municipio'])
            ->orderBy('escuela4s.fecha', $this->sortDirection)
            ->paginate(10);

        if ($this->isGraphView) {
            $mesesNombres = [1=>'Enero',2=>'Febrero',3=>'Marzo',4=>'Abril',5=>'Mayo',6=>'Junio',7=>'Julio',8=>'Agosto',9=>'Septiembre',10=>'Octubre',11=>'Noviembre',12=>'Diciembre'];
            return view('livewire.actividades.escuela_4s.graficos-index', [
                'nombreMes'  => $mesesNombres[(int)$this->graphMonth] ?? 'Desconocido',
                'ano'        => $this->graphAno,
                'municipios' => Municipio::orderBy('nombre')->get(),
            ]);
        }

        return view('livewire.actividades.escuela_4s.escuela_4s-index', [
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

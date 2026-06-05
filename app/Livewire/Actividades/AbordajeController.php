<?php

namespace App\Livewire\Actividades;

use App\Models\Abordaje;
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
use Livewire\Attributes\Url;

#[Layout('components.layouts.app')]
class AbordajeController extends Component
{
    use WithPagination;

    // ── Búsqueda y Filtros ────────────────────────────────────────────────────
    public string $search = '';
    public string $dateFrom = '';
    public string $dateTo = '';
    public string $sortDirection = 'desc';

    // ── Campos del formulario ─────────────────────────────────────────────────
    public ?int    $abordaje_id  = null;
    public ?string $observacion  = null;
    public string  $fecha        = '';
    public string  $municipio_id = '';
    public string  $parroquia_id = '';
    public string  $comuna_id    = '';
    public string  $sector_id    = '';
    public string  $cantidad     = '';
    public ?string $responsable  = null;
    public string  $total_a      = '0';
    public string  $total_b      = '0';
    public string  $total_a_plus = '0';
    // Condición de la población
    public string  $embarazada      = '0';
    public string  $mujer_lactante  = '0';
    public string  $menor_72_meses  = '0';
    public string  $escolar         = '0';
    public string  $adolescente     = '0';
    public string  $adulto          = '0';
    public string  $adulto_mayor    = '0';
    public string  $encamado        = '0';
    public string  $discapacidad    = '0';

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
    #[Url(as: 'municipio_id')]
    public ?int    $graphMunicipioId = null;
    public ?string $graphMunicipioNombre = null;
    public array   $graphKpis        = [];
    public array   $graphParroquias  = [];
    public array   $graphClasificacion = [];
    public array   $graphEvolucionClasificacion = [];
    public array   $graphDias        = [];
    public array   $graphCondicion   = [];
    public string  $colorHex         = '#84cc16';
    public string  $colorTw          = 'lime';

    // ── Datos del modal "Ver" ─────────────────────────────────────────────────
    public ?string $view_observacion = null;
    public string  $view_fecha       = '';
    public string  $view_municipio   = '';
    public string  $view_parroquia   = '';
    public string  $view_comuna      = '';
    public string  $view_sector      = '';
    public string  $view_cantidad    = '';
    public ?string $view_responsable  = null;
    public string  $view_total_a      = '0';
    public string  $view_total_b      = '0';
    public string  $view_total_a_plus = '0';
    // Condición (vista)
    public string  $view_embarazada      = '0';
    public string  $view_mujer_lactante  = '0';
    public string  $view_menor_72_meses  = '0';
    public string  $view_escolar         = '0';
    public string  $view_adolescente     = '0';
    public string  $view_adulto          = '0';
    public string  $view_adulto_mayor    = '0';
    public string  $view_encamado        = '0';
    public string  $view_discapacidad    = '0';

    // ── Lifecycle ─────────────────────────────────────────────────────────────

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
            'responsable'  => 'nullable|string|max:255',
            'fecha'        => 'required|date',
            'sector_id'    => 'required|exists:sectores,id',
            'cantidad'     => 'required|integer|min:0',
            'total_a'      => 'required|integer|min:0',
            'total_b'      => 'required|integer|min:0',
            'total_a_plus' => 'required|integer|min:0',
        ], [
            'fecha.required'        => 'La fecha es obligatoria.',
            'sector_id.required'    => 'Selecciona un sector.',
            'cantidad.required'     => 'La cantidad es obligatoria.',
            'total_a.required'      => 'El Total A es obligatorio.',
            'total_b.required'      => 'El Total B es obligatorio.',
            'total_a_plus.required' => 'El Total A+ es obligatorio.',
        ]);

        // Validación personalizada: la suma de totales no puede ser mayor a la cantidad
        $sumaTotales = (int)$this->total_a + (int)$this->total_b + (int)$this->total_a_plus;
        if ($sumaTotales > (int)$this->cantidad) {
            $this->addError('cantidad', 'La suma de los totales (A, B, A+) no puede ser mayor a la cantidad total.');
            return;
        }

        $data = [
            'observacion'  => $this->observacion ? mb_strtoupper(trim($this->observacion), 'UTF-8') : null,
            'responsable'  => $this->responsable ? mb_strtoupper(trim($this->responsable), 'UTF-8') : null,
            'fecha'        => $this->fecha,
            'sector_id'    => $this->sector_id,
            'cantidad'     => (int) $this->cantidad,
            'total_a'      => (int) $this->total_a,
            'total_b'      => (int) $this->total_b,
            'total_a_plus' => (int) $this->total_a_plus,
            // Condición
            'embarazada'      => (int) $this->embarazada,
            'mujer_lactante'  => (int) $this->mujer_lactante,
            'menor_72_meses'  => (int) $this->menor_72_meses,
            'escolar'         => (int) $this->escolar,
            'adolescente'     => (int) $this->adolescente,
            'adulto'          => (int) $this->adulto,
            'adulto_mayor'    => (int) $this->adulto_mayor,
            'encamado'        => (int) $this->encamado,
            'discapacidad'    => (int) $this->discapacidad,
        ];

        if ($this->abordaje_id) {
            Abordaje::findOrFail($this->abordaje_id)->update($data);
            $this->dispatch('swal', ['icon' => 'success', 'title' => 'Abordaje actualizado exitosamente.']);
        } else {
            Abordaje::create($data);
            $this->dispatch('swal', ['icon' => 'success', 'title' => 'Abordaje creado exitosamente.']);
        }

        // El Observer de Abordaje invalida el caché automáticamente.
        $this->closeModal();
    }

    public function edit(int $id): void
    {
        $this->resetInputFields();
        $a = Abordaje::with(['sector.comuna.parroquia.municipio'])->findOrFail($id);

        $this->abordaje_id  = $a->id;
        $this->observacion  = $a->observacion;
        $this->fecha        = Carbon::parse($a->fecha)->format('Y-m-d');
        
        $this->sector_id    = (string) $a->sector_id;
        $this->comuna_id    = (string) $a->sector->comuna_id;
        $this->parroquia_id = (string) $a->sector->comuna->parroquia_id;
        $this->municipio_id = (string) $a->sector->comuna->parroquia->municipio_id;
        
        $this->cantidad     = (string) $a->cantidad;
        $this->responsable  = $a->responsable;
        $this->total_a      = (string) $a->total_a;
        $this->total_b      = (string) $a->total_b;
        $this->total_a_plus = (string) $a->total_a_plus;
        // Condición
        $this->embarazada      = (string) ($a->embarazada ?? 0);
        $this->mujer_lactante  = (string) ($a->mujer_lactante ?? 0);
        $this->menor_72_meses  = (string) ($a->menor_72_meses ?? 0);
        $this->escolar         = (string) ($a->escolar ?? 0);
        $this->adolescente     = (string) ($a->adolescente ?? 0);
        $this->adulto          = (string) ($a->adulto ?? 0);
        $this->adulto_mayor    = (string) ($a->adulto_mayor ?? 0);
        $this->encamado        = (string) ($a->encamado ?? 0);
        $this->discapacidad    = (string) ($a->discapacidad ?? 0);

        // Cargar combos en cascada
        $this->parroquiasFiltradas = Parroquia::where('municipio_id', $this->municipio_id)->orderBy('nombre')->get();
        $this->comunasFiltradas    = Comuna::where('parroquia_id', $this->parroquia_id)->orderBy('nombre')->get();
        $this->sectoresFiltrados   = Sector::where('comuna_id', $this->comuna_id)->orderBy('nombre')->get();

        $this->isModalOpen = true;
    }

    public function show(int $id): void
    {
        $a = Abordaje::with(['sector.comuna.parroquia.municipio'])->findOrFail($id);

        $this->view_observacion = $a->observacion;
        $this->view_fecha       = Carbon::parse($a->fecha)->format('d/m/Y');
        $this->view_municipio   = $a->sector->comuna->parroquia->municipio->nombre;
        $this->view_parroquia   = $a->sector->comuna->parroquia->nombre;
        $this->view_comuna      = $a->sector->comuna->nombre;
        $this->view_sector      = $a->sector->nombre;
        $this->view_cantidad    = (string) $a->cantidad;
        $this->view_responsable  = $a->responsable;
        $this->view_total_a      = (string) $a->total_a;
        $this->view_total_b      = (string) $a->total_b;
        $this->view_total_a_plus = (string) $a->total_a_plus;
        // Condición (vista)
        $this->view_embarazada      = (string) ($a->embarazada ?? 0);
        $this->view_mujer_lactante  = (string) ($a->mujer_lactante ?? 0);
        $this->view_menor_72_meses  = (string) ($a->menor_72_meses ?? 0);
        $this->view_escolar         = (string) ($a->escolar ?? 0);
        $this->view_adolescente     = (string) ($a->adolescente ?? 0);
        $this->view_adulto          = (string) ($a->adulto ?? 0);
        $this->view_adulto_mayor    = (string) ($a->adulto_mayor ?? 0);
        $this->view_encamado        = (string) ($a->encamado ?? 0);
        $this->view_discapacidad    = (string) ($a->discapacidad ?? 0);

        $this->isViewModalOpen = true;
    }

    public function delete(int $id): void
    {
        Abordaje::findOrFail($id)->delete();
        // El Observer de Abordaje invalida el caché automáticamente.
        $this->dispatch('swal', ['icon' => 'success', 'title' => 'Abordaje eliminado correctamente.']);
    }

    public function closeModal(): void
    {
        $this->isModalOpen     = false;
        $this->isViewModalOpen = false;
        $this->resetInputFields();
    }

    private function resetInputFields(): void
    {
        $this->abordaje_id        = null;
        $this->observacion        = null;
        $this->fecha              = '';
        $this->municipio_id       = '';
        $this->parroquia_id       = '';
        $this->comuna_id          = '';
        $this->sector_id          = '';
        $this->cantidad           = '';
        $this->parroquiasFiltradas = [];
        $this->comunasFiltradas    = [];
        $this->sectoresFiltrados   = [];
        // Vista
        $this->view_observacion   = null;
        $this->view_fecha         = '';
        $this->view_municipio     = '';
        $this->view_parroquia     = '';
        $this->view_comuna        = '';
        $this->view_sector        = '';
        $this->view_cantidad      = '';
        $this->view_responsable   = null;
        $this->view_total_a       = '0';
        $this->view_total_b       = '0';
        $this->view_total_a_plus  = '0';
        $this->view_embarazada      = '0';
        $this->view_mujer_lactante  = '0';
        $this->view_menor_72_meses  = '0';
        $this->view_escolar         = '0';
        $this->view_adolescente     = '0';
        $this->view_adulto          = '0';
        $this->view_adulto_mayor    = '0';
        $this->view_encamado        = '0';
        $this->view_discapacidad    = '0';
        $this->responsable        = null;
        $this->total_a            = '0';
        $this->total_b            = '0';
        $this->total_a_plus       = '0';
        $this->embarazada         = '0';
        $this->mujer_lactante     = '0';
        $this->menor_72_meses     = '0';
        $this->escolar            = '0';
        $this->adolescente        = '0';
        $this->adulto             = '0';
        $this->adulto_mayor       = '0';
        $this->encamado           = '0';
        $this->discapacidad       = '0';
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
        $url = route('admin.actividades.abordajes.pdf', [
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
        $url = route('admin.actividades.abordajes.graficos', [
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

        $queryBase = Abordaje::query()
            ->join('sectores',  'abordajes.sector_id',      '=', 'sectores.id')
            ->join('comunas',   'sectores.comuna_id',        '=', 'comunas.id')
            ->join('parroquias','comunas.parroquia_id',      '=', 'parroquias.id')
            ->whereYear('abordajes.fecha', $año)
            ->whereMonth('abordajes.fecha', $mes)
            ->when($munId, fn($q) => $q->where('parroquias.municipio_id', $munId));

        $totales = (clone $queryBase)->selectRaw('
            COUNT(abordajes.id) as total_registros,
            COALESCE(SUM(abordajes.cantidad), 0) as total_cantidad,
            COALESCE(SUM(abordajes.total_a), 0) as total_a,
            COALESCE(SUM(abordajes.total_b), 0) as total_b,
            COALESCE(SUM(abordajes.total_a_plus), 0) as total_a_plus
        ')->first();

        $this->graphKpis = [
            'total_registros'  => $totales->total_registros,
            'total_cantidad'   => $totales->total_cantidad,
            'total_a'          => $totales->total_a,
            'total_b'          => $totales->total_b,
            'total_a_plus'     => $totales->total_a_plus,
            'promedio_diario'  => $totales->total_cantidad > 0
                ? round($totales->total_cantidad / max(1, now()->daysInMonth), 1)
                : 0,
        ];

        $this->graphParroquias = (clone $queryBase)
            ->select('parroquias.nombre', DB::raw('SUM(abordajes.cantidad) as total'))
            ->groupBy('parroquias.id', 'parroquias.nombre')
            ->orderByDesc('total')->get()->toArray();

        $this->graphClasificacion = [
            ['nombre' => 'Caso A', 'total' => $totales->total_a, 'color' => '#f43f5e'],
            ['nombre' => 'Caso B', 'total' => $totales->total_b, 'color' => '#f59e0b'],
            ['nombre' => 'Caso A+', 'total' => $totales->total_a_plus, 'color' => '#ec4899']
        ];

        $this->graphEvolucionClasificacion = (clone $queryBase)
            ->select(
                DB::raw('DAY(abordajes.fecha) as dia'),
                DB::raw('SUM(abordajes.total_a) as total_a'),
                DB::raw('SUM(abordajes.total_b) as total_b'),
                DB::raw('SUM(abordajes.total_a_plus) as total_a_plus')
            )
            ->groupBy(DB::raw('DAY(abordajes.fecha)'))
            ->orderBy('dia')->get()->toArray();

        $this->graphDias = (clone $queryBase)
            ->select(DB::raw('DAY(abordajes.fecha) as dia'), DB::raw('SUM(abordajes.cantidad) as total'))
            ->groupBy(DB::raw('DAY(abordajes.fecha)'))
            ->orderBy('dia')->get()->toArray();

        // Distribución por Condición de la Población
        $condTotales = (clone $queryBase)->selectRaw('
            COALESCE(SUM(abordajes.embarazada), 0)     as embarazada,
            COALESCE(SUM(abordajes.mujer_lactante), 0) as mujer_lactante,
            COALESCE(SUM(abordajes.menor_72_meses), 0) as menor_72_meses,
            COALESCE(SUM(abordajes.escolar), 0)        as escolar,
            COALESCE(SUM(abordajes.adolescente), 0)    as adolescente,
            COALESCE(SUM(abordajes.adulto), 0)         as adulto,
            COALESCE(SUM(abordajes.adulto_mayor), 0)   as adulto_mayor,
            COALESCE(SUM(abordajes.encamado), 0)       as encamado,
            COALESCE(SUM(abordajes.discapacidad), 0)   as discapacidad
        ')->first();

        $this->graphCondicion = [
            ['nombre' => 'Embarazada',   'total' => (int)($condTotales->embarazada ?? 0),     'color' => '#ec4899'],
            ['nombre' => 'M. Lactante',  'total' => (int)($condTotales->mujer_lactante ?? 0), 'color' => '#f43f5e'],
            ['nombre' => '< 72 meses',   'total' => (int)($condTotales->menor_72_meses ?? 0), 'color' => '#f97316'],
            ['nombre' => 'Escolar',      'total' => (int)($condTotales->escolar ?? 0),        'color' => '#eab308'],
            ['nombre' => 'Adolescente',  'total' => (int)($condTotales->adolescente ?? 0),    'color' => '#22c55e'],
            ['nombre' => 'Adulto',       'total' => (int)($condTotales->adulto ?? 0),         'color' => '#84cc16'],
            ['nombre' => 'Adulto Mayor', 'total' => (int)($condTotales->adulto_mayor ?? 0),   'color' => '#8b5cf6'],
            ['nombre' => 'Encamado',     'total' => (int)($condTotales->encamado ?? 0),       'color' => '#0ea5e9'],
            ['nombre' => 'Discapacidad', 'total' => (int)($condTotales->discapacidad ?? 0),   'color' => '#14b8a6'],
        ];
    }

    // ── Render ────────────────────────────────────────────────────────────────

    public function mount(): void
    {
        if (request()->routeIs('admin.actividades.abordajes.graficos')) {
            $this->isGraphView = true;
            
            // Forzamos la carga desde el request para evitar problemas con wire:navigate
            $this->graphMonth = (string) request()->query('mes', $this->graphMonth ?? now()->month);
            $this->graphAno   = (string) request()->query('año', $this->graphAno ?? now()->year);
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

        // Métricas cacheadas
        $metrics = Cache::rememberForever('abordaje_metrics', function () use ($now) {
            $totalAnual  = Abordaje::whereYear('fecha', $now->year)->sum('cantidad');
            $totalMes    = Abordaje::whereYear('fecha', $now->year)->whereMonth('fecha', $now->month)->sum('cantidad');

            $startOfWeek = $now->copy()->startOfWeek();
            $endOfWeek   = $now->copy()->endOfWeek();
            $totalSemana = Abordaje::whereBetween('fecha', [$startOfWeek, $endOfWeek])->sum('cantidad');

            $registrosMes = Abordaje::whereYear('fecha', $now->year)->whereMonth('fecha', $now->month)->count();

            // Distribución por municipio usando subconsultas o joins manuales para evitar dependencia de relaciones directas eliminadas
            $municipiosConTotales = Municipio::query()
                ->select('municipios.*')
                ->addSelect([
                    'total_anual' => DB::table('abordajes')
                        ->join('sectores', 'abordajes.sector_id', '=', 'sectores.id')
                        ->join('comunas', 'sectores.comuna_id', '=', 'comunas.id')
                        ->join('parroquias', 'comunas.parroquia_id', '=', 'parroquias.id')
                        ->whereColumn('parroquias.municipio_id', 'municipios.id')
                        ->whereYear('abordajes.fecha', $now->year)
                        ->selectRaw('COALESCE(SUM(cantidad), 0)'),
                    
                    'total_mes' => DB::table('abordajes')
                        ->join('sectores', 'abordajes.sector_id', '=', 'sectores.id')
                        ->join('comunas', 'sectores.comuna_id', '=', 'comunas.id')
                        ->join('parroquias', 'comunas.parroquia_id', '=', 'parroquias.id')
                        ->whereColumn('parroquias.municipio_id', 'municipios.id')
                        ->whereYear('abordajes.fecha', $now->year)
                        ->whereMonth('abordajes.fecha', $now->month)
                        ->selectRaw('COALESCE(SUM(cantidad), 0)'),
                        
                    'total_semana' => DB::table('abordajes')
                        ->join('sectores', 'abordajes.sector_id', '=', 'sectores.id')
                        ->join('comunas', 'sectores.comuna_id', '=', 'comunas.id')
                        ->join('parroquias', 'comunas.parroquia_id', '=', 'parroquias.id')
                        ->whereColumn('parroquias.municipio_id', 'municipios.id')
                        ->whereBetween('abordajes.fecha', [$startOfWeek, $endOfWeek])
                        ->selectRaw('COALESCE(SUM(cantidad), 0)'),

                    'abordajes_mes_count' => DB::table('abordajes')
                        ->join('sectores', 'abordajes.sector_id', '=', 'sectores.id')
                        ->join('comunas', 'sectores.comuna_id', '=', 'comunas.id')
                        ->join('parroquias', 'comunas.parroquia_id', '=', 'parroquias.id')
                        ->whereColumn('parroquias.municipio_id', 'municipios.id')
                        ->whereYear('abordajes.fecha', $now->year)
                        ->whereMonth('abordajes.fecha', $now->month)
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

        // Paginación dinámica con LEFT JOINs.
        // Fusionamos las tablas relacionadas en una sola consulta plana, evitando
        // subconsultas EXISTS (whereHas) que son lentas con volumen alto de registros.
        $abordajes = Abordaje::query()
            ->select('abordajes.*')
            ->join('sectores',   'abordajes.sector_id',    '=', 'sectores.id')
            ->join('comunas',     'sectores.comuna_id',     '=', 'comunas.id')
            ->join('parroquias',  'comunas.parroquia_id',   '=', 'parroquias.id')
            ->join('municipios',  'parroquias.municipio_id', '=', 'municipios.id')
            ->when($this->search, function ($q) {
                $term = '%' . $this->search . '%';
                $q->where(function ($q1) use ($term) {
                    $q1->where('abordajes.observacion', 'like', $term)
                       ->orWhere('abordajes.responsable', 'like', $term)
                       ->orWhere('municipios.nombre',   'like', $term)
                       ->orWhere('parroquias.nombre',   'like', $term)
                       ->orWhere('comunas.nombre',      'like', $term)
                       ->orWhere('sectores.nombre',     'like', $term);
                });
            })
            ->when($this->dateFrom, fn($q) => $q->whereDate('abordajes.fecha', '>=', $this->dateFrom))
            ->when($this->dateTo,   fn($q) => $q->whereDate('abordajes.fecha', '<=', $this->dateTo))
            ->with(['sector.comuna.parroquia.municipio'])
            ->orderBy('abordajes.fecha', $this->sortDirection)
            ->paginate(10);

        if ($this->isGraphView) {
            $mesesNombres = [
                1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
                5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
                9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
            ];
            return view('livewire.actividades.abordaje.graficos-index', [
                'nombreMes'  => $mesesNombres[(int)$this->graphMonth] ?? 'Desconocido',
                'ano'        => $this->graphAno,
                'municipios' => Municipio::orderBy('nombre')->get(),
            ]);
        }

        return view('livewire.actividades.abordaje.abordaje-index', [
            'abordajes'            => $abordajes,
            'municipios'           => Municipio::orderBy('nombre')->get(),
            'municipiosConTotales' => $metrics['municipiosConTotales'],
            'totalAnual'           => $metrics['totalAnual'],
            'totalMes'             => $metrics['totalMes'],
            'totalSemana'          => $metrics['totalSemana'],
            'registrosMes'         => $metrics['registrosMes'],
        ]);
    }
}

<?php

namespace App\Livewire\Actividades;

use App\Models\FeriaCampo;
use App\Models\Comuna;
use App\Models\Municipio;
use App\Models\Parroquia;
use App\Models\Sector;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Ajuste;
use Barryvdh\DomPDF\Facade\Pdf;

#[Layout('components.layouts.app')]
class FeriaCampoController extends Component
{
    use WithPagination;

    // ── Búsqueda y Filtros ────────────────────────────────────────────────────
    public string $search = '';
    public string $dateFrom = '';
    public string $dateTo = '';
    public string $sortDirection = 'desc';

    // ── Campos del formulario ─────────────────────────────────────────────────
    public ?int    $feria_id    = null;
    public ?string $observacion = null;
    public ?string $responsable = null;
    public string  $fecha       = '';
    public string  $municipio_id = '';
    public string  $parroquia_id = '';
    public string  $comuna_id    = '';
    public string  $sector_id    = '';

    // ── Nuevos campos ────────────────────────────────────────────────────────
    public string  $venta_lina_nutrivida = '0';
    public string  $antrometria          = '0';
    public ?int    $tipo_a               = null;
    public ?int    $tipo_b               = null;
    public ?int    $tipo_a_plus          = null;
    public string  $campana4s            = '0';
    public ?string $tema_tratado         = null;
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
    // Datos procesados para gráficas
    public array $graphKpis         = [];
    public array $graphParroquias   = [];
    public array $graphComunas      = [];
    public array $graphSectores     = [];
    public array $graphDias         = [];
    public array $graphServicios    = [];
    public array $graphTipologia    = [];
    public array $graphAntrometria  = [];
    public array $graphCondicion    = [];
    public string $colorHex         = '#6366f1';
    public string $colorTw          = 'indigo';

    // ── Datos del modal "Ver" ─────────────────────────────────────────────────
    public ?string $view_observacion = null;
    public ?string $view_responsable = null;
    public string  $view_fecha       = '';
    public string  $view_municipio   = '';
    public string  $view_parroquia   = '';
    public string  $view_comuna      = '';
    public string  $view_sector      = '';

    // ── Nuevos campos Ver ─────────────────────────────────────────────────────
    public string  $view_venta_lina_nutrivida = 'NO';
    public string  $view_antrometria          = 'NO';
    public ?int    $view_tipo_a               = null;
    public ?int    $view_tipo_b               = null;
    public ?int    $view_tipo_a_plus          = null;
    public string  $view_campana4s            = 'NO';
    public ?string $view_tema_tratado         = null;
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
            'observacion' => 'nullable|string|max:255',
            'responsable' => 'required|string|max:255',
            'fecha'       => 'required|date',
            'sector_id'   => 'required|exists:sectores,id',
            'venta_lina_nutrivida' => 'required|in:0,1',
            'antrometria'          => 'required|in:0,1',
            'tipo_a'               => 'nullable|required_if:antrometria,1|integer|min:0',
            'tipo_b'               => 'nullable|required_if:antrometria,1|integer|min:0',
            'tipo_a_plus'          => 'nullable|required_if:antrometria,1|integer|min:0',
            'campana4s'            => 'required|in:0,1',
            'tema_tratado'         => 'nullable|required_if:campana4s,1|string|max:255',
        ], [
            'responsable.required' => 'El responsable es obligatorio.',
            'fecha.required'       => 'La fecha es obligatoria.',
            'sector_id.required'   => 'Selecciona un sector.',
            'tipo_a.required_if'   => 'El total tipo A es obligatorio si hay antropometría.',
            'tipo_b.required_if'   => 'El total tipo B es obligatorio si hay antropometría.',
            'tipo_a_plus.required_if' => 'El total tipo A+ es obligatorio si hay antropometría.',
            'tema_tratado.required_if' => 'El tema tratado es obligatorio si es Campaña 4S.',
        ]);

        $data = [
            'observacion' => $this->observacion ? mb_strtoupper(trim($this->observacion), 'UTF-8') : null,
            'responsable' => mb_strtoupper(trim($this->responsable), 'UTF-8'),
            'fecha'       => $this->fecha,
            'sector_id'   => $this->sector_id,
            'venta_lina_nutrivida' => (bool)$this->venta_lina_nutrivida,
            'antrometria'          => (bool)$this->antrometria,
            'tipo_a'               => $this->antrometria === '1' ? $this->tipo_a : null,
            'tipo_b'               => $this->antrometria === '1' ? $this->tipo_b : null,
            'tipo_a_plus'          => $this->antrometria === '1' ? $this->tipo_a_plus : null,
            'campana4s'            => (bool)$this->campana4s,
            'tema_tratado'         => $this->campana4s === '1' ? ($this->tema_tratado ? mb_strtoupper(trim($this->tema_tratado), 'UTF-8') : null) : null,
            // Condición
            'embarazada'      => $this->antrometria === '1' ? (int) $this->embarazada : null,
            'mujer_lactante'  => $this->antrometria === '1' ? (int) $this->mujer_lactante : null,
            'menor_72_meses'  => $this->antrometria === '1' ? (int) $this->menor_72_meses : null,
            'escolar'         => $this->antrometria === '1' ? (int) $this->escolar : null,
            'adolescente'     => $this->antrometria === '1' ? (int) $this->adolescente : null,
            'adulto'          => $this->antrometria === '1' ? (int) $this->adulto : null,
            'adulto_mayor'    => $this->antrometria === '1' ? (int) $this->adulto_mayor : null,
            'encamado'        => $this->antrometria === '1' ? (int) $this->encamado : null,
            'discapacidad'    => $this->antrometria === '1' ? (int) $this->discapacidad : null,
        ];

        if ($this->feria_id) {
            FeriaCampo::findOrFail($this->feria_id)->update($data);
            $this->dispatch('swal', ['icon' => 'success', 'title' => 'Registro actualizado exitosamente.']);
        } else {
            FeriaCampo::create($data);
            $this->dispatch('swal', ['icon' => 'success', 'title' => 'Registro creado exitosamente.']);
        }

        $this->closeModal();
    }

    public function edit(int $id): void
    {
        $this->resetInputFields();
        $f = FeriaCampo::with(['sector.comuna.parroquia.municipio'])->findOrFail($id);

        $this->feria_id    = $f->id;
        $this->observacion = $f->observacion;
        $this->responsable = $f->responsable;
        $this->fecha       = Carbon::parse($f->fecha)->format('Y-m-d');

        $this->sector_id    = (string) $f->sector_id;
        $this->comuna_id    = (string) $f->sector->comuna_id;
        $this->parroquia_id = (string) $f->sector->comuna->parroquia_id;
        $this->municipio_id = (string) $f->sector->comuna->parroquia->municipio_id;

        $this->venta_lina_nutrivida = $f->venta_lina_nutrivida ? '1' : '0';
        $this->antrometria          = $f->antrometria ? '1' : '0';
        $this->tipo_a               = $f->tipo_a;
        $this->tipo_b               = $f->tipo_b;
        $this->tipo_a_plus          = $f->tipo_a_plus;
        $this->campana4s            = $f->campana4s ? '1' : '0';
        $this->tema_tratado         = $f->tema_tratado;
        // Condición
        $this->embarazada      = (string) ($f->embarazada ?? 0);
        $this->mujer_lactante  = (string) ($f->mujer_lactante ?? 0);
        $this->menor_72_meses  = (string) ($f->menor_72_meses ?? 0);
        $this->escolar         = (string) ($f->escolar ?? 0);
        $this->adolescente     = (string) ($f->adolescente ?? 0);
        $this->adulto          = (string) ($f->adulto ?? 0);
        $this->adulto_mayor    = (string) ($f->adulto_mayor ?? 0);
        $this->encamado        = (string) ($f->encamado ?? 0);
        $this->discapacidad    = (string) ($f->discapacidad ?? 0);

        // Cargar combos en cascada
        $this->parroquiasFiltradas = Parroquia::where('municipio_id', $this->municipio_id)->orderBy('nombre')->get();
        $this->comunasFiltradas    = Comuna::where('parroquia_id', $this->parroquia_id)->orderBy('nombre')->get();
        $this->sectoresFiltrados   = Sector::where('comuna_id', $this->comuna_id)->orderBy('nombre')->get();

        $this->isModalOpen = true;
    }

    public function show(int $id): void
    {
        $f = FeriaCampo::with(['sector.comuna.parroquia.municipio'])->findOrFail($id);

        $this->view_observacion = $f->observacion;
        $this->view_responsable = $f->responsable;
        $this->view_fecha       = Carbon::parse($f->fecha)->format('d/m/Y');
        $this->view_municipio   = $f->sector->comuna->parroquia->municipio->nombre;
        $this->view_parroquia   = $f->sector->comuna->parroquia->nombre;
        $this->view_comuna      = $f->sector->comuna->nombre;
        $this->view_sector      = $f->sector->nombre;

        $this->view_venta_lina_nutrivida = $f->venta_lina_nutrivida ? 'SI' : 'NO';
        $this->view_antrometria          = $f->antrometria ? 'SI' : 'NO';
        $this->view_tipo_a               = $f->tipo_a;
        $this->view_tipo_b               = $f->tipo_b;
        $this->view_tipo_a_plus          = $f->tipo_a_plus;
        $this->view_campana4s            = $f->campana4s ? 'SI' : 'NO';
        $this->view_tema_tratado         = $f->tema_tratado;
        // Condición (vista)
        $this->view_embarazada      = (string) ($f->embarazada ?? 0);
        $this->view_mujer_lactante  = (string) ($f->mujer_lactante ?? 0);
        $this->view_menor_72_meses  = (string) ($f->menor_72_meses ?? 0);
        $this->view_escolar         = (string) ($f->escolar ?? 0);
        $this->view_adolescente     = (string) ($f->adolescente ?? 0);
        $this->view_adulto          = (string) ($f->adulto ?? 0);
        $this->view_adulto_mayor    = (string) ($f->adulto_mayor ?? 0);
        $this->view_encamado        = (string) ($f->encamado ?? 0);
        $this->view_discapacidad    = (string) ($f->discapacidad ?? 0);

        $this->isViewModalOpen = true;
    }

    public function delete(int $id): void
    {
        FeriaCampo::findOrFail($id)->delete();
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
        $this->feria_id           = null;
        $this->observacion        = null;
        $this->responsable        = null;
        $this->fecha              = '';
        $this->municipio_id       = '';
        $this->parroquia_id       = '';
        $this->comuna_id          = '';
        $this->sector_id          = '';
        $this->parroquiasFiltradas = [];
        $this->comunasFiltradas    = [];
        $this->sectoresFiltrados   = [];

        $this->venta_lina_nutrivida = '0';
        $this->antrometria          = '0';
        $this->tipo_a               = null;
        $this->tipo_b               = null;
        $this->tipo_a_plus          = null;
        $this->campana4s            = '0';
        $this->tema_tratado         = null;
        // Condición
        $this->embarazada      = '0';
        $this->mujer_lactante  = '0';
        $this->menor_72_meses  = '0';
        $this->escolar         = '0';
        $this->adolescente     = '0';
        $this->adulto          = '0';
        $this->adulto_mayor    = '0';
        $this->encamado        = '0';
        $this->discapacidad    = '0';
        // Condición (vista)
        $this->view_embarazada      = '0';
        $this->view_mujer_lactante  = '0';
        $this->view_menor_72_meses  = '0';
        $this->view_escolar         = '0';
        $this->view_adolescente     = '0';
        $this->view_adulto          = '0';
        $this->view_adulto_mayor    = '0';
        $this->view_encamado        = '0';
        $this->view_discapacidad    = '0';

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
        $url = route('admin.actividades.feria.pdf', [
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
        $this->graphMonth        = (string) now()->month;
        $this->graphAno          = (string) now()->year;
        $this->isGraphModalOpen  = true;
    }

    public function closeGraphModal(): void
    {
        $this->isGraphModalOpen = false;
    }

    public function viewGraphs(): void
    {
        $url = route('admin.actividades.feria.graficos', [
            'mes'          => $this->graphMonth,
            'año'          => $this->graphAno,
            'municipio_id' => $this->graphMunicipioId,
        ]);

        $this->redirect($url, navigate: true);
    }

    public function cargarDatosGraficos(): void
    {
        $mes  = (int) ($this->graphMonth ?? now()->month);
        $año  = (int) ($this->graphAno   ?? now()->year);
        $munId = $this->graphMunicipioId;

        $queryBase = FeriaCampo::query()
            ->join('sectores',   'feria_campos.sector_id',        '=', 'sectores.id')
            ->join('comunas',     'sectores.comuna_id',           '=', 'comunas.id')
            ->join('parroquias',  'comunas.parroquia_id',         '=', 'parroquias.id')
            ->whereYear('feria_campos.fecha', $año)
            ->whereMonth('feria_campos.fecha', $mes)
            ->when($munId, fn($q) => $q->where('parroquias.municipio_id', $munId));

        // KPIs específicos de Feria de Campo
        $totales = (clone $queryBase)->selectRaw('
            COUNT(feria_campos.id) as total_ferias,
            COALESCE(SUM(feria_campos.tipo_a), 0) as total_tipo_a,
            COALESCE(SUM(feria_campos.tipo_b), 0) as total_tipo_b,
            COALESCE(SUM(feria_campos.tipo_a_plus), 0) as total_tipo_a_plus,
            SUM(CASE WHEN feria_campos.antrometria = 1 THEN 1 ELSE 0 END) as con_antrometria,
            SUM(CASE WHEN feria_campos.venta_lina_nutrivida = 1 THEN 1 ELSE 0 END) as con_venta,
            SUM(CASE WHEN feria_campos.campana4s = 1 THEN 1 ELSE 0 END) as con_campana
        ')->first();

        $this->graphKpis = [
            'total_ferias'      => $totales->total_ferias,
            'total_tipo_a'      => $totales->total_tipo_a,
            'total_tipo_b'      => $totales->total_tipo_b,
            'total_tipo_a_plus' => $totales->total_tipo_a_plus,
            'con_antrometria'   => $totales->con_antrometria,
            'con_venta'         => $totales->con_venta,
            'con_campana'       => $totales->con_campana,
            // Alias for generic dashboard view
            'total_cantidad'    => $totales->total_tipo_a + $totales->total_tipo_b + $totales->total_tipo_a_plus,
            'total_registros'   => $totales->total_ferias,
            'promedio_diario'   => $totales->total_ferias > 0
                ? round($totales->total_ferias / max(1, now()->daysInMonth), 1)
                : 0,
        ];

        // Distribución por Parroquia
        $this->graphParroquias = (clone $queryBase)
            ->select('parroquias.nombre', DB::raw('COUNT(feria_campos.id) as total'))
            ->groupBy('parroquias.id', 'parroquias.nombre')
            ->orderByDesc('total')->get()->toArray();

        // Distribución por Comuna
        $this->graphComunas = (clone $queryBase)
            ->select('comunas.nombre', DB::raw('COUNT(feria_campos.id) as total'))
            ->groupBy('comunas.id', 'comunas.nombre')
            ->orderByDesc('total')->get()->toArray();

        // Distribución por Sector
        $this->graphSectores = (clone $queryBase)
            ->select('sectores.nombre', DB::raw('COUNT(feria_campos.id) as total'))
            ->groupBy('sectores.id', 'sectores.nombre')
            ->orderByDesc('total')->get()->toArray();

        // Evolución por día
        $this->graphDias = (clone $queryBase)
            ->select(DB::raw('DAY(feria_campos.fecha) as dia'), DB::raw('COUNT(feria_campos.id) as total'))
            ->groupBy(DB::raw('DAY(feria_campos.fecha)'))
            ->orderBy('dia')->get()->toArray();

        // Arrays para Radar y Polar
        $this->graphServicios = [
            $totales->con_venta,
            $totales->con_antrometria,
            $totales->con_campana
        ];

        $this->graphTipologia = [
            ['nombre' => 'Tipo A', 'total' => $totales->total_tipo_a],
            ['nombre' => 'Tipo B', 'total' => $totales->total_tipo_b],
            ['nombre' => 'Tipo A+', 'total' => $totales->total_tipo_a_plus]
        ];

        // Antropometría: Tipo A, B, A+
        $this->graphAntrometria = (clone $queryBase)
            ->selectRaw('
                parroquias.nombre,
                COALESCE(SUM(feria_campos.tipo_a), 0) as tipo_a,
                COALESCE(SUM(feria_campos.tipo_b), 0) as tipo_b,
                COALESCE(SUM(feria_campos.tipo_a_plus), 0) as tipo_a_plus
            ')
            ->groupBy('parroquias.id', 'parroquias.nombre')
            ->orderByDesc('tipo_a')->get()->toArray();

        // Distribución por Condición de la Población
        $condicionTotales = (clone $queryBase)->selectRaw('
            COALESCE(SUM(feria_campos.embarazada), 0)     as embarazada,
            COALESCE(SUM(feria_campos.mujer_lactante), 0) as mujer_lactante,
            COALESCE(SUM(feria_campos.menor_72_meses), 0) as menor_72_meses,
            COALESCE(SUM(feria_campos.escolar), 0)        as escolar,
            COALESCE(SUM(feria_campos.adolescente), 0)    as adolescente,
            COALESCE(SUM(feria_campos.adulto), 0)         as adulto,
            COALESCE(SUM(feria_campos.adulto_mayor), 0)   as adulto_mayor,
            COALESCE(SUM(feria_campos.encamado), 0)       as encamado,
            COALESCE(SUM(feria_campos.discapacidad), 0)   as discapacidad
        ')->first();

        $this->graphCondicion = [
            ['nombre' => 'Embarazada',     'total' => (int)($condicionTotales->embarazada ?? 0),     'color' => '#ec4899'],
            ['nombre' => 'M. Lactante',    'total' => (int)($condicionTotales->mujer_lactante ?? 0), 'color' => '#f43f5e'],
            ['nombre' => '< 72 meses',     'total' => (int)($condicionTotales->menor_72_meses ?? 0), 'color' => '#f97316'],
            ['nombre' => 'Escolar',        'total' => (int)($condicionTotales->escolar ?? 0),        'color' => '#eab308'],
            ['nombre' => 'Adolescente',    'total' => (int)($condicionTotales->adolescente ?? 0),    'color' => '#22c55e'],
            ['nombre' => 'Adulto',         'total' => (int)($condicionTotales->adulto ?? 0),         'color' => '#6366f1'],
            ['nombre' => 'Adulto Mayor',   'total' => (int)($condicionTotales->adulto_mayor ?? 0),   'color' => '#8b5cf6'],
            ['nombre' => 'Encamado',       'total' => (int)($condicionTotales->encamado ?? 0),       'color' => '#0ea5e9'],
            ['nombre' => 'Discapacidad',   'total' => (int)($condicionTotales->discapacidad ?? 0),   'color' => '#14b8a6'],
        ];
    }

    // ── Render ────────────────────────────────────────────────────────────────

    public function mount(): void
    {
        if (request()->routeIs('admin.actividades.feria.graficos')) {
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

        $metrics = Cache::rememberForever('feria_campo_metrics', function () use ($now) {
            $totalAnual  = FeriaCampo::whereYear('fecha', $now->year)->count();
            $totalMes    = FeriaCampo::whereYear('fecha', $now->year)->whereMonth('fecha', $now->month)->count();

            $startOfWeek = $now->copy()->startOfWeek();
            $endOfWeek   = $now->copy()->endOfWeek();
            $totalSemana = FeriaCampo::whereBetween('fecha', [$startOfWeek, $endOfWeek])->count();

            $registrosMes = $totalMes;

            $municipiosConTotales = Municipio::query()
                ->select('municipios.*')
                ->addSelect([
                    'total_anual' => DB::table('feria_campos')
                        ->join('sectores', 'feria_campos.sector_id', '=', 'sectores.id')
                        ->join('comunas', 'sectores.comuna_id', '=', 'comunas.id')
                        ->join('parroquias', 'comunas.parroquia_id', '=', 'parroquias.id')
                        ->whereColumn('parroquias.municipio_id', 'municipios.id')
                        ->whereYear('feria_campos.fecha', $now->year)
                        ->selectRaw('COUNT(*)'),

                    'total_mes' => DB::table('feria_campos')
                        ->join('sectores', 'feria_campos.sector_id', '=', 'sectores.id')
                        ->join('comunas', 'sectores.comuna_id', '=', 'comunas.id')
                        ->join('parroquias', 'comunas.parroquia_id', '=', 'parroquias.id')
                        ->whereColumn('parroquias.municipio_id', 'municipios.id')
                        ->whereYear('feria_campos.fecha', $now->year)
                        ->whereMonth('feria_campos.fecha', $now->month)
                        ->selectRaw('COUNT(*)'),

                    'total_semana' => DB::table('feria_campos')
                        ->join('sectores', 'feria_campos.sector_id', '=', 'sectores.id')
                        ->join('comunas', 'sectores.comuna_id', '=', 'comunas.id')
                        ->join('parroquias', 'comunas.parroquia_id', '=', 'parroquias.id')
                        ->whereColumn('parroquias.municipio_id', 'municipios.id')
                        ->whereBetween('feria_campos.fecha', [$startOfWeek, $endOfWeek])
                        ->selectRaw('COUNT(*)'),

                    'abordajes_mes_count' => DB::table('feria_campos')
                        ->join('sectores', 'feria_campos.sector_id', '=', 'sectores.id')
                        ->join('comunas', 'sectores.comuna_id', '=', 'comunas.id')
                        ->join('parroquias', 'comunas.parroquia_id', '=', 'parroquias.id')
                        ->whereColumn('parroquias.municipio_id', 'municipios.id')
                        ->whereYear('feria_campos.fecha', $now->year)
                        ->whereMonth('feria_campos.fecha', $now->month)
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

        $registros = FeriaCampo::query()
            ->select('feria_campos.*')
            ->join('sectores',   'feria_campos.sector_id',    '=', 'sectores.id')
            ->join('comunas',     'sectores.comuna_id',     '=', 'comunas.id')
            ->join('parroquias',  'comunas.parroquia_id',   '=', 'parroquias.id')
            ->join('municipios',  'parroquias.municipio_id', '=', 'municipios.id')
            ->when($this->search, function ($q) {
                $term = '%' . $this->search . '%';
                $q->where(function ($q1) use ($term) {
                    $q1->where('feria_campos.responsable', 'like', $term)
                       ->orWhere('feria_campos.observacion', 'like', $term)
                       ->orWhere('municipios.nombre',   'like', $term)
                       ->orWhere('parroquias.nombre',   'like', $term)
                       ->orWhere('comunas.nombre',      'like', $term)
                       ->orWhere('sectores.nombre',     'like', $term);
                });
            })
            ->when($this->dateFrom, fn($q) => $q->whereDate('feria_campos.fecha', '>=', $this->dateFrom))
            ->when($this->dateTo,   fn($q) => $q->whereDate('feria_campos.fecha', '<=', $this->dateTo))
            ->with(['sector.comuna.parroquia.municipio'])
            ->orderBy('feria_campos.fecha', $this->sortDirection)
            ->paginate(10);

        if ($this->isGraphView) {
            $mesesNombres = [
                1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
                5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
                9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
            ];
            return view('livewire.actividades.feria_campo.graficos-index', [
                'nombreMes'    => $mesesNombres[(int)$this->graphMonth] ?? 'Desconocido',
                'municipios'   => Municipio::orderBy('nombre')->get(),
            ]);
        }

        return view('livewire.actividades.feria_campo.feria_campo-index', [
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

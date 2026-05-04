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
use Livewire\Component;
use Livewire\WithPagination;

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

    // ── Filtros en cascada ────────────────────────────────────────────────────
    public $parroquiasFiltradas = [];
    public $comunasFiltradas    = [];
    public $sectoresFiltrados   = [];

    // ── Control de modales ────────────────────────────────────────────────────
    public bool $isModalOpen     = false;
    public bool $isViewModalOpen = false;

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

        $this->resetValidation();
    }

    // ── Render ────────────────────────────────────────────────────────────────

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

    public function openReportModal($municipioId, $type)
    {
        // Placeholder para futura implementación
    }
}

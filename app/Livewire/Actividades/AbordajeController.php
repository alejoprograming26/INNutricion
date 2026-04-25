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

    // ── Filtros en cascada ────────────────────────────────────────────────────
    public $parroquiasFiltradas = [];
    public $comunasFiltradas    = [];
    public $sectoresFiltrados   = [];

    // ── Control de modales ────────────────────────────────────────────────────
    public bool $isModalOpen     = false;
    public bool $isViewModalOpen = false;

    // ── Datos del modal "Ver" ─────────────────────────────────────────────────
    public ?string $view_observacion = null;
    public string  $view_fecha       = '';
    public string  $view_municipio   = '';
    public string  $view_parroquia   = '';
    public string  $view_comuna      = '';
    public string  $view_sector      = '';
    public string  $view_cantidad    = '';

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
            'fecha'        => 'required|date',
            'municipio_id' => 'required|exists:municipios,id',
            'parroquia_id' => 'required|exists:parroquias,id',
            'comuna_id'    => 'required|exists:comunas,id',
            'sector_id'    => 'required|exists:sectores,id',
            'cantidad'     => 'required|integer|min:0',
        ], [
            'fecha.required'        => 'La fecha es obligatoria.',
            'municipio_id.required' => 'Selecciona un municipio.',
            'parroquia_id.required' => 'Selecciona una parroquia.',
            'comuna_id.required'    => 'Selecciona una comuna.',
            'sector_id.required'    => 'Selecciona un sector.',
            'cantidad.required'     => 'La cantidad es obligatoria.',
        ]);

        $data = [
            'observacion'  => $this->observacion ? mb_strtoupper(trim($this->observacion), 'UTF-8') : null,
            'fecha'        => $this->fecha,
            'municipio_id' => $this->municipio_id,
            'parroquia_id' => $this->parroquia_id,
            'comuna_id'    => $this->comuna_id,
            'sector_id'    => $this->sector_id,
            'cantidad'     => (int) $this->cantidad,
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
        $a = Abordaje::with(['municipio', 'parroquia', 'comuna', 'sector'])->findOrFail($id);

        $this->abordaje_id  = $a->id;
        $this->observacion  = $a->observacion;
        $this->fecha        = Carbon::parse($a->fecha)->format('Y-m-d');
        $this->municipio_id = (string) $a->municipio_id;
        $this->parroquia_id = (string) $a->parroquia_id;
        $this->comuna_id    = (string) $a->comuna_id;
        $this->sector_id    = (string) $a->sector_id;
        $this->cantidad     = (string) $a->cantidad;

        // Cargar combos en cascada
        $this->parroquiasFiltradas = Parroquia::where('municipio_id', $this->municipio_id)->orderBy('nombre')->get();
        $this->comunasFiltradas    = Comuna::where('parroquia_id', $this->parroquia_id)->orderBy('nombre')->get();
        $this->sectoresFiltrados   = Sector::where('comuna_id', $this->comuna_id)->orderBy('nombre')->get();

        $this->isModalOpen = true;
    }

    public function show(int $id): void
    {
        $a = Abordaje::with(['municipio', 'parroquia', 'comuna', 'sector'])->findOrFail($id);

        $this->view_observacion = $a->observacion;
        $this->view_fecha       = Carbon::parse($a->fecha)->format('d/m/Y');
        $this->view_municipio   = $a->municipio->nombre;
        $this->view_parroquia   = $a->parroquia->nombre;
        $this->view_comuna      = $a->comuna->nombre;
        $this->view_sector      = $a->sector->nombre;
        $this->view_cantidad    = (string) $a->cantidad;

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
        $this->resetValidation();
    }

    // ── Render ────────────────────────────────────────────────────────────────

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

            // Distribución por municipio
            $municipiosConTotales = Municipio::query()
                ->withSum(['abordajes as total_anual' => function ($q) use ($now) {
                    $q->whereYear('fecha', $now->year);
                }], 'cantidad')
                ->withSum(['abordajes as total_mes' => function ($q) use ($now) {
                    $q->whereYear('fecha', $now->year)->whereMonth('fecha', $now->month);
                }], 'cantidad')
                ->withSum(['abordajes as total_semana' => function ($q) use ($startOfWeek, $endOfWeek) {
                    $q->whereBetween('fecha', [$startOfWeek, $endOfWeek]);
                }], 'cantidad')
                ->withCount(['abordajes as abordajes_mes_count' => function ($q) use ($now) {
                    $q->whereYear('fecha', $now->year)->whereMonth('fecha', $now->month);
                }])
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
            ->leftJoin('municipios', 'abordajes.municipio_id', '=', 'municipios.id')
            ->leftJoin('parroquias', 'abordajes.parroquia_id', '=', 'parroquias.id')
            ->leftJoin('comunas',   'abordajes.comuna_id',    '=', 'comunas.id')
            ->leftJoin('sectores',  'abordajes.sector_id',    '=', 'sectores.id')
            ->when($this->search, function ($q) {
                $term = '%' . $this->search . '%';
                $q->where(function ($q1) use ($term) {
                    $q1->where('abordajes.observacion', 'like', $term)
                       ->orWhere('municipios.nombre',   'like', $term)
                       ->orWhere('parroquias.nombre',   'like', $term)
                       ->orWhere('comunas.nombre',      'like', $term)
                       ->orWhere('sectores.nombre',     'like', $term);
                });
            })
            ->when($this->dateFrom, fn($q) => $q->whereDate('abordajes.fecha', '>=', $this->dateFrom))
            ->when($this->dateTo,   fn($q) => $q->whereDate('abordajes.fecha', '<=', $this->dateTo))
            ->with(['municipio', 'parroquia', 'comuna', 'sector'])
            ->orderBy('abordajes.fecha', $this->sortDirection)
            ->paginate(10);

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

    public function openReportModal($municipioId, $type)
    {
        // Placeholder para futura implementación
    }
}

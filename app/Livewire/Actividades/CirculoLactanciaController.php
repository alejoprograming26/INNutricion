<?php

namespace App\Livewire\Actividades;

use App\Models\CirculoLactancia;
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
class CirculoLactanciaController extends Component
{
    use WithPagination;

    // ── Búsqueda y Filtros ────────────────────────────────────────────────────
    public string $search = '';
    public string $dateFrom = '';
    public string $dateTo = '';
    public string $sortDirection = 'desc';

    // ── Campos del formulario ─────────────────────────────────────────────────
    public ?int    $circulo_id   = null;
    public ?string $observacion  = null;
    public ?string $responsable  = null;
    public string  $fecha        = '';
    public string  $municipio_id = '';
    public string  $parroquia_id = '';
    public string  $comuna_id    = '';
    public string  $sector_id    = '';
    public string  $nombre_grupo = '';
    public string  $cantidad     = '';

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

    // ── Datos del modal "Ver" ─────────────────────────────────────────────────
    public ?string $view_observacion  = null;
    public ?string $view_responsable  = null;
    public string  $view_fecha        = '';
    public string  $view_municipio    = '';
    public string  $view_parroquia    = '';
    public string  $view_comuna       = '';
    public string  $view_sector       = '';
    public string  $view_nombre_grupo = '';
    public string  $view_cantidad     = '';

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
            'nombre_grupo' => 'required|string|max:255',
            'cantidad'     => 'required|integer|min:1',
        ], [
            'responsable.required'  => 'El responsable es obligatorio.',
            'fecha.required'        => 'La fecha es obligatoria.',
            'sector_id.required'    => 'Selecciona un sector.',
            'nombre_grupo.required' => 'El nombre del grupo es obligatorio.',
            'cantidad.required'     => 'La cantidad es obligatoria.',
        ]);

        $data = [
            'observacion'  => $this->observacion ? mb_strtoupper(trim($this->observacion), 'UTF-8') : null,
            'responsable'  => mb_strtoupper(trim($this->responsable), 'UTF-8'),
            'fecha'        => $this->fecha,
            'sector_id'    => $this->sector_id,
            'nombre_grupo' => mb_strtoupper(trim($this->nombre_grupo), 'UTF-8'),
            'cantidad'     => (int) $this->cantidad,
        ];

        if ($this->circulo_id) {
            CirculoLactancia::findOrFail($this->circulo_id)->update($data);
            $this->dispatch('swal', ['icon' => 'success', 'title' => 'Registro actualizado exitosamente.']);
        } else {
            CirculoLactancia::create($data);
            $this->dispatch('swal', ['icon' => 'success', 'title' => 'Registro creado exitosamente.']);
        }

        $this->closeModal();
    }

    public function edit(int $id): void
    {
        $this->resetInputFields();
        $c = CirculoLactancia::with(['sector.comuna.parroquia.municipio'])->findOrFail($id);

        $this->circulo_id   = $c->id;
        $this->observacion  = $c->observacion;
        $this->responsable  = $c->responsable;
        $this->fecha        = Carbon::parse($c->fecha)->format('Y-m-d');

        $this->sector_id    = (string) $c->sector_id;
        $this->comuna_id    = (string) $c->sector->comuna_id;
        $this->parroquia_id = (string) $c->sector->comuna->parroquia_id;
        $this->municipio_id = (string) $c->sector->comuna->parroquia->municipio_id;

        $this->nombre_grupo = $c->nombre_grupo;
        $this->cantidad     = (string) $c->cantidad;

        // Cargar combos en cascada
        $this->parroquiasFiltradas = Parroquia::where('municipio_id', $this->municipio_id)->orderBy('nombre')->get();
        $this->comunasFiltradas    = Comuna::where('parroquia_id', $this->parroquia_id)->orderBy('nombre')->get();
        $this->sectoresFiltrados   = Sector::where('comuna_id', $this->comuna_id)->orderBy('nombre')->get();

        $this->isModalOpen = true;
    }

    public function show(int $id): void
    {
        $c = CirculoLactancia::with(['sector.comuna.parroquia.municipio'])->findOrFail($id);

        $this->view_observacion  = $c->observacion;
        $this->view_responsable  = $c->responsable;
        $this->view_fecha        = Carbon::parse($c->fecha)->format('d/m/Y');
        $this->view_municipio    = $c->sector->comuna->parroquia->municipio->nombre;
        $this->view_parroquia    = $c->sector->comuna->parroquia->nombre;
        $this->view_comuna       = $c->sector->comuna->nombre;
        $this->view_sector       = $c->sector->nombre;
        $this->view_nombre_grupo = $c->nombre_grupo;
        $this->view_cantidad     = (string) $c->cantidad;

        $this->isViewModalOpen = true;
    }

    public function delete(int $id): void
    {
        CirculoLactancia::findOrFail($id)->delete();
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
        $this->circulo_id         = null;
        $this->observacion        = null;
        $this->responsable        = null;
        $this->fecha              = '';
        $this->municipio_id       = '';
        $this->parroquia_id       = '';
        $this->comuna_id          = '';
        $this->sector_id          = '';
        $this->nombre_grupo       = '';
        $this->cantidad           = '';
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
        $url = route('admin.actividades.circulo.pdf', [
            'mes' => $this->reportMonth,
            'año' => $this->reportYear,
            'municipio_id' => $this->reportMunicipioId
        ]);

        $this->dispatch('open-url-in-new-tab', url: $url);
        $this->closeReportModal();
    }

    // ── Render ────────────────────────────────────────────────────────────────

    public function render()
    {
        $now = now();

        $metrics = Cache::rememberForever('circulo_lactancia_metrics', function () use ($now) {
            $totalAnual  = CirculoLactancia::whereYear('fecha', $now->year)->sum('cantidad');
            $totalMes    = CirculoLactancia::whereYear('fecha', $now->year)->whereMonth('fecha', $now->month)->sum('cantidad');

            $startOfWeek = $now->copy()->startOfWeek();
            $endOfWeek   = $now->copy()->endOfWeek();
            $totalSemana = CirculoLactancia::whereBetween('fecha', [$startOfWeek, $endOfWeek])->sum('cantidad');

            $registrosMes = CirculoLactancia::whereYear('fecha', $now->year)->whereMonth('fecha', $now->month)->count();

            $municipiosConTotales = Municipio::query()
                ->select('municipios.*')
                ->addSelect([
                    'total_anual' => DB::table('circulo_lactancias')
                        ->join('sectores', 'circulo_lactancias.sector_id', '=', 'sectores.id')
                        ->join('comunas', 'sectores.comuna_id', '=', 'comunas.id')
                        ->join('parroquias', 'comunas.parroquia_id', '=', 'parroquias.id')
                        ->whereColumn('parroquias.municipio_id', 'municipios.id')
                        ->whereYear('circulo_lactancias.fecha', $now->year)
                        ->selectRaw('COALESCE(SUM(cantidad), 0)'),

                    'total_mes' => DB::table('circulo_lactancias')
                        ->join('sectores', 'circulo_lactancias.sector_id', '=', 'sectores.id')
                        ->join('comunas', 'sectores.comuna_id', '=', 'comunas.id')
                        ->join('parroquias', 'comunas.parroquia_id', '=', 'parroquias.id')
                        ->whereColumn('parroquias.municipio_id', 'municipios.id')
                        ->whereYear('circulo_lactancias.fecha', $now->year)
                        ->whereMonth('circulo_lactancias.fecha', $now->month)
                        ->selectRaw('COALESCE(SUM(cantidad), 0)'),

                    'total_semana' => DB::table('circulo_lactancias')
                        ->join('sectores', 'circulo_lactancias.sector_id', '=', 'sectores.id')
                        ->join('comunas', 'sectores.comuna_id', '=', 'comunas.id')
                        ->join('parroquias', 'comunas.parroquia_id', '=', 'parroquias.id')
                        ->whereColumn('parroquias.municipio_id', 'municipios.id')
                        ->whereBetween('circulo_lactancias.fecha', [$startOfWeek, $endOfWeek])
                        ->selectRaw('COALESCE(SUM(cantidad), 0)'),

                    'abordajes_mes_count' => DB::table('circulo_lactancias')
                        ->join('sectores', 'circulo_lactancias.sector_id', '=', 'sectores.id')
                        ->join('comunas', 'sectores.comuna_id', '=', 'comunas.id')
                        ->join('parroquias', 'comunas.parroquia_id', '=', 'parroquias.id')
                        ->whereColumn('parroquias.municipio_id', 'municipios.id')
                        ->whereYear('circulo_lactancias.fecha', $now->year)
                        ->whereMonth('circulo_lactancias.fecha', $now->month)
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

        $registros = CirculoLactancia::query()
            ->select('circulo_lactancias.*')
            ->join('sectores',   'circulo_lactancias.sector_id',    '=', 'sectores.id')
            ->join('comunas',     'sectores.comuna_id',     '=', 'comunas.id')
            ->join('parroquias',  'comunas.parroquia_id',   '=', 'parroquias.id')
            ->join('municipios',  'parroquias.municipio_id', '=', 'municipios.id')
            ->when($this->search, function ($q) {
                $term = '%' . $this->search . '%';
                $q->where(function ($q1) use ($term) {
                    $q1->where('circulo_lactancias.nombre_grupo', 'like', $term)
                       ->orWhere('circulo_lactancias.responsable', 'like', $term)
                       ->orWhere('circulo_lactancias.observacion', 'like', $term)
                       ->orWhere('municipios.nombre',   'like', $term)
                       ->orWhere('parroquias.nombre',   'like', $term)
                       ->orWhere('comunas.nombre',      'like', $term)
                       ->orWhere('sectores.nombre',     'like', $term);
                });
            })
            ->when($this->dateFrom, fn($q) => $q->whereDate('circulo_lactancias.fecha', '>=', $this->dateFrom))
            ->when($this->dateTo,   fn($q) => $q->whereDate('circulo_lactancias.fecha', '<=', $this->dateTo))
            ->with(['sector.comuna.parroquia.municipio'])
            ->orderBy('circulo_lactancias.fecha', $this->sortDirection)
            ->paginate(10);

        return view('livewire.actividades.circulo_lactancia.circulo_lactancia-index', [
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

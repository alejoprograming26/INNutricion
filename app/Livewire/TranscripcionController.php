<?php

namespace App\Livewire;

use App\Models\Comuna;
use App\Models\Municipio;
use App\Models\Parroquia;
use App\Models\Sector;
use App\Models\Transcripcion;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
class TranscripcionController extends Component
{
    use WithPagination;

    public string $tipoActivo = 'VULNERABILIDAD';

    // ── Búsqueda ──────────────────────────────────────────────────────────────
    public string $search = '';

    // ── Campos del formulario ─────────────────────────────────────────────────
    public ?int    $transcripcion_id = null;
    public ?string $observacion      = null;
    public string  $responsable      = '';
    public string  $fecha            = '';
    public string  $tipo             = '';
    public string  $municipio_id     = '';
    public string  $parroquia_id     = '';
    public string  $sector_id        = '';
    public string  $comuna_id        = '';
    public string  $cantidad         = '';
    public ?string $ingreso          = null;
    public ?string $egreso           = null;

    // ── Filtros en cascada ────────────────────────────────────────────────────
    public $parroquiasFiltradas = [];
    public $sectoresFiltrados   = [];
    public $comunasFiltradas    = [];

    // ── Control de modales ────────────────────────────────────────────────────
    public bool $isModalOpen     = false;
    public bool $isViewModalOpen = false;

    // ── Datos del modal "Ver" ─────────────────────────────────────────────────
    public ?string  $view_observacion = null;
    public string  $view_responsable = '';
    public string  $view_fecha       = '';
    public string  $view_tipo        = '';
    public string  $view_municipio   = '';
    public string  $view_parroquia   = '';
    public string  $view_sector      = '';
    public string  $view_comuna      = '';
    public string  $view_cantidad    = '';
    public ?string $view_ingreso     = null;
    public ?string $view_egreso      = null;

    // ── Labels del sidebar ────────────────────────────────────────────────────
    public static array $tipoLabels = [
        'VULNERABILIDAD'   => 'Vulnerabilidad',
        'CPLV'             => 'CPLV',
        'LACTANCIA MATERNA' => 'Lactancia Materna',
        'ENCUESTA DIETARIA' => 'Encuesta Dietaria',
        'MONITOREO DE PRECIO' => 'Monitoreo de Precio',
        'SUGIMA'           => 'SUGIMA',
        'PERINATAL'        => 'Perinatal',
        'PRIMER NIVEL DE ATENCION' => 'Primer Nivel de Atención',
        'DESNUTRICION GRAVE' => 'Desnutrición Grave',
        'CONSULTA'         => 'Consulta',
    ];

    // ── Lifecycle ─────────────────────────────────────────────────────────────

    public function mount(): void
    {
        // Leer el tipo desde el query string de la request actual
        $tipo  = request()->input('tipo', 'VULNERABILIDAD');
        $tipos = Transcripcion::TIPOS;

        $this->tipoActivo = in_array($tipo, $tipos) ? $tipo : $tipos[0];
        $this->tipo       = $this->tipoActivo;
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    // ── Cascada de selects ────────────────────────────────────────────────────

    public function updatedMunicipioId($value): void
    {
        $this->parroquia_id = '';
        $this->sector_id    = '';
        $this->comuna_id    = '';
        $this->sectoresFiltrados  = [];
        $this->comunasFiltradas   = [];

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

    // Eliminado el antiguo updatedSectorId ya que ahora es el último nivel o se movió de lugar.

    // ── CRUD ──────────────────────────────────────────────────────────────────

    public function create(): void
    {
        $this->resetInputFields();
        $this->tipo       = $this->tipoActivo; // Preseleccionar el tipo activo
        $this->fecha      = now()->format('Y-m-d');
        $this->isModalOpen = true;
    }

    public function store(): void
    {
        $esSugima = ($this->tipo === Transcripcion::TIPO_CON_INGRESOS_EGRESOS);

        $rules = [
            'observacion'  => 'nullable|string|max:255',
            'responsable'  => 'required|string|max:255',
            'fecha'        => 'required|date',
            'tipo'         => 'required|in:' . implode(',', Transcripcion::TIPOS),
            'municipio_id' => 'required|exists:municipios,id',
            'parroquia_id' => 'required|exists:parroquias,id',
            'sector_id'    => 'required|exists:sectores,id',
            'comuna_id'    => 'required|exists:comunas,id',
            'cantidad'     => 'required|integer|min:0',
        ];

        if ($esSugima) {
            $rules['ingreso'] = 'required|integer|min:0';
            $rules['egreso']  = 'required|integer|min:0';
        }

        $this->validate($rules, [
            'responsable.required'  => 'El responsable es obligatorio.',
            'fecha.required'        => 'La fecha es obligatoria.',
            'tipo.required'         => 'El tipo es obligatorio.',
            'municipio_id.required' => 'Selecciona un municipio.',
            'parroquia_id.required' => 'Selecciona una parroquia.',
            'sector_id.required'    => 'Selecciona un sector.',
            'comuna_id.required'    => 'Selecciona una comuna.',
            'cantidad.required'     => 'La cantidad es obligatoria.',
            'ingreso.required'      => 'El ingreso es obligatorio para SUGIMA.',
            'egreso.required'       => 'El egreso es obligatorio para SUGIMA.',
        ]);

        $data = [
            'observacion'  => $this->observacion ? mb_strtoupper(trim($this->observacion), 'UTF-8') : null,
            'responsable'  => mb_strtoupper(trim($this->responsable), 'UTF-8'),
            'fecha'        => $this->fecha,
            'tipo'         => $this->tipo,
            'municipio_id' => $this->municipio_id,
            'parroquia_id' => $this->parroquia_id,
            'sector_id'    => $this->sector_id,
            'comuna_id'    => $this->comuna_id,
            'cantidad'     => (int) $this->cantidad,
            'ingreso'      => $esSugima ? (int) $this->ingreso : null,
            'egreso'       => $esSugima ? (int) $this->egreso  : null,
        ];

        if ($this->transcripcion_id) {
            Transcripcion::findOrFail($this->transcripcion_id)->update($data);
            $this->dispatch('swal', ['icon' => 'success', 'title' => 'Transcripción actualizada exitosamente.']);
        } else {
            Transcripcion::create($data);
            $this->dispatch('swal', ['icon' => 'success', 'title' => 'Transcripción creada exitosamente.']);
        }

        Cache::forget('transcripcion_metrics_' . $this->tipoActivo);

        $this->closeModal();
    }

    public function edit(int $id): void
    {
        $this->resetInputFields();
        $t = Transcripcion::with(['municipio','parroquia','sector','comuna'])->findOrFail($id);

        $this->transcripcion_id = $t->id;
        $this->observacion      = $t->observacion;
        $this->responsable      = $t->responsable;
        $this->fecha            = \Illuminate\Support\Carbon::parse($t->fecha)->format('Y-m-d');
        $this->tipo             = $t->tipo;
        $this->municipio_id     = (string) $t->municipio_id;
        $this->parroquia_id     = (string) $t->parroquia_id;
        $this->sector_id        = (string) $t->sector_id;
        $this->comuna_id        = (string) $t->comuna_id;
        $this->cantidad         = (string) $t->cantidad;
        $this->ingreso          = $t->ingreso !== null ? (string) $t->ingreso : null;
        $this->egreso           = $t->egreso  !== null ? (string) $t->egreso  : null;

        // Cargar combos
        $this->parroquiasFiltradas = Parroquia::where('municipio_id', $this->municipio_id)->orderBy('nombre')->get();
        $this->comunasFiltradas    = Comuna::where('parroquia_id', $this->parroquia_id)->orderBy('nombre')->get();
        $this->sectoresFiltrados   = Sector::where('comuna_id', $this->comuna_id)->orderBy('nombre')->get();

        $this->isModalOpen = true;
    }

    public function show(int $id): void
    {
        $t = Transcripcion::with(['municipio','parroquia','sector','comuna'])->findOrFail($id);

        $this->view_observacion = $t->observacion;
        $this->view_responsable = $t->responsable;
        $this->view_fecha       = \Illuminate\Support\Carbon::parse($t->fecha)->format('d/m/Y');
        $this->view_tipo        = $t->tipo;
        $this->view_municipio   = $t->municipio->nombre;
        $this->view_parroquia   = $t->parroquia->nombre;
        $this->view_sector      = $t->sector->nombre;
        $this->view_comuna      = $t->comuna->nombre;
        $this->view_cantidad    = (string) $t->cantidad;
        $this->view_ingreso     = $t->ingreso !== null ? (string) $t->ingreso : null;
        $this->view_egreso      = $t->egreso  !== null ? (string) $t->egreso  : null;

        $this->isViewModalOpen = true;
    }

    public function delete(int $id): void
    {
        Transcripcion::findOrFail($id)->delete();
        Cache::forget('transcripcion_metrics_' . $this->tipoActivo);
        $this->dispatch('swal', ['icon' => 'success', 'title' => 'Transcripción eliminada correctamente.']);
    }

    public function closeModal(): void
    {
        $this->isModalOpen     = false;
        $this->isViewModalOpen = false;
        $this->resetInputFields();
    }

    private function resetInputFields(): void
    {
        $this->transcripcion_id    = null;
        $this->observacion         = null;
        $this->responsable         = '';
        $this->fecha               = '';
        $this->tipo                = $this->tipoActivo;
        $this->municipio_id        = '';
        $this->parroquia_id        = '';
        $this->sector_id           = '';
        $this->comuna_id           = '';
        $this->cantidad            = '';
        $this->ingreso             = null;
        $this->egreso              = null;
        $this->parroquiasFiltradas = [];
        $this->sectoresFiltrados   = [];
        $this->comunasFiltradas    = [];
        // Vista
        $this->view_observacion    = null;
        $this->view_responsable    = '';
        $this->view_fecha          = '';
        $this->view_tipo           = '';
        $this->view_municipio      = '';
        $this->view_parroquia      = '';
        $this->view_sector         = '';
        $this->view_comuna         = '';
        $this->view_cantidad       = '';
        $this->view_ingreso        = null;
        $this->view_egreso         = null;
        $this->resetValidation();
    }

    // ── Render ────────────────────────────────────────────────────────────────

    public function render()
    {
        $now = now();
        
        // Métricas cacheadas para evitar latencia de Supabase en cada pulsación de tecla
        $metrics = Cache::rememberForever('transcripcion_metrics_' . $this->tipoActivo, function () use ($now) {
            $queryBase = Transcripcion::where('tipo', $this->tipoActivo);

            $totalAnual = (clone $queryBase)->whereYear('fecha', $now->year)->sum('cantidad');
            $totalMes   = (clone $queryBase)->whereYear('fecha', $now->year)->whereMonth('fecha', $now->month)->sum('cantidad');
            
            $startOfWeek = $now->copy()->startOfWeek();
            $endOfWeek   = $now->copy()->endOfWeek();
            $totalSemana = (clone $queryBase)->whereBetween('fecha', [$startOfWeek, $endOfWeek])->sum('cantidad');

            $transcripcionesMes = (clone $queryBase)->whereYear('fecha', $now->year)->whereMonth('fecha', $now->month)->count();

            $municipiosConTotales = Municipio::query()
                ->withSum(['transcripciones as total_anual' => function ($q) use ($now) {
                    $q->where('tipo', $this->tipoActivo)->whereYear('fecha', $now->year);
                }], 'cantidad')
                ->withSum(['transcripciones as total_mes' => function ($q) use ($now) {
                    $q->where('tipo', $this->tipoActivo)->whereYear('fecha', $now->year)->whereMonth('fecha', $now->month);
                }], 'cantidad')
                ->withSum(['transcripciones as total_semana' => function ($q) use ($startOfWeek, $endOfWeek) {
                    $q->where('tipo', $this->tipoActivo)->whereBetween('fecha', [$startOfWeek, $endOfWeek]);
                }], 'cantidad')
                ->withCount(['transcripciones as transcripciones_mes_count' => function ($q) use ($now) {
                    $q->where('tipo', $this->tipoActivo)->whereYear('fecha', $now->year)->whereMonth('fecha', $now->month);
                }])
                ->orderBy('nombre')
                ->get();

            return [
                'totalAnual' => $totalAnual,
                'totalMes' => $totalMes,
                'totalSemana' => $totalSemana,
                'transcripcionesMes' => $transcripcionesMes,
                'municipiosConTotales' => $municipiosConTotales,
            ];
        });

        // Base query for the paginated table (this remains dynamic for search)
        $queryBase = Transcripcion::where('tipo', $this->tipoActivo);

        // Paginated records
        $transcripciones = (clone $queryBase)
            ->with(['municipio','parroquia','sector','comuna'])
            ->where(function ($q) {
                $q->where('observacion', 'like', '%' . $this->search . '%')
                  ->orWhere('responsable', 'like', '%' . $this->search . '%')
                  ->orWhereHas('municipio', fn($q2) => $q2->where('nombre', 'like', '%' . $this->search . '%'))
                  ->orWhereHas('parroquia', fn($q2) => $q2->where('nombre', 'like', '%' . $this->search . '%'))
                  ->orWhereHas('sector', fn($q2) => $q2->where('nombre', 'like', '%' . $this->search . '%'))
                  ->orWhereHas('comuna', fn($q2) => $q2->where('nombre', 'like', '%' . $this->search . '%'));
            })
            ->orderByDesc('fecha')
            ->paginate(10);

        return view('livewire.transcripcion.transcripcion-index', [
            'transcripciones'      => $transcripciones,
            'municipios'           => Municipio::orderBy('nombre')->get(),
            'municipiosConTotales' => $metrics['municipiosConTotales'],
            'tipos'                => Transcripcion::TIPOS,
            'tipoLabels'           => self::$tipoLabels,
            'esSugima'             => $this->tipoActivo === Transcripcion::TIPO_CON_INGRESOS_EGRESOS,
            'totalAnual'           => $metrics['totalAnual'],
            'totalMes'             => $metrics['totalMes'],
            'totalSemana'          => $metrics['totalSemana'],
            'transcripcionesMes'   => $metrics['transcripcionesMes'],
        ]);
    }
}

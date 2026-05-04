<?php

namespace App\Livewire;

use App\Models\Transcripcion;
use App\Models\Abordaje;
use App\Models\Escuela4s;
use App\Models\LiderazgoTerritorial;
use App\Models\DiversidadDietaria;
use App\Models\CirculoLactancia;
use App\Models\PlanVulnerabilidad;
use App\Models\FeriaCampo;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

#[Layout('components.layouts.app')]
class CalendarioController extends Component
{
    // Estado del modal de detalles
    public bool $isModalOpen = false;
    public string $fechaSeleccionada = '';
    public $transcripcionesDia = [];
    public $actividadesDia = []; // Nuevo para actividades

    // Modo de visualización: 'transcripciones' o 'actividades'
    public string $viewMode = 'transcripciones';

    // Mes visible en el calendario (para los indicadores)
    public int $mesVisible;
    public int $anioVisible;

    // Colores Hexadecimales para FullCalendar
    public static array $coloresFullCalendar = [
        // Transcripciones
        'VULNERABILIDAD'           => '#f43f5e', // rose
        'CPLV'                     => '#3b82f6', // blue
        'LACTANCIA MATERNA'        => '#ec4899', // pink
        'ENCUESTA DIETARIA'        => '#f59e0b', // amber
        'MONITOREO DE PRECIO'      => '#8b5cf6', // violet
        'SUGIMA'                   => '#84cc16', // lime
        'PERINATAL'                => '#6366f1', // indigo
        'PRIMER NIVEL DE ATENCION' => '#06b6d4', // cyan
        'DESNUTRICION GRAVE'       => '#ef4444', // red
        'CONSULTA'                 => '#10b981', // emerald
        
        // Actividades (Exactamente como sus encabezados)
        'ABORDAJE'              => '#84cc16', // lime
        'ESCUELA 4S'            => '#f59e0b', // amber
        'LIDERAZGO TERRITORIAL' => '#3b82f6', // sky/blue
        'DIVERSIDAD DIETARIA'   => '#d946ef', // fuchsia/purple
        'CIRCULO DE LACTANCIA'  => '#06b6d4', // teal/cyan
        'PLAN VULNERABILIDAD'   => '#f43f5e', // rose/red
        'FERIA DE CAMPO'        => '#6366f1', // indigo/violet
    ];

    // Colores Tailwind para el Modal y los Indicadores
    public static array $coloresTailwind = [
        // Transcripciones
        'VULNERABILIDAD'           => 'bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:text-rose-300',
        'CPLV'                     => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300',
        'LACTANCIA MATERNA'        => 'bg-pink-100 text-pink-700 dark:bg-pink-900/30 dark:text-pink-300',
        'ENCUESTA DIETARIA'        => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-300',
        'MONITOREO DE PRECIO'      => 'bg-violet-100 text-violet-700 dark:bg-violet-900/30 dark:text-violet-300',
        'SUGIMA'                   => 'bg-lime-100 text-lime-700 dark:bg-lime-900/30 dark:text-lime-300',
        'PERINATAL'                => 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-300',
        'PRIMER NIVEL DE ATENCION' => 'bg-cyan-100 text-cyan-700 dark:bg-cyan-900/30 dark:text-cyan-300',
        'DESNUTRICION GRAVE'       => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300',
        'CONSULTA'                 => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300',

        // Actividades
        'ABORDAJE'              => 'bg-lime-100 text-lime-700 dark:bg-lime-900/30 dark:text-lime-300',
        'ESCUELA 4S'            => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-300',
        'LIDERAZGO TERRITORIAL' => 'bg-sky-100 text-sky-700 dark:bg-sky-900/30 dark:text-sky-300',
        'DIVERSIDAD DIETARIA'   => 'bg-fuchsia-100 text-fuchsia-700 dark:bg-fuchsia-900/30 dark:text-fuchsia-300',
        'CIRCULO DE LACTANCIA'  => 'bg-teal-100 text-teal-700 dark:bg-teal-900/30 dark:text-teal-300',
        'PLAN VULNERABILIDAD'   => 'bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:text-rose-300',
        'FERIA DE CAMPO'        => 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-300',
    ];

    // Etiquetas cortas para indicadores
    public static array $etiquetasCortas = [
        'VULNERABILIDAD'   => 'Vulnerabilidad',
        'CPLV'             => 'CPLV',
        'LACTANCIA MATERNA' => 'Lactancia Materna',
        'ENCUESTA DIETARIA' => 'Encuesta Dietaria',
        'MONITOREO DE PRECIO' => 'Monitoreo Precio',
        'SUGIMA'           => 'SUGIMA',
        'PERINATAL'        => 'Perinatal',
        'PRIMER NIVEL DE ATENCION' => '1er Nivel Atención',
        'DESNUTRICION GRAVE' => 'Desnutrición Grave',
        'CONSULTA'         => 'Consulta',

        // Actividades
        'ABORDAJE'              => 'Abordaje',
        'ESCUELA 4S'            => 'Escuela 4S',
        'LIDERAZGO TERRITORIAL' => 'Liderazgo Terr.',
        'DIVERSIDAD DIETARIA'   => 'Diversidad Diet.',
        'CIRCULO DE LACTANCIA'  => 'Círculo Lactancia',
        'PLAN VULNERABILIDAD'   => 'Plan Vulnerab.',
        'FERIA DE CAMPO'        => 'Feria de Campo',
    ];

    // Bordes para indicadores (borde izquierdo colorido)
    public static array $bordeIndicador = [
        'VULNERABILIDAD'   => 'border-l-rose-500',
        'CPLV'             => 'border-l-blue-500',
        'LACTANCIA MATERNA' => 'border-l-pink-500',
        'ENCUESTA DIETARIA' => 'border-l-amber-500',
        'MONITOREO DE PRECIO' => 'border-l-violet-500',
        'SUGIMA'           => 'border-l-lime-500',
        'PERINATAL'        => 'border-l-indigo-500',
        'PRIMER NIVEL DE ATENCION' => 'border-l-cyan-500',
        'DESNUTRICION GRAVE' => 'border-l-red-500',
        'CONSULTA'         => 'border-l-emerald-500',

        // Actividades
        'ABORDAJE'              => 'border-l-emerald-500',
        'ESCUELA 4S'            => 'border-l-blue-500',
        'LIDERAZGO TERRITORIAL' => 'border-l-amber-500',
        'DIVERSIDAD DIETARIA'   => 'border-l-indigo-500',
        'CIRCULO DE LACTANCIA'  => 'border-l-pink-500',
        'PLAN VULNERABILIDAD'   => 'border-l-rose-500',
        'FERIA DE CAMPO'        => 'border-l-lime-500',
    ];

    public function mount(): void
    {
        $this->mesVisible = now()->month;
        $this->anioVisible = now()->year;
    }

    // Llamado desde JS cuando el usuario cambia de mes en FullCalendar
    public function cambiarMesVisible(int $mes, int $anio): void
    {
        $this->mesVisible = $mes;
        $this->anioVisible = $anio;
    }

    public function updatedViewMode(): void
    {
        $this->dispatch('view-mode-updated', eventos: $this->getEventos());
    }

    // Acción desde FullCalendar al hacer click en un evento
    public function abrirDia(string $fechaStr): void
    {
        $this->fechaSeleccionada = Carbon::parse($fechaStr)->format('Y-m-d');
        
        if ($this->viewMode === 'transcripciones') {
            $this->transcripcionesDia = Transcripcion::with(['sector.comuna.parroquia.municipio'])
                ->whereDate('fecha', $this->fechaSeleccionada)
                ->orderBy('tipo')
                ->get();
        } else {
            $this->actividadesDia = $this->getActividadesPorDia($this->fechaSeleccionada);
        }

        $this->isModalOpen = true;
    }

    private function getActividadesPorDia(string $fecha): array
    {
        $modelos = [
            'ABORDAJE'              => Abordaje::class,
            'ESCUELA 4S'            => Escuela4s::class,
            'LIDERAZGO TERRITORIAL' => LiderazgoTerritorial::class,
            'DIVERSIDAD DIETARIA'   => DiversidadDietaria::class,
            'CIRCULO DE LACTANCIA'  => CirculoLactancia::class,
            'PLAN VULNERABILIDAD'   => PlanVulnerabilidad::class,
            'FERIA DE CAMPO'        => FeriaCampo::class,
        ];

        $resultados = [];
        foreach ($modelos as $tipo => $modelo) {
            $items = $modelo::with(['sector.comuna.parroquia.municipio'])
                ->whereDate('fecha', $fecha)
                ->get();

            foreach ($items as $item) {
                $resultados[] = [
                    'tipo'        => $tipo,
                    'responsable' => $item->responsable,
                    'municipio'   => $item->sector->comuna->parroquia->municipio->nombre,
                    'sector'      => $item->sector->nombre,
                    'observacion' => $item->observacion,
                    'cantidad'    => $item->cantidad ?? 1, // Si no tiene cantidad, cuenta como 1 evento
                ];
            }
        }

        return $resultados;
    }

    public function closeModal(): void
    {
        $this->isModalOpen = false;
        $this->transcripcionesDia = [];
        $this->actividadesDia = [];
    }

    private function getEventos(): array
    {
        if ($this->viewMode === 'transcripciones') {
            $data = Transcripcion::select('fecha', 'tipo', DB::raw('SUM(cantidad) as total'))
                ->groupBy('fecha', 'tipo')
                ->get();

            $eventos = [];
            foreach ($data as $t) {
                $eventos[] = [
                    'title' => number_format($t->total) . ' ' . $t->tipo,
                    'start' => Carbon::parse($t->fecha)->format('Y-m-d'),
                    'allDay' => true,
                    'backgroundColor' => self::$coloresFullCalendar[$t->tipo] ?? '#6b7280',
                    'borderColor' => 'transparent',
                ];
            }
            return $eventos;
        }

        // Modo Actividades
        $modelos = [
            'ABORDAJE'              => Abordaje::class,
            'ESCUELA 4S'            => Escuela4s::class,
            'LIDERAZGO TERRITORIAL' => LiderazgoTerritorial::class,
            'DIVERSIDAD DIETARIA'   => DiversidadDietaria::class,
            'CIRCULO DE LACTANCIA'  => CirculoLactancia::class,
            'PLAN VULNERABILIDAD'   => PlanVulnerabilidad::class,
            'FERIA DE CAMPO'        => FeriaCampo::class,
        ];

        $eventos = [];
        foreach ($modelos as $tipo => $modelo) {
            $data = $modelo::select('fecha', DB::raw('COUNT(*) as total'))
                ->groupBy('fecha')
                ->get();

            foreach ($data as $t) {
                $eventos[] = [
                    'title' => number_format($t->total) . ' ' . $tipo,
                    'start' => Carbon::parse($t->fecha)->format('Y-m-d'),
                    'allDay' => true,
                    'backgroundColor' => self::$coloresFullCalendar[$tipo] ?? '#6b7280',
                    'borderColor' => 'transparent',
                ];
            }
        }
        return $eventos;
    }

    public function render()
    {
        $eventosFullCalendar = $this->getEventos();

        $inicioMes = Carbon::createFromDate($this->anioVisible, $this->mesVisible, 1)->startOfMonth();
        $finMes = $inicioMes->copy()->endOfMonth();

        if ($this->viewMode === 'transcripciones') {
            $totalesMes = Transcripcion::select('tipo', DB::raw('SUM(cantidad) as total'), DB::raw('COUNT(*) as registros'))
                ->whereBetween('fecha', [$inicioMes->format('Y-m-d'), $finMes->format('Y-m-d')])
                ->groupBy('tipo')
                ->orderByDesc('total')
                ->get();
        } else {
            // Totales para Actividades
            $modelos = [
                'ABORDAJE'              => Abordaje::class,
                'ESCUELA 4S'            => Escuela4s::class,
                'LIDERAZGO TERRITORIAL' => LiderazgoTerritorial::class,
                'DIVERSIDAD DIETARIA'   => DiversidadDietaria::class,
                'CIRCULO DE LACTANCIA'  => CirculoLactancia::class,
                'PLAN VULNERABILIDAD'   => PlanVulnerabilidad::class,
                'FERIA DE CAMPO'        => FeriaCampo::class,
            ];

            $totalesMes = collect();
            foreach ($modelos as $tipo => $modelo) {
                $count = $modelo::whereBetween('fecha', [$inicioMes->format('Y-m-d'), $finMes->format('Y-m-d')])->count();
                if ($count > 0) {
                    $totalesMes->push((object)[
                        'tipo' => $tipo,
                        'total' => $count, // En actividades, el KPI es el conteo de eventos
                        'registros' => $count,
                    ]);
                }
            }
            $totalesMes = $totalesMes->sortByDesc('total');
        }

        $granTotal = $totalesMes->sum('total');
        $totalRegistros = $totalesMes->sum('registros');

        return view('livewire.calendario.calendario-index', [
            'eventosFullCalendar' => $eventosFullCalendar,
            'coloresTailwind'    => self::$coloresTailwind,
            'totalesMes'         => $totalesMes,
            'granTotal'          => $granTotal,
            'totalRegistros'     => $totalRegistros,
            'nombreMesVisible'   => ucfirst($inicioMes->translatedFormat('F Y')),
            'etiquetasCortas'    => self::$etiquetasCortas,
            'bordeIndicador'     => self::$bordeIndicador,
            'coloresHex'         => self::$coloresFullCalendar,
        ]);
    }
}

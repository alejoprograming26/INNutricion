<?php

namespace App\Livewire;

use App\Models\Transcripcion;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Illuminate\Support\Carbon;

#[Layout('components.layouts.app')]
class CalendarioController extends Component
{
    // Estado del modal de detalles
    public bool $isModalOpen = false;
    public string $fechaSeleccionada = '';
    public $transcripcionesDia = [];

    // Mes visible en el calendario (para los indicadores)
    public int $mesVisible;
    public int $anioVisible;

    // Colores Hexadecimales para FullCalendar (usando los mismos de la paleta oficial)
    public static array $coloresFullCalendar = [
        'VULNERABILIDAD'   => '#f43f5e', // rose
        'CPLV'             => '#3b82f6', // blue
        'LACTANCIA MATERNA' => '#ec4899', // pink
        'ENCUESTA DIETARIA' => '#f59e0b', // amber
        'MONITOREO DE PRECIO' => '#8b5cf6', // violet
        'SUGIMA'           => '#84cc16', // lime
        'PERINATAL'        => '#6366f1', // indigo
        'PRIMER NIVEL DE ATENCION' => '#06b6d4', // cyan
        'DESNUTRICION GRAVE' => '#ef4444', // red
        'CONSULTA'         => '#10b981', // emerald
    ];

    // Colores Tailwind para el Modal y los Indicadores
    public static array $coloresTailwind = [
        'VULNERABILIDAD'   => 'bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:text-rose-300',
        'CPLV'             => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300',
        'LACTANCIA MATERNA' => 'bg-pink-100 text-pink-700 dark:bg-pink-900/30 dark:text-pink-300',
        'ENCUESTA DIETARIA' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-300',
        'MONITOREO DE PRECIO' => 'bg-violet-100 text-violet-700 dark:bg-violet-900/30 dark:text-violet-300',
        'SUGIMA'           => 'bg-lime-100 text-lime-700 dark:bg-lime-900/30 dark:text-lime-300',
        'PERINATAL'        => 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-300',
        'PRIMER NIVEL DE ATENCION' => 'bg-cyan-100 text-cyan-700 dark:bg-cyan-900/30 dark:text-cyan-300',
        'DESNUTRICION GRAVE' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300',
        'CONSULTA'         => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300',
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

    // Acción desde FullCalendar al hacer click en un evento
    public function abrirDia(string $fechaStr): void
    {
        $this->fechaSeleccionada = Carbon::parse($fechaStr)->format('Y-m-d');
        
        $this->transcripcionesDia = Transcripcion::with(['municipio', 'parroquia', 'sector', 'comuna'])
            ->whereDate('fecha', $this->fechaSeleccionada)
            ->orderBy('tipo')
            ->get();

        $this->isModalOpen = true;
    }

    public function closeModal(): void
    {
        $this->isModalOpen = false;
        $this->transcripcionesDia = [];
    }

    public function render()
    {
        // Eventos para FullCalendar (todos los meses — la librería se encarga del filtro visual)
        $transcripcionesDb = Transcripcion::select('fecha', 'tipo', \DB::raw('SUM(cantidad) as total'))
            ->groupBy('fecha', 'tipo')
            ->get();

        $eventosFullCalendar = [];
        foreach ($transcripcionesDb as $t) {
            $fechaLimpia = Carbon::parse($t->fecha)->format('Y-m-d');
            $eventosFullCalendar[] = [
                'title' => number_format($t->total) . ' ' . $t->tipo,
                'start' => $fechaLimpia,
                'allDay' => true,
                'backgroundColor' => self::$coloresFullCalendar[$t->tipo] ?? '#6b7280',
                'borderColor' => 'transparent',
            ];
        }

        // Indicadores mensuales: totales por tipo del mes visible
        $inicioMes = Carbon::createFromDate($this->anioVisible, $this->mesVisible, 1)->startOfMonth();
        $finMes = $inicioMes->copy()->endOfMonth();

        $totalesMes = Transcripcion::select('tipo', \DB::raw('SUM(cantidad) as total'), \DB::raw('COUNT(*) as registros'))
            ->whereBetween('fecha', [$inicioMes->format('Y-m-d'), $finMes->format('Y-m-d')])
            ->groupBy('tipo')
            ->orderByDesc('total')
            ->get();

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

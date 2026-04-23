<?php

namespace App\Livewire;

use App\Models\Municipio;
use App\Models\Transcripcion;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class GraficosTranscripcionController extends Component
{
    public $mes;
    public $año;
    public $tipo;
    public $municipioId;
    public $municipioNombre;

    // Datos procesados
    public $kpis = [];
    public $datosParroquias = [];
    public $datosComunas = [];
    public $datosSectores = [];
    public $datosDias = [];
    
    public $esSugima = false;
    public $colorThemaHex = '#84cc16';
    public $colorThemaTw = 'lime';

    public function mount()
    {
        $this->mes = request()->query('mes', now()->month);
        $this->año = request()->query('año', now()->year);
        $this->tipo = request()->query('tipo', 'VULNERABILIDAD');
        $this->municipioId = request()->query('municipio_id');
        
        $this->esSugima = $this->tipo === Transcripcion::TIPO_CON_INGRESOS_EGRESOS;

        if ($this->municipioId) {
            $mun = Municipio::find($this->municipioId);
            $this->municipioNombre = $mun ? $mun->nombre : 'Todos';
        } else {
            $this->municipioNombre = 'Todos';
        }

        $coloresTipo = [
            'VULNERABILIDAD' => ['hex' => '#f43f5e', 'tw' => 'rose'], // rose-500
            'CPLV' => ['hex' => '#3b82f6', 'tw' => 'blue'], // blue-500
            'LACTANCIA MATERNA' => ['hex' => '#ec4899', 'tw' => 'pink'], // pink-500
            'ENCUESTA DIETARIA' => ['hex' => '#f59e0b', 'tw' => 'amber'], // amber-500
            'MONITOREO DE PRECIO' => ['hex' => '#8b5cf6', 'tw' => 'violet'], // violet-500
            'SUGIMA' => ['hex' => '#84cc16', 'tw' => 'lime'], // lime-500
            'PERINATAL' => ['hex' => '#6366f1', 'tw' => 'indigo'], // indigo-500
            'PRIMER NIVEL DE ATENCION' => ['hex' => '#06b6d4', 'tw' => 'cyan'], // cyan-500
            'DESNUTRICION GRAVE' => ['hex' => '#dc2626', 'tw' => 'red'], // red-600
            'CONSULTA' => ['hex' => '#10b981', 'tw' => 'emerald'], // emerald-500
        ];
        
        $this->colorThemaHex = $coloresTipo[$this->tipo]['hex'] ?? '#84cc16';
        $this->colorThemaTw = $coloresTipo[$this->tipo]['tw'] ?? 'lime';

        $this->cargarDatos();
    }

    public function cargarDatos()
    {
        $queryBase = Transcripcion::where('transcripciones.tipo', $this->tipo)
            ->whereYear('transcripciones.fecha', $this->año)
            ->whereMonth('transcripciones.fecha', $this->mes)
            ->when($this->municipioId, function ($q) {
                $q->where('transcripciones.municipio_id', $this->municipioId);
            });

        // 1. KPIs
        $totales = (clone $queryBase)->selectRaw('
            COALESCE(SUM(cantidad), 0) as total_cantidad, 
            COUNT(*) as total_registros,
            COALESCE(SUM(ingreso), 0) as total_ingreso,
            COALESCE(SUM(egreso), 0) as total_egreso
        ')->first();

        // Evitamos división por cero para el promedio diario
        $diasEnMes = \Carbon\Carbon::createFromDate($this->año, $this->mes, 1)->daysInMonth;
        $promedioDiario = round($totales->total_cantidad / $diasEnMes, 1);

        $this->kpis = [
            'total_cantidad' => $totales->total_cantidad,
            'total_registros' => $totales->total_registros,
            'promedio_diario' => $promedioDiario,
            'total_ingreso' => $totales->total_ingreso,
            'total_egreso' => $totales->total_egreso,
        ];

        // 2. Parroquias (Doughnut Chart)
        $this->datosParroquias = (clone $queryBase)
            ->join('parroquias', 'transcripciones.parroquia_id', '=', 'parroquias.id')
            ->select('parroquias.nombre', DB::raw('SUM(cantidad) as total'))
            ->groupBy('parroquias.id', 'parroquias.nombre')
            ->orderByDesc('total')
            ->get()
            ->toArray();

        // 3. Comunas (Bar Chart)
        $this->datosComunas = (clone $queryBase)
            ->join('comunas', 'transcripciones.comuna_id', '=', 'comunas.id')
            ->select('comunas.nombre', DB::raw('SUM(cantidad) as total'))
            ->groupBy('comunas.id', 'comunas.nombre')
            ->orderByDesc('total')
            ->get()
            ->toArray();

        // 4. Sectores (Horizontal Bar Chart)
        $this->datosSectores = (clone $queryBase)
            ->join('sectores', 'transcripciones.sector_id', '=', 'sectores.id')
            ->select('sectores.nombre', DB::raw('SUM(cantidad) as total'))
            ->groupBy('sectores.id', 'sectores.nombre')
            ->orderByDesc('total')
            ->get()
            ->toArray();

        // 5. Evolución por Día (Line Chart)
        $this->datosDias = (clone $queryBase)
            ->select(DB::raw('DAY(fecha) as dia'), DB::raw('SUM(cantidad) as total'))
            ->groupBy(DB::raw('DAY(fecha)'))
            ->orderBy('dia')
            ->get()
            ->toArray();
    }

    // Permitir recargar datos si cambian mes/año desde el mismo dashboard
    public function updatedMes() { $this->recargar(); }
    public function updatedAño() { $this->recargar(); }

    public function recargar()
    {
        $this->cargarDatos();
        $this->dispatch('refreshCharts');
    }

    public function render()
    {
        $mesesNombres = [
            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
            5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
            9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
        ];

        return view('livewire.transcripcion.graficos-index', [
            'nombreMes' => $mesesNombres[(int)$this->mes] ?? 'Desconocido'
        ]);
    }
}

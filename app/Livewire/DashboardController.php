<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

#[Layout('components.layouts.app')]
class DashboardController extends Component
{
    public $graphDistribution = [];
    public $graphMonthly = [];
    public $recentActivities = [];
    public $totalAtendidos = 0;
    public $totalRegistros = 0;
    public $municipiosAbordados = 0;
    public $promedioDiario = 0;
    
    // Propiedad para filtrar por año si se requiere en el futuro
    public $year;

    public function mount()
    {
        $this->year = date('Y');
        $this->loadData();
    }

    public function loadData()
    {
        $year = $this->year;

        // Mapeo de actividades y colores usando las versiones Tailwind 500 para máximo contraste
        $actividades = [
            'Abordaje' => ['table' => 'abordajes', 'color' => '#84cc16', 'sum_col' => 'cantidad'],
            'Feria de Campo' => ['table' => 'feria_campos', 'color' => '#6366f1', 'sum_col' => '(COALESCE(tipo_a, 0) + COALESCE(tipo_b, 0) + COALESCE(tipo_a_plus, 0))'],
            'Vulnerabilidad' => ['table' => 'plan_vulnerabilidads', 'color' => '#f43f5e', 'sum_col' => 'total_entregas'],
            'Lactancia' => ['table' => 'circulo_lactancias', 'color' => '#14b8a6', 'sum_col' => 'cantidad'],
            'Escuela 4S' => ['table' => 'escuela4s', 'color' => '#f59e0b', 'sum_col' => '1'],
            'Liderazgo' => ['table' => 'liderazgo_territorials', 'color' => '#0ea5e9', 'sum_col' => 'cantidad'],
            'Div. Dietaria' => ['table' => 'diversidad_dietarias', 'color' => '#d946ef', 'sum_col' => 'cantidad'],
            'Transcripción' => ['table' => 'transcripciones', 'color' => '#8b5cf6', 'sum_col' => 'cantidad'],
        ];

        $distribution = [];
        $monthly = array_fill(1, 12, 0);
        $totalAtendidos = 0;
        $totalRegistros = 0;
        $queries = [];
        $sectorQueries = [];

        foreach ($actividades as $nombre => $cfg) {
            $table = $cfg['table'];
            $sumCol = $cfg['sum_col'];

            // 1. Conteo de registros totales (Operativos)
            $registrosCount = DB::table($table)->whereYear('fecha', $year)->count();
            $totalRegistros += $registrosCount;

            // 2. Distribución por actividad (Suma de personas)
            $totalActividad = DB::table($table)
                ->whereYear('fecha', $year)
                ->selectRaw("SUM($sumCol) as total")
                ->value('total') ?? 0;

            if ($registrosCount > 0) {
                $distribution[] = [
                    'nombre' => $nombre,
                    'total' => (int) $totalActividad,
                    'color' => $cfg['color']
                ];
                $totalAtendidos += $totalActividad;
            }

            // 3. Gráfica mensual (Evolución Anual)
            $monthlyData = DB::table($table)
                ->whereYear('fecha', $year)
                ->selectRaw("MONTH(fecha) as mes, SUM($sumCol) as total")
                ->groupBy('mes')
                ->get();

            foreach ($monthlyData as $row) {
                $monthly[$row->mes] += $row->total;
            }

            // 4. Últimos Registros
            $subtipoRaw = ($table === 'transcripciones') ? 'tipo' : 'NULL';
            $queries[] = DB::table($table)
                ->selectRaw("'$nombre' as tipo, $subtipoRaw as subtipo, fecha, created_at, '{$cfg['color']}' as color")
                ->whereYear('fecha', $year);
                
            // 5. Sectores activos para conteo de municipios
            $sectorQueries[] = DB::table($table)
                ->select('sector_id')
                ->whereYear('fecha', $year);
        }

        // Ordenar distribución de mayor a menor
        usort($distribution, function($a, $b) {
            return $b['total'] <=> $a['total'];
        });
        
        $this->graphDistribution = $distribution;
        $this->totalAtendidos = $totalAtendidos;
        $this->totalRegistros = $totalRegistros;
        
        // Promedio diario (basado en días transcurridos del año si es el año actual, o 365 si es pasado)
        $diasTranscurridos = ($year == date('Y')) ? (date('z') + 1) : 365;
        $this->promedioDiario = $diasTranscurridos > 0 ? round($totalAtendidos / $diasTranscurridos, 1) : 0;
        
        // Conteo de municipios únicos abordados
        $this->municipiosAbordados = 0;
        if (count($sectorQueries) > 0) {
            $unionSectores = array_shift($sectorQueries);
            foreach ($sectorQueries as $q) {
                $unionSectores->union($q);
            }
            
            $this->municipiosAbordados = DB::table('sectores')
                ->joinSub($unionSectores, 'activos', 'sectores.id', '=', 'activos.sector_id')
                ->join('comunas', 'sectores.comuna_id', '=', 'comunas.id')
                ->join('parroquias', 'comunas.parroquia_id', '=', 'parroquias.id')
                ->distinct('parroquias.municipio_id')
                ->count('parroquias.municipio_id');
        }
        
        // Formatear gráfica mensual para Chart.js
        $this->graphMonthly = [];
        $meses = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
        for ($i = 1; $i <= 12; $i++) {
            $this->graphMonthly[] = [
                'mes' => $meses[$i-1],
                'total' => (int) $monthly[$i]
            ];
        }

        // Ejecutar query combinada para el feed reciente
        if (count($queries) > 0) {
            $unionQuery = array_shift($queries);
            foreach ($queries as $q) {
                $unionQuery->unionAll($q);
            }
            $this->recentActivities = DB::query()
                ->fromSub($unionQuery, 'unioned')
                ->orderByDesc('created_at')
                ->limit(6)
                ->get()
                ->toArray();
        }
    }

    public function render()
    {
        return view('livewire.dashboard.dashboard-index')->title('Dashboard Principal');
    }
}

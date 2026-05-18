<?php

namespace App\Http\Controllers;

use App\Models\Abordaje;
use App\Models\CirculoLactancia;
use App\Models\DiversidadDietaria;
use App\Models\Escuela4s;
use App\Models\FeriaCampo;
use App\Models\LiderazgoTerritorial;
use App\Models\PlanVulnerabilidad;
use App\Models\Ajuste;
use App\Models\Municipio;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ReporteActividadController extends Controller
{
    private array $config = [
        'abordaje' => [
            'modelo' => Abordaje::class,
            'titulo' => 'Abordaje Comunitario',
            'color'  => '#84cc16', // Lime
            'sums'   => ['cantidad', 'total_a', 'total_b', 'total_a_plus']
        ],
        'circulo_lactancia' => [
            'modelo' => CirculoLactancia::class,
            'titulo' => 'Círculo de Lactancia',
            'color'  => '#14b8a6', // Teal
            'sums'   => ['cantidad']
        ],
        'diversidad_dietaria' => [
            'modelo' => DiversidadDietaria::class,
            'titulo' => 'Diversidad Dietaria',
            'color'  => '#d946ef', // Fuchsia
            'sums'   => ['cantidad']
        ],
        'escuela4s' => [
            'modelo' => Escuela4s::class,
            'titulo' => 'Escuela 4S',
            'color'  => '#f59e0b', // Amber
            'sums'   => [] // Solo cuenta
        ],
        'feria_campo' => [
            'modelo' => FeriaCampo::class,
            'titulo' => 'Feria de Campo',
            'color'  => '#6366f1', // Indigo
            'sums'   => ['tipo_a', 'tipo_b', 'tipo_a_plus'] // sum de antropometría
        ],
        'liderazgo_territorial' => [
            'modelo' => LiderazgoTerritorial::class,
            'titulo' => 'Liderazgo Territorial',
            'color'  => '#0ea5e9', // Sky
            'sums'   => ['cantidad']
        ],
        'plan_vulnerabilidad' => [
            'modelo' => PlanVulnerabilidad::class,
            'titulo' => 'Plan de Vulnerabilidad',
            'color'  => '#f43f5e', // Rose
            'sums'   => ['total_entregas']
        ]
    ];

    public function descargar(Request $request, string $actividad)
    {
        if (!isset($this->config[$actividad])) {
            abort(404, 'Actividad no encontrada');
        }

        $mes = $request->input('mes', now()->month);
        $año = $request->input('año', now()->year);
        $municipioId = $request->input('municipio_id');

        $meses = [
            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
            5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
            9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
        ];
        $nombreMes = $meses[(int)$mes] ?? 'Desconocido';

        $cfg = $this->config[$actividad];
        $modeloClase = $cfg['modelo'];

        $query = $modeloClase::with(['sector.comuna.parroquia.municipio'])
            ->join('sectores', (new $modeloClase)->getTable() . '.sector_id', '=', 'sectores.id')
            ->join('comunas', 'sectores.comuna_id', '=', 'comunas.id')
            ->join('parroquias', 'comunas.parroquia_id', '=', 'parroquias.id')
            ->whereYear('fecha', $año)
            ->whereMonth('fecha', $mes)
            ->select((new $modeloClase)->getTable() . '.*');

        if ($municipioId) {
            $query->where('parroquias.municipio_id', $municipioId);
        }

        $registros = $query->get();

        $resultadoAgrupado = $this->agruparDatos($registros, $cfg['sums']);

        $datos = [
            'titulo'                => $cfg['titulo'],
            'mes'                   => $nombreMes,
            'año'                   => $año,
            'colorThema'            => $cfg['color'],
            'datosAgrupados'        => $resultadoAgrupado['datos'],
            'totalesGenerales'      => $resultadoAgrupado['totales'],
            'fechaEmision'          => now()->format('d/m/Y H:i A'),
            'municipioSeleccionado' => $municipioId ? Municipio::find($municipioId)->nombre : 'TODOS',
            'ajuste'                => Ajuste::first(),
            'logoData'              => $this->getLogoData(),
        ];

        $pdf = Pdf::loadView("pdf.{$actividad}", $datos)
                  ->setPaper('letter', 'portrait');

        $nombreArchivo = "Reporte_{$actividad}_{$nombreMes}_{$año}.pdf";

        return $pdf->stream($nombreArchivo);
    }

    private function agruparDatos(iterable $registros, array $fieldsToSum): array
    {
        $datosAgrupados = [];
        
        // Inicializar totales generales
        $totalesGenerales = ['registros' => 0];
        foreach ($fieldsToSum as $field) {
            $totalesGenerales[$field] = 0;
        }

        foreach ($registros as $r) {
            $mun = $r->sector->comuna->parroquia->municipio->nombre ?? 'Desconocido';
            $par = $r->sector->comuna->parroquia->nombre ?? 'Desconocida';
            $com = $r->sector->comuna->nombre ?? 'Desconocida';
            $sec = $r->sector->nombre ?? 'Desconocido';

            // Crear jerarquía si no existe
            if (!isset($datosAgrupados[$mun])) {
                $datosAgrupados[$mun] = ['totales' => $this->initTotals($fieldsToSum), 'parroquias' => []];
            }
            if (!isset($datosAgrupados[$mun]['parroquias'][$par])) {
                $datosAgrupados[$mun]['parroquias'][$par] = ['totales' => $this->initTotals($fieldsToSum), 'comunas' => []];
            }
            if (!isset($datosAgrupados[$mun]['parroquias'][$par]['comunas'][$com])) {
                $datosAgrupados[$mun]['parroquias'][$par]['comunas'][$com] = ['totales' => $this->initTotals($fieldsToSum), 'sectores' => []];
            }
            if (!isset($datosAgrupados[$mun]['parroquias'][$par]['comunas'][$com]['sectores'][$sec])) {
                $datosAgrupados[$mun]['parroquias'][$par]['comunas'][$com]['sectores'][$sec] = $this->initTotals($fieldsToSum);
            }

            // Sumar a todos los niveles
            $levels = [
                &$datosAgrupados[$mun]['parroquias'][$par]['comunas'][$com]['sectores'][$sec],
                &$datosAgrupados[$mun]['parroquias'][$par]['comunas'][$com]['totales'],
                &$datosAgrupados[$mun]['parroquias'][$par]['totales'],
                &$datosAgrupados[$mun]['totales'],
                &$totalesGenerales
            ];

            foreach ($levels as &$level) {
                $level['registros'] += 1;
                foreach ($fieldsToSum as $field) {
                    $level[$field] += (int) $r->$field;
                }
            }
        }

        // Ordenamiento alfabético
        ksort($datosAgrupados);
        foreach ($datosAgrupados as &$dataMun) {
            ksort($dataMun['parroquias']);
            foreach ($dataMun['parroquias'] as &$dataPar) {
                ksort($dataPar['comunas']);
                foreach ($dataPar['comunas'] as &$dataCom) {
                    ksort($dataCom['sectores']);
                }
            }
        }

        return [
            'datos' => $datosAgrupados,
            'totales' => $totalesGenerales
        ];
    }

    private function initTotals(array $fields): array
    {
        $arr = ['registros' => 0];
        foreach ($fields as $f) {
            $arr[$f] = 0;
        }
        return $arr;
    }

    private function getLogoData(): string
    {
        $logoPath = public_path('assets/logo.png');
        if (file_exists($logoPath)) {
            $tipoMime = mime_content_type($logoPath);
            return 'data:' . $tipoMime . ';base64,' . base64_encode(file_get_contents($logoPath));
        }
        return '';
    }
}

<?php

namespace App\Livewire;

use App\Models\Transcripcion;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReporteTranscripcionController extends Controller
{
    public function descargar(Request $request)
    {
        $mes = $request->input('mes', now()->month);
        $año = $request->input('año', now()->year);
        $tipo = $request->input('tipo', 'VULNERABILIDAD');

        if (!in_array($tipo, Transcripcion::TIPOS)) {
            abort(400, 'Tipo de transcripción no válido.');
        }

        $esSugima = ($tipo === Transcripcion::TIPO_CON_INGRESOS_EGRESOS);
        $meses = [
            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
            5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
            9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
        ];
        $nombreMes = $meses[(int)$mes] ?? 'Desconocido';
        $municipioId = $request->input('municipio_id');

        $transcripciones = Transcripcion::with(['municipio', 'parroquia', 'comuna', 'sector'])
            ->where('tipo', $tipo)
            ->whereYear('fecha', $año)
            ->whereMonth('fecha', $mes)
            ->when($municipioId, function ($query, $municipioId) {
                return $query->where('municipio_id', $municipioId);
            })
            ->get();

        $resultadoAgrupado = $this->agruparDatos($transcripciones, $esSugima);
        $styles = $this->getColorStyles($tipo);

        $datos = array_merge([
            'tipo' => $tipo,
            'mes' => $nombreMes,
            'año' => $año,
            'datosAgrupados' => $resultadoAgrupado['datos'],
            'totalesGenerales' => $resultadoAgrupado['totales'],
            'esSugima' => $esSugima,
            'fechaEmision' => now()->format('d/m/Y H:i A'),
            'municipioSeleccionado' => $municipioId ? \App\Models\Municipio::find($municipioId)->nombre : 'TODOS',
            'ajuste' => \App\Models\Ajuste::first(),
            'logoData' => $this->getLogoData(),
        ], $styles);

        $pdf = Pdf::loadView('pdf.transcripciones-report', $datos)
                  ->setPaper('letter', 'portrait');

        $nombreArchivo = 'Reporte_' . str_replace(' ', '_', $tipo) . "_{$nombreMes}_{$año}.pdf";

        return $pdf->stream($nombreArchivo);
    }

    /**
     * Agrupa las transcripciones en una estructura jerárquica y calcula totales.
     */
    private function agruparDatos(iterable $transcripciones, bool $esSugima): array
    {
        $datosAgrupados = [];
        $totalesGenerales = ['cantidad' => 0, 'ingreso' => 0, 'egreso' => 0];

        foreach ($transcripciones as $t) {
            $mun = $t->municipio->nombre ?? 'Desconocido';
            $par = $t->parroquia->nombre ?? 'Desconocida';
            $com = $t->comuna->nombre ?? 'Desconocida';
            $sec = $t->sector->nombre ?? 'Desconocido';

            if (!isset($datosAgrupados[$mun])) {
                $datosAgrupados[$mun] = [
                    'totales' => ['cantidad' => 0, 'ingreso' => 0, 'egreso' => 0],
                    'parroquias' => []
                ];
            }
            if (!isset($datosAgrupados[$mun]['parroquias'][$par])) {
                $datosAgrupados[$mun]['parroquias'][$par] = [
                    'totales' => ['cantidad' => 0, 'ingreso' => 0, 'egreso' => 0],
                    'comunas' => []
                ];
            }
            if (!isset($datosAgrupados[$mun]['parroquias'][$par]['comunas'][$com])) {
                $datosAgrupados[$mun]['parroquias'][$par]['comunas'][$com] = [
                    'totales' => ['cantidad' => 0, 'ingreso' => 0, 'egreso' => 0],
                    'sectores' => []
                ];
            }
            if (!isset($datosAgrupados[$mun]['parroquias'][$par]['comunas'][$com]['sectores'][$sec])) {
                $datosAgrupados[$mun]['parroquias'][$par]['comunas'][$com]['sectores'][$sec] = [
                    'cantidad' => 0, 'ingreso' => 0, 'egreso' => 0,
                ];
            }

            $cant = $t->cantidad;
            $ing  = $esSugima ? $t->ingreso : 0;
            $egr  = $esSugima ? $t->egreso : 0;

            // Sumar a todos los niveles
            $levels = [
                &$datosAgrupados[$mun]['parroquias'][$par]['comunas'][$com]['sectores'][$sec],
                &$datosAgrupados[$mun]['parroquias'][$par]['comunas'][$com]['totales'],
                &$datosAgrupados[$mun]['parroquias'][$par]['totales'],
                &$datosAgrupados[$mun]['totales'],
                &$totalesGenerales
            ];

            foreach ($levels as &$level) {
                $level['cantidad'] += $cant;
                $level['ingreso'] += $ing;
                $level['egreso'] += $egr;
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

    /**
     * Calcula los colores y estilos de fondo basados en el tipo de reporte.
     */
    private function getColorStyles(string $tipo): array
    {
        $colores = [
            'VULNERABILIDAD'   => '#f43f5e',
            'CPLV'             => '#3b82f6',
            'LACTANCIA MATERNA' => '#ec4899',
            'ENCUESTA DIETARIA' => '#f59e0b',
            'MONITOREO DE PRECIO' => '#8b5cf6',
            'SUGIMA'           => '#84cc16',
            'PERINATAL'        => '#6366f1',
            'PRIMER NIVEL DE ATENCION' => '#06b6d4',
            'DESNUTRICION GRAVE' => '#ef4444',
            'CONSULTA'         => '#10b981',
        ];

        $colorThema = $colores[$tipo] ?? '#6b7280';
        $hex = str_replace('#', '', $colorThema);
        $r = hexdec(strlen($hex) == 3 ? str_repeat(substr($hex, 0, 1), 2) : substr($hex, 0, 2));
        $g = hexdec(strlen($hex) == 3 ? str_repeat(substr($hex, 1, 1), 2) : substr($hex, 2, 2));
        $b = hexdec(strlen($hex) == 3 ? str_repeat(substr($hex, 2, 1), 2) : substr($hex, 4, 2));

        return [
            'colorThema'  => $colorThema,
            'bgMunicipio' => "rgba($r, $g, $b, 0.15)",
            'bgParroquia' => "rgba($r, $g, $b, 0.08)",
            'bgComuna'    => "rgba($r, $g, $b, 0.03)",
        ];
    }

    /**
     * Obtiene el logo en formato base64.
     */
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

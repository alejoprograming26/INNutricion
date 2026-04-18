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

        // Validar el tipo
        if (!in_array($tipo, Transcripcion::TIPOS)) {
            abort(400, 'Tipo de transcripción no válido.');
        }

        $esSugima = ($tipo === Transcripcion::TIPO_CON_INGRESOS_EGRESOS);

        // Nombres de los meses
        $meses = [
            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
            5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
            9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
        ];
        $nombreMes = $meses[(int)$mes] ?? 'Desconocido';

        $municipioId = $request->input('municipio_id');

        // Obtener todos los registros del mes/año y tipo
        $transcripciones = Transcripcion::with(['municipio', 'parroquia', 'comuna', 'sector'])
            ->where('tipo', $tipo)
            ->whereYear('fecha', $año)
            ->whereMonth('fecha', $mes)
            ->when($municipioId, function ($query, $municipioId) {
                return $query->where('municipio_id', $municipioId);
            })
            ->get();

        // Agrupar los datos y calcular subtotales
        $datosAgrupados = [];
        $totalesGenerales = [
            'cantidad' => 0,
            'ingreso' => 0,
            'egreso' => 0,
        ];

        foreach ($transcripciones as $t) {
            $mun = $t->municipio->nombre ?? 'Desconocido';
            $par = $t->parroquia->nombre ?? 'Desconocida';
            $com = $t->comuna->nombre ?? 'Desconocida';
            $sec = $t->sector->nombre ?? 'Desconocido';

            // Estructura Municipio
            if (!isset($datosAgrupados[$mun])) {
                $datosAgrupados[$mun] = [
                    'totales' => ['cantidad' => 0, 'ingreso' => 0, 'egreso' => 0],
                    'parroquias' => []
                ];
            }
            // Estructura Parroquia
            if (!isset($datosAgrupados[$mun]['parroquias'][$par])) {
                $datosAgrupados[$mun]['parroquias'][$par] = [
                    'totales' => ['cantidad' => 0, 'ingreso' => 0, 'egreso' => 0],
                    'comunas' => []
                ];
            }
            // Estructura Comuna
            if (!isset($datosAgrupados[$mun]['parroquias'][$par]['comunas'][$com])) {
                $datosAgrupados[$mun]['parroquias'][$par]['comunas'][$com] = [
                    'totales' => ['cantidad' => 0, 'ingreso' => 0, 'egreso' => 0],
                    'sectores' => []
                ];
            }
            // Estructura Sector
            if (!isset($datosAgrupados[$mun]['parroquias'][$par]['comunas'][$com]['sectores'][$sec])) {
                $datosAgrupados[$mun]['parroquias'][$par]['comunas'][$com]['sectores'][$sec] = [
                    'cantidad' => 0,
                    'ingreso' => 0,
                    'egreso' => 0,
                ];
            }

            // Sumar a todos los niveles
            $cant = $t->cantidad;
            $ing  = $esSugima ? $t->ingreso : 0;
            $egr  = $esSugima ? $t->egreso : 0;

            // Sector
            $datosAgrupados[$mun]['parroquias'][$par]['comunas'][$com]['sectores'][$sec]['cantidad'] += $cant;
            $datosAgrupados[$mun]['parroquias'][$par]['comunas'][$com]['sectores'][$sec]['ingreso'] += $ing;
            $datosAgrupados[$mun]['parroquias'][$par]['comunas'][$com]['sectores'][$sec]['egreso'] += $egr;

            // Comuna
            $datosAgrupados[$mun]['parroquias'][$par]['comunas'][$com]['totales']['cantidad'] += $cant;
            $datosAgrupados[$mun]['parroquias'][$par]['comunas'][$com]['totales']['ingreso'] += $ing;
            $datosAgrupados[$mun]['parroquias'][$par]['comunas'][$com]['totales']['egreso'] += $egr;

            // Parroquia
            $datosAgrupados[$mun]['parroquias'][$par]['totales']['cantidad'] += $cant;
            $datosAgrupados[$mun]['parroquias'][$par]['totales']['ingreso'] += $ing;
            $datosAgrupados[$mun]['parroquias'][$par]['totales']['egreso'] += $egr;

            // Municipio
            $datosAgrupados[$mun]['totales']['cantidad'] += $cant;
            $datosAgrupados[$mun]['totales']['ingreso'] += $ing;
            $datosAgrupados[$mun]['totales']['egreso'] += $egr;

            // Total General
            $totalesGenerales['cantidad'] += $cant;
            $totalesGenerales['ingreso'] += $ing;
            $totalesGenerales['egreso'] += $egr;
        }

        // Ordenamiento alfabético por cada nivel de jerarquía
        ksort($datosAgrupados);
        foreach ($datosAgrupados as $mun => &$dataMun) {
            ksort($dataMun['parroquias']);
            foreach ($dataMun['parroquias'] as $par => &$dataPar) {
                ksort($dataPar['comunas']);
                foreach ($dataPar['comunas'] as $com => &$dataCom) {
                    ksort($dataCom['sectores']);
                }
            }
        }

        $colores = [
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
        $colorThema = $colores[$tipo] ?? '#6b7280'; // gris por defecto

        // Convertir HEX a RGB para fondos tenues jerárquicos
        $hex = str_replace('#', '', $colorThema);
        $r = hexdec(strlen($hex) == 3 ? str_repeat(substr($hex,0,1), 2) : substr($hex,0,2));
        $g = hexdec(strlen($hex) == 3 ? str_repeat(substr($hex,1,1), 2) : substr($hex,2,2));
        $b = hexdec(strlen($hex) == 3 ? str_repeat(substr($hex,2,1), 2) : substr($hex,4,2));
        $bgMunicipio = "rgba($r, $g, $b, 0.15)";
        $bgParroquia = "rgba($r, $g, $b, 0.08)";
        $bgComuna    = "rgba($r, $g, $b, 0.03)";

        // Datos de la institución (Ajustes) y Logo
        $ajuste = \App\Models\Ajuste::first();
        $logoPath = public_path('assets/logo.png');
        $logoData = '';
        if (file_exists($logoPath)) {
            $tipoMime = mime_content_type($logoPath);
            $logoData = 'data:' . $tipoMime . ';base64,' . base64_encode(file_get_contents($logoPath));
        }

        $datos = [
            'tipo' => $tipo,
            'mes' => $nombreMes,
            'año' => $año,
            'datosAgrupados' => $datosAgrupados,
            'totalesGenerales' => $totalesGenerales,
            'esSugima' => $esSugima,
            'fechaEmision' => now()->format('d/m/Y H:i A'),
            'municipioSeleccionado' => $municipioId ? \App\Models\Municipio::find($municipioId)->nombre : 'TODOS',
            'colorThema' => $colorThema,
            'bgMunicipio' => $bgMunicipio,
            'bgParroquia' => $bgParroquia,
            'bgComuna' => $bgComuna,
            'ajuste' => $ajuste,
            'logoData' => $logoData,
        ];

        // Usamos una orientación de tabla o normal, usualmente A4 o Letter
        $pdf = Pdf::loadView('pdf.transcripciones-report', $datos)
                  ->setPaper('letter', 'portrait');

        $nombreArchivo = 'Reporte_' . str_replace(' ', '_', $tipo) . "_{$nombreMes}_{$año}.pdf";

        return $pdf->stream($nombreArchivo);
    }
}

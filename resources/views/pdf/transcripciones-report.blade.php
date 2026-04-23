<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Transcripciones - {{ $tipo }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 11px;
            color: #333;
            margin: 0;
            padding: 0;
            background: #fff;
        }

        /* Cabecera del Documento */
        .header {
            width: 100%;
            border-bottom: 3px solid {{ $colorThema }};
            padding-bottom: 20px;
            margin-bottom: 20px;
        }

        .header table {
            width: 100%;
            border: none;
        }

        .header td {
            border: none;
            padding: 0;
        }

        .logo-text {
            font-size: 24px;
            font-weight: bold;
            color: #4d7c0f; /* lime-700 */
            letter-spacing: 1px;
        }

        .header-title {
            text-align: right;
        }

        .header-title h1 {
            margin: 0;
            font-size: 18px;
            color: #1f2937;
            text-transform: uppercase;
        }

        .header-title p {
            margin: 5px 0 0 0;
            font-size: 12px;
            color: #6b7280;
        }

        /* Información del Filtro */
        .info-box {
            background-color: #f3f4f6;
            border: 1px solid #e5e7eb;
            border-radius: 4px;
            padding: 10px;
            margin-bottom: 20px;
            width: 100%;
        }

        .info-box table {
            width: 100%;
            border: none;
        }

        .info-box td {
            border: none;
            padding: 5px;
            font-size: 12px;
        }

        .info-label {
            font-weight: bold;
            color: #4b5563;
        }

        .info-value {
            color: #111827;
        }

        /* Tabla Principal Jerárquica */
        .report-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            page-break-inside: auto;
        }

        .report-table tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }

        .report-table thead {
            display: table-header-group;
        }

        .report-table th, .report-table td {
            padding: 8px 10px;
            border-bottom: 1px solid #f3f4f6;
        }

        .report-table th {
            background-color: #f9fafb;
            color: #374151;
            font-weight: bold;
            text-align: left;
            text-transform: uppercase;
            font-size: 10px;
            border-bottom: 2px solid {{ $colorThema }};
        }

        .text-center { text-align: center !important; }
        .text-right { text-align: right !important; }
        
        .col-ingreso { color: #16a34a; font-weight: 500; }
        .col-egreso { color: #dc2626; font-weight: 500;  }

        /* Estilos de Jerarquía */
        .spacer-row td {
            height: 20px;
            border: none !important;
            padding: 0 !important;
            background-color: #ffffff;
        }

        .level-municipio td {
            background-color: {{ $bgMunicipio }};
            color: #111827;
            font-weight: bold;
            font-size: 13px;
            border-top: 3px solid {{ $colorThema }};
            border-bottom: 1px solid #d1d5db;
        }

        .level-parroquia td {
            background-color: {{ $bgParroquia }};
            color: #374151;
            font-weight: bold;
            font-size: 12px;
            text-transform: uppercase;
            border-top: 1px solid #e5e7eb;
            border-bottom: 1px solid #e5e7eb;
        }

        .level-comuna td {
            background-color: {{ $bgComuna }};
            color: #4b5563;
            font-weight: bold;
            font-size: 11px;
            font-style: italic;
        }

        .level-sector td {
            padding-left: 20px !important;
            color: #6b7280;
            font-size: 11px;
        }

        /* Footer */
        .footer {
            position: fixed;
            bottom: -30px;
            left: 0px;
            right: 0px;
            height: 30px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            font-size: 9px;
            color: #9ca3af;
            line-height: 30px;
        }

        .page-number:before {
            content: "Página " counter(page);
        }

        /* Totales Finales */
        .grand-total {
            margin-top: 30px;
            border-top: 3px solid {{ $colorThema }};
            padding-top: 10px;
            page-break-inside: avoid;
        }

        .grand-total table {
            width: 50%;
            float: right;
            border-collapse: collapse;
        }

        .grand-total th {
            text-align: left;
            padding: 8px;
            background-color: #f3f4f6;
            border: 1px solid #e5e7eb;
            color: #374151;
        }

        .grand-total td {
            text-align: right;
            padding: 8px;
            border: 1px solid #e5e7eb;
            font-weight: bold;
            font-size: 14px;
        }
        
        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }

        .empty-message {
            text-align: center;
            padding: 30px;
            color: #6b7280;
            font-style: italic;
            border: 1px dashed #d1d5db;
        }
    </style>
</head>
<body>

    <!-- Pie de página -->
    <div class="footer">
        {{ $ajuste->nombre ?? 'INNutricion' }} - Sistema de Gestión | Generado el {{ $fechaEmision }} | <span class="page-number"></span>
    </div>

    <!-- Cabecera -->
    <div class="header">
        <table>
            <tr>
                <td style="width: 15%; vertical-align: middle;">
                    @if($logoData)
                        <img src="{{ $logoData }}" alt="Logo" style="max-height: 70px; max-width: 100%;">
                    @else
                        <div class="logo-text" style="color: {{ $colorThema }};">LOGO</div>
                    @endif
                </td>
                <td style="width: 45%; vertical-align: middle; padding-left: 10px;">
                    <div style="font-size: 16px; font-weight: bold; color: #111827; margin-bottom: 3px;">
                        {{ $ajuste->nombre ?? 'INNutricion' }}
                    </div>
                    @if(isset($ajuste))
                        <div style="font-size: 10px; color: #4b5563; line-height: 1.3;">
                            @if($ajuste->sucursal) Sucursal: {{ $ajuste->sucursal }}<br>@endif
                            @if($ajuste->direccion) {{ $ajuste->direccion }}<br>@endif
                            @if($ajuste->telefonos) Tel: {{ $ajuste->telefonos }}<br>@endif
                            @if($ajuste->email) Email: {{ $ajuste->email }} @endif
                        </div>
                    @endif
                </td>
                <td style="width: 40%; vertical-align: middle;" class="header-title">
                    <h1 style="color: {{ $colorThema }}; font-size: 20px;">Reporte Mensual</h1>
                    <p style="font-size: 12px; margin-top: 3px;">Detalle Analítico de Transcripciones</p>
                </td>
            </tr>
        </table>
    </div>

    <!-- Filtros Aplicados -->
    <div class="info-box">
        <table>
            <tr>
                <td width="15%" class="info-label">Tipo Transcripción:</td>
                <td width="35%" class="info-value"><strong style="color: {{ $colorThema }};">{{ $tipo }}</strong></td>
                <td width="15%" class="info-label">Período Seleccionado:</td>
                <td width="35%" class="info-value"><strong>{{ $mes }} {{ $año }}</strong></td>
            </tr>
            <tr>
                <td class="info-label">Municipio:</td>
                <td class="info-value"><strong>{{ $municipioSeleccionado ?? 'TODOS' }}</strong></td>
                <td class="info-label">Fecha de Emisión:</td>
                <td class="info-value">{{ $fechaEmision }}</td>
            </tr>
        </table>
    </div>

    <!-- Tabla de Datos -->
    @if(count($datosAgrupados) > 0)
        <table class="report-table">
            <thead>
                <tr>
                    <th width="40%">Ubicación Geográfica (Mun / Parr / Com / Sector)</th>
                    <th width="20%" class="text-right">Suma de Cantidades (Total)</th>
                    @if($esSugima)
                        <th width="20%" class="text-right col-ingreso">Ingresos</th>
                        <th width="20%" class="text-right col-egreso">Egresos</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach($datosAgrupados as $mun => $dataMun)
                    <!-- Spacer -->
                    @if(!$loop->first)
                        <tr class="spacer-row"><td colspan="{{ $esSugima ? 4 : 2 }}"></td></tr>
                    @endif

                    <!-- Fila Municipio -->
                    <tr class="level-municipio">
                        <td>MUNICIPIO: {{ $mun }}</td>
                        <td class="text-right">{{ number_format($dataMun['totales']['cantidad'], 0, ',', '.') }}</td>
                        @if($esSugima)
                            <td class="text-right col-ingreso">{{ number_format($dataMun['totales']['ingreso'], 0, ',', '.') }}</td>
                            <td class="text-right col-egreso">{{ number_format($dataMun['totales']['egreso'], 0, ',', '.') }}</td>
                        @endif
                    </tr>
                    
                    @foreach($dataMun['parroquias'] as $par => $dataPar)
                        <!-- Fila Parroquia -->
                        <tr class="level-parroquia">
                            <td style="padding-left: 20px;">Parroquia: {{ $par }}</td>
                            <td class="text-right">{{ number_format($dataPar['totales']['cantidad'], 0, ',', '.') }}</td>
                            @if($esSugima)
                                <td class="text-right col-ingreso">{{ number_format($dataPar['totales']['ingreso'], 0, ',', '.') }}</td>
                                <td class="text-right col-egreso">{{ number_format($dataPar['totales']['egreso'], 0, ',', '.') }}</td>
                            @endif
                        </tr>

                        @foreach($dataPar['comunas'] as $com => $dataCom)
                            <!-- Fila Comuna -->
                            <tr class="level-comuna">
                                <td style="padding-left: 35px;">Comuna: {{ $com }}</td>
                                <td class="text-right">{{ number_format($dataCom['totales']['cantidad'], 0, ',', '.') }}</td>
                                @if($esSugima)
                                    <td class="text-right col-ingreso">{{ number_format($dataCom['totales']['ingreso'], 0, ',', '.') }}</td>
                                    <td class="text-right col-egreso">{{ number_format($dataCom['totales']['egreso'], 0, ',', '.') }}</td>
                                @endif
                            </tr>

                            @foreach($dataCom['sectores'] as $sec => $totales)
                                <!-- Fila Sector (Datos Mínimos) -->
                                <tr class="level-sector">
                                    <td style="padding-left: 55px !important;">Sector: {{ $sec }}</td>
                                    <td class="text-right">{{ number_format($totales['cantidad'], 0, ',', '.') }}</td>
                                    @if($esSugima)
                                        <td class="text-right col-ingreso">{{ number_format($totales['ingreso'], 0, ',', '.') }}</td>
                                        <td class="text-right col-egreso">{{ number_format($totales['egreso'], 0, ',', '.') }}</td>
                                    @endif
                                </tr>
                            @endforeach
                        @endforeach
                    @endforeach
                @endforeach
            </tbody>
        </table>

        <!-- Totales Generales -->
        <div class="grand-total clearfix">
            <table>
                <tr>
                    <th colspan="2" class="text-center" style="background-color: #e5e7eb;">GRAN TOTAL DEL MES</th>
                </tr>
                <tr>
                    <th>Gran Total (Suma de Cantidades)</th>
                    <td>{{ number_format($totalesGenerales['cantidad'], 0, ',', '.') }}</td>
                </tr>
                @if($esSugima)
                <tr>
                    <th>Total Ingresos</th>
                    <td class="col-ingreso">{{ number_format($totalesGenerales['ingreso'], 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <th>Total Egresos</th>
                    <td class="col-egreso">{{ number_format($totalesGenerales['egreso'], 0, ',', '.') }}</td>
                </tr>
                @endif
            </table>
        </div>

    @else
        <div class="empty-message">
            No se encontraron registros de transcripciones de tipo <b>{{ $tipo }}</b> para el período de <b>{{ $mes }} {{ $año }}</b>.
        </div>
    @endif

</body>
</html>

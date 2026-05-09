<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte Mensual - {{ $titulo }}</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 10px; color: #333; margin: 0; padding: 0; background: #fff; }
        .header { width: 100%; border-bottom: 3px solid {{ $colorThema }}; padding-bottom: 15px; margin-bottom: 15px; }
        .header table { width: 100%; border: none; }
        .header td { border: none; padding: 0; }
        .logo-text { font-size: 24px; font-weight: bold; color: #4d7c0f; letter-spacing: 1px; }
        .header-title { text-align: right; }
        .header-title h1 { margin: 0; font-size: 16px; color: #1f2937; text-transform: uppercase; }
        .header-title p { margin: 3px 0 0 0; font-size: 11px; color: #6b7280; }
        .info-box { background-color: #f3f4f6; border: 1px solid #e5e7eb; border-radius: 4px; padding: 8px; margin-bottom: 15px; width: 100%; }
        .info-box table { width: 100%; border: none; }
        .info-box td { border: none; padding: 3px 5px; font-size: 10px; }
        .info-label { font-weight: bold; color: #4b5563; }
        .info-value { color: #111827; }
        .report-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; page-break-inside: auto; }
        .report-table tr { page-break-inside: avoid; page-break-after: auto; }
        .report-table thead { display: table-header-group; }
        .report-table th, .report-table td { padding: 6px 8px; border-bottom: 1px solid #f3f4f6; }
        .report-table th { background-color: #f9fafb; color: #374151; font-weight: bold; text-align: left; text-transform: uppercase; font-size: 9px; border-bottom: 2px solid {{ $colorThema }}; }
        .text-center { text-align: center !important; }
        .text-right { text-align: right !important; }
        .spacer-row td { height: 15px; border: none !important; padding: 0 !important; background-color: #ffffff; }
        .level-municipio td { background-color: #f1f5f9; color: #111827; font-weight: bold; font-size: 11px; border-top: 2px solid {{ $colorThema }}; border-bottom: 1px solid #d1d5db; }
        .level-parroquia td { background-color: #f8fafc; color: #374151; font-weight: bold; font-size: 10px; text-transform: uppercase; }
        .level-comuna td { color: #4b5563; font-weight: bold; font-size: 9px; font-style: italic; }
        .level-sector td { padding-left: 15px !important; color: #6b7280; font-size: 9px; }
        .footer { position: fixed; bottom: -20px; left: 0px; right: 0px; height: 20px; border-top: 1px solid #e5e7eb; text-align: center; font-size: 8px; color: #9ca3af; line-height: 20px; }
        .page-number:before { content: "Página " counter(page); }
        .grand-total { margin-top: 20px; border-top: 2px solid {{ $colorThema }}; padding-top: 8px; page-break-inside: avoid; }
        .grand-total table { width: 50%; float: right; border-collapse: collapse; }
        .grand-total th { text-align: left; padding: 6px; background-color: #f3f4f6; border: 1px solid #e5e7eb; color: #374151; font-size: 9px; }
        .grand-total td { text-align: right; padding: 6px; border: 1px solid #e5e7eb; font-weight: bold; font-size: 11px; }
        .clearfix::after { content: ""; clear: both; display: table; }
        .empty-message { text-align: center; padding: 20px; color: #6b7280; font-style: italic; border: 1px dashed #d1d5db; }
    </style>
</head>
<body>
    <div class="footer"> {{ $ajuste->nombre ?? 'INNutricion' }} - Sistema de Gestión | Generado el {{ $fechaEmision }} | <span class="page-number"></span> </div>
    <div class="header">
        <table>
            <tr>
                <td style="width: 15%; vertical-align: middle;">
                    @if($logoData) <img src="{{ $logoData }}" alt="Logo" style="max-height: 60px; max-width: 100%;"> @else <div class="logo-text" style="color: {{ $colorThema }};">LOGO</div> @endif
                </td>
                <td style="width: 45%; vertical-align: middle; padding-left: 10px;">
                    <div style="font-size: 14px; font-weight: bold; color: #111827; margin-bottom: 2px;"> {{ $ajuste->nombre ?? 'INNutricion' }} </div>
                    @if(isset($ajuste))
                        <div style="font-size: 9px; color: #4b5563; line-height: 1.2;">
                            @if($ajuste->sucursal) Sucursal: {{ $ajuste->sucursal }}<br>@endif
                            @if($ajuste->direccion) {{ $ajuste->direccion }}<br>@endif
                            @if($ajuste->telefonos) Tel: {{ $ajuste->telefonos }}<br>@endif
                            @if($ajuste->email) Email: {{ $ajuste->email }} @endif
                        </div>
                    @endif
                </td>
                <td style="width: 40%; vertical-align: middle;" class="header-title"> <h1 style="color: {{ $colorThema }};">Reporte Mensual</h1> <p>{{ $titulo }}</p> </td>
            </tr>
        </table>
    </div>
    <div class="info-box">
        <table>
            <tr>
                <td width="15%" class="info-label">Mes:</td> <td width="35%" class="info-value"><strong>{{ $mes }}</strong></td>
                <td width="15%" class="info-label">Año:</td> <td width="35%" class="info-value"><strong>{{ $año }}</strong></td>
            </tr>
            <tr>
                <td class="info-label">Municipio:</td> <td class="info-value"><strong>{{ $municipioSeleccionado }}</strong></td>
                <td class="info-label">Emisión:</td> <td class="info-value">{{ $fechaEmision }}</td>
            </tr>
        </table>
    </div>
    @if(count($datosAgrupados) > 0)
        <table class="report-table">
            <thead>
                <tr>
                    <th width="50%">Ubicación (Mun / Parr / Com / Sec)</th>
                    <th width="25%" class="text-center">Formaciones Realizadas</th>
                    <th width="25%" class="text-right">Total Líderes Formados</th>
                </tr>
            </thead>
            <tbody>
                @foreach($datosAgrupados as $mun => $dataMun)
                    @if(!$loop->first) <tr class="spacer-row"><td colspan="3"></td></tr> @endif
                    <tr class="level-municipio">
                        <td>MUNICIPIO: {{ $mun }}</td>
                        <td class="text-center">{{ number_format($dataMun['totales']['registros'], 0, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($dataMun['totales']['cantidad'], 0, ',', '.') }}</td>
                    </tr>
                    @foreach($dataMun['parroquias'] as $par => $dataPar)
                        <tr class="level-parroquia">
                            <td style="padding-left: 15px;">Parroquia: {{ $par }}</td>
                            <td class="text-center">{{ number_format($dataPar['totales']['registros'], 0, ',', '.') }}</td>
                            <td class="text-right">{{ number_format($dataPar['totales']['cantidad'], 0, ',', '.') }}</td>
                        </tr>
                        @foreach($dataPar['comunas'] as $com => $dataCom)
                            <tr class="level-comuna">
                                <td style="padding-left: 30px;">Comuna: {{ $com }}</td>
                                <td class="text-center">{{ number_format($dataCom['totales']['registros'], 0, ',', '.') }}</td>
                                <td class="text-right">{{ number_format($dataCom['totales']['cantidad'], 0, ',', '.') }}</td>
                            </tr>
                            @foreach($dataCom['sectores'] as $sec => $totales)
                                <tr class="level-sector">
                                    <td style="padding-left: 45px !important;">Sector: {{ $sec }}</td>
                                    <td class="text-center">{{ number_format($totales['registros'], 0, ',', '.') }}</td>
                                    <td class="text-right">{{ number_format($totales['cantidad'], 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        @endforeach
                    @endforeach
                @endforeach
            </tbody>
        </table>
        <div class="grand-total clearfix">
            <table>
                <tr> <th colspan="2" class="text-center" style="background-color: #e5e7eb;">GRAN TOTAL DEL MES</th> </tr>
                <tr> <th>Total Formaciones Realizadas</th> <td>{{ number_format($totalesGenerales['registros'], 0, ',', '.') }}</td> </tr>
                <tr> <th>Total Líderes Formados</th> <td>{{ number_format($totalesGenerales['cantidad'], 0, ',', '.') }}</td> </tr>
            </table>
        </div>
    @else
        <div class="empty-message"> No se encontraron registros de <b>{{ $titulo }}</b> para el período de <b>{{ $mes }} {{ $año }}</b>. </div>
    @endif
</body>
</html>

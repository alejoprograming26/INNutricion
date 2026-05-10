<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte Mensual - {{ $titulo }}</title>
    @php
        $hex = str_replace('#', '', $colorThema);
        if(strlen($hex) == 3) {
            $r = hexdec(str_repeat(substr($hex,0,1),2));
            $g = hexdec(str_repeat(substr($hex,1,1),2));
            $b = hexdec(str_repeat(substr($hex,2,1),2));
        } else {
            $r = hexdec(substr($hex,0,2));
            $g = hexdec(substr($hex,2,2));
            $b = hexdec(substr($hex,4,2));
        }
        $colorFaded = "rgba($r, $g, $b, 0.1)";
    @endphp
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 10px; color: #1e293b; margin: 0; padding: 0; background: #fff; }
        .header { width: 100%; margin-bottom: 18px; background-color: {{ $colorFaded }}; border-bottom: 2px solid {{ $colorThema }}; padding: 0; }
        .header-inner { padding: 14px 18px; }
        .header table { width: 100%; border: none; border-collapse: collapse; }
        .header td { border: none; padding: 0; vertical-align: middle; }
        .org-name { font-size: 16px; font-weight: bold; color: #1e293b; letter-spacing: 0.5px; margin-bottom: 3px; }
        .org-info { font-size: 8.5px; color: #475569; line-height: 1.4; }
        .header-badge { text-align: right; }
        .header-badge .doc-type { display: inline-block; background-color: #ffffff; border: 1px solid {{ $colorThema }}; color: {{ $colorThema }}; font-size: 11px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; padding: 6px 12px; border-radius: 4px; margin-bottom: 4px; }
        .header-badge .doc-subtitle { font-size: 10px; color: #1e293b; text-align: right; display: block; font-weight: bold; }
        .header-accent-bar { height: 4px; background-color: {{ $colorThema }}; }
        .info-box { background-color: #f8fafc; border-left: 5px solid {{ $colorThema }}; border-top: 1px solid #e2e8f0; border-right: 1px solid #e2e8f0; border-bottom: 1px solid #e2e8f0; border-radius: 0 4px 4px 0; padding: 8px 12px; margin-bottom: 16px; width: 100%; box-sizing: border-box; }
        .info-box table { width: 100%; border: none; border-collapse: collapse; }
        .info-box td { border: none; padding: 3px 5px; font-size: 9.5px; }
        .info-label { font-weight: bold; color: {{ $colorThema }}; text-transform: uppercase; font-size: 8.5px; letter-spacing: 0.3px; }
        .info-value { color: #1e293b; font-weight: 600; }
        .report-table { width: 100%; border-collapse: collapse; margin-bottom: 16px; page-break-inside: auto; }
        .report-table tr { page-break-inside: avoid; page-break-after: auto; }
        .report-table thead { display: table-header-group; }
        .report-table th, .report-table td { padding: 6px 8px; }
        .report-table th { background-color: {{ $colorThema }}; color: #ffffff; font-weight: bold; text-align: left; text-transform: uppercase; font-size: 8.5px; letter-spacing: 0.5px; border-bottom: 3px solid rgba(0,0,0,0.2); border-right: 1px solid rgba(255,255,255,0.2); }
        .report-table th:last-child { border-right: none; }
        .text-center { text-align: center !important; }
        .text-right  { text-align: right !important; }
        .spacer-row td { height: 12px; border: none !important; padding: 0 !important; background-color: #ffffff; }
        .level-municipio td { background-color: {{ $colorThema }}; color: #ffffff; font-weight: bold; font-size: 10.5px; padding-top: 7px; padding-bottom: 7px; border-top: 2px solid rgba(0,0,0,0.1); border-bottom: 1px solid rgba(0,0,0,0.2); }
        .level-parroquia td { background-color: #f1f5f9; color: #1e293b; font-weight: bold; font-size: 9.5px; text-transform: uppercase; border-bottom: 1px solid #e2e8f0; border-left: 3px solid {{ $colorThema }}; }
        .level-comuna td { background-color: #f8fafc; color: #475569; font-weight: bold; font-size: 9px; border-bottom: 1px solid #e9edf2; }
        .level-sector td { padding-left: 20px !important; color: #475569; font-size: 9px; border-bottom: 1px solid #f1f5f9; background-color: #ffffff; }
        .footer { position: fixed; bottom: -20px; left: 0px; right: 0px; height: 20px; border-top: 2px solid #1e3a5f; background-color: #f8fafc; text-align: center; font-size: 7.5px; color: #64748b; line-height: 20px; }
        .page-number:before { content: "Página " counter(page); }
        .grand-total { margin-top: 20px; padding-top: 0; page-break-inside: avoid; }
        .grand-total table { width: 50%; float: right; border-collapse: collapse; }
        .grand-total th { text-align: left; padding: 6px 8px; background-color: #f1f5f9; border: 1px solid #e2e8f0; color: #374151; font-size: 8.5px; font-weight: bold; }
        .grand-total td { text-align: right; padding: 6px 8px; border: 1px solid #e2e8f0; font-weight: bold; font-size: 12px; color: #1e293b; background-color: #ffffff; }
        .clearfix::after { content: ""; clear: both; display: table; }
        .empty-message { text-align: center; padding: 25px; color: #64748b; font-style: italic; border: 1px dashed #94a3b8; background-color: #f8fafc; border-radius: 4px; }
    </style>
</head>
<body>
    <div class="footer"> {{ $ajuste->nombre ?? 'INNutricion' }} &mdash; Sistema de Gestión Nutricional &nbsp;|&nbsp; Generado el {{ $fechaEmision }} &nbsp;|&nbsp; <span class="page-number"></span> </div>
    <div class="header">
        <div class="header-accent-bar"></div>
        <div class="header-inner">
            <table><tr>
                <td style="width:15%;">
                    @if($logoData) <img src="{{ $logoData }}" alt="Logo" style="max-height:58px;max-width:100%;"> @else <div style="font-size:22px;font-weight:900;color:{{ $colorThema }};letter-spacing:2px;">INN</div> @endif
                </td>
                <td style="width:45%;padding-left:12px;">
                    <div class="org-name">{{ $ajuste->nombre ?? 'INNutricion' }}</div>
                    @if(isset($ajuste))<div class="org-info">@if($ajuste->sucursal){{ $ajuste->sucursal }}<br>@endif@if($ajuste->direccion){{ $ajuste->direccion }}<br>@endif@if($ajuste->telefonos)Tel: {{ $ajuste->telefonos }} @endif@if($ajuste->email)&nbsp;|&nbsp;{{ $ajuste->email }}@endif</div>@endif
                </td>
                <td style="width:40%;" class="header-badge">
                    <div class="doc-type">Reporte Mensual</div>
                    <span class="doc-subtitle">{{ $titulo }}</span>
                </td>
            </tr></table>
        </div>
    </div>
    <div class="info-box"><table>
        <tr>
            <td width="12%" class="info-label">Mes:</td><td width="38%" class="info-value"><strong>{{ $mes }}</strong></td>
            <td width="12%" class="info-label">Año:</td><td width="38%" class="info-value"><strong>{{ $año }}</strong></td>
        </tr>
        <tr>
            <td class="info-label">Municipio:</td><td class="info-value"><strong>{{ $municipioSeleccionado }}</strong></td>
            <td class="info-label">Emisión:</td><td class="info-value">{{ $fechaEmision }}</td>
        </tr>
    </table></div>
    @if(count($datosAgrupados) > 0)
        <table class="report-table">
            <thead><tr>
                <th width="50%">Ubicación (Mun / Parr / Com / Sec)</th>
                <th width="25%" class="text-center">Formaciones Realizadas</th>
                <th width="25%" class="text-right">Total Líderes Formados</th>
            </tr></thead>
            <tbody>
                @foreach($datosAgrupados as $mun => $dataMun)
                    @if(!$loop->first)<tr class="spacer-row"><td colspan="3"></td></tr>@endif
                    <tr class="level-municipio">
                        <td>&bull; MUNICIPIO: {{ $mun }}</td>
                        <td class="text-center">{{ number_format($dataMun['totales']['registros'],0,',','.') }}</td>
                        <td class="text-right">{{ number_format($dataMun['totales']['cantidad'],0,',','.') }}</td>
                    </tr>
                    @foreach($dataMun['parroquias'] as $par => $dataPar)
                        <tr class="level-parroquia">
                            <td style="padding-left:16px;">&raquo; Parroquia: {{ $par }}</td>
                            <td class="text-center">{{ number_format($dataPar['totales']['registros'],0,',','.') }}</td>
                            <td class="text-right">{{ number_format($dataPar['totales']['cantidad'],0,',','.') }}</td>
                        </tr>
                        @foreach($dataPar['comunas'] as $com => $dataCom)
                            <tr class="level-comuna">
                                <td style="padding-left:30px;">&bull; Comuna: {{ $com }}</td>
                                <td class="text-center">{{ number_format($dataCom['totales']['registros'],0,',','.') }}</td>
                                <td class="text-right">{{ number_format($dataCom['totales']['cantidad'],0,',','.') }}</td>
                            </tr>
                            @foreach($dataCom['sectores'] as $sec => $totales)
                                <tr class="level-sector">
                                    <td style="padding-left:46px !important;">&middot; Sector: {{ $sec }}</td>
                                    <td class="text-center">{{ number_format($totales['registros'],0,',','.') }}</td>
                                    <td class="text-right">{{ number_format($totales['cantidad'],0,',','.') }}</td>
                                </tr>
                            @endforeach
                        @endforeach
                    @endforeach
                @endforeach
            </tbody>
        </table>
        <div class="grand-total clearfix"><table>
            <tr><td colspan="2" style="background-color: {{ $colorThema }}; color: #fff; font-weight: bold; text-align: center; font-size: 9px; text-transform: uppercase; letter-spacing: 1px; padding: 7px;">RESUMEN GENERAL DEL MES</td></tr>
            <tr><th>Total Formaciones Realizadas</th><td>{{ number_format($totalesGenerales['registros'],0,',','.') }}</td></tr>
            <tr><th>Total Líderes Formados</th><td>{{ number_format($totalesGenerales['cantidad'],0,',','.') }}</td></tr>
        </table></div>
    @else
        <div class="empty-message">No se encontraron registros de <b>{{ $titulo }}</b> para el período de <b>{{ $mes }} {{ $año }}</b>.</div>
    @endif
</body>
</html>

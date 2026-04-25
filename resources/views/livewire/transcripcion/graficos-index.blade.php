<div class="mb-6">
    
    {{-- Topbar / Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4 bg-white dark:bg-zinc-900 p-4 lg:p-6 rounded-2xl border border-zinc-200 dark:border-zinc-800 shadow-sm">
        <div class="flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl bg-{{ $colorThemaTw }}-100 dark:bg-{{ $colorThemaTw }}-500/10 flex items-center justify-center border border-{{ $colorThemaTw }}-200 dark:border-{{ $colorThemaTw }}-500/20">
                <flux:icon.chart-pie class="w-6 h-6 text-{{ $colorThemaTw }}-600 dark:text-{{ $colorThemaTw }}-400" />
            </div>
            <div>
                <h1 class="text-2xl font-bold text-zinc-800 dark:text-zinc-100 tracking-tight">Dashboard Analítico</h1>
                <p class="text-sm text-zinc-500 dark:text-zinc-400">
                    <span class="text-{{ $colorThemaTw }}-600 dark:text-{{ $colorThemaTw }}-400 font-semibold">{{ $tipo }}</span> 
                    &bull; Municipio: <span class="text-zinc-700 dark:text-zinc-300 font-medium">{{ $municipioNombre }}</span> 
                    &bull; {{ $nombreMes }} {{ $año }}
                </p>
            </div>
        </div>
        
        <div class="flex flex-wrap items-center gap-3">
            <div class="flex items-center gap-2">
                <flux:select wire:model.live="mes" class="w-32">
                    <flux:select.option value="1">Enero</flux:select.option>
                    <flux:select.option value="2">Febrero</flux:select.option>
                    <flux:select.option value="3">Marzo</flux:select.option>
                    <flux:select.option value="4">Abril</flux:select.option>
                    <flux:select.option value="5">Mayo</flux:select.option>
                    <flux:select.option value="6">Junio</flux:select.option>
                    <flux:select.option value="7">Julio</flux:select.option>
                    <flux:select.option value="8">Agosto</flux:select.option>
                    <flux:select.option value="9">Septiembre</flux:select.option>
                    <flux:select.option value="10">Octubre</flux:select.option>
                    <flux:select.option value="11">Noviembre</flux:select.option>
                    <flux:select.option value="12">Diciembre</flux:select.option>
                </flux:select>
                <flux:input wire:model.live="año" type="number" class="w-24" />
            </div>

            <a href="{{ route('admin.transcripciones.index', ['tipo' => $tipo]) }}" wire:navigate
               class="px-4 py-2 bg-{{ $colorThemaTw }}-500 hover:bg-{{ $colorThemaTw }}-600 text-white dark:text-zinc-900 text-sm font-bold rounded-lg transition-colors flex items-center gap-2 shadow-sm">
                <flux:icon.arrow-left class="w-4 h-4" />
                Volver a Tabla
            </a>
        </div>
    </div>

    {{-- Tarjetas KPI --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        
        {{-- Total Cantidad --}}
        <div class="bg-white dark:bg-zinc-900 rounded-3xl p-6 border border-zinc-100 dark:border-zinc-800/80 shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-[0_8px_30px_rgb(0,0,0,0.1)] relative overflow-hidden group">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-{{ $colorThemaTw }}-500/10 rounded-full blur-2xl group-hover:bg-{{ $colorThemaTw }}-500/20 transition-all duration-500"></div>
            <div class="flex justify-between items-start relative z-10">
                <div>
                    <p class="text-xs font-semibold text-zinc-500 dark:text-zinc-400 uppercase tracking-widest mb-1">Cantidades Procesadas</p>
                    <h3 class="text-4xl font-black text-zinc-800 dark:text-zinc-100 tabular-nums">{{ number_format($kpis['total_cantidad'], 0, ',', '.') }}</h3>
                    <p class="text-xs text-{{ $colorThemaTw }}-600 dark:text-{{ $colorThemaTw }}-400 mt-2 flex items-center gap-1 font-medium">
                        <flux:icon.check-circle class="w-3 h-3" /> Reporte de {{ $nombreMes }}
                    </p>
                </div>
                <div class="w-10 h-10 rounded-full bg-{{ $colorThemaTw }}-100 dark:bg-{{ $colorThemaTw }}-500/10 flex items-center justify-center border border-{{ $colorThemaTw }}-200 dark:border-{{ $colorThemaTw }}-500/20 text-{{ $colorThemaTw }}-600 dark:text-{{ $colorThemaTw }}-400">
                    <flux:icon.document-text class="w-5 h-5" />
                </div>
            </div>
        </div>

        {{-- Total Registros --}}
        <div class="bg-white dark:bg-zinc-900 rounded-3xl p-6 border border-zinc-100 dark:border-zinc-800/80 shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-[0_8px_30px_rgb(0,0,0,0.1)] relative overflow-hidden group">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-blue-500/10 rounded-full blur-2xl group-hover:bg-blue-500/20 transition-all duration-500"></div>
            <div class="flex justify-between items-start relative z-10">
                <div>
                    <p class="text-xs font-semibold text-zinc-500 dark:text-zinc-400 uppercase tracking-widest mb-1">Nº de Registros (Filas)</p>
                    <h3 class="text-4xl font-black text-zinc-800 dark:text-zinc-100 tabular-nums">{{ number_format($kpis['total_registros'], 0, ',', '.') }}</h3>
                    <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-2 font-medium">Transcripciones únicas</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-500/10 flex items-center justify-center border border-blue-200 dark:border-blue-500/20 text-blue-600 dark:text-blue-400">
                    <flux:icon.table-cells class="w-5 h-5" />
                </div>
            </div>
        </div>

        {{-- Promedio Diario --}}
        <div class="bg-white dark:bg-zinc-900 rounded-3xl p-6 border border-zinc-100 dark:border-zinc-800/80 shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-[0_8px_30px_rgb(0,0,0,0.1)] relative overflow-hidden group">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-amber-500/10 rounded-full blur-2xl group-hover:bg-amber-500/20 transition-all duration-500"></div>
            <div class="flex justify-between items-start relative z-10">
                <div>
                    <p class="text-xs font-semibold text-zinc-500 dark:text-zinc-400 uppercase tracking-widest mb-1">Promedio Diario</p>
                    <h3 class="text-4xl font-black text-zinc-800 dark:text-zinc-100 tabular-nums">{{ number_format($kpis['promedio_diario'], 1, ',', '.') }}</h3>
                    <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-2 font-medium">Cantidades por día</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-amber-100 dark:bg-amber-500/10 flex items-center justify-center border border-amber-200 dark:border-amber-500/20 text-amber-600 dark:text-amber-400">
                    <flux:icon.clock class="w-5 h-5" />
                </div>
            </div>
        </div>

        {{-- Sugima (Ingresos/Egresos) o Placeholder --}}
        <div class="bg-white dark:bg-zinc-900 rounded-3xl p-6 border border-zinc-100 dark:border-zinc-800/80 shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-[0_8px_30px_rgb(0,0,0,0.1)] relative overflow-hidden group">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-rose-500/10 rounded-full blur-2xl group-hover:bg-rose-500/20 transition-all duration-500"></div>
            <div class="flex justify-between items-start relative z-10">
                @if($esSugima)
                    <div class="w-full">
                        <p class="text-xs font-semibold text-zinc-500 dark:text-zinc-400 uppercase tracking-widest mb-2">Finanzas (SUGIMA)</p>
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-sm text-green-600 dark:text-green-400 font-medium">Ingresos</span>
                            <span class="text-lg font-bold text-zinc-800 dark:text-zinc-100 tabular-nums">{{ number_format($kpis['total_ingreso'], 0, ',', '.') }}</span>
                        </div>
                        <div class="w-full bg-zinc-200 dark:bg-zinc-800 h-1 rounded-full mb-2">
                            <div class="bg-green-500 dark:bg-green-400 h-1 rounded-full" style="width: {{ $kpis['total_ingreso'] > 0 ? '100%' : '0%' }}"></div>
                        </div>
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-sm text-red-600 dark:text-red-400 font-medium">Egresos</span>
                            <span class="text-lg font-bold text-zinc-800 dark:text-zinc-100 tabular-nums">{{ number_format($kpis['total_egreso'], 0, ',', '.') }}</span>
                        </div>
                        <div class="w-full bg-zinc-200 dark:bg-zinc-800 h-1 rounded-full">
                            <div class="bg-red-500 dark:bg-red-400 h-1 rounded-full" style="width: {{ $kpis['total_egreso'] > 0 ? '100%' : '0%' }}"></div>
                        </div>
                    </div>
                @else
                    <div class="w-full flex flex-col items-center justify-center h-full text-center opacity-40">
                        <flux:icon.sparkles class="w-8 h-8 mb-2 text-zinc-400" />
                        <span class="text-xs uppercase tracking-widest font-bold text-zinc-500">Módulo Optimizado</span>
                    </div>
                @endif
            </div>
        </div>

    </div>

    {{-- Fila de Gráficos 1 --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        
        {{-- Gráfico de Dona: Parroquias --}}
        <div class="bg-white dark:bg-zinc-900 rounded-3xl p-6 border border-zinc-100 dark:border-zinc-800/80 shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-[0_8px_30px_rgb(0,0,0,0.1)] col-span-1 flex flex-col">
            <h3 class="text-sm font-bold text-zinc-800 dark:text-zinc-200 uppercase tracking-wider mb-4 flex items-center gap-2">
                <flux:icon.map class="w-4 h-4 text-{{ $colorThemaTw }}-500" />
                Distribución por Parroquia
            </h3>
            <div class="flex-1 relative w-full flex items-center justify-center min-h-[250px]" wire:ignore>
                <canvas id="parroquiasChart"></canvas>
            </div>
        </div>

        {{-- Gráfico de Líneas: Tendencia por Días --}}
        <div class="bg-white dark:bg-zinc-900 rounded-3xl p-6 border border-zinc-100 dark:border-zinc-800/80 shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-[0_8px_30px_rgb(0,0,0,0.1)] col-span-1 lg:col-span-2 flex flex-col">
            <h3 class="text-sm font-bold text-zinc-800 dark:text-zinc-200 uppercase tracking-wider mb-4 flex items-center gap-2">
                <flux:icon.chart-bar-square class="w-4 h-4 text-{{ $colorThemaTw }}-500" />
                Actividad por Día ({{ $nombreMes }})
            </h3>
            <div class="flex-1 relative w-full min-h-[250px]" wire:ignore>
                <canvas id="diasChart"></canvas>
            </div>
        </div>

    </div>

    {{-- Fila de Gráficos 2 --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        
        {{-- Gráfico de Barras: Comunas --}}
        <div class="bg-white dark:bg-zinc-900 rounded-3xl p-6 border border-zinc-100 dark:border-zinc-800/80 shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-[0_8px_30px_rgb(0,0,0,0.1)] flex flex-col">
            <h3 class="text-sm font-bold text-zinc-800 dark:text-zinc-200 uppercase tracking-wider mb-4 flex items-center gap-2">
                <flux:icon.building-office class="w-4 h-4 text-{{ $colorThemaTw }}-500" />
                Comunas Activas
            </h3>
            <div class="flex-1 relative w-full min-h-[600px]" wire:ignore>
                <canvas id="comunasChart"></canvas>
            </div>
        </div>

        {{-- Gráfico de Barras Horizontales: Sectores --}}
        <div class="bg-white dark:bg-zinc-900 rounded-3xl p-6 border border-zinc-100 dark:border-zinc-800/80 shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-[0_8px_30px_rgb(0,0,0,0.1)] flex flex-col">
            <h3 class="text-sm font-bold text-zinc-800 dark:text-zinc-200 uppercase tracking-wider mb-4 flex items-center gap-2">
                <flux:icon.map-pin class="w-4 h-4 text-{{ $colorThemaTw }}-500" />
                Sectores Activos
            </h3>
            <div class="flex-1 relative w-full min-h-[600px]" wire:ignore>
                <canvas id="sectoresChart"></canvas>
            </div>
        </div>

    </div>
    
    {{-- Dependencia de Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    @script
    <script>
        let charts = {};

        const renderCharts = () => {
            // Destruir gráficos anteriores para evitar superposiciones o memory leaks
            if (charts.parroquias) charts.parroquias.destroy();
            if (charts.dias) charts.dias.destroy();
            if (charts.comunas) charts.comunas.destroy();
            if (charts.sectores) charts.sectores.destroy();

            // Obtener datos directamente del componente Livewire
            const themeHex = $wire.colorThemaHex;
            const datosParroquias = $wire.datosParroquias;
            const datosComunas = $wire.datosComunas;
            const datosSectores = $wire.datosSectores;
            const datosDias = $wire.datosDias;

            // Determinar colores según el modo actual
            const isDarkMode = document.documentElement.classList.contains('dark');
            const textColor = isDarkMode ? '#a1a1aa' : '#71717a';
            const gridColor = isDarkMode ? 'rgba(255, 255, 255, 0.05)' : 'rgba(0, 0, 0, 0.04)';
            const cardBg = isDarkMode ? '#18181b' : '#ffffff';

            Chart.defaults.color = textColor;
            Chart.defaults.font.family = '"Inter", "Geist", ui-sans-serif, system-ui, sans-serif';
            
            // Animaciones fluidas
            Chart.defaults.animation = {
                duration: 1000,
                easing: 'easeInOutQuart'
            };
            
            // Tooltip Custom HTML
            const getOrCreateTooltip = (chart) => {
                let tooltipEl = chart.canvas.parentNode.querySelector('div.custom-tooltip');
                if (!tooltipEl) {
                    tooltipEl = document.createElement('div');
                    tooltipEl.classList.add('custom-tooltip');
                    tooltipEl.style.background = isDarkMode ? 'rgba(24, 24, 27, 0.95)' : 'rgba(255, 255, 255, 0.95)';
                    tooltipEl.style.backdropFilter = 'blur(8px)';
                    tooltipEl.style.borderRadius = '12px';
                    tooltipEl.style.color = isDarkMode ? '#fff' : '#18181b';
                    tooltipEl.style.opacity = 1;
                    tooltipEl.style.pointerEvents = 'none';
                    tooltipEl.style.position = 'absolute';
                    tooltipEl.style.transform = 'translate(-50%, -110%)';
                    tooltipEl.style.transition = 'opacity 0.15s ease';
                    tooltipEl.style.boxShadow = isDarkMode ? '0 10px 25px -5px rgba(0, 0, 0, 0.5), 0 8px 10px -6px rgba(0, 0, 0, 0.3)' : '0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.01)';
                    tooltipEl.style.border = isDarkMode ? '1px solid #3f3f46' : '1px solid #e4e4e7';
                    tooltipEl.style.zIndex = 50;

                    const table = document.createElement('table');
                    table.style.margin = '0px';
                    tooltipEl.appendChild(table);
                    chart.canvas.parentNode.appendChild(tooltipEl);
                }
                return tooltipEl;
            };

            const externalTooltipHandler = (context) => {
                const {chart, tooltip} = context;
                const tooltipEl = getOrCreateTooltip(chart);

                if (tooltip.opacity === 0) {
                    tooltipEl.style.opacity = 0;
                    return;
                }

                if (tooltip.body) {
                    const titleLines = tooltip.title || [];
                    const bodyLines = tooltip.body.map(b => b.lines);

                    const tableRoot = tooltipEl.querySelector('table');
                    while (tableRoot.firstChild) {
                        tableRoot.firstChild.remove();
                    }

                    const tableHead = document.createElement('thead');
                    titleLines.forEach(title => {
                        const tr = document.createElement('tr');
                        tr.style.borderWidth = 0;
                        const th = document.createElement('th');
                        th.style.borderWidth = 0;
                        th.style.padding = '12px 16px 8px 16px';
                        th.style.textAlign = 'left';
                        th.style.fontWeight = '600';
                        th.style.fontSize = '12px';
                        th.style.textTransform = 'uppercase';
                        th.style.letterSpacing = '0.05em';
                        th.style.color = isDarkMode ? '#a1a1aa' : '#71717a';
                        th.appendChild(document.createTextNode(title));
                        tr.appendChild(th);
                        tableHead.appendChild(tr);
                    });

                    const tableBody = document.createElement('tbody');
                    bodyLines.forEach((body, i) => {
                        const colors = tooltip.labelColors[i];

                        const span = document.createElement('span');
                        span.style.background = colors.backgroundColor;
                        span.style.borderColor = colors.borderColor;
                        span.style.borderWidth = '2px';
                        span.style.marginRight = '10px';
                        span.style.height = '12px';
                        span.style.width = '12px';
                        span.style.borderRadius = '50%';
                        span.style.display = 'inline-block';
                        span.style.boxShadow = '0 0 0 1px rgba(255,255,255,0.1) inset';

                        const tr = document.createElement('tr');
                        tr.style.backgroundColor = 'inherit';
                        tr.style.borderWidth = 0;

                        const td = document.createElement('td');
                        td.style.borderWidth = 0;
                        td.style.padding = titleLines.length ? '0px 16px 12px 16px' : '12px 16px 12px 16px';
                        td.style.display = 'flex';
                        td.style.alignItems = 'center';
                        td.style.fontSize = '14px';
                        td.style.fontWeight = '600';

                        td.appendChild(span);
                        td.appendChild(document.createTextNode(body));
                        tr.appendChild(td);
                        tableBody.appendChild(tr);
                    });

                    if (titleLines.length) tableRoot.appendChild(tableHead);
                    tableRoot.appendChild(tableBody);
                }

                const {offsetLeft: positionX, offsetTop: positionY} = chart.canvas;
                tooltipEl.style.opacity = 1;
                tooltipEl.style.left = positionX + tooltip.caretX + 'px';
                tooltipEl.style.top = positionY + tooltip.caretY + 'px';
            };

            const baseOptions = {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                plugins: {
                    legend: {
                        position: 'top',
                        align: 'end',
                        labels: {
                            usePointStyle: true,
                            pointStyle: 'circle',
                            padding: 20,
                            font: {
                                family: 'Inter, sans-serif',
                                weight: '500',
                                size: 12
                            }
                        }
                    },
                    tooltip: {
                        enabled: false,
                        position: 'nearest',
                        external: externalTooltipHandler
                    }
                }
            };

            const gridOptions = {
                color: gridColor,
                drawBorder: false,
                drawTicks: false,
            };

            const palette = [
                themeHex, 
                '#3b82f6', '#f59e0b', '#ec4899', '#8b5cf6', 
                '#10b981', '#06b6d4', '#f43f5e', '#6366f1', '#84cc16'
            ];

            // 1. Gráfico de Dona (Parroquias)
            const ctxParroquias = document.getElementById('parroquiasChart');
            if(ctxParroquias && datosParroquias.length > 0) {
                charts.parroquias = new Chart(ctxParroquias, {
                    type: 'doughnut',
                    data: {
                        labels: datosParroquias.map(d => d.nombre),
                        datasets: [{
                            data: datosParroquias.map(d => d.total),
                            backgroundColor: palette,
                            borderWidth: 0,
                            hoverOffset: 4
                        }]
                    },
                    options: {
                        ...baseOptions,
                        plugins: {
                            ...baseOptions.plugins,
                            legend: {
                                position: 'right',
                                align: 'center',
                                labels: {
                                    usePointStyle: true,
                                    pointStyle: 'circle',
                                    padding: 20,
                                    font: { family: 'Inter, sans-serif', weight: '500', size: 12 }
                                }
                            }
                        },
                        cutout: '75%',
                        elements: {
                            arc: {
                                borderRadius: 4,
                                borderJoinStyle: 'round'
                            }
                        }
                    }
                });
            }

            // 2. Gráfico de Líneas (Días)
            const ctxDias = document.getElementById('diasChart');
            if(ctxDias && datosDias.length > 0) {
                const labelsDias = Array.from({length: 31}, (_, i) => i + 1);
                const mapaDias = new Map(datosDias.map(d => [parseInt(d.dia), parseFloat(d.total)]));
                const dataDiasCompletos = labelsDias.map(d => mapaDias.get(d) || 0);

                let hex = themeHex.replace('#', '');
                let r = parseInt(hex.substring(0,2), 16);
                let g = parseInt(hex.substring(2,4), 16);
                let b = parseInt(hex.substring(4,6), 16);

                const ctx = ctxDias.getContext('2d');
                const gradient = ctx.createLinearGradient(0, 0, 0, 400);
                gradient.addColorStop(0, `rgba(${r}, ${g}, ${b}, 0.4)`);
                gradient.addColorStop(1, `rgba(${r}, ${g}, ${b}, 0)`);

                charts.dias = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labelsDias,
                        datasets: [{
                            label: 'Procesado',
                            data: dataDiasCompletos,
                            borderColor: themeHex,
                            backgroundColor: gradient,
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4, // Curvas suaves
                            pointBackgroundColor: cardBg,
                            pointBorderColor: themeHex,
                            pointBorderWidth: 2,
                            pointRadius: 0, // Ocultar por defecto
                            pointHoverRadius: 6, // Mostrar en hover
                            hitRadius: 10
                        }]
                    },
                    options: {
                        ...baseOptions,
                        scales: {
                            y: { 
                                beginAtZero: true,
                                grid: gridOptions,
                                border: { display: false }
                            },
                            x: { 
                                grid: { display: false },
                                border: { display: false }
                            }
                        }
                    }
                });
            }

            // 3. Gráfico de Barras (Comunas)
            const ctxComunas = document.getElementById('comunasChart');
            if(ctxComunas && datosComunas.length > 0) {
                
                const ctx = ctxComunas.getContext('2d');
                let hex = themeHex.replace('#', '');
                let r = parseInt(hex.substring(0,2), 16);
                let g = parseInt(hex.substring(2,4), 16);
                let b = parseInt(hex.substring(4,6), 16);
                
                const barGradient = ctx.createLinearGradient(400, 0, 0, 0);
                barGradient.addColorStop(0, `rgba(${r}, ${g}, ${b}, 1)`);
                barGradient.addColorStop(1, `rgba(${r}, ${g}, ${b}, 0.6)`);

                charts.comunas = new Chart(ctxComunas, {
                    type: 'bar',
                    data: {
                        labels: datosComunas.map(d => d.nombre.length > 15 ? d.nombre.substring(0,15)+'...' : d.nombre),
                        datasets: [{
                            label: 'Procesado',
                            data: datosComunas.map(d => d.total),
                            backgroundColor: barGradient,
                            borderRadius: 6,
                            borderSkipped: false,
                            barPercentage: 0.5
                        }]
                    },
                    options: {
                        ...baseOptions,
                        interaction: { mode: 'nearest', axis: 'y', intersect: false },
                        indexAxis: 'y',
                        scales: {
                            x: { 
                                beginAtZero: true,
                                grid: gridOptions,
                                border: { display: false }
                            },
                            y: { 
                                grid: { display: false },
                                border: { display: false }
                            }
                        }
                    }
                });
            }

            // 4. Gráfico Horizontal (Sectores)
            const ctxSectores = document.getElementById('sectoresChart');
            if(ctxSectores && datosSectores.length > 0) {
                
                const ctx = ctxSectores.getContext('2d');
                const barGradientAlt = ctx.createLinearGradient(400, 0, 0, 0);
                barGradientAlt.addColorStop(0, '#3b82f6');
                barGradientAlt.addColorStop(1, 'rgba(59, 130, 246, 0.6)');

                charts.sectores = new Chart(ctxSectores, {
                    type: 'bar',
                    data: {
                        labels: datosSectores.map(d => d.nombre.length > 20 ? d.nombre.substring(0,20)+'...' : d.nombre),
                        datasets: [{
                            label: 'Procesado',
                            data: datosSectores.map(d => d.total),
                            backgroundColor: barGradientAlt,
                            borderRadius: 6,
                            borderSkipped: false,
                            barPercentage: 0.6
                        }]
                    },
                    options: {
                        ...baseOptions,
                        interaction: { mode: 'nearest', axis: 'y', intersect: false },
                        indexAxis: 'y',
                        scales: {
                            x: { 
                                beginAtZero: true,
                                grid: gridOptions,
                                border: { display: false }
                            },
                            y: { 
                                grid: { display: false },
                                border: { display: false }
                            }
                        }
                    }
                });
            }
        };

        // Escuchar eventos de actualización desde el componente
        $wire.on('refreshCharts', () => {
            setTimeout(renderCharts, 50);
        });

        // Renderizar inmediatamente cuando se evalúa el script de Livewire
        setTimeout(() => {
            if (typeof Chart !== 'undefined') {
                renderCharts();
            } else {
                // Por si chart.js demora en cargar desde el CDN
                const script = document.querySelector('script[src*="chart.js"]');
                if(script) script.addEventListener('load', renderCharts);
            }
        }, 100);

    </script>
    @endscript
</div>

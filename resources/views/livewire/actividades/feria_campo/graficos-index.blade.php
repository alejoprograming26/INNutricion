<div class="mb-6">
    {{-- Topbar / Header --}}
    <div
        class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4 bg-white dark:bg-zinc-900 p-4 lg:p-6 rounded-2xl border border-zinc-200 dark:border-zinc-800 shadow-sm">
        <div class="flex items-center gap-4">
            <div
                class="w-10 h-10 rounded-xl bg-indigo-100 dark:bg-indigo-500/10 flex items-center justify-center border border-indigo-200 dark:border-indigo-500/20">
                <flux:icon.chart-pie class="w-6 h-6 text-indigo-600 dark:text-indigo-400" />
            </div>
            <div>
                <h1 class="text-2xl font-bold text-zinc-800 dark:text-zinc-100 tracking-tight">Dashboard: Feria de Campo
                </h1>
                <p class="text-sm text-zinc-500 dark:text-zinc-400">
                    Municipio: <span
                        class="text-zinc-700 dark:text-zinc-300 font-medium">{{ $graphMunicipioNombre }}</span>
                    &bull; {{ \Carbon\Carbon::create(null, $graphMonth)->translatedFormat('F') }} {{ $graphAno }}
                </p>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <div class="flex items-center gap-2">
                <flux:select wire:model.live="graphMonth" class="w-32">
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
                <flux:input wire:model.live="graphAno" type="number" class="w-24" />
            </div>

            <flux:button icon="arrow-left" variant="subtle" href="{{ route('admin.actividades.feria.index') }}"
                wire:navigate>
                Volver a Tabla
            </flux:button>
        </div>
    </div>

    {{-- Tarjetas KPI --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        {{-- Total Atendidos --}}
        <div
            class="bg-white dark:bg-zinc-900 rounded-3xl p-6 border border-zinc-100 dark:border-zinc-800/80 shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-[0_8px_30px_rgb(0,0,0,0.1)] relative overflow-hidden group">
            <div
                class="absolute -right-6 -top-6 w-24 h-24 bg-indigo-500/10 rounded-full blur-2xl group-hover:bg-indigo-500/20 transition-all duration-500">
            </div>
            <div class="flex justify-between items-start relative z-10">
                <div>
                    <p class="text-xs font-semibold text-zinc-500 dark:text-zinc-400 uppercase tracking-widest mb-1">
                        Impacto (Pers.)</p>
                    <h3 class="text-4xl font-black text-zinc-800 dark:text-zinc-100 tabular-nums">
                        {{ number_format($graphKpis['total_cantidad'], 0, ',', '.') }}</h3>
                    <p class="text-xs text-indigo-600 dark:text-indigo-400 mt-2 flex items-center gap-1 font-medium">
                        <flux:icon.check-circle class="w-3 h-3" /> Beneficiarios en Ferias
                    </p>
                </div>
                <div
                    class="w-10 h-10 rounded-full bg-indigo-100 dark:bg-indigo-500/10 flex items-center justify-center border border-indigo-200 dark:border-indigo-500/20 text-indigo-600 dark:text-indigo-400">
                    <flux:icon.users class="w-5 h-5" />
                </div>
            </div>
        </div>

        {{-- Total Registros --}}
        <div
            class="bg-white dark:bg-zinc-900 rounded-3xl p-6 border border-zinc-100 dark:border-zinc-800/80 shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-[0_8px_30px_rgb(0,0,0,0.1)] relative overflow-hidden group">
            <div
                class="absolute -right-6 -top-6 w-24 h-24 bg-violet-500/10 rounded-full blur-2xl group-hover:bg-violet-500/20 transition-all duration-500">
            </div>
            <div class="flex justify-between items-start relative z-10">
                <div>
                    <p class="text-xs font-semibold text-zinc-500 dark:text-zinc-400 uppercase tracking-widest mb-1">Nº
                        de Ferias</p>
                    <h3 class="text-4xl font-black text-zinc-800 dark:text-zinc-100 tabular-nums">
                        {{ number_format($graphKpis['total_registros'], 0, ',', '.') }}</h3>
                    <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-2 font-medium">Eventos realizados</p>
                </div>
                <div
                    class="w-10 h-10 rounded-full bg-violet-100 dark:bg-violet-500/10 flex items-center justify-center border border-violet-200 dark:border-violet-500/20 text-violet-600 dark:text-violet-400">
                    <flux:icon.shopping-cart class="w-5 h-5" />
                </div>
            </div>
        </div>

        {{-- Promedio Diario --}}
        <div
            class="bg-white dark:bg-zinc-900 rounded-3xl p-6 border border-zinc-100 dark:border-zinc-800/80 shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-[0_8px_30px_rgb(0,0,0,0.1)] relative overflow-hidden group">
            <div
                class="absolute -right-6 -top-6 w-24 h-24 bg-amber-500/10 rounded-full blur-2xl group-hover:bg-amber-500/20 transition-all duration-500">
            </div>
            <div class="flex justify-between items-start relative z-10">
                <div>
                    <p class="text-xs font-semibold text-zinc-500 dark:text-zinc-400 uppercase tracking-widest mb-1">
                        Promedio Diario</p>
                    <h3 class="text-4xl font-black text-zinc-800 dark:text-zinc-100 tabular-nums">
                        {{ number_format($graphKpis['promedio_diario'], 1, ',', '.') }}</h3>
                    <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-2 font-medium">Atenciones por día</p>
                </div>
                <div
                    class="w-10 h-10 rounded-full bg-amber-100 dark:bg-amber-500/10 flex items-center justify-center border border-amber-200 dark:border-amber-500/20 text-amber-600 dark:text-amber-400">
                    <flux:icon.bolt class="w-5 h-5" />
                </div>
            </div>
        </div>

        {{-- Tendencia --}}
        <div
            class="bg-white dark:bg-zinc-900 rounded-3xl p-6 border border-zinc-100 dark:border-zinc-800/80 shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-[0_8px_30px_rgb(0,0,0,0.1)] relative overflow-hidden group">
            <div
                class="absolute -right-6 -top-6 w-24 h-24 bg-indigo-500/10 rounded-full blur-2xl group-hover:bg-indigo-500/20 transition-all duration-500">
            </div>
            <div class="flex justify-between items-start relative z-10">
                <div class="w-full">
                    <p class="text-xs font-semibold text-zinc-500 dark:text-zinc-400 uppercase tracking-widest mb-1">
                        Antropometría</p>
                    <div class="mt-3">
                        <div class="flex justify-between items-end mb-1">
                            <span class="text-2xl font-black text-zinc-800 dark:text-zinc-100">{{ number_format($graphKpis['con_antrometria'] ?? 0, 0, ',', '.') }}</span>
                            <span class="text-xs font-bold text-indigo-600 dark:text-indigo-400">Evaluados</span>
                        </div>
                    </div>
                </div>
                <div
                    class="w-10 h-10 rounded-full bg-indigo-100 dark:bg-indigo-500/10 flex items-center justify-center border border-indigo-200 dark:border-indigo-500/20 text-indigo-600 dark:text-indigo-400">
                    <flux:icon.scale class="w-5 h-5" />
                </div>
            </div>
        </div>
    </div>
    
    {{-- Fila de Gráficos Específicos --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <div class="bg-white dark:bg-zinc-900 rounded-3xl p-6 border border-zinc-100 dark:border-zinc-800/80 shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-[0_8px_30px_rgb(0,0,0,0.1)] flex flex-col">
            <h3 class="text-sm font-bold text-zinc-800 dark:text-zinc-200 uppercase tracking-wider mb-4 flex items-center gap-2">
                <flux:icon.chart-pie class="w-4 h-4 text-indigo-500" />
                Servicios Presentes (Radar)
            </h3>
            <div class="flex-1 relative w-full flex items-center justify-center min-h-[300px]" wire:ignore>
                <canvas id="serviciosRadar"></canvas>
            </div>
        </div>

        <div class="bg-white dark:bg-zinc-900 rounded-3xl p-6 border border-zinc-100 dark:border-zinc-800/80 shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-[0_8px_30px_rgb(0,0,0,0.1)] flex flex-col">
            <h3 class="text-sm font-bold text-zinc-800 dark:text-zinc-200 uppercase tracking-wider mb-4 flex items-center gap-2">
                <flux:icon.chart-bar class="w-4 h-4 text-indigo-500" />
                Tipología Nutricional (Polar Area)
            </h3>
            <div class="flex-1 relative w-full min-h-[300px]" wire:ignore>
                <canvas id="tipologiaPolar"></canvas>
            </div>
        </div>
    </div>

    {{-- Fila de Gráficos 1 --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <div
            class="bg-white dark:bg-zinc-900 rounded-3xl p-6 border border-zinc-100 dark:border-zinc-800/80 shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-[0_8px_30px_rgb(0,0,0,0.1)] flex flex-col">
            <h3
                class="text-sm font-bold text-zinc-800 dark:text-zinc-200 uppercase tracking-wider mb-4 flex items-center gap-2">
                <flux:icon.map class="w-4 h-4 text-indigo-500" />
                Distribución por Parroquia
            </h3>
            <div class="flex-1 relative w-full flex items-center justify-center min-h-[300px]" wire:ignore>
                <canvas id="parroquiasChart"></canvas>
            </div>
        </div>

        <div
            class="bg-white dark:bg-zinc-900 rounded-3xl p-6 border border-zinc-100 dark:border-zinc-800/80 shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-[0_8px_30px_rgb(0,0,0,0.1)] lg:col-span-2 flex flex-col">
            <h3
                class="text-sm font-bold text-zinc-800 dark:text-zinc-200 uppercase tracking-wider mb-4 flex items-center gap-2">
                <flux:icon.chart-bar-square class="w-4 h-4 text-indigo-500" />
                Participación Diaria
            </h3>
            <div class="flex-1 relative w-full min-h-[300px]" wire:ignore>
                <canvas id="diasChart"></canvas>
            </div>
        </div>
    </div>

    {{-- Fila de Gráficos 2 --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <div
            class="bg-white dark:bg-zinc-900 rounded-3xl p-6 border border-zinc-100 dark:border-zinc-800/80 shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-[0_8px_30px_rgb(0,0,0,0.1)] flex flex-col">
            <h3
                class="text-sm font-bold text-zinc-800 dark:text-zinc-200 uppercase tracking-wider mb-4 flex items-center gap-2">
                <flux:icon.building-office class="w-4 h-4 text-indigo-500" />
                Comunas Atendidas
            </h3>
            <div class="flex-1 relative w-full min-h-[400px]" wire:ignore>
                <canvas id="comunasChart"></canvas>
            </div>
        </div>

        <div
            class="bg-white dark:bg-zinc-900 rounded-3xl p-6 border border-zinc-100 dark:border-zinc-800/80 shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-[0_8px_30px_rgb(0,0,0,0.1)] flex flex-col">
            <h3
                class="text-sm font-bold text-zinc-800 dark:text-zinc-200 uppercase tracking-wider mb-4 flex items-center gap-2">
                <flux:icon.map-pin class="w-4 h-4 text-indigo-500" />
                Sectores con Mayor Impacto
            </h3>
            <div class="flex-1 relative w-full min-h-[400px]" wire:ignore>
                <canvas id="sectoresChart"></canvas>
            </div>
        </div>
    </div>

    {{-- Sección de Condición por Población --}}
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6 mb-6">
        {{-- Barras Verticales: Condición por Población --}}
        <div class="lg:col-span-2 bg-white dark:bg-zinc-900 rounded-3xl p-6 border border-zinc-100 dark:border-zinc-800/80 shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-[0_8px_30px_rgb(0,0,0,0.1)] flex flex-col">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-bold text-zinc-800 dark:text-zinc-200 uppercase tracking-wider flex items-center gap-2">
                    <div class="w-2 h-2 rounded-full bg-violet-500"></div>
                    Distribución por Condición
                </h3>
            </div>
            <div class="flex-1 relative w-full flex items-center justify-center min-h-[320px]" wire:ignore>
                <canvas id="condicionFeriaChart"></canvas>
            </div>
        </div>

        {{-- Barras horizontales con totales por condición --}}
        <div class="lg:col-span-3 bg-white dark:bg-zinc-900 rounded-3xl p-6 border border-zinc-100 dark:border-zinc-800/80 shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-[0_8px_30px_rgb(0,0,0,0.1)] flex flex-col">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-bold text-zinc-800 dark:text-zinc-200 uppercase tracking-wider flex items-center gap-2">
                    <flux:icon.user-group class="w-4 h-4 text-violet-500" />
                    Detalle por Condición de la Población
                </h3>
                <span class="text-xs font-medium bg-violet-100 dark:bg-violet-500/20 text-violet-700 dark:text-violet-300 px-2 py-1 rounded-md">
                    {{ \Carbon\Carbon::create(null, $graphMonth)->translatedFormat('F') }} {{ $graphAno }}
                </span>
            </div>
            @php
                $condFeriaData = $graphCondicion;
                $totalCondFeria = collect($condFeriaData)->sum('total');
            @endphp
            @if($totalCondFeria > 0)
            <div class="space-y-3 flex-1 overflow-y-auto">
                @foreach($condFeriaData as $cond)
                    @if($cond['total'] > 0)
                    <div class="flex items-center gap-3">
                        <span class="w-24 text-[11px] font-bold text-zinc-600 dark:text-zinc-400 uppercase tracking-wide text-right shrink-0">
                            {{ $cond['nombre'] }}
                        </span>
                        <div class="flex-1 bg-zinc-100 dark:bg-zinc-800 rounded-full h-6 overflow-hidden">
                            <div class="h-full rounded-full flex items-center justify-end pr-2 transition-all duration-500"
                                 style="width: {{ $totalCondFeria > 0 ? round(($cond['total'] / $totalCondFeria) * 100, 1) : 0 }}%; background-color: {{ $cond['color'] }}">
                                <span class="text-[10px] font-black text-white drop-shadow">{{ $cond['total'] }}</span>
                            </div>
                        </div>
                        <span class="w-10 text-[11px] font-bold text-zinc-500 shrink-0">
                            {{ $totalCondFeria > 0 ? round(($cond['total'] / $totalCondFeria) * 100, 1) : 0 }}%
                        </span>
                    </div>
                    @endif
                @endforeach
            </div>
            @else
            <div class="flex-1 flex items-center justify-center text-zinc-400 text-sm">
                <div class="text-center">
                    <flux:icon.user-group class="w-10 h-10 mx-auto mb-2 opacity-30" />
                    <p>Sin datos de condición registrados</p>
                </div>
            </div>
            @endif
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    @script
        <script>
            let chartInstances = {};

            const initCharts = () => {
                ['parroquiasChart', 'diasChart', 'comunasChart', 'sectoresChart', 'serviciosRadar', 'tipologiaPolar', 'condicionFeriaChart'].forEach(id => {
                    const existing = Chart.getChart(id);
                    if (existing) existing.destroy();
                });

                const isDark = document.documentElement.classList.contains('dark');
                const textColor = isDark ? '#a1a1aa' : '#71717a';
                const gridColor = isDark ? 'rgba(255, 255, 255, 0.05)' : 'rgba(0, 0, 0, 0.04)';
                const themeColor = '#6366f1'; // Indigo 500

                const tooltipOptions = {
                    backgroundColor: isDark ? 'rgba(24, 24, 27, 0.9)' : 'rgba(255, 255, 255, 0.9)',
                    titleColor: isDark ? '#f4f4f5' : '#27272a',
                    bodyColor: isDark ? '#f4f4f5' : '#27272a',
                    borderColor: isDark ? 'rgba(255,255,255,0.1)' : 'rgba(0,0,0,0.1)',
                    borderWidth: 1,
                    padding: 12,
                    boxPadding: 6,
                    usePointStyle: true,
                    titleFont: { size: 13, weight: 'bold' },
                    bodyFont: { size: 12 }
                };

                const dataParroquias = $wire.graphParroquias;
                const dataComunas = $wire.graphComunas;
                const dataSectores = $wire.graphSectores;
                const dataDias = $wire.graphDias;

                Chart.defaults.color = textColor;
                Chart.defaults.font.family = 'Inter, system-ui, sans-serif';

                const ctxR = document.getElementById('serviciosRadar');
                if(ctxR && $wire.graphServicios && $wire.graphServicios.length) {
                    chartInstances.servicios = new Chart(ctxR, { 
                        type: 'radar', 
                        data: { 
                            labels: ['Venta Nutrivida', 'Antropometría', 'Campaña 4S'], 
                            datasets: [{ 
                                label: 'Ferias', 
                                data: $wire.graphServicios, 
                                backgroundColor: 'rgba(99, 102, 241, 0.2)', 
                                borderColor: themeColor, 
                                pointBackgroundColor: themeColor, 
                                borderWidth: 2 
                            }] 
                        }, 
                        options: { 
                            responsive: true, 
                            maintainAspectRatio: false, 
                            scales: { r: { grid: { color: gridColor }, angleLines: { color: gridColor }, ticks: { display: false } } }, 
                            plugins: { legend: { display: false } } 
                        } 
                    });
                }
                
                const ctxPo = document.getElementById('tipologiaPolar');
                if(ctxPo && $wire.graphTipologia && $wire.graphTipologia.length) {
                    chartInstances.tipologia = new Chart(ctxPo, { 
                        type: 'polarArea', 
                        data: { 
                            labels: ['Tipo A', 'Tipo B', 'Tipo A+'], 
                            datasets: [{ 
                                data: $wire.graphTipologia.map(t => t.total), 
                                backgroundColor: ['#4F46E5', '#10B981', '#F59E0B'], 
                                borderWidth: 0 
                            }] 
                        }, 
                        options: { 
                            responsive: true, 
                            maintainAspectRatio: false, 
                            scales: { r: { grid: { color: gridColor }, ticks: { display: false } } }, 
                            plugins: { legend: { position: 'right', labels: { usePointStyle: true } } } 
                        } 
                    });
                }

                const ctxP = document.getElementById('parroquiasChart');
                if (ctxP && dataParroquias.length) {
                    chartInstances.parroquias = new Chart(ctxP, {
                        type: 'doughnut',
                        data: {
                            labels: dataParroquias.map(d => d.nombre),
                            datasets: [{
                                data: dataParroquias.map(d => d.total),
                                backgroundColor: ['#4F46E5', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6',
                                    '#EC4899', '#06B6D4', '#84CC16', '#F97316', '#3B82F6', '#14B8A6',
                                    '#F43F5E', '#D946EF', '#6366F1', '#22C55E'
                                ],
                                borderWidth: 0,
                                hoverOffset: 15
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: {
                                        usePointStyle: true,
                                        padding: 20
                                    }
                                }
                            },
                            cutout: '70%'
                        }
                    });
                }

                const ctxD = document.getElementById('diasChart');
                if (ctxD && dataDias.length) {
                    const labels = Array.from({
                        length: 31
                    }, (_, i) => i + 1);
                    const values = labels.map(l => {
                        const found = dataDias.find(d => parseInt(d.dia) === l);
                        return found ? found.total : 0;
                    });
                    const gradient = ctxD.getContext('2d').createLinearGradient(0, 0, 0, 300);
                    gradient.addColorStop(0, 'rgba(99, 102, 241, 0.3)');
                    gradient.addColorStop(1, 'rgba(99, 102, 241, 0)');
                    chartInstances.dias = new Chart(ctxD, {
                        type: 'line',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Impacto',
                                data: values,
                                borderColor: themeColor,
                                backgroundColor: gradient,
                                fill: true,
                                tension: 0.4,
                                pointRadius: 0,
                                pointHoverRadius: 6,
                                borderWidth: 3
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    grid: {
                                        color: gridColor
                                    },
                                    border: {
                                        display: false
                                    }
                                },
                                x: {
                                    grid: {
                                        display: false
                                    },
                                    border: {
                                        display: false
                                    }
                                }
                            },
                            plugins: {
                                legend: {
                                    display: false
                                }
                            }
                        }
                    });
                }

                const ctxC = document.getElementById('comunasChart');
                if (ctxC && dataComunas.length) {
                    chartInstances.comunas = new Chart(ctxC, {
                        type: 'bar',
                        data: {
                            labels: dataComunas.map(d => d.nombre.substring(0, 15)),
                            datasets: [{
                                label: 'Impacto',
                                data: dataComunas.map(d => d.total),
                                backgroundColor: themeColor,
                                borderRadius: 8
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            indexAxis: 'y',
                            scales: {
                                x: {
                                    grid: {
                                        color: gridColor
                                    },
                                    border: {
                                        display: false
                                    }
                                },
                                y: {
                                    grid: {
                                        display: false
                                    },
                                    border: {
                                        display: false
                                    }
                                }
                            },
                            plugins: {
                                legend: {
                                    display: false
                                }
                            }
                        }
                    });
                }

                const ctxS = document.getElementById('sectoresChart');
                if (ctxS && dataSectores.length) {
                    chartInstances.sectores = new Chart(ctxS, {
                        type: 'bar',
                        data: {
                            labels: dataSectores.map(d => d.nombre.substring(0, 15)),
                            datasets: [{
                                label: 'Impacto',
                                data: dataSectores.map(d => d.total),
                                backgroundColor: '#8b5cf6',
                                borderRadius: 8
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            indexAxis: 'y',
                            scales: {
                                x: {
                                    grid: {
                                        color: gridColor
                                    },
                                    border: {
                                        display: false
                                    }
                                },
                                y: {
                                    grid: {
                                        display: false
                                    },
                                    border: {
                                        display: false
                                    }
                                }
                            },
                            plugins: {
                                legend: {
                                    display: false
                                }
                            }
                        }
                    });
                }

                // Condición por Población (Barras Verticales)
                const ctxCond = document.getElementById('condicionFeriaChart');
                const condFiltered = $wire.graphCondicion ? $wire.graphCondicion.filter(d => d.total > 0) : [];
                if (ctxCond && condFiltered.length) {
                    chartInstances.condicion = new Chart(ctxCond, {
                        type: 'bar',
                        data: {
                            labels: condFiltered.map(d => d.nombre),
                            datasets: [{
                                label: 'Total Atendidos',
                                data: condFiltered.map(d => d.total),
                                backgroundColor: condFiltered.map(d => d.color),
                                borderRadius: 6,
                                borderWidth: 0,
                                barPercentage: 0.6
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    grid: { color: gridColor, borderDash: [4, 4] },
                                    border: { display: false }
                                },
                                x: {
                                    grid: { display: false },
                                    border: { display: false }
                                }
                            },
                            plugins: {
                                legend: { display: false },
                                tooltip: tooltipOptions
                            }
                        }
                    });
                }
            };

            $wire.on('refreshCharts', () => {
                setTimeout(initCharts, 100);
            });

            setTimeout(() => {
                if (typeof Chart !== 'undefined') {
                    initCharts();
                } else {
                    const s = document.querySelector('script[src*="chart.js"]');
                    if (s) s.addEventListener('load', initCharts);
                }
            }, 200);
        </script>
    @endscript
</div>

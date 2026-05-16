<div class="mb-6">
    {{-- Topbar / Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4 bg-white/80 dark:bg-zinc-900/80 backdrop-blur-xl p-4 lg:p-6 rounded-2xl border border-zinc-200 dark:border-zinc-800 shadow-sm relative overflow-hidden">
        <div class="absolute -left-10 -top-10 w-32 h-32 bg-lime-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute right-0 bottom-0 w-32 h-32 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>
        
        <div class="flex items-center gap-4 relative z-10">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-lime-400 to-emerald-600 flex items-center justify-center shadow-lg shadow-lime-500/20 text-white">
                <flux:icon.shield-check class="w-7 h-7" />
            </div>
            <div>
                <h1 class="text-2xl font-black text-zinc-800 dark:text-zinc-100 tracking-tight">Dashboard: Análisis de Abordaje</h1>
                <p class="text-sm text-zinc-500 dark:text-zinc-400 font-medium">
                    <span class="text-lime-600 dark:text-lime-400">{{ $graphMunicipioNombre }}</span> 
                    &bull; {{ $nombreMes }} {{ $graphAno }}
                </p>
            </div>
        </div>
        
        <div class="flex flex-wrap items-center gap-3 relative z-10">
            <div class="flex items-center gap-2 bg-white dark:bg-zinc-800 p-1 rounded-lg border border-zinc-200 dark:border-zinc-700 shadow-sm">
                <flux:select wire:model.live="graphMonth" class="w-32 border-none shadow-none focus:ring-0">
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
                <div class="w-px h-6 bg-zinc-200 dark:bg-zinc-700"></div>
                <flux:input wire:model.live="graphAno" type="number" class="w-24 border-none shadow-none focus:ring-0" />
            </div>

            <flux:button icon="arrow-left" variant="subtle" href="{{ route('admin.actividades.abordajes.index') }}" wire:navigate class="hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors rounded-lg">
                Volver
            </flux:button>
        </div>
    </div>

    @php
        $totalCasosVulnerables = $graphKpis['total_a'] + $graphKpis['total_b'] + $graphKpis['total_a_plus'];
        $tasaVulnerabilidad = $graphKpis['total_cantidad'] > 0 ? round(($totalCasosVulnerables / $graphKpis['total_cantidad']) * 100, 1) : 0;
    @endphp

    {{-- Tarjetas KPI Premium --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        {{-- Total Atendidos --}}
        <div class="bg-white dark:bg-zinc-900 rounded-3xl p-6 border border-zinc-100 dark:border-zinc-800/80 shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-[0_8px_30px_rgb(0,0,0,0.1)] relative overflow-hidden group hover:border-lime-500/50 transition-all duration-300">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-lime-500/10 rounded-full blur-2xl group-hover:bg-lime-500/20 transition-all duration-500"></div>
            <div class="flex justify-between items-start relative z-10">
                <div>
                    <p class="text-[11px] font-bold text-zinc-500 dark:text-zinc-400 uppercase tracking-widest mb-1">Total Abordados</p>
                    <h3 class="text-4xl font-black text-zinc-800 dark:text-zinc-100 tabular-nums tracking-tight">{{ number_format($graphKpis['total_cantidad'], 0, ',', '.') }}</h3>
                    <p class="text-xs text-lime-600 dark:text-lime-400 mt-2 flex items-center gap-1 font-medium">
                        <flux:icon.users class="w-3.5 h-3.5" /> Personas evaluadas
                    </p>
                </div>
                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-lime-100 to-lime-200 dark:from-lime-500/20 dark:to-lime-600/20 flex items-center justify-center text-lime-600 dark:text-lime-400 shadow-inner">
                    <flux:icon.identification class="w-5 h-5" />
                </div>
            </div>
        </div>

        {{-- Tasa de Vulnerabilidad --}}
        <div class="bg-white dark:bg-zinc-900 rounded-3xl p-6 border border-zinc-100 dark:border-zinc-800/80 shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-[0_8px_30px_rgb(0,0,0,0.1)] relative overflow-hidden group hover:border-rose-500/50 transition-all duration-300">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-rose-500/10 rounded-full blur-2xl group-hover:bg-rose-500/20 transition-all duration-500"></div>
            <div class="flex justify-between items-start relative z-10">
                <div>
                    <p class="text-[11px] font-bold text-zinc-500 dark:text-zinc-400 uppercase tracking-widest mb-1">Tasa de Vulnerabilidad</p>
                    <div class="flex items-baseline gap-1">
                        <h3 class="text-4xl font-black text-zinc-800 dark:text-zinc-100 tabular-nums tracking-tight">{{ $tasaVulnerabilidad }}</h3>
                        <span class="text-xl font-bold text-zinc-500">%</span>
                    </div>
                    <p class="text-xs text-rose-600 dark:text-rose-400 mt-2 font-medium">Casos A, B y A+</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-rose-100 to-rose-200 dark:from-rose-500/20 dark:to-rose-600/20 flex items-center justify-center text-rose-600 dark:text-rose-400 shadow-inner">
                    <flux:icon.exclamation-triangle class="w-5 h-5" />
                </div>
            </div>
        </div>

        {{-- Frecuencia --}}
        <div class="bg-white dark:bg-zinc-900 rounded-3xl p-6 border border-zinc-100 dark:border-zinc-800/80 shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-[0_8px_30px_rgb(0,0,0,0.1)] relative overflow-hidden group hover:border-blue-500/50 transition-all duration-300">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-blue-500/10 rounded-full blur-2xl group-hover:bg-blue-500/20 transition-all duration-500"></div>
            <div class="flex justify-between items-start relative z-10">
                <div>
                    <p class="text-[11px] font-bold text-zinc-500 dark:text-zinc-400 uppercase tracking-widest mb-1">Abordajes Realizados</p>
                    <h3 class="text-4xl font-black text-zinc-800 dark:text-zinc-100 tabular-nums tracking-tight">{{ number_format($graphKpis['total_registros'], 0, ',', '.') }}</h3>
                    <p class="text-xs text-blue-600 dark:text-blue-400 mt-2 font-medium">Actividades en el mes</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-100 to-blue-200 dark:from-blue-500/20 dark:to-blue-600/20 flex items-center justify-center text-blue-600 dark:text-blue-400 shadow-inner">
                    <flux:icon.clipboard-document-list class="w-5 h-5" />
                </div>
            </div>
        </div>

        {{-- Promedio Diario --}}
        <div class="bg-white dark:bg-zinc-900 rounded-3xl p-6 border border-zinc-100 dark:border-zinc-800/80 shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-[0_8px_30px_rgb(0,0,0,0.1)] relative overflow-hidden group hover:border-amber-500/50 transition-all duration-300">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-amber-500/10 rounded-full blur-2xl group-hover:bg-amber-500/20 transition-all duration-500"></div>
            <div class="flex justify-between items-start relative z-10">
                <div>
                    <p class="text-[11px] font-bold text-zinc-500 dark:text-zinc-400 uppercase tracking-widest mb-1">Promedio Diario</p>
                    <h3 class="text-4xl font-black text-zinc-800 dark:text-zinc-100 tabular-nums tracking-tight">{{ number_format($graphKpis['promedio_diario'], 1, ',', '.') }}</h3>
                    <p class="text-xs text-amber-600 dark:text-amber-400 mt-2 font-medium">Personas por día</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-amber-100 to-amber-200 dark:from-amber-500/20 dark:to-amber-600/20 flex items-center justify-center text-amber-600 dark:text-amber-400 shadow-inner">
                    <flux:icon.bolt class="w-5 h-5" />
                </div>
            </div>
        </div>
    </div>

    {{-- Fila Principal Específica de Abordaje --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        {{-- Clasificación Nutricional (Donut) --}}
        <div class="bg-white dark:bg-zinc-900 rounded-3xl p-6 border border-zinc-100 dark:border-zinc-800/80 shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-[0_8px_30px_rgb(0,0,0,0.1)] flex flex-col relative overflow-hidden">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-bold text-zinc-800 dark:text-zinc-200 uppercase tracking-wider flex items-center gap-2">
                    <div class="w-2 h-2 rounded-full bg-rose-500"></div>
                    Clasificación Nutricional
                </h3>
            </div>
            <div class="flex-1 relative w-full flex items-center justify-center min-h-[300px]" wire:ignore>
                <canvas id="clasificacionChart"></canvas>
                {{-- Centro del Donut --}}
                <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none mt-4">
                    <span class="text-3xl font-black text-zinc-800 dark:text-zinc-100">{{ $totalCasosVulnerables }}</span>
                    <span class="text-[10px] font-bold text-zinc-500 uppercase tracking-widest">Total Vulnerables</span>
                </div>
            </div>
        </div>

        {{-- Evolución Diaria de Casos A, B, A+ (Stacked Bar) --}}
        <div class="bg-white dark:bg-zinc-900 rounded-3xl p-6 border border-zinc-100 dark:border-zinc-800/80 shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-[0_8px_30px_rgb(0,0,0,0.1)] lg:col-span-2 flex flex-col relative overflow-hidden">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-bold text-zinc-800 dark:text-zinc-200 uppercase tracking-wider flex items-center gap-2">
                    <flux:icon.chart-bar class="w-4 h-4 text-emerald-500" />
                    Evolución Diaria de Vulnerabilidad
                </h3>
                <span class="text-xs font-medium bg-zinc-100 dark:bg-zinc-800 px-2 py-1 rounded-md text-zinc-600 dark:text-zinc-300">Casos A, B y A+</span>
            </div>
            <div class="flex-1 relative w-full min-h-[300px]" wire:ignore>
                <canvas id="evolucionClasificacionChart"></canvas>
            </div>
        </div>
    </div>

    {{-- Fila Secundaria (Geografía e Histórico General) --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        {{-- Parroquias (Horizontal Bar) --}}
        <div class="bg-white dark:bg-zinc-900 rounded-3xl p-6 border border-zinc-100 dark:border-zinc-800/80 shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-[0_8px_30px_rgb(0,0,0,0.1)] flex flex-col">
            <h3 class="text-sm font-bold text-zinc-800 dark:text-zinc-200 uppercase tracking-wider mb-4 flex items-center gap-2">
                <flux:icon.map class="w-4 h-4 text-lime-500" />
                Impacto Territorial (Parroquias)
            </h3>
            <div class="flex-1 relative w-full min-h-[350px]" wire:ignore>
                <canvas id="parroquiasChart"></canvas>
            </div>
        </div>

        {{-- Línea de Tiempo General --}}
        <div class="bg-white dark:bg-zinc-900 rounded-3xl p-6 border border-zinc-100 dark:border-zinc-800/80 shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-[0_8px_30px_rgb(0,0,0,0.1)] flex flex-col">
            <h3 class="text-sm font-bold text-zinc-800 dark:text-zinc-200 uppercase tracking-wider mb-4 flex items-center gap-2">
                <flux:icon.chart-bar-square class="w-4 h-4 text-lime-500" />
                Volumen Total Abordado
            </h3>
            <div class="flex-1 relative w-full min-h-[350px]" wire:ignore>
                <canvas id="diasChart"></canvas>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    @script
    <script>
        let chartInstances = {};

        const initCharts = () => {
            // Limpieza
            ['clasificacionChart', 'evolucionClasificacionChart', 'parroquiasChart', 'diasChart'].forEach(id => {
                const existing = Chart.getChart(id);
                if (existing) existing.destroy();
            });

            const isDark = document.documentElement.classList.contains('dark');
            const textColor = isDark ? '#a1a1aa' : '#71717a';
            const gridColor = isDark ? 'rgba(255, 255, 255, 0.05)' : 'rgba(0, 0, 0, 0.04)';
            const tooltipBg = isDark ? 'rgba(24, 24, 27, 0.9)' : 'rgba(255, 255, 255, 0.9)';
            const tooltipColor = isDark ? '#f4f4f5' : '#27272a';

            // Datos inyectados desde Livewire
            const dataClasif = $wire.graphClasificacion;
            const dataEvolClasif = $wire.graphEvolucionClasificacion;
            const dataParroquias = $wire.graphParroquias;
            const dataDias = $wire.graphDias;

            Chart.defaults.color = textColor;
            Chart.defaults.font.family = 'Inter, system-ui, sans-serif';
            
            // Configuración común de tooltips premium
            const tooltipOptions = {
                backgroundColor: tooltipBg,
                titleColor: tooltipColor,
                bodyColor: tooltipColor,
                borderColor: isDark ? 'rgba(255,255,255,0.1)' : 'rgba(0,0,0,0.1)',
                borderWidth: 1,
                padding: 12,
                boxPadding: 6,
                usePointStyle: true,
                titleFont: { size: 13, weight: 'bold' },
                bodyFont: { size: 12 }
            };

            // 1. Clasificación Nutricional (Semi-circle Donut)
            const ctxC = document.getElementById('clasificacionChart');
            if(ctxC && dataClasif.length) {
                chartInstances.clasificacion = new Chart(ctxC, {
                    type: 'doughnut',
                    data: {
                        labels: dataClasif.map(d => d.nombre),
                        datasets: [{
                            data: dataClasif.map(d => d.total),
                            backgroundColor: dataClasif.map(d => d.color),
                            borderWidth: 0,
                            hoverOffset: 10
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        rotation: -90,
                        circumference: 180,
                        cutout: '75%',
                        plugins: {
                            legend: { position: 'bottom', labels: { usePointStyle: true, padding: 20, font: { weight: 'bold' } } },
                            tooltip: tooltipOptions
                        }
                    }
                });
            }

            // 2. Evolución de Clasificación (Stacked Bar)
            const ctxE = document.getElementById('evolucionClasificacionChart');
            if(ctxE && dataEvolClasif.length) {
                const labels = Array.from({length: 31}, (_, i) => i + 1);
                
                const dataA = labels.map(l => { const f = dataEvolClasif.find(d => parseInt(d.dia) === l); return f ? f.total_a : 0; });
                const dataB = labels.map(l => { const f = dataEvolClasif.find(d => parseInt(d.dia) === l); return f ? f.total_b : 0; });
                const dataAPlus = labels.map(l => { const f = dataEvolClasif.find(d => parseInt(d.dia) === l); return f ? f.total_a_plus : 0; });

                chartInstances.evolucionClasificacion = new Chart(ctxE, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [
                            { label: 'Caso A', data: dataA, backgroundColor: '#f43f5e', borderRadius: { topLeft: 4, topRight: 4, bottomLeft: 4, bottomRight: 4 }, borderSkipped: false },
                            { label: 'Caso B', data: dataB, backgroundColor: '#f59e0b', borderRadius: { topLeft: 4, topRight: 4, bottomLeft: 4, bottomRight: 4 }, borderSkipped: false },
                            { label: 'Caso A+', data: dataAPlus, backgroundColor: '#ec4899', borderRadius: { topLeft: 4, topRight: 4, bottomLeft: 4, bottomRight: 4 }, borderSkipped: false }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            x: { stacked: true, grid: { display: false }, border: { display: false } },
                            y: { stacked: true, grid: { color: gridColor, borderDash: [5, 5] }, border: { display: false }, beginAtZero: true }
                        },
                        plugins: {
                            legend: { position: 'top', align: 'end', labels: { usePointStyle: true, boxWidth: 8 } },
                            tooltip: { ...tooltipOptions, mode: 'index', intersect: false }
                        },
                        interaction: { mode: 'index', intersect: false }
                    }
                });
            }

            // 3. Parroquias (Horizontal Bar)
            const ctxP = document.getElementById('parroquiasChart');
            if(ctxP && dataParroquias.length) {
                chartInstances.parroquias = new Chart(ctxP, {
                    type: 'bar',
                    data: {
                        labels: dataParroquias.map(d => d.nombre.substring(0, 20) + (d.nombre.length > 20 ? '...' : '')),
                        datasets: [{
                            label: 'Abordados',
                            data: dataParroquias.map(d => d.total),
                            backgroundColor: '#10b981', // Emerald 500
                            borderRadius: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        indexAxis: 'y',
                        scales: {
                            x: { grid: { color: gridColor }, border: { display: false } },
                            y: { grid: { display: false }, border: { display: false } }
                        },
                        plugins: { legend: { display: false }, tooltip: tooltipOptions }
                    }
                });
            }

            // 4. Días (Line Area Gradient)
            const ctxD = document.getElementById('diasChart');
            if(ctxD && dataDias.length) {
                const labels = Array.from({length: 31}, (_, i) => i + 1);
                const values = labels.map(l => {
                    const found = dataDias.find(d => parseInt(d.dia) === l);
                    return found ? found.total : 0;
                });

                const gradient = ctxD.getContext('2d').createLinearGradient(0, 0, 0, 300);
                gradient.addColorStop(0, 'rgba(132, 204, 22, 0.4)'); // Lime 500
                gradient.addColorStop(1, 'rgba(132, 204, 22, 0.01)');

                chartInstances.dias = new Chart(ctxD, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Total Personas',
                            data: values,
                            borderColor: '#84cc16',
                            backgroundColor: gradient,
                            fill: true,
                            tension: 0.4,
                            pointRadius: 0,
                            pointHoverRadius: 6,
                            pointBackgroundColor: '#84cc16',
                            borderWidth: 3
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: { beginAtZero: true, grid: { color: gridColor, borderDash: [4, 4] }, border: { display: false } },
                            x: { grid: { display: false }, border: { display: false } }
                        },
                        plugins: { legend: { display: false }, tooltip: tooltipOptions },
                        interaction: { mode: 'nearest', axis: 'x', intersect: false }
                    }
                });
            }
        };

        $wire.on('refreshCharts', () => {
            setTimeout(initCharts, 150);
        });

        // Dar tiempo a Livewire
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

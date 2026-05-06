<div class="space-y-6">
    {{-- Header / Navigation --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 p-6 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-3xl shadow-sm overflow-hidden relative group">
        {{-- Decorative Background Glow --}}
        <div class="absolute -right-10 -top-10 w-40 h-40 bg-lime-500/5 rounded-full blur-3xl pointer-events-none group-hover:bg-emerald-500/10 transition-all duration-700"></div>
        <div class="absolute -left-10 -bottom-10 w-40 h-40 bg-emerald-500/5 rounded-full blur-3xl pointer-events-none group-hover:bg-lime-500/10 transition-all duration-700"></div>

        <div class="flex items-center gap-5 relative z-10">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-lime-500 to-emerald-600 flex items-center justify-center shadow-lg shadow-emerald-500/20 transform group-hover:scale-110 group-hover:rotate-3 transition-all duration-500">
                <flux:icon.presentation-chart-line class="w-8 h-8 text-white" />
            </div>
            <div class="flex flex-col">
                <h1 class="text-3xl font-black text-zinc-900 dark:text-zinc-100 tracking-tighter uppercase leading-none">
                    Análisis de <span class="text-transparent bg-clip-text bg-gradient-to-r from-lime-600 to-emerald-600">Metas Anuales</span>
                </h1>
                <div class="flex items-center gap-2 mt-1">
                    <p class="text-[10px] font-bold text-zinc-400 dark:text-zinc-500 uppercase tracking-[0.3em]">Visualización de Cumplimiento Global</p>
                    <span class="px-2 py-0.5 rounded-md bg-lime-50 dark:bg-lime-500/10 text-lime-700 dark:text-lime-400 text-[10px] font-black border border-lime-100 dark:border-lime-500/20">AÑO {{ $ano }}</span>
                </div>
            </div>
        </div>
        
        <div class="flex items-center gap-3 relative z-10">
            <flux:button as="a" href="{{ route('admin.metas.index') }}" wire:navigate icon="arrow-left" variant="ghost" class="font-bold">
                Volver a Metas
            </flux:button>
        </div>
    </div>

    {{-- Global KPIs --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        {{-- Meta Total --}}
        <div class="bg-gradient-to-br from-cyan-600 to-blue-700 rounded-2xl p-6 shadow-lg shadow-blue-500/20 relative overflow-hidden group hover:-translate-y-1 transition-all duration-300">
            <div class="absolute -right-6 -top-6 w-32 h-32 bg-white/10 rounded-full blur-2xl transition-transform duration-500 group-hover:scale-150"></div>
            <div class="flex justify-between items-start relative z-10">
                <div>
                    <p class="text-xs font-bold text-cyan-50 uppercase tracking-widest mb-1 opacity-90">Meta Global</p>
                    <h3 class="text-4xl font-black text-white tabular-nums drop-shadow-sm">{{ number_format($kpis['meta_total']) }}</h3>
                    <div class="mt-4 flex items-center gap-1.5 px-2 py-0.5 rounded bg-white/10 backdrop-blur-sm border border-white/20 w-fit">
                         <flux:icon.building-office class="w-3 h-3 text-white/80" />
                         <span class="text-[9px] font-bold text-white tracking-widest uppercase">{{ $kpis['total_municipios'] }} Municipios</span>
                    </div>
                </div>
                <div class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center border border-white/30 shadow-inner">
                    <flux:icon.flag class="w-5 h-5 text-white drop-shadow-sm" />
                </div>
            </div>
        </div>

        {{-- Real Total --}}
        <div class="bg-gradient-to-br from-emerald-500 to-teal-700 rounded-2xl p-6 shadow-lg shadow-emerald-500/20 relative overflow-hidden group hover:-translate-y-1 transition-all duration-300">
            <div class="absolute -right-6 -top-6 w-32 h-32 bg-white/10 rounded-full blur-2xl transition-transform duration-500 group-hover:scale-150"></div>
            <div class="flex justify-between items-start relative z-10">
                <div>
                    <p class="text-xs font-bold text-emerald-50 uppercase tracking-widest mb-1 opacity-90">Total Real</p>
                    <h3 class="text-4xl font-black text-white tabular-nums drop-shadow-sm">{{ number_format($kpis['real_total']) }}</h3>
                    <div class="mt-4 flex items-center gap-1.5 px-2 py-0.5 rounded bg-white/10 backdrop-blur-sm border border-white/20 w-fit">
                         <flux:icon.arrow-path class="w-3 h-3 text-white/80 animate-spin-slow" />
                         <span class="text-[9px] font-bold text-white tracking-widest uppercase">Actualizado</span>
                    </div>
                </div>
                <div class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center border border-white/30 shadow-inner">
                    <flux:icon.check-circle class="w-5 h-5 text-white drop-shadow-sm" />
                </div>
            </div>
        </div>

        {{-- Porcentaje --}}
        <div class="bg-gradient-to-br from-violet-500 to-purple-700 rounded-2xl p-6 shadow-lg shadow-purple-500/20 relative overflow-hidden group hover:-translate-y-1 transition-all duration-300">
            <div class="absolute -right-6 -top-6 w-32 h-32 bg-white/10 rounded-full blur-2xl transition-transform duration-500 group-hover:scale-150"></div>
            <div class="flex justify-between items-start relative z-10">
                <div>
                    <p class="text-xs font-bold text-purple-50 uppercase tracking-widest mb-1 opacity-90">Eficiencia</p>
                    <div class="flex items-baseline gap-1">
                        <h3 class="text-4xl font-black text-white tabular-nums drop-shadow-sm">{{ $kpis['porcentaje'] }}</h3>
                        <span class="text-xl font-bold text-white/80">%</span>
                    </div>
                    <div class="mt-4 w-32 h-1.5 bg-white/20 rounded-full overflow-hidden p-[1px] border border-white/10">
                        <div class="h-full bg-white rounded-full transition-all duration-1000 shadow-[0_0_8px_rgba(255,255,255,0.5)]" style="width: {{ min(100, $kpis['porcentaje']) }}%"></div>
                    </div>
                </div>
                <div class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center border border-white/30 shadow-inner">
                    <flux:icon.chart-pie class="w-5 h-5 text-white drop-shadow-sm" />
                </div>
            </div>
        </div>

        {{-- Logros --}}
        <div class="bg-gradient-to-br from-amber-400 to-orange-500 rounded-2xl p-6 shadow-lg shadow-orange-500/20 relative overflow-hidden group hover:-translate-y-1 transition-all duration-300">
            <div class="absolute -right-6 -top-6 w-32 h-32 bg-white/10 rounded-full blur-2xl transition-transform duration-500 group-hover:scale-150"></div>
            <div class="flex justify-between items-start relative z-10">
                <div>
                    <p class="text-xs font-bold text-orange-50 uppercase tracking-widest mb-1 opacity-90">Logros</p>
                    <h3 class="text-4xl font-black text-white tabular-nums drop-shadow-sm">
                        {{ $kpis['municipios_completados'] }}<span class="text-xl text-white/50 mx-1.5">/</span>{{ $kpis['total_municipios'] }}
                    </h3>
                    <p class="text-[9px] font-bold text-white/80 mt-4 uppercase tracking-widest">Metas Alcanzadas</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center border border-white/30 shadow-inner">
                    <flux:icon.trophy class="w-5 h-5 text-white drop-shadow-sm" />
                </div>
            </div>
        </div>
    </div>

    {{-- Main Comparative Charts --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        {{-- Municipality Comparison Chart --}}
        <div class="bg-white dark:bg-zinc-900 p-8 rounded-[2rem] border border-zinc-200 dark:border-zinc-800 shadow-sm relative overflow-hidden group">
            <div class="absolute -right-10 -top-10 w-40 h-40 bg-lime-500/5 rounded-full blur-3xl pointer-events-none group-hover:bg-lime-500/10 transition-all duration-700"></div>
            <div class="flex items-center justify-between mb-10">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-lime-50 dark:bg-lime-500/10 flex items-center justify-center border border-lime-100 dark:border-lime-500/20 shadow-inner">
                        <flux:icon.building-office class="w-6 h-6 text-lime-600 dark:text-lime-400" />
                    </div>
                    <div>
                        <h3 class="text-lg font-black text-zinc-900 dark:text-zinc-100 uppercase tracking-tighter leading-none">Rendimiento</h3>
                        <p class="text-[10px] font-bold text-zinc-400 dark:text-zinc-500 uppercase tracking-[0.3em] mt-1">Comparativa por Municipio</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 bg-zinc-50 dark:bg-zinc-800/50 p-2 rounded-xl border border-zinc-100 dark:border-zinc-700/50">
                    <div class="flex items-center gap-1.5 px-2 border-r border-zinc-200 dark:border-zinc-700">
                        <div class="w-2 h-2 bg-lime-500 rounded-full shadow-[0_0_8px_rgba(132,204,22,0.5)]"></div>
                        <span class="text-[9px] font-black text-zinc-500 uppercase tracking-widest">Real</span>
                    </div>
                    <div class="flex items-center gap-1.5 px-2">
                        <div class="w-2 h-2 bg-zinc-300 dark:bg-zinc-600 rounded-full"></div>
                        <span class="text-[9px] font-black text-zinc-500 uppercase tracking-widest">Meta</span>
                    </div>
                </div>
            </div>
            <div class="min-h-[350px] w-full" wire:ignore>
                <canvas id="municipiosChart"></canvas>
            </div>
        </div>

        {{-- Type Distribution Chart --}}
        <div class="bg-white dark:bg-zinc-900 p-8 rounded-[2rem] border border-zinc-200 dark:border-zinc-800 shadow-sm relative overflow-hidden group">
            <div class="absolute -right-10 -top-10 w-40 h-40 bg-blue-500/5 rounded-full blur-3xl pointer-events-none group-hover:bg-blue-500/10 transition-all duration-700"></div>
            <div class="flex items-center gap-4 mb-10">
                <div class="w-12 h-12 rounded-2xl bg-blue-50 dark:bg-blue-500/10 flex items-center justify-center border border-blue-100 dark:border-blue-500/20 shadow-inner">
                    <flux:icon.chart-pie class="w-6 h-6 text-blue-600 dark:text-blue-400" />
                </div>
                <div>
                    <h3 class="text-lg font-black text-zinc-900 dark:text-zinc-100 uppercase tracking-tighter leading-none">Distribución</h3>
                    <p class="text-[10px] font-bold text-zinc-400 dark:text-zinc-500 uppercase tracking-[0.3em] mt-1">Por Tipo de Transcripción</p>
                </div>
            </div>
            <div class="min-h-[350px] w-full flex items-center justify-center" wire:ignore>
                <canvas id="tiposChart"></canvas>
            </div>
        </div>
    </div>

    {{-- Monthly Progress Trend --}}
    <div class="bg-white dark:bg-zinc-900 p-8 rounded-[2rem] border border-zinc-200 dark:border-zinc-800 shadow-sm relative overflow-hidden group">
        <div class="absolute -right-10 -top-10 w-64 h-64 bg-amber-500/5 rounded-full blur-[100px] pointer-events-none group-hover:bg-amber-500/10 transition-all duration-700"></div>
        <div class="flex items-center justify-between mb-10">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-amber-50 dark:bg-amber-500/10 flex items-center justify-center border border-amber-100 dark:border-amber-500/20 shadow-inner">
                    <flux:icon.chart-bar class="w-6 h-6 text-amber-600 dark:text-amber-400" />
                </div>
                <div>
                    <h2 class="text-2xl font-black text-zinc-900 dark:text-zinc-100 uppercase tracking-tighter leading-none">Tendencia Mensual</h2>
                    <p class="text-[10px] font-bold text-zinc-400 dark:text-zinc-500 uppercase tracking-[0.3em] mt-1.5">Evolución Acumulada del Ejercicio Anual</p>
                </div>
            </div>
        </div>
        <div class="min-h-[400px] w-full" wire:ignore>
            <canvas id="tendenciaMensualChart"></canvas>
        </div>
    </div>

    {{-- Municipality Cards Grid --}}
    <div class="pt-4 border-t border-zinc-200 dark:border-zinc-800">
        <div class="flex items-center gap-4 mb-8 relative z-10">
            <div class="w-12 h-12 rounded-2xl bg-white dark:bg-zinc-900 flex items-center justify-center border border-zinc-200 dark:border-zinc-800 shadow-sm">
                <flux:icon.building-office-2 class="w-6 h-6 text-zinc-600 dark:text-zinc-400" />
            </div>
            <div class="flex flex-col">
                <h2 class="text-2xl font-black text-zinc-900 dark:text-zinc-100 uppercase tracking-tighter leading-none">Detalle Individual <span class="text-transparent bg-clip-text bg-gradient-to-r from-zinc-400 to-zinc-600 dark:from-zinc-500 dark:to-zinc-300">por Municipio</span></h2>
                <p class="text-[10px] font-bold text-zinc-400 dark:text-zinc-500 uppercase tracking-[0.3em] mt-1.5">Desglose Analítico de Rendimiento</p>
            </div>
        </div>
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            @foreach($municipioCards as $card)
                <div class="bg-white dark:bg-zinc-900 rounded-[2.5rem] border {{ $card['porcentaje'] >= 100 ? 'border-lime-500/50 dark:border-lime-400/30 shadow-[0_20px_50px_rgba(132,204,22,0.1)]' : 'border-zinc-200 dark:border-zinc-800 shadow-sm' }} overflow-hidden flex flex-col hover:shadow-xl hover:-translate-y-1 transition-all duration-500 group relative">
                    {{-- Decorative Background Glow --}}
                    <div class="absolute -right-20 -bottom-20 w-64 h-64 opacity-[0.03] dark:opacity-[0.05] pointer-events-none blur-[100px] group-hover:opacity-10 transition-all duration-700" style="background-color: {{ $card['color']['hex'] }}"></div>
                    <div class="absolute -left-20 -top-20 w-48 h-48 opacity-[0.02] dark:opacity-[0.03] pointer-events-none blur-[80px] group-hover:opacity-5 transition-all duration-700" style="background-color: {{ $card['color']['hex'] }}"></div>
                    
                    @if($card['porcentaje'] >= 100)
                        {{-- Meta Cumplida Badge --}}
                        <div class="absolute top-0 right-0 z-20 pointer-events-none">
                            <div class="bg-lime-600 text-white text-[10px] font-black py-1 px-4 rounded-bl-2xl shadow-lg flex items-center gap-1.5 uppercase tracking-widest">
                                <flux:icon.check-badge class="w-4 h-4" />
                                Meta Cumplida
                            </div>
                        </div>
                    @endif

                    {{-- Card Header --}}
                    <div class="p-6 border-b border-zinc-100 dark:border-zinc-800 flex items-center justify-between {{ $card['color']['light'] }}">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl {{ $card['color']['bg'] }} flex items-center justify-center text-white shadow-lg">
                                <flux:icon.building-office class="w-6 h-6" />
                            </div>
                            <h4 class="font-black text-lg {{ $card['color']['text'] }} tracking-tight">{{ $card['nombre'] }}</h4>
                        </div>
                        <div class="flex flex-col items-end">
                            <div class="px-4 py-1.5 rounded-full {{ $card['color']['bg'] }} text-white dark:text-zinc-900 text-xs font-black shadow-md">
                                {{ $card['porcentaje'] }}% completado
                            </div>
                        </div>
                    </div>

                    <div class="p-8 space-y-6 flex-1">
                        {{-- Stats --}}
                        <div class="grid grid-cols-2 gap-8">
                            <div class="bg-zinc-50 dark:bg-zinc-800/40 p-4 rounded-2xl border border-zinc-100 dark:border-zinc-700/50">
                                <p class="text-[10px] font-bold text-zinc-400 uppercase tracking-[0.2em] mb-1">Transcripciones Reales</p>
                                <p class="text-3xl font-black text-zinc-800 dark:text-zinc-100 tabular-nums">{{ number_format($card['real']) }}</p>
                            </div>
                            <div class="bg-zinc-50 dark:bg-zinc-800/40 p-4 rounded-2xl border border-zinc-100 dark:border-zinc-700/50 text-right">
                                <p class="text-[10px] font-bold text-zinc-400 uppercase tracking-[0.2em] mb-1">Meta Anual Establecida</p>
                                <p class="text-3xl font-black text-zinc-800 dark:text-zinc-100 tabular-nums">{{ number_format($card['meta_anual']) }}</p>
                            </div>
                        </div>

                        {{-- Progress Bar --}}
                        <div class="space-y-2">
                            <div class="flex justify-between text-xs font-bold uppercase tracking-wider">
                                <span class="text-zinc-500">Progreso Anual del Municipio</span>
                                <span class="{{ $card['color']['text'] }}">{{ number_format($card['faltante']) }} registros para la meta</span>
                            </div>
                            <div class="h-3 w-full bg-zinc-100 dark:bg-zinc-800 rounded-full overflow-hidden shadow-inner">
                                <div class="h-full {{ $card['color']['bg'] }} rounded-full shadow-[0_0_15px_rgba(0,0,0,0.1)] transition-all duration-1000" style="width: {{ min(100, $card['porcentaje']) }}%"></div>
                            </div>
                        </div>

                        {{-- Types Breakdown --}}
                        <div class="pt-4">
                            <p class="text-xs font-bold text-zinc-400 uppercase tracking-[0.2em] mb-4 flex items-center gap-2">
                                <flux:icon.list-bullet class="w-4 h-4" />
                                Desglose por Tipo de Transcripción
                            </p>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-6">
                                @forelse($card['tipos'] as $tipo)
                                    <div class="flex items-center justify-between p-3 rounded-xl bg-zinc-50 dark:bg-zinc-800/50 border border-zinc-100 dark:border-zinc-700/30 hover:shadow-sm transition-all">
                                        <span class="text-xs font-bold text-zinc-600 dark:text-zinc-400 truncate">{{ $tipo['tipo'] }}</span>
                                        <span class="text-sm font-black text-zinc-800 dark:text-zinc-200 ml-2">{{ number_format($tipo['total']) }}</span>
                                    </div>
                                @empty
                                    <div class="col-span-full py-4 text-center">
                                        <p class="text-xs text-zinc-400 italic">No hay registros de transcripciones para este municipio en {{ $ano }}</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        {{-- Individual Monthly Chart --}}
                        <div class="pt-6 border-t border-zinc-100 dark:border-zinc-800">
                            <p class="text-[10px] font-bold text-zinc-400 uppercase tracking-[0.2em] mb-4">Relación Mensual ({{ $ano }})</p>
                            <div class="h-40 w-full" wire:ignore>
                                <canvas id="chart-mun-{{ $card['id'] }}"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Dependencia de Chart.js --}}
    @if(!isset($chartScriptLoaded))
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        @php $chartScriptLoaded = true; @endphp
    @endif
    
    @script
    <script>
        const renderCharts = () => {
            if (typeof Chart === 'undefined') {
                setTimeout(renderCharts, 200);
                return;
            }

            const isDarkMode = document.documentElement.classList.contains('dark');
            const textColor = isDarkMode ? '#a1a1aa' : '#71717a';
            const gridColor = isDarkMode ? 'rgba(255, 255, 255, 0.05)' : 'rgba(0, 0, 0, 0.04)';

            // Destroy existing global charts
            ['municipiosChart', 'tiposChart', 'tendenciaMensualChart'].forEach(id => {
                const existing = Chart.getChart(id);
                if (existing) existing.destroy();
            });

            // 1. Comparativa Municipios
            const mData = $wire.municipios;
            const ctxMunicipios = document.getElementById('municipiosChart');
            if (ctxMunicipios && mData && mData.length > 0) {
                new Chart(ctxMunicipios, {
                    type: 'bar',
                    data: {
                        labels: mData.map(m => m.nombre),
                        datasets: [
                            {
                                label: 'Real',
                                data: mData.map(m => m.real),
                                backgroundColor: mData.map(m => m.color),
                                borderRadius: 6,
                                barPercentage: 0.8,
                            },
                            {
                                label: 'Meta',
                                data: mData.map(m => m.meta_anual),
                                backgroundColor: 'rgba(163, 230, 53, 0.08)',
                                borderColor: 'rgba(132, 204, 22, 0.6)',
                                borderWidth: 2,
                                borderDash: [4, 4],
                                borderRadius: 6,
                                barPercentage: 0.8,
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: isDarkMode ? '#18181b' : '#ffffff',
                                titleColor: isDarkMode ? '#ffffff' : '#18181b',
                                bodyColor: isDarkMode ? '#a1a1aa' : '#71717a',
                                borderColor: isDarkMode ? '#3f3f46' : '#e4e4e7',
                                borderWidth: 1,
                                padding: 12,
                                cornerRadius: 12,
                            }
                        },
                        scales: {
                            y: { beginAtZero: true, grid: { color: gridColor }, ticks: { color: textColor, font: { size: 10, weight: '600' } } },
                            x: { grid: { display: false }, ticks: { color: textColor, font: { size: 9, weight: '700' } } }
                        }
                    }
                });
            }

            // 2. Distribución Tipos
            const tData = $wire.tipoDistribucion;
            const ctxTipos = document.getElementById('tiposChart');
            if (ctxTipos && tData && tData.length > 0) {
                new Chart(ctxTipos, {
                    type: 'doughnut',
                    data: {
                        labels: tData.map(t => t.tipo),
                        datasets: [{
                            data: tData.map(t => t.total),
                            backgroundColor: ['#84cc16', '#3b82f6', '#f59e0b', '#ec4899', '#8b5cf6', '#10b981', '#06b6d4', '#f43f5e', '#6366f1', '#f97316'],
                            borderWidth: 0,
                            hoverOffset: 15
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '70%',
                        plugins: {
                            legend: { position: 'right', labels: { color: textColor, usePointStyle: true, pointStyle: 'circle', font: { size: 10, weight: '600' }, padding: 15 } }
                        }
                    }
                });
            }

            // 3. Tendencia Mensual (Multi-Series)
            const pData = $wire.progresoMensual;
            const ctxTendencia = document.getElementById('tendenciaMensualChart');
            if (ctxTendencia && pData && pData.length > 0) {
                const meses = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
                new Chart(ctxTendencia, {
                    type: 'line',
                    data: {
                        labels: meses,
                        datasets: pData.map(p => ({
                            label: p.nombre,
                            data: p.acumulado,
                            borderColor: p.color,
                            backgroundColor: 'transparent',
                            borderWidth: 2,
                            pointRadius: 0,
                            pointHoverRadius: 5,
                            tension: 0.4
                        }))
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: { mode: 'index', intersect: false },
                        plugins: { legend: { position: 'bottom', labels: { color: textColor, usePointStyle: true, padding: 20, font: { size: 10, weight: '600' } } } },
                        scales: {
                            y: { beginAtZero: true, grid: { color: gridColor }, ticks: { color: textColor, font: { weight: '600' } } },
                            x: { grid: { display: false }, ticks: { color: textColor, font: { weight: '600' } } }
                        }
                    }
                });
            }

            // 4. Individual Municipality Monthly Charts (Bar Charts with Monthly Goal)
            const cards = $wire.municipioCards;
            if (cards && cards.length > 0) {
                const mesesAbbr = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
                cards.forEach(card => {
                    const canvasId = `chart-mun-${card.id}`;
                    const ctx = document.getElementById(canvasId);
                    if (ctx) {
                        const existing = Chart.getChart(canvasId);
                        if (existing) existing.destroy();

                        new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: mesesAbbr,
                                datasets: [
                                    {
                                        label: 'Real',
                                        data: card.datos_mes,
                                        backgroundColor: card.color.hex,
                                        borderRadius: 4,
                                        barPercentage: 0.6,
                                    },
                                    {
                                        label: 'Meta Mensual',
                                        data: Array(12).fill(card.meta_mensual),
                                        backgroundColor: 'rgba(163, 230, 53, 0.08)', 
                                        borderColor: 'rgba(132, 204, 22, 0.6)', 
                                        borderWidth: 2,
                                        borderDash: [4, 4],
                                        borderRadius: 4,
                                        barPercentage: 0.8,
                                    }
                                ]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: { display: false },
                                    tooltip: {
                                        backgroundColor: isDarkMode ? '#18181b' : '#ffffff',
                                        titleColor: isDarkMode ? '#ffffff' : '#18181b',
                                        bodyColor: isDarkMode ? '#a1a1aa' : '#71717a',
                                        borderColor: isDarkMode ? '#3f3f46' : '#e4e4e7',
                                        borderWidth: 1,
                                        padding: 8,
                                        cornerRadius: 8,
                                        callbacks: {
                                            footer: (items) => {
                                                const real = items[0].raw;
                                                const meta = card.meta_mensual;
                                                const diff = real - meta;
                                                return `Diferencia: ${diff > 0 ? '+' : ''}${diff}`;
                                            }
                                        }
                                    }
                                },
                                scales: {
                                    y: { 
                                        beginAtZero: true, 
                                        grid: { color: gridColor },
                                        ticks: { color: textColor, font: { size: 8 } }
                                    },
                                    x: { 
                                        grid: { display: false },
                                        ticks: { color: textColor, font: { size: 8, weight: '600' } }
                                    }
                                }
                            }
                        });
                    }
                });
            }
        };

        $wire.on('refreshCharts', () => { setTimeout(renderCharts, 150); });
        setTimeout(renderCharts, 100);
        document.addEventListener('livewire:navigated', () => { setTimeout(renderCharts, 100); }, { once: true });
    </script>
    @endscript
</div>

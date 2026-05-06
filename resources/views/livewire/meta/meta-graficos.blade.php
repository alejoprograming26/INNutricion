<div class="space-y-6">
    {{-- Header / Navigation --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white dark:bg-zinc-900 p-6 rounded-2xl border border-zinc-200 dark:border-zinc-800 shadow-sm">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-lime-100 dark:bg-lime-500/10 flex items-center justify-center border border-lime-200 dark:border-lime-500/20 shadow-inner">
                <flux:icon.presentation-chart-line class="w-7 h-7 text-lime-600 dark:text-lime-400" />
            </div>
            <div>
                <h1 class="text-2xl font-black text-zinc-800 dark:text-zinc-100 tracking-tight">Análisis de Metas Anuales</h1>
                <p class="text-sm text-zinc-500 dark:text-zinc-400 flex items-center gap-1.5 font-medium">
                    <span class="px-2 py-0.5 rounded-md bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-300 font-bold border border-zinc-200 dark:border-zinc-700">Año {{ $ano }}</span>
                    <span>&bull; Visualización de cumplimiento por transcripciones</span>
                </p>
            </div>
        </div>
        
        <div class="flex items-center gap-3">
            <flux:button as="a" href="{{ route('admin.metas.index') }}" wire:navigate icon="arrow-left" variant="ghost" class="font-bold">
                Volver a Metas
            </flux:button>
            <flux:button icon="printer" variant="ghost" onclick="window.print()" class="hidden md:flex font-bold">
                Imprimir Reporte
            </flux:button>
        </div>
    </div>

    {{-- Global KPIs --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        {{-- Meta Total --}}
        <div class="bg-white dark:bg-zinc-900 rounded-2xl p-4 border border-zinc-100 dark:border-zinc-800 shadow-sm relative overflow-hidden group">
            <div class="flex justify-between items-start relative z-10">
                <div>
                    <p class="text-[9px] font-bold text-zinc-400 dark:text-zinc-500 uppercase tracking-wider mb-1">Meta Anual Global</p>
                    <h3 class="text-2xl font-black text-zinc-800 dark:text-zinc-100 tabular-nums">{{ number_format($kpis['meta_total']) }}</h3>
                </div>
                <div class="w-8 h-8 rounded-lg bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center text-zinc-500 border border-zinc-200 dark:border-zinc-700">
                    <flux:icon.flag class="w-4 h-4" />
                </div>
            </div>
        </div>

        {{-- Real Total --}}
        <div class="bg-white dark:bg-zinc-900 rounded-2xl p-4 border border-zinc-100 dark:border-zinc-800 shadow-sm relative overflow-hidden group">
            <div class="flex justify-between items-start relative z-10">
                <div>
                    <p class="text-[9px] font-bold text-zinc-400 dark:text-zinc-500 uppercase tracking-wider mb-1">Total Alcanzado</p>
                    <h3 class="text-2xl font-black text-zinc-800 dark:text-zinc-100 tabular-nums">{{ number_format($kpis['real_total']) }}</h3>
                </div>
                <div class="w-8 h-8 rounded-lg bg-lime-100 dark:bg-lime-500/10 flex items-center justify-center text-lime-600 border border-lime-200 dark:border-lime-500/20">
                    <flux:icon.check-circle class="w-4 h-4" />
                </div>
            </div>
        </div>

        {{-- Porcentaje --}}
        <div class="bg-white dark:bg-zinc-900 rounded-2xl p-4 border border-zinc-100 dark:border-zinc-800 shadow-sm relative overflow-hidden group">
            <div class="flex justify-between items-start relative z-10">
                <div>
                    <p class="text-[9px] font-bold text-zinc-400 dark:text-zinc-500 uppercase tracking-wider mb-1">% Cumplimiento</p>
                    <h3 class="text-2xl font-black text-zinc-800 dark:text-zinc-100 tabular-nums">{{ $kpis['porcentaje'] }}%</h3>
                </div>
                <div class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-500/10 flex items-center justify-center text-blue-600 border border-blue-200 dark:border-blue-500/20">
                    <flux:icon.arrow-trending-up class="w-4 h-4" />
                </div>
            </div>
        </div>

        {{-- Municipios Completados --}}
        <div class="bg-white dark:bg-zinc-900 rounded-2xl p-4 border border-zinc-100 dark:border-zinc-800 shadow-sm relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 w-16 h-16 bg-amber-500/5 rounded-full blur-xl group-hover:bg-amber-500/10 transition-all duration-500"></div>
            <div class="flex justify-between items-start relative z-10">
                <div>
                    <p class="text-[9px] font-bold text-zinc-400 dark:text-zinc-500 uppercase tracking-wider mb-1">Metas Logradas</p>
                    <h3 class="text-2xl font-black text-amber-600 dark:text-amber-500 tabular-nums">{{ $kpis['municipios_completados'] }} / {{ $kpis['total_municipios'] }}</h3>
                    <p class="text-[8px] font-bold text-zinc-400 mt-1 uppercase">Municipios al 100%</p>
                </div>
                <div class="w-8 h-8 rounded-lg bg-amber-100 dark:bg-amber-500/10 flex items-center justify-center text-amber-600 border border-amber-200 dark:border-amber-500/20">
                    <flux:icon.trophy class="w-4 h-4" />
                </div>
            </div>
        </div>

        {{-- Faltante --}}
        <div class="bg-white dark:bg-zinc-900 rounded-2xl p-4 border border-zinc-100 dark:border-zinc-800 shadow-sm relative overflow-hidden group">
            <div class="flex justify-between items-start relative z-10">
                <div>
                    <p class="text-[9px] font-bold text-zinc-400 dark:text-zinc-500 uppercase tracking-wider mb-1">Restante Global</p>
                    <h3 class="text-2xl font-black text-zinc-800 dark:text-zinc-100 tabular-nums">{{ number_format($kpis['faltante']) }}</h3>
                </div>
                <div class="w-8 h-8 rounded-lg bg-rose-100 dark:bg-rose-500/10 flex items-center justify-center text-rose-600 border border-rose-200 dark:border-rose-500/20">
                    <flux:icon.clock class="w-4 h-4" />
                </div>
            </div>
        </div>
    </div>

    {{-- Main Comparative Charts --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Municipality Comparison Chart --}}
        <div class="bg-white dark:bg-zinc-900 rounded-3xl p-6 border border-zinc-200 dark:border-zinc-800 shadow-sm flex flex-col">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-sm font-black text-zinc-800 dark:text-zinc-100 uppercase tracking-wider flex items-center gap-2">
                    <div class="w-2 h-6 bg-lime-500 rounded-full"></div>
                    Cumplimiento por Municipio
                </h3>
                <div class="flex items-center gap-3">
                    <div class="flex items-center gap-1.5">
                        <div class="w-3 h-3 bg-lime-500 rounded-full"></div>
                        <span class="text-[10px] font-bold text-zinc-500 uppercase tracking-wider">Real</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <div class="w-3 h-3 bg-zinc-200 dark:bg-zinc-700 rounded-full"></div>
                        <span class="text-[10px] font-bold text-zinc-500 uppercase tracking-wider">Meta</span>
                    </div>
                </div>
            </div>
            <div class="flex-1 min-h-[350px] w-full" wire:ignore>
                <canvas id="municipiosChart"></canvas>
            </div>
        </div>

        {{-- Type Distribution Chart --}}
        <div class="bg-white dark:bg-zinc-900 rounded-3xl p-6 border border-zinc-200 dark:border-zinc-800 shadow-sm flex flex-col">
            <h3 class="text-sm font-black text-zinc-800 dark:text-zinc-100 uppercase tracking-wider mb-6 flex items-center gap-2">
                <div class="w-2 h-6 bg-blue-500 rounded-full"></div>
                Distribución por Tipo de Transcripción
            </h3>
            <div class="flex-1 min-h-[350px] w-full flex items-center justify-center" wire:ignore>
                <canvas id="tiposChart"></canvas>
            </div>
        </div>
    </div>

    {{-- Monthly Progress Trend --}}
    <div class="bg-white dark:bg-zinc-900 rounded-3xl p-6 border border-zinc-200 dark:border-zinc-800 shadow-sm">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-sm font-black text-zinc-800 dark:text-zinc-100 uppercase tracking-wider flex items-center gap-2">
                <div class="w-2 h-6 bg-amber-500 rounded-full"></div>
                Tendencia Mensual Acumulada
            </h3>
            <p class="text-xs text-zinc-500 font-medium">Seguimiento del cumplimiento acumulado vs Meta Anual</p>
        </div>
        <div class="min-h-[300px] w-full" wire:ignore>
            <canvas id="tendenciaMensualChart"></canvas>
        </div>
    </div>

    {{-- Municipality Cards Grid --}}
    <div class="pt-4 border-t border-zinc-200 dark:border-zinc-800">
        <div class="flex items-center gap-2 mb-6">
            <flux:icon.building-office-2 class="w-5 h-5 text-zinc-400" />
            <h2 class="text-lg font-black text-zinc-800 dark:text-zinc-100 uppercase tracking-tight">Detalle Individual por Municipio</h2>
        </div>
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            @foreach($municipioCards as $card)
                <div class="bg-white dark:bg-zinc-900 rounded-[2rem] border {{ $card['porcentaje'] >= 100 ? 'border-lime-500 dark:border-lime-400 shadow-[0_0_20px_rgba(132,204,22,0.15)]' : 'border-zinc-200 dark:border-zinc-800 shadow-md' }} overflow-hidden flex flex-col hover:border-zinc-300 dark:hover:border-zinc-700 transition-all group relative">
                    
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

<div>
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-black text-zinc-900 dark:text-white tracking-tight">
                Mi Dashboard
            </h1>
            <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1 font-medium">
                Resumen estadístico anual &bull; <span class="text-indigo-600 dark:text-indigo-400">{{ now()->translatedFormat('l j \d\e F') }}</span>
            </p>
        </div>
    </div>

    {{-- Layout Grid Principal (Estilo NexusBank) --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        {{-- COLUMNA IZQUIERDA (Gráficos Principales) --}}
        <div class="xl:col-span-2 flex flex-col gap-6">

            {{-- Fila 1: Filtros / Botones rápidos (Opcionales, decorativos para estética) --}}
            <div class="flex gap-2 overflow-x-auto pb-2 scrollbar-hide">
                <button class="px-5 py-2 rounded-full bg-indigo-600 text-white text-xs font-bold tracking-wide shadow-lg shadow-indigo-500/30 whitespace-nowrap">
                    Todo el Estado
                </button>
                <button class="px-5 py-2 rounded-full bg-white dark:bg-zinc-900 text-zinc-600 dark:text-zinc-300 text-xs font-bold border border-zinc-200 dark:border-zinc-800 hover:bg-zinc-50 dark:hover:bg-zinc-800 transition-colors whitespace-nowrap">
                    Abordaje
                </button>
                <button class="px-5 py-2 rounded-full bg-white dark:bg-zinc-900 text-zinc-600 dark:text-zinc-300 text-xs font-bold border border-zinc-200 dark:border-zinc-800 hover:bg-zinc-50 dark:hover:bg-zinc-800 transition-colors whitespace-nowrap">
                    Feria de Campo
                </button>
                <button class="px-5 py-2 rounded-full bg-white dark:bg-zinc-900 text-zinc-600 dark:text-zinc-300 text-xs font-bold border border-zinc-200 dark:border-zinc-800 hover:bg-zinc-50 dark:hover:bg-zinc-800 transition-colors whitespace-nowrap">
                    Vulnerabilidad
                </button>
            </div>

            {{-- Fila 2: Gráfico de Evolución Anual (Bar Chart) --}}
            <div class="bg-white dark:bg-[#18181b] rounded-3xl p-6 border border-zinc-100 dark:border-zinc-800/80 shadow-xl shadow-black/5 relative overflow-hidden group flex flex-col">
                {{-- Efecto Glow de fondo --}}
                <div class="absolute -top-24 -left-24 w-64 h-64 bg-indigo-500/10 rounded-full blur-3xl group-hover:bg-indigo-500/20 transition-all duration-700"></div>
                
                <div class="flex justify-between items-center mb-6 relative z-10">
                    <h3 class="text-base font-bold text-zinc-800 dark:text-zinc-100">Evolución Anual</h3>
                    <div class="text-xs font-bold text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-500/10 px-3 py-1 rounded-full border border-indigo-100 dark:border-indigo-500/20">
                        Total Atendidos
                    </div>
                </div>
                
                <div class="flex-1 w-full min-h-[250px] relative z-10" wire:ignore>
                    <canvas id="monthlyChart"></canvas>
                </div>
            </div>

            {{-- Fila 3: Gráfico de Distribución y Mini KPIs --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Doughnut Chart --}}
                <div class="bg-white dark:bg-[#18181b] rounded-3xl p-6 border border-zinc-100 dark:border-zinc-800/80 shadow-xl shadow-black/5 flex flex-col relative overflow-hidden">
                    <h3 class="text-base font-bold text-zinc-800 dark:text-zinc-100 mb-4 relative z-10">Distribución de Actividades</h3>
                    <div class="flex-1 w-full min-h-[220px] flex items-center justify-center relative z-10" wire:ignore>
                        <canvas id="distributionChart"></canvas>
                    </div>
                </div>

                {{-- Mini KPIs (Grid 2x2) --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    {{-- Mini Tarjeta 1: Registros --}}
                    <div class="bg-white dark:bg-[#18181b] rounded-3xl p-5 border border-zinc-100 dark:border-zinc-800/80 shadow-xl shadow-black/5 flex flex-col justify-center relative overflow-hidden">
                        <div class="flex justify-between items-start relative z-10">
                            <div>
                                <h4 class="text-2xl font-black text-zinc-800 dark:text-zinc-100">{{ number_format($totalRegistros, 0, ',', '.') }}</h4>
                                <p class="text-[10px] font-bold text-zinc-500 dark:text-zinc-400 uppercase tracking-widest mt-1">Registros</p>
                            </div>
                            <div class="w-8 h-8 rounded-full bg-emerald-50 dark:bg-emerald-500/10 flex items-center justify-center text-emerald-500">
                                <flux:icon.document-text class="w-4 h-4" />
                            </div>
                        </div>
                    </div>

                    {{-- Mini Tarjeta 2: Municipios --}}
                    <div class="bg-white dark:bg-[#18181b] rounded-3xl p-5 border border-zinc-100 dark:border-zinc-800/80 shadow-xl shadow-black/5 flex flex-col justify-center relative overflow-hidden">
                        <div class="flex justify-between items-start relative z-10">
                            <div>
                                <h4 class="text-2xl font-black text-zinc-800 dark:text-zinc-100">{{ $municipiosAbordados }} <span class="text-sm font-medium text-zinc-400">/ 9</span></h4>
                                <p class="text-[10px] font-bold text-zinc-500 dark:text-zinc-400 uppercase tracking-widest mt-1">Municipios</p>
                            </div>
                            <div class="w-8 h-8 rounded-full bg-sky-50 dark:bg-sky-500/10 flex items-center justify-center text-sky-500">
                                <flux:icon.map class="w-4 h-4" />
                            </div>
                        </div>
                    </div>

                    {{-- Mini Tarjeta 3: Promedio --}}
                    <div class="bg-white dark:bg-[#18181b] rounded-3xl p-5 border border-zinc-100 dark:border-zinc-800/80 shadow-xl shadow-black/5 flex flex-col justify-center relative overflow-hidden">
                        <div class="flex justify-between items-start relative z-10">
                            <div>
                                <h4 class="text-2xl font-black text-zinc-800 dark:text-zinc-100">{{ number_format($promedioDiario, 1, ',', '.') }}</h4>
                                <p class="text-[10px] font-bold text-zinc-500 dark:text-zinc-400 uppercase tracking-widest mt-1">Prom. Diario</p>
                            </div>
                            <div class="w-8 h-8 rounded-full bg-amber-50 dark:bg-amber-500/10 flex items-center justify-center text-amber-500">
                                <flux:icon.bolt class="w-4 h-4" />
                            </div>
                        </div>
                    </div>

                    {{-- Mini Tarjeta 4: Principal --}}
                    <div class="bg-white dark:bg-[#18181b] rounded-3xl p-5 border border-zinc-100 dark:border-zinc-800/80 shadow-xl shadow-black/5 flex flex-col justify-center relative overflow-hidden">
                        <div class="flex justify-between items-start relative z-10">
                            <div class="w-full">
                                <h4 class="text-lg font-black text-zinc-800 dark:text-zinc-100 truncate w-full" title="{{ count($graphDistribution) > 0 ? $graphDistribution[0]['nombre'] : 'N/A' }}">
                                    {{ count($graphDistribution) > 0 ? $graphDistribution[0]['nombre'] : 'N/A' }}
                                </h4>
                                <p class="text-[10px] font-bold text-zinc-500 dark:text-zinc-400 uppercase tracking-widest mt-1">Top Módulo</p>
                            </div>
                            <div class="w-8 h-8 rounded-full bg-indigo-50 dark:bg-indigo-500/10 flex-shrink-0 flex items-center justify-center text-indigo-500">
                                <flux:icon.star class="w-4 h-4" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- COLUMNA DERECHA (Global Card & Feed) --}}
        <div class="flex flex-col gap-6">

            {{-- Tarjeta Resumen "My Card" --}}
            <div class="rounded-3xl p-6 md:p-8 relative overflow-hidden shadow-2xl shadow-lime-500/30 group"
                 style="background: linear-gradient(135deg, #84cc16 0%, #16a34a 100%); border: 1px solid rgba(255, 255, 255, 0.2);">
                 
                {{-- Estilos de animación para las Olas --}}
                <style>
                    @keyframes waveMove1 { 0% { transform: translateX(0); } 100% { transform: translateX(-50%); } }
                    @keyframes waveMove2 { 0% { transform: translateX(-25%); } 100% { transform: translateX(-75%); } }
                </style>

                {{-- Elementos decorativos (Olas Verdes Claro) --}}
                <div class="absolute bottom-0 left-0 w-full h-[140px] z-0 overflow-hidden rounded-b-3xl pointer-events-none">
                    {{-- Ola 1: Verde Lima Translúcida --}}
                    <svg class="absolute bottom-0 w-[200%] h-[120px]" style="animation: waveMove1 10s linear infinite;" viewBox="0 0 1200 120" preserveAspectRatio="none">
                        <path d="M0,120 V60 Q150,100 300,60 T600,60 T900,60 T1200,60 V120 Z" fill="rgba(190, 242, 100, 0.3)"></path>
                    </svg>
                    {{-- Ola 2: Blanco/Verde muy suave --}}
                    <svg class="absolute bottom-0 w-[200%] h-[90px]" style="animation: waveMove2 14s linear infinite;" viewBox="0 0 1200 120" preserveAspectRatio="none">
                        <path d="M0,120 V60 Q150,30 300,60 T600,60 T900,60 T1200,60 V120 Z" fill="rgba(255, 255, 255, 0.15)"></path>
                    </svg>
                    {{-- Ola 3: Verde Neón Translúcida --}}
                    <svg class="absolute bottom-0 w-[200%] h-[100px]" style="animation: waveMove1 18s linear infinite reverse;" viewBox="0 0 1200 120" preserveAspectRatio="none">
                        <path d="M0,120 V70 Q150,110 300,70 T600,70 T900,70 T1200,70 V120 Z" fill="rgba(163, 230, 53, 0.2)"></path>
                    </svg>
                </div>

                <div class="absolute -right-12 -top-12 w-40 h-40 bg-white/20 rounded-full blur-3xl z-0"></div>
                <div class="absolute -left-12 -bottom-12 w-32 h-32 bg-yellow-300/20 rounded-full blur-2xl"></div>
                
                <div class="relative z-10 flex justify-between items-start mb-8">
                    <div>
                        <p class="text-white/80 text-sm font-bold tracking-wide uppercase shadow-black/10 drop-shadow-sm">Resumen Global</p>
                        <p class="text-white/90 text-xs mt-1 font-medium">INNutrición Lara</p>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-white/20 backdrop-blur-md border border-white/30 flex items-center justify-center shadow-inner">
                        <flux:icon.chart-pie class="w-5 h-5 text-white drop-shadow-md" />
                    </div>
                </div>

                <div class="relative z-10 mb-6">
                    <p class="text-white/90 text-xs font-semibold mb-1 shadow-black/10 drop-shadow-sm">Total Personas Atendidas</p>
                    <h2 class="text-4xl md:text-5xl font-black text-white tracking-tight tabular-nums drop-shadow-md">
                        {{ number_format($totalAtendidos, 0, ',', '.') }}
                    </h2>
                </div>

                <div class="relative z-10 flex items-center justify-between pt-4 border-t border-white/20">
                    <div class="flex items-center gap-2">
                        <div class="w-2 h-2 rounded-full bg-yellow-300 animate-pulse shadow-[0_0_8px_rgba(253,224,71,0.8)]"></div>
                        <span class="text-white font-semibold text-xs drop-shadow-sm">Sistema Activo</span>
                    </div>
                    <span class="text-white font-black text-xs tracking-widest drop-shadow-sm">{{ date('Y') }}</span>
                </div>
            </div>

            {{-- Feed Últimos Registros (Estilo Recent Transactions) --}}
            <div class="bg-white dark:bg-[#18181b] rounded-3xl p-6 border border-zinc-100 dark:border-zinc-800/80 shadow-xl shadow-black/5 flex-1 flex flex-col">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-base font-bold text-zinc-800 dark:text-zinc-100">Últimos Registros</h3>
                    <button class="text-xs font-bold text-zinc-500 hover:text-zinc-800 dark:hover:text-zinc-300 transition-colors">
                        Ver Todos
                    </button>
                </div>

                <div class="flex flex-col gap-4 flex-1">
                    @forelse($recentActivities as $act)
                        <div class="flex items-center justify-between p-3 rounded-2xl hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors group cursor-pointer border border-transparent hover:border-zinc-100 dark:hover:border-zinc-800">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center relative overflow-hidden">
                                    <div class="absolute inset-0 opacity-20" style="background-color: {{ $act->color }}"></div>
                                    <flux:icon.bolt class="w-5 h-5 relative z-10" style="color: {{ $act->color }}" />
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-zinc-800 dark:text-zinc-200 group-hover:text-indigo-500 transition-colors">
                                        {{ $act->tipo }}
                                    </p>
                                    <p class="text-xs text-zinc-500 font-medium">
                                        {{ \Carbon\Carbon::parse($act->fecha)->translatedFormat('d M, Y') }}
                                    </p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-xs font-bold text-zinc-400">
                                    {{ \Carbon\Carbon::parse($act->created_at)->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <flux:icon.inbox class="w-8 h-8 text-zinc-300 dark:text-zinc-700 mx-auto mb-2" />
                            <p class="text-sm text-zinc-500">No hay registros recientes</p>
                        </div>
                    @endforelse
                </div>
                
            </div>

        </div>
    </div>

    {{-- Script de Gráficas --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    @script
    <script>
        let dashboardCharts = {};

        const initDashboardCharts = () => {
            // Destruir previas
            ['monthlyChart', 'distributionChart'].forEach(id => {
                const existing = Chart.getChart(id);
                if (existing) existing.destroy();
            });

            const isDark = document.documentElement.classList.contains('dark');
            const textColor = isDark ? '#a1a1aa' : '#71717a';
            const gridColor = isDark ? 'rgba(255, 255, 255, 0.05)' : 'rgba(0, 0, 0, 0.04)';

            Chart.defaults.color = textColor;
            Chart.defaults.font.family = 'Inter, system-ui, sans-serif';

            // Datos
            const monthlyData = @json($graphMonthly);
            const distributionData = @json($graphDistribution);

            // 1. Bar Chart (Evolución)
            const ctxM = document.getElementById('monthlyChart');
            if (ctxM && monthlyData.length) {
                // Configurar colores de barras: resaltar el mes actual
                const currentMonthIndex = new Date().getMonth();
                const barColors = monthlyData.map((_, index) => {
                    return index === currentMonthIndex 
                        ? (isDark ? '#818cf8' : '#6366f1') // Indigo claro/oscuro para el actual
                        : (isDark ? 'rgba(99, 102, 241, 0.15)' : 'rgba(99, 102, 241, 0.1)'); // Indigo muy tenue
                });

                const borderColors = monthlyData.map((_, index) => {
                    return index === currentMonthIndex 
                        ? (isDark ? '#a5b4fc' : '#4f46e5')
                        : 'transparent';
                });

                dashboardCharts.monthly = new Chart(ctxM, {
                    type: 'bar',
                    data: {
                        labels: monthlyData.map(d => d.mes),
                        datasets: [{
                            label: 'Atendidos',
                            data: monthlyData.map(d => d.total),
                            backgroundColor: barColors,
                            borderColor: borderColors,
                            borderWidth: index => index.dataIndex === currentMonthIndex ? 2 : 0,
                            borderRadius: 8,
                            borderSkipped: false
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: { color: gridColor, drawBorder: false },
                                border: { display: false }
                            },
                            x: {
                                grid: { display: false },
                                border: { display: false }
                            }
                        },
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: isDark ? 'rgba(0,0,0,0.8)' : 'rgba(255,255,255,0.9)',
                                titleColor: isDark ? '#fff' : '#000',
                                bodyColor: isDark ? '#fff' : '#000',
                                borderColor: gridColor,
                                borderWidth: 1,
                                padding: 10,
                                displayColors: false,
                                callbacks: {
                                    label: function(context) {
                                        let value = context.raw || 0;
                                        return value.toLocaleString('es-ES') + ' Atendidos';
                                    }
                                }
                            }
                        }
                    }
                });
            }

            // 2. Doughnut Chart (Distribución)
            const ctxD = document.getElementById('distributionChart');
            if (ctxD && distributionData.length) {
                dashboardCharts.distribution = new Chart(ctxD, {
                    type: 'doughnut',
                    data: {
                        labels: distributionData.map(d => d.nombre),
                        datasets: [{
                            data: distributionData.map(d => d.total),
                            backgroundColor: distributionData.map(d => d.color),
                            borderWidth: isDark ? 4 : 2,
                            borderColor: isDark ? '#18181b' : '#ffffff', // Match el fondo de la tarjeta
                            hoverOffset: 10
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '75%',
                        plugins: {
                            legend: {
                                position: 'right',
                                labels: {
                                    usePointStyle: true,
                                    padding: 20,
                                    font: { size: 11, weight: 'bold' }
                                }
                            }
                        }
                    }
                });
            }
        };

        $wire.on('refreshDashboardCharts', () => {
            setTimeout(initDashboardCharts, 100);
        });

        setTimeout(() => {
            if (typeof Chart !== 'undefined') {
                initDashboardCharts();
            } else {
                const s = document.querySelector('script[src*="chart.js"]');
                if (s) s.addEventListener('load', initDashboardCharts);
            }
        }, 200);
    </script>
    @endscript
</div>

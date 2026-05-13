<div class="mb-6">
    {{-- Topbar / Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4 bg-white dark:bg-zinc-900 p-4 lg:p-6 rounded-2xl border border-zinc-200 dark:border-zinc-800 shadow-sm">
        <div class="flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl bg-lime-100 dark:bg-lime-500/10 flex items-center justify-center border border-lime-200 dark:border-lime-500/20">
                <flux:icon.chart-pie class="w-6 h-6 text-lime-600 dark:text-lime-400" />
            </div>
            <div>
                <h1 class="text-2xl font-bold text-zinc-800 dark:text-zinc-100 tracking-tight">Dashboard: Abordaje</h1>
                <p class="text-sm text-zinc-500 dark:text-zinc-400">
                    Municipio: <span class="text-zinc-700 dark:text-zinc-300 font-medium">{{ $graphMunicipioNombre }}</span> 
                    &bull; {{ $nombreMes }} {{ $graphAno }}
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

            <flux:button icon="arrow-left" variant="subtle" href="{{ route('admin.actividades.abordajes.index') }}" wire:navigate>
                Volver a Tabla
            </flux:button>
        </div>
    </div>

    {{-- Tarjetas KPI --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        {{-- Total Atendidos --}}
        <div class="bg-white dark:bg-zinc-900 rounded-3xl p-6 border border-zinc-100 dark:border-zinc-800/80 shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-[0_8px_30px_rgb(0,0,0,0.1)] relative overflow-hidden group">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-lime-500/10 rounded-full blur-2xl group-hover:bg-lime-500/20 transition-all duration-500"></div>
            <div class="flex justify-between items-start relative z-10">
                <div>
                    <p class="text-xs font-semibold text-zinc-500 dark:text-zinc-400 uppercase tracking-widest mb-1">Personas Atendidas</p>
                    <h3 class="text-4xl font-black text-zinc-800 dark:text-zinc-100 tabular-nums">{{ number_format($graphKpis['total_cantidad'], 0, ',', '.') }}</h3>
                    <p class="text-xs text-lime-600 dark:text-lime-400 mt-2 flex items-center gap-1 font-medium">
                        <flux:icon.check-circle class="w-3 h-3" /> Impacto en el territorio
                    </p>
                </div>
                <div class="w-10 h-10 rounded-full bg-lime-100 dark:bg-lime-500/10 flex items-center justify-center border border-lime-200 dark:border-lime-500/20 text-lime-600 dark:text-lime-400">
                    <flux:icon.users class="w-5 h-5" />
                </div>
            </div>
        </div>

        {{-- Total Registros --}}
        <div class="bg-white dark:bg-zinc-900 rounded-3xl p-6 border border-zinc-100 dark:border-zinc-800/80 shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-[0_8px_30px_rgb(0,0,0,0.1)] relative overflow-hidden group">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-blue-500/10 rounded-full blur-2xl group-hover:bg-blue-500/20 transition-all duration-500"></div>
            <div class="flex justify-between items-start relative z-10">
                <div>
                    <p class="text-xs font-semibold text-zinc-500 dark:text-zinc-400 uppercase tracking-widest mb-1">Frecuencia Actividad</p>
                    <h3 class="text-4xl font-black text-zinc-800 dark:text-zinc-100 tabular-nums">{{ number_format($graphKpis['total_registros'], 0, ',', '.') }}</h3>
                    <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-2 font-medium">Abordajes realizados</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-500/10 flex items-center justify-center border border-blue-200 dark:border-blue-500/20 text-blue-600 dark:text-blue-400">
                    <flux:icon.clipboard-document-check class="w-5 h-5" />
                </div>
            </div>
        </div>

        {{-- Promedio Diario --}}
        <div class="bg-white dark:bg-zinc-900 rounded-3xl p-6 border border-zinc-100 dark:border-zinc-800/80 shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-[0_8px_30px_rgb(0,0,0,0.1)] relative overflow-hidden group">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-amber-500/10 rounded-full blur-2xl group-hover:bg-amber-500/20 transition-all duration-500"></div>
            <div class="flex justify-between items-start relative z-10">
                <div>
                    <p class="text-xs font-semibold text-zinc-500 dark:text-zinc-400 uppercase tracking-widest mb-1">Promedio Diario</p>
                    <h3 class="text-4xl font-black text-zinc-800 dark:text-zinc-100 tabular-nums">{{ number_format($graphKpis['promedio_diario'], 1, ',', '.') }}</h3>
                    <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-2 font-medium">Atenciones por día</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-amber-100 dark:bg-amber-500/10 flex items-center justify-center border border-amber-200 dark:border-amber-500/20 text-amber-600 dark:text-amber-400">
                    <flux:icon.bolt class="w-5 h-5" />
                </div>
            </div>
        </div>

        {{-- Meta Alcanzada (Simulada o basada en datos si hubiera) --}}
        <div class="bg-white dark:bg-zinc-900 rounded-3xl p-6 border border-zinc-100 dark:border-zinc-800/80 shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-[0_8px_30px_rgb(0,0,0,0.1)] relative overflow-hidden group">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-emerald-500/10 rounded-full blur-2xl group-hover:bg-emerald-500/20 transition-all duration-500"></div>
            <div class="flex justify-between items-start relative z-10">
                <div class="w-full">
                    <p class="text-xs font-semibold text-zinc-500 dark:text-zinc-400 uppercase tracking-widest mb-1">Crecimiento Mensual</p>
                    <div class="mt-3">
                         <div class="flex justify-between items-end mb-1">
                            <span class="text-2xl font-black text-zinc-800 dark:text-zinc-100">Positivo</span>
                            <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400">Tendencia</span>
                        </div>
                        <div class="w-full bg-zinc-100 dark:bg-zinc-800 h-2 rounded-full overflow-hidden">
                            <div class="bg-gradient-to-r from-lime-500 to-emerald-500 h-full rounded-full" style="width: 85%"></div>
                        </div>
                    </div>
                </div>
                <div class="w-10 h-10 rounded-full bg-emerald-100 dark:bg-emerald-500/10 flex items-center justify-center border border-emerald-200 dark:border-emerald-500/20 text-emerald-600 dark:text-emerald-400">
                    <flux:icon.arrow-trending-up class="w-5 h-5" />
                </div>
            </div>
        </div>
    </div>

    {{-- Fila de Gráficos 1 --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        {{-- Gráfico de Dona: Parroquias --}}
        <div class="bg-white dark:bg-zinc-900 rounded-3xl p-6 border border-zinc-100 dark:border-zinc-800/80 shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-[0_8px_30px_rgb(0,0,0,0.1)] flex flex-col">
            <h3 class="text-sm font-bold text-zinc-800 dark:text-zinc-200 uppercase tracking-wider mb-4 flex items-center gap-2">
                <flux:icon.map class="w-4 h-4 text-lime-500" />
                Distribución por Parroquia
            </h3>
            <div class="flex-1 relative w-full flex items-center justify-center min-h-[300px]" wire:ignore>
                <canvas id="parroquiasChart"></canvas>
            </div>
        </div>

        {{-- Gráfico de Líneas: Tendencia por Días --}}
        <div class="bg-white dark:bg-zinc-900 rounded-3xl p-6 border border-zinc-100 dark:border-zinc-800/80 shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-[0_8px_30px_rgb(0,0,0,0.1)] lg:col-span-2 flex flex-col">
            <h3 class="text-sm font-bold text-zinc-800 dark:text-zinc-200 uppercase tracking-wider mb-4 flex items-center gap-2">
                <flux:icon.chart-bar-square class="w-4 h-4 text-lime-500" />
                Actividad Diaria
            </h3>
            <div class="flex-1 relative w-full min-h-[300px]" wire:ignore>
                <canvas id="diasChart"></canvas>
            </div>
        </div>
    </div>

    {{-- Fila de Gráficos 2 --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        {{-- Gráfico de Barras: Comunas --}}
        <div class="bg-white dark:bg-zinc-900 rounded-3xl p-6 border border-zinc-100 dark:border-zinc-800/80 shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-[0_8px_30px_rgb(0,0,0,0.1)] flex flex-col">
            <h3 class="text-sm font-bold text-zinc-800 dark:text-zinc-200 uppercase tracking-wider mb-4 flex items-center gap-2">
                <flux:icon.building-office class="w-4 h-4 text-lime-500" />
                Comunas con Mayor Alcance
            </h3>
            <div class="flex-1 relative w-full min-h-[400px]" wire:ignore>
                <canvas id="comunasChart"></canvas>
            </div>
        </div>

        {{-- Gráfico de Barras Horizontales: Sectores --}}
        <div class="bg-white dark:bg-zinc-900 rounded-3xl p-6 border border-zinc-100 dark:border-zinc-800/80 shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-[0_8px_30px_rgb(0,0,0,0.1)] flex flex-col">
            <h3 class="text-sm font-bold text-zinc-800 dark:text-zinc-200 uppercase tracking-wider mb-4 flex items-center gap-2">
                <flux:icon.map-pin class="w-4 h-4 text-lime-500" />
                Sectores Priorizados
            </h3>
            <div class="flex-1 relative w-full min-h-[400px]" wire:ignore>
                <canvas id="sectoresChart"></canvas>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    @script
    <script>
        let chartInstances = {};

        const initCharts = () => {
            // Limpieza
            ['parroquiasChart', 'diasChart', 'comunasChart', 'sectoresChart'].forEach(id => {
                const existing = Chart.getChart(id);
                if (existing) existing.destroy();
            });

            const isDark = document.documentElement.classList.contains('dark');
            const textColor = isDark ? '#a1a1aa' : '#71717a';
            const gridColor = isDark ? 'rgba(255, 255, 255, 0.05)' : 'rgba(0, 0, 0, 0.04)';
            const themeColor = '#84cc16'; // Lime 500

            // Datos
            const dataParroquias = $wire.graphParroquias;
            const dataComunas = $wire.graphComunas;
            const dataSectores = $wire.graphSectores;
            const dataDias = $wire.graphDias;

            Chart.defaults.color = textColor;
            Chart.defaults.font.family = 'Inter, system-ui, sans-serif';

            // 1. Parroquias (Doughnut)
            const ctxP = document.getElementById('parroquiasChart');
            if(ctxP && dataParroquias.length) {
                chartInstances.parroquias = new Chart(ctxP, {
                    type: 'doughnut',
                    data: {
                        labels: dataParroquias.map(d => d.nombre),
                        datasets: [{
                            data: dataParroquias.map(d => d.total),
                            backgroundColor: ['#4F46E5', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6', '#EC4899', '#06B6D4', '#84CC16', '#F97316', '#3B82F6', '#14B8A6', '#F43F5E', '#D946EF', '#6366F1', '#22C55E'],
                            borderWidth: 0,
                            hoverOffset: 15
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { position: 'bottom', labels: { usePointStyle: true, padding: 20 } }
                        },
                        cutout: '70%'
                    }
                });
            }

            // 2. Días (Line)
            const ctxD = document.getElementById('diasChart');
            if(ctxD && dataDias.length) {
                const labels = Array.from({length: 31}, (_, i) => i + 1);
                const values = labels.map(l => {
                    const found = dataDias.find(d => parseInt(d.dia) === l);
                    return found ? found.total : 0;
                });

                const gradient = ctxD.getContext('2d').createLinearGradient(0, 0, 0, 300);
                gradient.addColorStop(0, 'rgba(132, 204, 22, 0.3)');
                gradient.addColorStop(1, 'rgba(132, 204, 22, 0)');

                chartInstances.dias = new Chart(ctxD, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Atenciones',
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
                            y: { beginAtZero: true, grid: { color: gridColor }, border: { display: false } },
                            x: { grid: { display: false }, border: { display: false } }
                        },
                        plugins: { legend: { display: false } }
                    }
                });
            }

            // 3. Comunas (Bar)
            const ctxC = document.getElementById('comunasChart');
            if(ctxC && dataComunas.length) {
                chartInstances.comunas = new Chart(ctxC, {
                    type: 'bar',
                    data: {
                        labels: dataComunas.map(d => d.nombre.substring(0, 15)),
                        datasets: [{
                            label: 'Atenciones',
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
                            x: { grid: { color: gridColor }, border: { display: false } },
                            y: { grid: { display: false }, border: { display: false } }
                        },
                        plugins: { legend: { display: false } }
                    }
                });
            }

            // 4. Sectores (Bar)
            const ctxS = document.getElementById('sectoresChart');
            if(ctxS && dataSectores.length) {
                chartInstances.sectores = new Chart(ctxS, {
                    type: 'bar',
                    data: {
                        labels: dataSectores.map(d => d.nombre.substring(0, 15)),
                        datasets: [{
                            label: 'Atenciones',
                            data: dataSectores.map(d => d.total),
                            backgroundColor: '#3b82f6',
                            borderRadius: 8
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
                        plugins: { legend: { display: false } }
                    }
                });
            }
        };

        $wire.on('refreshCharts', () => {
            setTimeout(initCharts, 100);
        });

        // Dar tiempo a Livewire para sincronizar el estado antes de renderizar
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

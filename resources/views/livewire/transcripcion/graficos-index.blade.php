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
        <div class="bg-white dark:bg-zinc-900 rounded-2xl p-6 border border-zinc-200 dark:border-zinc-800 shadow-sm relative overflow-hidden group">
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
        <div class="bg-white dark:bg-zinc-900 rounded-2xl p-6 border border-zinc-200 dark:border-zinc-800 shadow-sm relative overflow-hidden group">
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
        <div class="bg-white dark:bg-zinc-900 rounded-2xl p-6 border border-zinc-200 dark:border-zinc-800 shadow-sm relative overflow-hidden group">
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
        <div class="bg-white dark:bg-zinc-900 rounded-2xl p-6 border border-zinc-200 dark:border-zinc-800 shadow-sm relative overflow-hidden group">
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
        <div class="bg-white dark:bg-zinc-900 rounded-2xl p-6 border border-zinc-200 dark:border-zinc-800 shadow-sm col-span-1 flex flex-col">
            <h3 class="text-sm font-bold text-zinc-800 dark:text-zinc-200 uppercase tracking-wider mb-4 flex items-center gap-2">
                <flux:icon.map class="w-4 h-4 text-{{ $colorThemaTw }}-500" />
                Distribución por Parroquia
            </h3>
            <div class="flex-1 relative w-full flex items-center justify-center min-h-[250px]" wire:ignore>
                <canvas id="parroquiasChart"></canvas>
            </div>
        </div>

        {{-- Gráfico de Líneas: Tendencia por Días --}}
        <div class="bg-white dark:bg-zinc-900 rounded-2xl p-6 border border-zinc-200 dark:border-zinc-800 shadow-sm col-span-1 lg:col-span-2 flex flex-col">
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
        <div class="bg-white dark:bg-zinc-900 rounded-2xl p-6 border border-zinc-200 dark:border-zinc-800 shadow-sm flex flex-col">
            <h3 class="text-sm font-bold text-zinc-800 dark:text-zinc-200 uppercase tracking-wider mb-4 flex items-center gap-2">
                <flux:icon.building-office class="w-4 h-4 text-{{ $colorThemaTw }}-500" />
                Comunas Activas
            </h3>
            <div class="flex-1 relative w-full min-h-[600px]" wire:ignore>
                <canvas id="comunasChart"></canvas>
            </div>
        </div>

        {{-- Gráfico de Barras Horizontales: Sectores --}}
        <div class="bg-white dark:bg-zinc-900 rounded-2xl p-6 border border-zinc-200 dark:border-zinc-800 shadow-sm flex flex-col">
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
            const textColor = isDarkMode ? '#a1a1aa' : '#52525b';
            const gridColor = isDarkMode ? '#27272a' : '#e4e4e7';
            const tooltipBg = isDarkMode ? 'rgba(24, 24, 27, 0.9)' : 'rgba(255, 255, 255, 0.9)';
            const tooltipText = isDarkMode ? '#fff' : '#18181b';
            const tooltipBorder = isDarkMode ? '#3f3f46' : '#e4e4e7';
            const cardBg = isDarkMode ? '#18181b' : '#ffffff';

            Chart.defaults.color = textColor;
            Chart.defaults.borderColor = gridColor;
            Chart.defaults.font.family = 'Inter, ui-sans-serif, system-ui, sans-serif';
            
            const tooltipOptions = {
                backgroundColor: tooltipBg,
                titleColor: tooltipText,
                bodyColor: tooltipText,
                borderColor: tooltipBorder,
                borderWidth: 1,
                padding: 12,
                cornerRadius: 8,
                displayColors: true,
                boxPadding: 4,
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
                            borderWidth: 2,
                            borderColor: cardBg,
                            hoverOffset: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { position: 'bottom', labels: { boxWidth: 12, padding: 15 } },
                            tooltip: tooltipOptions
                        },
                        cutout: '75%'
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
                gradient.addColorStop(0, `rgba(${r}, ${g}, ${b}, 0.5)`);
                gradient.addColorStop(1, `rgba(${r}, ${g}, ${b}, 0)`);

                charts.dias = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labelsDias,
                        datasets: [{
                            label: 'Cantidades',
                            data: dataDiasCompletos,
                            borderColor: themeHex,
                            backgroundColor: gradient,
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: cardBg,
                            pointBorderColor: themeHex,
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false }, tooltip: tooltipOptions },
                        scales: {
                            y: { beginAtZero: true },
                            x: { grid: { display: false } }
                        }
                    }
                });
            }

            // 3. Gráfico de Barras (Comunas)
            const ctxComunas = document.getElementById('comunasChart');
            if(ctxComunas && datosComunas.length > 0) {
                charts.comunas = new Chart(ctxComunas, {
                    type: 'bar',
                    data: {
                        labels: datosComunas.map(d => d.nombre.length > 15 ? d.nombre.substring(0,15)+'...' : d.nombre),
                        datasets: [{
                            label: 'Cantidades',
                            data: datosComunas.map(d => d.total),
                            backgroundColor: themeHex,
                            borderRadius: 6,
                            barPercentage: 0.6
                        }]
                    },
                    options: {
                        indexAxis: 'y',
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false }, tooltip: tooltipOptions },
                        scales: {
                            x: { beginAtZero: true },
                            y: { grid: { display: false } }
                        }
                    }
                });
            }

            // 4. Gráfico Horizontal (Sectores)
            const ctxSectores = document.getElementById('sectoresChart');
            if(ctxSectores && datosSectores.length > 0) {
                charts.sectores = new Chart(ctxSectores, {
                    type: 'bar',
                    data: {
                        labels: datosSectores.map(d => d.nombre.length > 20 ? d.nombre.substring(0,20)+'...' : d.nombre),
                        datasets: [{
                            label: 'Cantidades',
                            data: datosSectores.map(d => d.total),
                            backgroundColor: '#3b82f6', // blue-500 para contraste
                            borderRadius: 6,
                            barPercentage: 0.7
                        }]
                    },
                    options: {
                        indexAxis: 'y',
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false }, tooltip: tooltipOptions },
                        scales: {
                            x: { beginAtZero: true },
                            y: { grid: { display: false } }
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

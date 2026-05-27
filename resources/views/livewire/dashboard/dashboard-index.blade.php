<div>
    {{-- Estilos de la pila de tarjetas 3D con fuentes normalizadas e Inter --}}
    <style>
        /* Widget de Tarjetas: Stack absoluto interactivo */
        .card-stack {
            position: relative;
            width: 100%;
            overflow: visible;
        }

        .stack-card {
            position: absolute;
            width: 100%;
            height: 150px;
            /* Altura completa de la tarjeta */
            border-radius: 22px;
            padding: 16px 20px;
            overflow: hidden;
            box-shadow: 0 8px 16px -4px rgba(0, 0, 0, 0.2);
            transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1),
                filter 0.4s ease,
                box-shadow 0.4s ease;
            cursor: pointer;
            opacity: 1 !important;
            /* Siempre 100% sólidas para evitar transparencias y mezclas de texto */
        }

        /* Al pasar el mouse sobre la pila, apagamos (opacamos de brillo, no de transparencia) las de atrás */
        .card-stack:hover .stack-card {
            filter: brightness(0.6) saturate(50%);
        }

        /* Al pasar el mouse sobre una tarjeta individual, se levanta, se superpone y brilla con color vivo */
        .card-stack .stack-card:hover {
            z-index: 100 !important;
            /* Trae al frente */
            filter: brightness(1) saturate(100%) !important;
            transform: translateY(-15px) scale(1.02);
            /* Se levanta de la pila */
            box-shadow: 0 20px 35px -8px rgba(0, 0, 0, 0.35);
        }

        .stack-card-content {
            opacity: 1;
            /* Siempre visible dentro de la tarjeta */
            transform: none;
        }

        /* Estilos de olas flotantes en el fondo de la tarjeta resumen */
        @keyframes floatingWave {
            0% {
                transform: translateX(0) translateZ(0) scaleY(1);
            }

            50% {
                transform: translateX(-25%) translateZ(0) scaleY(0.85);
            }

            100% {
                transform: translateX(-50%) translateZ(0) scaleY(1);
            }
        }

        .wave-animate-1 {
            animation: floatingWave 12s cubic-bezier(0.445, 0.05, 0.55, 0.95) infinite;
        }

        .wave-animate-2 {
            animation: floatingWave 18s cubic-bezier(0.445, 0.05, 0.55, 0.95) infinite reverse;
        }

        /* Glassmorphism utility helpers */
        .glass-panel {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }

        .light .glass-panel {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        /* Ocultar barra de scroll completamente */
        .no-scrollbar::-webkit-scrollbar {
            display: none !important;
            width: 0 !important;
            height: 0 !important;
            background: transparent !important;
        }

        .no-scrollbar {
            -ms-overflow-style: none !important;
            scrollbar-width: none !important;
        }

        /* Estilos del Mapa Interactivo Lara */
        .map-container svg {
            width: 100%;
            height: auto;
            max-height: none;
            display: block;
            filter: drop-shadow(0 16px 40px rgba(0, 0, 0, 0.18));
            transition: all 0.3s ease;
        }

        .municipio-group {
            transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.3s ease;
            transform-origin: center;
        }

        /* Parroquias: translucidez elegante por defecto */
        .municipio-group .parroquia-path {
            fill-opacity: 0.85;
            transition: fill-opacity 0.2s ease, stroke 0.2s ease, stroke-width 0.2s ease;
        }

        /* Hover sobre un municipio ilumina todas sus parroquias */
        .municipio-group:hover .parroquia-path {
            fill-opacity: 1 !important;
            stroke: #ffffff !important;
            stroke-width: 1.2px !important;
            stroke-opacity: 0.95 !important;
        }

        /* Estado Activo / Filtrado */
        .municipio-group.active-muni .parroquia-path {
            fill-opacity: 1 !important;
            stroke: #ffffff !important;
            stroke-width: 1.6px !important;
            stroke-opacity: 1 !important;
            filter: drop-shadow(0 0 6px rgba(255, 255, 255, 0.45));
        }

        /* Bordes exteriores de los municipios */
        .muni-outline-border {
            stroke: #111827;
            stroke-dasharray: none;
            stroke-width: 2.4px;
            stroke-opacity: 0.75;
            transition: stroke-opacity 0.2s ease, stroke-width 0.2s ease;
        }

        .municipio-group:hover .muni-outline-border {
            stroke: #030712;
            stroke-width: 2.8px;
            stroke-opacity: 0.95;
        }

        .municipio-group.active-muni .muni-outline-border {
            stroke: #ffffff;
            stroke-width: 3px;
            stroke-opacity: 1;
        }
    </style>

    <div class="flex flex-col gap-8 relative overflow-hidden">

        {{-- Efectos de Brillo Ambiental de Fondo (Eliminados a petición) --}}

        {{-- CABECERA DEL PANEL (Premium - Verde Lima con fondo y difuminados) --}}
        <div
            class="flex flex-col md:flex-row md:items-center justify-between gap-6 p-7
                    bg-gradient-to-br from-lime-50 via-white to-emerald-50
                    dark:from-zinc-900 dark:via-zinc-900 dark:to-zinc-800
                    border border-lime-100 dark:border-zinc-700
                    rounded-3xl shadow-md overflow-hidden relative group mb-2">

            {{-- Difuminados decorativos internos --}}
            <div
                class="absolute -right-8 -top-8 w-44 h-44 bg-lime-400/20 dark:bg-lime-500/10 rounded-full blur-3xl pointer-events-none group-hover:scale-125 transition-transform duration-700">
            </div>
            <div
                class="absolute -left-8 -bottom-8 w-44 h-44 bg-emerald-400/20 dark:bg-emerald-500/10 rounded-full blur-3xl pointer-events-none group-hover:scale-125 transition-transform duration-700">
            </div>
            <div
                class="absolute right-1/3 top-0 w-24 h-24 bg-lime-300/15 dark:bg-lime-400/5 rounded-full blur-2xl pointer-events-none">
            </div>

            <div class="flex items-center gap-5 relative z-10">
                <div
                    class="w-14 h-14 rounded-2xl bg-gradient-to-br from-lime-400 to-emerald-600
                            flex items-center justify-center
                            shadow-xl shadow-emerald-500/30
                            ring-4 ring-lime-100 dark:ring-lime-500/20
                            transform group-hover:scale-110 group-hover:rotate-3 transition-all duration-500">
                    <flux:icon.chart-pie class="w-8 h-8 text-white drop-shadow" />
                </div>
                <div class="flex flex-col">
                    <h1
                        class="text-3xl font-black tracking-tighter uppercase leading-none
                               text-zinc-800 dark:text-zinc-100">
                        Mi <span
                            class="text-transparent bg-clip-text bg-gradient-to-r from-lime-600 to-emerald-500">Dashboard</span>
                    </h1>
                    <p
                        class="text-[10px] font-bold text-zinc-500 dark:text-zinc-400 uppercase tracking-[0.25em] mt-1.5 flex items-center gap-2">
                        <span
                            class="w-1.5 h-1.5 rounded-full bg-lime-500 shadow-[0_0_8px_rgba(132,204,22,0.7)] animate-pulse"></span>
                        Estadísticas en tiempo real &bull;
                        <span
                            class="text-lime-600 dark:text-lime-400 font-black">{{ now()->translatedFormat('l j \d\e F') }}</span>
                    </p>
                </div>
            </div>

            {{-- Badge decorativo derecho --}}
            <div class="relative z-10 hidden md:flex items-center gap-3">
                <span
                    class="px-4 py-2 rounded-2xl bg-lime-500/10 dark:bg-lime-500/15 border border-lime-200 dark:border-lime-500/30
                             text-lime-700 dark:text-lime-400 text-[10px] font-black uppercase tracking-widest
                             shadow-sm">
                    🌿 INNutrición Lara
                </span>
                <span
                    class="px-4 py-2 rounded-2xl bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700
                             text-zinc-500 dark:text-zinc-400 text-[10px] font-bold uppercase tracking-widest shadow-sm">
                    Año {{ date('Y') }}
                </span>
            </div>
        </div>

        {{-- GRID PRINCIPAL DE 2 COLUMNAS (Estilo Ethereal, tipografía normalizada) --}}
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">

            {{-- COLUMNA IZQUIERDA: Tarjeta Resumen, Mini KPIs y Gráficas --}}
            <div class="xl:col-span-2 flex flex-col gap-8">

                {{-- Bloque superior: Gran tarjeta de balance + KPIs laterales --}}
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                    {{-- Tarjeta Principal "Total Atendidos" (Emerald-Teal gradient) --}}
                    <div class="lg:col-span-2 rounded-[32px] p-8 relative overflow-hidden shadow-2xl shadow-emerald-500/10 group flex flex-col justify-between"
                        style="background: linear-gradient(135deg, #a3e635 0%, #10b981 50%, #06b6d4 100%); min-height: 220px; border: 1px solid rgba(255, 255, 255, 0.25);">

                        {{-- Capas de Olas Flotantes Animadas en SVG --}}
                        <div
                            class="absolute bottom-0 left-0 w-full h-[150px] z-0 overflow-hidden pointer-events-none rounded-b-[32px]">
                            {{-- Ola 1 --}}
                            <svg class="absolute bottom-0 w-[200%] h-[120px] wave-animate-1" viewBox="0 0 1200 120"
                                preserveAspectRatio="none">
                                <path d="M0,120 V60 Q150,100 300,60 T600,60 T900,60 T1200,60 V120 Z"
                                    fill="rgba(255, 255, 255, 0.18)"></path>
                            </svg>
                            {{-- Ola 2 --}}
                            <svg class="absolute bottom-0 w-[200%] h-[95px] wave-animate-2" viewBox="0 0 1200 120"
                                preserveAspectRatio="none">
                                <path d="M0,120 V60 Q150,30 300,60 T600,60 T900,60 T1200,60 V120 Z"
                                    fill="rgba(255, 255, 255, 0.1)"></path>
                            </svg>
                        </div>

                        <div
                            class="absolute -right-8 -top-8 w-36 h-36 bg-white/20 rounded-full blur-2xl pointer-events-none">
                        </div>

                        <div class="relative z-10 flex justify-between items-start">
                            <div>
                                <p class="text-white/80 text-[11px] font-bold tracking-widest uppercase drop-shadow-sm">
                                    Total Atendidos</p>
                                <p class="text-white/90 text-xs font-semibold drop-shadow-sm">INNutrición Lara</p>
                            </div>
                            <div
                                class="w-11 h-11 rounded-2xl bg-white/20 backdrop-blur-md border border-white/30 flex items-center justify-center shadow-inner group-hover:scale-105 transition-transform duration-500">
                                <flux:icon.chart-pie class="w-5 h-5 text-white drop-shadow-md" />
                            </div>
                        </div>

                        <div class="relative z-10 my-4">
                            <h2 class="text-4xl font-bold text-white tracking-tight tabular-nums drop-shadow-md">
                                {{ number_format($totalAtendidos, 0, ',', '.') }}
                            </h2>
                            <p
                                class="text-white/90 text-xs font-semibold mt-1 drop-shadow-sm flex items-center gap-1.5">
                                <span class="px-2 py-0.5 rounded-full bg-white/20 text-[10px] font-bold">+5.4%</span>
                                <span>crecimiento este mes</span>
                            </p>
                        </div>

                        <div class="relative z-10 flex items-center justify-between pt-4 border-t border-white/20">
                            <div class="flex items-center gap-2">
                                <button
                                    class="px-4 py-1.5 rounded-full bg-black/30 hover:bg-black/40 backdrop-blur-sm text-[10px] font-bold text-white tracking-wide border border-white/10 active:scale-95 transition-all">
                                    Acciones Rápidas
                                </button>
                                <button
                                    class="px-4 py-1.5 rounded-full bg-white text-zinc-900 text-[10px] font-bold tracking-wide active:scale-95 transition-all">
                                    Descargar Reporte
                                </button>
                            </div>
                            <div class="flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-yellow-300 animate-ping"></span>
                                <span
                                    class="text-white font-bold text-[10px] tracking-wider uppercase drop-shadow-sm">Lara</span>
                            </div>
                        </div>
                    </div>

                    {{-- KPIs Laterales Apilados (Glass Style con negritas normales) --}}
                    <div class="flex flex-col gap-4">

                        {{-- KPI 1: Registros Operativos --}}
                        <div
                            class="rounded-3xl p-5 shadow-xl shadow-indigo-500/5 flex flex-col justify-between flex-1 relative overflow-hidden group hover:scale-[1.01] transition-transform duration-300 border border-indigo-100 dark:border-indigo-500/20 bg-gradient-to-br from-white to-indigo-50/50 dark:from-zinc-900 dark:to-indigo-900/10">
                            <div
                                class="absolute -right-6 -bottom-6 w-24 h-24 bg-indigo-500/20 dark:bg-indigo-500/20 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700">
                            </div>
                            <div
                                class="absolute -left-6 -top-6 w-20 h-20 bg-pink-500/10 dark:bg-pink-500/10 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700">
                            </div>
                            <div class="flex justify-between items-start relative z-10">
                                <p
                                    class="text-[10px] font-bold text-zinc-400 dark:text-zinc-500 uppercase tracking-widest">
                                    Registros Totales</p>
                                <span
                                    class="px-2 py-0.5 rounded-full bg-emerald-500/10 text-emerald-500 text-[9px] font-bold">+15.7%</span>
                            </div>
                            <div class="mt-4 flex items-baseline gap-2">
                                <h4
                                    class="text-2xl font-bold text-zinc-800 dark:text-zinc-100 tracking-tight tabular-nums">
                                    +{{ number_format($totalRegistros, 0, ',', '.') }}
                                </h4>
                            </div>
                            <p class="text-[10px] text-zinc-400 dark:text-zinc-500 mt-1">Operativos cargados este año
                            </p>
                        </div>

                        {{-- KPI 2: Promedio Diario --}}
                        <div
                            class="rounded-3xl p-5 shadow-xl shadow-cyan-500/5 flex flex-col justify-between flex-1 relative overflow-hidden group hover:scale-[1.01] transition-transform duration-300 border border-cyan-100 dark:border-cyan-500/20 bg-gradient-to-br from-white to-cyan-50/50 dark:from-zinc-900 dark:to-cyan-900/10">
                            <div
                                class="absolute -right-6 -bottom-6 w-24 h-24 bg-cyan-500/20 dark:bg-cyan-500/20 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700">
                            </div>
                            <div
                                class="absolute -left-6 -top-6 w-20 h-20 bg-emerald-500/10 dark:bg-emerald-500/10 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700">
                            </div>
                            <div class="flex justify-between items-start relative z-10">
                                <p
                                    class="text-[10px] font-bold text-zinc-400 dark:text-zinc-500 uppercase tracking-widest">
                                    Promedio Diario</p>
                                <span
                                    class="px-2 py-0.5 rounded-full bg-cyan-500/10 text-cyan-500 text-[9px] font-bold">Estable</span>
                            </div>
                            <div class="mt-4 flex items-baseline gap-2">
                                <h4
                                    class="text-2xl font-bold text-zinc-800 dark:text-zinc-100 tracking-tight tabular-nums">
                                    {{ number_format($promedioDiario, 1, ',', '.') }}
                                </h4>
                            </div>
                            <p class="text-[10px] text-zinc-400 dark:text-zinc-500 mt-1">Personas atendidas por día</p>
                        </div>

                    </div>
                </div>

                {{-- (mapa movido abajo como full-width, fuera del grid de 2 cols) --}}

                {{-- Bloque intermedio: Evolución Anual (Bar Chart) --}}
                <div class="glass-panel rounded-[32px] p-6 shadow-2xl relative overflow-hidden flex flex-col">
                    <div
                        class="absolute -top-24 -left-24 w-72 h-72 bg-purple-500/5 dark:bg-purple-600/5 rounded-full blur-3xl pointer-events-none">
                    </div>

                    <div class="flex justify-between items-center mb-6 relative z-10">
                        <div>
                            <h3 class="text-base font-bold text-zinc-800 dark:text-zinc-100">Evolución Anual</h3>
                            <p class="text-xs text-zinc-400 dark:text-zinc-500 font-medium">Volumen mensual de
                                atenciones</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <span
                                class="text-xs font-bold text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-500/10 px-3 py-1.5 rounded-full border border-indigo-100 dark:border-indigo-500/20">
                                Año {{ date('Y') }}
                            </span>
                        </div>
                    </div>

                    <div class="flex-1 w-full min-h-[260px] relative z-10" wire:ignore>
                        <canvas id="monthlyChart"></canvas>
                    </div>
                </div>

                {{-- Bloque inferior: Gráfico de Distribución (Doughnut) con Leyenda Custom HTML --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                    {{-- Doughnut con total interno --}}
                    <div
                        class="glass-panel rounded-[32px] p-6 shadow-2xl flex flex-col justify-between relative overflow-hidden">
                        <div class="absolute -right-24 -top-24 w-48 h-48 bg-indigo-500/5 rounded-full blur-3xl"></div>

                        <h3 class="text-base font-bold text-zinc-800 dark:text-zinc-100 mb-4 relative z-10">
                            Distribución de Actividades</h3>

                        <div class="flex-1 w-full min-h-[220px] flex items-center justify-center relative z-10"
                            wire:ignore>
                            <canvas id="distributionChart"></canvas>
                            <div
                                class="absolute flex flex-col items-center justify-center text-center pointer-events-none">
                                <span
                                    class="text-3xl font-bold text-zinc-800 dark:text-zinc-100 tracking-tight tabular-nums leading-none">
                                    {{ number_format($totalAtendidos, 0, ',', '.') }}
                                </span>
                                <span
                                    class="text-[9px] font-bold text-zinc-400 dark:text-zinc-500 uppercase tracking-widest mt-1">
                                    Personas
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- Leyendas Estilizadas Custom HTML --}}
                    <div
                        class="glass-panel rounded-[32px] p-6 shadow-2xl flex flex-col justify-between relative overflow-hidden">
                        <div>
                            <h3 class="text-base font-bold text-zinc-800 dark:text-zinc-100 mb-1">Módulos Activos</h3>
                            <p class="text-xs text-zinc-400 dark:text-zinc-500 font-medium mb-4">Porcentaje de
                                incidencia en el sistema</p>
                        </div>

                        <div class="flex flex-col gap-3 flex-1 overflow-y-auto max-h-[220px] pr-1 no-scrollbar">
                            @forelse($graphDistribution as $act)
                                @php
                                    $pct = $totalAtendidos > 0 ? round(($act['total'] / $totalAtendidos) * 100, 0) : 0;
                                @endphp
                                <div
                                    class="flex items-center justify-between py-1.5 border-b border-zinc-100/50 dark:border-zinc-800/30">
                                    <div class="flex items-center gap-3">
                                        <span class="w-2.5 h-2.5 rounded-full flex-shrink-0"
                                            style="background-color: {{ $act['color'] }}; box-shadow: 0 0 8px {{ $act['color'] }}60;"></span>
                                        <span
                                            class="text-xs font-semibold text-zinc-700 dark:text-zinc-200">{{ $act['nombre'] }}</span>
                                    </div>
                                    <div class="flex items-center gap-4">
                                        <span
                                            class="text-xs text-zinc-400 dark:text-zinc-500 font-medium tabular-nums">{{ number_format($act['total'], 0, ',', '.') }}</span>
                                        <span
                                            class="text-xs font-bold text-zinc-800 dark:text-zinc-100 tabular-nums w-8 text-right">{{ $pct }}%</span>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-8">
                                    <p class="text-xs text-zinc-500">Sin datos de distribución</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                </div>

                {{-- NUEVA TARJETA: Resumen Estratégico para llenar el espacio --}}
                <div class="glass-panel rounded-[32px] p-6 shadow-2xl relative overflow-hidden flex flex-col justify-between flex-1 border border-zinc-200/50 dark:border-zinc-800/60 bg-gradient-to-br from-indigo-50/50 via-white to-purple-50/50 dark:from-indigo-950/30 dark:via-zinc-900 dark:to-purple-950/20">
                    <div class="absolute -right-20 -top-20 w-48 h-48 bg-fuchsia-500/10 rounded-full blur-3xl pointer-events-none"></div>
                    <div class="absolute -left-20 -bottom-20 w-48 h-48 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>

                    <div class="relative z-10 flex items-start justify-between mb-6">
                        <div>
                            <h3 class="text-base font-bold text-zinc-800 dark:text-zinc-100">Resumen Estratégico</h3>
                            <p class="text-xs text-zinc-400 dark:text-zinc-500 font-medium mt-0.5">Métricas destacadas de la gestión</p>
                        </div>
                        <span class="w-10 h-10 rounded-2xl bg-fuchsia-500/10 flex items-center justify-center text-fuchsia-500 shadow-inner">
                            <flux:icon.sparkles class="w-5 h-5" />
                        </span>
                    </div>

                    <div class="relative z-10 flex flex-col gap-4 mt-auto">
                        {{-- Módulo Líder --}}
                        @if(count($graphDistribution) > 0)
                        <div class="p-4 rounded-2xl bg-white/60 dark:bg-zinc-800/60 border border-zinc-100 dark:border-zinc-700/50 shadow-sm backdrop-blur-md">
                            <p class="text-[10px] font-bold text-zinc-400 dark:text-zinc-500 uppercase tracking-widest mb-1.5 flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-pulse"></span> Módulo con mayor impacto
                            </p>
                            <div class="flex items-end justify-between">
                                <h4 class="text-lg font-black text-zinc-800 dark:text-zinc-100 leading-none" style="color: {{ $graphDistribution[0]['color'] }}">{{ $graphDistribution[0]['nombre'] }}</h4>
                                <span class="text-sm font-bold text-zinc-600 dark:text-zinc-300 tabular-nums leading-none">{{ number_format($graphDistribution[0]['total'], 0, ',', '.') }} <span class="text-[10px]">personas</span></span>
                            </div>
                        </div>
                        @endif

                        {{-- Progreso de Meta Anual (Simulada) --}}
                        @php
                            $meta = 500000;
                            $progreso = $meta > 0 ? min(100, round(($totalAtendidos / $meta) * 100, 1)) : 0;
                        @endphp
                        <div class="p-4 rounded-2xl bg-white/60 dark:bg-zinc-800/60 border border-zinc-100 dark:border-zinc-700/50 shadow-sm backdrop-blur-md">
                            <div class="flex items-center justify-between mb-2.5">
                                <p class="text-[10px] font-bold text-zinc-400 dark:text-zinc-500 uppercase tracking-widest">Meta Anual de Atención (500k)</p>
                                <span class="text-xs font-black text-emerald-500 dark:text-emerald-400">{{ $progreso }}%</span>
                            </div>
                            <div class="w-full h-2.5 bg-zinc-200 dark:bg-zinc-700/50 rounded-full overflow-hidden shadow-inner">
                                <div class="h-full bg-gradient-to-r from-emerald-400 to-emerald-500 rounded-full transition-all duration-1000 ease-out" style="width: {{ $progreso }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- COLUMNA DERECHA: Widget de Tarjetas 3D, KPIs y Feed de Actividad --}}
            <div class="flex flex-col gap-8">

                {{-- 3D CARD STACK WIDGET --}}
                <div class="glass-panel rounded-[32px] p-6 shadow-2xl flex flex-col justify-between relative">
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h3 class="text-base font-bold text-zinc-800 dark:text-zinc-100">Mis Módulos</h3>
                            <p class="text-xs text-zinc-400 dark:text-zinc-500 font-medium">Tarjetas de rendimiento
                                principal</p>
                        </div>
                        <span
                            class="px-2.5 py-1 text-[10px] font-bold text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-500/10 rounded-full">
                            Activo
                        </span>
                    </div>

                    {{-- Contenedor del Stack (Absoluto con altura dinámica según cantidad de módulos) --}}
                    <div class="card-stack my-2"
                        style="height: {{ max(150, (count($graphDistribution) - 1) * 40 + 150) }}px;">

                        @forelse($graphDistribution as $index => $act)
                            <div class="stack-card flex flex-col justify-between text-white flex-shrink-0"
                                style="background-color: {{ $act['color'] }}; 
                                        top: {{ $index * 40 }}px; 
                                        z-index: {{ ($index + 1) * 10 }}; 
                                        filter: brightness({{ count($graphDistribution) > 1 ? 0.7 + $index * (0.3 / (count($graphDistribution) - 1)) : 1.0 }});
                                        background-image: linear-gradient(135deg, rgba(255,255,255,0.2) 0%, rgba(0,0,0,0.05) 100%);">

                                {{-- Cabecera siempre visible --}}
                                <div class="flex justify-between items-center">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center">
                                            <flux:icon.chart-pie class="w-4 h-4 opacity-90" />
                                        </div>
                                        <div>
                                            <p
                                                class="text-[9px] font-bold uppercase tracking-widest opacity-80 leading-none mb-1">
                                                Módulo</p>
                                            <p class="text-xs font-semibold leading-none">{{ $act['nombre'] }}</p>
                                        </div>
                                    </div>
                                    <span
                                        class="text-xs font-black opacity-80 tabular-nums bg-black/10 px-2.5 py-1 rounded-full shadow-inner">{{ number_format($act['total'], 0, ',', '.') }}</span>
                                </div>

                                {{-- Contenido interior completo (visible al superponerse) --}}
                                <div class="stack-card-content mt-4">
                                    <div>
                                        <p class="text-[10px] opacity-75">Personas Atendidas</p>
                                        <h4 class="text-2xl font-bold tracking-tight tabular-nums">
                                            {{ number_format($act['total'], 0, ',', '.') }}</h4>
                                    </div>
                                    <div
                                        class="flex justify-between items-end pt-2 mt-2 border-t border-white/20 text-[9px] font-bold tracking-widest opacity-85">
                                        <span>INNutrición</span>
                                        <span>ESTADÍSTICO</span>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-6">
                                <p class="text-xs text-zinc-500">No hay módulos registrados</p>
                            </div>
                        @endforelse

                    </div>

                    <p class="text-[10px] text-zinc-400 dark:text-zinc-500 text-center font-medium mt-1">💡 Pasa el
                        ratón sobre un módulo para traerlo al frente</p>
                </div>

                {{-- SUB-BLOQUE: Cobertura Regional (Simplificado) --}}
                <div
                    class="glass-panel rounded-[32px] p-6 shadow-2xl relative overflow-hidden flex flex-col justify-between border border-zinc-200/50 dark:border-zinc-800/60 bg-gradient-to-br from-white to-zinc-50/30 dark:from-zinc-950 dark:to-zinc-900/50">
                    <div class="absolute -left-24 -bottom-24 w-48 h-48 bg-emerald-500/5 rounded-full blur-3xl"></div>

                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <h3 class="text-base font-bold text-zinc-800 dark:text-zinc-100">Cobertura Regional</h3>
                            <p class="text-xs text-zinc-400 dark:text-zinc-500 font-medium">Impacto en Municipios</p>
                        </div>
                        <span
                            class="w-10 h-10 rounded-2xl bg-sky-500/10 flex items-center justify-center text-sky-500">
                            <flux:icon.map class="w-5 h-5" />
                        </span>
                    </div>

                    <div class="flex items-center gap-2 mb-6">
                        <h4
                            class="text-3xl font-bold text-zinc-800 dark:text-zinc-100 tracking-tight tabular-nums leading-none">
                            {{ $municipiosAbordados }} <span
                                class="text-base font-bold text-zinc-400 dark:text-zinc-500">/ 9</span>
                        </h4>
                        <p class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest">Municipios activos</p>
                    </div>

                    {{-- Avatares horizontales estilizados decorativos --}}
                    <div class="flex items-center gap-2 pt-4 border-t border-zinc-100/50 dark:border-zinc-800/30">
                        <div class="flex -space-x-2.5 overflow-hidden">
                            <span
                                class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-emerald-500 text-white text-[9px] font-bold ring-4 ring-white dark:ring-zinc-900 shadow-sm shadow-emerald-500/30">AB</span>
                            <span
                                class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-indigo-500 text-white text-[9px] font-bold ring-4 ring-white dark:ring-zinc-900 shadow-sm shadow-indigo-500/30">VL</span>
                            <span
                                class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-cyan-500 text-white text-[9px] font-bold ring-4 ring-white dark:ring-zinc-900 shadow-sm shadow-cyan-500/30">FC</span>
                            <span
                                class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-yellow-500 text-white text-[9px] font-bold ring-4 ring-white dark:ring-zinc-900 shadow-sm shadow-yellow-500/30">LT</span>
                            <span
                                class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-zinc-800 text-zinc-300 text-[8px] font-bold ring-4 ring-white dark:ring-zinc-900">+4</span>
                        </div>
                        <span class="text-[10px] text-zinc-500 dark:text-zinc-400 font-semibold ml-2">Módulos en
                            territorio larense</span>
                    </div>
                </div>

                {{-- FEED DE ÚLTIMOS REGISTROS (Recent transactions style) --}}
                <div class="glass-panel rounded-[32px] p-6 shadow-2xl flex-1 flex flex-col justify-between">
                    <div>
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-base font-bold text-zinc-800 dark:text-zinc-100">Últimos Registros</h3>
                            <button
                                class="text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:underline transition-all">
                                Ver Todos
                            </button>
                        </div>

                        <div class="flex flex-col gap-3.5">
                            @forelse($recentActivities as $act)
                                <div
                                    class="flex items-center justify-between p-3 rounded-2.5xl hover:bg-zinc-50 dark:hover:bg-zinc-800/40 border border-transparent hover:border-zinc-200/50 dark:hover:border-zinc-800/60 transition-all duration-300 group cursor-pointer">
                                    <div class="flex items-center gap-4.5">
                                        <div
                                            class="w-10.5 h-10.5 rounded-2xl flex items-center justify-center relative overflow-hidden flex-shrink-0">
                                            <div class="absolute inset-0 opacity-12 group-hover:scale-110 transition-transform duration-300"
                                                style="background-color: {{ $act->color }}"></div>
                                            <flux:icon.bolt class="w-5.5 h-5.5 relative z-10"
                                                style="color: {{ $act->color }}" />
                                        </div>
                                        <div>
                                            <p class="text-xs font-black transition-colors leading-snug flex items-center flex-wrap gap-1.5 uppercase"
                                                style="color: {{ $act->color }}">
                                                <span>{{ $act->tipo }}</span>
                                                @if (!empty($act->subtipo))
                                                    <span class="text-[9px] px-1.5 py-0.5 rounded border shadow-sm"
                                                        style="background-color: {{ $act->color }}15; border-color: {{ $act->color }}40; color: {{ $act->color }}">
                                                        {{ $act->subtipo }}
                                                    </span>
                                                @endif
                                            </p>
                                            <p
                                                class="text-[10px] text-zinc-400 dark:text-zinc-500 font-semibold mt-0.5">
                                                {{ \Carbon\Carbon::parse($act->fecha)->translatedFormat('d M, Y') }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="text-right flex-shrink-0">
                                        <span
                                            class="px-2.5 py-0.5 rounded-full text-[9px] font-bold bg-emerald-500/10 text-emerald-500">
                                            Exitoso
                                        </span>
                                        <p
                                            class="text-[9px] font-bold text-zinc-400 dark:text-zinc-500 mt-1 tabular-nums">
                                            {{ \Carbon\Carbon::parse($act->created_at)->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-8">
                                    <flux:icon.inbox class="w-8 h-8 text-zinc-300 dark:text-zinc-700 mx-auto mb-2" />
                                    <p class="text-xs text-zinc-500">No hay registros recientes</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

            </div>

        </div>

        {{-- WIDGET DE MAPA: ANCHO TOTAL (fuera del grid lateral, ahora al fondo) --}}
        <div
            class="glass-panel rounded-[32px] p-8 shadow-2xl relative overflow-hidden flex flex-col border border-zinc-200/50 dark:border-zinc-800/60 bg-gradient-to-br from-white via-white to-zinc-50/30 dark:from-zinc-950 dark:via-zinc-950 dark:to-zinc-900/50 mb-8">
            <div class="absolute -left-32 -bottom-32 w-64 h-64 bg-lime-500/5 rounded-full blur-3xl pointer-events-none">
            </div>
            <div class="absolute -right-32 -top-32 w-64 h-64 bg-indigo-500/5 rounded-full blur-3xl pointer-events-none">
            </div>

            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6 relative z-10">
                <div>
                    <h3 class="text-lg font-bold text-zinc-800 dark:text-zinc-100 flex items-center gap-3">
                        Distribución Territorial — Estado Lara
                        @if ($selectedMunicipioNombre)
                            <span
                                class="px-3 py-1 rounded-full bg-lime-500/10 text-lime-600 dark:text-lime-400 text-xs font-black uppercase tracking-wider animate-pulse border border-lime-500/20">
                                Filtrado: {{ $selectedMunicipioNombre }}
                            </span>
                        @endif
                    </h3>
                    <p class="text-xs text-zinc-400 dark:text-zinc-500 font-medium mt-1">Monitorea y filtra la gestión
                        alimentaria a nivel de Municipios y Parroquias</p>
                </div>
                @if ($selectedMunicipioId)
                    <button wire:click="selectMunicipio({{ $selectedMunicipioId }})"
                        class="px-4 py-2 rounded-xl bg-zinc-100 dark:bg-zinc-800 hover:bg-zinc-200 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-300 text-xs font-black tracking-wide shadow-sm active:scale-95 transition-all">
                        Quitar Filtro Regional
                    </button>
                @else
                    <span
                        class="px-3.5 py-1.5 rounded-full bg-zinc-100/50 dark:bg-zinc-800/30 border border-zinc-200/30 dark:border-zinc-700/20 text-[10px] font-bold text-zinc-400 dark:text-zinc-500 flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-lime-500 shadow-sm animate-ping"></span>
                        💡 Haz clic en el mapa para interactuar
                    </span>
                @endif
            </div>

            {{-- SVG del mapa a ancho completo --}}
            <div class="map-container w-full relative z-10 mb-6" wire:ignore>
                {!! file_get_contents(public_path('images/lara-map.svg')) !!}
            </div>

            {{-- Leaderboard horizontal de los 9 municipios --}}
            <div class="relative z-10">
                <div
                    class="text-[10px] font-black text-zinc-400 uppercase tracking-widest pb-3 mb-3 border-b border-zinc-100 dark:border-zinc-800">
                    Ranking de Cobertura por Municipio
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 xl:grid-cols-9 gap-3">
                    @foreach ($municipiosStats as $m)
                        @php
                            $isActive = $selectedMunicipioId == $m['id'];
                            $isAnyActive = !is_null($selectedMunicipioId);
                        @endphp
                        <div wire:click="selectMunicipio({{ $m['id'] }})"
                            class="flex flex-col gap-2 p-3 rounded-2xl border cursor-pointer transition-all duration-300 group
                                    {{ $isActive
                                        ? 'border-lime-500/60 shadow-lg scale-[1.04] bg-gradient-to-br from-zinc-50 to-zinc-100 dark:from-zinc-800 dark:to-zinc-900'
                                        : 'border-transparent bg-zinc-50/50 dark:bg-zinc-900/30 hover:bg-zinc-100/70 dark:hover:bg-zinc-800/50 hover:scale-[1.02]' }}
                                    {{ $isAnyActive && !$isActive ? 'opacity-40 hover:opacity-100' : 'opacity-100' }}">
                            <div class="flex items-center gap-1.5 min-w-0">
                                <span class="w-2 h-2 rounded-full flex-shrink-0"
                                    style="background-color: {{ $m['color'] }}; box-shadow: 0 0 6px {{ $m['color'] }}60;"></span>
                                <span
                                    class="text-[10px] font-black text-zinc-700 dark:text-zinc-200 truncate leading-tight">{{ $m['nombre'] }}</span>
                            </div>
                            <span class="text-lg font-black tabular-nums leading-none text-zinc-800 dark:text-zinc-100">
                                {{ number_format($m['total_atendidos'], 0, ',', '.') }}
                            </span>
                            <div class="w-full h-1 bg-zinc-200 dark:bg-zinc-700 rounded-full overflow-hidden">
                                <div class="h-full rounded-full transition-all duration-500"
                                    style="width: {{ $m['porcentaje'] }}%; background-color: {{ $m['color'] }};">
                                </div>
                            </div>
                            <span
                                class="text-[9px] font-bold text-zinc-400 tabular-nums">{{ $m['porcentaje'] }}%</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>

    {{-- Script de Gráficas Unificado --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    @script
        <script>
            let dashboardCharts = {};

            const initAllCharts = () => {
                // Destruir instancias previas
                ['monthlyChart', 'distributionChart'].forEach(id => {
                    const existing = Chart.getChart(id);
                    if (existing) existing.destroy();
                });

                const isDark = document.documentElement.classList.contains('dark');
                const textColor = isDark ? '#a1a1aa' : '#71717a';
                const gridColor = isDark ? 'rgba(255, 255, 255, 0.05)' : 'rgba(0, 0, 0, 0.04)';

                Chart.defaults.color = textColor;
                Chart.defaults.font.family = "'Inter', system-ui, sans-serif";

                // Obtener datos inyectados por PHP
                const monthlyData = @json($graphMonthly);
                const distributionData = @json($graphDistribution);

                //  CHARTS: MODO PREMIUM (Con gradientes y efectos neón estilo Ethereal)
                const ctxM = document.getElementById('monthlyChart');
                if (ctxM && monthlyData.length) {
                    const canvasCtx = ctxM.getContext('2d');

                    // Creación de gradiente de neón (Púrpura a Rosa Translúcido)
                    const gradientPurple = canvasCtx.createLinearGradient(0, 0, 0, 240);
                    gradientPurple.addColorStop(0, 'rgba(139, 92, 246, 0.85)'); // Púrpura neón
                    gradientPurple.addColorStop(0.5, 'rgba(139, 92, 246, 0.4)');
                    gradientPurple.addColorStop(1, 'rgba(236, 72, 153, 0.05)'); // Rosa suave translúcido

                    const currentMonthIndex = new Date().getMonth();
                    const barColors = monthlyData.map((_, index) => {
                        return index === currentMonthIndex ?
                            (isDark ? '#a78bfa' : '#8b5cf6') // Destacar el actual más vibrante
                            :
                            gradientPurple;
                    });

                    dashboardCharts.monthly = new Chart(ctxM, {
                        type: 'bar',
                        data: {
                            labels: monthlyData.map(d => d.mes),
                            datasets: [{
                                label: 'Atendidos',
                                data: monthlyData.map(d => d.total),
                                backgroundColor: barColors,
                                borderRadius: 10,
                                borderSkipped: false
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    grid: {
                                        color: gridColor,
                                        drawBorder: false
                                    },
                                    border: {
                                        display: false
                                    },
                                    ticks: {
                                        font: {
                                            size: 10,
                                            weight: '600'
                                        }
                                    }
                                },
                                x: {
                                    grid: {
                                        display: false
                                    },
                                    border: {
                                        display: false
                                    },
                                    ticks: {
                                        font: {
                                            size: 10,
                                            weight: '600'
                                        }
                                    }
                                }
                            },
                            plugins: {
                                legend: {
                                    display: false
                                },
                                tooltip: {
                                    backgroundColor: isDark ? '#18181b' : '#ffffff',
                                    titleColor: isDark ? '#ffffff' : '#000000',
                                    bodyColor: isDark ? '#e4e4e7' : '#27272a',
                                    titleFont: {
                                        family: 'Inter',
                                        weight: 'bold'
                                    },
                                    bodyFont: {
                                        family: 'Inter',
                                        weight: '600'
                                    },
                                    borderColor: isDark ? 'rgba(255, 255, 255, 0.08)' : 'rgba(0, 0, 0, 0.08)',
                                    borderWidth: 1,
                                    padding: 12,
                                    displayColors: false,
                                    cornerRadius: 12,
                                    shadowColor: 'rgba(0, 0, 0, 0.25)',
                                    callbacks: {
                                        label: function(context) {
                                            let value = context.raw || 0;
                                            return '👤 ' + value.toLocaleString('es-ES') + ' Atendidos';
                                        }
                                    }
                                }
                            }
                        }
                    });
                }

                const ctxD = document.getElementById('distributionChart');
                if (ctxD && distributionData.length) {
                    dashboardCharts.distribution = new Chart(ctxD, {
                        type: 'doughnut',
                        data: {
                            labels: distributionData.map(d => d.nombre),
                            datasets: [{
                                data: distributionData.map(d => d.total),
                                backgroundColor: distributionData.map(d => d.color),
                                borderWidth: isDark ? 6 : 4,
                                borderColor: isDark ? '#0c0f14' : '#ffffff',
                                hoverOffset: 12
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            cutout: '80%',
                            plugins: {
                                legend: {
                                    display: false
                                },
                                tooltip: {
                                    backgroundColor: isDark ? '#18181b' : '#ffffff',
                                    titleColor: isDark ? '#ffffff' : '#000000',
                                    bodyColor: isDark ? '#e4e4e7' : '#27272a',
                                    titleFont: {
                                        family: 'Inter',
                                        weight: 'bold'
                                    },
                                    bodyFont: {
                                        family: 'Inter',
                                        weight: '600'
                                    },
                                    borderColor: isDark ? 'rgba(255, 255, 255, 0.08)' : 'rgba(0, 0, 0, 0.08)',
                                    borderWidth: 1,
                                    padding: 12,
                                    cornerRadius: 12
                                }
                            }
                        }
                    });
                }
            };

            // Escuchar refrescos de Livewire
            $wire.on('refreshDashboardCharts', () => {
                setTimeout(() => {
                    initAllCharts();
                    syncMapSelection();
                }, 100);
            });

            // Sincronizar selección del mapa
            const syncMapSelection = () => {
                const activeId = @js($selectedMunicipioId);
                document.querySelectorAll('.municipio-group').forEach(el => {
                    const mId = el.getAttribute('data-id');
                    if (activeId && mId == activeId) {
                        el.classList.add('active-muni');
                        el.style.opacity = '1';
                    } else if (activeId) {
                        el.classList.remove('active-muni');
                        el.style.opacity = '0.35'; // Dim inactivos
                    } else {
                        el.classList.remove('active-muni');
                        el.style.opacity = '1'; // Opacidad normal
                    }
                });
            };

            // Delegación global para el clic en los municipios
            document.addEventListener('click', (e) => {
                const group = e.target.closest('.municipio-group');
                if (group) {
                    const id = group.getAttribute('data-id');
                    $wire.selectMunicipio(id);
                }
            });

            // Inicialización en la carga inicial
            setTimeout(() => {
                if (typeof Chart !== 'undefined') {
                    initAllCharts();
                } else {
                    const s = document.querySelector('script[src*="chart.js"]');
                    if (s) s.addEventListener('load', initAllCharts);
                }
                syncMapSelection();
            }, 200);
        </script>
    @endscript
</div>

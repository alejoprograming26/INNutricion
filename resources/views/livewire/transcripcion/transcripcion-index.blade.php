<div>
    {{-- ═══════════════════════════════════════════════════
         TABS de navegación por Tipo
    ═══════════════════════════════════════════════════ --}}
    <div class="mb-6">
        <div class="flex flex-wrap gap-2 border-b border-zinc-200 dark:border-zinc-700 pb-0">
            @foreach ($tipoLabels as $clave => $etiqueta)
                @php
                    $esActivo = $tipoActivo === $clave;
                    $colores = match ($clave) {
                        'VULNERABILIDAD' => [
                            'tab' => 'border-rose-500 text-rose-600 dark:text-rose-400',
                            'badge' => 'bg-rose-100 text-rose-700 dark:bg-rose-900/40 dark:text-rose-300',
                        ],
                        'CPLV' => [
                            'tab' => 'border-blue-500 text-blue-600 dark:text-blue-400',
                            'badge' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300',
                        ],
                        'LACTANCIA MATERNA' => [
                            'tab' => 'border-pink-500 text-pink-600 dark:text-pink-400',
                            'badge' => 'bg-pink-100 text-pink-700 dark:bg-pink-900/40 dark:text-pink-300',
                        ],
                        'ENCUESTA DIETARIA' => [
                            'tab' => 'border-amber-500 text-amber-600 dark:text-amber-400',
                            'badge' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300',
                        ],
                        'MONITOREO DE PRECIO' => [
                            'tab' => 'border-violet-500 text-violet-600 dark:text-violet-400',
                            'badge' => 'bg-violet-100 text-violet-700 dark:bg-violet-900/40 dark:text-violet-300',
                        ],
                        'SUGIMA' => [
                            'tab' => 'border-lime-500 text-lime-600 dark:text-lime-400',
                            'badge' => 'bg-lime-100 text-lime-700 dark:bg-lime-900/40 dark:text-lime-300',
                        ],
                        'PERINATAL' => [
                            'tab' => 'border-indigo-500 text-indigo-600 dark:text-indigo-400',
                            'badge' => 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300',
                        ],
                        'PRIMER NIVEL DE ATENCION' => [
                            'tab' => 'border-cyan-500 text-cyan-600 dark:text-cyan-400',
                            'badge' => 'bg-cyan-100 text-cyan-700 dark:bg-cyan-900/40 dark:text-cyan-300',
                        ],
                        'DESNUTRICION GRAVE' => [
                            'tab' => 'border-red-600 text-red-700 dark:text-red-500',
                            'badge' => 'bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-300',
                        ],
                        'CONSULTA' => [
                            'tab' => 'border-emerald-500 text-emerald-600 dark:text-emerald-400',
                            'badge' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300',
                        ],
                        default => ['tab' => 'border-zinc-500 text-zinc-600', 'badge' => 'bg-zinc-100 text-zinc-700'],
                    };
                @endphp
                <a href="{{ route('admin.transcripciones.index', ['tipo' => $clave]) }}"
                    class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold border-b-2 transition-all duration-150 -mb-px select-none
                          {{ $esActivo
                              ? $colores['tab'] . ' bg-white dark:bg-zinc-900'
                              : 'border-transparent text-zinc-500 hover:text-zinc-700 dark:text-zinc-400 dark:hover:text-zinc-200 hover:border-zinc-300 bg-transparent' }}">
                    {{ $etiqueta }}
                </a>
            @endforeach
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════
         Header
    ═══════════════════════════════════════════════════ --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            @php
                $coloresTitulo = match ($tipoActivo) {
                    'VULNERABILIDAD' => 'text-rose-600 dark:text-rose-400',
                    'CPLV' => 'text-blue-600 dark:text-blue-400',
                    'LACTANCIA MATERNA' => 'text-pink-600 dark:text-pink-400',
                    'ENCUESTA DIETARIA' => 'text-amber-600 dark:text-amber-400',
                    'MONITOREO DE PRECIO' => 'text-violet-600 dark:text-violet-400',
                    'SUGIMA' => 'text-lime-600 dark:text-lime-400',
                    'PERINATAL' => 'text-indigo-600 dark:text-indigo-400',
                    'PRIMER NIVEL DE ATENCION' => 'text-cyan-600 dark:text-cyan-400',
                    'DESNUTRICION GRAVE' => 'text-red-600 dark:text-red-400',
                    'CONSULTA' => 'text-emerald-600 dark:text-emerald-400',
                    default => 'text-zinc-700',
                };
            @endphp
            <flux:heading size="xl" level="1" class="{{ $coloresTitulo }}">
                Transcripciones — {{ $tipoLabels[$tipoActivo] ?? $tipoActivo }}
            </flux:heading>
            <flux:subheading size="lg">
                Registros de tipo <strong>{{ $tipoLabels[$tipoActivo] ?? $tipoActivo }}</strong>: crear, editar y
                eliminar.
            </flux:subheading>
        </div>
        <div>
            <flux:button wire:click="create" icon="plus"
                class="!bg-lime-500 !text-zinc-900 border-none hover:!bg-lime-400 font-bold">
                Nueva Transcripción
            </flux:button>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════
         Cards de Resumen
    ═══════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        {{-- Total Anual --}}
        <div class="bg-gradient-to-br from-sky-400 via-blue-500 to-indigo-600 rounded-2xl p-6 shadow-lg shadow-indigo-500/20 relative overflow-hidden group hover:-translate-y-1 transition-all duration-300">
            <div class="absolute -right-6 -top-6 w-32 h-32 bg-white/10 rounded-full blur-2xl transition-transform duration-500 group-hover:scale-150"></div>
            <div class="flex justify-between items-start relative z-10">
                <div>
                    <p class="text-xs font-bold text-sky-50 uppercase tracking-widest mb-1 opacity-90">Total Anual</p>
                    <h3 class="text-3xl font-black text-white tabular-nums drop-shadow-sm">{{ number_format($totalAnual) }}</h3>
                </div>
                <div class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center border border-white/30 shadow-inner">
                    <flux:icon.calendar-days class="w-5 h-5 text-white drop-shadow-sm" />
                </div>
            </div>
        </div>

        {{-- Total Mes --}}
        <div class="bg-gradient-to-br from-teal-400 via-emerald-500 to-emerald-600 rounded-2xl p-6 shadow-lg shadow-emerald-500/20 relative overflow-hidden group hover:-translate-y-1 transition-all duration-300">
            <div class="absolute -right-6 -top-6 w-32 h-32 bg-white/10 rounded-full blur-2xl transition-transform duration-500 group-hover:scale-150"></div>
            <div class="flex justify-between items-start relative z-10">
                <div>
                    <p class="text-xs font-bold text-emerald-50 uppercase tracking-widest mb-1 opacity-90">Total Mes</p>
                    <h3 class="text-3xl font-black text-white tabular-nums drop-shadow-sm">{{ number_format($totalMes) }}</h3>
                </div>
                <div class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center border border-white/30 shadow-inner">
                    <flux:icon.chart-pie class="w-5 h-5 text-white drop-shadow-sm" />
                </div>
            </div>
        </div>

        {{-- Total Semana --}}
        <div class="bg-gradient-to-br from-violet-400 via-purple-500 to-fuchsia-600 rounded-2xl p-6 shadow-lg shadow-fuchsia-500/20 relative overflow-hidden group hover:-translate-y-1 transition-all duration-300">
            <div class="absolute -right-6 -top-6 w-32 h-32 bg-white/10 rounded-full blur-2xl transition-transform duration-500 group-hover:scale-150"></div>
            <div class="flex justify-between items-start relative z-10">
                <div>
                    <p class="text-xs font-bold text-fuchsia-50 uppercase tracking-widest mb-1 opacity-90">Total Semana</p>
                    <h3 class="text-3xl font-black text-white tabular-nums drop-shadow-sm">{{ number_format($totalSemana) }}</h3>
                </div>
                <div class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center border border-white/30 shadow-inner">
                    <flux:icon.bolt class="w-5 h-5 text-white drop-shadow-sm" />
                </div>
            </div>
        </div>

        {{-- Registros Mes --}}
        <div class="bg-gradient-to-br from-rose-400 via-pink-500 to-red-600 rounded-2xl p-6 shadow-lg shadow-red-500/20 relative overflow-hidden group hover:-translate-y-1 transition-all duration-300">
            <div class="absolute -right-6 -top-6 w-32 h-32 bg-white/10 rounded-full blur-2xl transition-transform duration-500 group-hover:scale-150"></div>
            <div class="flex justify-between items-start relative z-10">
                <div>
                    <p class="text-xs font-bold text-rose-50 uppercase tracking-widest mb-1 opacity-90">Registros (Mes)</p>
                    <h3 class="text-3xl font-black text-white tabular-nums drop-shadow-sm">{{ number_format($transcripcionesMes) }}</h3>
                </div>
                <div class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center border border-white/30 shadow-inner">
                    <flux:icon.document-check class="w-5 h-5 text-white drop-shadow-sm" />
                </div>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════
         Tabla
    ═══════════════════════════════════════════════════ --}}
    <flux:card class="shadow-sm mb-6">
        <div
            class="mb-4 flex flex-col lg:flex-row gap-4 items-center justify-between bg-zinc-50 dark:bg-zinc-800/50 p-3 rounded-lg border border-zinc-200 dark:border-zinc-700">
            <div class="w-full lg:w-1/3">
                <flux:input wire:model.live.debounce.300ms="search" icon="magnifying-glass"
                    placeholder="Buscar registro..." />
            </div>

            <div class="flex flex-wrap items-center gap-3 w-full lg:w-auto">
                <div class="flex items-center gap-2">
                    <span class="text-sm font-medium text-zinc-500">Desde:</span>
                    <flux:input type="date" wire:model.live="dateFrom" class="w-36" />
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-sm font-medium text-zinc-500">Hasta:</span>
                    <flux:input type="date" wire:model.live="dateTo" class="w-36" />
                </div>
                <div class="h-6 w-px bg-zinc-300 dark:bg-zinc-600 hidden sm:block"></div>

                @if ($search !== '' || $dateFrom !== '' || $dateTo !== '')
                    <flux:button wire:click="clearFilters" size="sm" variant="danger" icon="x-mark">
                        Limpiar
                    </flux:button>
                @endif

                <flux:button wire:click="toggleSort" size="sm" variant="subtle"
                    icon="{{ $sortDirection === 'desc' ? 'bars-arrow-down' : 'bars-arrow-up' }}">
                    {{ $sortDirection === 'desc' ? 'Recientes' : 'Antiguos' }}
                </flux:button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-zinc-600 dark:text-zinc-400">
                <thead class="bg-white dark:bg-zinc-900 text-xs uppercase font-semibold text-zinc-500 border-b border-zinc-200 dark:border-zinc-800">
                    <tr>
                        <th class="px-6 py-4 w-12 text-center">#</th>
                        <th class="px-6 py-4">Información del Registro</th>
                        <th class="px-6 py-4">Ubicación Geográfica</th>
                        <th class="px-6 py-4 text-center">Cantidades</th>
                        <th class="px-6 py-4 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800/50 bg-white dark:bg-zinc-900">
                    @forelse($transcripciones as $t)
                        <tr class="hover:bg-zinc-50/50 dark:hover:bg-zinc-800/50 transition-colors group">
                            <td class="px-6 py-4 text-center font-medium text-zinc-400">
                                {{ ($transcripciones->currentPage() - 1) * $transcripciones->perPage() + $loop->iteration }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-blue-50 dark:bg-blue-500/10 flex items-center justify-center shrink-0 border border-blue-100 dark:border-blue-500/20 text-blue-600 dark:text-blue-400">
                                        <flux:icon.document-text class="w-5 h-5" />
                                    </div>
                                    <div>
                                        <p class="font-bold text-zinc-800 dark:text-zinc-100 line-clamp-1" title="{{ $t->observacion }}">
                                            {{ $t->observacion ?? 'Registro de Transcripción' }}
                                        </p>
                                        <p class="text-xs text-zinc-500 dark:text-zinc-400 font-medium mt-0.5">
                                            Por <span class="text-zinc-700 dark:text-zinc-300">{{ $t->responsable }}</span> &bull; {{ \Carbon\Carbon::parse($t->fecha)->format('d \d\e F, Y') }}
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col gap-1.5">
                                    <div class="flex items-center gap-2">
                                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                        <span class="font-medium text-zinc-800 dark:text-zinc-200">{{ $t->municipio->nombre }}</span>
                                        <span class="text-zinc-400 text-xs">&bull;</span>
                                        <span class="text-zinc-600 dark:text-zinc-400 text-xs">{{ $t->parroquia->nombre }}</span>
                                    </div>
                                    <div class="flex flex-wrap items-center gap-2 mt-1.5">
                                        <div class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md bg-amber-50 dark:bg-amber-500/10 text-amber-700 dark:text-amber-400 border border-amber-200/60 dark:border-amber-500/20 text-[10px] font-bold uppercase tracking-wider shadow-sm">
                                            <flux:icon.building-office-2 class="w-3.5 h-3.5" />
                                            <span>{{ $t->comuna->nombre }}</span>
                                        </div>
                                        <div class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md bg-sky-50 dark:bg-sky-500/10 text-sky-700 dark:text-sky-400 border border-sky-200/60 dark:border-sky-500/20 text-[10px] font-bold uppercase tracking-wider shadow-sm">
                                            <flux:icon.map-pin class="w-3.5 h-3.5" />
                                            <span>{{ $t->sector->nombre }}</span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col items-center justify-center gap-1.5">
                                    @if ($esSugima)
                                        <div class="inline-flex items-center justify-center px-3 py-1 rounded-full bg-emerald-50 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-300 font-black border border-emerald-100 dark:border-emerald-500/20 text-xs w-full max-w-[110px]" title="Ingreso">
                                            <flux:icon.arrow-down-right class="w-3 h-3 mr-1" /> {{ $t->ingreso !== null ? number_format($t->ingreso) : '—' }}
                                        </div>
                                        <div class="inline-flex items-center justify-center px-3 py-1 rounded-full bg-rose-50 dark:bg-rose-500/10 text-rose-700 dark:text-rose-300 font-black border border-rose-100 dark:border-rose-500/20 text-xs w-full max-w-[110px]" title="Egreso">
                                            <flux:icon.arrow-up-right class="w-3 h-3 mr-1" /> {{ $t->egreso !== null ? number_format($t->egreso) : '—' }}
                                        </div>
                                    @else
                                        <div class="inline-flex items-center justify-center px-3 py-1 rounded-full bg-lime-50 dark:bg-lime-500/10 text-lime-700 dark:text-lime-300 font-black border border-lime-100 dark:border-lime-500/20">
                                            {{ number_format($t->cantidad) }}
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-1">
                                    <flux:button wire:click="show({{ $t->id }})" size="sm" variant="ghost" icon="eye" class="text-zinc-500 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-500/10" />
                                    <flux:button wire:click="edit({{ $t->id }})" size="sm" variant="ghost" icon="pencil-square" class="text-zinc-500 hover:text-amber-600 dark:hover:text-amber-400 hover:bg-amber-50 dark:hover:bg-amber-500/10" />
                                    <flux:button @click="confirmAction($wire, {{ $t->id }}, 'delete', '¿Eliminar transcripción?', 'Esta acción no se puede deshacer.', 'warning', 'Sí, eliminar')" size="sm" variant="ghost" icon="trash" class="text-zinc-500 hover:text-red-600 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-500/10" />
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12">
                                <div class="flex flex-col items-center justify-center text-center">
                                    <div class="w-16 h-16 rounded-full bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center mb-4">
                                        <flux:icon.inbox class="w-8 h-8 text-zinc-400" />
                                    </div>
                                    <h3 class="text-sm font-bold text-zinc-800 dark:text-zinc-200">No hay transcripciones registradas</h3>
                                    <p class="text-xs text-zinc-500 mt-1 max-w-sm">No se encontraron transcripciones de tipo <strong>{{ $tipoLabels[$tipoActivo] ?? $tipoActivo }}</strong>.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $transcripciones->links() }}
        </div>
    </flux:card>

    {{-- ═══════════════════════════════════════════════════
         Resumen Estadístico por Municipio
    ═══════════════════════════════════════════════════ --}}
    <flux:card class="shadow-sm mb-6">
        <div class="mb-4">
            <h2 class="text-lg font-bold text-zinc-800 dark:text-zinc-100 uppercase tracking-wide">Relación por
                Municipio</h2>
            <p class="text-sm text-zinc-500">Resumen de la actividad en cada municipio para el tipo <span
                    class="font-bold text-zinc-700 dark:text-zinc-300">{{ $tipoLabels[$tipoActivo] ?? $tipoActivo }}</span>
            </p>
        </div>
        <div class="overflow-x-auto rounded-lg border border-zinc-200 dark:border-zinc-700">
            <table class="w-full text-sm text-left text-zinc-600 dark:text-zinc-400">
                <thead
                    class="bg-zinc-50 dark:bg-zinc-800/50 text-xs uppercase font-semibold text-zinc-700 dark:text-zinc-300 border-b border-zinc-200 dark:border-zinc-700">
                    <tr class="text-center">
                        <th class="px-3 py-3 text-left">Municipio</th>
                        <th class="px-3 py-3">Total Anual</th>
                        <th class="px-3 py-3">Total Mes</th>
                        <th class="px-3 py-3">Última Semana</th>
                        <th class="px-3 py-3">Trancripciones (Mes)</th>
                        <th class="px-3 py-3 text-center">Reporte</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                    @forelse($municipiosConTotales as $m)
                        <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800 transition-colors text-center">
                            <td class="px-3 py-3 text-left font-semibold text-zinc-800 dark:text-zinc-100">
                                {{ $m->nombre }}
                            </td>
                            <td class="px-3 py-3">
                                <span
                                    class="bg-sky-100 text-sky-700 dark:bg-sky-900/40 dark:text-sky-300 px-2 py-1 rounded text-xs font-bold">
                                    {{ number_format($m->total_anual ?? 0) }}
                                </span>
                            </td>
                            <td class="px-3 py-3">
                                <span
                                    class="bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300 px-2 py-1 rounded text-xs font-bold">
                                    {{ number_format($m->total_mes ?? 0) }}
                                </span>
                            </td>
                            <td class="px-3 py-3">
                                <span
                                    class="bg-violet-100 text-violet-700 dark:bg-violet-900/40 dark:text-violet-300 px-2 py-1 rounded text-xs font-bold">
                                    {{ number_format($m->total_semana ?? 0) }}
                                </span>
                            </td>
                            <td class="px-3 py-3">
                                <span
                                    class="bg-rose-100 text-rose-700 dark:bg-rose-900/40 dark:text-rose-300 px-2 py-1 rounded text-xs font-bold">
                                    {{ number_format($m->transcripciones_mes_count ?? 0) }}
                                </span>
                            </td>
                            <td class="px-3 py-3">
                                <div class="flex items-center justify-center gap-2">
                                    <flux:button wire:click="openReportModal({{ $m->id }}, 'grafico')"
                                        size="sm" icon="chart-bar"
                                        class="!bg-violet-600 !text-white border-none hover:!bg-violet-700 font-bold"
                                        title="Ver Gráficas para {{ $m->nombre }}">
                                        Gráficas
                                    </flux:button>
                                    <flux:button wire:click="openReportModal({{ $m->id }}, 'pdf')"
                                        size="sm" icon="document-text"
                                        class="!bg-red-600 !text-white border-none hover:!bg-red-700 font-semibold"
                                        title="Descargar PDF para {{ $m->nombre }}">
                                        PDF
                                    </flux:button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-zinc-500">No hay municipios
                                registrados en el sistema.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </flux:card>

    {{-- ═══════════════════════════════════════════════════
         Modal Crear / Editar
    ═══════════════════════════════════════════════════ --}}
    @if ($isModalOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4"
            wire:key="modal-{{ $transcripcion_id ?? 'new' }}">
            <div class="bg-white dark:bg-zinc-900 w-full max-w-5xl rounded-xl shadow-xl flex flex-col max-h-[90vh]">

                {{-- Header --}}
                <div
                    class="flex items-center justify-between px-6 py-4 border-b border-zinc-200 dark:border-zinc-700 shrink-0">
                    <div>
                        <h2 class="text-lg font-bold text-zinc-800 dark:text-zinc-100">
                            {{ $transcripcion_id ? 'Editar Transcripción' : 'Nueva Transcripción' }}
                        </h2>
                        <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">
                            Tipo: <span
                                class="font-semibold text-lime-600 dark:text-lime-400">{{ $tipoLabels[$tipo] ?? $tipo }}</span>
                        </p>
                    </div>
                    <flux:button wire:click="closeModal" variant="ghost" icon="x-mark" />
                </div>

                {{-- Body (Scrollable) --}}
                <div class="p-6 overflow-y-auto">
                    <form wire:submit="store" id="transcripcionForm">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

                            {{-- Fila 1 --}}
                            <div>
                                <flux:input wire:model="observacion" label="Observación" placeholder="Opcional..." />
                                @error('observacion')
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <flux:input wire:model="responsable" label="Responsable *" placeholder="Responsable"
                                    required />
                                @error('responsable')
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <flux:input wire:model="fecha" type="date" label="Fecha *" required />
                                @error('fecha')
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <flux:input value="{{ $tipoLabels[$tipo] ?? $tipo }}" label="Tipo *" disabled />
                                <!-- Se usa field oculto para que Livewire rastree el dato si fuera necesario, aunque ya está en memoria -->
                                <input type="hidden" wire:model="tipo">
                            </div>

                            {{-- Fila 2 --}}
                            <div>
                                <flux:select wire:model.live="municipio_id" label="Municipio *"
                                    placeholder="Selecciona..." required wire:key="select-municipio">
                                    @foreach ($municipios as $municipio)
                                        <flux:select.option value="{{ $municipio->id }}"
                                            wire:key="mun-{{ $municipio->id }}">
                                            {{ $municipio->nombre }}
                                        </flux:select.option>
                                    @endforeach
                                </flux:select>
                                @error('municipio_id')
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <flux:select wire:model.live="parroquia_id" label="Parroquia *"
                                    placeholder="{{ $municipio_id ? 'Selecciona...' : '— Primero municipio —' }}"
                                    :disabled="!$municipio_id" required
                                    wire:key="select-parroquia-{{ $municipio_id ?: 'none' }}">
                                    @foreach ($parroquiasFiltradas as $parroquia)
                                        <flux:select.option value="{{ $parroquia->id }}"
                                            wire:key="par-{{ $parroquia->id }}">
                                            {{ $parroquia->nombre }}
                                        </flux:select.option>
                                    @endforeach
                                </flux:select>
                                @error('parroquia_id')
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <flux:select wire:model.live="comuna_id" label="Comuna *"
                                    placeholder="{{ $parroquia_id ? 'Selecciona...' : '— Primero parroquia —' }}"
                                    :disabled="!$parroquia_id" required
                                    wire:key="select-comuna-{{ $parroquia_id ?: 'none' }}">
                                    @foreach ($comunasFiltradas as $comuna)
                                        <flux:select.option value="{{ $comuna->id }}"
                                            wire:key="com-{{ $comuna->id }}">
                                            {{ $comuna->nombre }}
                                        </flux:select.option>
                                    @endforeach
                                </flux:select>
                                @error('comuna_id')
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <flux:select wire:model="sector_id" label="Sector *"
                                    placeholder="{{ $comuna_id ? 'Selecciona...' : '— Primero comuna —' }}"
                                    :disabled="!$comuna_id" required
                                    wire:key="select-sector-{{ $comuna_id ?: 'none' }}">
                                    @foreach ($sectoresFiltrados as $sector)
                                        <flux:select.option value="{{ $sector->id }}"
                                            wire:key="sec-{{ $sector->id }}">
                                            {{ $sector->nombre }}
                                        </flux:select.option>
                                    @endforeach
                                </flux:select>
                                @error('sector_id')
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Fila 3 --}}
                            <div>
                                <flux:input wire:model="cantidad" type="number" min="0" label="Cantidad *"
                                    placeholder="0" required />
                                @error('cantidad')
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            @if ($tipo === 'SUGIMA')
                                <div>
                                    <flux:input wire:model="ingreso" type="number" min="0" label="Ingreso *"
                                        placeholder="0" required />
                                    @error('ingreso')
                                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <flux:input wire:model="egreso" type="number" min="0" label="Egreso *"
                                        placeholder="0" required />
                                    @error('egreso')
                                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            @endif

                        </div>
                    </form>
                </div>

                {{-- Footer (Fixed at bottom) --}}
                <div
                    class="px-6 py-4 border-t border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800/50 shrink-0 flex justify-end gap-3 rounded-b-xl">
                    <flux:button wire:click="closeModal" variant="ghost">Cancelar</flux:button>
                    <flux:button type="submit" form="transcripcionForm"
                        class="!bg-lime-500 !text-zinc-900 border-none hover:!bg-lime-400 font-bold shadow-sm">
                        <span wire:loading.remove wire:target="store">Guardar</span>
                        <span wire:loading wire:target="store">Guardando...</span>
                    </flux:button>
                </div>

            </div>
        </div>
    @endif

    {{-- ═══════════════════════════════════════════════════
         Modal Ver
    ═══════════════════════════════════════════════════ --}}
    @if ($isViewModalOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
            <div
                class="bg-white dark:bg-zinc-900 w-full max-w-lg p-6 rounded-xl shadow-xl flex flex-col space-y-4 max-h-[90vh] overflow-y-auto">
                <div>
                    <flux:heading size="lg">Detalle de la Transcripción</flux:heading>
                    <flux:subheading>
                        <flux:badge size="sm" color="lime">{{ $view_tipo }}</flux:badge>
                    </flux:subheading>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <span
                            class="block text-xs font-semibold uppercase tracking-wide text-zinc-500 mb-0.5">Observación</span>
                        <span
                            class="block text-sm font-medium text-zinc-800 dark:text-zinc-100">{{ $view_observacion ?? '—' }}</span>
                    </div>
                    <div>
                        <span
                            class="block text-xs font-semibold uppercase tracking-wide text-zinc-500 mb-0.5">Responsable</span>
                        <span
                            class="block text-sm font-medium text-zinc-800 dark:text-zinc-100">{{ $view_responsable }}</span>
                    </div>
                    <div>
                        <span
                            class="block text-xs font-semibold uppercase tracking-wide text-zinc-500 mb-0.5">Fecha</span>
                        <span
                            class="block text-sm font-medium text-zinc-800 dark:text-zinc-100">{{ $view_fecha }}</span>
                    </div>
                    <div>
                        <span
                            class="block text-xs font-semibold uppercase tracking-wide text-zinc-500 mb-0.5">Municipio</span>
                        <span
                            class="block text-sm font-medium text-zinc-800 dark:text-zinc-100">{{ $view_municipio }}</span>
                    </div>
                    <div>
                        <span
                            class="block text-xs font-semibold uppercase tracking-wide text-zinc-500 mb-0.5">Parroquia</span>
                        <span
                            class="block text-sm font-medium text-zinc-800 dark:text-zinc-100">{{ $view_parroquia }}</span>
                    </div>
                    <div>
                        <span
                            class="block text-xs font-semibold uppercase tracking-wide text-zinc-500 mb-0.5">Comuna</span>
                        <span
                            class="block text-sm font-medium text-zinc-800 dark:text-zinc-100">{{ $view_comuna }}</span>
                    </div>
                    <div>
                        <span
                            class="block text-xs font-semibold uppercase tracking-wide text-zinc-500 mb-0.5">Sector</span>
                        <span
                            class="block text-sm font-medium text-zinc-800 dark:text-zinc-100">{{ $view_sector }}</span>
                    </div>
                    <div>
                        <span
                            class="block text-xs font-semibold uppercase tracking-wide text-zinc-500 mb-0.5">Cantidad</span>
                        <span
                            class="block text-sm font-semibold text-zinc-800 dark:text-zinc-100">{{ number_format((int) $view_cantidad) }}</span>
                    </div>
                    @if ($view_tipo === 'SUGIMA')
                        <div>
                            <span
                                class="block text-xs font-semibold uppercase tracking-wide text-zinc-500 mb-0.5">Ingreso</span>
                            <span class="block text-sm font-semibold text-green-600 dark:text-green-400">
                                {{ $view_ingreso !== null ? number_format((int) $view_ingreso) : '—' }}
                            </span>
                        </div>
                        <div>
                            <span
                                class="block text-xs font-semibold uppercase tracking-wide text-zinc-500 mb-0.5">Egreso</span>
                            <span class="block text-sm font-semibold text-red-600 dark:text-red-400">
                                {{ $view_egreso !== null ? number_format((int) $view_egreso) : '—' }}
                            </span>
                        </div>
                    @endif
                </div>

                <div class="flex justify-end pt-4 border-t border-zinc-200 dark:border-zinc-800">
                    <flux:button wire:click="closeModal" variant="ghost">Cerrar</flux:button>
                </div>
            </div>
        </div>
    @endif

    {{-- ═══════════════════════════════════════════════════
         Modal Descargar Reporte PDF
    ═══════════════════════════════════════════════════ --}}
    @if ($isReportModalOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
            <div class="bg-white dark:bg-zinc-900 w-full max-w-sm rounded-xl shadow-xl flex flex-col">
                {{-- Header --}}
                <div class="flex items-center justify-between px-6 py-4 border-b border-zinc-200 dark:border-zinc-700">
                    <h2 class="text-lg font-bold text-zinc-800 dark:text-zinc-100">
                        {{ $reportType === 'pdf' ? 'Generar Reporte PDF' : 'Ver Gráficas Estadísticas' }}
                    </h2>
                    <flux:button wire:click="closeReportModal" variant="ghost" icon="x-mark" />
                </div>

                {{-- Body --}}
                <div class="p-6">
                    <p class="text-sm text-zinc-600 dark:text-zinc-400 mb-4">
                        Selecciona el mes y el año para descargar el reporte de <span
                            class="font-bold">{{ $tipoLabels[$tipoActivo] ?? $tipoActivo }}</span> para el municipio
                        <span
                            class="font-bold text-emerald-600 dark:text-emerald-400">{{ $reportMunicipioNombre ?? 'Seleccionado' }}</span>.
                    </p>

                    <div class="space-y-4">
                        <flux:select wire:model.live="reportMonth" label="Mes del Reporte">
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

                        <flux:input wire:model.live="reportYear" type="number" step="1" min="2000"
                            max="2100" label="Año" required />
                    </div>
                </div>

                {{-- Footer --}}
                <div
                    class="px-6 py-4 border-t border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800/50 flex justify-end gap-3 rounded-b-xl">
                    <flux:button wire:click="closeReportModal" variant="ghost">Cancelar</flux:button>

                    @if ($reportType === 'pdf')
                        <flux:button wire:click="viewPdf" icon="arrow-down-tray" variant="primary"
                            class="!bg-red-600 hover:!bg-red-700">
                            Descargar PDF
                        </flux:button>
                    @else
                        <flux:button wire:click="viewDashboard" icon="chart-pie" variant="primary"
                            class="!bg-violet-600 !text-white hover:!bg-violet-700 font-bold">
                            Ver Gráficas
                        </flux:button>
                    @endif
                </div>
            </div>
        </div>
    @endif

    @script
        <script>
            $wire.on('open-url-in-new-tab', (event) => {
                if (event.url) {
                    window.open(event.url, '_blank');
                } else if (event[0] && event[0].url) {
                    window.open(event[0].url, '_blank');
                }
            });
        </script>
    @endscript
</div>

<div class="space-y-6">
    {{-- ═══════════════════════════════════════════════════
         Header & Acciones Principales
    ═══════════════════════════════════════════════════ --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white dark:bg-zinc-900 p-6 rounded-2xl border border-zinc-200 dark:border-zinc-800 shadow-sm relative overflow-hidden">
        <div class="absolute -right-10 -top-10 w-40 h-40 bg-lime-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -left-10 -bottom-10 w-40 h-40 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>
        
        <div class="flex items-center gap-4 relative z-10">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-lime-500 to-emerald-600 flex items-center justify-center shadow-lg shadow-lime-500/30">
                <flux:icon.clipboard-document-list class="w-8 h-8 text-white" />
            </div>
            <div>
                <h1 class="text-3xl font-black text-zinc-800 dark:text-zinc-100 tracking-tight">Abordajes</h1>
                <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1 font-medium">
                    Gestión integral y análisis geográfico de actividades de campo.
                </p>
            </div>
        </div>
        <div class="relative z-10">
            <flux:button wire:click="create" icon="plus" class="!bg-gradient-to-r !from-lime-600 !to-emerald-600 hover:!from-lime-500 hover:!to-emerald-500 !text-white border-none font-bold shadow-md shadow-lime-500/20 transition-all duration-300 transform hover:-translate-y-0.5">
                Registrar Abordaje
            </flux:button>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════
         KPI Cards - Premium Design
    ═══════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        {{-- Total Anual --}}
        <div class="bg-gradient-to-br from-lime-500 to-emerald-600 rounded-2xl p-6 shadow-lg shadow-emerald-500/20 relative overflow-hidden group hover:-translate-y-1 transition-all duration-300">
            <div class="absolute -right-6 -top-6 w-32 h-32 bg-white/10 rounded-full blur-2xl transition-transform duration-500 group-hover:scale-150"></div>
            <div class="flex justify-between items-start relative z-10">
                <div>
                    <p class="text-xs font-bold text-emerald-50 uppercase tracking-widest mb-1 opacity-90">Total Anual</p>
                    <h3 class="text-3xl font-black text-white tabular-nums drop-shadow-sm">{{ number_format($totalAnual) }}</h3>
                </div>
                <div class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center border border-white/30 shadow-inner">
                    <flux:icon.calendar-days class="w-5 h-5 text-white drop-shadow-sm" />
                </div>
            </div>
        </div>

        {{-- Total Mes --}}
        <div class="bg-gradient-to-br from-sky-400 to-blue-600 rounded-2xl p-6 shadow-lg shadow-blue-500/20 relative overflow-hidden group hover:-translate-y-1 transition-all duration-300">
            <div class="absolute -right-6 -top-6 w-32 h-32 bg-white/10 rounded-full blur-2xl transition-transform duration-500 group-hover:scale-150"></div>
            <div class="flex justify-between items-start relative z-10">
                <div>
                    <p class="text-xs font-bold text-blue-50 uppercase tracking-widest mb-1 opacity-90">Total Mes</p>
                    <h3 class="text-3xl font-black text-white tabular-nums drop-shadow-sm">{{ number_format($totalMes) }}</h3>
                </div>
                <div class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center border border-white/30 shadow-inner">
                    <flux:icon.chart-pie class="w-5 h-5 text-white drop-shadow-sm" />
                </div>
            </div>
        </div>

        {{-- Total Semana --}}
        <div class="bg-gradient-to-br from-amber-400 to-orange-500 rounded-2xl p-6 shadow-lg shadow-orange-500/20 relative overflow-hidden group hover:-translate-y-1 transition-all duration-300">
            <div class="absolute -right-6 -top-6 w-32 h-32 bg-white/10 rounded-full blur-2xl transition-transform duration-500 group-hover:scale-150"></div>
            <div class="flex justify-between items-start relative z-10">
                <div>
                    <p class="text-xs font-bold text-orange-50 uppercase tracking-widest mb-1 opacity-90">Total Semana</p>
                    <h3 class="text-3xl font-black text-white tabular-nums drop-shadow-sm">{{ number_format($totalSemana) }}</h3>
                </div>
                <div class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center border border-white/30 shadow-inner">
                    <flux:icon.bolt class="w-5 h-5 text-white drop-shadow-sm" />
                </div>
            </div>
        </div>

        {{-- Registros Mes --}}
        <div class="bg-gradient-to-br from-teal-400 to-emerald-500 rounded-2xl p-6 shadow-lg shadow-teal-500/20 relative overflow-hidden group hover:-translate-y-1 transition-all duration-300">
            <div class="absolute -right-6 -top-6 w-32 h-32 bg-white/10 rounded-full blur-2xl transition-transform duration-500 group-hover:scale-150"></div>
            <div class="flex justify-between items-start relative z-10">
                <div>
                    <p class="text-xs font-bold text-teal-50 uppercase tracking-widest mb-1 opacity-90">Registros (Mes)</p>
                    <h3 class="text-3xl font-black text-white tabular-nums drop-shadow-sm">{{ number_format($registrosMes) }}</h3>
                </div>
                <div class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center border border-white/30 shadow-inner">
                    <flux:icon.document-check class="w-5 h-5 text-white drop-shadow-sm" />
                </div>
            </div>
        </div>
    </div>


    {{-- ═══════════════════════════════════════════════════
         Filtros y Tabla Principal
    ═══════════════════════════════════════════════════ --}}
    <flux:card class="shadow-sm border-zinc-200/60 dark:border-zinc-800/60 !p-0 overflow-hidden">
        
        {{-- Toolbar de Filtros --}}
        <div class="bg-zinc-50/80 dark:bg-zinc-800/30 p-4 border-b border-zinc-200 dark:border-zinc-800 flex flex-col lg:flex-row gap-4 items-center justify-between">
            <div class="w-full lg:w-1/3">
                <flux:input wire:model.live.debounce.300ms="search" icon="magnifying-glass" placeholder="Buscar por observación, municipio, comuna..." class="bg-white dark:bg-zinc-900 shadow-sm" />
            </div>
            
            <div class="flex flex-wrap items-center gap-3 w-full lg:w-auto">
                <div class="flex items-center gap-2 bg-white dark:bg-zinc-900 p-1.5 rounded-lg border border-zinc-200 dark:border-zinc-700 shadow-sm">
                    <flux:input type="date" wire:model.live="dateFrom" class="w-36 !border-none !shadow-none !ring-0" />
                    <span class="text-zinc-400">—</span>
                    <flux:input type="date" wire:model.live="dateTo" class="w-36 !border-none !shadow-none !ring-0" />
                </div>
                
                @if($search !== '' || $dateFrom !== '' || $dateTo !== '')
                    <flux:button wire:click="clearFilters" size="sm" variant="danger" icon="x-mark">
                        Limpiar
                    </flux:button>
                @endif
                
                <flux:button wire:click="toggleSort" size="sm" variant="subtle" icon="{{ $sortDirection === 'desc' ? 'bars-arrow-down' : 'bars-arrow-up' }}" class="bg-white dark:bg-zinc-900 shadow-sm border-zinc-200 dark:border-zinc-700">
                    {{ $sortDirection === 'desc' ? 'Recientes' : 'Antiguos' }}
                </flux:button>
            </div>
        </div>

        {{-- Tabla --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-zinc-600 dark:text-zinc-400">
                <thead class="bg-white dark:bg-zinc-900 text-xs uppercase font-semibold text-zinc-500 border-b border-zinc-200 dark:border-zinc-800">
                    <tr>
                        <th class="px-6 py-4 w-12 text-center">#</th>
                        <th class="px-6 py-4">Información del Abordaje</th>
                        <th class="px-6 py-4">Ubicación Geográfica</th>
                        <th class="px-6 py-4 text-center">Cantidad</th>
                        <th class="px-6 py-4 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800/50 bg-white dark:bg-zinc-900">
                    @forelse($abordajes as $a)
                        <tr class="hover:bg-zinc-50/50 dark:hover:bg-zinc-800/50 transition-colors group">
                            <td class="px-6 py-4 text-center font-medium text-zinc-400">
                                {{ ($abordajes->currentPage() - 1) * $abordajes->perPage() + $loop->iteration }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-lime-50 dark:bg-lime-500/10 flex items-center justify-center shrink-0 border border-lime-100 dark:border-lime-500/20 text-lime-600 dark:text-lime-400">
                                        <flux:icon.calendar class="w-5 h-5" />
                                    </div>
                                    <div>
                                        <p class="font-bold text-zinc-800 dark:text-zinc-100 line-clamp-1" title="{{ $a->observacion }}">
                                            {{ $a->observacion ?? 'Abordaje Regular' }}
                                        </p>
                                        <p class="text-xs text-zinc-500 dark:text-zinc-400 font-medium mt-0.5">
                                            {{ \Carbon\Carbon::parse($a->fecha)->format('d \d\e F, Y') }}
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col gap-1.5">
                                    <div class="flex items-center gap-2">
                                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                        <span class="font-medium text-zinc-800 dark:text-zinc-200">{{ $a->municipio->nombre }}</span>
                                        <span class="text-zinc-400 text-xs">&bull;</span>
                                        <span class="text-zinc-600 dark:text-zinc-400 text-xs">{{ $a->parroquia->nombre }}</span>
                                    </div>
                                    <div class="flex flex-wrap items-center gap-2 mt-1.5">
                                        <div class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md bg-amber-50 dark:bg-amber-500/10 text-amber-700 dark:text-amber-400 border border-amber-200/60 dark:border-amber-500/20 text-[10px] font-bold uppercase tracking-wider shadow-sm">
                                            <flux:icon.building-office-2 class="w-3.5 h-3.5" />
                                            <span>{{ $a->comuna->nombre }}</span>
                                        </div>
                                        <div class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md bg-sky-50 dark:bg-sky-500/10 text-sky-700 dark:text-sky-400 border border-sky-200/60 dark:border-sky-500/20 text-[10px] font-bold uppercase tracking-wider shadow-sm">
                                            <flux:icon.map-pin class="w-3.5 h-3.5" />
                                            <span>{{ $a->sector->nombre }}</span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="inline-flex items-center justify-center px-3 py-1 rounded-full bg-lime-50 dark:bg-lime-500/10 text-lime-700 dark:text-lime-300 font-black border border-lime-100 dark:border-lime-500/20">
                                    {{ number_format($a->cantidad) }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-1">
                                    <flux:button wire:click="show({{ $a->id }})" size="sm" variant="ghost" icon="eye" class="text-zinc-500 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-500/10" />
                                    <flux:button wire:click="edit({{ $a->id }})" size="sm" variant="ghost" icon="pencil-square" class="text-zinc-500 hover:text-amber-600 dark:hover:text-amber-400 hover:bg-amber-50 dark:hover:bg-amber-500/10" />
                                    <flux:button @click="confirmAction($wire, {{ $a->id }}, 'delete', '¿Eliminar abordaje?', 'Esta acción no se puede deshacer.', 'warning', 'Sí, eliminar')" size="sm" variant="ghost" icon="trash" class="text-zinc-500 hover:text-red-600 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-500/10" />
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
                                    <h3 class="text-sm font-bold text-zinc-800 dark:text-zinc-200">No hay abordajes registrados</h3>
                                    <p class="text-xs text-zinc-500 mt-1 max-w-sm">No se encontraron resultados para los filtros actuales o aún no has registrado ningún abordaje.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($abordajes->hasPages())
            <div class="p-4 bg-white dark:bg-zinc-900 border-t border-zinc-200 dark:border-zinc-800">
                {{ $abordajes->links() }}
            </div>
        @endif
    </flux:card>

    {{-- ═══════════════════════════════════════════════════
         Resumen Estadístico por Municipio
    ═══════════════════════════════════════════════════ --}}
    <flux:card class="shadow-sm mb-6">
        <div class="mb-4">
            <h2 class="text-lg font-bold text-zinc-800 dark:text-zinc-100 uppercase tracking-wide">Relación por Municipio</h2>
            <p class="text-sm text-zinc-500">Resumen general de abordajes registrados en cada municipio.</p>
        </div>
        <div class="overflow-x-auto rounded-lg border border-zinc-200 dark:border-zinc-700">
            <table class="w-full text-sm text-left text-zinc-600 dark:text-zinc-400">
                <thead class="bg-zinc-50 dark:bg-zinc-800/50 text-xs uppercase font-semibold text-zinc-700 dark:text-zinc-300 border-b border-zinc-200 dark:border-zinc-700">
                    <tr class="text-center">
                        <th class="px-3 py-3 text-left">Municipio</th>
                        <th class="px-3 py-3">Total Anual</th>
                        <th class="px-3 py-3">Total Mes</th>
                        <th class="px-3 py-3">Última Semana</th>
                        <th class="px-3 py-3">Abordajes (Mes)</th>
                        <th class="px-3 py-3 text-center">Reportes</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                    @forelse($municipiosConTotales as $m)
                        <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800 transition-colors text-center">
                            <td class="px-3 py-3 text-left font-semibold text-zinc-800 dark:text-zinc-100">
                                {{ $m->nombre }}
                            </td>
                            <td class="px-3 py-3">
                                <span class="bg-sky-100 text-sky-700 dark:bg-sky-900/40 dark:text-sky-300 px-2 py-1 rounded text-xs font-bold">
                                    {{ number_format($m->total_anual ?? 0) }}
                                </span>
                            </td>
                            <td class="px-3 py-3">
                                <span class="bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300 px-2 py-1 rounded text-xs font-bold">
                                    {{ number_format($m->total_mes ?? 0) }}
                                </span>
                            </td>
                            <td class="px-3 py-3">
                                <span class="bg-violet-100 text-violet-700 dark:bg-violet-900/40 dark:text-violet-300 px-2 py-1 rounded text-xs font-bold">
                                    {{ number_format($m->total_semana ?? 0) }}
                                </span>
                            </td>
                            <td class="px-3 py-3">
                                <span class="bg-rose-100 text-rose-700 dark:bg-rose-900/40 dark:text-rose-300 px-2 py-1 rounded text-xs font-bold">
                                    {{ number_format($m->abordajes_mes_count ?? 0) }}
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
                            <td colspan="6" class="px-4 py-8 text-center text-zinc-500">No hay municipios registrados en el sistema.</td>
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
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-zinc-900/40 dark:bg-black/60 backdrop-blur-sm p-4" wire:key="modal-{{ $abordaje_id ?? 'new' }}">
            <div class="bg-white dark:bg-zinc-900 w-full max-w-4xl rounded-2xl shadow-2xl flex flex-col max-h-[90vh] border border-zinc-200 dark:border-zinc-800 overflow-hidden transform transition-all">
                
                {{-- Header con gradiente suave --}}
                <div class="relative px-6 py-5 border-b border-zinc-200 dark:border-zinc-800 shrink-0 overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-r from-lime-50 to-emerald-50 dark:from-lime-500/5 dark:to-emerald-500/5"></div>
                    <div class="relative flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-white dark:bg-zinc-800 flex items-center justify-center shadow-sm border border-zinc-200 dark:border-zinc-700">
                                <flux:icon.map class="w-5 h-5 text-lime-600 dark:text-lime-400" />
                            </div>
                            <div>
                                <h2 class="text-lg font-black text-zinc-800 dark:text-zinc-100">
                                    {{ $abordaje_id ? 'Editar Abordaje' : 'Registrar Nuevo Abordaje' }}
                                </h2>
                                <p class="text-xs text-zinc-500 font-medium">Complete los datos geográficos y la cantidad.</p>
                            </div>
                        </div>
                        <flux:button wire:click="closeModal" variant="ghost" icon="x-mark" class="rounded-full hover:bg-white dark:hover:bg-zinc-800" />
                    </div>
                </div>

                {{-- Body --}}
                <div class="p-6 overflow-y-auto bg-zinc-50/50 dark:bg-zinc-900/50">
                    <form wire:submit="store" id="abordajeForm" class="space-y-6">
                        
                        {{-- Datos Generales Card --}}
                        <div class="bg-white dark:bg-zinc-900 p-5 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm">
                            <h3 class="text-xs font-bold uppercase tracking-wider text-zinc-500 mb-4 flex items-center gap-2">
                                <flux:icon.document-text class="w-4 h-4" /> Datos Generales
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div>
                                    <flux:input wire:model="observacion" label="Observación" placeholder="Detalles adicionales (opcional)" />
                                </div>
                                <div>
                                    <flux:input wire:model="fecha" type="date" label="Fecha *" required />
                                </div>
                            </div>
                        </div>

                        {{-- Ubicación Card --}}
                        <div class="bg-white dark:bg-zinc-900 p-5 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm">
                            <h3 class="text-xs font-bold uppercase tracking-wider text-zinc-500 mb-4 flex items-center gap-2">
                                <flux:icon.map-pin class="w-4 h-4" /> Ubicación Geográfica
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div>
                                    <flux:select wire:model.live="municipio_id" label="Municipio *" placeholder="Selecciona municipio" required>
                                        @foreach ($municipios as $municipio)
                                            <flux:select.option value="{{ $municipio->id }}">{{ $municipio->nombre }}</flux:select.option>
                                        @endforeach
                                    </flux:select>
                                </div>
                                <div>
                                    <flux:select wire:model.live="parroquia_id" label="Parroquia *" placeholder="{{ $municipio_id ? 'Selecciona parroquia' : '— Primero municipio —' }}" :disabled="!$municipio_id" required>
                                        @foreach ($parroquiasFiltradas as $parroquia)
                                            <flux:select.option value="{{ $parroquia->id }}">{{ $parroquia->nombre }}</flux:select.option>
                                        @endforeach
                                    </flux:select>
                                </div>
                                <div>
                                    <flux:select wire:model.live="comuna_id" label="Comuna *" placeholder="{{ $parroquia_id ? 'Selecciona comuna' : '— Primero parroquia —' }}" :disabled="!$parroquia_id" required>
                                        @foreach ($comunasFiltradas as $comuna)
                                            <flux:select.option value="{{ $comuna->id }}">{{ $comuna->nombre }}</flux:select.option>
                                        @endforeach
                                    </flux:select>
                                </div>
                                <div>
                                    <flux:select wire:model="sector_id" label="Sector *" placeholder="{{ $comuna_id ? 'Selecciona sector' : '— Primero comuna —' }}" :disabled="!$comuna_id" required>
                                        @foreach ($sectoresFiltrados as $sector)
                                            <flux:select.option value="{{ $sector->id }}">{{ $sector->nombre }}</flux:select.option>
                                        @endforeach
                                    </flux:select>
                                </div>
                            </div>
                        </div>

                        {{-- Resultados Card --}}
                        <div class="bg-lime-50/50 dark:bg-lime-500/5 p-5 rounded-xl border border-lime-100 dark:border-lime-500/20 shadow-sm">
                            <h3 class="text-xs font-bold uppercase tracking-wider text-lime-600 dark:text-lime-400 mb-4 flex items-center gap-2">
                                <flux:icon.calculator class="w-4 h-4" /> Resultados
                            </h3>
                            <div class="w-full md:w-1/2">
                                <flux:input wire:model="cantidad" type="number" min="0" label="Cantidad Alcanzada *" placeholder="0" required class="text-lg font-bold" />
                            </div>
                        </div>

                    </form>
                </div>

                {{-- Footer --}}
                <div class="px-6 py-4 border-t border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 shrink-0 flex justify-end gap-3 rounded-b-2xl">
                    <flux:button wire:click="closeModal" variant="ghost" class="hover:bg-zinc-100 dark:hover:bg-zinc-800">Cancelar</flux:button>
                    <flux:button type="submit" form="abordajeForm" class="!bg-gradient-to-r !from-lime-600 !to-emerald-600 hover:!from-lime-500 hover:!to-emerald-500 !text-zinc-900 border-none font-bold shadow-md shadow-lime-500/20">
                        <span wire:loading.remove wire:target="store">Guardar Registro</span>
                        <span wire:loading wire:target="store">Guardando...</span>
                    </flux:button>
                </div>
            </div>
        </div>
    @endif

    {{-- ═══════════════════════════════════════════════════
         Modal Ver Detalle
    ═══════════════════════════════════════════════════ --}}
    @if ($isViewModalOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-zinc-900/40 dark:bg-black/60 backdrop-blur-sm p-4">
            <div class="bg-white dark:bg-zinc-900 w-full max-w-md p-0 rounded-2xl shadow-2xl flex flex-col overflow-hidden border border-zinc-200 dark:border-zinc-800">
                <div class="h-24 bg-gradient-to-r from-lime-500 to-emerald-600 relative">
                    <div class="absolute -bottom-8 left-6 w-16 h-16 rounded-2xl bg-white dark:bg-zinc-900 flex items-center justify-center shadow-lg border-4 border-white dark:border-zinc-900">
                        <flux:icon.map class="w-8 h-8 text-lime-600" />
                    </div>
                </div>
                
                <div class="px-6 pt-12 pb-6">
                    <h2 class="text-2xl font-black text-zinc-800 dark:text-zinc-100 mb-1">Detalle del Abordaje</h2>
                    <p class="text-sm font-medium text-zinc-500 mb-6">{{ $view_fecha }}</p>

                    <div class="space-y-4">
                        <div class="bg-zinc-50 dark:bg-zinc-800/50 p-4 rounded-xl border border-zinc-100 dark:border-zinc-700/50">
                            <span class="block text-[10px] font-bold uppercase tracking-wider text-zinc-500 mb-1">Observación</span>
                            <span class="block text-sm font-medium text-zinc-800 dark:text-zinc-200">{{ $view_observacion ?? 'Sin observaciones' }}</span>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <span class="block text-[10px] font-bold uppercase tracking-wider text-zinc-500 mb-1">Municipio</span>
                                <span class="block text-sm font-semibold text-zinc-800 dark:text-zinc-200">{{ $view_municipio }}</span>
                            </div>
                            <div>
                                <span class="block text-[10px] font-bold uppercase tracking-wider text-zinc-500 mb-1">Parroquia</span>
                                <span class="block text-sm font-semibold text-zinc-800 dark:text-zinc-200">{{ $view_parroquia }}</span>
                            </div>
                            <div>
                                <span class="block text-[10px] font-bold uppercase tracking-wider text-zinc-500 mb-1">Comuna</span>
                                <span class="block text-sm font-semibold text-zinc-800 dark:text-zinc-200">{{ $view_comuna }}</span>
                            </div>
                            <div>
                                <span class="block text-[10px] font-bold uppercase tracking-wider text-zinc-500 mb-1">Sector</span>
                                <span class="block text-sm font-semibold text-zinc-800 dark:text-zinc-200">{{ $view_sector }}</span>
                            </div>
                        </div>

                        <div class="mt-4 flex items-center justify-between p-4 rounded-xl bg-lime-50 dark:bg-lime-500/10 border border-lime-100 dark:border-lime-500/20">
                            <span class="text-sm font-bold uppercase tracking-wider text-lime-700 dark:text-lime-400">Cantidad Total</span>
                            <span class="text-2xl font-black text-lime-700 dark:text-lime-300">{{ number_format((int) $view_cantidad) }}</span>
                        </div>
                    </div>
                </div>

                <div class="p-4 border-t border-zinc-200 dark:border-zinc-800 bg-zinc-50/50 dark:bg-zinc-800/30 flex justify-end">
                    <flux:button wire:click="closeModal" variant="ghost">Cerrar Detalle</flux:button>
                </div>
            </div>
        </div>
    @endif


</div>

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
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">

        {{-- TOTAL ANUAL --}}
        <flux:card class="overflow-hidden min-w-[12rem] flex flex-col justify-between p-0 sm:p-0">
            <div class="px-6 pt-6 flex justify-between items-start">
                <div>
                    <flux:text class="uppercase text-xs font-bold tracking-wider text-zinc-500">TOTAL ANUAL</flux:text>
                    <flux:heading size="xl" class="mt-1 tabular-nums pb-2">{{ number_format($totalAnual) }}
                    </flux:heading>
                </div>
                <div class="p-2 bg-sky-100 dark:bg-sky-900/40 rounded-lg">
                    <flux:icon.chart-pie class="w-5 h-5 text-sky-600 dark:text-sky-400" />
                </div>
            </div>
            <div class="-mx-0 -mb-0 h-[3rem]">
                <svg class="w-full h-full" preserveAspectRatio="none" viewBox="0 0 100 100">
                    <polygon fill="currentColor" class="text-sky-100 dark:text-sky-400/30"
                        points="0,100 0,60 15,50 30,70 45,30 60,40 75,20 90,30 100,10 100,100" />
                    <polyline fill="none" stroke="currentColor" stroke-width="2" vector-effect="non-scaling-stroke"
                        class="text-sky-300 dark:text-sky-400"
                        points="0,60 15,50 30,70 45,30 60,40 75,20 90,30 100,10" />
                </svg>
            </div>
        </flux:card>

        {{-- TOTAL MES --}}
        <flux:card class="overflow-hidden min-w-[12rem] flex flex-col justify-between p-0 sm:p-0">
            <div class="px-6 pt-6 flex justify-between items-start">
                <div>
                    <flux:text class="uppercase text-xs font-bold tracking-wider text-zinc-500">TOTAL MES</flux:text>
                    <flux:heading size="xl" class="mt-1 tabular-nums pb-2">{{ number_format($totalMes) }}
                    </flux:heading>
                </div>
                <div class="p-2 bg-emerald-100 dark:bg-emerald-900/40 rounded-lg">
                    <flux:icon.chart-bar class="w-5 h-5 text-emerald-600 dark:text-emerald-400" />
                </div>
            </div>
            <div class="-mx-0 -mb-0 h-[3rem]">
                <svg class="w-full h-full" preserveAspectRatio="none" viewBox="0 0 100 100">
                    <polygon fill="currentColor" class="text-emerald-100 dark:text-emerald-400/30"
                        points="0,100 0,80 15,60 30,65 45,40 60,50 75,30 90,10 100,20 100,100" />
                    <polyline fill="none" stroke="currentColor" stroke-width="2" vector-effect="non-scaling-stroke"
                        class="text-emerald-300 dark:text-emerald-400"
                        points="0,80 15,60 30,65 45,40 60,50 75,30 90,10 100,20" />
                </svg>
            </div>
        </flux:card>

        {{-- TOTAL SEMANA --}}
        <flux:card class="overflow-hidden min-w-[12rem] flex flex-col justify-between p-0 sm:p-0">
            <div class="px-6 pt-6 flex justify-between items-start">
                <div>
                    <flux:text class="uppercase text-xs font-bold tracking-wider text-zinc-500">TOTAL SEMANA</flux:text>
                    <flux:heading size="xl" class="mt-1 tabular-nums pb-2">{{ number_format($totalSemana) }}
                    </flux:heading>
                </div>
                <div class="p-2 bg-violet-100 dark:bg-violet-900/40 rounded-lg">
                    <flux:icon.arrow-trending-up class="w-5 h-5 text-violet-600 dark:text-violet-400" />
                </div>
            </div>
            <div class="-mx-0 -mb-0 h-[3rem]">
                <svg class="w-full h-full" preserveAspectRatio="none" viewBox="0 0 100 100">
                    <polygon fill="currentColor" class="text-violet-100 dark:text-violet-400/30"
                        points="0,100 0,50 15,60 30,30 45,45 60,20 75,30 90,10 100,20 100,100" />
                    <polyline fill="none" stroke="currentColor" stroke-width="2" vector-effect="non-scaling-stroke"
                        class="text-violet-300 dark:text-violet-400"
                        points="0,50 15,60 30,30 45,45 60,20 75,30 90,10 100,20" />
                </svg>
            </div>
        </flux:card>

        {{-- CANTIDAD TRANSC. MES --}}
        <flux:card class="overflow-hidden min-w-[12rem] flex flex-col justify-between p-0 sm:p-0">
            <div class="px-6 pt-6 flex justify-between items-start">
                <div>
                    <flux:text class="uppercase text-xs font-bold tracking-wider text-zinc-500 line-clamp-1">TRANSC.
                        (MES)</flux:text>
                    <flux:heading size="xl" class="mt-1 tabular-nums pb-2">{{ number_format($transcripcionesMes) }}
                    </flux:heading>
                </div>
                <div class="p-2 bg-rose-100 dark:bg-rose-900/40 rounded-lg">
                    <flux:icon.document-text class="w-5 h-5 text-rose-600 dark:text-rose-400" />
                </div>
            </div>
            <div class="-mx-0 -mb-0 h-[3rem]">
                <svg class="w-full h-full" preserveAspectRatio="none" viewBox="0 0 100 100">
                    <polygon fill="currentColor" class="text-rose-100 dark:text-rose-400/30"
                        points="0,100 0,70 15,80 30,40 45,60 60,20 75,40 90,5 100,10 100,100" />
                    <polyline fill="none" stroke="currentColor" stroke-width="2" vector-effect="non-scaling-stroke"
                        class="text-rose-300 dark:text-rose-400"
                        points="0,70 15,80 30,40 45,60 60,20 75,40 90,5 100,10" />
                </svg>
            </div>
        </flux:card>

    </div>

    {{-- ═══════════════════════════════════════════════════
         Tabla
    ═══════════════════════════════════════════════════ --}}
    <flux:card class="shadow-sm mb-6">
        <div class="mb-4">
            <flux:input wire:model.live="search" icon="magnifying-glass"
                placeholder="Buscar por nombre, responsable, municipio, parroquia, sector o comuna..."
                class="w-full md:w-1/2" />
        </div>

        <div class="overflow-x-auto rounded-lg border border-zinc-200 dark:border-zinc-700">
            <table class="w-full text-sm text-left text-zinc-600 dark:text-zinc-400">
                <thead
                    class="bg-zinc-50 dark:bg-zinc-800/50 text-xs uppercase font-semibold text-zinc-700 dark:text-zinc-300 border-b border-zinc-200 dark:border-zinc-700">
                    <tr class="text-center">
                        <th class="px-3 py-3 w-10">#</th>
                        <th class="px-3 py-3 text-left">Observación</th>
                        <th class="px-3 py-3">Responsable</th>
                        <th class="px-3 py-3">Fecha</th>
                        <th class="px-3 py-3">Municipio</th>
                        <th class="px-3 py-3">Parroquia</th>
                        <th class="px-3 py-3">Comuna</th>
                        <th class="px-3 py-3">Sector</th>
                        <th class="px-3 py-3">Cantidad</th>
                        @if ($esSugima)
                            <th class="px-3 py-3">Ingreso</th>
                            <th class="px-3 py-3">Egreso</th>
                        @endif
                        <th class="px-3 py-3">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                    @forelse($transcripciones as $t)
                        <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800 transition-colors text-center">
                            <td class="px-3 py-3 font-medium text-zinc-500">
                                {{ ($transcripciones->currentPage() - 1) * $transcripciones->perPage() + $loop->iteration }}
                            </td>
                            <td class="px-3 py-3 text-left font-medium text-zinc-800 dark:text-zinc-100 line-clamp-1" title="{{ $t->observacion }}">
                                {{ $t->observacion ?? '—' }}
                            </td>
                            <td class="px-3 py-3 text-zinc-600 dark:text-zinc-300">
                                {{ $t->responsable }}
                            </td>
                            <td class="px-3 py-3 whitespace-nowrap">
                                <flux:badge size="sm" color="zinc">
                                    {{ $t->fecha->format('d/m/Y') }}
                                </flux:badge>
                            </td>
                            <td class="px-3 py-3">
                                <flux:badge size="sm" color="zinc">
                                    {{ $t->municipio->nombre }}
                                </flux:badge>
                            </td>
                            <td class="px-3 py-3">
                                <flux:badge size="sm" color="blue">
                                    {{ $t->parroquia->nombre }}
                                </flux:badge>
                            </td>
                            <td class="px-3 py-3">
                                <flux:badge size="sm" color="green">
                                    {{ $t->comuna->nombre }}
                                </flux:badge>
                            </td>
                            <td class="px-3 py-3">
                                <flux:badge size="sm" color="amber">
                                    {{ $t->sector->nombre }}
                                </flux:badge>
                            </td>
                            <td class="px-3 py-3 font-semibold text-zinc-800 dark:text-zinc-100">
                                {{ number_format($t->cantidad) }}
                            </td>
                            @if ($esSugima)
                                <td class="px-3 py-3 text-green-600 dark:text-green-400 font-semibold">
                                    {{ $t->ingreso !== null ? number_format($t->ingreso) : '—' }}
                                </td>
                                <td class="px-3 py-3 text-red-600 dark:text-red-400 font-semibold">
                                    {{ $t->egreso !== null ? number_format($t->egreso) : '—' }}
                                </td>
                            @endif
                            <td class="px-3 py-3">
                                <div class="flex items-center justify-center gap-2">
                                    <flux:button wire:click="show({{ $t->id }})" size="sm"
                                        variant="ghost" icon="eye" class="text-zinc-500 hover:text-blue-500" />
                                    <flux:button wire:click="edit({{ $t->id }})" size="sm"
                                        variant="ghost" icon="pencil-square"
                                        class="text-zinc-500 hover:text-amber-500" />
                                    <flux:button
                                        @click="confirmAction($wire, {{ $t->id }}, 'delete', '¿Eliminar transcripción?', 'Esta acción no se puede deshacer.', 'warning', 'Sí, eliminar')"
                                        size="sm" variant="ghost" icon="trash"
                                        class="text-zinc-500 hover:text-red-500" />
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $esSugima ? 12 : 10 }}" class="px-4 py-10 text-center text-zinc-500">
                                No se encontraron transcripciones de tipo
                                <strong>{{ $tipoLabels[$tipoActivo] ?? $tipoActivo }}</strong>.
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
                                <flux:button wire:click="openReportModal({{ $m->id }})" size="sm" icon="document-text" class="!bg-red-600 !text-white border-none hover:!bg-red-700 font-semibold" title="Descargar PDF para {{ $m->nombre }}">
                                    PDF
                                </flux:button>
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
                        Generar Reporte PDF
                    </h2>
                    <flux:button wire:click="closeReportModal" variant="ghost" icon="x-mark" />
                </div>

                {{-- Body --}}
                <div class="p-6">
                    <p class="text-sm text-zinc-600 dark:text-zinc-400 mb-4">
                        Selecciona el mes y el año para descargar el reporte de <span class="font-bold">{{ $tipoLabels[$tipoActivo] ?? $tipoActivo }}</span> para el municipio <span class="font-bold text-emerald-600 dark:text-emerald-400">{{ $reportMunicipioNombre ?? 'Seleccionado' }}</span>.
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

                        <flux:input wire:model.live="reportYear" type="number" step="1" min="2000" max="2100" label="Año" required />
                    </div>
                </div>

                {{-- Footer --}}
                <div class="px-6 py-4 border-t border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800/50 flex justify-end gap-3 rounded-b-xl">
                    <flux:button wire:click="closeReportModal" variant="ghost">Cancelar</flux:button>
                    <a href="{{ route('admin.transcripciones.pdf', ['mes' => $reportMonth, 'año' => $reportYear, 'tipo' => $tipoActivo, 'municipio_id' => $reportMunicipioId]) }}" 
                       target="_blank" 
                       @click="$wire.closeReportModal()"
                       class="inline-flex items-center gap-2 px-4 py-2 font-bold text-white bg-lime-500 rounded-lg shadow-sm hover:focus:bg-lime-600 hover:bg-lime-600 transition-colors">
                        <flux:icon.arrow-down-tray class="w-4 h-4" />
                        Descargar
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>

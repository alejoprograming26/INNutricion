<div class="space-y-6">
    {{-- ═══════════════════════════════════════════════════
         Header & Acciones Principales
    ═══════════════════════════════════════════════════ --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white dark:bg-zinc-900 p-6 rounded-2xl border border-zinc-200 dark:border-zinc-800 shadow-sm relative overflow-hidden">
        <div class="absolute -right-10 -top-10 w-40 h-40 bg-lime-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -left-10 -bottom-10 w-40 h-40 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>
        
        <div class="flex items-center gap-4 relative z-10">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-lime-500 to-emerald-600 flex items-center justify-center shadow-lg shadow-lime-500/30">
                <flux:icon.chart-bar-square class="w-8 h-8 text-white" />
            </div>
            <div>
                <h1 class="text-3xl font-black text-zinc-800 dark:text-zinc-100 tracking-tight">Diversidad Dietaria</h1>
                <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1 font-medium">
                    Encuestas de consumo y diversidad alimentaria.
                </p>
            </div>
        </div>
        <div class="relative z-10">
            <flux:button wire:click="create" icon="plus" class="!bg-gradient-to-r !from-lime-600 !to-emerald-600 hover:!from-lime-500 hover:!to-emerald-500 !text-white border-none font-bold shadow-md shadow-lime-500/20 transition-all duration-300 transform hover:-translate-y-0.5">
                Registrar Encuesta
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
                    <p class="text-xs font-bold text-emerald-50 uppercase tracking-widest mb-1 opacity-90">Total Anual (Enc.)</p>
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
                    <p class="text-xs font-bold text-blue-50 uppercase tracking-widest mb-1 opacity-90">Total Mes (Enc.)</p>
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
                    <p class="text-xs font-bold text-orange-50 uppercase tracking-widest mb-1 opacity-90">Total Semana (Enc.)</p>
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
                <flux:input wire:model.live.debounce.300ms="search" icon="magnifying-glass" placeholder="Buscar responsable, municipio, sector..." class="bg-white dark:bg-zinc-900 shadow-sm" />
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
                        <th class="px-6 py-4">Responsable / Fecha</th>
                        <th class="px-6 py-4">Ubicación</th>
                        <th class="px-6 py-4 text-center">Encuestas</th>
                        <th class="px-6 py-4 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800/50 bg-white dark:bg-zinc-900">
                    @forelse($registros as $d)
                        <tr class="hover:bg-zinc-50/50 dark:hover:bg-zinc-800/50 transition-colors group">
                            <td class="px-6 py-4 text-center font-medium text-zinc-400">
                                {{ ($registros->currentPage() - 1) * $registros->perPage() + $loop->iteration }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-lime-50 dark:bg-lime-500/10 flex items-center justify-center shrink-0 border border-lime-100 dark:border-lime-500/20 text-lime-600 dark:text-lime-400">
                                        <flux:icon.chart-bar-square class="w-5 h-5" />
                                    </div>
                                    <div>
                                        <p class="font-bold text-zinc-800 dark:text-zinc-100 line-clamp-1">
                                            {{ $d->responsable }}
                                        </p>
                                        <p class="text-xs text-zinc-500 dark:text-zinc-400 font-medium mt-0.5">
                                            {{ \Carbon\Carbon::parse($d->fecha)->format('d/m/Y') }}
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col gap-1">
                                    <span class="font-medium text-zinc-800 dark:text-zinc-200">{{ $d->sector->comuna->parroquia->municipio->nombre }}</span>
                                    <span class="text-zinc-500 text-xs">{{ $d->sector->nombre }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center px-3 py-1 rounded-full bg-lime-50 dark:bg-lime-500/10 text-lime-700 dark:text-lime-300 font-black border border-lime-100 dark:border-lime-500/20">
                                    {{ number_format($d->cantidad) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-1">
                                    <flux:button wire:click="show({{ $d->id }})" size="sm" variant="ghost" icon="eye" class="text-zinc-500 hover:text-blue-600" />
                                    <flux:button wire:click="edit({{ $d->id }})" size="sm" variant="ghost" icon="pencil-square" class="text-zinc-500 hover:text-amber-600" />
                                    <flux:button @click="confirmAction($wire, {{ $d->id }}, 'delete', '¿Eliminar registro?', 'Esta acción no se puede deshacer.', 'warning', 'Sí, eliminar')" size="sm" variant="ghost" icon="trash" class="text-zinc-500 hover:text-red-600" />
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-zinc-500">No se encontraron registros.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($registros->hasPages())
            <div class="p-4 bg-white dark:bg-zinc-900 border-t border-zinc-200 dark:border-zinc-800">
                {{ $registros->links() }}
            </div>
        @endif
    </flux:card>

    {{-- ═══════════════════════════════════════════════════
         Modal Crear / Editar
    ═══════════════════════════════════════════════════ --}}
    @if ($isModalOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-zinc-900/40 backdrop-blur-sm p-4">
            <div class="bg-white dark:bg-zinc-900 w-full max-w-4xl rounded-2xl shadow-2xl flex flex-col max-h-[90vh] border border-zinc-200 dark:border-zinc-800 overflow-hidden">
                <div class="px-6 py-5 border-b border-zinc-200 dark:border-zinc-800 flex items-center justify-between">
                    <h2 class="text-lg font-black text-zinc-800 dark:text-zinc-100">
                        {{ $diversidad_id ? 'Editar Encuesta' : 'Nueva Encuesta' }}
                    </h2>
                    <flux:button wire:click="closeModal" variant="ghost" icon="x-mark" class="rounded-full" />
                </div>

                <div class="p-6 overflow-y-auto bg-zinc-50/50 dark:bg-zinc-900/50">
                    <form wire:submit="store" id="diversidadForm" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <flux:input wire:model="fecha" type="date" label="Fecha *" required />
                        <flux:input wire:model="responsable" label="Responsable *" placeholder="Nombre del responsable" required />
                        
                        <flux:select wire:model.live="municipio_id" label="Municipio *" placeholder="Seleccionar municipio" required>
                            @foreach ($municipios as $m)
                                <flux:select.option value="{{ $m->id }}">{{ $m->nombre }}</flux:select.option>
                            @endforeach
                        </flux:select>

                        <flux:select wire:model.live="parroquia_id" label="Parroquia *" placeholder="{{ $municipio_id ? 'Selecciona parroquia' : '— Primero municipio —' }}" :disabled="!$municipio_id" required>
                            @foreach ($parroquiasFiltradas as $p)
                                <flux:select.option value="{{ $p->id }}">{{ $p->nombre }}</flux:select.option>
                            @endforeach
                        </flux:select>

                        <flux:select wire:model.live="comuna_id" label="Comuna *" placeholder="{{ $parroquia_id ? 'Selecciona comuna' : '— Primero parroquia —' }}" :disabled="!$parroquia_id" required>
                            @foreach ($comunasFiltradas as $c)
                                <flux:select.option value="{{ $c->id }}">{{ $c->nombre }}</flux:select.option>
                            @endforeach
                        </flux:select>

                        <flux:select wire:model="sector_id" label="Sector *" placeholder="{{ $comuna_id ? 'Selecciona sector' : '— Primero comuna —' }}" :disabled="!$comuna_id" required>
                            @foreach ($sectoresFiltrados as $s)
                                <flux:select.option value="{{ $s->id }}">{{ $s->nombre }}</flux:select.option>
                            @endforeach
                        </flux:select>

                        <flux:input wire:model="cantidad" type="number" min="1" label="Cantidad de Encuestas *" placeholder="0" required />

                        <div class="md:col-span-2">
                            <flux:textarea wire:model="observacion" label="Observación" placeholder="Detalles adicionales..." />
                        </div>
                    </form>
                </div>

                <div class="px-6 py-4 border-t border-zinc-200 dark:border-zinc-800 flex justify-end gap-3">
                    <flux:button wire:click="closeModal" variant="ghost">Cancelar</flux:button>
                    <flux:button type="submit" form="diversidadForm" class="!bg-gradient-to-r !from-lime-600 !to-emerald-600 hover:!from-lime-500 hover:!to-emerald-500 !text-zinc-900 border-none font-bold shadow-md shadow-lime-500/20">
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
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-zinc-900/40 backdrop-blur-sm p-4">
            <div class="bg-white dark:bg-zinc-900 w-full max-w-2xl rounded-2xl shadow-2xl overflow-hidden border border-zinc-200 dark:border-zinc-800">
                <div class="h-24 bg-gradient-to-r from-lime-500 to-emerald-600"></div>
                <div class="px-6 pb-6 -mt-8">
                    <div class="w-16 h-16 rounded-2xl bg-white dark:bg-zinc-900 flex items-center justify-center shadow-lg mb-4 border-4 border-white dark:border-zinc-900">
                        <flux:icon.chart-bar-square class="w-8 h-8 text-emerald-600" />
                    </div>
                    
                    <h2 class="text-2xl font-black text-zinc-800 dark:text-zinc-100">Encuesta de Diversidad</h2>
                    <p class="text-sm text-zinc-500 mb-6">{{ $view_municipio }} &bull; {{ $view_sector }}</p>

                    <div class="grid grid-cols-2 gap-6 text-sm">
                        <div>
                            <p class="text-zinc-400 font-bold uppercase text-[10px] tracking-widest">Responsable</p>
                            <p class="font-medium text-zinc-800 dark:text-zinc-200">{{ $view_responsable }}</p>
                        </div>
                        <div>
                            <p class="text-zinc-400 font-bold uppercase text-[10px] tracking-widest">Fecha</p>
                            <p class="font-medium">{{ $view_fecha }}</p>
                        </div>
                    </div>

                    <div class="mt-6 flex items-center justify-between p-4 rounded-xl bg-lime-50 dark:bg-lime-500/10 border border-lime-100 dark:border-lime-500/20">
                        <span class="text-sm font-bold uppercase tracking-wider text-lime-700 dark:text-lime-400">Encuestas Realizadas</span>
                        <span class="text-2xl font-black text-lime-700 dark:text-lime-300">{{ number_format($view_cantidad) }}</span>
                    </div>

                    @if($view_observacion)
                        <div class="mt-4 p-4 border border-zinc-100 dark:border-zinc-800 rounded-xl">
                            <p class="text-zinc-400 font-bold uppercase text-[10px] tracking-widest mb-2">Observaciones</p>
                            <p class="text-sm text-zinc-600 dark:text-zinc-400">{{ $view_observacion }}</p>
                        </div>
                    @endif
                </div>
                <div class="px-6 py-4 bg-zinc-50 dark:bg-zinc-800/50 flex justify-end">
                    <flux:button wire:click="closeModal" variant="ghost">Cerrar</flux:button>
                </div>
            </div>
        </div>
    @endif
</div>

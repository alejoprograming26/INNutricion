<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white dark:bg-zinc-900 p-6 rounded-2xl border border-zinc-200 dark:border-zinc-800 shadow-sm relative overflow-hidden">
        <div class="absolute -right-10 -top-10 w-40 h-40 bg-teal-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -left-10 -bottom-10 w-40 h-40 bg-cyan-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="flex items-center gap-4 relative z-10">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-teal-400 to-cyan-600 flex items-center justify-center shadow-lg shadow-teal-500/30">
                <flux:icon.heart class="w-8 h-8 text-white" />
            </div>
            <div>
                <h1 class="text-3xl font-black text-zinc-800 dark:text-zinc-100 tracking-tight">Círculo de Lactancia</h1>
                <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1 font-medium">Grupos de apoyo y promoción de lactancia materna.</p>
            </div>
        </div>
        <div class="relative z-10 flex gap-2">
            <flux:button wire:click="create" icon="plus" class="!bg-gradient-to-r !from-lime-600 !to-emerald-600 hover:!from-lime-500 hover:!to-emerald-500 !text-white border-none font-bold shadow-md shadow-lime-500/20 transition-all duration-300 transform hover:-translate-y-0.5">Registrar Círculo</flux:button>
        </div>
    </div>

    {{-- KPI Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-gradient-to-br from-lime-500 to-emerald-600 rounded-2xl p-6 shadow-lg shadow-emerald-500/20 relative overflow-hidden group hover:-translate-y-1 transition-all duration-300">
            <div class="absolute -right-6 -top-6 w-32 h-32 bg-white/10 rounded-full blur-2xl transition-transform duration-500 group-hover:scale-150"></div>
            <div class="flex justify-between items-start relative z-10">
                <div>
                    <p class="text-xs font-bold text-emerald-50 uppercase tracking-widest mb-1 opacity-90">Total Anual (Pers.)</p>
                    <h3 class="text-3xl font-black text-white tabular-nums drop-shadow-sm">{{ number_format($totalAnual) }}</h3>
                </div>
                <div class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center border border-white/30 shadow-inner">
                    <flux:icon.calendar-days class="w-5 h-5 text-white drop-shadow-sm" />
                </div>
            </div>
        </div>
        <div class="bg-gradient-to-br from-sky-400 to-blue-600 rounded-2xl p-6 shadow-lg shadow-blue-500/20 relative overflow-hidden group hover:-translate-y-1 transition-all duration-300">
            <div class="absolute -right-6 -top-6 w-32 h-32 bg-white/10 rounded-full blur-2xl transition-transform duration-500 group-hover:scale-150"></div>
            <div class="flex justify-between items-start relative z-10">
                <div>
                    <p class="text-xs font-bold text-blue-50 uppercase tracking-widest mb-1 opacity-90">Total Mes (Pers.)</p>
                    <h3 class="text-3xl font-black text-white tabular-nums drop-shadow-sm">{{ number_format($totalMes) }}</h3>
                </div>
                <div class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center border border-white/30 shadow-inner"><flux:icon.chart-pie class="w-5 h-5 text-white drop-shadow-sm" /></div>
            </div>
        </div>
        <div class="bg-gradient-to-br from-amber-400 to-orange-500 rounded-2xl p-6 shadow-lg shadow-orange-500/20 relative overflow-hidden group hover:-translate-y-1 transition-all duration-300">
            <div class="absolute -right-6 -top-6 w-32 h-32 bg-white/10 rounded-full blur-2xl transition-transform duration-500 group-hover:scale-150"></div>
            <div class="flex justify-between items-start relative z-10">
                <div>
                    <p class="text-xs font-bold text-orange-50 uppercase tracking-widest mb-1 opacity-90">Total Semana (Pers.)</p>
                    <h3 class="text-3xl font-black text-white tabular-nums drop-shadow-sm">{{ number_format($totalSemana) }}</h3>
                </div>
                <div class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center border border-white/30 shadow-inner"><flux:icon.bolt class="w-5 h-5 text-white drop-shadow-sm" /></div>
            </div>
        </div>
        <div class="bg-gradient-to-br from-teal-400 to-emerald-500 rounded-2xl p-6 shadow-lg shadow-teal-500/20 relative overflow-hidden group hover:-translate-y-1 transition-all duration-300">
            <div class="absolute -right-6 -top-6 w-32 h-32 bg-white/10 rounded-full blur-2xl transition-transform duration-500 group-hover:scale-150"></div>
            <div class="flex justify-between items-start relative z-10">
                <div>
                    <p class="text-xs font-bold text-teal-50 uppercase tracking-widest mb-1 opacity-90">Registros (Mes)</p>
                    <h3 class="text-3xl font-black text-white tabular-nums drop-shadow-sm">{{ number_format($registrosMes) }}</h3>
                </div>
                <div class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center border border-white/30 shadow-inner"><flux:icon.document-check class="w-5 h-5 text-white drop-shadow-sm" /></div>
            </div>
        </div>
    </div>

    {{-- Tabla --}}
    <flux:card class="shadow-sm border-zinc-200/60 dark:border-zinc-800/60 !p-0 overflow-hidden">
        <div class="bg-zinc-50/80 dark:bg-zinc-800/30 p-4 border-b border-zinc-200 dark:border-zinc-800 flex flex-col lg:flex-row gap-4 items-center justify-between">
            <div class="w-full lg:w-1/3">
                <flux:input wire:model.live.debounce.300ms="search" icon="magnifying-glass" placeholder="Buscar grupo, responsable, municipio..." class="bg-white dark:bg-zinc-900 shadow-sm" />
            </div>
            <div class="flex flex-wrap items-center gap-3 w-full lg:w-auto">
                <div class="flex items-center gap-2 bg-white dark:bg-zinc-900 p-1.5 rounded-lg border border-zinc-200 dark:border-zinc-700 shadow-sm">
                    <flux:input type="date" wire:model.live="dateFrom" class="w-36 !border-none !shadow-none !ring-0" />
                    <span class="text-zinc-400">—</span>
                    <flux:input type="date" wire:model.live="dateTo" class="w-36 !border-none !shadow-none !ring-0" />
                </div>
                @if($search !== '' || $dateFrom !== '' || $dateTo !== '')
                    <flux:button wire:click="clearFilters" size="sm" variant="danger" icon="x-mark">Limpiar</flux:button>
                @endif
                <flux:button wire:click="toggleSort" size="sm" variant="subtle" icon="{{ $sortDirection === 'desc' ? 'bars-arrow-down' : 'bars-arrow-up' }}" class="bg-white dark:bg-zinc-900 shadow-sm border-zinc-200 dark:border-zinc-700">{{ $sortDirection === 'desc' ? 'Recientes' : 'Antiguos' }}</flux:button>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-zinc-600 dark:text-zinc-400">
                <thead class="bg-white dark:bg-zinc-900 text-xs uppercase font-semibold text-zinc-500 border-b border-zinc-200 dark:border-zinc-800">
                    <tr>
                        <th class="px-6 py-4 w-12 text-center">#</th>
                        <th class="px-6 py-4">Grupo / Responsable</th>
                        <th class="px-6 py-4">Ubicación</th>
                        <th class="px-6 py-4 text-center">Cantidad</th>
                        <th class="px-6 py-4 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800/50 bg-white dark:bg-zinc-900">
                    @forelse($registros as $c)
                        <tr class="hover:bg-zinc-50/50 dark:hover:bg-zinc-800/50 transition-colors group">
                            <td class="px-6 py-4 text-center font-medium text-zinc-400">{{ ($registros->currentPage() - 1) * $registros->perPage() + $loop->iteration }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-teal-50 dark:bg-teal-500/10 flex items-center justify-center shrink-0 border border-teal-100 dark:border-teal-500/20 text-teal-600 dark:text-teal-400"><flux:icon.heart class="w-5 h-5" /></div>
                                    <div>
                                        <p class="font-bold text-zinc-800 dark:text-zinc-100 line-clamp-1">{{ $c->nombre_grupo }}</p>
                                        <p class="text-xs text-zinc-500 dark:text-zinc-400 font-medium mt-0.5">{{ $c->responsable }} &bull; {{ \Carbon\Carbon::parse($c->fecha)->format('d/m/Y') }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col gap-1">
                                    <span class="font-medium text-zinc-800 dark:text-zinc-200">{{ $c->sector->comuna->parroquia->municipio->nombre }}</span>
                                    <span class="text-zinc-500 text-xs">{{ $c->sector->nombre }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center px-3 py-1 rounded-full bg-teal-50 dark:bg-teal-500/10 text-teal-700 dark:text-teal-300 font-black border border-teal-100 dark:border-teal-500/20">{{ number_format($c->cantidad) }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-1">
                                    <flux:button wire:click="show({{ $c->id }})" size="sm" variant="ghost" icon="eye" class="text-zinc-500 hover:text-blue-600" />
                                    <flux:button wire:click="edit({{ $c->id }})" size="sm" variant="ghost" icon="pencil-square" class="text-zinc-500 hover:text-amber-600" />
                                    <flux:button @click="confirmAction($wire, {{ $c->id }}, 'delete', '¿Eliminar registro?', 'Esta acción no se puede deshacer.', 'warning', 'Sí, eliminar')" size="sm" variant="ghost" icon="trash" class="text-zinc-500 hover:text-red-600" />
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-6 py-12 text-center text-zinc-500">No se encontraron registros.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($registros->hasPages())
            <div class="p-4 bg-white dark:bg-zinc-900 border-t border-zinc-200 dark:border-zinc-800">{{ $registros->links() }}</div>
        @endif
    </flux:card>

    {{-- Relación por Municipio --}}
    <flux:card class="shadow-sm mb-6 mt-6">
        <div class="mb-4">
            <h2 class="text-lg font-bold text-zinc-800 dark:text-zinc-100 uppercase tracking-wide">Relación por Municipio</h2>
            <p class="text-sm text-zinc-500">Resumen general de actividades registradas en cada municipio.</p>
        </div>
        <div class="overflow-x-auto rounded-lg border border-zinc-200 dark:border-zinc-700">
            <table class="w-full text-sm text-left text-zinc-600 dark:text-zinc-400">
                <thead class="bg-zinc-50 dark:bg-zinc-800/50 text-xs uppercase font-semibold text-zinc-700 dark:text-zinc-300 border-b border-zinc-200 dark:border-zinc-700">
                    <tr class="text-center">
                        <th class="px-3 py-3 text-left">Municipio</th>
                        <th class="px-3 py-3">Total Anual</th>
                        <th class="px-3 py-3">Total Mes</th>
                        <th class="px-3 py-3">Última Semana</th>
                        <th class="px-3 py-3">Registros (Mes)</th>
                        <th class="px-3 py-3 text-center">Reportes</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                    @forelse($municipiosConTotales as $m)
                        <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800 transition-colors text-center">
                            <td class="px-3 py-3 text-left font-semibold text-zinc-800 dark:text-zinc-100">{{ $m->nombre }}</td>
                            <td class="px-3 py-3"><span class="bg-sky-100 text-sky-700 dark:bg-sky-900/40 dark:text-sky-300 px-2 py-1 rounded text-xs font-bold">{{ number_format($m->total_anual ?? 0) }}</span></td>
                            <td class="px-3 py-3"><span class="bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300 px-2 py-1 rounded text-xs font-bold">{{ number_format($m->total_mes ?? 0) }}</span></td>
                            <td class="px-3 py-3"><span class="bg-violet-100 text-violet-700 dark:bg-violet-900/40 dark:text-violet-300 px-2 py-1 rounded text-xs font-bold">{{ number_format($m->total_semana ?? 0) }}</span></td>
                            <td class="px-3 py-3"><span class="bg-rose-100 text-rose-700 dark:bg-rose-900/40 dark:text-rose-300 px-2 py-1 rounded text-xs font-bold">{{ number_format($m->abordajes_mes_count ?? 0) }}</span></td>
                            <td class="px-3 py-3">
                                <div class="flex items-center justify-center gap-2">
                                    <flux:button wire:click="openGraphModal({{ $m->id }})"
                                        size="sm" icon="chart-pie"
                                        class="!bg-violet-600 !text-white border-none hover:!bg-violet-700 font-semibold"
                                        title="Ver Gráficas para {{ $m->nombre }}">
                                        Gráfica
                                    </flux:button>
                                    <flux:button wire:click="openReportModal({{ $m->id }}, 'pdf')" size="sm" icon="document-text" class="!bg-red-600 !text-white border-none hover:!bg-red-700 font-semibold" title="Descargar PDF">PDF</flux:button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-4 py-8 text-center text-zinc-500">No hay municipios registrados en el sistema.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </flux:card>

    {{-- Modal Crear / Editar --}}
    @if ($isModalOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-zinc-900/40 dark:bg-black/60 backdrop-blur-sm p-4" wire:key="modal-{{ $circulo_id ?? 'new' }}">
            <div class="bg-white dark:bg-zinc-900 w-full max-w-4xl rounded-2xl shadow-2xl flex flex-col max-h-[90vh] border border-zinc-200 dark:border-zinc-800 overflow-hidden">
                <div class="relative px-6 py-5 border-b border-zinc-200 dark:border-zinc-800 shrink-0 overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-r from-lime-50 to-emerald-50 dark:from-lime-500/5 dark:to-emerald-500/5"></div>
                    <div class="relative flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-white dark:bg-zinc-800 flex items-center justify-center shadow-sm border border-zinc-200 dark:border-zinc-700"><flux:icon.heart class="w-5 h-5 text-teal-600 dark:text-teal-400" /></div>
                            <div>
                                <h2 class="text-lg font-black text-zinc-800 dark:text-zinc-100">{{ $circulo_id ? 'Editar Círculo' : 'Registrar Nuevo Círculo' }}</h2>
                                <p class="text-xs text-zinc-500 font-medium">Complete los datos del grupo y ubicación.</p>
                            </div>
                        </div>
                        <flux:button wire:click="closeModal" variant="ghost" icon="x-mark" class="rounded-full hover:bg-white dark:hover:bg-zinc-800" />
                    </div>
                </div>
                <div class="p-6 overflow-y-auto bg-zinc-50/50 dark:bg-zinc-900/50">
                    <form wire:submit="store" id="circuloForm" class="space-y-6">
                        <div class="bg-white dark:bg-zinc-900 p-5 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm">
                            <h3 class="text-xs font-bold uppercase tracking-wider text-zinc-500 mb-4 flex items-center gap-2"><flux:icon.document-text class="w-4 h-4" /> Datos Generales</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div class="md:col-span-2"><flux:input wire:model="observacion" label="Observación" placeholder="Detalles adicionales (opcional)" /></div>
                                <div class="md:col-span-2"><flux:input wire:model="nombre_grupo" label="Nombre del Grupo *" placeholder="Nombre del grupo de lactancia" required /></div>
                                <div><flux:input wire:model="fecha" type="date" label="Fecha *" required /></div>
                                <div><flux:input wire:model="responsable" label="Responsable *" placeholder="Nombre del responsable" required /></div>
                            </div>
                        </div>
                        <div class="bg-white dark:bg-zinc-900 p-5 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm">
                            <h3 class="text-xs font-bold uppercase tracking-wider text-zinc-500 mb-4 flex items-center gap-2"><flux:icon.map-pin class="w-4 h-4" /> Ubicación Geográfica</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div><flux:select wire:model.live="municipio_id" label="Municipio *" placeholder="Selecciona municipio" required>@foreach ($municipios as $m)<flux:select.option value="{{ $m->id }}">{{ $m->nombre }}</flux:select.option>@endforeach</flux:select></div>
                                <div><flux:select wire:model.live="parroquia_id" label="Parroquia *" placeholder="{{ $municipio_id ? 'Selecciona parroquia' : '— Primero municipio —' }}" :disabled="!$municipio_id" required>@foreach ($parroquiasFiltradas as $p)<flux:select.option value="{{ $p->id }}">{{ $p->nombre }}</flux:select.option>@endforeach</flux:select></div>
                                <div><flux:select wire:model.live="comuna_id" label="Comuna *" placeholder="{{ $parroquia_id ? 'Selecciona comuna' : '— Primero parroquia —' }}" :disabled="!$parroquia_id" required>@foreach ($comunasFiltradas as $c)<flux:select.option value="{{ $c->id }}">{{ $c->nombre }}</flux:select.option>@endforeach</flux:select></div>
                                <div><flux:select wire:model="sector_id" label="Sector *" placeholder="{{ $comuna_id ? 'Selecciona sector' : '— Primero comuna —' }}" :disabled="!$comuna_id" required>@foreach ($sectoresFiltrados as $s)<flux:select.option value="{{ $s->id }}">{{ $s->nombre }}</flux:select.option>@endforeach</flux:select></div>
                            </div>
                        </div>
                        <div class="bg-lime-50/50 dark:bg-lime-500/5 p-5 rounded-xl border border-lime-100 dark:border-lime-500/20 shadow-sm">
                            <h3 class="text-xs font-bold uppercase tracking-wider text-lime-600 dark:text-lime-400 mb-4 flex items-center gap-2"><flux:icon.calculator class="w-4 h-4" /> Resultados</h3>
                            <div><flux:input wire:model="cantidad" type="number" min="1" label="Cantidad de Personas *" placeholder="0" required class="text-lg font-bold" /></div>
                        </div>
                    </form>
                </div>
                <div class="px-6 py-4 border-t border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 shrink-0 flex justify-end gap-3 rounded-b-2xl">
                    <flux:button wire:click="closeModal" variant="ghost" class="hover:bg-zinc-100 dark:hover:bg-zinc-800">Cancelar</flux:button>
                    <flux:button type="submit" form="circuloForm" class="!bg-gradient-to-r !from-lime-600 !to-emerald-600 hover:!from-lime-500 hover:!to-emerald-500 !text-white border-none font-bold shadow-md shadow-lime-500/20">
                        <span wire:loading.remove wire:target="store">Guardar Registro</span>
                        <span wire:loading wire:target="store">Guardando...</span>
                    </flux:button>
                </div>
            </div>
        </div>
    @endif

    {{-- Modal Ver Detalle --}}
    @if ($isViewModalOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-zinc-900/40 dark:bg-black/60 backdrop-blur-sm p-4">
            <div class="bg-white dark:bg-zinc-900 w-full max-w-md p-0 rounded-2xl shadow-2xl flex flex-col overflow-hidden border border-zinc-200 dark:border-zinc-800">
                <div class="h-20 bg-gradient-to-r from-teal-400 to-cyan-600 relative">
                    <div class="absolute -bottom-6 left-6 w-12 h-12 rounded-xl bg-white dark:bg-zinc-900 flex items-center justify-center shadow-md border-[3px] border-white dark:border-zinc-900"><flux:icon.heart class="w-6 h-6 text-teal-600" /></div>
                </div>
                <div class="px-6 pt-8 pb-5">
                    <div class="flex justify-between items-start mb-4">
                        <div class="pr-2">
                            <h2 class="text-xl font-black text-zinc-800 dark:text-zinc-100 leading-tight line-clamp-2">{{ $view_nombre_grupo }}</h2>
                            <p class="text-xs font-medium text-zinc-500 mt-0.5">{{ $view_fecha }}</p>
                        </div>
                        <div class="text-right shrink-0">
                            <span class="block text-[9px] font-bold uppercase tracking-wider text-teal-600 dark:text-teal-400 mb-0.5">Personas</span>
                            <span class="text-xl font-black text-teal-700 dark:text-teal-300 leading-none">{{ number_format((int) $view_cantidad) }}</span>
                        </div>
                    </div>
                    <div class="bg-zinc-50 dark:bg-zinc-800/50 rounded-xl border border-zinc-100 dark:border-zinc-700/50 overflow-hidden">
                        <div class="grid grid-cols-1 border-b border-zinc-100 dark:border-zinc-700/50"><div class="p-3"><span class="block text-[9px] font-bold uppercase tracking-wider text-zinc-400 mb-0.5">Responsable</span><span class="block text-xs font-semibold text-zinc-800 dark:text-zinc-200">{{ $view_responsable ?? 'No especificado' }}</span></div></div>
                        <div class="grid grid-cols-2 divide-x divide-zinc-100 dark:divide-zinc-700/50 border-b border-zinc-100 dark:border-zinc-700/50">
                            <div class="p-3"><span class="block text-[9px] font-bold uppercase tracking-wider text-zinc-400 mb-0.5">Municipio</span><span class="block text-xs font-medium text-zinc-700 dark:text-zinc-300">{{ $view_municipio }}</span></div>
                            <div class="p-3"><span class="block text-[9px] font-bold uppercase tracking-wider text-zinc-400 mb-0.5">Sector</span><span class="block text-xs font-medium text-zinc-700 dark:text-zinc-300">{{ $view_sector }}</span></div>
                        </div>
                        <div class="p-3 bg-white dark:bg-zinc-900/50"><span class="block text-[9px] font-bold uppercase tracking-wider text-zinc-400 mb-1">Observación</span><p class="text-[11px] text-zinc-600 dark:text-zinc-400 leading-relaxed">{{ $view_observacion ?? 'Sin observaciones' }}</p></div>
                    </div>
                </div>
                <div class="px-4 py-3 border-t border-zinc-200 dark:border-zinc-800 bg-zinc-50/50 dark:bg-zinc-800/30 flex justify-end"><flux:button wire:click="closeModal" variant="ghost" size="sm">Cerrar Detalle</flux:button></div>
            </div>
        </div>
    @endif

    {{-- ═══════════════════════════════════════════════════
         Modal de Gráficas
    ═══════════════════════════════════════════════════ --}}
    @if ($isGraphModalOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-zinc-900/40 dark:bg-black/60 backdrop-blur-sm p-4">
            <div class="bg-white dark:bg-zinc-900 w-full max-w-sm rounded-xl shadow-xl flex flex-col border border-zinc-200 dark:border-zinc-800">
                <div class="flex items-center justify-between px-6 py-4 border-b border-zinc-200 dark:border-zinc-700 bg-gradient-to-r from-violet-50 to-purple-50 dark:from-violet-500/5 dark:to-purple-500/5 rounded-t-xl">
                    <div class="flex items-center gap-2 overflow-hidden">
                        <flux:icon.chart-bar class="w-5 h-5 text-violet-600 shrink-0" />
                        <h2 class="text-base font-bold text-zinc-800 dark:text-zinc-100 truncate">Análisis de Gráficas</h2>
                    </div>
                    <flux:button wire:click="closeGraphModal" variant="ghost" icon="x-mark" class="rounded-full shrink-0" />
                </div>

                <div class="p-6">
                    <p class="text-sm text-zinc-600 dark:text-zinc-400 mb-4">
                        Visualiza el análisis estadístico detallado
                        @if($graphMunicipioNombre)
                            para <span class="font-bold text-violet-600 dark:text-violet-400">{{ $graphMunicipioNombre }}</span>.
                        @else
                            de todos los municipios.
                        @endif
                    </p>

                    <div class="space-y-4">
                        <flux:select wire:model.live="graphMonth" label="Mes de Análisis">
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
                        <flux:input wire:model.live="graphAno" type="number" step="1" label="Año" required />
                    </div>
                </div>

                <div class="px-6 py-4 border-t border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800/50 flex justify-end gap-3 rounded-b-xl">
                    <flux:button wire:click="closeGraphModal" variant="ghost">Cancelar</flux:button>
                    <flux:button wire:click="viewGraphs" icon="presentation-chart-line" class="!bg-gradient-to-r !from-violet-600 !to-purple-600 hover:!from-violet-500 hover:!to-purple-500 !text-white border-none font-bold shadow-md shadow-violet-500/20">
                        Ver Gráficas
                    </flux:button>
                </div>
            </div>
        </div>
    @endif

    {{-- ═══════════════════════════════════════════════════
         Modal Descargar Reporte PDF
    ═══════════════════════════════════════════════════ --}}
    @if ($isReportModalOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-zinc-900/40 dark:bg-black/60 backdrop-blur-sm p-4">
            <div class="bg-white dark:bg-zinc-900 w-full max-w-sm rounded-xl shadow-xl flex flex-col border border-zinc-200 dark:border-zinc-800">
                {{-- Header --}}
                <div class="flex items-center justify-between px-6 py-4 border-b border-zinc-200 dark:border-zinc-700 bg-gradient-to-r from-red-50 to-rose-50 dark:from-red-500/5 dark:to-rose-500/5 rounded-t-xl">
                    <div class="flex items-center gap-2 overflow-hidden">
                        <flux:icon.document-text class="w-5 h-5 text-red-600 shrink-0" />
                        <h2 class="text-base font-bold text-zinc-800 dark:text-zinc-100 truncate">
                            Generar Reporte PDF
                        </h2>
                    </div>
                    <flux:button wire:click="closeReportModal" variant="ghost" icon="x-mark" class="rounded-full shrink-0" />
                </div>

                {{-- Body --}}
                <div class="p-6">
                    <p class="text-sm text-zinc-600 dark:text-zinc-400 mb-4">
                        Selecciona el mes y el año para descargar el reporte consolidado
                        @if($reportMunicipioNombre)
                            para el municipio <span class="font-bold text-red-600 dark:text-red-400">{{ $reportMunicipioNombre }}</span>.
                        @else
                            de todos los municipios.
                        @endif
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
                    <flux:button wire:click="viewPdf" icon="arrow-down-tray" class="!bg-gradient-to-r !from-red-600 !to-rose-600 hover:!from-red-500 hover:!to-rose-500 !text-white border-none font-bold shadow-md shadow-red-500/20">
                        Descargar PDF
                    </flux:button>
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

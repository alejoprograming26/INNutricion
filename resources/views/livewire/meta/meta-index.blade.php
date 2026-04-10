<div>
    {{-- Header --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <flux:heading size="xl" level="1">Gestión de Metas</flux:heading>
            <flux:subheading size="lg">Administra las metas anuales de abordajes por municipio.</flux:subheading>
        </div>
        <div>
            <flux:button wire:click="create" icon="plus"
                class="!bg-lime-500 !text-zinc-900 border-none hover:!bg-lime-400 font-bold">
                Nueva Meta
            </flux:button>
        </div>
    </div>

    {{-- Tabla Principal --}}
    <flux:card class="shadow-sm mb-6">
        <div class="mb-4">
            <flux:input wire:model.live="search" icon="magnifying-glass"
                placeholder="Buscar por año..." class="w-full md:w-1/3" />
        </div>

        <div class="overflow-x-auto rounded-lg border border-zinc-200 dark:border-zinc-700">
            <table class="w-full text-sm text-left text-zinc-600 dark:text-zinc-400">
                <thead class="bg-zinc-50 dark:bg-zinc-800/50 text-xs uppercase font-semibold text-zinc-700 dark:text-zinc-300 border-b border-zinc-200 dark:border-zinc-700">
                    <tr class="text-center">
                        <th class="px-4 py-3 w-12">#</th>
                        <th class="px-4 py-3">Año</th>
                        <th class="px-4 py-3">Total Abordajes</th>
                        <th class="px-4 py-3">Meta Mensual Aprox.</th>
                        <th class="px-4 py-3">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                    @forelse($metas as $meta)
                        <tr class="hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors text-center">
                            <td class="px-4 py-3 font-medium text-zinc-500">
                                {{ ($metas->currentPage() - 1) * $metas->perPage() + $loop->iteration }}
                            </td>
                            <td class="px-4 py-3">
                                <flux:badge size="sm" color="lime">{{ $meta->ano }}</flux:badge>
                            </td>
                            <td class="px-4 py-3 font-semibold text-zinc-800 dark:text-zinc-100">
                                {{ number_format($meta->total) }}
                            </td>
                            <td class="px-4 py-3 text-zinc-500 dark:text-zinc-400">
                                ~{{ number_format((int) round($meta->total / 12)) }} / mes
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-center gap-2">
                                    <flux:button wire:click="show({{ $meta->id }})" size="sm" variant="ghost"
                                        icon="eye" class="text-zinc-500 hover:text-blue-500" />
                                    <flux:button wire:click="edit({{ $meta->id }})" size="sm" variant="ghost"
                                        icon="pencil-square" class="text-zinc-500 hover:text-amber-500" />
                                    <flux:button
                                        @click="confirmAction($wire, {{ $meta->id }}, 'delete', '¿Eliminar meta?', 'Se eliminarán también todos los detalles por municipio. Esta acción no se puede deshacer.', 'warning', 'Sí, eliminar')"
                                        size="sm" variant="ghost" icon="trash"
                                        class="text-zinc-500 hover:text-red-500" />
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-zinc-500">
                                No se encontraron metas registradas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $metas->links() }}
        </div>
    </flux:card>

    {{-- ══════════════════════════════════════════════════════════════════════ --}}
    {{-- Modal Crear / Editar                                                  --}}
    {{-- ══════════════════════════════════════════════════════════════════════ --}}
    @if ($isModalOpen)
    <div class="fixed inset-0 z-50 flex items-start justify-center bg-black/60 backdrop-blur-sm py-6 overflow-y-auto"
         wire:key="meta-modal-{{ $meta_id ?? 'new' }}"
         x-data="{
             vals: @js($detalles),
             updateVal(id, v) {
                 this.vals[id] = parseInt(v) || 0;
             },
             monthly(id) {
                 const v = parseInt(this.vals[id]) || 0;
                 return Math.round(v / 12).toLocaleString('es-VE');
             },
             totalFmt() {
                 const t = Object.values(this.vals).reduce((s, v) => s + (parseInt(v) || 0), 0);
                 return t.toLocaleString('es-VE');
             },
             totalMonthlyFmt() {
                 const t = Object.values(this.vals).reduce((s, v) => s + (parseInt(v) || 0), 0);
                 return '~' + Math.round(t / 12).toLocaleString('es-VE');
             }
         }">
        <div class="bg-white dark:bg-zinc-900 w-full max-w-2xl p-6 rounded-xl shadow-xl flex flex-col space-y-4 mx-4 my-auto">

            <div>
                <flux:heading size="lg">{{ $meta_id ? 'Editar Meta' : 'Nueva Meta' }}</flux:heading>
                <flux:subheading>Define las metas anuales de abordajes por municipio.</flux:subheading>
            </div>

            <form wire:submit="store" class="space-y-5">

                {{-- Año --}}
                <div class="w-48">
                    <flux:input wire:model="ano" label="Año *" type="number" min="2000" max="2100"
                        placeholder="{{ date('Y') }}" required />
                </div>

                {{-- Tabla de municipios --}}
                <div>
                    <p class="text-sm font-semibold text-zinc-600 dark:text-zinc-300 mb-2 uppercase tracking-wide">
                        Metas por Municipio
                    </p>
                    <div class="overflow-x-auto rounded-lg border border-zinc-200 dark:border-zinc-700 max-h-80 overflow-y-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="bg-zinc-100 dark:bg-zinc-800 text-xs uppercase font-semibold text-zinc-700 dark:text-zinc-300 border-b border-zinc-200 dark:border-zinc-700 sticky top-0 z-10">
                                <tr>
                                    <th class="px-4 py-2">Municipio</th>
                                    <th class="px-4 py-2 text-center">Meta Anual</th>
                                    <th class="px-4 py-2 text-center">Meta Mensual</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700 bg-white dark:bg-zinc-900">
                                @foreach($municipios as $municipio)
                                    <tr class="hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors duration-100">
                                        <td class="px-4 py-2 font-medium text-zinc-700 dark:text-zinc-200">
                                            {{ $municipio->nombre }}
                                        </td>
                                        <td class="px-3 py-1 text-center">
                                            <input
                                                type="number"
                                                min="0"
                                                wire:model="detalles.{{ $municipio->id }}"
                                                @input="updateVal({{ $municipio->id }}, $event.target.value)"
                                                class="w-28 text-center rounded-md border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 text-zinc-800 dark:text-zinc-100 px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-lime-400"
                                                placeholder="0"
                                            />
                                        </td>
                                        <td class="px-4 py-2 text-center text-zinc-500 dark:text-zinc-400 font-medium"
                                            x-text="monthly({{ $municipio->id }})">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-lime-50 dark:bg-zinc-800 border-t-2 border-lime-300 dark:border-lime-600 sticky bottom-0 z-10">
                                <tr>
                                    <td class="px-4 py-2 font-bold text-zinc-700 dark:text-zinc-100 uppercase text-xs tracking-wide">
                                        TOTAL
                                    </td>
                                    <td class="px-4 py-2 text-center font-bold text-lime-700 dark:text-white text-sm"
                                        x-text="totalFmt()">
                                    </td>
                                    <td class="px-4 py-2 text-center font-bold text-lime-600 dark:text-white text-sm"
                                        x-text="totalMonthlyFmt()">
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-zinc-200 dark:border-zinc-800">
                    @error('detalles')
                        <p class="flex-1 text-sm text-red-500 font-medium flex items-center gap-1.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4 shrink-0" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                    <flux:button wire:click="closeModal" variant="ghost">Cancelar</flux:button>
                    <flux:button type="submit"
                        class="!bg-lime-500 !text-zinc-900 border-none hover:!bg-lime-400 font-bold shadow-md">
                        <span wire:loading.remove wire:target="store">Guardar</span>
                        <span wire:loading wire:target="store">Guardando…</span>
                    </flux:button>
                </div>

            </form>
        </div>
    </div>
    @endif

    {{-- ══════════════════════════════════════════════════════════════════════ --}}
    {{-- Modal Ver (Show)                                                       --}}
    {{-- ══════════════════════════════════════════════════════════════════════ --}}
    @if ($isViewModalOpen)
    <div class="fixed inset-0 z-50 flex items-start justify-center bg-black/60 backdrop-blur-sm py-6 overflow-y-auto">
        <div class="bg-white dark:bg-zinc-900 w-full max-w-2xl p-6 rounded-xl shadow-xl flex flex-col space-y-4 mx-4 my-auto">

            <div class="flex items-center justify-between">
                <div>
                    <flux:heading size="lg">Detalle de Meta</flux:heading>
                    <flux:subheading>Año <span class="font-bold text-lime-600">{{ $view_ano }}</span></flux:subheading>
                </div>
                <div class="text-right">
                    <p class="text-xs uppercase tracking-wide text-zinc-500 dark:text-zinc-400 font-semibold">Total Anual</p>
                    <p class="text-2xl font-extrabold text-lime-600 dark:text-lime-400">{{ number_format($view_total) }}</p>
                    <p class="text-xs text-zinc-400 dark:text-zinc-500 mt-0.5">~{{ number_format((int) round($view_total / 12)) }} / mes</p>
                </div>
            </div>

            <div class="overflow-x-auto rounded-lg border border-zinc-200 dark:border-zinc-700 max-h-96 overflow-y-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-zinc-100 dark:bg-zinc-800 text-xs uppercase font-semibold text-zinc-700 dark:text-zinc-300 border-b border-zinc-200 dark:border-zinc-700 sticky top-0 z-10">
                        <tr>
                            <th class="px-4 py-2">#</th>
                            <th class="px-4 py-2">Municipio</th>
                            <th class="px-4 py-2 text-center">Meta Anual</th>
                            <th class="px-4 py-2 text-center">Meta Mensual</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700 bg-white dark:bg-zinc-900">
                        @foreach($view_detalles as $i => $detalle)
                            <tr class="hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors duration-100">
                                <td class="px-4 py-2 text-zinc-400 text-xs">{{ $i + 1 }}</td>
                                <td class="px-4 py-2 font-medium text-zinc-700 dark:text-zinc-200">
                                    {{ $detalle['municipio'] }}
                                </td>
                                <td class="px-4 py-2 text-center font-semibold text-zinc-800 dark:text-zinc-100">
                                    {{ number_format($detalle['meta_anual']) }}
                                </td>
                                <td class="px-4 py-2 text-center text-zinc-500 dark:text-zinc-400">
                                    {{ number_format($detalle['meta_mensual']) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="flex justify-end pt-4 border-t border-zinc-200 dark:border-zinc-800">
                <flux:button wire:click="closeModal" variant="ghost">Cerrar</flux:button>
            </div>
        </div>
    </div>
    @endif

</div>

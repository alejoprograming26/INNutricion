<div>
    {{-- Header --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <flux:heading size="xl" level="1">Gestión de Sectores</flux:heading>
            <flux:subheading size="lg">Administra los sectores del Estado Lara (crear, editar, eliminar).
            </flux:subheading>
        </div>
        <div>
            <flux:button wire:click="create" icon="plus"
                class="!bg-lime-500 !text-zinc-900 border-none hover:!bg-lime-400 font-bold">
                Nuevo Sector
            </flux:button>
        </div>
    </div>

    {{-- Tabla --}}
    <flux:card class="shadow-sm mb-6">
        <div class="mb-4">
            <flux:input wire:model.live="search" icon="magnifying-glass"
                placeholder="Buscar por sector, parroquia o municipio..." class="w-full md:w-1/3" />
        </div>

        <div class="overflow-x-auto rounded-lg border border-zinc-200 dark:border-zinc-700">
            <table class="w-full text-sm text-left text-zinc-600 dark:text-zinc-400">
                <thead
                    class="bg-zinc-50 dark:bg-zinc-800/50 text-xs uppercase font-semibold text-zinc-700 dark:text-zinc-300 border-b border-zinc-200 dark:border-zinc-700">
                    <tr class="text-center">
                        <th class="px-4 py-3 w-12">#</th>
                        <th class="px-4 py-3 text-left">Sector</th>
                        <th class="px-4 py-3">Parroquia</th>
                        <th class="px-4 py-3">Municipio</th>
                        <th class="px-4 py-3">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                    @forelse($sectores as $sector)
                        <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800 transition-colors text-center">
                            <td class="px-4 py-3 font-medium text-zinc-500">
                                {{ ($sectores->currentPage() - 1) * $sectores->perPage() + $loop->iteration }}
                            </td>
                            <td class="px-4 py-3 text-left font-medium text-zinc-800 dark:text-zinc-100">
                                {{ $sector->nombre }}
                            </td>
                            <td class="px-4 py-3">
                                <flux:badge size="sm" color="blue">
                                    {{ $sector->parroquia->nombre }}
                                </flux:badge>
                            </td>
                            <td class="px-4 py-3">
                                <flux:badge size="sm" color="zinc">
                                    {{ $sector->municipio->nombre }}
                                </flux:badge>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-center gap-2">
                                    <flux:button wire:click="show({{ $sector->id }})" size="sm" variant="ghost"
                                        icon="eye" class="text-zinc-500 hover:text-blue-500" />

                                    <flux:button wire:click="edit({{ $sector->id }})" size="sm" variant="ghost"
                                        icon="pencil-square" class="text-zinc-500 hover:text-amber-500" />

                                    <flux:button
                                        @click="confirmAction($wire, {{ $sector->id }}, 'delete', '¿Eliminar sector?', 'Esta acción no se puede deshacer.', 'warning', 'Sí, eliminar')"
                                        size="sm" variant="ghost" icon="trash"
                                        class="text-zinc-500 hover:text-red-500" />
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-zinc-500">
                                No se encontraron sectores.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $sectores->links() }}
        </div>
    </flux:card>

    {{-- Modal Crear / Editar --}}
    @if ($isModalOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm" wire:key="sector-modal-{{ $sector_id ?? 'new' }}">
        <div class="bg-white dark:bg-zinc-900 w-full max-w-md p-6 rounded-xl shadow-xl flex flex-col space-y-4 mx-4">
            <div>
                <flux:heading size="lg">{{ $sector_id ? 'Editar Sector' : 'Nuevo Sector' }}</flux:heading>
                <flux:subheading>Ingresa los datos del sector a continuación.</flux:subheading>
            </div>

            <form wire:submit="store" class="space-y-4">
                {{-- Municipio --}}
                <flux:select wire:model.live="municipio_id" label="Municipio *" placeholder="Selecciona un municipio" required wire:key="select-municipio">
                    @foreach($municipios as $municipio)
                        <flux:select.option value="{{ $municipio->id }}" wire:key="mun-{{ $municipio->id }}">{{ $municipio->nombre }}</flux:select.option>
                    @endforeach
                </flux:select>
                @error('municipio_id')
                    <span class="text-sm text-red-500 font-medium">{{ $message }}</span>
                @enderror

                {{-- Parroquia --}}
                <flux:select wire:model="parroquia_id" label="Parroquia *"
                    placeholder="{{ $municipio_id ? 'Selecciona una parroquia' : 'Primero selecciona un municipio' }}"
                    :disabled="!$municipio_id" required wire:key="select-parroquia-{{ $municipio_id ?? 'none' }}">
                    @foreach($parroquiasFiltradas as $parroquia)
                        <flux:select.option value="{{ $parroquia->id }}" wire:key="par-{{ $parroquia->id }}">{{ $parroquia->nombre }}</flux:select.option>
                    @endforeach
                </flux:select>
                @error('parroquia_id')
                    <span class="text-sm text-red-500 font-medium">{{ $message }}</span>
                @enderror

                {{-- Nombre del sector --}}
                <flux:input wire:model="nombre" label="Nombre del Sector *"
                    placeholder="Ej. CENTRO" required class="uppercase" />

                <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-zinc-200 dark:border-zinc-800">
                    <flux:button wire:click="closeModal" variant="ghost">Cancelar</flux:button>
                    <flux:button type="submit"
                        class="!bg-lime-500 !text-zinc-900 border-none hover:!bg-lime-400 font-bold shadow-md">
                        <span wire:loading.remove wire:target="store">Guardar</span>
                        <span wire:loading wire:target="store">Guardando...</span>
                    </flux:button>
                </div>
            </form>
        </div>
    </div>
    @endif

    {{-- Modal Ver --}}
    @if ($isViewModalOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
        <div class="bg-white dark:bg-zinc-900 w-full max-w-sm p-6 rounded-xl shadow-xl flex flex-col space-y-4 mx-4">
            <div>
                <flux:heading size="lg">Detalle del Sector</flux:heading>
            </div>

            <div class="space-y-4">
                <div>
                    <span class="block text-sm font-medium text-zinc-500 font-semibold uppercase tracking-wide">Municipio</span>
                    <span class="block text-base font-medium text-zinc-800 dark:text-zinc-100">{{ $view_municipio }}</span>
                </div>
                <div>
                    <span class="block text-sm font-medium text-zinc-500 font-semibold uppercase tracking-wide">Parroquia</span>
                    <span class="block text-base font-medium text-zinc-800 dark:text-zinc-100">{{ $view_parroquia }}</span>
                </div>
                <div>
                    <span class="block text-sm font-medium text-zinc-500 font-semibold uppercase tracking-wide">Sector</span>
                    <span class="block text-lg font-semibold text-lime-600 dark:text-lime-400">{{ $view_nombre }}</span>
                </div>
            </div>

            <div class="flex justify-end pt-4 border-t border-zinc-200 dark:border-zinc-800">
                <flux:button wire:click="closeModal" variant="ghost">Cerrar</flux:button>
            </div>
        </div>
    </div>
    @endif
</div>

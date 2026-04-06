<div>
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <flux:heading size="xl" level="1">Gestión de Roles</flux:heading>
            <flux:subheading size="lg">Administra los roles del sistema (crear, editar, ver, eliminar).
            </flux:subheading>
        </div>
        <div>
            <flux:button wire:click="create" icon="plus"
                class="!bg-lime-500 !text-zinc-900 border-none hover:!bg-lime-400 font-bold">
                Nuevo Rol
            </flux:button>
        </div>
    </div>

    <flux:card class="shadow-sm mb-6">
        <div class="mb-4">
            <flux:input wire:model.live="search" icon="magnifying-glass" placeholder="Buscar rol..."
                class="w-full md:w-1/3" />
        </div>

        <div class="overflow-x-auto rounded-lg border border-zinc-200 dark:border-zinc-700">
            <table class="w-full text-sm text-left text-zinc-600 dark:text-zinc-400">
                <thead
                    class="bg-zinc-50 dark:bg-zinc-800/50 text-xs uppercase font-semibold text-zinc-700 dark:text-zinc-300 border-b border-zinc-200 dark:border-zinc-700">
                    <tr style="text-align: center;">
                        <th class="px-4 py-3">#</th>
                        <th class="px-4 py-3">Nombre</th>
                        <th class="px-4 py-3">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                    @forelse($roles as $role)
                        <tr style="text-align: center;"
                            class="hover:bg-zinc-50 dark:hover:bg-zinc-800 transition-colors">
                            <td class="px-4 py-3 font-medium">
                                {{ ($roles->currentPage() - 1) * $roles->perPage() + $loop->iteration }}</td>
                            <td class="px-4 py-3">{{ $role->name }}</td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex items-center justify-center gap-2">
                                    <flux:button wire:click="show({{ $role->id }})" size="sm" variant="ghost"
                                        icon="eye" class="text-zinc-500 hover:text-blue-500" />
                                    <flux:button wire:click="edit({{ $role->id }})" size="sm" variant="ghost"
                                        icon="pencil-square" class="text-zinc-500 hover:text-amber-500" />
                                    <flux:button @click="confirmDelete($wire, {{ $role->id }})" size="sm"
                                        variant="ghost" icon="trash" class="text-zinc-500 hover:text-red-500" />
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-4 py-8 text-center text-zinc-500">
                                No se encontraron roles.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $roles->links() }}
        </div>
    </flux:card>

    <!-- Modal para Crear/Editar -->
    @if ($isModalOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
            <div class="bg-white dark:bg-zinc-900 w-full max-w-md p-6 rounded-xl shadow-xl flex flex-col space-y-4">
                <div>
                    <flux:heading size="lg">{{ $role_id ? 'Editar Rol' : 'Crear Rol' }}</flux:heading>
                    <flux:subheading>Ingresa el nombre del rol a continuación.</flux:subheading>
                </div>
                <form wire:submit="store" class="space-y-4">
                    <flux:input wire:model="name" class="uppercase" label="Nombre del Rol"
                        placeholder="Ej. ADMINISTRADOR" required />
                    <div class="flex justify-end gap-3 mt-6">
                        <flux:button wire:click="closeModal" variant="ghost">Cancelar</flux:button>
                        <flux:button type="submit"
                            class="!bg-lime-500 !text-zinc-900 border-none hover:!bg-lime-400 font-bold">
                            Guardar
                        </flux:button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Modal para Ver -->
    @if ($isViewModalOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
            <div class="bg-white dark:bg-zinc-900 w-full max-w-sm p-6 rounded-xl shadow-xl flex flex-col space-y-4">
                <div>
                    <flux:heading size="lg">Detalles del Rol</flux:heading>
                </div>
                <div class="space-y-4">
                    <div>
                        <span class="block text-sm font-medium text-zinc-500">N°</span>
                        <span class="block text-base text-zinc-900 dark:text-white">{{ $role_id }}</span>
                    </div>
                    <div>
                        <span class="block text-sm font-medium text-zinc-500">Nombre del Rol</span>
                        <span class="block text-base text-zinc-900 dark:text-white">{{ $name }}</span>
                    </div>
                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <flux:button wire:click="closeModal" variant="ghost">Cerrar</flux:button>
                </div>
            </div>
        </div>
    @endif
</div>

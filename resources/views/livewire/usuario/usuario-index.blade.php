<div>
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <flux:heading size="xl" level="1">Gestión de Usuarios</flux:heading>
            <flux:subheading size="lg">Administra los usuarios del sistema (crear, editar, activar/desactivar).
            </flux:subheading>
        </div>
        <div>
            <flux:button wire:click="create" icon="plus"
                class="!bg-lime-500 !text-zinc-900 border-none hover:!bg-lime-400 font-bold">
                Nuevo Usuario
            </flux:button>
        </div>
    </div>

    <flux:card class="shadow-sm mb-6">
        <div class="mb-4">
            <flux:input wire:model.live="search" icon="magnifying-glass" placeholder="Buscar usuario..."
                class="w-full md:w-1/3" />
        </div>

        <div class="overflow-x-auto rounded-lg border border-zinc-200 dark:border-zinc-700">
            <table class="w-full text-sm text-left text-zinc-600 dark:text-zinc-400">
                <thead
                    class="bg-zinc-50 dark:bg-zinc-800/50 text-xs uppercase font-semibold text-zinc-700 dark:text-zinc-300 border-b border-zinc-200 dark:border-zinc-700">
                    <tr style="text-align: center">
                        <th class="px-4 py-3 w-12">#</th>
                        <th class="px-4 py-3">Usuario</th>
                        <th class="px-4 py-3">Rol</th>
                        <th class="px-4 py-3">Teléfono</th>
                        <th class="px-4 py-3">Estado</th>
                        <th class="px-4 py-3">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                    @forelse($users as $user)
                        <tr style="text-align: center"
                            class="hover:bg-zinc-50 dark:hover:bg-zinc-800 transition-colors {{ !$user->is_active ? 'opacity-60 bg-zinc-50 dark:bg-zinc-800' : '' }}">
                            <td class="px-4 py-3 font-medium text-zinc-500">
                                {{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}</td>
                            <td class="px-4 py-3 flex items-center gap-3">
                                <div class="relative">
                                    <flux:profile
                                        avatar="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&color=0c0f14&background=a3e635"
                                        name="{{ $user->name }}" description="{{ $user->email }}" />
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                @forelse($user->roles as $rol)
                                    <flux:badge size="sm" color="zinc">{{ $rol->name }}</flux:badge>
                                @empty
                                    <span class="text-xs text-zinc-400">Sin Rol</span>
                                @endforelse
                            </td>
                            <td class="px-4 py-3">{{ $user->telefono ?? 'N/A' }}</td>
                            <td class="px-4 py-3">
                                @if ($user->is_active)
                                    <flux:badge size="sm" color="green" icon="check-circle">Activo</flux:badge>
                                @else
                                    <flux:badge size="sm" color="red" icon="x-circle">Inactivo</flux:badge>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <flux:button wire:click="show({{ $user->id }})" size="sm" variant="ghost"
                                        icon="eye" class="text-zinc-500 hover:text-blue-500" />
                                    <flux:button wire:click="edit({{ $user->id }})" size="sm" variant="ghost"
                                        icon="pencil-square" class="text-zinc-500 hover:text-amber-500" />

                                    @if ($user->is_active)
                                        <flux:button
                                            @click="confirmAction($wire, {{ $user->id }}, 'toggleStatus', '¿Desactivar usuario?', 'El usuario no podrá ingresar al sistema hasta que sea activado nuevamente.', 'warning', 'Sí, desactivar')"
                                            size="sm" variant="ghost" icon="no-symbol"
                                            class="text-zinc-500 hover:text-red-500" title="Desactivar" />
                                    @else
                                        <flux:button
                                            @click="confirmAction($wire, {{ $user->id }}, 'toggleStatus', '¿Restaurar usuario?', 'El usuario recuperará el acceso al sistema inmediatamente.', 'info', 'Sí, restaurar')"
                                            size="sm" variant="ghost" icon="arrow-path"
                                            class="text-zinc-500 hover:text-green-500" title="Restaurar" />
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-zinc-500">
                                No se encontraron usuarios.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </flux:card>

    <!-- Modal para Crear/Editar -->
    @if ($isModalOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
        <div class="bg-white dark:bg-zinc-900 w-full max-w-2xl p-6 rounded-xl shadow-xl flex flex-col space-y-4 mx-4 max-h-[90vh] overflow-y-auto">
        <div class="space-y-4">
            <div>
                <flux:heading size="lg">{{ $user_id ? 'Editar Usuario' : 'Crear Usuario' }}</flux:heading>
                <flux:subheading>Completa los campos del formulario para gestionar el acceso.</flux:subheading>
            </div>

            <form wire:submit="store" class="space-y-4">


                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <flux:input wire:model="name" class="uppercase" label="Nombre Completo *"
                        placeholder="JUAN PÉREZ" required />

                    <flux:input wire:model="telefono" label="Teléfono" placeholder="+1 234 567 89" />

                    <flux:input wire:model="email" type="email" label="Correo Electrónico *"
                        placeholder="ejemplo@correo.com" required />

                    <flux:select wire:model="role_id" label="Rol *" required>
                        <flux:select.option value="" disabled>Selecciona un rol</flux:select.option>
                        @foreach ($roles as $rol)
                            <flux:select.option value="{{ $rol->id }}">{{ $rol->name }}</flux:select.option>
                        @endforeach
                    </flux:select>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <flux:input wire:model="password" type="password"
                        label="{{ $user_id ? 'Nueva Contraseña' : 'Contraseña *' }}" placeholder="••••••••" viewable
                        :required="!$user_id" />
                    <flux:input wire:model="password_confirmation" type="password"
                        label="{{ $user_id ? 'Confirmar Nueva Contraseña' : 'Confirmar Contraseña *' }}"
                        placeholder="••••••••" viewable :required="!$user_id" />
                </div>

                <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-zinc-200 dark:border-zinc-800">
                    <flux:button wire:click="closeModal" variant="ghost">Cancelar</flux:button>
                    <flux:button type="submit"
                        class="!bg-lime-500 !text-zinc-900 border-none hover:!bg-lime-400 font-bold shadow-md">
                        <span wire:loading.remove wire:target="store">Guardar Usuario</span>
                        <span wire:loading wire:target="store">Guardando...</span>
                    </flux:button>
                </div>
            </form>
        </div>
    </div>
    </div>
    @endif

    <!-- Modal para Ver -->
    @if ($isViewModalOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
        <div class="bg-white dark:bg-zinc-900 w-full max-w-md p-6 rounded-xl shadow-xl flex flex-col space-y-4 mx-4">
        <div class="space-y-6">
            <div class="flex items-center gap-4">
                <div
                    class="w-20 h-20 rounded-full overflow-hidden border-2 border-zinc-200 dark:border-zinc-700 shadow-sm flex-shrink-0">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($name) }}&color=0c0f14&background=a3e635"
                        class="w-full h-full object-cover">
                </div>
                <div class="overflow-hidden">
                    <flux:heading size="lg" class="truncate">{{ $name }}</flux:heading>
                    <div class="text-sm text-zinc-500 truncate">{{ $email }}</div>
                </div>
            </div>

            <div class="space-y-4 pt-4 border-t border-zinc-200 dark:border-zinc-800">
                <div>
                    <span class="block text-sm font-medium text-zinc-500">Rol</span>
                    <span class="block mt-1">
                        @php $user = \App\Models\User::find($user_id); @endphp
                        @if ($user && $user->roles->count())
                            <flux:badge size="sm" color="zinc">{{ $user->roles->first()->name }}</flux:badge>
                        @else
                            <span class="text-zinc-400 text-sm">Sin rol asignado</span>
                        @endif
                    </span>
                </div>
                <div>
                    <span class="block text-sm font-medium text-zinc-500">Teléfono</span>
                    <span
                        class="block text-base text-zinc-900 dark:text-white">{{ $telefono ?? 'No registrado' }}</span>
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-6">
                <flux:button wire:click="closeModal" variant="ghost">Cerrar</flux:button>
            </div>
        </div>
    </div>
    </div>
    @endif
</div>

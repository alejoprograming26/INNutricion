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
                                        avatar="{{ $user->foto_perfil ? asset('storage/' . $user->foto_perfil) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&color=0c0f14&background=a3e635' }}"
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
                <flux:subheading>Completa los campos. Usa una foto de perfil profesional si es posible.
                </flux:subheading>
            </div>

            <form wire:submit="store" class="space-y-4">
                <!-- Foto de perfil -->
                <div class="flex flex-col items-center justify-center gap-4 py-4">
                    <div
                        class="relative w-28 h-28 rounded-full overflow-hidden border-4 border-zinc-100 dark:border-zinc-800 shadow-md flex-shrink-0">
                        @if ($new_foto_perfil)
                            <img src="{{ $new_foto_perfil->temporaryUrl() }}" class="w-full h-full object-cover">
                        @elseif ($foto_perfil)
                            <img src="{{ asset('storage/' . $foto_perfil) }}" class="w-full h-full object-cover">
                        @else
                            <div
                                class="w-full h-full bg-zinc-200 dark:bg-zinc-800 flex items-center justify-center text-zinc-400">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-12 h-12">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                </svg>
                            </div>
                        @endif

                        <div wire:loading wire:target="new_foto_perfil"
                            class="absolute inset-0 bg-black/50 flex items-center justify-center text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-6 h-6 animate-spin">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                            </svg>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <label for="dropzone-file"
                            class="flex flex-col items-center justify-center px-4 py-2 border border-zinc-300 rounded-lg cursor-pointer bg-white text-zinc-700 text-sm font-medium hover:bg-zinc-50 dark:hover:bg-zinc-800 dark:bg-zinc-900 dark:border-zinc-700 dark:text-zinc-300 shadow-sm transition-colors">
                            <div class="flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5" />
                                </svg>
                                <span>Subir foto</span>
                            </div>
                            <input id="dropzone-file" wire:model.live="new_foto_perfil" type="file"
                                class="hidden" accept="image/*" />
                        </label>

                        @if ($foto_perfil || $new_foto_perfil)
                            <button type="button" wire:click="removePhoto"
                                class="px-4 py-2 text-sm font-medium text-red-600 bg-red-50 hover:bg-red-100 dark:bg-red-900/20 dark:hover:bg-red-900/40 dark:text-red-400 rounded-lg transition-colors flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                </svg>
                                Eliminar
                            </button>
                        @endif
                    </div>
                    @error('new_foto_perfil')
                        <span class="text-sm text-red-500 font-medium">{{ $message }}</span>
                    @enderror
                </div>

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
                    @if ($foto_perfil)
                        <img src="{{ asset('storage/' . $foto_perfil) }}" class="w-full h-full object-cover">
                    @else
                        <div
                            class="w-full h-full bg-zinc-200 dark:bg-zinc-800 flex items-center justify-center text-zinc-400">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-10 h-10">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                            </svg>
                        </div>
                    @endif
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

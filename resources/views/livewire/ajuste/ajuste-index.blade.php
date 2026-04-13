<div>
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <flux:heading size="xl" level="1">Configuración del Sistema</flux:heading>
            <flux:subheading size="lg">Administra la información general, sucursal, e imágenes de tu plataforma.</flux:subheading>
        </div>
    </div>

    <!-- El mensaje de éxito ahora se maneja globalmente con SweetAlert2 en el layout principal y dispatch de Livewire -->

    <form wire:submit="save">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- Sección de Datos Generales -->
            <flux:card class="shadow-sm">
                <flux:heading size="lg" class="mb-4 text-zinc-800 dark:text-white flex items-center">
                    <flux:icon.building-office class="size-5 mr-2 text-zinc-400" />
                    Datos Generales
                </flux:heading>
                
                <div class="space-y-4">
                    <flux:input wire:model="nombre" label="Nombre del Sistema" placeholder="Ej. INNutricion" />
                    <flux:input wire:model="descripcion" label="Descripción Breve" placeholder="Clínica Nutricional especializada" />
                    <flux:input wire:model="sucursal" label="Nombre de Sucursal" placeholder="Sucursal Principal - Centro" />
                    <flux:textarea wire:model="direccion" label="Dirección Física" rows="3" placeholder="Av. Principal..." />
                </div>
            </flux:card>

            <!-- Sección de Contacto y Configuración -->
            <flux:card class="shadow-sm">
                <flux:heading size="lg" class="mb-4 text-zinc-800 dark:text-white flex items-center">
                    <flux:icon.phone class="size-5 mr-2 text-zinc-400" />
                    Contacto y Configuración
                </flux:heading>
                
                <div class="space-y-4">
                    <flux:input wire:model="telefonos" label="Teléfonos de Contacto" placeholder="+58 414..." />
                    <flux:input wire:model="email" type="email" label="Correo Electrónico Principal" placeholder="contacto@innutricion.com" />
                    <flux:input wire:model="pagina_web" type="url" label="Página Web (Opcional)" placeholder="https://innutricion.com" />
                </div>
            </flux:card>

            <!-- Sección de Imágenes (Subida con Preview Profesional) -->
            <flux:card class="md:col-span-2 shadow-sm">
                <flux:heading size="lg" class="mb-4 text-zinc-800 dark:text-white flex items-center">
                    <flux:icon.photo class="size-5 mr-2 text-zinc-400" />
                    Imágenes y Branding
                </flux:heading>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    
                    <!-- Previsualizador de Logotipo -->
                    <div class="border flex flex-col p-6 border-zinc-200 dark:border-zinc-700 rounded-xl bg-zinc-50/50 dark:bg-zinc-800/30">
                        <flux:heading size="sm" class="mb-3 font-medium text-zinc-700 dark:text-zinc-300">Logotipo Institucional</flux:heading>
                        
                        <div class="relative w-full max-w-[200px] h-40 mx-auto mb-4 rounded-xl flex items-center justify-center bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 overflow-hidden shadow-sm">
                            @if ($logo && is_object($logo))
                                <img wire:key="temp-logo-{{ $logo->temporaryUrl() }}" src="{{ $logo->temporaryUrl() }}" class="object-contain w-full h-full p-2 transition-transform hover:scale-105 duration-300">
                            @elseif ($logo_actual)
                                <img wire:key="actual-logo-{{ $logo_actual }}"
                                    src="{{ asset('storage/' . $logo_actual) }}?v={{ \App\Models\Ajuste::first()->updated_at->timestamp ?? '1' }}"
                                    class="object-contain w-full h-full p-2 transition-transform hover:scale-105 duration-300">
                            @else
                                <div class="flex flex-col items-center">
                                    <flux:icon.photo class="size-10 text-zinc-200 dark:text-zinc-700 mb-2" />
                                    <span class="text-xs text-zinc-400">Sin Logotipo</span>
                                </div>
                            @endif
                            
                            <div wire:loading wire:target="logo" class="absolute inset-0 bg-white/60 dark:bg-black/60 flex items-center justify-center backdrop-blur-sm">
                                <flux:icon.arrow-path class="size-6 text-zinc-600 dark:text-zinc-300 animate-spin" />
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-center w-full">
                            <label class="flex flex-col items-center justify-center w-full h-24 border-2 border-zinc-300 border-dashed rounded-xl cursor-pointer bg-white dark:bg-zinc-800 hover:bg-zinc-50 dark:border-zinc-600 dark:hover:border-zinc-500 dark:hover:bg-zinc-700 transition">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <flux:icon.arrow-up-tray class="w-6 h-6 mb-2 text-zinc-400 dark:text-zinc-500" />
                                    <p class="text-sm text-zinc-600 dark:text-zinc-400 font-medium">Subir nuevo logo</p>
                                </div>
                                <input type="file" wire:model="logo" class="hidden" accept="image/png, image/jpeg, image/jpg, image/svg+xml" />
                            </label>
                        </div>
                        @error('logo') <span class="text-red-500 text-sm mt-2 block text-center font-medium">{{ $message }}</span> @enderror
                    </div>

                    <!-- Previsualizador de Imagen de Login -->
                    <div class="border flex flex-col p-6 border-zinc-200 dark:border-zinc-700 rounded-xl bg-zinc-50/50 dark:bg-zinc-800/30">
                        <flux:heading size="sm" class="mb-3 font-medium text-zinc-700 dark:text-zinc-300">Banner para Pantalla de Login</flux:heading>
                        
                        <div class="relative w-full h-40 mb-4 rounded-xl flex items-center justify-center bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 overflow-hidden shadow-sm">
                            @if ($imagen_login && is_object($imagen_login))
                                <img wire:key="temp-login-{{ $imagen_login->temporaryUrl() }}" src="{{ $imagen_login->temporaryUrl() }}" class="object-cover w-full h-full transition-opacity opacity-90 hover:opacity-100 duration-300">
                            @elseif ($imagen_login_actual)
                                <img wire:key="actual-login-{{ $imagen_login_actual }}"
                                    src="{{ asset('storage/' . $imagen_login_actual) }}?v={{ \App\Models\Ajuste::first()->updated_at->timestamp ?? '1' }}"
                                    class="object-cover w-full h-full transition-opacity opacity-90 hover:opacity-100 duration-300">
                            @else
                                <div class="flex flex-col items-center">
                                    <flux:icon.photo class="size-10 text-zinc-200 dark:text-zinc-700 mb-2" />
                                    <span class="text-xs text-zinc-400">Sin Banner</span>
                                </div>
                            @endif
                            
                            <div wire:loading wire:target="imagen_login" class="absolute inset-0 bg-white/60 dark:bg-black/60 flex items-center justify-center backdrop-blur-sm">
                                <flux:icon.arrow-path class="size-6 text-zinc-600 dark:text-zinc-300 animate-spin" />
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-center w-full">
                            <label class="flex flex-col items-center justify-center w-full h-24 border-2 border-zinc-300 border-dashed rounded-xl cursor-pointer bg-white dark:bg-zinc-800 hover:bg-zinc-50 dark:border-zinc-600 dark:hover:border-zinc-500 dark:hover:bg-zinc-700 transition">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <flux:icon.arrow-up-tray class="w-6 h-6 mb-2 text-zinc-400 dark:text-zinc-500" />
                                    <p class="text-sm text-zinc-600 dark:text-zinc-400 font-medium">Subir nuevo banner</p>
                                </div>
                                <input type="file" wire:model="imagen_login" class="hidden" accept="image/png, image/jpeg, image/jpg" />
                            </label>
                        </div>
                        @error('imagen_login') <span class="text-red-500 text-sm mt-2 block text-center font-medium">{{ $message }}</span> @enderror
                    </div>

                </div>

            </flux:card>
        </div>

        <div class="flex items-center justify-end mt-8 border-t border-zinc-200 dark:border-zinc-700 pt-6">
            <flux:button type="submit" icon="check-circle" class="w-full sm:w-auto px-8 py-2.5 !bg-lime-500 !text-zinc-900 border-none hover:!bg-lime-400 dark:!bg-lime-500 dark:hover:!bg-lime-400 font-bold transition-colors shadow-sm">
                <span wire:loading.remove wire:target="save, logo, imagen_login">Guardar Configuración</span>
                <span wire:loading wire:target="save, logo, imagen_login">Procesando...</span>
            </flux:button>
        </div>
    </form>
</div>

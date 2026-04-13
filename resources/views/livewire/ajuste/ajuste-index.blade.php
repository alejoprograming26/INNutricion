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


        </div>

        <div class="flex items-center justify-end mt-8 border-t border-zinc-200 dark:border-zinc-700 pt-6">
            <flux:button type="submit" icon="check-circle" class="w-full sm:w-auto px-8 py-2.5 !bg-lime-500 !text-zinc-900 border-none hover:!bg-lime-400 dark:!bg-lime-500 dark:hover:!bg-lime-400 font-bold transition-colors shadow-sm">
                <span wire:loading.remove wire:target="save, logo, imagen_login">Guardar Configuración</span>
                <span wire:loading wire:target="save, logo, imagen_login">Procesando...</span>
            </flux:button>
        </div>
    </form>
</div>

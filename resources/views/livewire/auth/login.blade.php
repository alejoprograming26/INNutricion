<flux:card class="space-y-6 shadow-xl shadow-zinc-200/50 dark:shadow-none border border-zinc-200/60 dark:border-zinc-800 rounded-2xl relative overflow-hidden bg-white dark:bg-zinc-900">
    <div class="absolute top-0 left-0 w-full h-1.5" style="background: linear-gradient(90deg, #a3e635, #65a30d);"></div>

    <div class="text-center pt-3">
        <flux:heading size="xl" class="font-extrabold text-zinc-800 dark:text-zinc-100">Bienvenido de vuelta</flux:heading>
        <flux:subheading class="mt-1.5 text-zinc-500 dark:text-zinc-400">Ingresa tus credenciales para acceder a tu área de trabajo.</flux:subheading>
    </div>

    <form wire:submit="login" class="space-y-6">
        <flux:input 
            wire:model="email" 
            label="Correo Electrónico" 
            type="email" 
            placeholder="tu@correo.com" 
            icon="envelope" 
            required 
            autofocus 
        />

        <div>
            <flux:input 
                wire:model="password" 
                label="Contraseña" 
                type="password" 
                placeholder="••••••••" 
                icon="lock-closed" 
                required 
                viewable
            />
            <div class="flex justify-end mt-2">
                <flux:link href="#" variant="subtle" class="text-sm">¿Olvidaste tu contraseña?</flux:link>
            </div>
        </div>

        <flux:checkbox wire:model="remember" label="Recordarme" />

        <flux:button type="submit" variant="primary" class="w-full !bg-lime-500 hover:!bg-lime-600 !text-zinc-900 border-none font-bold py-2.5 shadow-md shadow-lime-500/20 transition-all">
            <span wire:loading.remove wire:target="login">Iniciar Sesión</span>
            <span wire:loading wire:target="login">Verificando...</span>
        </flux:button>
    </form>
</flux:card>

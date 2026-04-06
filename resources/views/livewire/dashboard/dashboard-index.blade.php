<div>
    <div class="flex items-center justify-between mb-8">
        <div>
            <flux:heading size="xl" level="1">¡Hola, {{ auth()->user()->name }}! 👋</flux:heading>
            <flux:subheading size="lg" class="mt-1 text-zinc-500 dark:text-zinc-400">
                Aquí tienes el resumen de tu clínica virtual para el día de hoy, <span
                    class="font-medium text-zinc-900 dark:text-white">{{ now()->translatedFormat('l j \d\e F') }}</span>.
            </flux:subheading>
        </div>
    </div>
</div>

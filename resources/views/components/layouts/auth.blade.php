<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="{{ $ajuste->nombre ?? 'INNutricion' }} — Inicia sesión en tu plataforma de nutrición">
    <title>{{ $title ?? 'Iniciar Sesión' }} — {{ $ajuste->nombre ?? 'INNutricion' }}</title>
    @if ($ajuste && $ajuste->logo)
        <link rel="icon" type="image/x-icon" href="{{ asset('storage/' . $ajuste->logo) }}?v={{ time() }}">
        <link rel="shortcut icon" href="{{ asset('storage/' . $ajuste->logo) }}?v={{ time() }}">
    @endif

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia(
                '(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark')
        } else {
            document.documentElement.classList.remove('dark')
        }
    </script>
    </script>
</head>

<body class="min-h-screen font-sans antialiased text-zinc-900 dark:text-zinc-100 bg-zinc-50 dark:bg-zinc-900">
    <div class="flex min-h-screen w-full">
        <!-- Form Container -->
        <div
            class="w-full md:w-1/2 flex flex-col justify-between items-center p-8 sm:p-12 md:p-16 lg:p-24 bg-zinc-50 dark:bg-zinc-950 z-10 w-full">
            <div class="w-full max-w-md my-auto">
                <div class="flex flex-col justify-center items-center mb-10 text-center space-y-4">
                    @if ($ajuste && $ajuste->logo)
                        <img src="{{ asset('storage/' . $ajuste->logo) }}?v={{ time() }}" alt="Logo"
                            class="h-20 w-auto object-contain transition-transform hover:scale-105 duration-500 filter drop-shadow-md">
                    @else
                        <div class="size-20 rounded-2xl flex items-center justify-center font-black text-4xl shadow-lg shadow-lime-500/20 text-zinc-900 transition-transform hover:scale-105 duration-500"
                            style="background: linear-gradient(135deg, #a3e635, #65a30d);">
                            IN
                        </div>
                    @endif
                    <h1 class="text-3xl font-extrabold tracking-tight text-zinc-900 dark:text-white">
                        {{ $ajuste->nombre ?? 'INNutricion' }}</h1>
                </div>

                <div class="w-full">
                    {{ $slot }}
                </div>
            </div>

            <div class="mt-8 text-center text-sm text-zinc-500 dark:text-zinc-500 font-medium">
                &copy; {{ date('Y') }} {{ $ajuste->nombre ?? 'INNutricion' }}
            </div>
        </div>

        <!-- Image Container -->
        <div class="hidden md:flex md:w-1/2 relative bg-zinc-100 dark:bg-zinc-800 bg-cover bg-center"
            @if ($ajuste && $ajuste->imagen_login) style="background-image: url('{{ asset('storage/' . $ajuste->imagen_login) }}?v={{ time() }}');" @endif>

            <div class="absolute inset-0 bg-zinc-900/20"></div>

            @if (!$ajuste || !$ajuste->imagen_login)
                <div
                    class="absolute inset-0 bg-gradient-to-br from-lime-400 to-lime-600 opacity-90 flex items-center justify-center">
                    <div class="text-white opacity-20 size-64 rounded-full blur-3xl"
                        style="background: linear-gradient(135deg, #a3e635, #65a30d);"></div>
                </div>
            @endif
        </div>
    </div>

    @livewireScripts
    @fluxScripts
</body>

</html>

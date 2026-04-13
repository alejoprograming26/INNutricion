<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="{{ $ajuste->nombre ?? 'INNutricion' }} — Inicia sesión en tu plataforma de nutrición">
    <title>{{ $title ?? 'Iniciar Sesión' }} — {{ $ajuste->nombre ?? 'INNutricion' }}</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/logo.png') }}">
    <link rel="shortcut icon" href="{{ asset('assets/logo.png') }}">

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
</head>

<body class="min-h-screen font-sans antialiased text-zinc-900 dark:text-zinc-100 bg-zinc-50 dark:bg-zinc-900">
    <div class="flex min-h-screen w-full">
        <!-- Form Container -->
        <div
            class="w-full md:w-1/2 flex flex-col justify-between items-center p-8 sm:p-12 md:p-16 lg:p-24 bg-zinc-50 dark:bg-zinc-950 z-10 w-full">
            <div class="w-full max-md my-auto">
                <div class="flex flex-col justify-center items-center mb-10 text-center space-y-4">
                    <img src="{{ asset('assets/logo.png') }}" alt="Logo"
                        class="h-20 w-auto object-contain transition-transform hover:scale-105 duration-500 filter drop-shadow-md">
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
            style="background-image: url('{{ asset('assets/login-bg.jpg') }}');">

            <div class="absolute inset-0 bg-zinc-900/20"></div>
        </div>
    </div>

    @livewireScripts
    @fluxScripts
</body>

</html>

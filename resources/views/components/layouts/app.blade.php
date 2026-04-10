<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $title ?? ($ajuste->nombre ?? 'INNutricion') }}</title>
    @if ($ajuste && $ajuste->logo)
        <link rel="icon" type="image/x-icon" href="{{ asset('storage/' . $ajuste->logo) }}?v={{ time() }}">
        <link rel="shortcut icon" href="{{ asset('storage/' . $ajuste->logo) }}?v={{ time() }}">
    @endif


    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

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

<body class="min-h-screen bg-white dark:bg-zinc-900 lg:flex">

    <flux:sidebar sticky stashable
        class="max-h-screen sticky top-0 overflow-y-auto bg-zinc-50 dark:bg-zinc-900 border-r border-zinc-200 dark:border-zinc-700">
        <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

        <flux:brand href="/dashboard" class="px-4 py-2" name="{{ $ajuste->nombre ?? 'INNutricion' }}">
            <x-slot name="logo" class="!h-10 !w-auto !overflow-visible !min-w-0 !shrink-0">
                @if ($ajuste && $ajuste->logo)
                    <img src="{{ asset('storage/' . $ajuste->logo) }}?v={{ time() }}" alt="Logo"
                        class="h-10 w-auto object-contain rounded">
                @else
                    <div class="size-10 rounded-lg flex items-center justify-center font-extrabold text-base"
                        style="background: linear-gradient(135deg, #a3e635, #65a30d); color: #0c0f14; flex-shrink:0;">IN
                    </div>
                @endif
            </x-slot>
        </flux:brand>
        <flux:navlist variant="outline">
            <flux:navlist.item icon="home" href="/dashboard" :current="request()->routeIs('dashboard')">Panel Inicial
            </flux:navlist.item>
            <flux:navlist.item icon="user-group" href="{{ route('admin.roles.index') }}"
                :current="request()->routeIs('admin.roles.index')">Roles</flux:navlist.item>
            <flux:navlist.item icon="users" href="{{ route('admin.usuarios.index') }}"
                :current="request()->routeIs('admin.usuarios.index')">Usuarios</flux:navlist.item>
            <flux:navlist.item icon="map-pin" href="{{ route('admin.sectores.index') }}"
                :current="request()->routeIs('admin.sectores.index')">
                Sectores</flux:navlist.item>
            <flux:navlist.item icon="building-office-2" href="{{ route('admin.comunas.index') }}"
                :current="request()->routeIs('admin.comunas.index')">Comunas</flux:navlist.item>
            <flux:navlist.item icon="chart-bar" href="{{ route('admin.metas.index') }}"
                :current="request()->routeIs('admin.metas.index')">Metas</flux:navlist.item>

            <flux:sidebar.group expandable heading="Transcripciones" class="grid" icon="book-open">
                <flux:sidebar.item href="{{ route('admin.transcripciones.index', ['tipo' => 'VULNERABILIDAD']) }}"
                    :current="request()->routeIs('admin.transcripciones.index') && request()->get('tipo') === 'VULNERABILIDAD'">
                    Vulnerabilidad
                </flux:sidebar.item>
                <flux:sidebar.item href="{{ route('admin.transcripciones.index', ['tipo' => 'CPLV']) }}"
                    :current="request()->routeIs('admin.transcripciones.index') && request()->get('tipo') === 'CPLV'">
                    CPLV
                </flux:sidebar.item>
                <flux:sidebar.item href="{{ route('admin.transcripciones.index', ['tipo' => 'LACTANCIA MATERNA']) }}"
                    :current="request()->routeIs('admin.transcripciones.index') && request()->get('tipo') === 'LACTANCIA MATERNA'">
                    Lactancia Materna
                </flux:sidebar.item>
                <flux:sidebar.item href="{{ route('admin.transcripciones.index', ['tipo' => 'ENCUESTA DIETARIA']) }}"
                    :current="request()->routeIs('admin.transcripciones.index') && request()->get('tipo') === 'ENCUESTA DIETARIA'">
                    Encuesta Dietaria
                </flux:sidebar.item>
                <flux:sidebar.item href="{{ route('admin.transcripciones.index', ['tipo' => 'AJUSTES DE PRECIO']) }}"
                    :current="request()->routeIs('admin.transcripciones.index') && request()->get('tipo') === 'AJUSTES DE PRECIO'">
                    Ajuste de Precio
                </flux:sidebar.item>
                <flux:sidebar.item href="{{ route('admin.transcripciones.index', ['tipo' => 'SUGIMA']) }}"
                    :current="request()->routeIs('admin.transcripciones.index') && request()->get('tipo') === 'SUGIMA'">
                    Sujima
                </flux:sidebar.item>
            </flux:sidebar.group>
            <flux:navlist.item icon="cog-6-tooth" href="{{ route('admin.ajustes.index') }}"
                :current="request()->routeIs('admin.ajustes.index')">Ajustes
            </flux:navlist.item>

        </flux:navlist>
    </flux:sidebar>

    <div class="flex-1 flex flex-col min-w-0">
        <flux:header class="!block bg-zinc-50 dark:bg-zinc-900 border-b border-zinc-200 dark:border-zinc-700">
            <flux:navbar class="w-full">
                <flux:sidebar.toggle class="lg:hidden" icon="bars-3" />
                <flux:spacer />

                {{-- Dark Mode Toggle --}}
                <button x-data="{
                    theme: localStorage.theme || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'),
                    toggle() {
                        this.theme = this.theme === 'dark' ? 'light' : 'dark';
                        localStorage.theme = this.theme;
                        if (this.theme === 'dark') {
                            document.documentElement.classList.add('dark');
                        } else {
                            document.documentElement.classList.remove('dark');
                        }
                    },
                    init() {
                        if (this.theme === 'dark') {
                            document.documentElement.classList.add('dark');
                        } else {
                            document.documentElement.classList.remove('dark');
                        }
                        $watch('theme', val => {
                            if (val === 'dark') document.documentElement.classList.add('dark');
                            else document.documentElement.classList.remove('dark');
                        });
                    }
                }" @click="toggle()"
                    class="mr-4 p-2 rounded-full text-zinc-500 hover:text-zinc-700 hover:bg-zinc-100 dark:text-zinc-400 dark:hover:text-zinc-300 dark:hover:bg-zinc-800 transition-colors"
                    aria-label="Cambiar tema de color">
                    <svg x-show="theme === 'dark'" style="display: none;" xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" />
                    </svg>
                    <svg x-show="theme === 'light'" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z" />
                    </svg>
                </button>

                <flux:dropdown position="bottom-end" align="end">
                    <flux:profile
                        avatar="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'I N') }}&color=0c0f14&background=a3e635"
                        name="{{ auth()->user()->name ?? 'Dra. Nutricionista' }}" />

                    <flux:menu>
                        <flux:menu.item icon="user">Mi Perfil</flux:menu.item>
                        <flux:menu.separator />
                        <flux:menu.item icon="arrow-right-start-on-rectangle" href="{{ route('logout') }}">Cerrar
                            Sesión
                        </flux:menu.item>
                    </flux:menu>
                </flux:dropdown>
            </flux:navbar>
        </flux:header>

        <flux:main container>
            {{ $slot }}
        </flux:main>
    </div>

    @livewireScripts
    @fluxScripts

    @php
        $swalIcon = null;
        $swalMessage = null;
        foreach (['success', 'error', 'warning', 'info', 'question'] as $type) {
            if (session()->has($type)) {
                $swalIcon = $type;
                $swalMessage = session($type);
                break;
            }
        }
    @endphp

    @if ($swalMessage)
        <script type="module">
            document.addEventListener('DOMContentLoaded', function() {
                window.Toast.fire({
                    icon: '{{ $swalIcon }}',
                    title: '{{ $swalMessage }}'
                });
            });
        </script>
    @endif

    <script>
        window.confirmAction = function(wire, id, methodName, title, text, icon = 'warning', confirmButtonText =
            'Confirmar') {
            window.Swal.fire({
                title: title,
                text: text,
                icon: icon,
                showCancelButton: true,
                confirmButtonColor: '#84cc16', // lime-500
                cancelButtonColor: '#ef4444', // red-500
                confirmButtonText: confirmButtonText,
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    wire[methodName](id);
                }
            });
        }

        window.confirmDelete = function(wire, id, methodName = 'delete') {
            window.confirmAction(
                wire,
                id,
                methodName,
                '¿Estás seguro?',
                'Esta acción no se puede deshacer.',
                'warning',
                'Sí, eliminar'
            );
        }
    </script>
</body>

</html>

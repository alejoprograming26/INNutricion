<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>403 - No Autorizado | INNutricion</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <script>
        function applyTheme() {
            if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia(
                    '(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark')
            } else {
                document.documentElement.classList.remove('dark')
            }
        }
        applyTheme();
    </script>

    <style>
        /* Base Animation Keyframes */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(24px) scale(0.98);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0) rotate(0deg);
            }
            50% {
                transform: translateY(-8px) rotate(1deg);
            }
        }

        @keyframes floatReverse {
            0%, 100% {
                transform: translateY(0) scale(1);
            }
            50% {
                transform: translateY(6px) scale(1.05);
            }
        }

        @keyframes pulseGlow {
            0%, 100% {
                opacity: 0.15;
                transform: scale(1);
            }
            50% {
                opacity: 0.25;
                transform: scale(1.15);
            }
        }

        @keyframes blobOne {
            0%, 100% {
                transform: translate(0px, 0px) scale(1);
            }
            33% {
                transform: translate(40px, -40px) scale(1.2);
            }
            66% {
                transform: translate(-20px, 20px) scale(0.9);
            }
        }

        @keyframes blobTwo {
            0%, 100% {
                transform: translate(0px, 0px) scale(1);
            }
            50% {
                transform: translate(-30px, 50px) scale(1.15);
            }
        }

        /* Utility Classes */
        .animate-fade-in-up {
            opacity: 0;
            animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        .animate-float {
            animation: float 4s ease-in-out infinite;
        }

        .animate-float-reverse {
            animation: floatReverse 5s ease-in-out infinite;
        }

        .animate-pulse-glow {
            animation: pulseGlow 5s ease-in-out infinite;
        }

        .animate-blob-1 {
            animation: blobOne 16s ease-in-out infinite;
        }

        .animate-blob-2 {
            animation: blobTwo 20s ease-in-out infinite;
        }

        /* Delays for staggered loading */
        .delay-150 {
            animation-delay: 150ms;
        }
        .delay-300 {
            animation-delay: 300ms;
        }
        .delay-450 {
            animation-delay: 450ms;
        }
        .delay-600 {
            animation-delay: 600ms;
        }
    </style>
</head>
<body class="antialiased min-h-screen bg-zinc-50 dark:bg-zinc-950 flex flex-col justify-center items-center p-4 relative overflow-hidden font-sans select-none">
    
    <!-- Dynamic Glowing/Decorative Background Blobs -->
    <div class="absolute top-1/4 left-1/4 -translate-x-1/2 -translate-y-1/2 w-80 h-80 bg-lime-500/10 dark:bg-lime-500/5 rounded-full blur-3xl pointer-events-none animate-blob-1 animate-pulse-glow"></div>
    <div class="absolute bottom-1/4 right-1/4 translate-x-1/2 translate-y-1/2 w-[400px] h-[400px] bg-red-500/10 dark:bg-red-500/5 rounded-full blur-3xl pointer-events-none animate-blob-2 animate-pulse-glow"></div>

    <div class="max-w-md w-full text-center relative z-10">
        <!-- Glassmorphism Card with Fade in and Staggered Items -->
        <div class="animate-fade-in-up bg-white/80 dark:bg-zinc-900/80 backdrop-blur-xl border border-zinc-200/80 dark:border-zinc-800/80 rounded-3xl p-8 shadow-2xl shadow-zinc-200/50 dark:shadow-black/70 hover:shadow-zinc-300/50 dark:hover:shadow-black/80 transition-all duration-500">
            
            <!-- Animated Icon Holder -->
            <div class="flex justify-center mb-6 animate-float">
                <div class="relative group">
                    <!-- Triple layer glowing rings -->
                    <div class="absolute inset-0 bg-red-500/20 dark:bg-red-500/30 rounded-full blur-2xl group-hover:scale-125 transition-transform duration-500"></div>
                    <div class="absolute -inset-1 bg-gradient-to-tr from-red-500 to-amber-500 rounded-full blur opacity-30 animate-pulse"></div>
                    
                    <div class="relative bg-gradient-to-b from-red-50 to-red-100 dark:from-red-950/40 dark:to-red-900/20 text-red-500 dark:text-red-400 p-5 rounded-full border border-red-200/60 dark:border-red-800/40 shadow-inner flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" class="w-12 h-12 transform group-hover:scale-110 transition-transform duration-300">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Error Code & Header (Staggered fade) -->
            <div class="animate-fade-in-up delay-150">
                <span class="inline-flex items-center px-3.5 py-1 rounded-full text-xs font-semibold bg-red-500/10 text-red-600 dark:text-red-400 border border-red-500/20 mb-4 tracking-wide">
                    <span class="w-1.5 h-1.5 rounded-full bg-red-500 mr-1.5 animate-ping"></span>
                    Error 403 • Sin Autorización
                </span>
                
                <h1 class="text-3xl font-extrabold text-zinc-900 dark:text-white tracking-tight mb-3">
                    Acceso Restringido
                </h1>
            </div>
            
            <!-- Description text (Staggered fade) -->
            <div class="animate-fade-in-up delay-300">
                <p class="text-zinc-600 dark:text-zinc-400 text-sm leading-relaxed mb-6">
                    Lo sentimos, tu usuario actual no posee las credenciales necesarias para visualizar esta sección. Si consideras que se trata de un error de permisos, por favor contacta al administrador.
                </p>
            </div>

            <!-- Support / Admin details (Staggered fade) -->
            <div class="animate-fade-in-up delay-450 bg-zinc-50/50 dark:bg-zinc-950/30 border border-zinc-150 dark:border-zinc-800/80 rounded-2xl p-4 mb-8 text-left flex items-start space-x-3 hover:border-zinc-200 dark:hover:border-zinc-700/80 transition-colors duration-300">
                <div class="text-red-500 dark:text-red-400 mt-0.5 bg-red-500/10 dark:bg-red-500/20 p-1.5 rounded-lg animate-float-reverse">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4.5 h-4.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                    </svg>
                </div>
                <div>
                    <h4 class="text-xs font-bold text-zinc-800 dark:text-zinc-200">Soporte Técnico</h4>
                    <p class="text-[11px] text-zinc-500 dark:text-zinc-400 mt-0.5 leading-normal">
                        Para habilitar este módulo, envía una solicitud formal al administrador del sistema o escribe a soporte.
                    </p>
                </div>
            </div>

            <!-- Action buttons (Staggered fade) -->
            <div class="animate-fade-in-up delay-600 flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-3">
                <button onclick="window.history.back()" class="flex-1 inline-flex justify-center items-center px-5 py-3.5 rounded-2xl text-sm font-semibold text-zinc-700 dark:text-zinc-200 bg-zinc-100 hover:bg-zinc-200 dark:bg-zinc-800 dark:hover:bg-zinc-700/80 border border-transparent active:scale-[0.97] hover:-translate-y-0.5 transition-all duration-300">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4 mr-2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                    Regresar
                </button>
                <a href="/dashboard" class="flex-1 inline-flex justify-center items-center px-5 py-3.5 rounded-2xl text-sm font-semibold text-zinc-950 bg-lime-400 hover:bg-lime-500 dark:bg-lime-400 dark:hover:bg-lime-500 shadow-lg shadow-lime-500/10 hover:shadow-lime-500/20 active:scale-[0.97] hover:-translate-y-0.5 transition-all duration-300">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4 mr-2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                    </svg>
                    Ir al Inicio
                </a>
            </div>
        </div>

        <!-- Footer / Branding -->
        <p class="animate-fade-in-up delay-600 text-xs text-zinc-400 dark:text-zinc-600 mt-6 tracking-wide">
            © {{ date('Y') }} {{ $ajuste->nombre ?? 'INNutricion' }}. Todos los derechos reservados.
        </p>
    </div>
</body>
</html>

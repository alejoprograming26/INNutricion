<div class="space-y-6">
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 p-6 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-3xl shadow-sm overflow-hidden relative group">
        {{-- Decorative Background Glow --}}
        <div class="absolute -right-10 -top-10 w-40 h-40 bg-lime-500/5 rounded-full blur-3xl pointer-events-none group-hover:bg-emerald-500/10 transition-all duration-700"></div>
        <div class="absolute -left-10 -bottom-10 w-40 h-40 bg-emerald-500/5 rounded-full blur-3xl pointer-events-none group-hover:bg-lime-500/10 transition-all duration-700"></div>

        <div class="flex items-center gap-5 relative z-10">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-lime-500 to-emerald-600 flex items-center justify-center shadow-lg shadow-emerald-500/20 transform group-hover:scale-110 group-hover:rotate-3 transition-all duration-500">
                <flux:icon.calendar class="w-8 h-8 text-white" />
            </div>
            <div class="flex flex-col">
                <h1 class="text-3xl font-black text-zinc-900 dark:text-zinc-100 tracking-tighter uppercase leading-none">
                    Calendario <span class="text-transparent bg-clip-text bg-gradient-to-r from-lime-600 to-emerald-600">Institucional</span>
                </h1>
                <p class="text-[10px] font-bold text-zinc-400 dark:text-zinc-500 uppercase tracking-[0.3em] mt-1">Gestión Centralizada de Operaciones</p>
            </div>
        </div>
        
        <div class="flex items-center bg-zinc-100 dark:bg-zinc-800 p-1.5 rounded-2xl border border-zinc-200 dark:border-zinc-700 shadow-inner relative z-10">
            <button 
                wire:click="$set('viewMode', 'transcripciones')"
                id="btn-mode-transcripciones"
                class="px-6 py-2.5 text-xs font-black rounded-xl transition-all duration-500 {{ $viewMode === 'transcripciones' ? 'bg-gradient-to-r from-lime-600 to-emerald-600 text-white shadow-lg shadow-emerald-500/20 scale-105' : 'text-zinc-500 hover:text-zinc-800 dark:hover:text-zinc-200' }}">
                TRANSCRIPCIONES
            </button>
            <button 
                wire:click="$set('viewMode', 'actividades')"
                id="btn-mode-actividades"
                class="px-6 py-2.5 text-xs font-black rounded-xl transition-all duration-500 {{ $viewMode === 'actividades' ? 'bg-gradient-to-r from-lime-600 to-emerald-600 text-white shadow-lg shadow-emerald-500/20 scale-105' : 'text-zinc-500 hover:text-zinc-800 dark:hover:text-zinc-200' }}">
                ACTIVIDADES
            </button>
        </div>
    </div>

    {{-- Contenedor Principal FullCalendar --}}
    <flux:card class="p-4 border border-zinc-200 dark:border-zinc-800 rounded-xl bg-white dark:bg-zinc-900 shadow-sm">
        <div wire:ignore id="calendar-container"></div>
    </flux:card>

    {{-- Panel de Indicadores Mensuales --}}
    <div class="mb-4 space-y-4">
        {{-- Cabecera: Mes + Total --}}
        <div
            class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-6 border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 rounded-2xl shadow-sm relative overflow-hidden group">
            <div class="absolute -right-10 -top-10 w-32 h-32 bg-emerald-500/5 rounded-full blur-3xl pointer-events-none"></div>
            
            <div class="flex items-center gap-4 relative z-10">
                <div class="w-12 h-12 rounded-xl bg-zinc-50 dark:bg-zinc-800 flex items-center justify-center border border-zinc-100 dark:border-zinc-700 shadow-inner">
                    <flux:icon.chart-pie class="w-6 h-6 text-emerald-600" />
                </div>
                <div class="space-y-0.5">
                    <h3 class="text-[10px] font-bold text-zinc-400 dark:text-zinc-500 uppercase tracking-[0.2em]">Resumen Mensual</h3>
                    <div class="flex items-center gap-2">
                        <span class="text-2xl font-black text-zinc-800 dark:text-zinc-100 capitalize">{{ $nombreMesVisible }}</span>
                        <span class="text-[10px] px-2 py-0.5 rounded-md bg-emerald-50 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400 font-bold border border-emerald-100 dark:border-emerald-500/20">
                            {{ number_format($totalRegistros) }} REG.
                        </span>
                    </div>
                </div>
            </div>

            <div class="flex flex-col sm:items-end relative z-10">
                <span class="text-[10px] font-bold text-zinc-400 dark:text-zinc-500 uppercase tracking-[0.2em] mb-1">
                    {{ $viewMode === 'transcripciones' ? 'Total Procesado' : 'Impacto Total' }}
                </span>
                <div class="flex items-baseline gap-1">
                    <span class="text-4xl font-black text-zinc-900 dark:text-white leading-none tracking-tighter">{{ number_format($granTotal) }}</span>
                    @if($viewMode === 'actividades')
                        <span class="text-xs font-bold text-zinc-400 uppercase">Pers.</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Cards individuales por Tipo --}}
        @if (count($totalesMes) > 0)
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 2xl:grid-cols-5 gap-4">
                @foreach ($totalesMes as $item)
                    @php
                        $etiqueta = $etiquetasCortas[$item->tipo] ?? $item->tipo;
                        $hexColor = $coloresHex[$item->tipo] ?? '#6b7280';
                        $porcentaje = $granTotal > 0 ? round(($item->total / $granTotal) * 100, 1) : 0;
                    @endphp
                    <flux:card
                        class="relative flex flex-col p-5 overflow-hidden border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 rounded-2xl shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group"
                        style="border-left: 4px solid {{ $hexColor }};">
                        
                        {{-- Decorative Background Glow --}}
                        <div class="absolute -right-4 -bottom-4 w-20 h-20 rounded-full blur-2xl opacity-10 pointer-events-none group-hover:opacity-20 transition-opacity" style="background-color: {{ $hexColor }};"></div>

                        {{-- Top --}}
                        <div class="flex justify-between items-start mb-4">
                            <div class="flex items-center gap-2.5 max-w-[70%]">
                                <div class="w-2 h-2 rounded-full flex-shrink-0 animate-pulse" style="background-color: {{ $hexColor }}; box-shadow: 0 0 10px {{ $hexColor }}88;"></div>
                                <span class="text-[10px] font-black text-zinc-500 dark:text-zinc-400 uppercase tracking-widest truncate" title="{{ $etiqueta }}">{{ $etiqueta }}</span>
                            </div>
                            <span class="text-[9px] font-black text-zinc-400 dark:text-zinc-500 bg-zinc-50 dark:bg-zinc-800/80 px-2 py-1 rounded border border-zinc-100 dark:border-zinc-800 tabular-nums">
                                {{ $item->registros }} REG
                             </span>
                        </div>

                        {{-- Main Value --}}
                        <div class="flex flex-col mb-4">
                            <div class="flex items-baseline gap-1">
                                <span class="text-3xl font-black text-zinc-900 dark:text-white tabular-nums leading-none tracking-tighter group-hover:scale-110 transition-transform origin-left duration-500">{{ number_format($item->total) }}</span>
                                @if($viewMode === 'actividades')
                                    <span class="text-[10px] font-bold text-zinc-400 uppercase">Pers.</span>
                                @endif
                            </div>
                        </div>

                        {{-- Bottom Bar & Percentage --}}
                        <div class="mt-auto space-y-2">
                            <div class="flex justify-between items-center text-[9px] font-black text-zinc-400 dark:text-zinc-500 uppercase tracking-widest">
                                <span>Distribución</span>
                                <span>{{ $porcentaje }}%</span>
                            </div>
                            <div class="w-full h-1.5 bg-zinc-100 dark:bg-zinc-800/50 rounded-full overflow-hidden p-[1px]">
                                <div class="h-full rounded-full transition-all duration-1000 ease-out shadow-[0_0_8px_rgba(0,0,0,0.1)]"
                                    style="width: {{ $porcentaje }}%; background-color: {{ $hexColor }};"></div>
                            </div>
                        </div>
                    </flux:card>
                @endforeach
            </div>
        @else
            <flux:card
                class="flex flex-col items-center justify-center py-12 border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 rounded-xl shadow-sm">
                <flux:icon.chart-bar class="w-12 h-12 mb-4 text-zinc-300 dark:text-zinc-700" />
                <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400 text-center">No hay registros
                    procesados<br />en este mes.</p>
            </flux:card>
        @endif
    </div>

    {{-- MODAL DE DETALLES --}}
    @if ($isModalOpen)
        <div
            class="fixed inset-0 z-[100] flex items-center justify-center bg-black/50 backdrop-blur-sm p-4 w-full h-full">
            <div class="bg-white dark:bg-zinc-900 w-full max-w-5xl rounded-xl shadow-xl flex flex-col max-h-[90vh]">
                <div class="flex items-center justify-between px-6 py-4 border-b border-zinc-200 dark:border-zinc-700">
                    <h2 class="text-lg font-bold text-zinc-800 dark:text-zinc-100">
                        Actividad Registrada el <span
                            class="bg-lime-100 dark:bg-lime-900/50 text-lime-700 dark:text-lime-300 px-2 py-1 rounded tracking-wide">{{ \Carbon\Carbon::parse($fechaSeleccionada)->format('d/m/Y') }}</span>
                    </h2>
                    <flux:button wire:click="closeModal" variant="ghost" icon="x-mark" />
                </div>

                <div class="p-6 overflow-y-auto custom-scrollbar">
                    @if (count($transcripcionesDia) > 0 || count($actividadesDia) > 0)
                        <div class="overflow-x-auto rounded-lg border border-zinc-200 dark:border-zinc-700">
                            <table class="w-full text-sm text-left text-zinc-600 dark:text-zinc-400">
                                <thead
                                    class="bg-zinc-50 dark:bg-zinc-800/50 text-xs uppercase font-semibold text-zinc-700 dark:text-zinc-300 border-b border-zinc-200 dark:border-zinc-700">
                                    <tr class="text-center">
                                        <th class="px-3 py-3 w-10">#</th>
                                        <th class="px-3 py-3 text-left">Tipo</th>
                                        <th class="px-3 py-3 text-left">Observación</th>
                                        <th class="px-3 py-3">Responsable</th>
                                        <th class="px-3 py-3">Municipio</th>
                                        <th class="px-3 py-3">Sector</th>
                                        <th class="px-3 py-3">{{ $viewMode === 'transcripciones' ? 'Cantidad' : 'Eventos' }}</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                                    {{-- Renderizar Transcripciones --}}
                                    @foreach ($transcripcionesDia as $t)
                                        <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800 transition-colors text-center">
                                            <td class="px-3 py-3 font-medium text-zinc-500">{{ $loop->iteration }}</td>
                                            <td class="px-3 py-3 text-left">
                                                <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded {{ $coloresTailwind[$t->tipo] ?? 'bg-zinc-100 text-zinc-700' }}">
                                                    {{ $t->tipo }}
                                                </span>
                                            </td>
                                            <td class="px-3 py-3 text-left font-medium text-zinc-800 dark:text-zinc-100 max-w-[200px] truncate" title="{{ $t->observacion }}">
                                                {{ $t->observacion ?? '—' }}
                                            </td>
                                            <td class="px-3 py-3 text-zinc-600 dark:text-zinc-300">{{ $t->responsable }}</td>
                                            <td class="px-3 py-3 text-zinc-600 dark:text-zinc-300">
                                                <flux:badge size="sm" color="zinc">{{ $t->sector->comuna->parroquia->municipio->nombre }}</flux:badge>
                                            </td>
                                            <td class="px-3 py-3 text-zinc-600 dark:text-zinc-300">
                                                <flux:badge size="sm" color="zinc">{{ $t->sector->nombre }}</flux:badge>
                                            </td>
                                            <td class="px-3 py-3 font-bold text-zinc-800 dark:text-zinc-100">{{ number_format($t->cantidad) }}</td>
                                        </tr>
                                    @endforeach

                                    {{-- Renderizar Actividades --}}
                                    @foreach ($actividadesDia as $a)
                                        <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800 transition-colors text-center">
                                            <td class="px-3 py-3 font-medium text-zinc-500">{{ count($transcripcionesDia) + $loop->iteration }}</td>
                                            <td class="px-3 py-3 text-left">
                                                <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded {{ $coloresTailwind[$a['tipo']] ?? 'bg-zinc-100 text-zinc-700' }}">
                                                    {{ $a['tipo'] }}
                                                </span>
                                            </td>
                                            <td class="px-3 py-3 text-left font-medium text-zinc-800 dark:text-zinc-100 max-w-[200px] truncate" title="{{ $a['observacion'] }}">
                                                {{ $a['observacion'] ?? '—' }}
                                            </td>
                                            <td class="px-3 py-3 text-zinc-600 dark:text-zinc-300">{{ $a['responsable'] }}</td>
                                            <td class="px-3 py-3 text-zinc-600 dark:text-zinc-300">
                                                <flux:badge size="sm" color="zinc">{{ $a['municipio'] }}</flux:badge>
                                            </td>
                                            <td class="px-3 py-3 text-zinc-600 dark:text-zinc-300">
                                                <flux:badge size="sm" color="zinc">{{ $a['sector'] }}</flux:badge>
                                            </td>
                                            <td class="px-3 py-3 font-bold text-zinc-800 dark:text-zinc-100">{{ number_format($a['cantidad']) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-12 text-zinc-500">
                            <flux:icon.calendar class="w-12 h-12 mx-auto mb-4 opacity-30" />
                            No hay registros para esta fecha.
                        </div>
                    @endif
                </div>

                <div
                    class="px-6 py-4 border-t border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800/50 flex justify-end gap-3 rounded-b-xl">
                    <flux:button wire:click="closeModal" variant="subtle">Cerrar Detalles</flux:button>
                </div>
            </div>
        </div>
    @endif


    {{-- FullCalendar v6 via @assets (compatible con wire:navigate) --}}
    @assets
        <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
        <style>
            /* Estilos para FullCalendar - Sobrescribir colores por defecto */
            .fc .fc-button-primary {
                background: #f4f4f5 !important; /* zinc-100 */
                border-color: #e4e4e7 !important; /* zinc-200 */
                color: #3f3f46 !important; /* zinc-700 */
                font-weight: 800 !important;
                text-transform: uppercase !important;
                font-size: 0.65rem !important;
                letter-spacing: 0.05em !important;
                padding: 0.5rem 1rem !important;
                transition: all 0.2s ease-in-out !important;
                border-radius: 0.5rem !important;
                box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05) !important;
            }
            .fc .fc-button-primary:hover {
                background: #e4e4e7 !important; /* zinc-200 */
                border-color: #d4d4d8 !important; /* zinc-300 */
                color: #18181b !important; /* zinc-900 */
                transform: translateY(-1px) !important;
                box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1) !important;
            }
            .fc .fc-button-active {
                background: #27272a !important; /* zinc-800 */
                border-color: #27272a !important;
                color: #ffffff !important;
                box-shadow: inset 0 2px 4px 0 rgb(0 0 0 / 0.06) !important;
            }
            .fc .fc-toolbar-title {
                font-weight: 900 !important;
                text-transform: uppercase !important;
                letter-spacing: -0.025em !important;
                color: #27272a !important;
                font-size: 1.25rem !important;
            }
            .dark .fc .fc-toolbar-title {
                color: #f4f4f5 !important;
            }
            .fc .fc-daygrid-day-number {
                font-weight: 700 !important;
                font-size: 0.85rem !important;
                padding: 8px !important;
            }
            .fc .fc-col-header-cell-cushion {
                font-weight: 800 !important;
                text-transform: uppercase !important;
                font-size: 0.7rem !important;
                letter-spacing: 0.05em !important;
                color: #71717a !important;
                padding: 10px 0 !important;
            }
        </style>
    @endassets

    @script
        <script>
            let calendarEl = document.getElementById('calendar-container');
            let eventos = @js($eventosFullCalendar);

            let calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'es',
                firstDay: 1,
                height: 'auto',
                headerToolbar: {
                    left: 'prev,today,next',
                    center: 'title',
                    right: 'dayGridMonth,listMonth'
                },
                buttonText: {
                    today: 'Hoy',
                    month: 'Mes',
                    list: 'Lista'
                },
                events: eventos,
                eventClick: function(info) {
                    info.jsEvent.preventDefault();
                    $wire.abrirDia(info.event.startStr);
                },
                datesSet: function(dateInfo) {
                    // Cuando el usuario cambia de mes, notificar al backend
                    let visibleDate = dateInfo.view.currentStart;
                    let mes = visibleDate.getMonth() + 1;
                    let anio = visibleDate.getFullYear();
                    $wire.cambiarMesVisible(mes, anio);
                }
            });

            calendar.render();

            // Escuchar cambios de modo para refrescar eventos
            Livewire.on('view-mode-updated', (data) => {
                calendar.removeAllEvents();
                calendar.addEventSource(data.eventos);
            });
        </script>
    @endscript
</div>

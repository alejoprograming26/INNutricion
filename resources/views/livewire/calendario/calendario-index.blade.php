<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-zinc-900 dark:text-zinc-100">Calendario de Transcripciones y Actividades</h1>
    </div>

    {{-- Contenedor Principal FullCalendar --}}
    <flux:card class="p-4 border border-zinc-200 dark:border-zinc-800 rounded-xl bg-white dark:bg-zinc-900 shadow-sm">
        <div wire:ignore id="calendar-container"></div>
    </flux:card>

    {{-- Panel de Indicadores Mensuales --}}
    <div class="mb-4 space-y-4">
        {{-- Cabecera: Mes + Total --}}
        <div
            class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 p-5 border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 rounded-xl shadow-sm">
            <div class="space-y-1.5">
                <h3 class="text-sm font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-widest">Resumen
                    Mensual</h3>
                <div class="flex items-center gap-3">
                    <span
                        class="text-xl font-bold text-zinc-800 dark:text-zinc-100 capitalize">{{ $nombreMesVisible }}</span>
                    <span
                        class="text-xs px-2.5 py-1 rounded-full bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-300 font-semibold border border-zinc-200 dark:border-zinc-700">
                        {{ number_format($totalRegistros) }} registros
                    </span>
                </div>
            </div>
            <div class="flex flex-col sm:items-end">
                <span class="text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-widest mb-1">Total
                    Procesado</span>
                <span
                    class="text-4xl font-black text-zinc-900 dark:text-white leading-none tracking-tight">{{ number_format($granTotal) }}</span>
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
                        class="relative flex flex-col p-4 overflow-hidden border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 rounded-xl shadow-sm hover:shadow-md transition-all duration-300 group">
                        {{-- Top --}}
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex items-center gap-2 max-w-[70%]">
                                <span class="w-2.5 h-2.5 rounded-full flex-shrink-0 shadow-sm"
                                    style="background-color: {{ $hexColor }};"></span>
                                <span
                                    class="text-xs font-bold text-zinc-600 dark:text-zinc-300 uppercase tracking-wider truncate"
                                    title="{{ $etiqueta }}">{{ $etiqueta }}</span>
                            </div>
                            <span
                                class="text-[10px] font-bold text-zinc-500 dark:text-zinc-400 bg-zinc-50 dark:bg-zinc-800/80 px-1.5 py-0.5 rounded-md flex-shrink-0 tabular-nums border border-zinc-100 dark:border-zinc-800">
                                {{ $item->registros }} reg
                            </span>
                        </div>

                        {{-- Main Value --}}
                        <div class="flex flex-col mb-3">
                            <span
                                class="text-3xl font-black text-zinc-800 dark:text-zinc-100 tabular-nums leading-none tracking-tight group-hover:scale-[1.03] transition-transform origin-left">{{ number_format($item->total) }}</span>
                        </div>

                        {{-- Bottom Bar & Percentage --}}
                        <div class="mt-auto space-y-1.5">
                            <div
                                class="flex justify-between items-center text-[10px] font-semibold text-zinc-400 dark:text-zinc-500 uppercase tracking-wide">
                                <span>Progreso</span>
                                <span>{{ $porcentaje }}%</span>
                            </div>
                            <div class="w-full h-1 bg-zinc-100 dark:bg-zinc-800 rounded-full overflow-hidden">
                                <div class="h-full rounded-full transition-all duration-700 ease-out"
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
                    @if (count($transcripcionesDia) > 0)
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
                                        <th class="px-3 py-3">Cantidad</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                                    @foreach ($transcripcionesDia as $t)
                                        <tr
                                            class="hover:bg-zinc-50 dark:hover:bg-zinc-800 transition-colors text-center">
                                            <td class="px-3 py-3 font-medium text-zinc-500">{{ $loop->iteration }}</td>
                                            <td class="px-3 py-3 text-left">
                                                @php
                                                    $badgeColorClass =
                                                        $coloresTailwind[$t->tipo] ?? 'bg-zinc-100 text-zinc-700';
                                                @endphp
                                                <span
                                                    class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded {{ $badgeColorClass }}">
                                                    {{ $t->tipo }}
                                                </span>
                                            </td>
                                            <td class="px-3 py-3 text-left font-medium text-zinc-800 dark:text-zinc-100 max-w-[200px] truncate"
                                                title="{{ $t->observacion }}">
                                                {{ $t->observacion ?? '—' }}
                                            </td>
                                            <td class="px-3 py-3 text-zinc-600 dark:text-zinc-300">
                                                {{ $t->responsable }}
                                            </td>
                                            <td class="px-3 py-3 text-zinc-600 dark:text-zinc-300">
                                                <flux:badge size="sm" color="zinc">{{ $t->municipio->nombre }}
                                                </flux:badge>
                                            </td>
                                            <td class="px-3 py-3 text-zinc-600 dark:text-zinc-300">
                                                <flux:badge size="sm" color="zinc">{{ $t->sector->nombre }}
                                                </flux:badge>
                                            </td>
                                            <td class="px-3 py-3 font-bold text-zinc-800 dark:text-zinc-100">
                                                {{ number_format($t->cantidad) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-12 text-zinc-500">
                            <flux:icon.calendar class="w-12 h-12 mx-auto mb-4 opacity-30" />
                            No hay ninguna actividad auditable para esta fecha.
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
        </script>
    @endscript
</div>

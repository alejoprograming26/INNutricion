<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-zinc-900 dark:text-zinc-100">Calendario De transcripciones y Actividades</h1>
    </div>

    {{-- Contenedor Principal FullCalendar --}}
    <flux:card class="p-4 border border-zinc-200 dark:border-zinc-800 rounded-xl bg-white dark:bg-zinc-900 shadow-sm">
        <div wire:ignore id="calendar-container"></div>
    </flux:card>

    {{-- Panel de Indicadores Mensuales --}}
    <div class="mb-4 space-y-4">
        {{-- Cabecera: Mes + Total --}}
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 p-5 border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 rounded-xl shadow-sm">
            <div class="space-y-1.5">
                <h3 class="text-sm font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-widest">Resumen Mensual</h3>
                <div class="flex items-center gap-3">
                    <span class="text-xl font-bold text-zinc-800 dark:text-zinc-100 capitalize">{{ $nombreMesVisible }}</span>
                    <span class="text-xs px-2.5 py-1 rounded-full bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-300 font-semibold border border-zinc-200 dark:border-zinc-700">
                        {{ number_format($totalRegistros) }} registros
                    </span>
                </div>
            </div>
            <div class="flex flex-col sm:items-end">
                <span class="text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-widest mb-1">Total Procesado</span>
                <span class="text-4xl font-black text-zinc-900 dark:text-white leading-none tracking-tight">{{ number_format($granTotal) }}</span>
            </div>
        </div>

        {{-- Cards individuales por Tipo --}}
        @if(count($totalesMes) > 0)
            <div class="grid grid-cols-2 md:grid-cols-4 xl:grid-cols-5 2xl:grid-cols-6 gap-4">
                @foreach($totalesMes as $item)
                    @php
                        $etiqueta = $etiquetasCortas[$item->tipo] ?? $item->tipo;
                        $hexColor = $coloresHex[$item->tipo] ?? '#6b7280';
                        $porcentaje = $granTotal > 0 ? round(($item->total / $granTotal) * 100, 1) : 0;
                    @endphp
                    <flux:card class="relative flex flex-col p-4 overflow-hidden border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 rounded-xl shadow-sm hover:shadow-md transition-all duration-300 group">
                        {{-- Top --}}
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex items-center gap-2 max-w-[70%]">
                                <span class="w-2.5 h-2.5 rounded-full flex-shrink-0 shadow-sm" style="background-color: {{ $hexColor }};"></span>
                                <span class="text-[11px] font-bold text-zinc-600 dark:text-zinc-300 uppercase tracking-wider truncate" title="{{ $etiqueta }}">{{ $etiqueta }}</span>
                            </div>
                            <span class="text-[9px] font-bold text-zinc-500 dark:text-zinc-400 bg-zinc-50 dark:bg-zinc-800/80 px-1.5 py-0.5 rounded-md flex-shrink-0 tabular-nums border border-zinc-100 dark:border-zinc-800">
                                {{ $item->registros }} reg
                            </span>
                        </div>
                        
                        {{-- Main Value --}}
                        <div class="flex flex-col mb-3">
                            <span class="text-2xl font-black text-zinc-800 dark:text-zinc-100 tabular-nums leading-none tracking-tight group-hover:scale-[1.03] transition-transform origin-left">{{ number_format($item->total) }}</span>
                        </div>
                        
                        {{-- Bottom Bar & Percentage --}}
                        <div class="mt-auto space-y-1.5">
                            <div class="flex justify-between items-center text-[9px] font-semibold text-zinc-400 dark:text-zinc-500 uppercase tracking-wide">
                                <span>Progreso</span>
                                <span>{{ $porcentaje }}%</span>
                            </div>
                            <div class="w-full h-1 bg-zinc-100 dark:bg-zinc-800 rounded-full overflow-hidden">
                                <div class="h-full rounded-full transition-all duration-700 ease-out" style="width: {{ $porcentaje }}%; background-color: {{ $hexColor }};"></div>
                            </div>
                        </div>
                    </flux:card>
                @endforeach
            </div>
        @else
            <flux:card class="flex flex-col items-center justify-center py-12 border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 rounded-xl shadow-sm">
                <flux:icon.chart-bar class="w-12 h-12 mb-4 text-zinc-300 dark:text-zinc-700" />
                <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400 text-center">No hay registros procesados<br/>en este mes.</p>
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

    {{-- CSS para adaptar FullCalendar a la estética Tailwind/Flux --}}
    <style>
        .fc {
            font-family: 'Inter', ui-sans-serif, system-ui, -apple-system, sans-serif !important;
        }

        .fc .fc-toolbar-title {
            font-size: 1.25rem !important;
            font-weight: 700;
            color: #18181b;
            text-transform: capitalize;
        }

        .dark .fc .fc-toolbar-title {
            color: #f4f4f5;
        }

        .fc .fc-button-primary {
            background-color: #e4e4e7 !important;
            border-color: #d4d4d8 !important;
            text-transform: capitalize;
            box-shadow: none !important;
            font-weight: 600;
            color: #3f3f46 !important;
        }

        .fc .fc-button-primary:hover {
            background-color: #d4d4d8 !important;
            border-color: #a1a1aa !important;
            color: #27272a !important;
        }

        .fc .fc-button-primary:not(:disabled):active,
        .fc .fc-button-primary:not(:disabled).fc-button-active {
            background-color: #a1a1aa !important;
            border-color: #a1a1aa !important;
            color: #18181b !important;
        }

        /* Encabezado de días (Lun, Mar, Mié...) */
        .fc .fc-col-header-cell {
            background-color: #f9fafb;
        }

        .dark .fc .fc-col-header-cell {
            background-color: #27272a !important;
        }

        .fc .fc-col-header-cell-cushion {
            color: #52525b;
            text-transform: uppercase;
            font-size: 0.8rem;
            font-weight: 600;
            padding: 10px 0 !important;
        }

        .dark .fc .fc-col-header-cell-cushion {
            color: #a1a1aa !important;
        }

        /* Celdas de los días */
        .fc .fc-daygrid-day-number {
            color: #3f3f46;
            font-weight: bold;
            padding: 4px 8px;
        }

        .dark .fc .fc-daygrid-day-number {
            color: #d4d4d8;
        }

        .dark .fc .fc-daygrid-day {
            background-color: #18181b;
        }

        .dark .fc .fc-day-other {
            background-color: #0f0f11 !important;
        }

        /* Día actual */
        .fc .fc-day-today {
            background-color: #f7fee7 !important;
        }

        .dark .fc .fc-day-today {
            background-color: rgba(132, 204, 22, 0.08) !important;
        }

        /* Eventos (barras de color) */
        .fc .fc-event {
            cursor: pointer;
            border: none;
            border-radius: 4px;
            padding: 3px 6px;
            margin-top: 2px;
            font-weight: 600;
            font-size: 0.75rem;
            box-shadow: rgba(0, 0, 0, 0.1) 0px 1px 2px;
            color: #fff !important;
        }

        .fc .fc-event:hover {
            opacity: 0.9;
        }

        /* Bordes y grid */
        .fc .fc-scrollgrid {
            border-color: #e4e4e7;
            border-radius: 8px;
            overflow: hidden;
        }

        .dark .fc .fc-scrollgrid {
            border-color: #27272a !important;
        }

        .dark .fc td,
        .dark .fc th {
            border-color: #27272a !important;
        }

        /* ===== VISTA LISTA (listMonth) ===== */
        .fc .fc-list {
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
        }

        .dark .fc .fc-list {
            background-color: #18181b !important;
        }

        .fc .fc-list-day-cushion {
            background-color: #f4f4f5 !important;
            padding: 10px 16px !important;
        }

        .dark .fc .fc-list-day-cushion {
            background-color: #27272a !important;
        }

        .fc .fc-list-day-text,
        .fc .fc-list-day-side-text {
            color: #18181b;
            font-weight: 800;
            font-size: 0.85rem;
            text-transform: capitalize;
        }

        .dark .fc .fc-list-day-text,
        .dark .fc .fc-list-day-side-text {
            color: #e4e4e7 !important;
        }

        .fc .fc-list-event td {
            color: #3f3f46;
            padding: 10px 14px !important;
            font-size: 0.85rem;
            font-weight: 500;
            border-bottom: 1px solid #f4f4f5;
        }

        .dark .fc .fc-list-event td {
            color: #d4d4d8 !important;
            background-color: #18181b !important;
            border-bottom-color: #27272a !important;
        }

        .dark .fc .fc-list-event:hover td {
            background-color: #27272a !important;
        }

        .fc .fc-list-event:hover td {
            background-color: #fafafa !important;
        }

        .fc .fc-list-event-dot {
            border-width: 6px !important;
            border-radius: 50% !important;
        }

        .fc .fc-list-empty {
            background-color: #ffffff;
            color: #71717a;
            padding: 40px 20px !important;
            font-size: 0.9rem;
        }

        .dark .fc .fc-list-empty {
            background-color: #18181b !important;
            color: #a1a1aa !important;
        }

        /* ===== DARK MODE: Cobrar TODOS los textos restantes ===== */
        .dark .fc {
            color: #d4d4d8 !important;
        }

        .dark .fc a {
            color: #d4d4d8 !important;
        }

        .dark .fc .fc-list-event-title a {
            color: #f4f4f5 !important;
            font-weight: 600;
        }

        .dark .fc .fc-daygrid-more-link {
            color: #a1a1aa !important;
        }

        .dark .fc .fc-button-primary {
            background-color: #52525b !important;
            border-color: #52525b !important;
        }

        .dark .fc .fc-button-primary:hover {
            background-color: #71717a !important;
            border-color: #71717a !important;
        }

        .dark .fc .fc-button-primary:not(:disabled):active,
        .dark .fc .fc-button-primary:not(:disabled).fc-button-active {
            background-color: #3f3f46 !important;
            border-color: #3f3f46 !important;
        }

        .dark .fc .fc-toolbar .fc-button {
            color: #ffffff !important;
        }

        .dark .fc .fc-daygrid-day-events {
            color: #ffffff !important;
        }

        .dark .fc .fc-list-event .fc-list-event-time {
            color: #a1a1aa !important;
        }

        /* Scrollbar personalizado */
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background-color: #d4d4d8;
            border-radius: 20px;
        }

        .dark .custom-scrollbar::-webkit-scrollbar-thumb {
            background-color: #52525b;
        }
    </style>

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

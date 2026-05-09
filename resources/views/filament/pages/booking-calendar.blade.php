<x-filament-panels::page>
    @push('styles')
        <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css" rel="stylesheet" />
        <style>
            #calendar {
                max-width: 100%;
                margin: 0 auto;
            }
            .fc-event {
                cursor: pointer;
                font-size: 0.85rem;
            }
            .fc-toolbar-title {
                font-size: 1.25rem !important;
                font-weight: 600 !important;
            }
            .filter-bar {
                display: flex;
                gap: 1rem;
                align-items: center;
                margin-bottom: 1rem;
                flex-wrap: wrap;
            }
            .filter-bar select {
                padding: 0.5rem 0.75rem;
                border-radius: 0.5rem;
                border: 1px solid #d1d5db;
                background: var(--filament-input-bg, #fff);
                color: var(--filament-text-color, #111);
                min-width: 200px;
            }
            .filter-bar label {
                font-weight: 500;
                font-size: 0.875rem;
            }

            .fc .fc-button-primary {
                background-color: var(--filament-primary-600, #2563eb);
                border-color: var(--filament-primary-600, #2563eb);
            }
            .fc .fc-button-primary:hover {
                background-color: var(--filament-primary-700, #1d4ed8);
                border-color: var(--filament-primary-700, #1d4ed8);
            }
            .fc .fc-button-primary:not(:disabled).fc-button-active,
            .fc .fc-button-primary:not(:disabled):active {
                background-color: var(--filament-primary-800, #1e40af);
                border-color: var(--filament-primary-800, #1e40af);
            }

            .fc-event.pending {
                background-color: #f59e0b;
                border-color: #f59e0b;
            }
            .fc-event.approved {
                background-color: #10b981;
                border-color: #10b981;
            }
            .fc-event.rejected {
                background-color: #ef4444;
                border-color: #ef4444;
                opacity: 0.6;
            }

            .dark .fc {
                --fc-page-bg-color: transparent;
                --fc-neutral-bg-color: var(--filament-sidebar-bg, #1f2937);
                --fc-neutral-text-color: #e5e7eb;
                --fc-border-color: #374151;
                --fc-button-text-color: #fff;
                --fc-button-bg-color: var(--filament-primary-600, #2563eb);
                --fc-button-border-color: var(--filament-primary-600, #2563eb);
                --fc-button-hover-bg-color: var(--filament-primary-700, #1d4ed8);
                --fc-button-hover-border-color: var(--filament-primary-700, #1d4ed8);
                --fc-button-active-bg-color: var(--filament-primary-800, #1e40af);
                --fc-button-active-border-color: var(--filament-primary-800, #1e40af);
                --fc-today-bg-color: rgba(59, 130, 246, 0.15);
                --fc-event-bg-color: #3b82f6;
                --fc-event-border-color: #3b82f6;
                --fc-event-text-color: #fff;
                --fc-event-selected-overlay-color: rgba(0, 0, 0, 0.25);
                --fc-more-link-bg-color: #374151;
                --fc-more-link-text-color: #e5e7eb;
                --fc-list-event-hover-bg-color: rgba(255, 255, 255, 0.05);
                --fc-highlight-color: rgba(255, 255, 255, 0.05);
                --fc-non-business-color: rgba(255, 255, 255, 0.05);
            }

            .dark .fc table,
            .dark .fc th,
            .dark .fc td {
                border-color: #374151;
            }

            .dark .fc .fc-daygrid-day-number,
            .dark .fc .fc-col-header-cell-cushion,
            .dark .fc .fc-list-day-text,
            .dark .fc .fc-list-day-side-text,
            .dark .fc .fc-list-event-title,
            .dark .fc .fc-list-event-time,
            .dark .fc .fc-list-event td,
            .dark .fc .fc-timegrid-slot-label,
            .dark .fc .fc-timegrid-axis,
            .dark .fc .fc-daygrid-more-link {
                color: #e5e7eb;
            }

            .dark .fc .fc-day-today .fc-daygrid-day-number {
                color: #60a5fa;
                font-weight: 700;
            }

            .dark .fc .fc-list-empty {
                color: #9ca3af;
            }

            .dark .fc .fc-popover {
                background-color: #1f2937;
                border-color: #374151;
            }
            .dark .fc .fc-popover-title {
                color: #e5e7eb;
            }
            .dark .fc .fc-popover-header {
                background-color: #111827;
            }

            .dark .fc .fc-day-other .fc-daygrid-day-top {
                opacity: 0.35;
            }

            .dark .fc .fc-timegrid-now-indicator-line {
                border-color: #f59e0b;
            }
            .dark .fc .fc-timegrid-now-indicator-arrow {
                border-color: #f59e0b;
                color: #f59e0b;
            }

            .dark .fc .fc-scrollgrid {
                border-color: #374151;
            }

            .dark .fc .fc-list-day-cushion {
                background-color: #111827;
            }

            .fc .fc-daygrid-day-frame {
                min-height: 80px;
            }
        </style>
    @endpush

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js">
        </script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const calendarEl = document.getElementById('calendar');
                const filterSelect = document.getElementById('booking-type-filter');

                if (!calendarEl) return;

                const calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth',
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
                    },
                    locale: 'id',
                    firstDay: 1,
                    height: 'auto',
                    slotMinTime: '07:00:00',
                    slotMaxTime: '22:00:00',
                    slotDuration: '00:30:00',
                    expandRows: true,
                    stickyHeaderDates: true,
                    nowIndicator: true,
                    eventSources: [{
                        url: '/booking-orders/calendar-data',
                        method: 'GET',
                        extraParams: function() {
                            return {
                                booking_type_id: filterSelect ? filterSelect.value : ''
                            };
                        },
                        failure: function() {
                            console.error('Gagal memuat data booking.');
                        }
                    }],
                    eventColor: '#3b82f6',
                    eventDidMount: function(info) {
                        const status = info.event.extendedProps.status;
                        info.el.classList.add(status);

                        const tooltip = document.createElement('div');
                        tooltip.className = 'fc-event-tooltip';
                        tooltip.style.cssText = `
                            display: none;
                            position: absolute;
                            background: var(--filament-sidebar-bg, #1f2937);
                            color: var(--filament-text-color, #fff);
                            padding: 0.75rem;
                            border-radius: 0.5rem;
                            font-size: 0.8rem;
                            z-index: 1000;
                            min-width: 200px;
                            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                            border: 1px solid rgba(255,255,255,0.1);
                            pointer-events: none;
                        `;
                        tooltip.innerHTML = `
                            <strong>${info.event.title}</strong><br>
                            ${info.event.extendedProps.status_text}<br>
                            Host: ${info.event.extendedProps.host || '-'}<br>
                            Unit: ${info.event.extendedProps.unit || '-'}<br>
                            Waktu: ${info.event.extendedProps.time || '-'}
                        `;
                        info.el.appendChild(tooltip);

                        info.el.addEventListener('mouseenter', function() {
                            tooltip.style.display = 'block';
                        });
                        info.el.addEventListener('mouseleave', function() {
                            tooltip.style.display = 'none';
                        });
                    },
                    eventClick: function(info) {
                        if (info.event.url) {
                            window.open(info.event.url, '_self');
                            info.jsEvent.preventDefault();
                        }
                    },
                    loading: function(isLoading) {
                        const loader = document.getElementById('calendar-loader');
                        if (loader) {
                            loader.style.display = isLoading ? 'block' : 'none';
                        }
                    }
                });

                calendar.render();

                if (filterSelect) {
                    filterSelect.addEventListener('change', function() {
                        calendar.refetchEvents();
                    });
                }
            });
        </script>
    @endpush

    <div class="space-y-4">
        <div class="filter-bar">
            <label for="booking-type-filter">Filter Jenis Booking:</label>
            <select id="booking-type-filter" class="focus:ring-2 focus:ring-primary-500">
                <option value="">Semua Jenis</option>
                @foreach($this->getBookingTypes() as $id => $name)
                    <option value="{{ $id }}">{{ $name }}</option>
                @endforeach
            </select>
            <div id="calendar-loader" style="display:none; color: #6b7280; font-size: 0.875rem;">
                Memuat data...
            </div>
        </div>

        <div id="calendar"></div>
    </div>
</x-filament-panels::page>

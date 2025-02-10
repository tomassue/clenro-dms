<div>
    <!--begin::Row-->
    <div class="row g-5 g-xl-8">
        <!--begin::Col-->
        <div class="col-xxl-12">
            <!--begin::Mixed Widget 5-->
            <div class="card card-xxl-stretch">
                <!--begin::Beader-->
                <div class="card-header border-0 py-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bolder fs-3 mb-1">Calendar</span>
                        <!-- <span class="text-muted fw-bold fs-7">Manage users</span> -->
                    </h3>
                    <div class="card-toolbar">
                        <!--end::Menu-->
                    </div>
                </div>
                <!--end::Header-->

                <!--begin::Body-->
                <div class="card-body d-flex flex-column">
                    <!--begin: FILTER SELECT -->
                    <div class="row g-5 justify-content-between">
                        <div class="col-sm-12 col-md-12 col-lg-2">
                            <div class="col-12 mb-10">
                                <select class="form-select text-capitalize" aria-label="Select example" wire:model.live="filter_status">
                                    <option value="">-Status (All)-</option>
                                    @foreach ($status_select as $item)
                                    <option value="{{ $item->id }}">{{ $item->status_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <!--end: FILTER SELECT -->

                    <div class="mb-5" wire:ignore>
                        <div id="incoming_requests_calendar"></div>
                    </div>

                    <!--end::Items-->
                </div>
                <!--end::Body-->
            </div>
            <!--end::Mixed Widget 5-->
        </div>
        <!--end::Col-->
    </div>
    <!--end::Row-->

    @include('livewire.modals.calendar-modal')
</div>

@script
<script>
    $wire.on('show-detailsModal', () => {
        $('#detailsModal').modal('show');
    });

    $wire.on('hide-detailsModal', () => {
        $('#detailsModal').modal('hide');
    });

    /* -------------------------------------------------------------------------- */

    $wire.on('read-file', (url) => {
        window.open(event.detail.url, '_blank'); // Open the signed URL in a new tab
    });

    /* -------------------------------------------------------------------------- */

    document.addEventListener('livewire:initialized', function() {
        var calendarEl = document.getElementById('incoming_requests_calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            themeSystem: 'bootstrap5',
            initialView: 'dayGridMonth',
            height: 650,
            headerToolbar: {
                left: 'dayGridMonth,listWeek,timeGridWeek,timeGridDay',
                center: 'title',
                right: 'prev,today,next' // user can switch between the two
            },
            selectable: true,
            events: @json($incoming_request_calendar),

            // Customize the time display format to show both start and end times
            eventTimeFormat: {
                hour: 'numeric',
                minute: '2-digit',
                meridiem: 'short'
            },

            eventContent: function(arg) {
                let startTime = FullCalendar.formatDate(arg.event.start, {
                    hour: 'numeric',
                    minute: '2-digit',
                    meridiem: 'short'
                });
                let endTime = FullCalendar.formatDate(arg.event.end, {
                    hour: 'numeric',
                    minute: '2-digit',
                    meridiem: 'short'
                });

                // Combine the start and end time in the display
                let timeHtml = startTime + ' - ' + endTime;

                return {
                    html: '<div class="fc-event-time" style="cursor: pointer;">' + timeHtml + '</div><div class="fc-event-title" style="cursor: pointer;">' + arg.event.title + '</div>'
                };
            },

            eventClick: function(info) {
                // Trigger Livewire event to show details
                // $wire.dispatch('show-details', {
                //     key: info.event.id
                // });

                $wire.showDetails(info.event.id);
            },

            dateClick: function(info) {
                // Change to dayGridDay view on date click
                calendar.changeView('timeGridDay', info.dateStr);
            }
        });

        calendar.render();

        $wire.on('refresh-calendar', (data) => {
            calendar.removeAllEvents();
            calendar.addEventSource(data.meetings); // Use `data.meetings` here
        });

    });
</script>
@endscript
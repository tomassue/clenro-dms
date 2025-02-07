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
                                    <option value="">-Status-</option>

                                </select>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-2 text-end">
                            <button type="button" class="btn btn-light" wire:click="clear">Clear</button>
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
</div>

@script
<script>
    document.addEventListener('livewire:initialized', function() {
        var calendarEl = document.getElementById('incoming_requests_calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            timeZone: 'UTC',
            themeSystem: 'bootstrap5',
            initialView: 'dayGridMonth',
            height: 650,
            headerToolbar: {
                left: 'dayGridMonth,listWeek,timeGridWeek,timeGridDay',
                center: 'title',
                right: 'prev,today,next' // user can switch between the two
            },
            selectable: true,
            events: [{
                    id: '1',
                    title: 'Project Meeting',
                    start: '2025-02-10T10:00:00',
                    end: '2025-02-10T11:00:00',
                    description: 'Discussion about project deliverables.',
                    backgroundColor: '#f39c12',
                    borderColor: '#f39c12'
                },
                {
                    id: '2',
                    title: 'Client Presentation',
                    start: '2025-02-12T14:00:00',
                    end: '2025-02-12T15:30:00',
                    description: 'Presenting Q1 updates to the client.',
                    backgroundColor: '#00a65a',
                    borderColor: '#00a65a'
                },
                {
                    id: '3',
                    title: 'Team Lunch',
                    start: '2025-02-14T12:00:00',
                    end: '2025-02-14T13:30:00',
                    description: 'Celebration for reaching the milestone.',
                    backgroundColor: '#0073b7',
                    borderColor: '#0073b7'
                },
                {
                    id: '4',
                    title: 'Code Review',
                    start: '2025-02-15T09:00:00',
                    end: '2025-02-15T10:30:00',
                    description: 'Reviewing the new code changes.',
                    backgroundColor: '#dd4b39',
                    borderColor: '#dd4b39'
                },
                {
                    id: '5',
                    title: 'Weekly Standup',
                    start: '2025-02-17T08:30:00',
                    end: '2025-02-17T09:00:00',
                    description: 'Regular weekly sync with the team.',
                    backgroundColor: '#605ca8',
                    borderColor: '#605ca8'
                }
            ],

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
                    html: '<div class="fc-event-time">' + timeHtml + '</div><div class="fc-event-title">' + arg.event.title + '</div>'
                };
            },

            eventClick: function(info) {
                // Trigger Livewire event to show details
                $wire.dispatch('show-details', {
                    key: info.event.id
                });
            },

            dateClick: function(info) {
                // Change to dayGridDay view on date click
                calendar.changeView('timeGridDay', info.dateStr);
            }
        });

        calendar.render();
    });
</script>
@endscript
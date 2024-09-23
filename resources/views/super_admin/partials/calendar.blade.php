<!-- resources/views/partials/calendar.blade.php -->

<div id="calendar"></div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar/main.min.css" />
<script src="https://cdn.jsdelivr.net/npm/fullcalendar/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/moment/min/moment.min.js"></script>

<!-- Modal for event details -->
<div class="modal fade" id="eventModal" tabindex="-1" role="dialog" aria-labelledby="eventModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body" id="modalContent">
                <!-- Modal content will be dynamically inserted here -->
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const calendarEl = document.getElementById('calendar');

        const calendar = new FullCalendar.Calendar(calendarEl, {
            headerToolbar: {
                left: 'title',
                center: '',
                right: 'prev,next today'
            },
            initialView: 'dayGridMonth',
            events: '{{ route("events.get") }}', // Laravel route that fetches events
            eventContent: function(info) {
                return {
                    html: `
                        <div class="custom-event">
                            <strong>${info.event.title}</strong><br>
                            <small>${info.event.extendedProps.start_time} - ${info.event.extendedProps.end_time}</small>
                        </div>
                    `
                };
            },

            eventClick: function(info) {
                const date = moment(info.event.start).format('MMMM DD, YYYY');
                const time = moment(info.event.start).format('h:mm A')+ " to " + moment(info.event.end).format('h:mm A');
                const ownerName = info.event.extendedProps.first_name + " " +
                                  (info.event.extendedProps.middle_name || '') + " " +
                                  info.event.extendedProps.last_name;

                // Build the content for the modal
                const modalContent = `
                    <div class="modal-header">
                        <h5 class="modal-title">Event Details</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p><strong>Event:</strong> ${info.event.title}</p>
                        <p><strong>Description:</strong> ${info.event.extendedProps.description || 'No description available'}</p>
                        <p><strong>Date:</strong> ${date}</p>
                        <p><strong>Time:</strong> ${time}</p>
                        <p><strong>Owner:</strong> ${ownerName}</p>
                    </div>
                     <a href="/event/${info.event.id}" class="btn btn-primary">View Event</a>
                `;

                // Create a Bootstrap Modal
                const modal = new bootstrap.Modal(document.getElementById('eventModal'), {
                    backdrop: 'static',
                    keyboard: true
                });

                document.getElementById('modalContent').innerHTML = modalContent;
                modal.show();
            },
        });

        calendar.render();
    });
</script>
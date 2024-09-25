document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');
    let calendar;

    // Function to fetch and reload events based on filter
    function loadCalendarEvents(filter = 'all') {
        if (calendar) {
            calendar.destroy(); // Destroy previous calendar instance if exists
        }

        calendar = new FullCalendar.Calendar(calendarEl, {
            headerToolbar: {
                left: 'title',
                center: '',
                right: 'prev,next today'
            },
            initialView: 'dayGridMonth',
            events: function(fetchInfo, successCallback, failureCallback) {
                // Fetch events based on filter
                fetch(`/get-events?filter=${filter}`)
                    .then(response => response.json())
                    .then(events => successCallback(events))
                    .catch(error => failureCallback(error));
            },
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
                const time = moment(info.event.start).format('h:mm A') + " to " + moment(info.event.end).format('h:mm A');
                const ownerName = info.event.extendedProps.first_name + " " +
                                  (info.event.extendedProps.middle_name || '') + " " +
                                  info.event.extendedProps.last_name;

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

                const modal = new bootstrap.Modal(document.getElementById('eventModal'), {
                    backdrop: 'static',
                    keyboard: true
                });

                document.getElementById('modalContent').innerHTML = modalContent;
                modal.show();
            },
        });

        calendar.render();
    }

    // Load the calendar initially with all events
    loadCalendarEvents();

    // Listen for changes in the dropdown and reload the calendar based on the selected filter
    document.getElementById('calendarFilter').addEventListener('change', function() {
        const selectedFilter = this.value;
        loadCalendarEvents(selectedFilter);
    });
});

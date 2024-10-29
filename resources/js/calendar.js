document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');
    let calendar;

    // Function to sanitize HTML and prevent HTML tags from rendering
    function sanitizeHTML(str) {
        var temp = document.createElement('div');
        temp.textContent = str;
        return temp.innerHTML;
    }

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
                // Truncate the event title if it exceeds 12 characters
                let eventTitle = sanitizeHTML(info.event.title.length > 12 ? info.event.title.substring(0, 12) + '...' : info.event.title);
                
                return {
                    html: `
                        <div class="custom-event" style="background-color: #003366; color: white; padding: 2px 6px; border-radius: 5px; text-align: center; display: inline-block; font-size: 10px; margin: 2px; width: 100px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                            <strong>${eventTitle}</strong><br>
                        </div>
                    `
                };
            },
            eventClick: function(info) {
                const eventTitle = sanitizeHTML(info.event.title); // Sanitize title
                const date = moment(info.event.start).format('MMMM DD, YYYY');
                const start_time = moment(info.event.start).format('h:mm A');
                const end_time = moment(info.event.end).format('h:mm A');
                const ownerName = sanitizeHTML(info.event.extendedProps.first_name + " " +
                                (info.event.extendedProps.middle_name || '') + " " +
                                info.event.extendedProps.last_name);

                const maxLength = 125; // Set the maximum number of characters for the description
                const description = info.event.extendedProps.description 
                                    ? (info.event.extendedProps.description.length > maxLength 
                                    ? sanitizeHTML(info.event.extendedProps.description.substring(0, maxLength) + '...') 
                                    : sanitizeHTML(info.event.extendedProps.description)) 
                                    : 'No description available';

                const modalContent = `
                    <div class="modal-header">
                        <h5 class="modal-title">Event Details</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p><strong>Event:</strong> ${eventTitle}</p>
                        <p><strong>Description:</strong> ${description}</p>
                        <p><strong>Date:</strong> ${date}</p>
                        <p><strong>Time:</strong> ${start_time} - ${end_time}</p>
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

    // Load the calendar initially based on the selected filter when the page is loaded
    const initialFilter = document.getElementById('calendarFilter').value; // Get the current value of the filter dropdown
    loadCalendarEvents(initialFilter); // Load the calendar with the initial filter

    // Listen for changes in the dropdown and reload the calendar based on the selected filter
    document.getElementById('calendarFilter').addEventListener('change', function() {
        const selectedFilter = this.value;
        loadCalendarEvents(selectedFilter); // Reload calendar with the new filter
    });
});

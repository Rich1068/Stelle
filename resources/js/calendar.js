document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');
    const filterDropdown = document.getElementById('calendarFilter');
    let calendar;

    function sanitizeHTML(str) {
        const temp = document.createElement('div');
        temp.textContent = str;
        return temp.innerHTML;
    }

    function loadCalendarEvents(filter = 'all') {
        if (calendar) calendar.destroy();

        calendar = new FullCalendar.Calendar(calendarEl, {
            headerToolbar: {
                left: 'title',
                center: '',
                right: 'prev,next today'
            },
            initialView: 'dayGridMonth',
            events: function(fetchInfo, successCallback, failureCallback) {
                fetch(`/get-events?filter=${filter}`)
                    .then(response => response.json())
                    .then(events => {
                        events = events.map(event => {
                            // Set allDay to true only if the event spans multiple days
                            if (event.start_date !== event.end_date) {
                                event.allDay = true;
                            } else {
                                event.allDay = false;
                            }
                            return event;
                        });
                        successCallback(events);
                    })
                    .catch(error => failureCallback(error));
            },
            eventContent: function(info) {
                const eventTitle = sanitizeHTML(info.event.title);

                return {
                    html: `
                    <div class="custom-event-label">
                        <span class="custom-event-title">${eventTitle}</span>
                    </div>
                    `
                };
            },
            eventClick: function(info) {
                const { title, start, end, extendedProps, id } = info.event;
                const startDate = moment(start).format('MMMM DD, YYYY');
                const endDate = moment(end).format('MMMM DD, YYYY');
                const startTime = moment(start).format('h:mm A');
                const endTime = moment(extendedProps.end_time, 'HH:mm:ss').format('h:mm A');
                const ownerName = sanitizeHTML(`${extendedProps.first_name || 'Unknown'} ${extendedProps.middle_name || ''} ${extendedProps.last_name || 'Unknown'}`);
                const description = extendedProps.description 
                    ? sanitizeHTML(extendedProps.description.length > 125 ? extendedProps.description.substring(0, 125) + '...' : extendedProps.description) 
                    : 'No description available';

                const modalContent = `
                    <div class="modal-header">
                        <h5 class="modal-title">Event Details</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p><strong>Event:</strong> ${sanitizeHTML(title)}</p>
                        <p><strong>Description:</strong> ${description}</p>
                        <p><strong>Date:</strong> ${startDate} ${startDate !== endDate ? ' - ' + endDate : ''}</p>
                        <p><strong>Time:</strong> ${info.event.allDay ? 'All Day' : `${startTime} - ${endTime}`}</p>
                        <p><strong>Owner:</strong> ${ownerName}</p>
                    </div>
                    <a href="/event/${id}" class="btn btn-primary">View Event</a>
                `;

                showModal(modalContent);
            }
        });

        calendar.render();
    }

    function showModal(content) {
        const modal = new bootstrap.Modal(document.getElementById('eventModal'), {
            backdrop: 'static',
            keyboard: true
        });
        document.getElementById('modalContent').innerHTML = content;
        modal.show();
    }

    const initialFilter = filterDropdown.value;
    loadCalendarEvents(initialFilter);

    filterDropdown.addEventListener('change', function() {
        const selectedFilter = this.value;
        loadCalendarEvents(selectedFilter);
    });
});

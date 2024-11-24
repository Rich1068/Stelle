@extends('layouts.app')

@section('body')
<div class="container-fluid">
        <div class="top-container my-4">
            <h2 class="font-weight-bold">
                <i class="fas fa-calendar-alt"></i> Event Attendance for {{ $event->title }}
            </h2>
        </div>


    <div class="row">
        <!-- Left Side: QR Code Scanner -->
        <div class="col-lg-4 col-md-12 mb-4">
            <div class="card">
                <div class="card-header text-center">
                    <h4 class ="m-0 font-weight-bold text-primary">QR Code Scanner</h4>
                </div>
                <div class="card-body">
                    <div id="qrScanner" style="width: 100%; height: auto; border: 1px solid #ccc;"></div>
                    <p class="text-center mt-3" id="scannerFeedback">Initializing scanner...</p>
                    <div class="text-center mt-3">
                        <button id="toggleCamera" class="btn btn-primary">Turn Camera Off</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side: Attendance Logs -->
        <div class="col-lg-8 col-md-12">
            <div class="card">
                <div class="card-header text-center">
                    <h4 class ="m-0 font-weight-bold text-primary">Attendance Logs</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="attendanceLogsTable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Participant Name</th>
                                    <th>Email</th>
                                    <th>Scanned At</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($logs as $index => $log)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            {{ $log->user->first_name ?? 'Unknown' }}
                                            {{ $log->user->last_name ?? '' }}
                                        </td>
                                        <td>{{ $log->user->email ?? 'N/A' }}</td>
                                        <td>{{ \Carbon\Carbon::parse($log->scanned_at)->setTimezone('Asia/Manila')->format('Y-m-d h:i:s A') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

<style>
    /* General table styles */
.table-responsive {
  overflow-x: auto;
  -webkit-overflow-scrolling: touch; 
}

.table {
  width: 100%;
  border-collapse: collapse;
  word-wrap: break-word; 
}

.table th, .table td {
  text-align: center;
  vertical-align: middle;
  word-break: break-word; 
  font-size: 1rem; 
  padding: 8px;
}


@media (max-width: 768px) {
  .table th, .table td {
    font-size: 0.85rem; 
    padding: 6px; 
  }

  .table th {
    font-weight: bold; 
    background-color: #f8f9fa; 
  }

  .table td {
    white-space: normal; 
  }
}


@media (max-width: 576px) {
  .table th, .table td {
    font-size: 0.8rem; 
    padding: 4px; 
  }
}


@media (min-width: 992px) {
  .table th, .table td {
    font-size: 1.1rem; 
    padding: 10px;
  }
}

    #qrScanner {
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        height: 200px;
    }

.page-item.active .page-link {
  background-color: transparent !important;
  border-color: transparent !important;
  color: inherit !important;
  font-weight: normal !important;
}

.pagination {
  margin-top: 20px !important;
  padding-bottom: 40px !important;
  display: flex !important;
  justify-content: center !important;
  list-style-type: none !important;
  margin-top: 10px !important;
  padding: 0 !important;
  align-items: center !important;
  margin-left: auto !important;
  margin-right: auto !important;
}

.pagination a, .pagination span {
  display: inline-block !important;
  color: grey !important;
  text-decoration: none !important;
  background-color: transparent !important;
  border: none !important;
  padding: 10px 15px !important;
  margin: 0 5px !important;
  font-weight: 600 !important;
  font-size: 1rem !important;
}

.pagination .active span {
  background-color: darkblue !important;
  color: white !important;
  font-weight: 800 !important;
  border-radius: 50% !important;
}

.pagination a:hover {
  background-color: lightgray !important;
  color: white !important;
  border-radius: 50% !important;
}

@media (max-width: 768px) {
  .pagination {
    justify-content: center !important;
    margin: auto !important;
  }

  .pagination a, .pagination span {
    padding: 6px 8px !important;
    font-size: 0.85rem !important;
    margin: 0 3px !important; /* Closer numbers */
  }

  .pagination .active span {
    font-weight: 700 !important;
  }
}


</style>
@endsection

@section('scripts')
<!-- Include DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/html5-qrcode/minified/html5-qrcode.min.js"></script>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        const qrScanner = document.getElementById('qrScanner');
        const feedbackElement = document.getElementById('scannerFeedback');
        const toggleCameraButton = document.getElementById('toggleCamera');
        let lastScanTime = 0;
        let html5QrCode = null;
        let isCameraOn = true;

        // Initialize DataTables
        $(document).ready(function () {
            $('#attendanceLogsTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: `/events/{{ $event->id }}/logs`,
                    type: 'GET',
                },
                columns: [
                    { data: null, name: null }, // Placeholder for the `#` column
                    { data: 'name', name: 'name' },
                    { data: 'email', name: 'email' },
                    { data: 'scanned_at', name: 'scanned_at' },
                ],
                order: [[3, 'desc']], // Default order by scanned_at descending
                columnDefs: [
                    {
                        targets: 0, // Target the first column (the `#` column)
                        orderable: false, // Prevent sorting on the `#` column itself
                        render: function (data, type, row, meta) {
                            const table = $('#attendanceLogsTable').DataTable();
                            const info = table.page.info();
                            const ascending = table.order()[0][1] === 'asc'; // Check current sort direction

                            if (ascending) {
                                // Global ascending row number for filtered dataset
                                return meta.row + 1 + (info.start);
                            } else {
                                // Global descending row number for filtered dataset
                                return info.recordsDisplay - (meta.row + info.start);
                            }
                        },
                    },
                ],
                
            });
        });

        // Initialize QR Code Scanner
        function startScanner() {
            html5QrCode = new Html5Qrcode("qrScanner");
            html5QrCode.start(
                { facingMode: "environment" },
                {
                    fps: 1,
                    qrbox: { width: 200, height: 200 },
                },
                (decodedText) => {
                    const currentTime = new Date().getTime();
                    if (currentTime - lastScanTime > 4000) {
                        lastScanTime = currentTime;
                        console.log("QR Code detected:", decodedText);
                        feedbackElement.textContent = `QR Code Scanned: ${decodedText}`;
                        markAttendance(decodedText);
                    } else {
                        feedbackElement.textContent = "Scanning too quickly. Please wait...";
                    }
                },
                (errorMessage) => {
                    feedbackElement.textContent = "Scanning for QR codes...";
                }
            ).catch(err => {
                console.error("Error initializing QR code scanner:", err);
                feedbackElement.textContent = "Error initializing scanner.";
            });
        }

        function stopScanner() {
            if (html5QrCode) {
                html5QrCode.stop().then(() => {
                    console.log("Camera stopped.");
                    html5QrCode.clear();
                }).catch(err => {
                    console.error("Error stopping QR code scanner:", err);
                });
            }
        }

        // Toggle Camera On/Off
        toggleCameraButton.addEventListener('click', function () {
            if (isCameraOn) {
                stopScanner();
                toggleCameraButton.textContent = "Turn Camera On";
                feedbackElement.textContent = "Camera is off.";
            } else {
                startScanner();
                toggleCameraButton.textContent = "Turn Camera Off";
            }
            isCameraOn = !isCameraOn;
        });

        // Start Scanner on Page Load
        startScanner();

        // Function to mark attendance using AJAX
        function markAttendance(decodedText) {
            const token = decodedText.split('/').pop();
            const url = `/event/{{ $event->id }}/qr/${token}?system=true`;

            fetch(url, {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    feedbackElement.textContent = "Attendance marked successfully!";
                    updateLogs(data); // Dynamically update logs
                } else {
                    feedbackElement.textContent = data.error || "Invalid QR Code!";
                }
            })
            .catch(error => {
                feedbackElement.textContent = "Error marking attendance. Please try again.";
                console.error('Error:', error);
            });
        }
        function formatToLocalTime(utcTime) {
            const localDate = new Date(utcTime);
            return localDate.toLocaleString('en-US', { timeZone: 'Asia/Manila' });
        }

        // Function to dynamically update the logs table
        function updateLogs() {
            const table = $('#attendanceLogsTable').DataTable();
            table.ajax.reload(null, false); // Reload the table data without resetting pagination
        }
        
    });
</script>
@endsection

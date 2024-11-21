@extends('layouts.app')

@section('body')
<div class="container-fluid">
    <h1 class="my-4 text-center">Event Attendance for {{ $event->name }}</h1>

    <div class="row">
        <!-- Left Side: QR Code Scanner -->
        <div class="col-lg-6 col-md-12 mb-4">
            <div class="card">
                <div class="card-header text-center">
                    <h4>QR Code Scanner</h4>
                </div>
                <div class="card-body">
                    <div id="qrScanner" style="width: 100%; height: auto; border: 1px solid #ccc;"></div>
                    <p class="text-center mt-3" id="scannerFeedback">Initializing scanner...</p>
                </div>
            </div>
        </div>

        <!-- Right Side: Attendance Logs -->
        <div class="col-lg-6 col-md-12">
            <div class="card">
                <div class="card-header text-center">
                    <h4>Attendance Logs</h4>
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
                                        <td>{{ $log->scanned_at->format('Y-m-d H:i:s') }}</td>
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
    #qrScanner {
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        height: 300px;
    }
</style>
@endsection

@section('scripts')
<!-- Include DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/html5-qrcode/minified/html5-qrcode.min.js"></script>

<script>
    // Initialize DataTables
    $(document).ready(function () {
        $('#attendanceLogsTable').DataTable({
            "pageLength": 10,
            "order": [[3, "desc"]], // Order by "Scanned At" column descending
        });
    });

    // QR Code Scanner Initialization
    document.addEventListener('DOMContentLoaded', function () {
        const qrScanner = document.getElementById('qrScanner');
        const feedbackElement = document.getElementById('scannerFeedback');

        if (!qrScanner) {
            console.error("QR scanner element not found!");
            feedbackElement.textContent = "Scanner element not available.";
            return;
        }

        const html5QrCode = new Html5Qrcode("qrScanner");

        html5QrCode.start(
            { facingMode: "environment" },
            {
                fps: 30,
                qrbox: { width: 250, height: 250 }, // Adjust as needed
            },
            (decodedText, decodedResult) => {
                console.log("QR Code detected:", decodedText);
                feedbackElement.textContent = `QR Code Scanned: ${decodedText}`;
            },
            (errorMessage) => {
                feedbackElement.textContent = "Scanning for QR codes...";
            }
        ).catch(err => {
            console.error("Error initializing QR code scanner:", err);
            feedbackElement.textContent = "Error initializing scanner.";
        });

        // Function to mark attendance using AJAX
        function markAttendance(token) {
            const url = `/events/{{ $event->id }}/qr/${token}?system=true`;
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

        // Function to dynamically update the logs table
        function updateLogs(data) {
            const table = $('#attendanceLogsTable').DataTable();
            const newRow = table.row.add([
                table.rows().count() + 1,
                `${data.user.first_name} ${data.user.last_name}`,
                data.user.email,
                new Date(data.scanned_at).toLocaleString(),
            ]);
            newRow.draw();
        }
    });
</script>
@endsection

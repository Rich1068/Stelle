@extends('layouts.app')

@section('body')

<div class="top-container mb-4 d-flex align-items-left justify-content-between" style="background-color: #fff; border-radius: 15px; padding: 20px; box-shadow: none;">
    <!-- Left: Manage Evaluation Forms Title -->
    <div class="d-flex align-items-center">
        <h2 class="font-weight-bold mb-0" style="color: #002060;">
            <i class="fas fa-clipboard-list"></i> Manage Evaluation Forms
        </h2>
        <!-- Arrow Button (Dropdown Trigger) -->
        <button id="toggleButton" class="btn custom-btn-light ms-2" type="button" aria-expanded="false" style="border: none; background-color: transparent;">
            <i id="arrowIcon" class="fas fa-chevron-down" style="font-size: 1.5rem; color: #002060;"></i>
        </button>
    </div>

    <!-- Right: Hidden Button -->
    <div id="buttonContainer" style="display: none; margin-left: 10px;" class="button-group">
        <form action="{{ route('evaluation-forms.create') }}" method="get" style="display: inline;">
            <button type="submit" class="btn btn-primary-2" style="border-radius: 20px;">
                <i class="fas fa-plus"></i> Add Evaluation Form
            </button>
        </form>
    </div>
</div>

<div class="container-fluid" style="padding: 0;">
    @if($evaluationForms->isEmpty())
        <p class="text-center">No evaluation forms found.</p>
    @else
        <!-- Search Bar -->
        <div class="search-container" style="margin: 40px auto; max-width: 60%;">
            <input type="text" id="searchInput" placeholder="Search for forms..." class="search-input" onkeyup="filterTable()">
            <button class="search-button"><i class="fas fa-search"></i></button>
        </div>

        <!-- Responsive Table -->
        <div class="table-responsive">
            <table class="table table-striped custom-table text-center" id="dataFilter" style="width: 90%; table-layout: fixed; margin: auto;">
                <thead class="custom-thead">
                    <tr>
                        <th onclick="sortTable(0)">ID <i class="fas fa-sort"></i></th>
                        <th onclick="sortTable(1)">Form Name <i class="fas fa-sort"></i></th>
                        <th onclick="sortTable(2)">Status <i class="fas fa-sort"></i></th>
                        <th onclick="sortTable(3)">Created By <i class="fas fa-sort"></i></th>
                        <th onclick="sortTable(4)">Created At <i class="fas fa-sort"></i></th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    @foreach($evaluationForms as $index => $form)
                    <tr class="table-row" style="background-color: {{ $index % 2 === 0 ? '#f9f9f9' : 'white' }};">
                        <td style="font-size: 14px; word-wrap: break-word;">{{ $form->id }}</td>
                        <td style="font-size: 14px; word-wrap: break-word;">{{ $form->form_name }}</td>
                        <td style="font-size: 14px; word-wrap: break-word;">{{ $form->status->status }}</td>
                        <td style="font-size: 14px; word-wrap: break-word;">{{ $form->creator->first_name }}</td>
                        <td style="font-size: 14px; word-wrap: break-word;">{{ $form->created_at->format('Y-m-d') }}</td>
                        <td>
                            <div class="button-group" style="display: flex; justify-content: center; align-items: center;">
                                <a href="{{ route('evaluation-forms.edit', $form->id) }}" class="btn btn-edit rounded-circle me-2" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-edit" style="color: white; margin: auto;"></i>
                                </a>
                                <form action="{{ route('evaluation-forms.deactivate', $form->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-delete rounded-circle" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;" onclick="return confirm('Are you sure you want to deactivate this form?')">
                                        <i class="fas fa-times" style="color: white; margin: auto;"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

<!-- Styles -->
<style>
    .custom-btn-light {
        background-color: transparent;
        color: #002060;
    }

    .custom-btn-light:hover {
        color: #004080;
    }

    .btn-primary {
        background-color: #001e54;
        color: white;
        border-radius: 20px;
        padding: 10px 15px;
        font-size: 15px;
        font-weight: bold;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-top: 10px;
    }

    @media (max-width: 768px) {
        .btn-primary {
            padding: 5px 10px;
            font-size: 12px;
            margin-top: 5px;
        }

        .button-group {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .search-container {
            max-width: 90%; /* Adjust to fill more space on mobile */
        }

        .custom-table td {
            font-size: 12px; /* Decrease font size on smaller screens */
        }
    }

    @media (min-width: 769px) {
        .button-group {
            display: flex;
            align-items: center;
            gap: 10px;
        }
    }

    .custom-thead {
        background-color: #001e54;
        color: white;
    }

    .custom-thead th {
        padding: 1rem;
        cursor: pointer;
    }

    .custom-table {
        border: none;
        width: 100%;
    }

    .custom-table td, .custom-table th {
        border: none; 
        text-align: center; 
        vertical-align: middle; 
        padding: 10px; /* Reduced padding to fit more content */
        overflow-wrap: break-word; /* Ensure long text wraps */
    }

    .custom-table tbody tr:hover {
        background-color: #f2f2f2; 
    }

    .search-container {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-bottom: 10px;
    }

    .search-input {
        padding: 10px;
        border-radius: 15px 0 0 15px;
        border: 1px solid #ccc;
        transition: border-color 0.3s;
        border-right: none; 
        font-size: 14px; 
        flex: 1; /* Allow the input to take up available space */
    }

    .search-input:focus {
        border-color: black;
        outline: none;
    }

    .search-button {
        padding: 10px;
        border-radius: 0 15px 15px 0;
        border: 1px solid #001e54;
        background-color: #001e54; 
        color: white; 
        cursor: pointer; 
        font-size: 13px; 
    }

    .search-button:hover {
        background-color: #0d3b76; 
    }

    .btn-edit, .btn-delete {
        margin-top: 0; 
        color: white;
        border-radius: 20px;
        padding: 10px; 
        font-size: 15px; 
        font-weight: bold;
        text-align: center; 
        display: inline-flex; 
        border: none; 
        min-width: 40px; 
        height: 40px; 
        cursor: pointer; 
        background-color: #001e54; 
        justify-content: center; 
        align-items: center; 
    }

    .btn-delete {
        background-color: #c9302c; 
    }
</style>

<!-- JavaScript -->
<script>
    document.getElementById("toggleButton").addEventListener("click", function() {
        var buttonContainer = document.getElementById("buttonContainer");
        var arrowIcon = document.getElementById("arrowIcon");

        if (buttonContainer.style.display === "none") {
            buttonContainer.style.display = "flex";
            arrowIcon.classList.remove("fa-chevron-down");
            arrowIcon.classList.add("fa-chevron-up");
        } else {
            buttonContainer.style.display = "none";
            arrowIcon.classList.remove("fa-chevron-up");
            arrowIcon.classList.add("fa-chevron-down");
        }
    });

    function filterTable() {
        const input = document.getElementById('searchInput');
        const filter = input.value.toLowerCase();
        const tableBody = document.getElementById('tableBody');
        const rows = tableBody.getElementsByTagName('tr');

        for (let row of rows) {
            const cells = row.getElementsByTagName('td');
            let rowContainsFilter = false;

            for (let cell of cells) {
                if (cell.innerText.toLowerCase().includes(filter)) {
                    rowContainsFilter = true;
                    break;
                }
            }

            row.style.display = rowContainsFilter ? '' : 'none';
        }
    }

    function sortTable(columnIndex) {
        var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
        table = document.getElementById("dataFilter");
        switching = true;
        dir = "asc";  // Set the sorting direction to ascending

        while (switching) {
            switching = false;
            rows = table.rows;

            // Loop through all table rows (except the headers)
            for (i = 1; i < (rows.length - 1); i++) {
                shouldSwitch = false;
                x = rows[i].getElementsByTagName("TD")[columnIndex];
                y = rows[i + 1].getElementsByTagName("TD")[columnIndex];

                // Compare the two rows based on the current direction
                if (dir == "asc") {
                    if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                        shouldSwitch = true;
                        break;
                    }
                } else if (dir == "desc") {
                    if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                        shouldSwitch = true;
                        break;
                    }
                }
            }

            if (shouldSwitch) {
                // If a switch has been marked, make the switch and mark switching as true
                rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                switching = true;
                switchcount++;
            } else {
                // If no switching was done and the direction is "asc", change direction to "desc"
                if (switchcount == 0 && dir == "asc") {
                    dir = "desc";
                    switching = true;
                }
            }
        }
    }
</script>

@endsection

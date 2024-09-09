@extends('layouts.app')

@section('body')
<div class="container-fluid">

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <div>
    Want to be organize events? Be an admin now!
    <form id="registerAdminForm" action="{{ route('register.admin') }}" method="POST">
            @csrf
            <button type="button" class="btn btn-success" onclick="confirmSubmission()">Register</button>
    </form>
    </div>

    <!-- Hello Container -->
    <div class="card mb-4" style="background-color: #f8f9fc; border-radius: 15px; box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);">
        <div class="card-body">
            <h2 class="name-bold" style="color: #8A2BE2; font-size: 36px; font-weight: bold; margin-bottom: 0;">
                Good Evening <span style="color: black;">User!</span>
            </h2>
            <p style="font-size: 18px; color: gray; margin-top: 0;">How are you feeling?</p>
        </div>
    </div>

    <!-- Mockup Calendar -->
    <div class="row">
        <div class="col-lg-12 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Event Calendar</h6>
                </div>
                <div class="card-body">
                    <table class="table table-bordered text-center">
                        <thead>
                            <tr>
                                <th>Sun</th>
                                <th>Mon</th>
                                <th>Tue</th>
                                <th>Wed</th>
                                <th>Thu</th>
                                <th>Fri</th>
                                <th>Sat</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- First Week -->
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>1</td>
                                <td>2</td>
                                <td>3</td>
                                <td>4</td>
                            </tr>
                            <!-- Second Week -->
                            <tr>
                                <td>5</td>
                                <td>6 <br><span class="text-primary fw-bold">Dev Meet</span></td>
                                <td>7</td>
                                <td>8</td>
                                <td>9 <br><span class="text-info fw-bold">AI Conf</span></td>
                                <td>10</td>
                                <td>11</td>
                            </tr>
                            <!-- Third Week -->
                            <tr>
                                <td>12</td>
                                <td>13</td>
                                <td>14 <br><span class="text-warning fw-bold">Tech Expo</span></td>
                                <td>15</td>
                                <td>16</td>
                                <td>17</td>
                                <td>18 <br><span class="text-success fw-bold">Code Fest</span></td>
                            </tr>
                            <!-- Fourth Week -->
                            <tr>
                                <td>19 <br><span class="text-danger fw-bold">Web Summit</span></td>
                                <td>20</td>
                                <td>21</td>
                                <td>22</td>
                                <td>23 <br><span class="text-primary fw-bold">Data Talk</span></td>
                                <td>24</td>
                                <td>25</td>
                            </tr>
                            <!-- Fifth Week -->
                            <tr>
                                <td>26</td>
                                <td>27 <br><span class="text-info fw-bold">JS Meetup</span></td>
                                <td>28</td>
                                <td>29</td>
                                <td>30 <br><span class="text-warning fw-bold">Cloud Conf</span></td>
                                <td>31</td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
    function confirmSubmission() {
        // Show confirmation dialog
        if (confirm('Are you sure you want to register as an event admin?')) {
            // If confirmed, submit the form
            document.getElementById('registerAdminForm').submit();
        }
        // If not confirmed, do nothing
    }
</script>
@endsection

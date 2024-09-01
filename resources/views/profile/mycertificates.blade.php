@extends('layouts.app')

@section('body')
    <div class="container">
        <h1>Your Certificates</h1>

        @if($user->certificates->isEmpty())
            <p>No certificates found.</p>
        @else
            <ul>
                @foreach($user->certificates as $certificate)
                    <li>
                        <img src="{{ asset($certificate->cert_path)}}" style="height:200px; width:200px">

                        <!-- View Button to open modal -->
                        <button type="button" class="btn btn-primary view-certificate-btn" data-image-url="{{ asset($certificate->cert_path) }}" data-bs-toggle="modal" data-bs-target="#viewCertificateModal">
                            View
                        </button>

                        <!-- Download Button -->
                        <a href="{{ asset($certificate->cert_path) }}" download class="btn btn-success">
                            Download
                        </a>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

    <!-- Modal -->
    <div class="modal fade" id="viewCertificateModal" tabindex="-1" aria-labelledby="viewCertificateModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewCertificateModalLabel">Certificate Preview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <img id="certificateImage" src="" style="width:100%;">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript to handle modal and image update -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
    // Event delegation to handle dynamically loaded buttons
        document.body.addEventListener('click', function(event) {
            if (event.target.matches('.view-certificate-btn')) {
                const imageUrl = event.target.getAttribute('data-image-url');
                const certificateImage = document.querySelector('#certificateImage');
                if (certificateImage) {
                    certificateImage.src = imageUrl;
                }
            }
        });
    });
    </script>
@endsection

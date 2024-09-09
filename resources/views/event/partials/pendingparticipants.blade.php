
<div class="modal fade" id="pendingParticipantsModal" tabindex="-1" role="dialog" aria-labelledby="pendingParticipantsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="pendingParticipantsModalLabel">Pending Participants for {{ $event->title }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Success/Error Alerts -->
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <!-- Participant List -->
                <div class="participant-list-container">
                    @foreach($pendingparticipants as $pendingparticipant)
                        <div class="participant-list-item">
                            <!-- User Information -->
                            <div class="participant-info">
                                <div class="participant-profile">
                                    <img src="{{ $pendingparticipant->user->profile_picture_url }}" alt="{{ $pendingparticipant->user->first_name }}" class="profile-picture">
                                    <div class="participant-details">
                                        <a href="{{ route('profile.view', $pendingparticipant->user->id) }}" class="participant-name">
                                            {{ $pendingparticipant->user->first_name }} {{ $pendingparticipant->user->last_name }}
                                        </a>
                                        <div class="participant-status">{{ $pendingparticipant->status->status }}</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="participant-actions">
                                <form action="{{ route('participants.updateStatus', [$event->id, $pendingparticipant->user_id]) }}" method="POST" class="participant-action-form">
                                    @csrf
                                    <input type="hidden" name="status_id" value="1"> <!-- Accepted -->
                                    <button type="submit" class="btn btn-success">Accept</button>
                                </form>
                                <form action="{{ route('participants.updateStatus', [$event->id, $pendingparticipant->user_id]) }}" method="POST" class="participant-action-form">
                                    @csrf
                                    <input type="hidden" name="status_id" value="2"> <!-- Declined -->
                                    <button type="submit" class="btn btn-danger">Decline</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

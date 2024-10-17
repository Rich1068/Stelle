<form method="post" action="{{ route('role.update', $user->id) }}" id="updateRoleForm" class="password-update-form mt-6 space-y-6">
    @csrf
    @method('patch')

    <!-- Role Selection Dropdown -->
    <div class="form-group">
        <label for="role_id" class="form-label">{{ __('Select Role') }}</label>
        <select name="role_id" id="role_id" class="form-control">
            <option value="1" {{ $user->role_id == 1 ? 'selected' : '' }}>Super Admin</option>
            <option value="2" {{ $user->role_id == 2 ? 'selected' : '' }}>Admin</option>
            <option value="3" {{ $user->role_id == 3 ? 'selected' : '' }}>User</option>
        </select>
        @if ($errors->has('role_id'))
            <span class="text-danger">{{ $errors->first('role_id') }}</span>
        @endif
    </div>

    <!-- Button to Trigger Modal -->
    <div class="form-group mt-4">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#confirmationModal">
            {{ __('Update Role') }}
        </button>
    </div>
</form>

<!-- Modal HTML -->
<div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmationModalLabel">{{ __('Confirm Role Update') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>{{ __('Are you sure you want to update the role for this user?') }}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Cancel') }}</button>
                <button type="button" class="btn btn-primary" id="confirmUpdate">{{ __('Confirm') }}</button>
            </div>
        </div>
    </div>
</div>
<script>
    document.getElementById('confirmUpdate').addEventListener('click', function () {
        document.getElementById('updateRoleForm').submit();
    });
</script>
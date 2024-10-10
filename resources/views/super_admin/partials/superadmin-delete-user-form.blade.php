<section class="delete-profile-section space-y-6">
    <header class="delete-profile-header">
        <h2 class="delete-profile-title text-lg font-medium text-gray-900">
            {{ __('Delete Account') }}
        </h2>

        <p class="delete-profile-description mt-1 text-sm text-gray-600">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </p>
    </header>

    <div class="delete-profile-container-wrapper">
        <button type="button" class="btn btn-danger" x-data @click="$dispatch('open-modal', 'alpineDeleteModal')">
            {{ __('Delete Account') }}
        </button>
    </div>

    <!-- First Alpine.js Confirmation Modal -->
    <div x-data="{ show: false }" x-on:open-modal.window="if ($event.detail === 'alpineDeleteModal') show = true" x-on:keydown.escape.window="show = false">
        <div x-show="show" class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50">
            <div class="bg-white p-4 rounded-lg max-w-lg mx-auto shadow-lg">
                <h5 class="modal-title">{{ __('Are you sure you want to delete this account?') }}</h5>
                <p>{{ __('Once the account is deleted, all of its resources and data will be permanently deleted.') }}</p>
                <div class="mt-4 flex justify-end space-x-2">
                    <button @click="show = false" class="btn btn-secondary">{{ __('Cancel') }}</button>
                    <button type="button" @click="show = false" data-bs-toggle="modal" data-bs-target="#finalDeleteModal" class="btn btn-danger">{{ __('Yes, Proceed') }}</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Second Final Bootstrap Confirmation Modal -->
    <div class="modal fade" id="finalDeleteModal" tabindex="-1" aria-labelledby="finalDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="finalDeleteModalLabel">{{ __('Please confirm deletion') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{ __('This action is irreversible. Are you sure you want to proceed?') }}
                </div>
                <div class="modal-footer">
                    <form method="post" action="{{ route('superadmin.destroyUser', ['id' => $user->id]) }}">
                        @csrf
                        @method('delete')
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                        <button type="submit" class="btn btn-danger">{{ __('Confirm Delete') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Include Bootstrap JS (if not already included) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

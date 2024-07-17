<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Event Information') }}
        </h2>
        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your event information") }}
        </p>
    </header>


    <form method="post" action="{{ route('event.update', $event->id) }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <!-- title -->
        <div>
            <x-input-label for="title" :value="__('Title')" />
            <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title', $event->title)" required autofocus autocomplete="title" />
            <x-input-error class="mt-2" :messages="$errors->get('title')" />
        </div>

        <!-- description -->
        <div>
            <x-input-label for="description" :value="__('Description')" />
            <textarea id="description" name="description" class="mt-1 block w-full" required>{{ old('description', $event->description) }}</textarea>
            <x-input-error class="mt-2" :messages="$errors->get('description')" />
        </div>

        <!-- Date -->
        <div>
            <x-input-label for="date" :value="__('Date')" />
            <x-text-input id="date" name="date" type="date" class="mt-1 block w-full" :value="old('date', $event->date)" required />
            <x-input-error class="mt-2" :messages="$errors->get('date')" />
        </div>

        <!-- Address -->
        <div>
            <x-input-label for="address" :value="__('Address')" />
            <x-text-input id="address" name="address" type="text" class="mt-1 block w-full" :value="old('address', $event->address)" required />
            <x-input-error class="mt-2" :messages="$errors->get('address')" />
        </div>

        <!-- Start Time -->
        <div>
            <x-input-label for="start_time" :value="__('Start Time')" />
            <x-text-input id="start_time" name="start_time" type="time" class="mt-1 block w-full" :value="old('start_time', \Carbon\Carbon::parse($event->start_time)->format('H:i'))" required />
            <x-input-error class="mt-2" :messages="$errors->get('start_time')" />
        </div>

        <!-- End Time -->
        <div>
            <x-input-label for="end_time" :value="__('End Time')" />
            <x-text-input id="end_time" name="end_time" type="time" class="mt-1 block w-full" :value="old('end_time', \Carbon\Carbon::parse($event->end_time)->format('H:i'))" required />
            <x-input-error class="mt-2" :messages="$errors->get('end_time')" />
        </div>

        <!-- Capacity -->
        <div>
            <x-input-label for="capacity" :value="__('Capacity')" />
            <x-text-input id="capacity" name="capacity" type="number" class="mt-1 block w-full" :value="old('capacity', $event->capacity)" required />
            <x-input-error class="mt-2" :messages="$errors->get('capacity')" />
        </div>

        <div>
            <x-input-label for="mode" :value="__('Mode')" />
            <select id="mode" name="mode" class="block mt-1 w-full">
                <option value="">{{ __('Select Mode') }}</option>
                <option value="onsite" {{ old('mode', $event->mode) == 'onsite' ? 'selected' : '' }}>{{ __('Onsite') }}</option>
                <option value="virtual" {{ old('mode', $event->mode) == 'virtual' ? 'selected' : '' }}>{{ __('Virtual') }}</option>
            </select>
            <x-input-error :messages="$errors->get('mode')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="event_banner" :value="__('Event Banner')" />
            <x-text-input id="event_banner" name="event_banner" type="file" class="mt-1 block w-full" accept="image/*" onchange="previewImage(event)" />
            <x-input-error class="mt-2" :messages="$errors->get('event_banner')" />
            @if($event->event_banner)
                <img id="image_preview" src="{{ asset($event->event_banner) }}" alt="Event Banner" class="mt-4" style="max-width: 75px;">
                <div class="mt-2">
                    <label for="remove_event_banner">
                        <input type="checkbox" name="remove_event_banner" id="remove_event_banner"> {{ __('Remove Event Banner') }}
                    </label>
                </div>
            @endif
        </div>


        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'event-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
<script>
function previewImage(event) {
    var reader = new FileReader();
    reader.onload = function() {
        var output = document.getElementById('image_preview');
        output.src = reader.result;
        output.style.display = 'block';
    }
    reader.readAsDataURL(event.target.files[0]);
    
}

</script>
</section>
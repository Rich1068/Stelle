<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EventUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        $eventId = $this->route('id');
        return [
            'title' => ['required', 'string', 'max:255', Rule::unique('events', 'title')->ignore($eventId)],
            'description' => ['required', 'string', 'max:500'],
            'start_date' => ['required', 'date', 'date_format:Y-m-d'],
            'end_date' => ['required', 'date', 'date_format:Y-m-d', 'after_or_equal:start_date'],
            'mode' => ['required', 'string', 'in:onsite,virtual'],
            'address' => ['required', 'string', 'max:255'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i'], 
            'capacity' => ['required', 'integer', 'min:1'],
            'event_banner' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'remove_event_banner' => ['nullable', 'boolean'],
            'organization_id' => [
                'required',
                function ($attribute, $value, $fail) {

                    if (!is_null($value) && !\App\Models\Organization::where('id', $value)->exists()) {
                        $fail('The selected organization is invalid.');
                    }
                },
            ],
        ];
    }
    
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->start_date === $this->end_date) {
                if (strtotime($this->start_time) >= strtotime($this->end_time)) {
                    $validator->errors()->add('end_time', "End time must be after start time on the same day.");
                }
            }
        });
    }
}
<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class superadminProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255',Rule::unique(User::class)->ignore($this->route('id'))],
            'profile_picture' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'gender' => ['nullable', 'string', 'in:male,female'],
            'country_id' => ['nullable', 'int'],
            'description' => ['nullable', 'string', 'max:255'],
            'contact_number' => ['nullable', 'string', 'max:11', 'regex:/^\d+$/'],
            'birthdate' => ['nullable', 'date', 'before:today', 'after:1900-01-01'],
        ];
    }
}
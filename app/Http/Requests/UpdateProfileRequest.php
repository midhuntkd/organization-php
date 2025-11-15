<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'user_image' => ['nullable', 'image', 'max:2048'],
            'blood_group' => ['nullable', 'string', Rule::in(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'])],

            'address_line1' => ['nullable', 'string', 'max:255'],
            'address_line2' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:100'],
            'zipcode' => ['nullable', 'string', 'max:10'],
            'norka_registration_number' => ['nullable', 'string', 'max:50'],
            'permanent_home_address' => ['nullable', 'string', 'max:500'],
            'insurance_provider' => ['nullable', 'string', 'max:100'],
            'policy_number' => ['nullable', 'string', 'max:100'],
            'expiry_date' => ['nullable', 'date'],
            'amount' => ['nullable', 'numeric', 'min:0'],
        ];
    }
}

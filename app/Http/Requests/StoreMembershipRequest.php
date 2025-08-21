<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMembershipRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $orgId = $this->route('organization')->id;
        $id = $this->route('membership')?->id;

        return [
            'name'                    => ['required', 'string', 'max:255', 'unique:memberships,name,' . $id . ',id,organization_id,' . $orgId],
            'membership_category_id'  => ['required', 'exists:membership_categories,id'],
            'prefix'                  => ['nullable', 'string', 'max:20'], // NEW
            'joining_fee'             => ['required', 'numeric', 'min:0'],
            'monthly_fee'             => ['required', 'numeric', 'min:0'],
            'status'                  => ['required', 'in:active,inactive'],
            'is_default'              => ['boolean'],
        ];
    }
}

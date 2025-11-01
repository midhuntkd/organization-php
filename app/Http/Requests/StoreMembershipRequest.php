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
            //'membership_category_id' => ['required', 'exists:membership_categories,id'],
            'prefix' => ['nullable', 'string', 'max:10'],
            'joining_fee' => ['nullable', 'numeric', 'min:0'],
            'monthly_fee' => ['nullable', 'numeric', 'min:0'],
            'status' => ['required', 'in:active,inactive'],
            'is_default' => ['nullable', 'boolean'],

            'rule_ids' => ['nullable', 'array'],
            'rule_ids.*' => ['exists:membership_rules,id'],

            'benefit_ids' => ['nullable', 'array'],
            'benefit_ids.*' => ['exists:membership_benefits,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Membership name is required.',
            //'membership_category_id.required' => 'Please select a membership category.',
            'status.in' => 'Invalid status provided.',
        ];
    }
}

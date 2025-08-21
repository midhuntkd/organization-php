<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMembershipCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $orgId = $this->route('organization')->id;
        $id = $this->route('membership_category')?->id;

        return [
            'name'        => ['required', 'string', 'max:255', 'unique:membership_categories,name,' . $id . ',id,organization_id,' . $orgId],
            'prefix'      => ['required', 'string', 'max:20', 'unique:membership_categories,prefix,' . $id . ',id,organization_id,' . $orgId],
            'description' => ['nullable', 'string'],
            'status'      => ['required', 'in:active,inactive'],
            'is_default'  => ['boolean'],
        ];
    }
}

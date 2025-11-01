@php
    $name = old('name', $membership->name ?? '');
    $categoryId = old('membership_category_id', $membership->membership_category_id ?? '');
    $prefix = old('prefix', $membership->prefix ?? ($organization->prefix ?? ''));
    $joiningFee = old('joining_fee', $membership->joining_fee ?? 0);
    $monthlyFee = old('monthly_fee', $membership->monthly_fee ?? 0);
    $status = old('status', $membership->status ?? 'active');
    $is_default = old('is_default', $membership->is_default ?? false);
@endphp

@if ($errors->any())
    <div class="alert alert-danger">
        <strong>Please fix the following errors:</strong>
        <ul class="mb-0">
            @foreach ($errors->all() as $e)
                <li>{{ $e }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label" for="name">Name <span class="text-danger">*</span></label>
        <input id="name" type="text" name="name" class="form-control @error('name') is-invalid @enderror"
            value="{{ $name }}" required maxlength="255">
        @error('name')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>
    <input type="hidden" name="membership_category_id" value="2">

    {{-- REMOVED: Membership category --}}
    <!-- <div class="col-md-6 mb-3">
        <label class="form-label" for="membership_category_id">Category <span class="text-danger">*</span></label>
        <select id="membership_category_id" name="membership_category_id"
            class="form-select @error('membership_category_id') is-invalid @enderror" required>
            <option value="">-- Select Active Category --</option>
            @foreach ($categories as $cat)
<option value="{{ $cat->id }}" @selected($categoryId == $cat->id)>
                {{ $cat->prefix }} — {{ $cat->name }}
            </option>
@endforeach
        </select>
        @error('membership_category_id')
    <div class="invalid-feedback d-block">{{ $message }}</div>
@enderror
    </div> -->

    {{-- NEW: Membership prefix (defaults to organization prefix) --}}
    <div class="col-md-6 mb-3">
        <label class="form-label" for="prefix">Membership Prefix</label>
        <input id="prefix" type="text" name="prefix" class="form-control @error('prefix') is-invalid @enderror"
            value="{{ $prefix }}" maxlength="20" placeholder="e.g., GOLD">
        @error('prefix')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
        <small class="text-muted">If left blank, the organization prefix ({{ $organization->prefix ?? 'ORG' }}) will be
            used.</small>
    </div>

    {{-- Display generated unique_id on edit (read-only). On create it’s generated after save. --}}
    @isset($membership)
        <div class="col-md-6 mb-3">
            <label class="form-label">Unique ID</label>
            <input type="text" class="form-control" value="{{ $membership->unique_id }}" readonly>
            <small class="text-muted">Auto-generated from prefix + sequence (e.g., ACME10001).</small>
        </div>
    @endisset

    <div class="col-md-6 mb-3">
        <label class="form-label" for="joining_fee">Joining Fee</label>
        <input id="joining_fee" type="number" step="0.01" min="0" name="joining_fee"
            class="form-control @error('joining_fee') is-invalid @enderror" value="{{ $joiningFee }}">
        @error('joining_fee')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label" for="monthly_fee">Monthly Fee</label>
        <input id="monthly_fee" type="number" step="0.01" min="0" name="monthly_fee"
            class="form-control @error('monthly_fee') is-invalid @enderror" value="{{ $monthlyFee }}">
        @error('monthly_fee')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label" for="status">Status <span class="text-danger">*</span></label>
        <select id="status" name="status" class="form-select @error('status') is-invalid @enderror" required>
            <option value="active" @selected($status === 'active')>Active</option>
            <option value="inactive" @selected($status === 'inactive')>Inactive</option>
        </select>
        @error('status')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 mb-3 d-flex align-items-center">
        <div class="form-check mt-4">
            <input class="form-check-input @error('is_default') is-invalid @enderror" type="checkbox" name="is_default"
                id="is_default" value="1" @checked($is_default)>
            <label class="form-check-label" for="is_default">Set as default membership</label>
            @error('is_default')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
    </div>

    {{-- <div class="mb-3">
        <label class="form-label">Select Rules</label>
        <select name="rule_ids[]" class="form-select" multiple>
            @foreach ($rules as $rule)
                <option value="{{ $rule->id }}">{{ $rule->title }}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Select Benefits</label>
        <select name="benefit_ids[]" class="form-select" multiple>
            @foreach ($benefits as $benefit)
                <option value="{{ $benefit->id }}">{{ $benefit->title }}</option>
            @endforeach
        </select>
    </div> --}}

    {{-- === RULES CHECKBOXES === --}}
    <div class="mb-3">
        <label class="form-label d-block">Select Rules</label>

        @forelse($rules as $rule)
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="rule_ids[]" id="rule_{{ $rule->id }}"
                    value="{{ $rule->id }}" {{-- Keep checked if old input or editing existing membership --}} @if (
                        (isset($membership) && in_array($rule->id, $membership->rules->pluck('id')->toArray())) ||
                            (is_array(old('rule_ids')) && in_array($rule->id, old('rule_ids')))) checked @endif>
                <label class="form-check-label" for="rule_{{ $rule->id }}">
                    {{ $rule->title }}
                </label>
            </div>
        @empty
            <p class="text-muted">No rules available yet.</p>
        @endforelse
    </div>

    {{-- === BENEFITS CHECKBOXES === --}}
    <div class="mb-3">
        <label class="form-label d-block">Select Benefits</label>

        @forelse($benefits as $benefit)
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="benefit_ids[]"
                    id="benefit_{{ $benefit->id }}" value="{{ $benefit->id }}" {{-- Keep checked if old input or editing existing membership --}}
                    @if (
                        (isset($membership) && in_array($benefit->id, $membership->benefits->pluck('id')->toArray())) ||
                            (is_array(old('benefit_ids')) && in_array($benefit->id, old('benefit_ids')))) checked @endif>
                <label class="form-check-label" for="benefit_{{ $benefit->id }}">
                    {{ $benefit->title }}
                </label>
            </div>
        @empty
            <p class="text-muted">No benefits available yet.</p>
        @endforelse
    </div>


</div>

<div class="d-flex gap-2">
    <button type="submit" class="btn btn-primary">{{ $submitText ?? 'Save' }}</button>
    <a href="{{ route('orgadmin.memberships.index', $organization->slug) }}" class="btn btn-light">Cancel</a>
</div>

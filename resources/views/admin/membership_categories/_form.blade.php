@php
$name = old('name', $membership_category->name ?? '');
$prefix = old('prefix', $membership_category->prefix ?? '');
$description = old('description', $membership_category->description ?? '');
$prefix = old('prefix', $membership->prefix ?? ($organization->prefix ?? '')); 
$status = old('status', $membership_category->status ?? 'active');
$is_default = old('is_default', $membership_category->is_default ?? false);
@endphp

{{-- Top-level error summary (optional but helpful) --}}
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
        <input id="name"
            type="text"
            name="name"
            class="form-control @error('name') is-invalid @enderror"
            value="{{ $name }}"
            required
            maxlength="255">
        @error('name')
        <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label" for="prefix">Prefix <span class="text-danger">*</span></label>
        <input id="prefix"
            type="text"
            name="prefix"
            class="form-control @error('prefix') is-invalid @enderror"
            value="{{ $prefix }}"
            required
            maxlength="20"
            placeholder="e.g., GOLD, SILV">
        @error('prefix')
        <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12 mb-3">
        <label class="form-label" for="description">Description</label>
        <textarea id="description"
            name="description"
            class="form-control @error('description') is-invalid @enderror"
            rows="3"
            placeholder="Optional">{{ $description }}</textarea>
        @error('description')
        <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label" for="status">Status <span class="text-danger">*</span></label>
        <select id="status"
            name="status"
            class="form-select @error('status') is-invalid @enderror"
            required>
            <option value="active" @selected($status==='active' )>Active</option>
            <option value="inactive" @selected($status==='inactive' )>Inactive</option>
        </select>
        @error('status')
        <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 mb-3 d-flex align-items-center">
        <div class="form-check mt-4">
            <input class="form-check-input @error('is_default') is-invalid @enderror"
                type="checkbox" name="is_default" id="is_default" value="1"
                @checked($is_default)>
            <label class="form-check-label" for="is_default">Set as default category</label>
            @error('is_default')
            <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<div class="d-flex gap-2">
    <button type="submit" class="btn btn-primary">{{ $submitText ?? 'Save' }}</button>
    <a href="{{ route('orgadmin.membership-categories.index', $organization->slug) }}" class="btn btn-light">Cancel</a>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // focus the first field with an error
        const invalid = document.querySelector('.is-invalid');
        if (invalid && typeof invalid.focus === 'function') invalid.focus();
    });
</script>
@endpush
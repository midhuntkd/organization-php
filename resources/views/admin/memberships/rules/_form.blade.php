@php
$title = old('title', $rule->title ?? '');
$description = old('description', $rule->description ?? '');
@endphp

@if ($errors->any())
<div class="alert alert-danger">
    <strong>Please fix the errors:</strong>
    <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
</div>
@endif

<div class="mb-3">
    <label class="form-label" for="title">Title <span class="text-danger">*</span></label>
    <input id="title" type="text" name="title" class="form-control @error('title') is-invalid @enderror"
        value="{{ $title }}" required maxlength="255">
    @error('title') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label class="form-label" for="description">Description</label>
    <textarea id="description" name="description" class="form-control @error('description') is-invalid @enderror"
        rows="4" placeholder="Optional">{{ $description }}</textarea>
    @error('description') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
</div>

<div class="d-flex gap-2">
    <button class="btn btn-primary" type="submit">{{ $submitText ?? 'Save' }}</button>
    <a href="{{ route('orgadmin.membership_rules.index', [$organization->slug]) }}" class="btn btn-light">Cancel</a>
</div>
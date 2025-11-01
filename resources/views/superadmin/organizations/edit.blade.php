@extends('layouts.inner_page')
@section('page_title', 'Edit Organization')

@section('content')
<div class="container py-4">
    @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('superadmin.organizations.update', $organization) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Name *</label>
            <input type="text" name="name" value="{{ old('name', $organization->name) }}" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Prefix</label>
            <input type="text" name="org_prefix" value="{{ old('org_prefix', $organization->org_prefix) }}" class="form-control">
        </div>
        <div class="mb-3">
            <label>Logo</label><br>
            @if($organization->logo_url)
                <img src="{{ $organization->logo_url }}" width="100" class="mb-2">
            @endif
            <input type="file" name="logo" class="form-control">
        </div>
        <div class="mb-3">
            <label>Background Image</label><br>
            @if($organization->background_image_url)
                <img src="{{ $organization->background_image_url }}" width="100" class="mb-2">
            @endif
            <input type="file" name="background_image" class="form-control">
        </div>

        <div class="text-end">
            <button class="btn btn-primary">Update Organization</button>
        </div>
    </form>
</div>
@endsection

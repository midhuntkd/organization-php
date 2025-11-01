@extends('layouts.inner_page')

@section('page_title', 'Create Organization')

@section('content')
<div class="container py-4">
    <form action="{{ route('superadmin.organizations.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <h4 class="mb-3">Organization Details</h4>
        <div class="mb-3">
            <label>Name *</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Prefix</label>
            <input type="text" name="org_prefix" class="form-control">
        </div>
        <div class="mb-3">
            <label>Logo</label>
            <input type="file" name="logo" class="form-control">
        </div>
        <div class="mb-3">
            <label>Background Image</label>
            <input type="file" name="background_image" class="form-control">
        </div>

        <hr>
        <h4 class="mb-3">Admin User Information</h4>
        <div class="mb-3">
            <label>Admin Name *</label>
            <input type="text" name="admin_name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Admin Email *</label>
            <input type="email" name="admin_email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Admin Phone</label>
            <input type="text" name="admin_phone" class="form-control">
        </div>

        <div class="text-end">
            <button class="btn btn-primary">Create Organization & Send Invite</button>
        </div>
    </form>
</div>
@endsection
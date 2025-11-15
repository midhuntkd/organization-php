@extends('layouts.inner_page')

@section('page_title', 'Create Organization')

@section('breadcrumb')
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('superadmin.dashboard') }}">
                <i class="mdi mdi-home-outline"></i>
            </a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('superadmin.organizations.index') }}">Organizations</a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">Create</li>
    </ol>
@endsection

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="box">
                    <div class="box-header with-border d-flex justify-content-between align-items-center">
                        <h4 class="box-title mb-0">Create Organization</h4>
                        <a href="{{ route('superadmin.organizations.index') }}" class="btn btn-sm btn-outline-primary">
                            <i class="mdi mdi-arrow-left"></i> Back to List
                        </a>
                    </div>
                    <div class="box-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('superadmin.organizations.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="row">
                                <div class="col-md-6">
                                    <h5 class="fw-semibold mb-3">Organization Details</h5>
                                    <div class="mb-3">
                                        <label class="form-label">Name *</label>
                                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Prefix</label>
                                        <input type="text" name="org_prefix" class="form-control" value="{{ old('org_prefix') }}">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Logo</label>
                                        <input type="file" name="logo" class="form-control">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Header Logo (Square)</label>
                                        <input type="file" name="header_logo" class="form-control">
                                        <small class="text-muted d-block mt-1">Recommended: upload a square image for the header.</small>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Background Image</label>
                                        <input type="file" name="background_image" class="form-control">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <h5 class="fw-semibold mb-3">Admin User Information</h5>
                                    <div class="mb-3">
                                        <label class="form-label">Admin Name *</label>
                                        <input type="text" name="admin_name" class="form-control" value="{{ old('admin_name') }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Admin Email *</label>
                                        <input type="email" name="admin_email" class="form-control" value="{{ old('admin_email') }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Admin Phone</label>
                                        <input type="text" name="admin_phone" class="form-control" value="{{ old('admin_phone') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="text-end mt-4">
                                <button class="btn btn-primary">
                                    <i class="mdi mdi-content-save"></i> Create Organization & Send Invite
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

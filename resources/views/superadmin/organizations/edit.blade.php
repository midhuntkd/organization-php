@extends('layouts.inner_page')
@section('page_title', 'Edit Organization')

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
        <li class="breadcrumb-item active" aria-current="page">Edit</li>
    </ol>
@endsection

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="box">
                    <div class="box-header with-border d-flex justify-content-between align-items-center">
                        <h4 class="box-title mb-0">Edit Organization</h4>
                        <a href="{{ route('superadmin.organizations.index') }}" class="btn btn-sm btn-outline-primary">
                            <i class="mdi mdi-arrow-left"></i> Back to List
                        </a>
                    </div>

                    <div class="box-body">
                        @if(session('status'))
                            <div class="alert alert-success">{{ session('status') }}</div>
                        @endif

                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('superadmin.organizations.update', $organization) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Name *</label>
                                        <input type="text" name="name" value="{{ old('name', $organization->name) }}" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Prefix</label>
                                        <input type="text" name="org_prefix" value="{{ old('org_prefix', $organization->org_prefix) }}" class="form-control">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Logo</label><br>
                                        @if($organization->logo_url)
                                            <img src="{{ $organization->logo_url }}" width="100" class="mb-2">
                                        @endif
                                        <input type="file" name="logo" class="form-control">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Header Logo (Square)</label><br>
                                        @if($organization->header_logo)
                                            <img src="{{ $organization->header_logo_url }}" width="100" class="mb-2">
                                        @endif
                                        <input type="file" name="header_logo" class="form-control">
                                        <small class="text-muted d-block mt-1">Recommended size: square image for the header.</small>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Background Image</label><br>
                                        @if($organization->background_image_url)
                                            <img src="{{ $organization->background_image_url }}" width="100" class="mb-2">
                                        @endif
                                        <input type="file" name="background_image" class="form-control">
                                    </div>
                                </div>
                            </div>

                            <div class="text-end mt-4">
                                <button class="btn btn-primary">
                                    <i class="mdi mdi-content-save"></i> Update Organization
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

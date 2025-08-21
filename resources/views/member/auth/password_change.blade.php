@extends('layouts.inner_page')

@section('page_title', 'Change Password')

@section('breadcrumb')
<ol class="breadcrumb">
    <li class="breadcrumb-item">
        <a href="#"><i class="mdi mdi-home-outline"></i></a>
    </li>
    <li class="breadcrumb-item active">Change Password</li>
</ol>
@endsection

@section('content')
<section class="content">
    <div class="row">
        <div class="col-lg-6 col-md-8 col-12 mx-auto">

            @if (session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
            @endif

            <div class="box">
                <div class="box-header with-border">
                    <h4 class="box-title">Set a new password</h4>
                </div>
                <div class="box-body">

                    <form method="POST" action="{{ route('member.password.change.submit', $organization->slug) }}">
                        @csrf

                        {{-- Current Password --}}
                        <div class="mb-3">
                            <label class="form-label" for="current_password">Current Password</label>
                            <input type="password" id="current_password" name="current_password"
                                class="form-control @error('current_password') is-invalid @enderror" required>
                            @error('current_password')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- New Password --}}
                        <div class="mb-3">
                            <label class="form-label" for="password">New Password</label>
                            <input type="password" id="password" name="password"
                                class="form-control @error('password') is-invalid @enderror"
                                minlength="8" required>
                            @error('password')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Minimum 8 characters.</small>
                        </div>

                        {{-- Confirm --}}
                        <div class="mb-3">
                            <label class="form-label" for="password_confirmation">Confirm New Password</label>
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                class="form-control" required>
                        </div>

                        <div class="d-flex gap-2">
                            <button class="btn btn-primary" type="submit">Update Password</button>
                            <a class="btn btn-light" href="{{ route('member.dashboard', $organization->slug) }}">Cancel</a>
                        </div>
                    </form>

                </div>
            </div>

        </div>
    </div>
</section>
@endsection
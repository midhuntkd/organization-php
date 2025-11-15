@extends('layouts.custom_auth')

@section('content')
<div class="rounded10 shadow-lg my-auto px-10 pb-20 col-lg-10 col-12" style="background-color: rgba(63, 66, 84, .65);">
    <div class="content-top-agile p-20 pb-0">
        <h2 class="text-white fw-600">Reset Password</h2>
        <p class="mb-0 text-fade">Set a new password for your account.</p>
    </div>
    <div class="p-15">
        @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('org.password.store', $organization->slug) }}">
            @csrf

            <input type="hidden" name="token" value="{{ $token ?? $request->route('token') }}">

            <div class="form-group">
                <div class="input-group mb-3">
                    <span class="input-group-text bg-transparent"><i class="text-fade ti-email"></i></span>
                    <input type="email" id="email" name="email" value="{{ old('email', $request->email) }}" required class="form-control ps-15" placeholder="Email">
                </div>
            </div>

            <div class="form-group">
                <div class="input-group mb-3">
                    <span class="input-group-text bg-transparent"><i class="text-fade ti-lock"></i></span>
                    <input type="password" id="password" name="password" required class="form-control ps-15" placeholder="New Password">
                </div>
            </div>

            <div class="form-group">
                <div class="input-group mb-3">
                    <span class="input-group-text bg-transparent"><i class="text-fade ti-lock"></i></span>
                    <input type="password" id="password_confirmation" name="password_confirmation" required class="form-control ps-15" placeholder="Confirm New Password">
                </div>
            </div>

            <div class="row">
                <div class="col-12 text-center">
                    <button type="submit" class="btn btn-primary w-p100 mt-10">Reset Password</button>
                </div>
            </div>
        </form>

        <div class="text-center">
            <p class="mt-15 mb-0 text-fade">
                Return to
                <a href="{{ route('member_login', $organization->slug) }}" class="text-white ms-5">Login</a>
            </p>
        </div>
    </div>
@endsection


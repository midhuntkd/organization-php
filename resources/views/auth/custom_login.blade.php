@extends('layouts.custom_auth')

@section('content')
<div class="bg-white rounded10 shadow-lg">
    <div class="content-top-agile p-20 pb-0">
        <h2 class="text-primary fw-600">Let's Get Started</h2>
        <p class="mb-0 text-fade">Sign in to continue the membership.</p>
    </div>
    <div class="p-40">
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
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
        @endif
        @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
        @endif

        @if (isset($organization) && $userType['user_type'] === 'member')
            <form action="{{ route('custom_member_login.submit', $organization->slug) }}" method="POST">
        @else
            <form action="{{ route('custom_login.submit') }}" method="POST">
        @endif
                @csrf
                <input type="hidden" name="user_type" value="{{ $userType['user_type'] ?? 'admin' }}">
                <div class="form-group">
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-transparent"><i class="text-fade ti-email"></i></span>
                        <input type="email" name="email" required class="form-control ps-15 bg-transparent" placeholder="Email">
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-transparent"><i class="text-fade ti-lock"></i></span>
                        <input type="password" name="password" required class="form-control ps-15 bg-transparent" placeholder="Password">
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="checkbox">
                            <input type="checkbox" id="remember_me" name="remember">
                            <label for="remember_me">Remember Me</label>
                        </div>
                    </div>
                    <!-- /.col -->
                    @if (Route::has('password.request'))
                    <div class="col-6">
                        <div class="fog-pwd text-end">
                            <a href="{{ route('password.request') }}" class="text-primary fw-500 hover-primary"><i class="ion ion-locked"></i> Forgot Password?</a><br>
                        </div>
                    </div>
                    @endif
                    <!-- /.col -->
                    <div class="col-12 text-center">
                        <button type="submit" class="btn btn-primary w-p100 mt-10">SIGN IN</button>
                    </div>
                </div>
            </form>
            <div class="text-center">
                <p class="mt-15 mb-0 text-fade">Don't have an account? <a href="{{ route('custom_register', $organization->slug) }}" class="text-primary ms-5">Sign Up</a></p>
            </div>
    </div>
</div>
@endsection
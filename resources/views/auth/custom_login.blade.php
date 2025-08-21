@extends('layouts.custom_auth')

@section('content')
<div class="bg-white rounded10 shadow-lg my-auto  px-10 pb-20">
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
                <input type="hidden" id="org_slug" value="{{ isset($organization) ? $organization->slug : '' }}">
                <!-- <div class="form-group">
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-transparent"><i class="text-fade ti-email"></i></span>
                        <input type="email" name="email" required class="form-control ps-15 bg-transparent" placeholder="Email">
                    </div>
                </div> -->
                <div class="form-group">
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-transparent"><i class="text-fade ti-email"></i></span>
                        <input type="email" id="email" name="email" required class="form-control ps-15 bg-transparent" placeholder="Email">
                        <button type="button" id="verifyBtn" class="btn btn-outline-primary">
                            <span class="verify-text">Verify</span>
                            <span class="verify-spinner d-none">...</span>
                        </button>
                    </div>
                    <div id="verifyMsg" class="small mt-1"></div>
                </div>

                <div class="form-group">
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-transparent"><i class="text-fade ti-lock"></i></span>
                        <input type="password" id="password" name="password" required class="form-control ps-15 bg-transparent" placeholder="Password" disabled>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="checkbox">
                            <input type="checkbox" id="remember_me" name="remember" disabled>
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
                        <button type="submit" id="submitBtn" class="btn btn-primary w-p100 mt-10" disabled>SIGN IN</button>
                    </div>
                </div>
            </form>
            <div class="text-center">
                <p class="mt-15 mb-0 text-fade">Don't have an account? <a href="{{ route('custom_register', $organization->slug) }}" class="text-primary ms-5">Sign Up</a></p>
            </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const verifyBtn = document.getElementById('verifyBtn');
        const emailInput = document.getElementById('email');
        const pwdInput = document.getElementById('password');
        const submitBtn = document.getElementById('submitBtn');
        const rememberCb = document.getElementById('remember_me');
        const verifyMsg = document.getElementById('verifyMsg');
        const orgSlug = document.getElementById('org_slug')?.value || '';
        const userType = document.querySelector('input[name="user_type"]').value || 'admin';

        function setLoading(isLoading) {
            verifyBtn.disabled = isLoading;
            verifyBtn.querySelector('.verify-text').classList.toggle('d-none', isLoading);
            verifyBtn.querySelector('.verify-spinner').classList.toggle('d-none', !isLoading);
        }

        function setVerified(enabled, message, success = true) {
            if (enabled) {
                emailInput.readOnly = true;
                pwdInput.disabled = false;
                submitBtn.disabled = false;
                rememberCb.disabled = false;
                verifyBtn.disabled = true;
            } else {
                emailInput.readOnly = false;
                pwdInput.disabled = true;
                submitBtn.disabled = true;
                rememberCb.disabled = true;
            }
            verifyMsg.textContent = message || '';
            verifyMsg.className = 'small mt-1 ' + (success ? 'text-success' : 'text-danger');
        }

        verifyBtn.addEventListener('click', async function() {
            const email = emailInput.value.trim();
            if (!email) {
                setVerified(false, 'Please enter your email.', false);
                return;
            }

            // rudimentary email check
            if (!/^\S+@\S+\.\S+$/.test(email)) {
                setVerified(false, 'Please enter a valid email address.', false);
                return;
            }

            setLoading(true);
            setVerified(false, ''); // reset state

            const url = orgSlug ?
                `{{ route('auth.precheck', ':slug') }}`.replace(':slug', encodeURIComponent(orgSlug)) :
                `{{ route('auth.precheck') }}`;

            try {
                const res = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        email: email,
                        user_type: userType, // 'member' or 'admin'
                    })
                });

                const data = await res.json();

                if (res.ok && data.ok) {
                    setVerified(true, data.message || 'Verified.');
                } else {
                    // Some hosts return HTML on 419s; guard against non-JSON
                    const msg = (data && data.message) ? data.message : 'Verification failed.';
                    setVerified(false, msg, false);
                }
            } catch (e) {
                setVerified(false, 'Network error. Please try again.', false);
            } finally {
                setLoading(false);
            }
        });

        // If email changes, reset verification
        emailInput.addEventListener('input', () => {
            emailInput.readOnly = false;
            pwdInput.disabled = true;
            submitBtn.disabled = true;
            rememberCb.disabled = true;
            verifyBtn.disabled = false;
            verifyMsg.textContent = '';
            verifyMsg.className = 'small mt-1';
        });
    });
</script>
@endpush
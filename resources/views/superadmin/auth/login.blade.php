@extends('layouts.custom_super_auth')

@section('content')
    <div class="rounded10 shadow-lg my-auto  px-10 pb-20 col-lg-10 col-12" style="background-color: rgba(63, 66, 84, .65);">
        <div class="content-top-agile p-20 pb-0">
            <h2 class="text-white fw-600">Let's Get Started</h2>
            <p class="mb-0 text-fade">Sign in as Super Admin</p>
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
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('superadmin.login.submit') }}" method="POST">
                @csrf
                <div class="form-group">
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-transparent"><i class="text-fade ti-email"></i></span>
                        <input type="email" id="email" name="email" required class="form-control ps-15"
                            placeholder="Email">
                        <button type="button" id="verifyBtn" class="btn btn-primary">
                            <span class="verify-text">Verify</span>
                            <span class="verify-spinner d-none">...</span>
                        </button>
                    </div>
                    <div id="verifyMsg" class="small mt-1"></div>
                </div>

                <div class="form-group">
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-transparent"><i class="text-fade ti-lock"></i></span>
                        <input type="password" id="password" name="password" required class="form-control ps-15"
                            placeholder="Password" disabled>
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
                                <a href="{{ route('password.request') }}" class="text-white fw-500 hover-primary"><i
                                        class="ion ion-locked"></i> Forgot Password?</a><br>
                            </div>
                        </div>
                    @endif
                    <!-- /.col -->
                    <div class="col-12 text-center">
                        <button type="submit" id="submitBtn" class="btn btn-primary w-p100 mt-10" disabled>SIGN IN</button>
                    </div>
                </div>
            </form>
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
            const userType = 'super-admin';

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

                const url = `{{ route('superadmin.auth.precheck') }}`;

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

@extends('layouts.custom_auth')

@section('content')
<div class="rounded10 shadow-lg  my-auto  px-10 pb-20 col-10" style="background-color: rgba(63, 66, 84, .65);">
    <div class="content-top-agile p-20 pb-0">
        <h2 class="text-white fw-600">Member Registration</h2>
        <p class="mb-0 text-fade">Create your account for {{ $organization->name }}</p>
    </div>

    <div class="p-40">

        {{-- Global errors / status --}}
        @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
        @endif
        @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
        @endif
        <div class="alert alert-danger" id="ajaxError">
        </div>
        <form id="registerForm"
            method="POST"
            action="{{ route('custom_register.submit', $organization->slug) }}"
            enctype="multipart/form-data">
            @csrf

            {{-- 1) EMAIL + SEND OTP --}}
            <div class="mb-3">
                <label class="form-label">Email <span class="text-danger">*</span></label>
                <div class="input-group">
                    <input type="email"
                        name="email"
                        id="emailInput"
                        class="form-control @error('email') is-invalid @enderror"
                        value="{{ old('email') }}"
                        placeholder="you@example.com"
                        required>
                    <button type="button" id="sendOtpBtn" class="btn btn-primary">
                        Send OTP
                    </button>
                </div>
                @error('email') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                <small id="otpHint" class="text-muted d-none">OTP sent to your email (valid for 10 minutes).</small>
            </div>


            <div id="otpBlock" class="mb-3 d-none">
                <label class="form-label">Enter OTP <span class="text-danger">*</span></label>
                <div class="input-group">
                    <input type="text"
                        name="otp"
                        id="otpInput"
                        class="form-control @error('otp') is-invalid @enderror"
                        maxlength="6"
                        placeholder="6-digit code">
                    <button type="button" id="verifyOtpBtn" class="btn btn-success">
                        Verify OTP
                    </button>
                </div>
                @error('otp') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                <small id="otpVerifiedMsg" class="text-success d-none">OTP verified. You can continue.</small>
            </div>

            {{-- 3) REST OF FORM (hidden until OTP verified) --}}
            <div id="restBlock" class="d-none">

                {{-- Name --}}
                <div class="mb-3">
                    <label class="form-label">Full Name <span class="text-danger">*</span></label>
                    <input type="text"
                        name="name"
                        class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name') }}"
                        required>
                    @error('name') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>

                {{-- Country --}}
                <div class="mb-3">
                    <label class="form-label">Country of Residence <span class="text-danger">*</span></label>
                    <select name="country_of_residence"
                        id="countrySelect"
                        class="form-select @error('country_of_residence') is-invalid @enderror"
                        required>
                        <option value="">-- Select --</option>
                        <option value="UAE" @selected(old('country_of_residence')==='UAE' )>UAE</option>
                        <option value="India" @selected(old('country_of_residence')==='India' )>India</option>
                    </select>
                    @error('country_of_residence') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>

                {{-- Phone with dynamic prefix --}}
                <div class="mb-3">
                    <label class="form-label">Phone <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text" id="phonePrefix">+971</span>
                        <input type="hidden" name="phone_prefix" id="phonePrefixInput" value="+971">
                        <input type="text"
                            name="phone"
                            id="phoneInput"
                            class="form-control @error('phone') is-invalid @enderror"
                            value="{{ old('phone') }}"
                            placeholder="Enter phone number"
                            required>
                    </div>
                    <small class="text-muted" style="color:#fff">Prefix updates with country: +971 (UAE) or +91 (India). We store the full number with prefix.</small>
                    @error('phone') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>

                {{-- Verification Type --}}
                <div class="mb-3">
                    <label class="form-label">Verification Type <span class="text-danger">*</span></label>
                    <div class="d-flex gap-3">
                        <div class="form-check">
                            <input class="form-check-input"
                                type="radio"
                                name="verification_type"
                                id="aadhaar"
                                value="aadhaar"
                                @checked(old('verification_type')==='aadhaar' )>
                            <label class="form-check-label" for="aadhaar">Aadhaar (India)</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input"
                                type="radio"
                                name="verification_type"
                                id="emirates_id"
                                value="emirates_id"
                                @checked(old('verification_type')==='emirates_id' )>
                            <label class="form-check-label" for="emirates_id">Emirates ID (UAE)</label>
                        </div>
                    </div>
                    @error('verification_type') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>

                {{-- Verification ID Number --}}
                <div class="mb-3">
                    <label class="form-label">Verification ID Number <span class="text-danger">*</span></label>
                    <input type="text"
                        name="verification_id_number"
                        id="verification_id_number"
                        required
                        class="form-control @error('verification_id_number') is-invalid @enderror"
                        value="{{ old('verification_id_number') }}"
                        placeholder="Aadhaar: 12 digits, Emirates ID: 15 digits">
                    @error('verification_id_number') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>

                {{-- ID Card Front --}}
                <div class="mb-3">
                    <label class="form-label">ID Card (Front) <span class="text-danger">*</span></label>
                    <input type="file"
                        name="id_card_front"
                        class="form-control @error('id_card_front') is-invalid @enderror"
                        accept="image/*"
                        required>
                    @error('id_card_front') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>

                {{-- ID Card Back --}}
                <div class="mb-3">
                    <label class="form-label">ID Card (Back) <span class="text-danger">*</span></label>
                    <input type="file"
                        name="id_card_back"
                        class="form-control @error('id_card_back') is-invalid @enderror"
                        accept="image/*"
                        required>
                    @error('id_card_back') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-primary w-100">Register</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Elements
        const sendBtn = document.getElementById('sendOtpBtn');
        const emailEl = document.getElementById('emailInput');
        const otpBlock = document.getElementById('otpBlock');
        const otpHint = document.getElementById('otpHint');

        const verifyBtn = document.getElementById('verifyOtpBtn');
        const otpEl = document.getElementById('otpInput');
        const otpOkMsg = document.getElementById('otpVerifiedMsg');
        const restBlock = document.getElementById('restBlock');

        const countrySelect = document.getElementById('countrySelect');
        const phonePrefix = document.getElementById('phonePrefix');
        const phonePrefixInput = document.getElementById('phonePrefixInput');
        const phoneInput = document.getElementById('phoneInput');

        const radios = document.querySelectorAll("input[name='verification_type']");
        const idField = document.getElementById("verification_id_number");
        const ajaxErrorDiv = document.getElementById('ajaxError');
        ajaxErrorDiv.style.display = 'none';

        const aadhaarRadio = document.getElementById('aadhaar');
        const emiratesRadio = document.getElementById('emirates_id');


        const nameEl = document.querySelector('input[name="name"]');
        const countryEl = document.getElementById('countrySelect');
        const phoneEl = document.getElementById('phoneInput');

        const vTypeEls = document.querySelectorAll('input[name="verification_type"]');
        const vIdEl = document.getElementById('verification_id_number');

        const frontEl = document.querySelector('input[name="id_card_front"]');
        const backEl = document.querySelector('input[name="id_card_back"]');

        const MAX_IMG_MB = 20;
        const MAX_IMG_BYTES = MAX_IMG_MB * 1024 * 1024;
        const digitsOnly = s => (s || '').replace(/\D+/g, '');
        const isEmail = s => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test((s || '').trim());
        const getCheckedValue = nodes => {
            for (const n of nodes)
                if (n.checked) return n.value;
            return null;
        };

        function syncVerificationTypeWithCountry() {
            const country = countrySelect.value;

            // Auto-pick type based on country
            if (country === 'India') {
                if (aadhaarRadio && !aadhaarRadio.checked) {
                    aadhaarRadio.checked = true;
                    // trigger the same formatting you already have on radio change
                    aadhaarRadio.dispatchEvent(new Event('change'));
                }
            } else if (country === 'UAE') {
                if (emiratesRadio && !emiratesRadio.checked) {
                    emiratesRadio.checked = true;
                    emiratesRadio.dispatchEvent(new Event('change'));
                }
            }
        }

        function applyPhoneRule() {
            let val = phoneInput.value.replace(/\D/g, ''); // keep digits only
            let maxLen = 10; // default India

            if (countrySelect.value === 'UAE') {
                maxLen = 9;
            }

            if (val.length > maxLen) {
                val = val.slice(0, maxLen);
            }

            phoneInput.value = val;
        }

        // Country → phone prefix behavior
        function updatePrefix() {
            const c = countrySelect.value;
            const prefix = (c === 'India') ? '+91' : '+971';
            phonePrefix.textContent = prefix;
            phonePrefixInput.value = prefix;
            // Initialize or change leading prefix in phone input
            // if (!phoneInput.value) {
            //     phoneInput.value = prefix + ' ';
            // } else if (!phoneInput.value.startsWith(prefix)) {
            //     const digits = phoneInput.value.replace(/^\+?\d+\s*/, '').trim();
            //     phoneInput.value = prefix + ' ' + digits;
            // }
        }
        if (countrySelect) {
            // Extend your existing country change handlers
            countrySelect.addEventListener('change', function() {
                updatePrefix(); // your existing function
                applyPhoneRule(); // your existing function
                syncVerificationTypeWithCountry(); // NEW
                // Clear the ID field when country changes (optional but recommended)
                idField.value = '';
            });

            // On first load, make sure they align too
            updatePrefix();
            applyPhoneRule();
            syncVerificationTypeWithCountry(); // initial
        }



        phoneInput.addEventListener('input', applyPhoneRule);

        radios.forEach(radio => {
            radio.addEventListener("change", function() {
                idField.value = ""; // clear on change
                if (this.value === "aadhaar") {
                    idField.setAttribute("placeholder", "XXXX XXXX XXXX");
                    idField.setAttribute("maxlength", "14"); // incl. spaces
                } else if (this.value === "emirates_id") {
                    idField.setAttribute("placeholder", "###-####-#######-#");
                    idField.setAttribute("maxlength", "18"); // incl. dashes
                }
            });
        });

        idField.addEventListener("input", function() {
            let selected = document.querySelector("input[name='verification_type']:checked")?.value;

            if (selected === "aadhaar") {
                let v = this.value.replace(/\D/g, "").substring(0, 12);
                v = v.replace(/(\d{4})(\d{4})(\d{0,4})/, function(_, a, b, c) {
                    return [a, b, c].filter(Boolean).join(" ");
                });
                this.value = v;
            } else if (selected === "emirates_id") {
                let v = this.value.replace(/\D/g, "").substring(0, 15);
                v = v.replace(/(\d{3})(\d{4})(\d{7})(\d{0,1})/, function(_, a, b, c, d) {
                    return [a, b, c, d].filter(Boolean).join("-");
                });
                this.value = v;
            }
        });



        // 1) SEND OTP (AJAX)
        sendBtn?.addEventListener('click', function() {
            const email = (emailEl.value || '').trim();
            if (!email) {
                alert('Enter email first');
                return;
            }

            sendBtn.disabled = true;

            fetch("{{ route('custom_register.send_otp', $organization->slug) }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}",
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        email
                    })
                })
                .then(async res => {
                    const data = await res.json().catch(() => ({}));
                    //if (!res.ok) throw new Error(data.message || 'Failed to send OTP');
                    if (!res.ok) {
                        let message = 'Your email is invalid or already registered.';
                        if (data.errors && data.errors.email) {
                            message = data.errors.email[0];
                        } else if (data.message) {
                            message = data.message;
                        }
                        ajaxErrorDiv.style.display = 'block';
                        ajaxErrorDiv.innerHTML = message;
                    } else {
                        // Success → lock email, reveal OTP block
                        ajaxErrorDiv.innerHTML = '';
                        ajaxErrorDiv.style.display = 'none';
                        emailEl.readOnly = true;
                        sendBtn.classList.add('disabled');
                        otpHint.classList.remove('d-none');
                        otpBlock.classList.remove('d-none');
                        otpEl.focus();
                    }
                })
                .catch(err => alert(err.message))
                .finally(() => {
                    sendBtn.disabled = false;
                });
        });

        // 2) VERIFY OTP (AJAX)
        verifyBtn?.addEventListener('click', function() {
            const email = (emailEl.value || '').trim();
            const otp = (otpEl.value || '').trim();

            if (!email) {
                alert('Missing email');
                return;
            }
            if (!otp || otp.length !== 6) {
                alert('Enter the 6-digit OTP.');
                return;
            }

            verifyBtn.disabled = true;

            fetch("{{ route('custom_register.verify_otp', $organization->slug) }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}",
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        email,
                        otp
                    })
                })
                .then(async res => {
                    const data = await res.json().catch(() => ({}));
                    if (!res.ok) throw new Error(data.message || 'OTP verification failed');

                    // OK → show rest of the form, lock OTP field
                    otpOkMsg.classList.remove('d-none');
                    restBlock.classList.remove('d-none');

                    emailEl.readOnly = true;
                    otpEl.readOnly = true;
                    verifyBtn.classList.add('disabled');
                })
                .catch(err => alert(err.message))
                .finally(() => {
                    verifyBtn.disabled = false;
                });
        });
    });


    function showError(inputEl, msg) {
        let container = inputEl?.closest('.mb-3') || inputEl?.parentElement || inputEl;
        let err = container.querySelector('.client-error');
        if (!err) {
            err = document.createElement('div');
            err.className = 'client-error invalid-feedback d-block';
            container.appendChild(err);
        }
        err.textContent = msg;
        inputEl?.classList?.add('is-invalid');
    }

    function clearError(inputEl) {
        const container = inputEl?.closest('.mb-3') || inputEl?.parentElement || inputEl;
        const err = container?.querySelector('.client-error');
        if (err) err.textContent = '';
        inputEl?.classList?.remove('is-invalid');
    }

    function clearAllErrors() {
        form.querySelectorAll('.client-error').forEach(el => el.textContent = '');
        form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
    }

    if (phoneEl) {
        phoneEl.addEventListener('input', () => {
            const needLen = countryEl?.value === 'India' ? 10 : 9;
            let val = digitsOnly(phoneEl.value).slice(0, needLen);
            phoneEl.value = val;
            clearError(phoneEl);
        });
    }

    if (vIdEl) {
        vIdEl.addEventListener('input', () => {
            vIdEl.value = digitsOnly(vIdEl.value); // your formatting script can re-add spaces/dashes
            clearError(vIdEl);
        });
    }

    // Re-validate constraints when country or type changes
    countryEl?.addEventListener('change', () => {
        clearError(countryEl);
        if (phoneEl) {
            const needLen = countryEl.value === 'India' ? 10 : 9;
            phoneEl.value = digitsOnly(phoneEl.value).slice(0, needLen);
            clearError(phoneEl);
        }
        // You already auto-select verification type based on country elsewhere
    });

    vTypeEls.forEach(r => r.addEventListener('change', () => {
        clearError(r);
        if (vIdEl) clearError(vIdEl);
    }));

    const form = document.getElementById('registerForm');
    form.addEventListener('submit', function(e) {
        clearAllErrors();
        let firstInvalid = null;

        // Email
        if (!emailEl || !emailEl.value.trim()) {
            showError(emailEl, 'Email is required.');
            firstInvalid = firstInvalid || emailEl;
        } else if (!isEmail(emailEl.value)) {
            showError(emailEl, 'Enter a valid email address.');
            firstInvalid = firstInvalid || emailEl;
        }

        // OTP
        const otp = digitsOnly(otpEl?.value);
        if (!otp || otp.length !== 6) {
            showError(otpEl, 'Enter the 6-digit OTP.');
            firstInvalid = firstInvalid || otpEl;
        }
        // Make sure OTP was verified via AJAX
        if (!otpVerifiedEl || otpVerifiedEl.value !== '1') {
            showError(otpEl, 'Please verify the OTP before submitting.');
            firstInvalid = firstInvalid || otpEl;
        }

        // Name
        if (!nameEl || !nameEl.value.trim()) {
            showError(nameEl, 'Full name is required.');
            firstInvalid = firstInvalid || nameEl;
        }

        // Country
        if (!countryEl || !countryEl.value) {
            showError(countryEl, 'Please select your country.');
            firstInvalid = firstInvalid || countryEl;
        }

        // Phone (digits only, len by country)
        if (phoneEl) {
            const phoneDigits = digitsOnly(phoneEl.value);
            const needLen = (countryEl?.value === 'India') ? 10 : 9;
            if (!phoneDigits) {
                showError(phoneEl, `Phone is required (${needLen} digits).`);
                firstInvalid = firstInvalid || phoneEl;
            } else if (phoneDigits.length !== needLen) {
                showError(phoneEl, `Phone must be exactly ${needLen} digits.`);
                firstInvalid = firstInvalid || phoneEl;
            }
        }

        // Verification type
        const vType = getCheckedValue(vTypeEls);
        if (!vType) {
            showError(vTypeEls[0], 'Please choose a verification type.');
            firstInvalid = firstInvalid || vTypeEls[0];
        }

        // Verification ID number
        if (vIdEl) {
            const idDigits = digitsOnly(vIdEl.value);
            if (!idDigits) {
                showError(vIdEl, 'Verification ID number is required.');
                firstInvalid = firstInvalid || vIdEl;
            } else {
                if (vType === 'aadhaar' && idDigits.length !== 12) {
                    showError(vIdEl, 'Aadhaar must be exactly 12 digits.');
                    firstInvalid = firstInvalid || vIdEl;
                }
                if (vType === 'emirates_id' && idDigits.length !== 15) {
                    showError(vIdEl, 'Emirates ID must be exactly 15 digits.');
                    firstInvalid = firstInvalid || vIdEl;
                }
            }
        }

        // Files
        function validateImage(inputEl, label) {
            if (!inputEl || !inputEl.files || inputEl.files.length === 0) {
                showError(inputEl, `${label} is required.`);
                return false;
            }
            const f = inputEl.files[0];
            if (!/^image\//.test(f.type)) {
                showError(inputEl, `${label} must be an image file.`);
                return false;
            }
            if (f.size > MAX_IMG_BYTES) {
                showError(inputEl, `${label} must be ≤ ${MAX_IMG_MB} MB.`);
                return false;
            }
            return true;
        }
        if (!validateImage(frontEl, 'ID Card (Front)')) firstInvalid = firstInvalid || frontEl;
        if (!validateImage(backEl, 'ID Card (Back)')) firstInvalid = firstInvalid || backEl;

        // Stop submit if any invalid
        if (firstInvalid) {
            e.preventDefault();
            firstInvalid.focus({
                preventScroll: false
            });
            firstInvalid.scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });
        }
    });
</script>
@endpush
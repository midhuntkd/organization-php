@extends('layouts.inner_page')

@section('page_title', 'My Profile')

@section('content')
    <style>
        .bg-transparant {
            background: rgba(255, 255, 255, 0.1);
            padding: 20px;
        }
    </style>
    <section class="content">
        <div class="row justify-content-center align-items-center" style="min-height:80vh;">
            <div class="col-lg-10 col-md-12">
                <div class="row g-0 shadow-lg rounded overflow-hidden">

                    {{-- LEFT SIDE: PROFILE FORM --}}
                    <div class="col-md-12 bg-transparant p-20">
                        <h4 class="fw-bold mb-4 text-primary">Update Your Profile</h4>

                        @if (session('status'))
                            <div class="alert alert-success">{{ session('status') }}</div>
                        @endif

                        <form method="POST" action="{{ route('member.profile.update', $organization->slug) }}"
                            enctype="multipart/form-data">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" name="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                                <input type="text" name="phone"
                                    class="form-control @error('phone') is-invalid @enderror"
                                    value="{{ old('phone', $user->phone) }}" required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Blood Group</label>
                                @php
                                    $bloodGroups = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
                                @endphp
                                <select name="blood_group"
                                    class="form-select @error('blood_group') is-invalid @enderror">
                                    <option value="">-- Select --</option>
                                    @foreach ($bloodGroups as $group)
                                        <option value="{{ $group }}" @selected(old('blood_group', $details->blood_group) === $group)>
                                            {{ $group }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('blood_group')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Profile Image</label>
                                <input type="file" name="user_image"
                                    class="form-control @error('user_image') is-invalid @enderror" accept="image/*">
                                @error('user_image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                @if ($user->user_image)
                                    <div class="mt-3">
                                        <img src="{{ $user->user_image_url }}" alt="User Image" width="100"
                                            class="rounded-circle border">
                                    </div>
                                @endif
                            </div>

                            <hr class="my-4">
                            <h5 class="fw-bold text-primary mb-3">Address & Norka Details</h5>

                            <div class="mb-3">
                                <label class="form-label">Address Line 1</label>
                                <input type="text" name="address_line1" class="form-control"
                                    value="{{ old('address_line1', $details->address_line1) }}">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Address Line 2</label>
                                <input type="text" name="address_line2" class="form-control"
                                    value="{{ old('address_line2', $details->address_line2) }}">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">City</label>
                                <input type="text" name="city" class="form-control"
                                    value="{{ old('city', $details->city) }}">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Zipcode</label>
                                <input type="text" name="zipcode" class="form-control"
                                    value="{{ old('zipcode', $details->zipcode) }}">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Norka Registration Number</label>
                                <input type="text" name="norka_registration_number" class="form-control"
                                    value="{{ old('norka_registration_number', $details->norka_registration_number) }}">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Permanent Home Address</label>
                                <textarea name="permanent_home_address" rows="3" class="form-control">{{ old('permanent_home_address', $details->permanent_home_address) }}</textarea>
                            </div>

                            <hr class="my-4">
                            <h5 class="fw-bold text-primary mb-3">Insurance Details</h5>

                            <div class="mb-3">
                                <label class="form-label">Insurance Provider</label>
                                <input type="text" name="insurance_provider" class="form-control"
                                    value="{{ old('insurance_provider', $details->insurance_provider) }}">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Policy Number</label>
                                <input type="text" name="policy_number" class="form-control"
                                    value="{{ old('policy_number', $details->policy_number) }}">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Expiry Date</label>
                                <input type="date" name="expiry_date" class="form-control"
                                    value="{{ old('expiry_date', $details->expiry_date?->format('Y-m-d')) }}">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Amount</label>
                                <input type="number" name="amount" step="0.01" class="form-control"
                                    value="{{ old('amount', $details->amount) }}">
                            </div>


                            <button type="submit" class="btn btn-primary mt-3">Save Changes</button>
                        </form>
                    </div>

                    {{-- RIGHT SIDE: ORG LOGO + NAME --}}
                    {{-- <div class="col-md-6 d-flex justify-content-center align-items-center flex-column text-center p-4" 
                     style="background: url('{{ $organization->background_image_url }}') center/cover no-repeat;">
                    <div style="background-color: rgba(255,255,255,0.85); padding: 30px; border-radius: 10px;">
                        <img src="{{ $organization->logo_url }}" alt="Logo" class="mb-3" style="max-height: 120px;">
                        <h3 class="fw-bold text-dark mb-0">{{ $organization->name }}</h3>
                    </div>
                </div> --}}

                </div>
            </div>
        </div>
    </section>
@endsection

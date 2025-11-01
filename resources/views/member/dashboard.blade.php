@extends('layouts.inner_page')

@section('page_title', 'Member Dashboard')

@section('breadcrumb')
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="#"><i class="mdi mdi-home-outline"></i></a>
        </li>
        <li class="breadcrumb-item active">Dashboard</li>
    </ol>
@endsection

@section('content')
    <section class="content">
        <div class="row">

            @if (session('status'))
                <div class="col-12">
                    <div class="alert alert-success">{{ session('status') }}</div>
                </div>
            @endif

            <div class="row">
                <div class="col-lg-9 col-md-9 col-sm-7 col-12">
                    <div class="row">
                        {{-- Profile card --}}
                        <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                            <div class="box">
                                <div class="box-header with-border">
                                    <h4 class="box-title">Welcome, {{ $user->name }}</h4>
                                </div>
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-5">
                                            <img src="{{ asset('hyper/images/user_icon.png') }}" alt="User Avatar"
                                                class="img-thumbnail mb-3" style="max-width: 100%;">
                                        </div>
                                        <div class="col-7">
                                            <p class="mb-1"> {{ $user->name }}</p>
                                            <p class="mb-1"> {{ $user->email }}</p>
                                            <p class="mb-1"> {{ $user->phone }}</p>
                                            <a href="{{ route('member.id-card') }}" class="btn btn-primary mt-3">
                                                <i class="mdi mdi-card-account-details me-1"></i> View ID Card
                                            </a>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        {{-- Rules --}}
                        <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                            <div class="box">
                                <div class="box-header with-border d-flex justify-content-between align-items-center">
                                    <h4 class="box-title">Membership Details</h4>
                                </div>
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-7">
                                            <h3 class="mb-1"> {{ $organization->name }}</h3><br />

                                            <h3 class="mb-1">{{ $user->membership_code ?? '—' }}</h3>
                                            <br />
                                            @if ($membership)
                                                <h3 class="mb-1">{{ $membership->name }}</h3>
                                            @endif
                                        </div>
                                        <div class="col-5">
                                            <img src="{{ $organization->logo_url }}" alt="Logo" class="mb-3" style="max-height: 120px;">
                                            <h3 class="fw-bold text-dark mb-0">{{ $organization->name }}</h3>
                                            <button type="button" class="btn btn-primary mt-3">Upgrade Membership</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Benefits --}}
                        <div class="col-lg-4 col-md-4 col-sm-4 col-12">
                            <div class="box">
                                <div class="box-header with-border d-flex justify-content-between align-items-center">
                                    <h4 class="box-title">My Wallet</h4>
                                </div>
                                <div class="box-body text-center">
                                    <img src="{{ asset('hyper/images/wallet-card.png') }}" alt="User Avatar"
                                                class="mb-3" style="max-width: 100%;max-height:150px;">
                                    <br/> 
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                               
                                        <p class="mb-1"><strong> 255.22</strong></p>
                                        <button class="btn btn-primary btn-sm" id="topup">TOPUP MY WALLET</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-8 col-sm-8 col-12">
                            <div class="box">
                                <div class="box-header with-border">
                                    <h4 class="box-title">Membership Benefits</h4>
                                </div>
                                <div class="box-body">
                                    @if ($benefits->isEmpty())
                                        <!-- <p class="text-muted">No benefits available for your plan.</p> -->
                                    @else
                                        <div class="row">
                                            @foreach ($benefits as $b)
                                                <div class="col-md-12">
                                                    <div class="border rounded p-3 mb-3">
                                                        <h5 class="mb-2">{{ $b->title }}</h5>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-5 col-12">
                    <div class="box">
                        <div class="box-header with-border d-flex justify-content-between align-items-center">
                            <h4 class="box-title">Social Media</h4>
                        </div>
                        <div class="box-body sm-panel">
                            <style>
                                .sm-panel .sm-heading { font-size: 1.05rem; font-weight: 600; }
                                .sm-panel .sm-content { font-size: 0.95rem; color: #8fa3b0; }
                            </style>
                            <p class="mb-2 sm-heading"><strong>Personal Details</strong></p>
                            <div class="sm-content">
                                <div>{{ $user->name }}</div>
                                <div>{{ $user->email }}</div>
                                <div>{{ $user->phone }}</div>
                                @if ($user->details)
                                    <div class="mt-2">
                                        <div>{{ $user->details->address_line1 }}</div>
                                        @if ($user->details->address_line2)
                                            <div>{{ $user->details->address_line2 }}</div>
                                        @endif
                                        <div>{{ $user->details->city }} {{ $user->details->zipcode }}</div>
                                    </div>
                                @endif
                            </div>

                            <hr>
                            <p class="mb-2 sm-heading"><strong>Norka ID</strong></p>
                            <div class="sm-content">
                                @if ($user->details?->norka_registration_number)
                                    <div>{{ $user->details->norka_registration_number }}</div>
                                @else
                                    <div>-</div>
                                @endif
                            </div>

                            <hr>
                            <p class="mb-2 sm-heading"><strong>Insurance Details</strong></p>
                            <div class="sm-content">
                                @if ($user->details)
                                    <div>Provider: {{ $user->details->insurance_provider ?: '-' }}</div>
                                    <div>Policy No: {{ $user->details->policy_number ?: '-' }}</div>
                                    <div>Expiry: {{ $user->details->expiry_date?->format('d M Y') ?: '-' }}</div>
                                    <div>Amount: {{ $user->details->amount !== null ? $user->details->amount : '-' }}</div>
                                @else
                                    <div>-</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection
@push('scripts')
    <script>
        const topup = document.getElementById('topup');
        topup.addEventListener('click', async function() {
            alert('Topup feature coming soon!');
        });
    </script>
@endpush

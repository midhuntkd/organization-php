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
    <section class="content ">
        <div class="box flex-fill mb-4">
            <div class="sm-panel p-4 prof-card">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <div class="d-flex align-items-center">
                            <div class="flex-fixed prof-thumb">
                                <img src="{{ $user->user_image_url }}" alt="User Avatar"
                                    class="img-thumbnail rounded-circle" style="max-width: 100%;">
                            </div>
                            <div class="flex-grow-1 ps-4">
                                <h6 class="">
                                    <span class="sm-content">Hi, {{ $user->name }}</span> (Member ID:
                                    {{ $user->membership_code ?? '—' }})
                                </h6>
                                <h2 class="fw-bold">Welcome, AJPS</h2>
                                <div class="d-flex sm-content">
                                    <div class="d-flex me-3">
                                        <span class="mdi mdi-email-outline me-2"></span>{{ $user->email }}
                                    </div>
                                    <div class="d-flex">
                                        <span class="mdi mdi-phone me-2"></span>{{ $user->phone }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex-fixed">
                        <a href="https://member.org.in/member/id-card" class="btn btn-primary ">
                            <i class="mdi mdi-card-account-details me-1"></i> View ID Card
                        </a>
                    </div>
                </div>
                <div class="my-3" style="height: 1px;background-color: #a1a4b5;opacity:50"></div>
                <div class="d-flex">
                    <div class="flex-grow-1 d-flex">
                        <div class="d-flex pe-50 border-end align-items-center">

                            <img src="{{ asset('hyper/images/card.svg') }}" alt="User Avatar" class="" width="28"
                                height="20" />
                            <div class="ps-2">
                                <div class="text-light">Norka ID</div>
                                <div class="text-dark h6 fw-semibold m-0">Norka ID</div>
                            </div>
                        </div>
                        <div class="d-flex pe-25 ps-25 align-items-center">

                            <img src="{{ asset('hyper/images/crown.svg') }}" alt="User Avatar" class="" width="27"
                                height="24" />
                            <div class="ps-2">
                                <div class="text-light">Membership Plan</div>
                                <div class="text-dark h6 fw-semibold m-0">{{ $membership->name }}</div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">

                            <img src="{{ asset('hyper/images/office.svg') }}" alt="User Avatar" class=""
                                width="22" height="19" />
                            <div class="ps-2">
                                <div class="text-light">Organisation</div>
                                <div class="text-dark h6 fw-semibold m-0">{{ $organization->name }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="flex-fixed">
                        @if ($pendingUpgradeRequest)
                            <a href="{{ route('member.membership.upgrade', $organization->slug) }}"
                                class="btn btn-light border border-light strip-btn disabled not-allowed"
                                title="Waiting for Aproovel">Upgrade</a>
                        @else
                            <a href="{{ route('member.membership.upgrade', $organization->slug) }}"
                                class="btn btn-light strip-btn">Upgrade</a>
                        @endif
                    </div>
                </div>
                @if ($pendingUpgradeRequest)
                <div class="border border-warning py-3 px-4 rounded border border-dashed mt-3 d-flex align-items-center"
                    role="alert">

                    <div class="flex-fixed pe-4"><img src="{{ asset('hyper/images/hourglass.svg') }}" alt="User Avatar"
                            class="" width="16" height="20" /></div>
                    <div class="flex-grow-1">
                        <div class="fw-medium">Waiting for Approval</div>
                        <div class="small">Your membership upgrade request for <b>{{ $pendingUpgradeRequest->membership_name }}</b> plan is waiting for admin approval.</div>

                    </div>
                </div>
                @endif
                @if ($rejectedUpgradeRequest && !$pendingUpgradeRequest)
                <div class="border border-danger py-3 px-4 rounded border border-dashed mt-3 d-flex align-items-center"
                    role="alert">

                    @php
                        $rejectReasonTitle = '';
                        $rejectReasonDesc = '';
                        if (!empty($rejectedUpgradeRequest?->reject_reason)) {
                            $parts = preg_split("/\n\n+/", $rejectedUpgradeRequest->reject_reason, 2);
                            $rejectReasonTitle = trim($parts[0] ?? '');
                            $rejectReasonDesc = trim($parts[1] ?? '');
                        }
                    @endphp
                    <div class="flex-fixed pe-4"><img src="{{ asset('hyper/images/blocked.svg') }}" alt="User Avatar"
                            class="" width="17" height="15" /></div>
                    <div class="flex-grow-1">
                        <div class="fw-medium text-danger">Request Rejected</div>
                        <div class="small">Your membership upgrade request for <b>{{ $rejectedUpgradeRequest->membership_name }}</b> plan is rejected</div>

                    </div>
                    <div class="flex-fixed pe-4">
                        <div class="d-flex gap-2">
                            <a href="#" class="btn btn-danger rounded-pill fs-6 py-2 px-3" data-bs-toggle="modal"
                                data-bs-target="#exampleModal">
                                <small> View Reason</small>
                            </a>
                            <form method="POST"
                                action="{{ route('member.membership_upgrades.hide', [$organization->slug, $rejectedUpgradeRequest->id]) }}">
                                @csrf
                                <button type="submit" class="btn btn-outline-secondary rounded-pill fs-6 py-2 px-3">
                                    <small>Hide</small>
                                </button>
                            </form>
                        </div>
                        <!-- Modal -->
                        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Reason for Rejection</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-2"><strong>Reason Title : </strong> {{ $rejectReasonTitle ?: 'No title provided' }}</div>
                                        @if($rejectReasonDesc)
                                            <div class="small "><strong>Reason : </strong>{!! nl2br(e($rejectReasonDesc)) !!}</div>
                                        @else
                                            <div class="small ">No description provided.</div>
                                        @endif
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
        <div class="row pb-3">
            <div class="col-lg-9 col-md-9 col-sm-7 col-12">
                <div class="box flex-fill p-4 mb-4 position-relative">
                    <a href="{{ route('member.profile.edit', $organization->slug) }}"
                        class="btn waves-effect waves-light nav-link btn-primary-light svg-bt-icon rounded-circle position-absolute end-0 top-0 p-0 d-flex align-items-center justify-content-center mt-2 me-2"
                        style="width:35px;height:35px;"><span class="mdi mdi-pencil"></span></a>
                    <div class="row align-items-center ">

                        <div class="col-md-auto col-3">
                            <img src="{{ asset('hyper/images/profile.svg') }}" alt="User Avatar" class=""
                                width="208" height="152" />
                        </div>
                        <div class="col-md-auto col-7">

                            <div class="sm-content text-light pb-2">Personal Details</div>
                            <h4 class="fw-medium"> {{ $user->name }}</h4>

                            @if ($user->details)
                                <div class="mt-2 text-light text-capitalize">
                                    <div><span class="mdi mdi-map-marker-outline"></span>
                                        {{ $user->details->address_line1 }}
                                        @if ($user->details->address_line2)
                                            {{ $user->details->address_line2 }},
                                        @endif
                                        {{ $user->details->city }} {{ $user->details->zipcode }}
                                    </div>
                                    <div><span class="mdi mdi-test-tube"></span> Blood Group :
                                        {{ $user->details->blood_group }}</div>
                                </div>
                            @endif

                        </div>
                    </div>
                </div>
                <div class="box flex-fill p-4 position-relative">
                    <a href=""
                        class="btn waves-effect waves-light nav-link btn-primary-light svg-bt-icon rounded-circle position-absolute end-0 top-0 p-0 d-flex align-items-center justify-content-center mt-2 me-2"
                        style="width:35px;height:35px;"><span class="mdi mdi-pencil"></span></a>
                    <div class="d-flex pb-3">
                        <div class="flex-fixed"><img src="{{ asset('hyper/images/shield.svg') }}" alt="User Avatar"
                                class="" width="42" height="47" /></div>
                        <div class="flex-fill ps-4">
                            <div class="sm-content text-light pb-2">Insurance Provider</div>
                            <h4 class="fw-medium"> {{ $user->details->insurance_provider ?: '' }}</h4>

                        </div>
                    </div>
                    @if ($user->details)
                        <div class="row">
                            <div class="col-md-4">
                                <div class="card-bg-light rounded p-3 d-flex gap-2 align-items-center">
                                    <img src="{{ asset('hyper/images/number.svg') }}" alt="User Avatar" class=""
                                        width="28" height="28" />
                                    <div>
                                        <div class="small text-light">Policy No</div>
                                        <div class="font-medium">{{ $user->details->policy_number ?: '-' }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card-bg-light rounded p-3 d-flex gap-2 align-items-center">
                                    <img src="{{ asset('hyper/images/calendar.svg') }}" alt="User Avatar" class=""
                                        width="24" height="24" />
                                    <div>
                                        <div class="small text-light">Expery</div>
                                        <div class="font-medium">
                                            {{ $user->details->expiry_date?->format('d M Y') ?: '-' }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card-bg-light rounded p-3 d-flex gap-2 align-items-center">
                                    <img src="{{ asset('hyper/images/rupee.svg') }}" alt="User Avatar" class=""
                                        width="24" height="24" />
                                    <div>
                                        <div class="small text-light">Expery</div>
                                        <div class="font-medium">
                                            {{ $user->details->amount !== null ? $user->details->amount : '-' }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-5 col-12">
                <div class="box flex-fill p-4 ">
                    <div class="text-center">
                        <img src="{{ asset('hyper/images/walet.svg') }}" alt="User Avatar" class=""
                            width="241" height="155" />
                    </div>
                    <h4 class="fw-medium text-center pb-3 pt-4"> Abdul Saleem</h4>




                    <div class="d-flex justify-content-between align-items-center pb-2 mb-2 ">
                        <span class="text-dark fw-medium">Pending Balance</span>
                        <span class="fw-medium text-primary">{{ number_format($wallet['pending_balance'], 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center pb-2 mb-2 ">
                        <span class="text-dark fw-medium">Payments Awaiting Approval</span>
                        <span class="fw-medium text-info">{{ number_format($wallet['pending_payments'], 2) }}</span>
                    </div>

                    <div class="d-flex gap-2 pt-4">
                        <button class="btn btn-primary btn-sm flex-fill" data-bs-toggle="modal"
                            data-bs-target="#topupModal">Topup / Pay</button>
                        <button class="btn btn-outline-secondary btn-sm flex-fill" data-bs-toggle="modal"
                            data-bs-target="#historyModal">View History</button>
                    </div>





                </div>
            </div>
        </div>
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
                            <div class="border-bottom">
                                <div class="py-2 m-0 d-flex align-items-center">
                                    <div class="flex-grow-1">{{ $b->title }}</div>
                                    <div class="flex-fixed">
                                        <button type="button" class="btn btn-primary h-6 py-1 px-3 rounded-pill"
                                            data-bs-toggle="modal" data-bs-target="#benefitModal-{{ $b->id }}">
                                            <small> Detail</small>
                                        </button>

                                        <div class="modal fade" id="benefitModal-{{ $b->id }}" tabindex="-1"
                                            aria-labelledby="benefitModalLabel-{{ $b->id }}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="benefitModalLabel-{{ $b->id }}">
                                                            {{ $b->title }}
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        {!! nl2br(e($b->description ?? 'No description provided.')) !!}
                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
        <div class="row">

            @if (session('status'))
                <div class="col-12">
                    <div class="alert alert-success">{{ session('status') }}</div>
                </div>
            @endif

            <div class="row d-none">
                <div class="col-lg-9 col-md-9 col-sm-7 col-12">
                    <div class="row g-3">
                        {{-- Profile card --}}
                        <div class="col-lg-6 col-md-6 col-sm-6 col-12 d-flex">
                            <div class="box flex-fill">
                                <div class="box-header with-border">
                                    <h4 class="box-title">Welcome, {{ $user->name }}</h4>
                                </div>
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-5">
                                            <img src="{{ $user->user_image_url }}" alt="User Avatar"
                                                class="img-thumbnail mb-3" style="max-width: 100%;">
                                        </div>
                                        <div class="col-7">

                                            <p class="mb-1"> {{ $user->email }}</p>
                                            <p class="mb-1"> {{ $user->phone }}</p>
                                            <p class="mb-1"> {{ $user->membership_code ?? '—' }}</p>
                                            <a href="{{ route('member.id-card') }}" class="btn btn-primary mt-3">
                                                <i class="mdi mdi-card-account-details me-1"></i> View ID Card
                                            </a>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        {{-- Rules --}}
                        <div class="col-lg-6 col-md-6 col-sm-6 col-12 d-flex">
                            <div class="box flex-fill">
                                <div class="box-header with-border d-flex justify-content-between align-items-center">
                                    <h4 class="box-title">Membership Details</h4>
                                    @if ($pendingUpgradeRequest)
                                        <span class="badge bg-warning text-dark">Request Pending</span>
                                    @else
                                        <a href="{{ route('member.membership.upgrade', $organization->slug) }}"
                                            class="btn btn-primary strip-btn">Upgrade Membership</a>
                                    @endif
                                </div>
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-7">
                                            <h3 class="mb-1"> {{ $membership->name }}</h3><br />
                                            <h3 class="mb-1">{{ $user->membership_code ?? '—' }}</h3>
                                        </div>
                                        <div class="col-5">
                                            <img src="{{ $organization->logo_url }}" alt="Logo" class="mb-3"
                                                style="max-height: 120px;">
                                            <h3 class="fw-bold text-dark mb-0">{{ $organization->name }}</h3>

                                        </div>
                                    </div>
                                    @if (!$pendingUpgradeRequest && $rejectedUpgradeRequest)
                                        <div
                                            class="alert alert-danger mt-3 d-flex justify-content-between align-items-center">
                                            <div>
                                                Your last upgrade request was rejected.
                                            </div>
                                            <button type="button" class="btn btn-sm btn-light" data-bs-toggle="modal"
                                                data-bs-target="#rejectReasonModal">
                                                View Reason
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Benefits --}}
                        <div class="col-lg-4 col-md-4 col-sm-4 col-12">
                            <div class="box">
                                <div class="box-header with-border d-flex justify-content-between align-items-center">
                                    <h4 class="box-title">My Wallet</h4>
                                </div>
                                <div class="box-body">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="text-dark fw-semibold">Pending Balance</span>
                                        <span
                                            class="fw-bold text-primary">{{ number_format($wallet['pending_balance'], 2) }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="text-dark fw-semibold">Payments Awaiting Approval</span>
                                        <span
                                            class="fw-bold text-info">{{ number_format($wallet['pending_payments'], 2) }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="text-dark fw-semibold">Plan</span>
                                        <span class="fw-bold text-success">{{ $membership->name }}</span>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-primary btn-sm flex-fill" data-bs-toggle="modal"
                                            data-bs-target="#topupModal">Topup / Pay</button>
                                        <button class="btn btn-outline-secondary btn-sm flex-fill" data-bs-toggle="modal"
                                            data-bs-target="#historyModal">View History</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-8 col-sm-8 col-12">

                        </div>
                    </div>
                </div>
                <style>
                    .sm-panel .sm-heading {
                        font-size: 1.05rem;
                        font-weight: 600;
                    }

                    .sm-panel .sm-content {
                        font-size: 0.95rem;
                        color: #8fa3b0;
                    }

                    .strip-btn {
                        font-size: .95rem;
                    }
                </style>
                <div class="col-lg-3 col-md-3 col-sm-5 col-12">
                    <div class="box">
                        <div class="box-header with-border d-flex justify-content-between align-items-center">
                            <h4 class="box-title">Personal Details</h4>
                        </div>
                        <div class="box-body sm-panel">

                            <div class="sm-content">

                                @if ($user->details)
                                    <div class="mt-2">
                                        <div>{{ $user->details->address_line1 }}</div>
                                        @if ($user->details->address_line2)
                                            <div>{{ $user->details->address_line2 }}</div>
                                        @endif
                                        <div>{{ $user->details->city }} {{ $user->details->zipcode }}</div>
                                        <div>Blood Group : {{ $user->details->blood_group }}</div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="box">
                        <div class="box-header with-border d-flex justify-content-between align-items-center">
                            <h4 class="box-title">Norka ID</h4>
                        </div>
                        <div class="box-body sm-panel">
                            <div class="sm-content">
                                @if ($user->details?->norka_registration_number)
                                    <div>{{ $user->details->norka_registration_number }}</div>
                                @else
                                    <div>-</div>
                                @endif
                            </div>

                        </div>
                    </div>


                    <div class="box">
                        <div class="box-header with-border d-flex justify-content-between align-items-center">
                            <h4 class="box-title">Insurance Details</h4>
                        </div>
                        <div class="box-body sm-panel">
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

    {{-- Topup Modal --}}
    <div class="modal fade" id="topupModal" tabindex="-1" aria-labelledby="topupModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" method="POST"
                action="{{ route('member.payments.store', $organization->slug) }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="topupModalLabel">Submit Payment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Amount <span class="text-danger">*</span></label>
                        <input type="number" name="amount" step="0.01" min="0.01" class="form-control"
                            required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Payment Method <span class="text-danger">*</span></label>
                        <select name="payment_method" id="payment_method" class="form-select" required>
                            <option value="online_transfer">Online Transfer</option>
                            <option value="by_hand">By Hand</option>
                            <option value="cheque_draft">Cheque / Draft</option>
                            <option value="other">Other Method</option>
                        </select>
                    </div>
                    <div class="mb-3" id="handoverRow">
                        <label class="form-label">Hand Over Person</label>
                        <input type="text" name="handover_person" class="form-control"
                            placeholder="Person who collected cash">
                    </div>
                    <div class="mb-3" id="proofRow">
                        <label class="form-label">Proof Image</label>
                        <input type="file" name="proof" accept="image/*" class="form-control">
                        <small class="text-muted">Required for methods other than "By Hand".</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Payment Date <span class="text-danger">*</span></label>
                        <input type="date" name="payment_date" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3" maxlength="2000" placeholder="Optional notes..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Submit for Approval</button>
                </div>
            </form>
        </div>
    </div>

    {{-- History Modal --}}
    <div class="modal fade" id="historyModal" tabindex="-1" aria-labelledby="historyModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="historyModalLabel">Payment & Charges History</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted small mb-2">Showing your latest 10 transactions.</p>
                    <div class="table-responsive">
                        <table class="table table-sm align-middle">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Reason</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($wallet['history'] as $entry)
                                    <tr>
                                        <td>{{ $entry->created_at?->format('d M Y') }}</td>
                                        <td>{{ ucfirst($entry->entry_type) }}</td>
                                        <td>{{ $entry->reason }}</td>
                                        <td
                                            class="fw-bold {{ $entry->entry_type === 'charge' ? 'text-danger' : 'text-success' }}">
                                            {{ $entry->entry_type === 'charge' ? '-' : '+' }}{{ number_format($entry->amount, 2) }}
                                        </td>
                                        <td>
                                            @if ($entry->status === 'approved')
                                                <span class="badge bg-success">Approved</span>
                                            @elseif($entry->status === 'pending')
                                                <span class="badge bg-warning text-dark">Pending</span>
                                            @elseif($entry->status === 'cancelled')
                                                <span class="badge bg-secondary">Cancelled</span>
                                            @else
                                                <span class="badge bg-danger">Rejected</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($entry->status === 'rejected' && $entry->rejected_reason)
                                                <div><strong>Rejected:</strong> {!! nl2br(e($entry->rejected_reason)) !!}</div>
                                            @endif
                                            @if ($entry->description)
                                                <div>{!! nl2br(e($entry->description)) !!}</div>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">No history yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="{{ route('member.transactions.index', $organization->slug) }}" class="btn btn-primary">View
                        All Transactions</a>
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    @if (!$pendingUpgradeRequest && $rejectedUpgradeRequest)
        <div class="modal fade" id="rejectReasonModal" tabindex="-1" aria-labelledby="rejectReasonModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="rejectReasonModalLabel">Upgrade Request Rejection</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="mb-1"><strong>Requested Plan:</strong>
                            {{ $rejectedUpgradeRequest->membership?->name ?? '-' }}</p>
                        <p class="mb-3"><strong>Rejected On:</strong>
                            {{ $rejectedUpgradeRequest->rejected_at?->format('d M Y, h:i A') ?? '-' }}</p>
                        <div class="border rounded p-3 bg-light">
                            {!! nl2br(e($rejectedUpgradeRequest->reject_reason ?? 'No reason provided.')) !!}
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <a href="{{ route('member.membership.upgrade', $organization->slug) }}"
                            class="btn btn-primary">Resubmit Upgrade</a>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const methodSelect = document.getElementById('payment_method');
            const proofInputRow = document.getElementById('proofRow');
            const handoverRow = document.getElementById('handoverRow');

            const toggleConditionalFields = () => {
                const method = methodSelect.value;
                if (method === 'by_hand') {
                    handoverRow.classList.remove('d-none');
                    proofInputRow.classList.add('d-none');
                } else {
                    handoverRow.classList.add('d-none');
                    proofInputRow.classList.remove('d-none');
                }
            };

            methodSelect.addEventListener('change', toggleConditionalFields);
            toggleConditionalFields();
        });
    </script>
@endpush

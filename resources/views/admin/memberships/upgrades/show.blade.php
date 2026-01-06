@extends('layouts.inner_page')

@section('page_title', 'Upgrade Request')

@section('breadcrumb')
<ol class="breadcrumb">
    <li class="breadcrumb-item">
        <a href="{{ route('orgadmin.dashboard', $organization->slug) }}">
            <i class="mdi mdi-home-outline"></i>
        </a>
    </li>
    <li class="breadcrumb-item"><a href="{{ route('orgadmin.membership_upgrades.index', $organization->slug) }}">Upgrade Requests</a></li>
    <li class="breadcrumb-item active" aria-current="page">Request #{{ $upgradeRequest->id }}</li>
</ol>
@endsection

@section('content')
<section class="content">
    <div class="row">
        <div class="col-12">
            @if(session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div class="box">
                <div class="box-header with-border d-flex justify-content-between align-items-center">
                    <h4 class="box-title">Member</h4>
                    <span class="badge bg-secondary text-uppercase">{{ $upgradeRequest->status }}</span>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Name:</strong> {{ $upgradeRequest->user?->name ?? '-' }}</p>
                            <p class="mb-1"><strong>Email:</strong> {{ $upgradeRequest->user?->email ?? '-' }}</p>
                            <p class="mb-1"><strong>Phone:</strong> {{ $upgradeRequest->user?->phone ?? '-' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Current Plan:</strong> {{ $upgradeRequest->currentMembership?->name ?? '-' }}</p>
                            <p class="mb-1"><strong>Joined On:</strong>
                                {{ $upgradeRequest->user?->membership_started_at
                                    ? \Illuminate\Support\Carbon::parse($upgradeRequest->user->membership_started_at)->format('d M Y')
                                    : '-' }}
                            </p>
                            <p class="mb-1"><strong>Pending Amount:</strong>
                                <span class="badge bg-warning text-dark">{{ number_format($pendingBalance ?? 0, 2) }}</span>
                            </p>
                            <p class="mb-1"><strong>Requested Plan:</strong> {{ $upgradeRequest->membership?->name ?? '-' }}</p>
                            <p class="mb-1"><strong>Requested On:</strong> {{ $upgradeRequest->created_at?->format('d M Y, h:i A') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="box">
                <div class="box-header with-border">
                    <h4 class="box-title mb-0">Requested Plan Details</h4>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Name:</strong> {{ $upgradeRequest->membership?->name ?? '-' }}</p>
                            <p class="mb-1"><strong>Joining Fee:</strong> {{ $upgradeRequest->membership?->joining_fee ?? '-' }}</p>
                            <p class="mb-3"><strong>Monthly Fee:</strong> {{ $upgradeRequest->membership?->monthly_fee ?? '-' }}</p>

                            <h5>Rules</h5>
                            <ul class="mb-3">
                                @forelse ($upgradeRequest->membership?->rules ?? [] as $rule)
                                    <li class="mb-1"><strong>{{ $rule->title }}</strong><br><small class="text-muted">{{ $rule->description }}</small></li>
                                @empty
                                    <li class="text-muted">No rules defined.</li>
                                @endforelse
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h5>Benefits</h5>
                            <ul class="mb-3">
                                @forelse ($upgradeRequest->membership?->benefits ?? [] as $benefit)
                                    <li class="mb-1"><strong>{{ $benefit->title }}</strong><br><small class="text-muted">{{ $benefit->description }}</small></li>
                                @empty
                                    <li class="text-muted">No benefits defined.</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2">
                @if(in_array($upgradeRequest->status, ['requested','hold']))
                <form action="{{ route('orgadmin.membership_upgrades.approve', [$organization->slug, $upgradeRequest->id]) }}"
                      method="POST"
                      onsubmit="return confirm('Approve this membership upgrade?');">
                    @csrf
                    <button class="btn btn-success"><i class="mdi mdi-check"></i> Approve</button>
                </form>

                <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
                    <i class="mdi mdi-close"></i> Reject
                </button>
                @endif

                <a href="{{ route('orgadmin.membership_upgrades.index', $organization->slug) }}" class="btn btn-light">Back</a>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('orgadmin.membership_upgrades.reject', [$organization->slug, $upgradeRequest->id]) }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="rejectModalLabel">Reject Upgrade Request</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Reason <span class="text-danger">*</span></label>
                        <input type="text" name="reason" class="form-control" required maxlength="255">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3" maxlength="2000" placeholder="Optional details..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reject</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

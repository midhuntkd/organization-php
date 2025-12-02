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
                                        <a href="{{ route('member.membership.upgrade', $organization->slug) }}" class="btn btn-primary strip-btn">Upgrade Membership</a>
                                    @endif
                                </div>
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-7">
                                            <h3 class="mb-1"> {{ $membership->name }}</h3><br />
                                            <h3 class="mb-1">{{ $user->membership_code ?? '—' }}</h3>
                                        </div>
                                        <div class="col-5">
                                            <img src="{{ $organization->logo_url }}" alt="Logo" class="mb-3" style="max-height: 120px;">
                                            <h3 class="fw-bold text-dark mb-0">{{ $organization->name }}</h3>
                                            
                                        </div>
                                    </div>
                                    @if(!$pendingUpgradeRequest && $rejectedUpgradeRequest)
                                        <div class="alert alert-danger mt-3 d-flex justify-content-between align-items-center">
                                            <div>
                                                Your last upgrade request was rejected.
                                            </div>
                                            <button type="button" class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#rejectReasonModal">
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
                                        <span class="fw-bold text-primary">{{ number_format($wallet['pending_balance'], 2) }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="text-dark fw-semibold">Payments Awaiting Approval</span>
                                        <span class="fw-bold text-info">{{ number_format($wallet['pending_payments'], 2) }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="text-dark fw-semibold">Plan</span>
                                        <span class="fw-bold text-success">{{ $membership->name }}</span>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-primary btn-sm flex-fill" data-bs-toggle="modal" data-bs-target="#topupModal">Topup / Pay</button>
                                        <button class="btn btn-outline-secondary btn-sm flex-fill" data-bs-toggle="modal" data-bs-target="#historyModal">View History</button>
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
                <style>
                    .sm-panel .sm-heading { font-size: 1.05rem; font-weight: 600; }
                    .sm-panel .sm-content { font-size: 0.95rem; color: #8fa3b0; }
                    .strip-btn{ font-size: .95rem;}
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
            <form class="modal-content" method="POST" action="{{ route('member.payments.store', $organization->slug) }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="topupModalLabel">Submit Payment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Amount <span class="text-danger">*</span></label>
                        <input type="number" name="amount" step="0.01" min="0.01" class="form-control" required>
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
                        <input type="text" name="handover_person" class="form-control" placeholder="Person who collected cash">
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
                                        <td class="fw-bold {{ $entry->entry_type === 'charge' ? 'text-danger' : 'text-success' }}">
                                            {{ $entry->entry_type === 'charge' ? '-' : '+' }}{{ number_format($entry->amount, 2) }}
                                        </td>
                                        <td>
                                            @if($entry->status === 'approved')
                                                <span class="badge bg-success">Approved</span>
                                            @elseif($entry->status === 'pending')
                                                <span class="badge bg-warning text-dark">Pending</span>
                                            @else
                                                <span class="badge bg-danger">Rejected</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($entry->status === 'rejected' && $entry->rejected_reason)
                                                <div><strong>Rejected:</strong> {!! nl2br(e($entry->rejected_reason)) !!}</div>
                                            @endif
                                            @if($entry->description)
                                                <div>{!! nl2br(e($entry->description)) !!}</div>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="text-center text-muted">No history yet.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="{{ route('member.transactions.index', $organization->slug) }}" class="btn btn-primary">View All Transactions</a>
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    @if(!$pendingUpgradeRequest && $rejectedUpgradeRequest)
    <div class="modal fade" id="rejectReasonModal" tabindex="-1" aria-labelledby="rejectReasonModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="rejectReasonModalLabel">Upgrade Request Rejection</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-1"><strong>Requested Plan:</strong> {{ $rejectedUpgradeRequest->membership?->name ?? '-' }}</p>
                    <p class="mb-3"><strong>Rejected On:</strong> {{ $rejectedUpgradeRequest->rejected_at?->format('d M Y, h:i A') ?? '-' }}</p>
                    <div class="border rounded p-3 bg-light">
                        {!! nl2br(e($rejectedUpgradeRequest->reject_reason ?? 'No reason provided.')) !!}
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <a href="{{ route('member.membership.upgrade', $organization->slug) }}" class="btn btn-primary">Resubmit Upgrade</a>
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

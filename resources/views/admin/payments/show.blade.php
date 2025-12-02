@extends('layouts.inner_page')

@section('page_title', 'Payment Details')

@section('breadcrumb')
<ol class="breadcrumb">
    <li class="breadcrumb-item">
        <a href="{{ route('orgadmin.dashboard', $organization->slug) }}">
            <i class="mdi mdi-home-outline"></i>
        </a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('orgadmin.payments.index', $organization->slug) }}">Payment Approvals</a>
    </li>
    <li class="breadcrumb-item active" aria-current="page">Payment #{{ $payment->id }}</li>
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
                    <h4 class="box-title mb-0">Payment Details</h4>
                    @php
                        $statusClass = match ($payment->status) {
                            'approved' => 'bg-success',
                            'pending' => 'bg-warning text-dark',
                            default => 'bg-danger',
                        };
                    @endphp
                    <span class="badge {{ $statusClass }}">{{ ucfirst($payment->status) }}</span>
                </div>
                <div class="box-body">
                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <div class="detail-card p-3 h-100">
                                <div class="detail-label">Member</div>
                                <div class="detail-value mb-1">{{ $payment->user?->name ?? '-' }}</div>
                                <div class="detail-sub">{{ $payment->user?->email ?? '-' }}</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="detail-card p-3 h-100">
                                <div class="detail-label">Payment</div>
                                <div class="detail-value">Method: {{ str_replace('_',' ', $payment->payment_method ?? '-') }}</div>
                                <div class="detail-sub">Amount: {{ number_format($payment->amount, 2) }}</div>
                                <div class="detail-sub">Date: {{ $payment->payment_date?->format('d M Y') ?? '-' }}</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="detail-card p-3 h-100">
                                <div class="detail-label">Membership</div>
                                <div class="detail-value mb-2">{{ $payment->membership?->name ?? '-' }}</div>
                                <div class="detail-label mb-1">Proof</div>
                                @if($payment->proof_path)
                                    <a href="{{ asset('storage/'.$payment->proof_path) }}" target="_blank" class="btn btn-outline-light btn-sm">View Proof</a>
                                @else
                                    <div class="detail-sub">No proof uploaded.</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="detail-card p-3 mb-3">
                        <div class="detail-label mb-2">Description</div>
                        <div class="description-box">
                            {!! nl2br(e($payment->description ?? 'No description provided.')) !!}
                        </div>
                    </div>
                    @if($payment->status === 'rejected' && $payment->rejected_reason)
                        <div class="detail-card p-3">
                            <div class="detail-label mb-2 text-danger">Rejection Notes</div>
                            <div class="description-box">
                                {!! nl2br(e($payment->rejected_reason)) !!}
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="box">
                <div class="box-header with-border">
                    <h4 class="box-title mb-0">Review Actions</h4>
                </div>
                <div class="box-body">
                    <div class="action-card p-3">
                        @if($payment->status === 'pending')
                            <div class="d-flex flex-column flex-lg-row gap-3 review-row align-items-start">
                                <form action="{{ route('orgadmin.payments.approve', [$organization->slug, $payment->id]) }}" method="POST" onsubmit="return confirm('Approve this payment?');" class="d-grid approve-form">
                                    @csrf
                                    <button type="submit" class="btn btn-success">
                                        <i class="mdi mdi-check"></i> Approve Payment
                                    </button>
                                </form>
                                <button type="button" class="btn btn-danger reject-trigger">
                                    <i class="mdi mdi-close"></i> Reject Payment
                                </button>
                            </div>
                        @else
                            @php
                                $reasonTitle = '';
                                $reasonDesc = '';
                                if ($payment->status === 'rejected' && $payment->rejected_reason) {
                                    $parts = preg_split("/\\n\\n+/", $payment->rejected_reason, 2);
                                    $reasonTitle = trim($parts[0] ?? '');
                                    $reasonDesc = trim($parts[1] ?? '');
                                }
                            @endphp
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="detail-card p-3 h-100">
                                        <div class="detail-label">Status</div>
                                        <div class="detail-value">{{ ucfirst($payment->status) }}</div>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="detail-card p-3 h-100">
                                        <div class="detail-label mb-2">Review Notes</div>
                                        <div class="detail-value mb-1">{{ $reasonTitle ?: 'No title provided' }}</div>
                                        @if($reasonDesc)
                                            <div class="detail-sub">{!! nl2br(e($reasonDesc)) !!}</div>
                                        @else
                                            <div class="detail-sub text-muted">No additional description.</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <a href="{{ route('orgadmin.payments.index', $organization->slug) }}" class="btn btn-outline-secondary mt-2">
                <i class="mdi mdi-arrow-left"></i> Back to Pending Payments
            </a>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    .detail-card {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 12px;
    }
    .detail-label {
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: #9fb3c8;
    }
    .detail-value {
        font-size: 1.05rem;
        color: #f4f6fb;
        font-weight: 600;
    }
    .detail-sub {
        color: #c8d3e0;
        font-size: 0.92rem;
    }
    .description-box {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 10px;
        padding: 12px;
        color: #f4f6fb;
        white-space: pre-wrap;
    }
    .action-card {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 12px;
    }
    .payment-input {
        background: rgba(255, 255, 255, 0.06);
        border: 1px solid rgba(255, 255, 255, 0.18);
        color: #f4f6fb;
        min-height: 44px;
    }
    .payment-input::placeholder {
        color: #c8d3e0;
        opacity: 0.9;
    }
    .review-row .btn {
        min-height: 44px;
    }
    .approve-form {
        min-width: 180px;
    }
    .reject-trigger {
        min-height: 44px;
        min-width: 170px;
    }
</style>
@endpush

@push('modals')
<div class="modal fade" id="rejectPaymentModal" tabindex="-1" aria-labelledby="rejectPaymentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" id="rejectPaymentForm" action="{{ route('orgadmin.payments.reject', [$organization->slug, $payment->id]) }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="rejectPaymentModalLabel">Reject Payment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Reason <span class="text-danger">*</span></label>
                        <input type="text" name="reason" class="form-control" required maxlength="255" placeholder="Title of the reason">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3" maxlength="2000" placeholder="Add more details (optional)"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reject Payment</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const trigger = document.querySelector('.reject-trigger');
        const modalEl = document.getElementById('rejectPaymentModal');
        if (trigger && modalEl) {
            trigger.addEventListener('click', () => {
                const modal = new bootstrap.Modal(modalEl);
                modal.show();
            });
        }
    });
</script>
@endpush

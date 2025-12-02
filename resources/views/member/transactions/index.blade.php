@extends('layouts.inner_page')

@section('page_title', 'Transactions')

@section('breadcrumb')
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ $dashboardRoute }}">
                <i class="mdi mdi-home-outline"></i>
            </a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">Transactions</li>
    </ol>
@endsection

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="box">
                    <div class="box-header with-border d-flex justify-content-between align-items-center">
                        <h4 class="box-title mb-0">All Transactions</h4>
                    </div>
                    <div class="box-body">
                        <form method="GET" action="{{ $transactionsRoute }}" class="mb-3">
                            <div class="row g-2 align-items-end">
                                <div class="col-md-3">
                                    <label class="form-label">Start Date</label>
                                    <input type="date" name="start_date" class="form-control"
                                        value="{{ $filters['start_date'] }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">End Date</label>
                                    <input type="date" name="end_date" class="form-control"
                                        value="{{ $filters['end_date'] }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Entry Type</label>
                                    <select name="entry_type" class="form-select">
                                        <option value="">All</option>
                                        <option value="payment" @selected($filters['entry_type'] === 'payment')>Payment</option>
                                        <option value="charge" @selected($filters['entry_type'] === 'charge')>Charge</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Method</label>
                                    <select name="payment_method" class="form-select">
                                        <option value="">All</option>
                                        @foreach ($paymentMethods as $key => $label)
                                            <option value="{{ $key }}" @selected($filters['payment_method'] === $key)>
                                                {{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @if ($isOrgAdmin)
                                    <div class="col-md-2">
                                        <label class="form-label">User ID</label>

                                        <select name="user_id" class="form-select">
                                            <option value="">All Members</option>
                                            @foreach ($organizationMembers as $member)
                                                <option value="{{ $member->id }}" @selected((string) $filters['user_id'] === (string) $member->id)>
                                                    {{ $member->name }} (#{{ $member->id }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif
                                <div class="col-12 d-flex gap-2 mt-2">
                                    <button type="submit" class="btn btn-primary">Filter</button>
                                    <a href="{{ $transactionsRoute }}" class="btn btn-outline-secondary">Clear</a>
                                </div>
                            </div>
                        </form>

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover align-middle">
                                <thead>
                                    <tr class="text-dark">
                                        <th>Date</th>
                                        <th>Type</th>
                                        <th>Member</th>
                                        <th>Method</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($transactions as $txn)
                                        <tr>
                                            <td>{{ $txn->payment_date?->format('d M Y') ?? $txn->created_at?->format('d M Y') }}
                                            </td>
                                            <td class="text-capitalize">{{ $txn->entry_type }}</td>
                                            <td>{{ $txn->user?->name ?? '-' }} <span
                                                    class="text-muted">(#{{ $txn->user_id }})</span></td>
                                            <td>{{ str_replace('_', ' ', $txn->payment_method ?? '-') }}</td>
                                            <td
                                                class="fw-bold {{ $txn->entry_type === 'charge' ? 'text-danger' : 'text-success' }}">
                                                {{ $txn->entry_type === 'charge' ? '-' : '+' }}{{ number_format($txn->amount, 2) }}
                                            </td>
                                            <td>
                                                @if ($txn->status === 'approved')
                                                    <span class="badge bg-success">Approved</span>
                                                @elseif($txn->status === 'pending')
                                                    <span class="badge bg-warning text-dark">Pending</span>
                                                @else
                                                    <span class="badge bg-danger">Rejected</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($txn->status === 'rejected' && $txn->rejected_reason)
                                                    <div><strong>Rejected:</strong> {!! nl2br(e($txn->rejected_reason)) !!}</div>
                                                @endif
                                                @if ($txn->description)
                                                    <div>{!! nl2br(e($txn->description)) !!}</div>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted">No transactions found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-3">
                            {{ $transactions->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

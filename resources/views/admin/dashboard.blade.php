@extends('layouts.inner_page')

@section('page_title', 'Organization Dashboard')

@section('breadcrumb')
<ol class="breadcrumb">
    <li class="breadcrumb-item">
        <a href="{{ route('orgadmin.dashboard', $organization->slug) }}">
            <i class="mdi mdi-home-outline"></i>
        </a>
    </li>
    <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
</ol>
@endsection

@section('content')
<section class="content">
    <div class="row">
        <div class="col-12">
            <div class="row g-3">
                <div class="col-md-3">
                    <div class="box p-3">
                        <div class="text-muted">Membership Plans</div>
                        <div class="h3 mb-0">{{ $membershipCount }}</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="box p-3">
                        <div class="text-muted">Members</div>
                        <div class="h3 mb-0">{{ $membersCount }}</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="box p-3">
                        <div class="text-muted">Pending Member Approvals</div>
                        <div class="h3 mb-0">{{ $pendingMemberApprovals }}</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="box p-3">
                        <div class="text-muted">Payment Approvals</div>
                        <div class="h3 mb-0">{{ $pendingPaymentApprovals }}</div>
                    </div>
                </div>
            </div>

            <div class="row g-3 mt-1">
                <div class="col-lg-6">
                    <div class="box p-3 h-100">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div>
                                <div class="text-muted">Newly Joined Members</div>
                                <div class="h3 mb-0">{{ $newMembersCount }}</div>
                                <small class="text-muted">
                                    {{ $joinedStart->format('d M Y') }} - {{ $joinedEnd->format('d M Y') }}
                                </small>
                            </div>
                        </div>
                        {{-- <form method="GET" class="row g-2 align-items-end">
                            <div class="col-md-5">
                                <label class="form-label">Start Date</label>
                                <input type="date" name="joined_start" class="form-control"
                                    value="{{ request('joined_start') ?? $joinedStart->format('Y-m-d') }}">
                            </div>
                            <div class="col-md-5">
                                <label class="form-label">End Date</label>
                                <input type="date" name="joined_end" class="form-control"
                                    value="{{ request('joined_end') ?? $joinedEnd->format('Y-m-d') }}">
                            </div>
                            <div class="col-md-2 d-flex gap-2">
                                <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                            </div>
                        </form> --}}
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="box p-3 h-100">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div>
                                <div class="text-muted">Balance Amount To Collect</div>
                                <div class="h3 mb-0">{{ number_format($totalBalanceToCollect, 2) }}</div>
                            </div>
                        </div>
                        {{-- <form method="GET" class="row g-2 align-items-end">
                            <div class="col-md-5">
                                <label class="form-label">Start Date</label>
                                <input type="date" name="balance_start" class="form-control"
                                    value="{{ $balanceStart }}">
                            </div>
                            <div class="col-md-5">
                                <label class="form-label">End Date</label>
                                <input type="date" name="balance_end" class="form-control"
                                    value="{{ $balanceEnd }}">
                            </div>
                            <div class="col-md-2 d-flex gap-2">
                                <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                            </div>
                        </form>
                        <small class="text-muted d-block mt-2">Filters use approved charges and payments.</small> --}}
                    </div>
                </div>
            </div>

            <div class="row g-3 mt-1">
                <div class="col-lg-6">
                    <div class="box">
                        <div class="box-header with-border">
                            <h4 class="box-title mb-0">Memberships & Users</h4>
                        </div>
                        <div class="box-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover align-middle">
                                    <thead>
                                        <tr class="text-dark">
                                            <th>Name</th>
                                            <th>Prefix</th>
                                            <th>Total Users</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($memberships as $membership)
                                            <tr>
                                                <td>{{ $membership->name }}</td>
                                                <td>{{ $membership->prefix }}</td>
                                                <td>{{ $membership->users_count }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="text-center text-muted">No memberships found.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="box">
                        <div class="box-header with-border">
                            <h4 class="box-title mb-0">Newly Joined (Top 10)</h4>
                        </div>
                        <div class="box-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover align-middle">
                                    <thead>
                                        <tr class="text-dark">
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>Membership</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($newMembers as $member)
                                            <tr>
                                                <td>{{ $member->name }}</td>
                                                <td>{{ $member->email }}</td>
                                                <td>{{ $member->phone }}</td>
                                                <td>{{ $member->membership?->name ?? '-' }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center text-muted">No new members found.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-3 mt-1">
                <div class="col-12">
                    <div class="box">
                        <div class="box-header with-border d-flex justify-content-between align-items-center">
                            <h4 class="box-title mb-0">Last Month Transactions</h4>
                            <small class="text-muted">
                                {{ $lastMonthStart->format('d M Y') }} - {{ $lastMonthEnd->format('d M Y') }}
                            </small>
                        </div>
                        <div class="box-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover align-middle">
                                    <thead>
                                        <tr class="text-dark">
                                            <th>Date</th>
                                            <th>Type</th>
                                            <th>Member</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($lastMonthTransactions as $txn)
                                            <tr>
                                                <td>{{ $txn->payment_date?->format('d M Y') ?? $txn->created_at?->format('d M Y') }}</td>
                                                <td class="text-capitalize">{{ $txn->entry_type }}</td>
                                                <td>{{ $txn->user?->name ?? '-' }}</td>
                                                <td class="fw-bold">
                                                    {{ $txn->entry_type === 'charge' ? '-' : '+' }}{{ number_format($txn->amount, 2) }}
                                                </td>
                                                <td>
                                                    @if ($txn->status === 'approved')
                                                        <span class="badge bg-success">Approved</span>
                                                    @elseif($txn->status === 'pending')
                                                        <span class="badge bg-warning text-dark">Pending</span>
                                                    @elseif($txn->status === 'cancelled')
                                                        <span class="badge bg-secondary">Cancelled</span>
                                                    @else
                                                        <span class="badge bg-danger">Rejected</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center text-muted">No transactions found.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

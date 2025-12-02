@extends('layouts.inner_page')

@section('page_title', 'Payment Approvals')

@section('breadcrumb')
<ol class="breadcrumb">
    <li class="breadcrumb-item">
        <a href="{{ route('orgadmin.dashboard', $organization->slug) }}">
            <i class="mdi mdi-home-outline"></i>
        </a>
    </li>
    <li class="breadcrumb-item active" aria-current="page">Payment Approvals</li>
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
                    <h4 class="box-title">Pending Payments</h4>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table text-fade table-bordered table-hover display nowrap margin-top-10 w-p100">
                            <thead>
                                <tr class="text-dark">
                                    <th>Member</th>
                                    <th>Email</th>
                                    <th>Method</th>
                                    <th>Amount</th>
                                    <th>Date</th>
                                    <th>Proof</th>
                                    <th>Description</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($payments as $payment)
                                <tr>
                                    <td class="fw-600 text-dark">{{ $payment->user?->name ?? '-' }}</td>
                                    <td>{{ $payment->user?->email ?? '-' }}</td>
                                    <td>{{ str_replace('_',' ', $payment->payment_method ?? '-') }}</td>
                                    <td class="fw-bold">{{ number_format($payment->amount, 2) }}</td>
                                    <td>{{ $payment->payment_date?->format('d M Y') ?? '-' }}</td>
                                    <td>
                                        @if($payment->proof_path)
                                            <a href="{{ asset('storage/'.$payment->proof_path) }}" target="_blank" class="btn btn-sm btn-outline-secondary">View</a>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>{!! nl2br(e($payment->description ?? '-')) !!}</td>
                                    <td class="d-flex gap-1">
                                        <a href="{{ route('orgadmin.payments.show', [$organization->slug, $payment->id]) }}" class="btn btn-sm btn-info">
                                            <i class="mdi mdi-eye"></i> View
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="8" class="text-center">No pending payments.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@extends('layouts.inner_page')

@section('page_title', 'Membership Upgrade Requests')

@section('breadcrumb')
<ol class="breadcrumb">
    <li class="breadcrumb-item">
        <a href="{{ route('orgadmin.dashboard', $organization->slug) }}">
            <i class="mdi mdi-home-outline"></i>
        </a>
    </li>
    <li class="breadcrumb-item">Membership Plans</li>
    <li class="breadcrumb-item active" aria-current="page">Upgrade Requests</li>
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
                    <h4 class="box-title">Pending Upgrade Requests</h4>
                </div>

                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table text-fade table-bordered table-hover display nowrap margin-top-10 w-p100">
                            <thead>
                                <tr class="text-dark">
                                    <th>Member</th>
                                    <th>Email</th>
                                    <th>Current Plan</th>
                                    <th>Requested Plan</th>
                                    <th>Requested On</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($requests as $req)
                                <tr>
                                    <td class="fw-600 text-dark">{{ $req->user?->name ?? '-' }}</td>
                                    <td>{{ $req->user?->email ?? '-' }}</td>
                                    <td>{{ $req->currentMembership?->name ?? '-' }}</td>
                                    <td class="fw-600">{{ $req->membership?->name ?? '-' }}</td>
                                    <td>{{ $req->created_at?->format('d M Y, h:i A') }}</td>
                                    <td class="d-flex gap-1">
                                        <a href="{{ route('orgadmin.membership_upgrades.show', [$organization->slug, $req->id]) }}"
                                           class="btn btn-sm btn-info">
                                            <i class="mdi mdi-eye"></i> View
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">No pending requests found.</td>
                                </tr>
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

@extends('layouts.inner_page')

@section('page_title', 'Member Permissions')

@section('breadcrumb')
<ol class="breadcrumb">
    <li class="breadcrumb-item">
        <a href="{{ route('orgadmin.dashboard', $organization->slug) }}">
            <i class="mdi mdi-home-outline"></i>
        </a>
    </li>
    <li class="breadcrumb-item">Members</li>
    <li class="breadcrumb-item active" aria-current="page">Permissions</li>
</ol>
@endsection

@section('content')
<section class="content">
    <div class="row">
        <div class="col-12">
            <div class="box">
                <div class="box-header with-border d-flex justify-content-between align-items-center">
                    <h4 class="box-title">Member Permissions</h4>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table text-fade table-bordered table-hover display nowrap margin-top-10 w-p100">
                            <thead>
                                <tr class="text-dark">
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Permissions</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($members as $member)
                                <tr>
                                    <td class="fw-600 text-dark">{{ $member->name }}</td>
                                    <td>{{ $member->email }}</td>
                                    <td>
                                        @php $names = $member->getPermissionNames(); @endphp
                                        @if($names->isEmpty())
                                            <span class="text-muted">None</span>
                                        @else
                                            <span class="badge bg-primary">{{ $names->implode(', ') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('orgadmin.member.view', [$organization->slug, $member->id]) }}#permissions"
                                           class="btn btn-sm btn-primary">
                                            Manage
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">No members found.</td>
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

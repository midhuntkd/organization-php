@extends('layouts.inner_page')
@section('page_title', 'Organizations')

@section('breadcrumb')
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('superadmin.dashboard') }}">
                <i class="mdi mdi-home-outline"></i>
            </a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">Organizations</li>
    </ol>
@endsection

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-12">

                @if (session('status'))
                    <div class="alert alert-success">{{ session('status') }}</div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $e)
                                <li>{{ $e }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="box">
                    <div class="box-header with-border d-flex justify-content-between align-items-center">
                        <h4 class="box-title">Organizations List</h4>
                        <a href="{{ route('superadmin.organizations.create') }}" class="btn btn-primary btn-sm"><i
                                class="mdi mdi-plus"></i>New Organization</a>
                    </div>

                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table text-fade table-bordered table-hover display nowrap margin-top-10 w-p100">
                                <thead>
                                    <tr class="text-dark">
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Prefix</th>
                                        <th>Slug</th>
                                        <th>Logo</th>
                                        <th>Admin</th>
                                        <th width="220">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($organizations as $org)
                                        @php
                                            $admin = $org
                                                ->users()
                                                ->whereHas('roles', fn($q) => $q->where('name', 'organization-admin'))
                                                ->first();
                                        @endphp
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $org->name }}</td>
                                            <td>{{ $org->org_prefix }}</td>
                                            <td>{{ $org->slug }}</td>
                                            <td>
                                                @if ($org->logo_url)
                                                    <img src="{{ $org->logo_url }}" width="60">
                                                @endif
                                            </td>
                                            <td>{{ $admin?->name ?? '—' }}</td>
                                            <td class="d-flex gap-1">
                                                <a href="{{ route('superadmin.organizations.show', $org) }}"
                                                    class="btn btn-info btn-sm"><i class="mdi mdi-eye"></i></a>
                                                <a href="{{ route('superadmin.organizations.edit', $org) }}"
                                                    class="btn btn-warning btn-sm"><i class="mdi mdi-pencil"></i></a>
                                                <form action="{{ route('superadmin.organizations.destroy', $org) }}"
                                                    method="POST" style="display:inline">
                                                    @csrf @method('DELETE')
                                                    <button onclick="return confirm('Delete organization?')"
                                                        class="btn btn-danger btn-sm"><i
                                                            class="mdi mdi-delete"></i></button>
                                                </form>
                                                {{-- @if ($admin)
                                                    <form action="{{ route('superadmin.organizations.resendInvite', $org) }}" method="POST" style="display:inline">
                                                        @csrf
                                                        <button class="btn btn-secondary btn-sm">Resend Invite</button>
                                                    </form>
                                                @endif --}}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted">No organizations found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>

                            </table>
                        </div>

                        {{-- If you're paginating in controller --}}
                        @if (method_exists($organizations, 'links'))
                            <div class="mt-3">{{ $organizations->links() }}</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

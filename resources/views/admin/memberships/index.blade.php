@extends('layouts.inner_page')

@section('page_title', 'Memberships')

@section('breadcrumb')
<ol class="breadcrumb">
    <li class="breadcrumb-item">
        <a href="{{ route('orgadmin.dashboard', $organization->slug) }}">
            <i class="mdi mdi-home-outline"></i>
        </a>
    </li>
    <li class="breadcrumb-item active" aria-current="page">Memberships</li>
</ol>
@endsection

@section('content')
<section class="content">
    <div class="row">
        <div class="col-12">

            @if(session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
            @endif
            @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                </ul>
            </div>
            @endif

            <div class="box">
                <div class="box-header with-border d-flex justify-content-between align-items-center">
                    <h4 class="box-title">Memberships</h4>
                    <a href="{{ route('orgadmin.memberships.create', $organization->slug) }}"
                        class="btn btn-primary btn-sm"><i class="mdi mdi-plus"></i> Add New</a>
                </div>

                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table text-fade table-bordered table-hover display nowrap margin-top-10 w-p100">
                            <thead>
                                <tr class="text-dark">
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Prefix</th>
                                    <th>Joining Fee</th>
                                    <th>Monthly Fee</th>
                                    <th>Status</th>
                                    <th>Default</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($memberships as $m)
                                <tr>
                                    <td class="text-dark">{{ $m->id }}</td>
                                    <td class="fw-600">{{ $m->name }}</td>
                                    <td>{{ $m->prefix }} </td>
                                    <td>{{ number_format($m->joining_fee, 2) }}</td>
                                    <td>{{ number_format($m->monthly_fee, 2) }}</td>
                                    <td>
                                        @if($m->status === 'active')
                                        <span class="badge bg-success">Active</span>
                                        @else
                                        <span class="badge bg-secondary">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($m->is_default)
                                        <span class="badge bg-primary">Default</span>
                                        @else
                                        -
                                        @endif
                                    </td>
                                    <td>{{ $m->created_at?->format('d M Y') }}</td>
                                    <td class="d-flex gap-1">
                                        <a href="{{ route('orgadmin.memberships.edit', [$organization->slug, $m->id]) }}"
                                            class="btn btn-sm btn-warning">
                                            <i class="mdi mdi-pencil"></i>
                                        </a>

                                        {{-- Delete --}}
                                        <form action="{{ route('orgadmin.memberships.destroy', [$organization->slug, $m->id]) }}"
                                            method="POST" onsubmit="return confirm('Delete this membership?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="mdi mdi-delete"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center">No memberships found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- If you're paginating in controller --}}
                    @if(method_exists($memberships, 'links'))
                    <div class="mt-3">{{ $memberships->links() }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
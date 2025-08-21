@extends('layouts.inner_page')

@section('page_title', 'Membership Categories')

@section('breadcrumb')
<ol class="breadcrumb">
    <li class="breadcrumb-item">
        <a href="{{ route('orgadmin.dashboard', $organization->slug) }}">
            <i class="mdi mdi-home-outline"></i>
        </a>
    </li>
    <li class="breadcrumb-item active" aria-current="page">Membership Categories</li>
</ol>
@endsection

@section('content')
<section class="content">
    <div class="row">
        <div class="col-12">

            @if(session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
            @endif

            <div class="box">
                <div class="box-header with-border d-flex justify-content-between align-items-center">
                    <h4 class="box-title">Membership Categories</h4>
                    <a href="{{ route('orgadmin.membership-categories.create', $organization->slug) }}"
                        class="btn btn-primary btn-sm">
                        <i class="mdi mdi-plus"></i> Add New
                    </a>
                </div>

                <div class="box-body">
                    <div class="table-responsive">
                        <table  class="table text-fade table-bordered table-hover display nowrap margin-top-10 w-p100">
                            <thead>
                                <tr class="text-dark">
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Prefix</th>
                                    <th>Description</th>
                                    <th>Status</th>
                                    <th>Default</th>
                                    <th>Created At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($categories as $cat)
                                <tr>
                                    <td class="text-dark">{{ $cat->id }}</td>
                                    <td class="fw-600">{{ $cat->name }}</td>
                                    <td>{{ $cat->prefix }}</td>
                                    <td>{{ Str::limit($cat->description, 40) }}</td>
                                    <td>
                                        @if($cat->status === 'active')
                                        <span class="badge bg-success">Active</span>
                                        @else
                                        <span class="badge bg-secondary">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($cat->is_default)
                                        <span class="badge bg-primary">Default</span>
                                        @else
                                        -
                                        @endif
                                    </td>
                                    <td>{{ $cat->created_at->format('d M Y') }}</td>
                                    <td>
                                        <a href="{{ route('orgadmin.membership-categories.edit', [$organization->slug, $cat->id]) }}"
                                            class="btn btn-sm btn-warning">
                                            <i class="mdi mdi-pencil"></i>
                                        </a>
                                        <form action="{{ route('orgadmin.membership-categories.destroy', [$organization->slug, $cat->id]) }}"
                                            method="POST"
                                            style="display:inline-block"
                                            onsubmit="return confirm('Are you sure to delete this category?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="mdi mdi-delete"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center">No membership categories found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div> <!-- /.box -->
        </div>
    </div>
</section>
@endsection
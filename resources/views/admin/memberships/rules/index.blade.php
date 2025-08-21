@extends('layouts.inner_page')

@section('page_title', 'Membership Rules')

@section('breadcrumb')
<ol class="breadcrumb">
    <li class="breadcrumb-item">
        <a href="{{ route('orgadmin.dashboard', $organization->slug) }}"><i class="mdi mdi-home-outline"></i></a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('orgadmin.memberships.index', $organization->slug) }}">Memberships</a>
    </li>
    <li class="breadcrumb-item active" aria-current="page">Rules: {{ $membership->name }}</li>
</ol>
@endsection

@section('content')
<section class="content">
    <div class="row">
        <div class="col-12">

            @if(session('status')) <div class="alert alert-success">{{ session('status') }}</div> @endif

            <div class="box">
                <div class="box-header with-border d-flex justify-content-between align-items-center">
                    <h4 class="box-title">Rules for: <span class="fw-600">{{ $membership->name }}</span></h4>
                    <a href="{{ route('orgadmin.memberships.rules.create', [$organization->slug, $membership->id]) }}"
                        class="btn btn-primary btn-sm">
                        <i class="mdi mdi-plus"></i> Add Rule
                    </a>
                </div>

                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr class="text-dark">
                                    <th>#</th>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th>Created</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($rules as $rule)
                                <tr>
                                    <td class="text-dark">{{ $rule->id }}</td>
                                    <td class="fw-600">{{ $rule->title }}</td>
                                    <td>{{ Str::limit($rule->description, 80) }}</td>
                                    <td>{{ $rule->created_at?->format('d M Y') }}</td>
                                    <td class="text-end">
                                        <a href="{{ route('orgadmin.rules.edit', [$organization->slug, $rule->id]) }}"
                                            class="btn btn-sm btn-warning"><i class="mdi mdi-pencil"></i></a>
                                        <form action="{{ route('orgadmin.rules.destroy', [$organization->slug, $rule->id]) }}"
                                            method="POST" style="display:inline-block"
                                            onsubmit="return confirm('Delete this rule?');">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-danger"><i class="mdi mdi-delete"></i></button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">No rules added yet.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div><!-- /.box -->
        </div>
    </div>
</section>
@endsection
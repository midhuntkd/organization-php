@extends('layouts.inner_page')

@section('page_title', 'Membership Benefits')

@section('breadcrumb')
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
        <li class="breadcrumb-item active" aria-current="page">Membership Benefits</li>
    </ol>
@endsection

@section('content')
    <section class="content">
        <div class="box">
            <div class="box-header with-border d-flex justify-content-between">
                <h4 class="box-title">Membership Benefits</h4>
                <a href="{{ route('orgadmin.membership_benefits.create', $organization->slug) }}"
                    class="btn btn-primary btn-sm">+ Add Benefit</a>
            </div>

            <div class="box-body">
                @if (session('status'))
                    <div class="alert alert-success">{{ session('status') }}</div>
                @endif

                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Created</th>
                                <th width="160">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($benefits as $benefit)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td class="fw-600">{{ $benefit->title }}</td>
                                    <td>{{ Str::limit($benefit->description, 80) }}</td>
                                    <td>{{ $benefit->created_at?->format('d M Y') }}</td>
                                    <td>
                                        <a href="{{ route('orgadmin.membership_benefits.edit', [$organization->slug, $benefit->id]) }}"
                                            class="btn btn-sm btn-warning">
                                            <i class="mdi mdi-pencil"></i> Edit
                                        </a>
                                        <form
                                            action="{{ route('orgadmin.membership_benefits.destroy', [$organization->slug, $benefit->id]) }}"
                                            method="POST" style="display:inline-block"
                                            onsubmit="return confirm('Delete this benefit?');">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-danger"><i class="mdi mdi-delete"></i>
                                                Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">No benefits added yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection

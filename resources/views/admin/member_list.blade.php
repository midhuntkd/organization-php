@extends('layouts.inner_page_table')

@section('page_title', 'Member List')

@section('breadcrumb')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
    <li class="breadcrumb-item" aria-current="page">Memeber List</li>
    <!-- <li class="breadcrumb-item active" aria-current="page">Data Tables</li> -->
</ol>
@endsection

@section('content')
<section class="content">
    <div class="row">
        <div class="col-12">

            <div class="box">
                <div class="box-header with-border">
                    <h4 class="box-title">Member List</h4>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="table-responsive">
                        <table id="example" class="table text-fade table-bordered table-hover display nowrap margin-top-10 w-p100">
                            <thead>
                                <tr class="text-dark">
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Country of Residence</th>
                                    <th>Verification Info</th>
                                    <th>Image</th>
                                    <th>Approved</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($members as $member)
                                <tr>
                                    <td class="text-dark">{{ $member->name }}</td>
                                    <td>{{ $member->email }}</td>
                                    <td>{{ $member->phone }}</td>
                                    <td>{{ $member->country_of_residence }}</td>
                                    <td>{{ $member->verification_type }} <br /> {{ $member->verification_id_number }}</td>
                                    <td>
                                        <a class="image-popup-no-margins" href="{{ $member->verification_image_url }}" title="{{ $member->verification_id_number }}">
                                            <img src="{{ $member->verification_image_url }}" width="150" />
                                        </a>
                                    </td>
                                    <td>@if($member->approved)
                                        <span class="badge bg-success">Approved</span>
                                        @elseif($member->rejected)
                                        <span class="badge bg-danger">Rejected</span>
                                        @else
                                        <span class="badge bg-secondary">Pending</span>
                                        @endif
                                    </td>
                                    <td>

                                        @if(!$member->approved && !$member->rejected)
                                        {{-- Approve --}}

                                        <a href="{{ route('orgadmin.member.approve', ['organization' => $organization->slug, 'user' => $member->id]) }}" class="btn btn-primary btn-sm">Approve</a>
                                        {{-- Reject: opens modal --}}
                                        <button type="button"
                                            class="btn btn-danger btn-sm"
                                            data-bs-toggle="modal"
                                            data-bs-target="#rejectModal"
                                            data-user-id="{{ $member->id }}"
                                            data-user-name="{{ $member->name }}">
                                            Reject
                                        </button>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
</section>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" id="rejectForm">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="rejectModalLabel">Reject Member</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-2">Rejecting: <strong id="rejectUserName"></strong></p>
                    <div class="mb-3">
                        <label class="form-label">Reason Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" required maxlength="255">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="4" maxlength="5000" placeholder="Optional details..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reject</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('rejectModal');
        const form = document.getElementById('rejectForm');
        const nameEl = document.getElementById('rejectUserName');

        modal.addEventListener('show.bs.modal', function(event) {
            //event.preventDefault();
            const button = event.relatedTarget;
            const userId = button.getAttribute('data-user-id');
            const userName = button.getAttribute('data-user-name');

            nameEl.textContent = userName;

            // Build action URL: /{slug}/admin/members/{user}/reject
            //const actionTemplate = @json(route('orgadmin.member.reject', [$organization->slug, '__USER__']));
            const actionTemplate = "{{ route('orgadmin.member.reject', ['organization' => $organization->slug, 'user' => '__USER__']) }}";
            form.action = actionTemplate.replace('__USER__', userId);
        });
    });
</script>
@endpush
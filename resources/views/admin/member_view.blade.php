@extends('layouts.inner_page')

@section('page_title', 'Member List')

@section('breadcrumb')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
    <li class="breadcrumb-item" aria-current="page">Memeber List</li>
    <li class="breadcrumb-item active" aria-current="page">Member Details</li>
</ol>
@endsection

@section('content')
<section class="content">
    <div class="row">
        <div class="col-lg-12 col-12">
            <!-- Basic Forms -->
            <div class="box">
                <div class="box-header with-border">
                    <h4 class="box-title">Member Details</h4>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="row">
                        <div class="col-12">
                            @if (session('status'))
                            <div class="alert alert-success">{{ session('status') }}</div>
                            @endif
                            @if (session('error'))
                            <div class="alert alert-danger" id="ajaxError">
                                {{ session('error') }}
                            </div>
                            @endif
                        </div>
                        <div class="col-12">


                            <div class="form-group row">
                                <label for="example-text-input" class="col-sm-2 col-form-label">Name</label>
                                <div class="col-sm-10">
                                    <input class="form-control" type="text" value="{{ $user->name }}" id="example-text-input">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="example-search-input" class="col-sm-2 col-form-label">Email</label>
                                <div class="col-sm-10">
                                    <input class="form-control" type="email" value="{{ $user->email }}" id="example-search-input">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="example-url-input" class="col-sm-2 col-form-label">Country of Residence</label>
                                <div class="col-sm-10">
                                    <input class="form-control" type="url" value="{{ $user->country_of_residence }}" id="example-url-input">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="example-email-input" class="col-sm-2 col-form-label">Phone</label>
                                <div class="col-sm-10">
                                    <input class="form-control" type="text" value="{{ $user->phone }}" id="example-email-input">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="example-tel-input" class="col-sm-2 col-form-label">verification Type</label>
                                <div class="col-sm-10">
                                    <input class="form-control" type="tel" value="{{ $user->verification_type }}" id="example-tel-input">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="example-tel-input" class="col-sm-2 col-form-label">Verification ID Number</label>
                                <div class="col-sm-10">
                                    <input class="form-control" type="tel" value="{{ $user->verification_id_number }}" id="example-tel-input">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="example-password-input" class="col-sm-2 col-form-label">ID Front side</label>
                                <div class="col-sm-10">
                                    <a class="image-popup-no-margins" href="{{ $user->id_card_front_url }}">
                                        <img src="{{ $user->id_card_front_url }}" width="150" />
                                    </a>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="example-password-input" class="col-sm-2 col-form-label">ID Back side</label>
                                <div class="col-sm-10">
                                    <a class="image-popup-no-margins" href="{{ $user->id_card_back_url }}">
                                        <img src="{{ $user->id_card_back_url }}" width="150" />
                                    </a>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="example-password-input" class="col-sm-2 col-form-label"></label>
                                <div class="col-sm-10">
                                    @if(!$user->approved && !$user->rejected)
                                    {{-- Approve --}}

                                    <form action="{{ route('orgadmin.member.approve', [$organization->slug, $user->id]) }}" method="POST" style="display:inline;" id="approveForm">
                                        @csrf
                                        <button type="submit" class="btn btn-primary btn-sm approve-btn" data-id="{{ $user->id }}">Approve</button>
                                    </form>

                                    {{-- Reject: opens modal --}}
                                    <button type="button"
                                        class="btn btn-danger btn-sm"
                                        data-bs-toggle="modal"
                                        data-bs-target="#rejectModal"
                                        data-user-id="{{ $user->id }}"
                                        data-user-name="{{ $user->name }}">
                                        Reject
                                    </button>
                                    @endif

                                    <button type="button"
                                        class="btn btn-danger btn-sm"
                                        data-bs-toggle="modal"
                                        data-bs-target="#updateModal"
                                        data-user-id="{{ $user->id }}"
                                        data-user-name="{{ $user->name }}">
                                        Update Details
                                    </button>
                                </div>
                            </div>

                            @if(auth()->user()->hasAnyRole('organization-admin', 'super-admin'))
                            <hr>
                            <h5 class="mb-3" id="permissions">Access Permissions</h5>
                            <form method="POST" action="{{ route('orgadmin.member.permissions', [$organization->slug, $user->id]) }}">
                                @csrf
                                <div class="row">
                                    @foreach($permissionOptions as $option)
                                    <div class="col-md-4 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="permissions[]"
                                                value="{{ $option['key'] }}"
                                                id="perm_{{ $option['key'] }}"
                                                @checked(in_array($option['key'], $userPermissions, true))>
                                            <label class="form-check-label" for="perm_{{ $option['key'] }}">
                                                {{ $option['label'] }}
                                            </label>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                <button type="submit" class="btn btn-primary btn-sm mt-2">Save Permissions</button>
                            </form>
                            @endif

                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->
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

<div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" id="updateForm" action="{{ route('orgadmin.member.update', [$organization->slug, $user->id]) }} " enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="rejectModalLabel">Update Member Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" value="{{ $user->name }}" class="form-control" required maxlength="255">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Country Of Residence</label>
                        <select name="country_of_residence"
                            id="countrySelect"
                            class="form-select @error('country_of_residence') is-invalid @enderror"
                            required>
                            <option value="">-- Select --</option>
                            <option value="UAE" @selected(old('country_of_residence')==='UAE' ) @selected($user->country_of_residence == 'UAE')>UAE</option>
                            <option value="India" @selected(old('country_of_residence')==='India' ) @selected($user->country_of_residence == 'India')>India</option>
                        </select>
                        @error('country_of_residence') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">phone</label>
                        <input type="text" name="phone" value="{{ $user->phone }}" class="form-control" required maxlength="255">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Verification Type</label>
                        <select name="verification_type"
                            id="verification_type"
                            class="form-select @error('verification_type') is-invalid @enderror"
                            required>
                            <option value="">-- Select --</option>
                            <option value="aadhaar" @selected(old('verification_type')==='aadhaar' ) @selected($user->verification_type == 'aadhaar')>Aadhaar</option>
                            <option value="emirates_id" @selected(old('verification_type')==='emirates_id' ) @selected($user->verification_type == 'emirates_id')>Emirates ID</option>
                        </select>
                        @error('verification_type') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Verification ID Number</label>
                        <input type="text" name="verification_id_number" value="{{ $user->verification_id_number }}" class="form-control" required maxlength="255">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Profile Photo</label>
                        <input type="file" name="user_image" class="form-control @error('user_image') is-invalid @enderror" accept="image/*">
                        @error('user_image')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        @if ($user->user_image)
                            <img src="{{ $user->user_image_url }}" alt="User photo" class="mt-2 rounded" width="120">
                        @endif
                    </div>
                    <div class="mb-3">
                        <label class="form-label">ID Front</label>
                        <input type="file" name="id_card_front" class="form-control @error('id_card_front') is-invalid @enderror" accept="image/*">
                        @error('id_card_front')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        @if ($user->id_card_front_url)
                            <img src="{{ $user->id_card_front_url }}" alt="Front ID" class="mt-2 rounded" width="150">
                        @endif
                    </div>
                    <div class="mb-3">
                        <label class="form-label">ID Back</label>
                        <input type="file" name="id_card_back" class="form-control @error('id_card_back') is-invalid @enderror" accept="image/*">
                        @error('id_card_back')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        @if ($user->id_card_back_url ?? false)
                            <img src="{{ $user->id_card_back_url }}" alt="Back ID" class="mt-2 rounded" width="150">
                        @endif
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Update</button>
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
        const approveForm = document.getElementById('approveForm');

        const updateForm = document.getElementById('updateForm');
        const updateModal = document.getElementById('updateModal');

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

        updateModal.addEventListener('show.bs.modal', function(event) {
            //event.preventDefault();
            const button = event.relatedTarget;
            //const userId = button.getAttribute('data-user-id');
            //const actionTemplate = "{{ route('orgadmin.member.update', ['organization' => $organization->slug, 'user' => '__USER__']) }}";
            //form.action = actionTemplate.replace('__USER__', userId);
        });

        // APPROVE BUTTON HANDLER
        $(document).on('click', '.approve-btn', function(e) {
            e.preventDefault();
            let userId = $(this).data('id');

            // Build approve route
            let ajaxURL = "{{ route('orgadmin.member.approve', ['organization' => $organization->slug, 'user' => '__USER__']) }}".replace('__USER__', userId);

            if (!confirm('Are you sure you want to approve this member?')) return;

            approveForm.submit()

        });
    });
</script>
@endpush

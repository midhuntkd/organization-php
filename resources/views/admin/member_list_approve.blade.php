@extends('layouts.inner_page')

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
                    <form method="GET" class="mb-3">
                        <div class="row g-2 align-items-end">
                            <div class="col-md-3">
                                <label class="form-label">Name</label>
                                <input type="text" name="name" class="form-control" value="{{ request('name') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Email</label>
                                <input type="text" name="email" class="form-control" value="{{ request('email') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Phone</label>
                                <input type="text" name="phone" class="form-control" value="{{ request('phone') }}">
                            </div>
                            <div class="col-md-3 d-flex gap-2">
                                <button type="submit" class="btn btn-primary">Filter</button>
                                <a href="{{ url()->current() }}" class="btn btn-outline-secondary">Clear</a>
                            </div>
                        </div>
                    </form>
                    <div class="table-responsive">
                        <table class="table text-fade table-bordered table-hover display nowrap margin-top-10 w-p100">
                            <thead>
                                <tr class="text-dark">
                                    <th>Sl No</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Country </th>
                                    <th>ID Type</th>
                                    <th>ID Number</th>
                                    <th>Member ID</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($members as $member)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td class="text-dark">{{ $member->name }}</td>
                                    <td>{{ $member->email }}</td>
                                    <td>{{ $member->phone }}</td>
                                    <td>{{ $member->country_of_residence }}</td>
                                    <td>{{ $member->verification_type }} </td>
                                    <td>{{ $member->verification_id_number }}</td>
                                    <td>{{ $member->membership_code }}</td>
                                    <td>
                                        <a href="{{ route('orgadmin.member.view', ['organization' => $organization->slug, 'user' => $member->id]) }}"
                                            class="text-primary" title="View">
                                            <span class="mdi mdi-eye"></span>
                                        </a>
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


@endsection

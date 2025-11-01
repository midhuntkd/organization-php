@extends('layouts.inner_page')
@section('page_title', 'Organization Details')
@section('breadcrumb')
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('superadmin.dashboard') }}">
                <i class="mdi mdi-home-outline"></i>
            </a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">Organization Details</li>
    </ol>
@endsection

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-12">


                <div class="box">
                    <div class="box-header with-border d-flex justify-content-between align-items-center">
                        <h4 class="box-title">{{ $organization->name }}</h4>
                        <a href="{{ route('superadmin.organizations.create') }}" class="btn btn-primary btn-sm"><i
                                class="mdi mdi-plus"></i>New Organization</a>
                    </div>

                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table text-fade table-bordered table-hover display nowrap margin-top-10 w-p100">
                                <tbody>
                                    <tr>
                                        <th colspan="2">
                                            <h3>Organization Details</h3>
                                        </th>
                                    </tr>
                                    <tr>
                                        <td>Slug:</td>
                                        <td>{{ $organization->slug }}</td>
                                    </tr>
                                    <tr>
                                        <td>Prefix:</td>
                                        <td>{{ $organization->org_prefix }}</td>
                                    </tr>
                                    @if ($organization->logo_url)
                                        <tr>
                                            <td>Logo:</td>
                                            <td><img src="{{ $organization->logo_url }}" width="120"></td>
                                        </tr>
                                    @endif
                                    @if ($adminUser)
                                        <tr>
                                            <th colspan="2">
                                                <h3>Admin User Details</h3>
                                            </th>
                                        </tr>
                                        <tr>
                                            <td>Name:</td>
                                            <td>{{ $adminUser->name }}</td>
                                        </tr>
                                        <tr>
                                            <td>Email:</td>
                                            <td>{{ $adminUser->email }}</td>
                                        </tr>
                                        <tr>
                                            <td>Phone:</td>
                                            <td>{{ $adminUser->phone }}</td>
                                        </tr>
                                    @endif

                                    <tr>
                                        <th colspan="2">
                                            <form
                                                action="{{ route('superadmin.organizations.resendInvite', $organization) }}"
                                                method="POST">
                                                @csrf
                                                <button class="btn btn-secondary btn-sm">Resend Invite</button>
                                            </form>

                                            <a href="{{ route('superadmin.organizations.index') }}"
                                                class="btn btn-light mt-3">← Back to
                                                list</a>
                                        </th>
                                    </tr>
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@extends('layouts.inner_page')

@section('page_title', 'Add Membership')

@section('breadcrumb')
<ol class="breadcrumb">
    <li class="breadcrumb-item">
        <a href="{{ route('orgadmin.dashboard', $organization->slug) }}">
            <i class="mdi mdi-home-outline"></i>
        </a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('orgadmin.memberships.index', $organization->slug) }}">Memberships</a>
    </li>
    <li class="breadcrumb-item active" aria-current="page">Add</li>
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
                <div class="box-header with-border">
                    <h4 class="box-title">Add Membership</h4>
                </div>
                <div class="box-body">
                    <form id="membershipForm" method="POST"
                        action="{{ route('orgadmin.memberships.store', $organization->slug) }}">
                        @csrf
                        @include('admin.memberships._form', ['submitText' => 'Create'])
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
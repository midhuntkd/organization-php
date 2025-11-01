@extends('layouts.inner_page')

@section('page_title', 'Add Rule')

@section('breadcrumb')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('orgadmin.dashboard', $organization->slug) }}"><i class="mdi mdi-home-outline"></i></a></li>
    <li class="breadcrumb-item"><a href="{{ route('orgadmin.membership_rules.index', $organization->slug) }}">Rules</a></li>
    <li class="breadcrumb-item active">Add</li>
</ol>
@endsection

@section('content')
<section class="content">
    <div class="row">
        <div class="col-12">
            <div class="box">
                <div class="box-header with-border">
                    <h4 class="box-title">Add Rule </h4>
                </div>
                <div class="box-body">
                    <form action="{{ route('orgadmin.membership_rules.store', $organization->slug) }}" method="POST">
                        @csrf
                        @include('admin.memberships.rules._form', ['submitText' => 'Create'])
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
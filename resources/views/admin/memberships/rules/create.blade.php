@extends('layouts.inner_page')

@section('page_title', 'Add Rule')

@section('breadcrumb')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('orgadmin.dashboard', $organization->slug) }}"><i class="mdi mdi-home-outline"></i></a></li>
    <li class="breadcrumb-item"><a href="{{ route('orgadmin.memberships.index', $organization->slug) }}">Memberships</a></li>
    <li class="breadcrumb-item"><a href="{{ route('orgadmin.memberships.rules.index', [$organization->slug, $membership->id]) }}">Rules</a></li>
    <li class="breadcrumb-item active">Add</li>
</ol>
@endsection

@section('content')
<section class="content">
    <div class="row">
        <div class="col-12">
            <div class="box">
                <div class="box-header with-border">
                    <h4 class="box-title">Add Rule — {{ $membership->name }}</h4>
                </div>
                <div class="box-body">
                    <form method="POST" action="{{ route('orgadmin.memberships.rules.store', [$organization->slug, $membership->id]) }}">
                        @csrf
                        @include('admin.memberships.rules._form', ['submitText' => 'Create'])
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
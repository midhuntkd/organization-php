@extends('layouts.inner_page')

@section('page_title', 'Edit Benefit')

@section('breadcrumb')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('orgadmin.dashboard', $organization->slug) }}"><i class="mdi mdi-home-outline"></i></a></li>
    <li class="breadcrumb-item"><a href="{{ route('orgadmin.memberships.index', $organization->slug) }}">Memberships</a></li>
    <li class="breadcrumb-item"><a href="{{ route('orgadmin.memberships.benefits.index', [$organization->slug, $membership->id]) }}">Benefits</a></li>
    <li class="breadcrumb-item active">Edit</li>
</ol>
@endsection

@section('content')
<section class="content">
    <div class="row">
        <div class="col-12">
            <div class="box">
                <div class="box-header with-border">
                    <h4 class="box-title">Edit Benefit — {{ $membership->name }}</h4>
                </div>
                <div class="box-body">
                    <form method="POST" action="{{ route('orgadmin.benefits.update', [$organization->slug, $benefit->id]) }}">
                        @csrf @method('PUT')
                        @include('admin.memberships.benefits._form', ['submitText' => 'Update', 'benefit' => $benefit])
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@extends('layouts.inner_page')

@section('page_title', 'Edit Membership Category')

@section('breadcrumb')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('orgadmin.dashboard', $organization->slug) }}"><i class="mdi mdi-home-outline"></i></a></li>
    <li class="breadcrumb-item"><a href="{{ route('orgadmin.membership-categories.index', $organization->slug) }}">Membership Categories</a></li>
    <li class="breadcrumb-item active" aria-current="page">Edit</li>
</ol>
@endsection

@section('content')
<section class="content">
    <div class="row">
        <div class="col-12">
            @if(session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
            @endif
            @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                </ul>
            </div>
            @endif

            <div class="box">
                <div class="box-header with-border">
                    <h4 class="box-title">Edit: {{ $membership_category->name }}</h4>
                </div>
                <div class="box-body">
                    <form method="POST" action="{{ route('orgadmin.membership-categories.update', [$organization->slug, $membership_category->id]) }}">
                        @csrf
                        @method('PUT')
                        @include('admin.membership_categories._form', [
                        'submitText' => 'Update',
                        'membership_category' => $membership_category
                        ])
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
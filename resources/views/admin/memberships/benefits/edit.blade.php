@extends('layouts.inner_page')

@section('page_title', 'Edit Benefit')

@section('breadcrumb')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('orgadmin.dashboard', $organization->slug) }}"><i class="mdi mdi-home-outline"></i></a></li>
    <li class="breadcrumb-item"><a href="{{ route('orgadmin.membership_benefits.index', [$organization->slug]) }}">Benefits</a></li>
    <li class="breadcrumb-item active">Edit</li>
</ol>
@endsection

@section('content')
<section class="content">
    <div class="row">
        <div class="col-12">
            <div class="box">
                <div class="box-header with-border">
                    <h4 class="box-title">Edit Benefit  </h4>
                </div>
                <div class="box-body">
                    <form action="{{ route('orgadmin.membership_benefits.update', [$organization->slug, $benefitInfo->id]) }}" method="POST">
                        @csrf @method('PUT')
                        @include('admin.memberships.benefits._form', ['submitText' => 'Update', 'benefit' => $benefitInfo])
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
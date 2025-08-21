@extends('layouts.inner_page')

@section('page_title', 'Member Dashboard')

@section('breadcrumb')
<ol class="breadcrumb">
    <li class="breadcrumb-item">
        <a href="#"><i class="mdi mdi-home-outline"></i></a>
    </li>
    <li class="breadcrumb-item active">Dashboard</li>
</ol>
@endsection

@section('content')
<section class="content">
    <div class="row">

        @if(session('status'))
        <div class="col-12">
            <div class="alert alert-success">{{ session('status') }}</div>
        </div>
        @endif

        {{-- Profile card --}}
        <div class="col-lg-6 col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h4 class="box-title">Welcome, {{ $user->name }}</h4>
                </div>
                <div class="box-body">
                    <p class="mb-1"><strong>Email:</strong> {{ $user->email }}</p>
                    <p class="mb-1"><strong>Organization:</strong> {{ $organization->name }}</p>
                    <p class="mb-1"><strong>Membership Code:</strong> <code>{{ $user->membership_code ?? '—' }}</code></p>

                    @if($membership)
                    <p class="mb-1"><strong>Plan:</strong> {{ $membership->name }}</p>
                    @if($category)
                    <p class="mb-0"><strong>Category:</strong> {{ $category->name }} ({{ $category->prefix }})</p>
                    @endif
                    @else
                    <div class="alert alert-warning mt-2">
                        No membership assigned yet. Please contact support.
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Rules --}}
        <div class="col-lg-6 col-md-12">
            <div class="box">
                <div class="box-header with-border d-flex justify-content-between align-items-center">
                    <h4 class="box-title">Membership Rules</h4>
                </div>
                <div class="box-body">
                    @if($rules->isEmpty())
                    <p class="text-muted">No rules available for your plan.</p>
                    @else
                    <ul class="list-group">
                        @foreach($rules as $r)
                        <li class="list-group-item">
                            <strong>{{ $r->title }}</strong>
                            @if($r->description)
                            <div class="text-fade mt-1">{{ $r->description }}</div>
                            @endif
                        </li>
                        @endforeach
                    </ul>
                    @endif
                </div>
            </div>
        </div>

        {{-- Benefits --}}
        <div class="col-lg-12">
            <div class="box">
                <div class="box-header with-border">
                    <h4 class="box-title">Membership Benefits</h4>
                </div>
                <div class="box-body">
                    @if($benefits->isEmpty())
                    <p class="text-muted">No benefits available for your plan.</p>
                    @else
                    <div class="row">
                        @foreach($benefits as $b)
                        <div class="col-md-4">
                            <div class="border rounded p-3 mb-3 h-100">
                                <h5 class="mb-2">{{ $b->title }}</h5>
                                @if($b->description)
                                <p class="mb-0 text-fade">{{ $b->description }}</p>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
</section>
@endsection
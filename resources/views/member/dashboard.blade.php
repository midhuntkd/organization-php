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

        <div class="row">
            <div class="col-lg-9 col-md-9 col-sm-7 col-12">
                <div class="row">
                    {{-- Profile card --}}
                    <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                        <div class="box">
                            <div class="box-header with-border">
                                <h4 class="box-title">Welcome, {{ $user->name }}</h4>
                            </div>
                            <div class="box-body">
                                <div class="row">
                                    <div class="col-5">
                                        <img src="{{ asset('hyper/images/user_icon.png') }}" alt="User Avatar" class="img-thumbnail mb-3" style="max-width: 100%;">
                                    </div>
                                    <div class="col-7">
                                        <p class="mb-1"> {{ $user->name }}</p>
                                        <p class="mb-1"> {{ $user->email }}</p>
                                        <p class="mb-1"> {{ $user->phone }}</p>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    {{-- Rules --}}
                    <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                        <div class="box">
                            <div class="box-header with-border d-flex justify-content-between align-items-center">
                                <h4 class="box-title">Membership Details</h4>
                            </div>
                            <div class="box-body">
                                <h3 class="mb-1"> {{ $organization->name }}</h3><br />

                                <h3 class="mb-1">{{ $user->membership_code ?? '—' }}</h3>
                                <br />
                                @if($membership)
                                <h3 class="mb-1">{{ $membership->name }}</h3>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Benefits --}}
                    <div class="col-lg-4 col-md-4 col-sm-4 col-12">
                        <div class="box">
                            <div class="box-header with-border d-flex justify-content-between align-items-center">
                                <h4 class="box-title">My Wallet</h4>
                            </div>
                            <div class="box-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <p class="mb-1"><strong> 255.22</strong></p>
                                    <button class="btn btn-primary btn-sm" id="topup">TOPUP MY WALLET</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8 col-sm-8 col-12">
                        <div class="box">
                            <div class="box-header with-border">
                                <h4 class="box-title">Membership Benefits</h4>
                            </div>
                            <div class="box-body">
                                @if($benefits->isEmpty())
                                <!-- <p class="text-muted">No benefits available for your plan.</p> -->
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
            </div>
            <div class="col-lg-3 col-md-3 col-sm-5 col-12">
                <div class="box">
                    <div class="box-header with-border d-flex justify-content-between align-items-center">
                        <h4 class="box-title">Social Media</h4>
                    </div>
                    <div class="box-body">
                        <p class="mb-1"><strong>Personal Details :</strong> </p>
                        <br /><br /><br /><br />

                        <p class="mb-1"><strong>Norka ID :</strong> </p>
                        <br /><br /><br /><br />

                        <p class="mb-1"><strong>Insurance Details :</strong> </p>
                        <br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
@endsection
@push('scripts')
<script>
    const topup = document.getElementById('topup');
    topup.addEventListener('click', async function() {
        alert('Topup feature coming soon!');
    });
</script>
@endpush
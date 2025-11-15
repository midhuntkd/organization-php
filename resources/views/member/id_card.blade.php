@extends('layouts.inner_page')

@section('page_title', 'Member ID Card')

@section('breadcrumb')
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard') }}"><i class="mdi mdi-home-outline"></i></a>
        </li>
        <li class="breadcrumb-item active">ID Card</li>
    </ol>
@endsection

@php
$headerLogoUrl = isset($organization) ? $organization->header_logo_url : asset('hyper/images/l-logo-ico.png');
@endphp

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Member ID Card</h5>
                    <div class="d-flex gap-2">
                        <button type="button" id="btnPrintIdCard" class="btn btn-secondary">
                            <i class="mdi mdi-printer me-1"></i> Print
                        </button>
                        {{-- <a href="{{ route('member.id-card.download') }}" class="btn btn-primary">
                            <i class="mdi mdi-download me-1"></i> Download PDF
                        </a> --}}
                    </div>
                </div>
                <div class="card-body">
                    <div id="idcard-print" class="d-flex flex-wrap gap-4 justify-content-center">
                        <!-- Front Side -->
                        <div class="idc idc-front">
                            <div class="idc-top text-center">
                                <div class="idc-img-box"><img src="{{ $headerLogoUrl }}" alt="Logo" class="idc-logo-small"></div>
                                <div class="idc-img-box"><img src="{{ $organization->logo_url }}" alt="Logo" class="idc-logo-small-2"></div>
                                
                                
                                {{-- <div class="idc-org-name mt-2">{{ $organization->name }}</div> --}}
                                <div class="idc-badge">Membership ID Card</div>
                            </div>

                            <div class="idc-photo">
                                <img src="{{ $user->user_image_url }}" alt="Member Photo">
                            </div>

                            <div class="idc-bottom">
                                <div class="idc-member-name">{{ strtoupper($user->name) }}</div>
                                <div class="idc-info-grid">
                                    <div>Membership ID</div><div>: {{ $user->membership_code ?? '-' }}</div>
                                    <div>Valid Thru</div><div>: {{ $details?->expiry_date?->format('d-M-Y') ?? '-' }}</div>
                                    <div>Country of Residence</div><div>: {{ $user->country_of_residence ?: '-' }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Back Side -->
                        <div class="idc idc-back">
                            <div class="idc-back-top text-right">
                                <div class="idc-img-box"><img src="{{ $headerLogoUrl }}" alt="Logo" class="idc-logo-small"></div>
                                <div class="idc-img-box"><img src="{{ $organization->logo_url }}" alt="Logo" class="idc-logo-small-2"></div>
                                {{-- <div class="idc-org-name-dark mt-2">{{ strtoupper($organization->name) }}</div> --}}
                            </div>
                            <div class="idc-back-content">
                                <div class="idc-back-table">
                                    <div class="lbl"><strong>ADDRESS</strong></div>
                                    <div class="val">
                                        {{ $details?->address_line1 }}<br>
                                        @if($details?->address_line2)
                                            {{ $details->address_line2 }}<br>
                                        @endif
                                        {{ $details?->city }} {{ $details?->zipcode }}
                                    </div>
                                    <div class="lbl"><strong>B/G</strong></div>
                                    <div class="val">{{ $details?->blood_group ?? 'N/A' }}</div>
                                    <div class="lbl"><strong>Tel</strong></div>
                                    <div class="val">{{ $user->phone ?: '-' }}</div>
                                </div>

                                <ul class="idc-notes">
                                    <li>This card is intended only to prove the identity of the card holder as a member.</li>
                                    <li>This card is not transferable.</li>
                                </ul>
                            </div>
                            <div class="idc-back-footer">
                                @php($siteUrl = "https://member.org.in/")
                                <div class="d-flex align-items-center gap-2">
                                    <span class="mdi mdi-web"></span>
                                    <span>{{ $siteUrl }}</span>
                                </div>
                                @if(config('mail.from.address'))
                                <div class="d-flex align-items-center gap-2">
                                    <span class="mdi mdi-email-outline"></span>
                                    <span>{{ config('mail.from.address') }}</span>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Shared card shell */
    .idc { width: 320px; height: 520px; border-radius: 10px; overflow: hidden; position: relative; box-shadow: 0 8px 20px rgba(0,0,0,.15); }

    /* Front */
    .idc-front { background: linear-gradient(160deg,#0F2334 0%, #0B1B2A 40%, #0F2334 100%); color: #fff; display:flex; flex-direction:column; }
    .idc-top { padding: 22px 18px 10px; position: relative; }
    .idc-logo { width: 64px; height: 64px; object-fit: contain; }
    .idc-org-name { color: #ffffff; font-weight: 700; letter-spacing: .4px; font-size: 14px; }
    .idc-badge { display:inline-block; margin-top:10px; background:#21c1d6; color:#073d49; font-weight:700; padding:6px 14px; border-radius: 16px; font-size: 12px; }
    .idc-photo { margin: 18px auto 12px; width: 180px; height: 190px; background:#1cc3d1; border-radius: 14px; display:flex; align-items:center; justify-content:center; overflow:hidden; }
    .idc-photo img { width: 100%; height: 100%; object-fit: cover; }
    .idc-bottom { background:#fff; color:#111; margin-top:auto; padding: 16px 18px 18px; }
    .idc-member-name { font-weight: 800; letter-spacing:.6px; font-size: 18px; margin-bottom: 10px; }
    .idc-info-grid { display:grid; grid-template-columns: 1fr 1fr; gap:6px 12px; font-size: 12px; }

    /* Back */
    .idc-back { background: #f5f7fa; color:#0d2231; display:grid; grid-template-rows: auto 1fr auto; }
    .idc-back::before { content:''; position:absolute; top:-30px; left:0px; width:100%; height:160px; background: linear-gradient(135deg,#102A3B,#0C1F2F); border-bottom-right-radius:0px; opacity:.85; }
    .idc-back::after { content:''; position:absolute; bottom:-30px; right:0px; width:100%; height:160px; background: linear-gradient(135deg,#0C1F2F,#21c1d6); border-top-left-radius:0px; opacity:.9; }
    .idc-back-top { padding: 22px 18px 6px; position: relative; z-index:1; text-align: center; }
    .idc-logo-small { max-width: 80%; max-height: 30px; object-fit: contain; }
    .idc-logo-small-2 { max-width: 80%; max-height: 60px; object-fit: contain; }
    .idc-org-name-dark { font-weight:800; font-size: 13px; letter-spacing:.4px; }
    .idc-back-content { padding: 10px 18px 0; font-size: 12px; z-index:1; align-self: center; }
    .idc-back-table { display:grid; grid-template-columns:max-content 1fr; gap:8px 10px; align-items:start; }
    .idc-back-table .lbl { white-space:nowrap; }
    .idc-back-table .val { line-height:1.3; }
    .idc-notes { margin:12px 0 0; padding-left: 18px; font-size: 11px; }
    .idc-back-footer { padding: 10px 18px 16px; font-size: 11px; z-index:1; color:#2e5163; }
    .idc-img-box{ display: flex; align-items: center; justify-content: center; width: 100%; height: auto;  }

    /* Print only the ID cards */
    @media print {
        @page { size: A4 portrait; margin: 10mm; }
        body * { visibility: hidden !important; }
        #idcard-print, #idcard-print * { visibility: visible !important; }
        #idcard-print { position: static !important; width: auto !important; margin: 0 !important; display: grid !important; grid-template-columns: 1fr 1fr; gap: 8mm; }
        .idc { box-shadow: none !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; page-break-inside: avoid; width: 80mm !important; height: 130mm !important; }
    }
</style>
@endpush

@push('scripts')
<script>
    document.getElementById('btnPrintIdCard')?.addEventListener('click', function () {
        window.print();
    });
</script>
@endpush

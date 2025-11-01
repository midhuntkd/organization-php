<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Member ID Card</title>
    @php
        $logoPath = $organization->logo ? public_path('storage/'.$organization->logo) : null;
        $photoPath = $user->user_image ? public_path('storage/'.$user->user_image) : null;
    @endphp
    <style>
        body { font-family: DejaVu Sans, Arial, sans-serif; margin: 0; padding: 10px; }
        .wrap { display: table; width: 100%; }
        .col { display: table-cell; width: 50%; vertical-align: top; padding: 6px; }

        /* Card shell */
        .idc { width: 300px; height: 480px; border-radius: 10px; overflow: hidden; position: relative; box-shadow: 0 6px 14px rgba(0,0,0,.12); }

        /* Front */
        .front { background: #0f2334; color:#fff; }
        .front .top { text-align:center; padding: 16px 12px 8px; }
        .front .logo { width: 58px; height: 58px; }
        .front .org { font-weight: bold; font-size: 13px; }
        .front .badge { display:inline-block; margin-top:6px; background:#21c1d6; color:#073d49; font-weight:bold; padding:5px 12px; border-radius: 14px; font-size: 11px; }
        .front .photo { margin: 14px auto 10px; width: 170px; height: 180px; background:#1cc3d1; border-radius: 12px; overflow:hidden; }
        .front .photo img { width:100%; height:100%; object-fit:cover; }
        .front .bottom { background:#fff; color:#111; position:absolute; left:0; right:0; bottom:0; padding: 14px 14px 16px; }
        .front .name { font-weight:800; font-size: 16px; margin-bottom:8px; }
        .front .grid { font-size: 11px; }
        .front .grid .row { display: table; width: 100%; margin: 2px 0; }
        .front .grid .row .l { display: table-cell; width: 46%; }
        .front .grid .row .r { display: table-cell; width: 54%; }

        /* Back */
        .back { background:#f5f7fa; color:#0d2231; position: relative; }
        .back .top { text-align:center; padding: 16px 12px 4px; }
        .back .logo { width: 44px; height: 44px; }
        .back .org { font-weight:800; font-size: 12px; }
        .back .content { padding: 8px 14px 0; font-size: 11px; }
        .tbl { display: table; width:100%; }
        .tbl .r { display: table-row; }
        .tbl .c1, .tbl .c2 { display: table-cell; padding: 2px 6px 2px 0; vertical-align: top; }
        .tbl .c1 { width: 34%; white-space: nowrap; font-weight: bold; }
        .notes { margin: 8px 0 0; padding-left: 16px; font-size: 10px; }
        .footer { position:absolute; left:0; right:0; bottom:0; padding: 8px 14px 12px; font-size: 10px; color:#2e5163; }
    </style>
    </head>
<body>
    <div class="wrap">
        <!-- FRONT -->
        <div class="col">
            <div class="idc front">
                <div class="top">
                    @if($logoPath && file_exists($logoPath))
                        <img src="{{ $logoPath }}" class="logo" alt="logo">
                    @endif
                    <div class="org">{{ $organization->name }}</div>
                    <div class="badge">Membership ID Card</div>
                </div>
                <div class="photo">
                    @if($photoPath && file_exists($photoPath))
                        <img src="{{ $photoPath }}" alt="photo">
                    @endif
                </div>
                <div class="bottom">
                    <div class="name">{{ strtoupper($user->name) }}</div>
                    <div class="grid">
                        <div class="row"><div class="l">Membership ID</div><div class="r">: {{ $user->membership_code ?? '-' }}</div></div>
                        <div class="row"><div class="l">Valid Thru</div><div class="r">: {{ $details?->expiry_date?->format('d-M-Y') ?? '-' }}</div></div>
                        <div class="row"><div class="l">Country of Residence</div><div class="r">: {{ $user->country_of_residence ?: '-' }}</div></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- BACK -->
        <div class="col">
            <div class="idc back">
                <div class="top">
                    @if($logoPath && file_exists($logoPath))
                        <img src="{{ $logoPath }}" class="logo" alt="logo">
                    @endif
                    <div class="org">{{ strtoupper($organization->name) }}</div>
                </div>
                <div class="content">
                    <div class="tbl">
                        <div class="r"><div class="c1">ADDRESS</div>
                            <div class="c2">
                                {{ $details?->address_line1 }}<br>
                                @if($details?->address_line2)
                                    {{ $details->address_line2 }}<br>
                                @endif
                                {{ $details?->city }} {{ $details?->zipcode }}
                            </div>
                        </div>
                        <div class="r"><div class="c1">B/G</div><div class="c2">{{ $details?->blood_group ?? 'N/A' }}</div></div>
                        <div class="r"><div class="c1">Tel</div><div class="c2">{{ $user->phone ?: '-' }}</div></div>
                    </div>
                    <ul class="notes">
                        <li>This card is intended only to prove the identity of the card holder as a member.</li>
                        <li>This card is not transferable.</li>
                    </ul>
                </div>
                <div class="footer">
                    {{ config('app.url') }}
                    @if(config('mail.from.address')) | {{ config('mail.from.address') }} @endif
                </div>
            </div>
        </div>
    </div>
</body>
</html>



@php
    $allFacilities = collect($facilities ?? [])->merge($spotFacilities ?? []);
@endphp
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Proposal</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color:#111; }
        .page { padding: 18px; }

        /* Header */
        .header { width: 100%; border-bottom: 2px solid #222; padding-bottom: 10px; margin-bottom: 14px; }
        .brand { font-size: 18px; font-weight: 700; }
        .brand-sub { font-size: 11px; color:#666; margin-top: 2px; }
        .meta { font-size: 11px; color:#444; }
        .meta strong { color:#111; }

        /* Sections */
        h2 { margin: 0; font-size: 18px; }
        h3 { margin: 0 0 6px 0; font-size: 14px; }
        .section { margin-top: 14px; }
        .muted { color: #666; }
        .pre { white-space: pre-wrap; line-height: 1.5; }

        /* Tables */
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; vertical-align: top; }
        th { background: #f2f2f2; font-weight: 700; font-size: 11px; }
        td { font-size: 11px; }
        .right { text-align: right; }
        .nowrap { white-space: nowrap; }

        /* Summary box */
        .summary { width: 100%; margin-top: 8px; border: 1px solid #ddd; }
        .summary td { border: none; padding: 6px 8px; }
        .summary tr td:first-child { color:#444; }
        .summary .total { font-weight: 700; font-size: 12px; border-top: 1px solid #ddd; padding-top: 8px; }

        /* Lists */
        ul { margin: 6px 0 0 16px; padding: 0; }
        li { margin: 3px 0; }

        /* Footer signature */
        .sign { margin-top: 26px; }
        .sign td { border: none; padding: 10px 0; }
        .line { border-top: 1px solid #111; width: 75%; margin-top: 30px; }

        /* Small badge */
        .badge { display:inline-block; font-size:10px; padding:3px 8px; border:1px solid #999; color:#333; border-radius: 2px; }
    </style>
</head>
<body>
<div class="page">

    {{-- ===== Header ===== --}}
    <div class="header">
        <table style="border:none;">
            <tr>
                <td style="border:none; width:60%;">
                  <div class="brand">{{ $company?->company_name ?? config('app.name') }}</div>

                    <div class="brand-sub">
                        {{-- চাইলে এগুলো static করে দাও --}}
                        Address: {{$company?->company_address ?? 'N/A'}} • Phone: {{$company?->company_phone ?? 'N/A'}} • Email: {{$company?->company_email ?? 'N/A'}}
                    </div>
                </td>
                <td style="border:none; width:40%;" class="meta right">
                    <!-- <div><span class="badge">{{ strtoupper($proposal->status ?? 'DRAFT') }}</span></div> -->
                    <div style="margin-top:6px;">
                        <strong>Proposal ID:</strong> #{{ $proposal->proposal_number }}<br>
                        <strong>Date:</strong> {{ $proposal->created_at->format('d M Y') }}
                    </div>
                </td>
            </tr>
        </table>
    </div>

    {{-- ===== Title + Client ===== --}}
    <table style="border:none;">
        <tr>
            <td style="border:none; width:60%;">
                <h2>{{ $proposal->proposal_title ?? 'Service Proposal' }}</h2>
                <div class="muted" style="margin-top:4px;">
                    
@php $customer = $proposal->customer; @endphp
Prepared for: <strong>{{ $customer?->customer_name ?? 'N/A' }}</strong><br>

                    @if($proposal->client_email) Email: {{ $proposal->client_email }}<br>@endif
                    @if($proposal->client_phone) Phone: {{ $proposal->client_phone }}<br>@endif
                </div>
            </td>
            <td style="border:none; width:40%;" class="right">
                {{-- চাইলে এখানে logo বসাতে পারো (dompdf এ public_path দিয়ে) --}}
                {{-- <img src="{{ public_path('logo.png') }}" width="120"> --}}
            </td>
        </tr>
    </table>

    {{-- ===== Introduction ===== --}}
    @if($proposal->intro_text)
        <div class="section">
            <h3>Introduction</h3>
            <div class="pre">{{ $proposal->intro_text }}</div>
        </div>
    @endif

    {{-- ===== Scope / Summary ===== --}}
    <div class="section">
        <h3>Scope of Offer</h3>
        <div class="muted">The following items are included in this proposal based on your selection.</div>

        <ul>
            @if($rooms->count()) <li><strong>Rooms:</strong> {{ $rooms->count() }} item(s)</li> @endif
            @if($spots->count()) <li><strong>Spots:</strong> {{ $spots->count() }} item(s)</li> @endif
            @if($packages->count()) <li><strong>Packages:</strong> {{ $packages->count() }} item(s)</li> @endif
            @if($services->count()) <li><strong>Additional Services:</strong> {{ $services->count() }} item(s)</li> @endif
            <!-- @if($facilities->count()) <li><strong>Common Facilities:</strong> Included</li> @endif -->
        </ul>
    </div>

    {{-- ===== Rooms Table ===== --}}
    @if($rooms->count())
        <div class="section">
            <h3>Rooms</h3>
            <table>
                <thead>
                <tr>
                    <th>Item</th>
                    <th class="right nowrap">Qty</th>
                    <th class="right nowrap">Nights</th>
                    <th class="right nowrap">Unit Price</th>
                    <th class="right nowrap">Total</th>
                </tr>
                </thead>
                <tbody>
                @foreach($rooms as $it)
                    <tr>
                        <td>
                            <strong>{{ $it->title }}</strong>
                            @if($it->description)<div class="muted">{{ $it->description }}</div>@endif
                        </td>
                        <td class="right">{{ $it->quantity }}</td>
                        <td class="right">{{ $it->nights }}</td>
                        <td class="right">{{ number_format($it->unit_price, 2) }}</td>
                        <td class="right">{{ number_format($it->line_total, 2) }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @endif

    {{-- ===== Spots Table ===== --}}
    @if($spots->count())
        <div class="section">
            <h3>Spots</h3>
            <table>
                <thead>
                <tr>
                    <th>Item</th>
                    <th class="right nowrap">Qty</th>
                    <th class="right nowrap">Unit Price</th>
                    <th class="right nowrap">Total</th>
                </tr>
                </thead>
                <tbody>
                @foreach($spots as $it)
                    <tr>
                        <td>
    <strong>{{ $it->title }}</strong>

    @php
    $desc = $it->description ?? '';
    $looksLikeHtml = $desc !== strip_tags($desc); // HTML tag আছে কিনা
@endphp

@if($desc)
    <div class="muted">
        @if($looksLikeHtml)
            {!! $desc !!} {{-- table/html হলে render --}}
        @else
            {!! nl2br(e($desc)) !!} {{-- normal text safe + newline support --}}
        @endif
    </div>
@endif

</td>

                        <td class="right">{{ $it->quantity }}</td>
                        <td class="right">{{ number_format($it->unit_price, 2) }}</td>
                        <td class="right">{{ number_format($it->line_total, 2) }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @endif

    {{-- ===== Packages Table ===== --}}
    @if($packages->count())
        <div class="section">
            <h3>Packages</h3>
            <table>
                <thead>
                <tr>
                    <th>Item</th>
                    <th class="right nowrap">Qty</th>
                    <th class="right nowrap">Unit Price</th>
                    <th class="right nowrap">Total</th>
                </tr>
                </thead>
                <tbody>
                @foreach($packages as $it)
                    <tr>
                        <td>
                            <strong>{{ $it->title }}</strong>
                            @if($it->description)<div class="muted">{{ $it->description }}</div>@endif
                        </td>
                        <td class="right">{{ $it->quantity }}</td>
                        <td class="right">{{ number_format($it->unit_price, 2) }}</td>
                        <td class="right">{{ number_format($it->line_total, 2) }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @endif

    {{-- ===== Services Table ===== --}}
    @if($services->count())
        <div class="section">
            <h3>Additional Services</h3>
            <table>
                <thead>
                <tr>
                    <th>Item</th>
                    <th class="right nowrap">Qty</th>
                    <th class="right nowrap">Unit Price</th>
                    <th class="right nowrap">Total</th>
                </tr>
                </thead>
                <tbody>
                @foreach($services as $it)
                    <tr>
                        <td>
                            <strong>{{ $it->title }}</strong>
                            @if($it->description)<div class="muted">{{ $it->description }}</div>@endif
                        </td>
                        <td class="right">{{ $it->quantity }}</td>
                        <td class="right">{{ number_format($it->unit_price, 2) }}</td>
                        <td class="right">{{ number_format($it->line_total, 2) }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @endif

    {{-- ===== Facilities ===== --}}
    @if($allFacilities->count())
    <h5>Facilities Included</h5>
    <ul>
        @foreach($allFacilities as $it)
            <li>
                {{ $it->title ?? '' }}
                @if(($it->item_type ?? null) === 'spot_facility' && !empty($it->description))
                    <small class="text-muted">({{ $it->description }})</small>
                @endif
            </li>
        @endforeach
    </ul>
@endif

    {{-- ===== Pricing Summary ===== --}}
    <div class="section">
    <h3>Pricing Summary</h3>

    <table class="summary">
        @if(($roomsTotal ?? 0) > 0)
            <tr>
                <td class="right"><strong>Rooms Total</strong></td>
                <td class="right nowrap">{{ number_format($roomsTotal, 2) }}</td>
            </tr>
        @endif

        @if(($spotsTotal ?? 0) > 0)
            <tr>
                <td class="right"><strong>Spots Total</strong></td>
                <td class="right nowrap">{{ number_format($spotsTotal, 2) }}</td>
            </tr>
        @endif

        @if(($packagesTotal ?? 0) > 0)
            <tr>
                <td class="right"><strong>Packages Total</strong></td>
                <td class="right nowrap">{{ number_format($packagesTotal, 2) }}</td>
            </tr>
        @endif

        @if(($servicesTotal ?? 0) > 0)
            <tr>
                <td class="right"><strong>Services Total</strong></td>
                <td class="right nowrap">{{ number_format($servicesTotal, 2) }}</td>
            </tr>
        @endif

        <tr>
            <td class="right"><strong>Subtotal</strong></td>
            <td class="right nowrap">{{ number_format($proposal->subtotal, 2) }}</td>
        </tr>

        <tr>
            <td class="right"><strong>Discount</strong></td>
            <td class="right nowrap">{{ number_format($proposal->discount, 2) }}</td>
        </tr>

        <tr>
            <td class="right"><strong>Tax</strong></td>
            <td class="right nowrap">{{ number_format($proposal->tax, 2) }}</td>
        </tr>

        <tr>
            <td class="right total"><strong>Total Payable</strong></td>
            <td class="right total nowrap"><strong>{{ number_format($proposal->total, 2) }}</strong></td>
        </tr>
    </table>

    <div class="muted" style="margin-top:6px;">
        * Prices are subject to availability and may change based on final confirmation.
    </div>
</div>


    {{-- ===== Terms ===== --}}
    @if($proposal->terms_text)
        <div class="section">
            <h3>Terms & Conditions</h3>
            <div class="pre">{{ $proposal->terms_text }}</div>
        </div>
    @endif

    {{-- ===== Notes ===== --}}
    @if($proposal->notes_text)
        <div class="section">
            <h3>Notes</h3>
            <div class="pre">{{ $proposal->notes_text }}</div>
        </div>
    @endif

    {{-- ===== Signatures ===== --}}
    <div class="sign">
        <table style="border:none; width:100%;">
            <tr>
                <td style="border:none; width:50%;">
                    <div class="line"></div>
                    Authorized Signature
                </td>
                <td style="border:none; width:50%; text-align:right;">
                    <div class="line" style="margin-left:auto;"></div>
                    Client Signature
                </td>
            </tr>
        </table>
    </div>

</div>
</body>
</html>

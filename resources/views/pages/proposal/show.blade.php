@php
    $customer = $proposal->customer;
    $allFacilities = collect($facilities ?? [])->merge($spotFacilities ?? []);
@endphp

<x-default-layout>
<div class="container my-4">

    {{-- Header --}}
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
        <div>
            <h3 class="mb-0">Proposal / Invoice</h3>
            <div class="text-muted small">
                Proposal No: <strong>{{ $proposal->proposal_number ?? ('#'.$proposal->id) }}</strong>
                • Date: <strong>{{ optional($proposal->created_at)->format('d M Y') }}</strong>
            </div>
        </div>

        <div class="d-flex gap-2">
            <a class="btn btn-outline-dark" href="{{ route('proposals.pdf', $proposal) }}">
                <i class="fa fa-file-pdf me-1"></i> Download PDF
            </a>
            <button class="btn btn-outline-secondary" onclick="window.print()">
                <i class="fa fa-print me-1"></i> Print
            </button>
        </div>
    </div>

    {{-- Body --}}
    <div class="card shadow-sm border-0">
        <div class="card-body p-4">

            {{-- Title + Status --}}
            <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-3">
                <div>
                    <h4 class="mb-1">{{ $proposal->proposal_title ?? 'Proposal' }}</h4>
                    <span class="badge bg-secondary text-uppercase">
                        {{ $proposal->status ?? 'draft' }}
                    </span>
                </div>

                <div class="text-end">
                    <div class="text-muted small">Total Payable</div>
                    <div class="fs-4 fw-bold">৳{{ number_format($proposal->total ?? 0, 2) }}</div>
                </div>
            </div>

            <hr>

            {{-- Client Info --}}
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <div class="fw-bold mb-1">Billed To</div>
                    <div class="small">
                        <div><strong>{{ $customer?->customer_name ?? $proposal->client_name ?? 'N/A' }}</strong></div>
                        <div class="text-muted">{{ $customer?->customer_email ?? $proposal->client_email ?? 'N/A' }}</div>
                        <div class="text-muted">{{ $customer?->customer_mobile ?? $proposal->client_phone ?? 'N/A' }}</div>
                    </div>
                </div>

                <div class="col-md-6 text-md-end">
                    <div class="fw-bold mb-1">Invoice Info</div>
                    <div class="small">
                        <div>Proposal ID: <strong>{{ $proposal->proposal_number ?? $proposal->id }}</strong></div>
                        <div>Date: <strong>{{ optional($proposal->created_at)->format('d M Y') }}</strong></div>
                    </div>
                </div>
            </div>

            {{-- Intro --}}
            @if($proposal->intro_text)
                <div class="mb-4">
                    <div class="fw-bold mb-2">Introduction</div>
                    <div class="border rounded p-3 bg-light" style="white-space: pre-wrap;">
                        {{ $proposal->intro_text }}
                    </div>
                </div>
            @endif

            {{-- Items Table --}}
            <div class="table-responsive mb-4">
                <table class="table table-bordered align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th style="width: 50px;">SL</th>
                            <th>Item</th>
                            <th style="width: 120px;" class="text-end">Qty</th>
                            <th style="width: 120px;" class="text-end">Nights</th>
                            <th style="width: 140px;" class="text-end">Unit Price</th>
                            <th style="width: 160px;" class="text-end">Line Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $sl = 1; @endphp

                        {{-- Rooms --}}
                        @foreach($rooms as $it)
                            <tr>
                                <td class="text-center">{{ $sl++ }}</td>
                                <td>
                                    <div class="fw-bold">{{ $it->title }}</div>
                                    @if($it->description) <div class="text-muted small">{{ $it->description }}</div> @endif
                                    <span class="badge bg-primary mt-1">Room</span>
                                </td>
                                <td class="text-end">{{ $it->quantity }}</td>
                                <td class="text-end">{{ $it->nights ?? '-' }}</td>
                                <td class="text-end">৳{{ number_format($it->unit_price, 2) }}</td>
                                <td class="text-end fw-bold">৳{{ number_format($it->line_total, 2) }}</td>
                            </tr>
                        @endforeach

                        {{-- Spots --}}
                        @foreach($spots as $it)
                            <tr>
                                <td class="text-center">{{ $sl++ }}</td>
                                <td>
                                    <div class="fw-bold">{{ $it->title }}</div>
                                    @if($it->description) <div class="text-muted small">{{ $it->description }}</div> @endif
                                    <span class="badge bg-info mt-1">Spot</span>
                                </td>
                                <td class="text-end">{{ $it->quantity }}</td>
                                <td class="text-end">-</td>
                                <td class="text-end">৳{{ number_format($it->unit_price, 2) }}</td>
                                <td class="text-end fw-bold">৳{{ number_format($it->line_total, 2) }}</td>
                            </tr>
                        @endforeach

                        {{-- Packages --}}
                        @foreach($packages as $it)
                            <tr>
                                <td class="text-center">{{ $sl++ }}</td>
                                <td>
                                    <div class="fw-bold">{{ $it->title }}</div>
                                    @if($it->description) <div class="text-muted small">{{ $it->description }}</div> @endif
                                    <span class="badge bg-warning text-dark mt-1">Package</span>
                                </td>
                                <td class="text-end">{{ $it->quantity }}</td>
                                <td class="text-end">-</td>
                                <td class="text-end">৳{{ number_format($it->unit_price, 2) }}</td>
                                <td class="text-end fw-bold">৳{{ number_format($it->line_total, 2) }}</td>
                            </tr>
                        @endforeach

                        {{-- Services --}}
                        @foreach($services as $it)
                            <tr>
                                <td class="text-center">{{ $sl++ }}</td>
                                <td>
                                    <div class="fw-bold">{{ $it->title }}</div>
                                    @if($it->description) <div class="text-muted small">{{ $it->description }}</div> @endif
                                    <span class="badge bg-success mt-1">Service</span>
                                </td>
                                <td class="text-end">{{ $it->quantity }}</td>
                                <td class="text-end">-</td>
                                <td class="text-end">৳{{ number_format($it->unit_price, 2) }}</td>
                                <td class="text-end fw-bold">৳{{ number_format($it->line_total, 2) }}</td>
                            </tr>
                        @endforeach

                        @if($sl === 1)
                            <tr>
                                <td colspan="6" class="text-center text-muted">No items found.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            {{-- Facilities --}}
            @if($allFacilities->count())
                <div class="mb-4">
                    <div class="fw-bold mb-2">Facilities Included</div>
                    <div class="border rounded p-3 bg-light">
                        <ul class="mb-0">
                            @foreach($allFacilities as $it)
                                <li class="small">
                                    {{ $it->title ?? '' }}
                                    @if(($it->item_type ?? null) === 'spot_facility' && !empty($it->description))
                                        <span class="text-muted">({{ $it->description }})</span>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            {{-- Summary --}}
            <div class="row justify-content-end">
                <div class="col-md-5">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Subtotal</span>
                                <strong>৳{{ number_format($proposal->subtotal, 2) }}</strong>
                            </div>
                            <div class="d-flex justify-content-between mt-2">
                                <span class="text-muted">Discount</span>
                                <strong>৳{{ number_format($proposal->discount, 2) }}</strong>
                            </div>
                            <div class="d-flex justify-content-between mt-2">
                                <span class="text-muted">Tax</span>
                                <strong>৳{{ number_format($proposal->tax, 2) }}</strong>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between">
                                <span class="fw-bold">Total</span>
                                <span class="fw-bold fs-5">৳{{ number_format($proposal->total, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Terms --}}
            @if($proposal->terms_text)
                <div class="mt-4">
                    <div class="fw-bold mb-2">Terms & Conditions</div>
                    <div class="border rounded p-3" style="white-space: pre-wrap;">
                        {{ $proposal->terms_text }}
                    </div>
                </div>
            @endif

            {{-- Notes --}}
            @if($proposal->notes_text)
                <div class="mt-4">
                    <div class="fw-bold mb-2">Notes</div>
                    <div class="border rounded p-3" style="white-space: pre-wrap;">
                        {{ $proposal->notes_text }}
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>

{{-- Print style --}}
<style>
@media print {
    .btn, nav, .sidebar, .navbar, footer { display: none !important; }
    .card { border: none !important; box-shadow: none !important; }
    .container { margin: 0 !important; padding: 0 !important; }
}
</style>

</x-default-layout>

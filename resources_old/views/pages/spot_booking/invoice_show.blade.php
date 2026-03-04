<x-default-layout>

    @php
        $invoiceSummary['invoice_amount']  = $invoiceAmount;
        $invoiceSummary['received_amount'] = $receivedAmount;
        $invoiceSummary['due_amount']      = $dueAmount;

        $subTotal       = $invoiceSummary['sub_total'];
        $discountAmount = $invoiceSummary['discount_amount'];
        $netTotal       = $invoiceSummary['net_total'];
    @endphp

    <div class="container-fluid py-5 fs-6">

        {{-- ================= Invoice Header ================= --}}
        <div class="card shadow-sm mb-5 border-0">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h2 class="fw-bold mb-1">
                            Invoice: {{ $invoiceSummary['invoice'] }}
                        </h2>

                        <a href="{{ route('spot-bookings.invoice.pdf', $invoiceSummary['invoice']) }}"
                          target="_blank" class="btn btn-sm btn-danger mb-2">
                            Download PDF
                        </a>

                        <p class="mb-0 text-muted">
                            Date:
                            {{ \Carbon\Carbon::parse($invoiceSummary['date'])->format('d M Y') }}
                        </p>
                    </div>

                    <div class="text-end">
                        @if ($invoiceSummary['due_amount'] == 0)
                            <span class="badge bg-success fs-6 px-3 py-2">Paid</span>
                        @else
                            <span class="badge bg-warning fs-6 px-3 py-2 text-dark">
                                Partially Paid
                            </span>
                        @endif
                    </div>
                </div>

                <hr class="my-3">

                <p class="mb-1">
                    <strong>Customer:</strong>
                    {{ $invoiceSummary['customer_name'] ?? 'N/A' }}
                </p>
                <p class="mb-0">
                    <strong>Mobile:</strong>
                    {{ $invoiceSummary['customer_mobile'] ?? 'N/A' }}
                </p>
            </div>
        </div>

        {{-- ================= Spot Details ================= --}}
        @php $spotBookings = $bookings->whereNotNull('spot_id'); @endphp
        @if($spotBookings->count())
            <div class="mb-5 table-responsive">
                <h5>Spot Details</h5>
                <table class="table table-bordered table-sm align-middle text-nowrap">
                    <colgroup>
                        <col style="width:50%">
                        <col style="width:15%">
                        <col style="width:15%">
                        <col style="width:20%">
                    </colgroup>
                    <thead class="table-light">
                        <tr>
                            <th>Spot</th>
                            <th class="text-center">Qty</th>
                            <th class="text-end">Price (৳)</th>
                            <th class="text-end">Amount (৳)</th>
                        </tr>
                    </thead>
                        @php
    $priceTotal = 0;
@endphp
                    <tbody>
                        @foreach($spotBookings as $row)
                            @php
                                $spotDiscount = ($row->total_price * ($row->spot_discount_percent ?? 0)) / 100;
                                $spotNet = $row->total_price - $spotDiscount;
                                 $priceTotal += $row->total_price;
                            @endphp
                            <tr>
                                <td>{{ $row->spot_title }}</td>
                                <td class="text-center">1</td>
                                <td class="text-end">{{ number_format($row->total_price, 2) }}</td>
                                <td class="text-end fw-semibold">{{ number_format($spotNet, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <th colspan="2" class="text-end">Total</th>
                            <th class="text-end">
                                {{ number_format($priceTotal, 2) }}
                            </th>
                            <th class="text-end fw-bold text-primary">
                                ৳ {{
                                    number_format(
                                        $spotBookings->sum(fn($b) =>
                                            $b->total_price - ($b->total_price * ($b->spot_discount_percent ?? 0) / 100)
                                        ),
                                        2
                                    )
                                }}
                            </th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @endif

        {{-- ================= Package Details ================= --}}
        @php $packageBookings = $bookings->whereNotNull('package_id'); @endphp
        @if($packageBookings->count())
            <div class="mb-5 table-responsive">
                <h5>Package Details</h5>
                <table class="table table-bordered table-sm align-middle text-nowrap">
                    <colgroup>
                        <col style="width:50%">
                        <col style="width:15%">
                        <col style="width:15%">
                        <col style="width:20%">
                    </colgroup>
                    <thead class="table-light">
                        <tr>
                            <th>Package</th>
                            <th class="text-center">Qty</th>
                            <th class="text-end">Price (৳)</th>
                            <th class="text-end">Amount (৳)</th>
                        </tr>
                    </thead>
                        @php
                            $packagePriceTotal = 0;
                        @endphp
                    <tbody>
                        @foreach($packageBookings as $row)
                                @php
                                    $packagePriceTotal += $row->package_price;
                                @endphp
                                                            <tr>
                                <td>{{ $row->package_name }}</td>
                                <td class="text-center">{{ $row->package_persons }}</td>
                                <td class="text-end">{{ number_format($row->package_price, 2) }}</td>
                                <td class="text-end fw-semibold">{{ number_format($row->total_price, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <th colspan="2" class="text-end">Total</th>
                                   <th class="text-end">
                                        {{ number_format($packagePriceTotal, 2) }}
                                    </th>
                            <th class="text-end fw-bold text-primary">৳ {{ number_format($packageBookings->sum('total_price'), 2) }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @endif

        {{-- ================= Additional Services ================= --}}
        @if ($services->count())
            <div class="mb-5 table-responsive">
                <h5>Additional Services</h5>
                <table class="table table-bordered table-sm align-middle text-nowrap">
                    <colgroup>
                        <col style="width:50%">
                        <col style="width:15%">
                        <col style="width:15%">
                        <col style="width:20%">
                    </colgroup>
                    <thead class="table-light">
                        <tr>
                            <th>Service</th>
                            <th class="text-center">Qty</th>
                            <th class="text-end">Price (৳)</th>
                            <th class="text-end">Total (৳)</th>
                        </tr>
                    </thead>
                        @php
                            $ServicePriceTotal = 0;
                        @endphp
                    <tbody>
                        @foreach ($services as $service)
                                @php
                                    $ServicePriceTotal += $service->price;
                                @endphp
                            <tr>
                                <td>{{ $service->service_title }}</td>
                                <td class="text-center">{{ $service->quantity }}</td>
                                <td class="text-end">{{ number_format($service->price, 2) }}</td>
                                <td class="text-end fw-semibold">{{ number_format($service->total_price, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <th colspan="2" class="text-end">Total</th>
                                <th class="text-end">
                                    {{ number_format($ServicePriceTotal, 2) }}
                                </th>
                            <th class="text-end fw-bold text-primary">৳ {{ number_format($invoiceSummary['service_total'], 2) }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @endif

        {{-- ================= Payment Summary ================= --}}
        <div class="mb-5 table-responsive">
            <table class="table table-bordered table-sm align-middle text-nowrap">
                <colgroup>
                    <col style="width:80%">
                    <col style="width:30%">
                </colgroup>
                <tbody>
                    <tr>
                        <th class="text-end">Sub Total</th>
                        <th class="text-end text-primary">৳ {{ number_format($subTotal, 2) }}</th>
                    </tr>
                    <tr>
                        <th class="text-end text-danger">Discount</th>
                        <th class="text-end text-danger">-৳ {{ number_format($discountAmount, 2) }}</th>
                    </tr>
                    <tr class="table-light">
                        <th class="text-end fw-bold">Net Amount</th>
                        <th class="text-end fw-bold">৳ {{ number_format($netTotal, 2) }}</th>
                    </tr>
                    <tr>
                        <th class="text-end text-success">Received Amount</th>
                        <th class="text-end text-success">৳ {{ number_format($invoiceSummary['received_amount'], 2) }}</th>
                    </tr>
                    <tr>
                        <th class="text-end text-danger fs-5">Due Amount</th>
                        <th class="text-end text-danger fs-5">৳ {{ number_format($invoiceSummary['due_amount'], 2) }}</th>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>

</x-default-layout>

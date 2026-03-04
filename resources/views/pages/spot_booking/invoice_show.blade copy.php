<x-default-layout>

    <div class="container-fluid py-5">

        {{-- Invoice Header --}}
        <div class="card shadow-sm mb-5">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h3 class="fw-bold">Invoice: {{ $invoiceSummary['invoice'] }}</h3>
                        
                        <a href="{{ route('spot-bookings.invoice.pdf', $invoiceSummary['invoice']) }}"
                            class="btn btn-sm btn-danger">
                            Download PDF
                        </a>

                        <p class="mb-0">
                            Date: {{ \Carbon\Carbon::parse($invoiceSummary['date'])->format('d M Y') }}
                        </p>
                    </div>
                    <div>
                        <span class="badge badge-light-{{ $invoiceSummary['status'] ? 'success' : 'secondary' }}">
                            {{ $invoiceSummary['status'] ? 'Confirmed' : 'Pending' }}
                        </span>
                    </div>
                </div>

                <hr>

                <p class="mb-1"><strong>Customer:</strong> {{ $invoiceSummary['customer_name'] ?? 'N/A' }}</p>
                <p><strong>Mobile:</strong> {{ $invoiceSummary['customer_mobile'] ?? 'N/A' }}</p>
            </div>
        </div>

        {{-- Spot & Package Details --}}
        <div class="card shadow-sm mb-5">
            <div class="card-header">
                <h5 class="mb-0">Spot & Package Details</h5>
            </div>
            <div class="card-body p-0">
                <table class="table table-bordered mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th>Spot</th>
                            <th>Persons</th>
                            <th class="text-end">Amount (৳)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($bookings as $row)
                            <tr>
                                <td>{{ $row->spot_title }}</td>
                                <td>{{ $row->persons }}</td>
                                <td class="text-end">{{ number_format($row->total_price, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="2" class="text-end">Sub Total</th>
                            <th class="text-end">
                                {{ number_format($invoiceSummary['spot_total'], 2) }}
                            </th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>


        {{-- Additional Services --}}
        @if ($services->count())
            <div class="card shadow-sm mb-5">
                <div class="card-header">
                    <h5 class="mb-0">Additional Services</h5>
                </div>
                <div class="card-body p-0">
                    <table class="table table-bordered mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>Service</th>
                                <th class="text-center">Qty</th>
                                <th class="text-end">Price (৳)</th>
                                <th class="text-end">Total (৳)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($services as $service)
                                <tr>
                                    <td>{{ $service->service_title }}</td>
                                    <td class="text-center">{{ $service->quantity }}</td>
                                    <td class="text-end">{{ number_format($service->price, 2) }}</td>
                                    <td class="text-end">{{ number_format($service->total_price, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3" class="text-end">Service Total</th>
                                <th class="text-end">
                                    {{ number_format($invoiceSummary['service_total'], 2) }}
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        @endif


        {{-- Grand Total --}}
        <div class="card shadow-sm">
            <div class="card-body text-end">
                <h3 class="fw-bold">
                    Grand Total: ৳ {{ number_format($invoiceSummary['grand_total'], 2) }}
                </h3>
            </div>
        </div>


    </div>

</x-default-layout>

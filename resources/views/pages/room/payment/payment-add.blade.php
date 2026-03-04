<x-default-layout>
<div class="app-main flex-column flex-row-fluid" id="kt_app_main">
    <div class="d-flex flex-column flex-column-fluid">

        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container">

                {{-- Error Alert --}}
                @if ($errors->any())
                    <div class="alert alert-danger shadow-sm">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('payment.store') }}">
                    @csrf

                    <div class="row g-5">

                        {{-- LEFT SIDE – SUMMARY --}}
                        <div class="col-lg-4">
                            <div class="card shadow-sm border-0">
                                <div class="card-header bg-light text-white">
                                    <h4 class="mb-0">Payment Summary</h4>
                                </div>

                                <div class="card-body fs-6">

                                    <div class="mb-3">
                                        <strong>Customer Name:</strong><br>
                                        <span class="text-dark fw-semibold">
                                            {{ $firstBooking->customer->customer_name ?? 'N/A' }}
                                        </span>
                                    </div>

                                    <hr>

                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Total Amount:</span>
                                        <strong>{{ $bookingTotalSum }}</strong>
                                    </div>

                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Discount:</span>
                                        <strong class="text-danger">- {{ $discount }}</strong>
                                    </div>

                                    <div class="d-flex justify-content-between mb-2">
                                        <span>After Discount:</span>
                                        <strong>{{ $bookingTotalSum - $discount }}</strong>
                                    </div>

                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Total Received:</span>
                                        <strong class="text-success">{{ $totalReceivedSum }}</strong>
                                    </div>

                                    <div class="d-flex justify-content-between border-top pt-3 mt-3">
                                        <span class="fw-bold">Due Amount:</span>
                                        <strong class="text-danger fs-5">
                                            {{ $bookingTotalSum - $totalReceivedSum - $discount }}
                                        </strong>
                                    </div>

                                    <hr class="my-4">

                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">
                                            Receive Amount
                                        </label>
                                        <input type="number"
                                               name="amount"
                                               class="form-control form-control-sm"
                                               placeholder="Enter amount">
                                        <input type="hidden" name="booking_no"
                                               value="{{ $bookingNo }}">
                                    </div>

                                    <div class="d-flex justify-content-end mt-4">
                                        <a href="{{ route('booking.index') }}"
                                           class="btn btn-light me-3">
                                            Cancel
                                        </a>

                                        <button type="submit"
                                                class="btn btn-success">
                                            Save Payment
                                        </button>
                                    </div>

                                </div>
                            </div>
                        </div>


                        {{-- RIGHT SIDE – PAYMENT HISTORY --}}
                        <div class="col-lg-8">
                            <div class="card shadow-sm border-0">
                                <div class="card-header bg-light">
                                    <h4 class="mb-0">Payment History</h4>
                                </div>

                                <div class="card-body">

                                    <table class="table table-striped table-hover align-middle text-center">
                                        <thead class="table-light">
                                            <tr class="fw-bold text-uppercase">
                                                <th>ID</th>
                                                <th>Date</th>
                                                <th>Paid Amount</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @php $totalAmount = 0; @endphp

                                            @foreach ($totalReceived as $payment)
                                                @php $totalAmount += $payment->amount; @endphp
                                                <tr>
                                                    <td>{{ $payment->id }}</td>
                                                    <td>{{ $payment->created_at->format('d M Y') }}</td>
                                                    <td class="text-success fw-semibold">
                                                        {{ $payment->amount }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>

                                        <tfoot class="table-light">
                                            <tr>
                                                <th colspan="2" class="text-end">
                                                    Total Received:
                                                </th>
                                                <th class="text-success fs-6">
                                                    {{ $totalAmount }}
                                                </th>
                                            </tr>
                                        </tfoot>
                                    </table>

                                </div>
                            </div>
                        </div>

                    </div>
                </form>

            </div>
        </div>

    </div>
</div>
</x-default-layout>

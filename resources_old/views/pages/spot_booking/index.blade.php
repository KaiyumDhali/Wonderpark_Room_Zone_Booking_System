<x-default-layout>

    <style>
        .dataTables_filter {
            float: right;
        }

        .dataTables_buttons {
            float: left;
        }

        .bottom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .status-pending {
            background-color: #ffc107; /* yellow */
            color: #000;
        }

        .status-approved {
            background-color: #198754; /* green */
            color: #fff;
        }

        .status-cancelled {
            background-color: #dc3545; /* red */
            color: #fff;
        }
    </style>

    {{-- Alerts --}}
    <div class="col-xl-12 px-5">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('message'))
            <div class="alert alert-{{ session('alert-type', 'success') }}">
                {{ session('message') }}
            </div>
        @endif
    </div>

    <!-- Toolbar -->
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div class="app-container d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center">
                <h3>Spot Bookings</h3>
                <span class="text-muted fs-7">List of all spot package bookings</span>
            </div>

            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('spot-bookings.create') }}" class="btn btn-sm btn-primary">
                    New Booking
                </a>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container">

            <div class="card card-flush">

                <!-- Card Header -->
                <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                    <div class="card-title">
                        <div class="d-flex align-items-center position-relative my-1">
                            <span class="svg-icon svg-icon-1 position-absolute ms-4">
                                <i class="bi bi-search fs-4"></i>
                            </span>
                            <input type="text" class="form-control form-control-solid w-250px ps-14 p-2"
                                placeholder="Search booking">
                        </div>
                    </div>
                </div>

                <!-- Table -->
                <div class="card-body pt-0">
                    <table class="table table-striped table-bordered align-middle table-row-dashed fs-7 gy-5 mb-0">
                        <thead>
                            <tr class="text-start fs-7 text-uppercase gs-0">
                                <th>No</th>
                                <th>Invoice</th>
                                <th>Date</th>
                                <th>Total Persons</th>
                                <th>Total (৳)</th>
                                <th>Discount % (৳)</th>
                                <th>Net Total (৳)</th>
                                <th>Customer</th>
                                <th>Mobile</th>
                                <th>Status</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>

                        <tbody class="fw-semibold text-gray-700">
                            @forelse($bookings as $booking)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>

                                    <td>{{ $booking->invoice_number }}</td>

                                    <td>{{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}</td>

                                    <td class="text-center">{{ $booking->total_persons }}</td>

                                    {{-- Subtotal (spot + services) --}}
                                    <td class="text-end">{{ number_format($booking->sub_total, 2) }}</td>

                                    {{-- Total discount (spot discount + manual discount) --}}
                                    <!-- <td class="text-end text-danger">
                                        -{{ number_format($booking->manual_discount_amount, 2) }}
                                        <br>
                                        <small class="text-muted">
                                            (Discount: {{ $booking->discount_percent }}%)
                                        </small>
                                    </td> -->
                                        <td class="text-end text-danger">
                                            -{{ number_format($booking->manual_discount_amount, 2) }}
                                        </td>


                                    {{-- Grand total --}}
                                    <td class="text-end fw-bold text-success">
                                        {{ number_format($booking->grand_total, 2) }}
                                    </td>

                                    <td>{{ $booking->customer_name ?? '-' }}</td>

                                    <td>{{ $booking->customer_mobile ?? '-' }}</td>

                                    {{-- Status dropdown --}}
                                    <td>
                                        <select class="form-select form-select-sm status-dropdown"
                                            data-invoice="{{ $booking->invoice_number }}">
                                            <option value="0" {{ $booking->status == 0 ? 'selected' : '' }}>Pending</option>
                                            <option value="1" {{ $booking->status == 1 ? 'selected' : '' }}>Approved</option>
                                            <option value="2" {{ $booking->status == 2 ? 'selected' : '' }}>Cancelled</option>
                                        </select>
                                    </td>

                                    {{-- Actions --}}
                                    <td class="text-end">
                                        <a href="{{ route('spot-bookings.show.invoice', $booking->invoice_number) }}"
                                            class="btn btn-sm btn-light-primary">
                                            View
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="11" class="text-center">No Bookings Found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    {{-- Pagination --}}
                    @if (method_exists($bookings, 'links'))
                        <div class="mt-4 d-flex justify-content-end">
                            {{ $bookings->links() }}
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>

</x-default-layout>

{{-- jQuery + status update --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    function updateDropdownColor(dropdown) {
        let val = dropdown.val();
        dropdown.removeClass('status-pending status-approved status-cancelled');
        if(val == 0) dropdown.addClass('status-pending');
        else if(val == 1) dropdown.addClass('status-approved');
        else if(val == 2) dropdown.addClass('status-cancelled');
    }

    // Set initial colors
    $('.status-dropdown').each(function() {
        updateDropdownColor($(this));
    });

    // On change
    $('.status-dropdown').change(function() {
        let dropdown = $(this);
        let invoice = dropdown.data('invoice');
        let status = dropdown.val();
        let token = '{{ csrf_token() }}';

        updateDropdownColor(dropdown);

        $.ajax({
            url: '{{ route("spot-bookings.updateStatus") }}',
            type: 'POST',
            data: {
                _token: token,
                invoice_number: invoice,
                status: status
            },
            success: function(response) {
                alert(response.message);
            },
            error: function(xhr) {
                alert('Error: ' + xhr.responseText);
            }
        });
    });
});
</script>

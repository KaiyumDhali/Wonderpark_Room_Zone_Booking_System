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

        .status-select {
            border: 1px solid #ccc;
            padding: 5px;
            border-radius: 4px;
        }

        .status-select.pending {
            background-color: yellow;
            color: black;
        }

        .status-select.approved {
            background-color: green;
            color: white;
        }

        .status-select.cancelled {
            background-color: red;
            color: white;
        }
    </style>
    <div class="col-xl-12 px-5">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if (session('message'))
            <div class="alert alert-{{ session('alert-type') }}">
                {{ session('message') }}
            </div>
        @endif
    </div>
    <!--begin::Toolbar-->
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <!--begin::Toolbar container-->
        <div id="kt_app_toolbar_container" class="app-container d-flex flex-stack">
            <!--begin::Page title-->
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <!--begin::Title-->
                <h3>Booking List</h3>
                <!--end::Title-->
            </div>
            <!--end::Page title-->
        </div>
        <!--end::Toolbar container-->
    </div>
    <!--end::Toolbar-->

    <!--begin::Content-->
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container">
            <!--begin::Products-->
            <div class="card card-flush">
                <!--begin::Card header-->
                <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                    <!--begin::Card title-->
                    <div class="card-title">
                        <!--begin::Search-->
                        <div class="d-flex align-items-center position-relative my-1">
                            <!--begin::Svg Icon | path: icons/duotune/general/gen021.svg-->
                            <span class="svg-icon svg-icon-1 position-absolute ms-4">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2"
                                        rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor" />
                                    <path
                                        d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z"
                                        fill="currentColor" />
                                </svg>
                            </span>
                            <!--end::Svg Icon-->
                            <input type="text" data-kt-ecommerce-order-filter="search"
                                class="form-control form-control-solid w-250px ps-14 p-2" placeholder="Search Report" />
                        </div>
                        <!--end::Search-->
                        <!--begin::Export buttons-->
                        <div id="kt_ecommerce_report_customer_orders_export" class="d-none"></div>
                        <!--end::Export buttons-->
                    </div>
                    <!--end::Card title-->
                    <!--begin::Card toolbar-->
                    <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                        <!--begin::Daterangepicker-->
                        {{-- <input class="form-control form-control-solid w-100 mw-250px" placeholder="Pick date range" id="kt_ecommerce_report_customer_orders_daterangepicker" /> --}}
                        <!--end::Daterangepicker-->
                        <!--begin::Filter-->
                        {{-- <div class="w-150px">
                            <select class="form-select p-2 form-select-solid" data-control="select2"
                                data-hide-search="true" data-placeholder="Status"
                                data-kt-ecommerce-order-filter="status">
                                <option value="all"> Status</option>
                                <option value="Active">Active</option>
                                <option value="Disabled">Disabled</option>
                            </select>
                        </div> --}}
                        <!--end::Filter-->
                        {{-- @can('create customer')
                            <a href="{{ route('booking.create') }}" class="btn btn-sm btn-primary">New Booking</a>
                        @endcan --}}
                    </div>
                    <!--end::Card toolbar-->
                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body pt-0">
                    <!--begin::Table-->
                    <table class="table table-striped table-bordered align-middle table-row-dashed fs-7 gy-5 mb-0"
                        id="kt_ecommerce_report_customer_orders_table">


                        @foreach ($bookings as $bookingNo => $group)
                            <tr>
                                <td rowspan="{{ count($group) }}">
                                    <h4>Booking No: {{ $bookingNo }}
                                        <a class="btn btn-sm btn-success p-2"
                                            href="{{ route('payment.edit', $bookingNo) }}">
                                            Payment Details
                                        </a>
                                    </h4>
                                </td>
                            </tr>

                            <table class="table table-bordered">
                                <thead class="table-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Customer</th>
                                        <th>Room</th>
                                        <th>Check-in</th>
                                        <th>Check-end</th>
                                        <th>Check-out</th>
                                        <th>Total Days</th>
                                        <th>Total Amount</th>
                                        <th>Payment Status</th>
                                        <th>Booking Status</th>
                                    </tr>
                                </thead>


                                <tbody>
                                    @php $firstRow = true; @endphp
                                    @foreach ($group as $booking)
                                        <tr>
                                            <td>{{ $booking->id }}</td>
                                            <td>{{ $booking->customer->customer_name }}</td>
                                            <td>{{ $booking->room->room_number }}</td>
                                            <td>{{ $booking->check_in_date }}</td>

                                            <td>
                                                {{ \Carbon\Carbon::parse($booking->check_out_date)->subDay()->toDateString() }}
                                            </td>
                                            <td>
                                                {{ \Carbon\Carbon::parse($booking->check_out_date)->toDateString() }}
                                            </td>
                                            <td>{{ $booking->total_days }}</td>
                                            <td>{{ $booking->total_amount }}</td>

                                            @if ($firstRow)
                                                <td rowspan="{{ count($group) }}">
                                                    <span
                                                        class="badge bg-{{ $booking->payment_status == '1' ? 'success' : 'warning' }}">
                                                        {{ $booking->payment_status == 1 ? 'Paid' : 'Due' }}
                                                    </span>
                                                </td>

                                                {{-- Booking Status Dropdown --}}
                                                <td rowspan="{{ count($group) }}">
                                                    @php
                                                        $statusColors = [
                                                            0 => 'text-warning', // Pending (Yellow)
                                                            1 => 'text-primary', // Approved (Blue)
                                                            2 => 'text-danger', // Canceled (Red)
                                                        ];
                                                    @endphp

                                                    <select
                                                        class="form-select badge bg-{{ $statusColors[$booking->Booking_status] ?? 'secondary' }} booking-status {{ $statusColors[$booking->Booking_status] }}"
                                                        data-booking-id="{{ $booking->id }}">
                                                        <option class="text-warning " value="0"
                                                            {{ $booking->Booking_status == 0 ? 'selected' : '' }}>
                                                            Pending</option>
                                                        <option class="text-primary" value="1"
                                                            {{ $booking->Booking_status == 1 ? 'selected' : '' }}>
                                                            Approved</option>
                                                        <option class="text-danger" value="2"
                                                            {{ $booking->Booking_status == 2 ? 'selected' : '' }}>
                                                            Canceled</option>
                                                    </select>
                                                </td>
                                            @endif
                                        </tr>
                                        @php $firstRow = false; @endphp
                                    @endforeach
                                </tbody>


                            </table>
                        @endforeach




                    </table>
                    <!--end::Table-->
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Products-->
        </div>
        <!--end::Content container-->
    </div>
    <!--end::Content-->




</x-default-layout>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll('.booking-status').forEach(function(dropdown) {
            dropdown.addEventListener('change', function() {
                let bookingId = this.getAttribute('data-booking-id');
                let newStatus = this.value;

                fetch("{{ route('update.booking.status') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                            "X-Requested-With": "XMLHttpRequest"
                        },
                        body: JSON.stringify({
                            id: bookingId,
                            booking_status: newStatus
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        console.log("Server Response:", data); // ✅ Debugging output
                        if (data.success) {
                            alert("Booking status updated successfully!");
                        } else {
                            alert("Failed to update booking status: " + data.message);
                        }
                    })
                    .catch(error => console.error("Fetch Error:", error));
            });
        });
    });



    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll('.booking-status').forEach(function(dropdown) {
            function updateColor(dropdown) {
                // Remove all text color classes
                dropdown.classList.remove('text-warning', 'text-primary', 'text-danger');

                // Add new color based on selected value
                let selectedValue = dropdown.value;
                if (selectedValue == "0") {
                    dropdown.classList.add('text-warning'); // Pending
                } else if (selectedValue == "1") {
                    dropdown.classList.add('text-primary'); // Approved
                } else if (selectedValue == "2") {
                    dropdown.classList.add('text-danger'); // Canceled
                }
            }

            // Initialize color on page load
            updateColor(dropdown);

            // Change color dynamically when selection changes
            dropdown.addEventListener('change', function() {
                updateColor(dropdown);
            });
        });
    });
</script>

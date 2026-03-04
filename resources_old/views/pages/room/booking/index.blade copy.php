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
                        @can('create customer')
                            <a href="{{ route('booking.create') }}" class="btn btn-sm btn-primary">New Booking</a>
                        @endcan
                    </div>
                    <!--end::Card toolbar-->
                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body pt-0">
                    <!--begin::Table-->
                    <table class="table table-striped table-bordered align-middle table-row-dashed fs-7 gy-5 mb-0"
                        id="kt_ecommerce_report_customer_orders_table">
                        <!--begin::Table head-->
                        <thead>
                            <!--begin::Table row-->
                            <tr class="text-start fs-7 text-uppercase gs-0">
                                <th class="min-w-50px">ID</th>
                                <th class="min-w-100px">Customer Name</th>
                                <th class="min-w-100px">Floor</th>
                                <th class="min-w-100px">Room No</th>
                                <th class="min-w-100px">Check-In Date</th>
                                <th class="min-w-100px">Check-Out Date</th>
                                <th class="min-w-100px">Total Days</th>
                                <th class="min-w-100px">Total Amount</th>
                                {{-- <th class="min-w-100px">Paid Amount</th> --}}
                                {{-- <th class="min-w-100px">Due Amount</th> --}}
                                <th class="min-w-100px">Payment Status</th>
                                <th class="min-w-100px">Status</th>
                                <th class="min-w-100px">Payment Details</th>
                                <th class="min-w-100px">Booking Status</th>
                                
                                {{-- <th class="text-end min-w-100px">Actions</th> --}}
                            </tr>

                            <!--end::Table row-->
                        </thead>
                        <!--end::Table head-->
                        <!--begin::Table body-->
                        <tbody class="fw-semibold text-gray-700">
                            @php
                                $sl = 1;
                            @endphp
                            @foreach ($booking as $bookings)
                                <tr>
                                    <td>
                                        {{ $sl }}
                                        {{-- {{ $bookings->id }} --}}
                                    </td>
                                    <td>{{ $bookings->customer->customer_name ?? 'N/A' }}</td>
                                    <td>{{ $bookings->room->floor ?? 'N/A'}}</td>
                                    <td>{{ $bookings->room->room_number ?? 'N/A' }}</td>
                                    <td>{{ $bookings->check_in_date ?? 'N/A' }}</td>
                                    <td>{{ $bookings->check_out_date ?? 'N/A' }}</td>
                                    <td>{{ $bookings->total_days ?? 'N/A' }}</td>
                                    <td>{{ $bookings->total_amount ?? 'N/A' }}</td>
                                    {{-- <td>{{ $bookings->paid_amount }}</td> --}}
                                    {{-- <td>{{ $bookings->total_amount - $bookings->paid_amount }}</td> --}}
                                    <td>
                                        <span
                                            class="{{ $bookings->payment_status == 1 ? 'text-success' : 'text-danger' }}">
                                            {{ $bookings->payment_status == 1 ? 'Paid' : 'Due' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div
                                            class="badge badge-light-{{ $bookings->status == 1 ? 'success' : 'info' }}">
                                            {{ $bookings->status == 1 ? 'Active' : 'Disabled' }}
                                        </div>
                                    </td>
                                    <td>

                                        {{-- <button type="button" class="btn btn-sm btn-success p-2"
                                            data-productType="{{ json_encode($bookings) }}"
                                            onclick="openEditModalProductType(this)">
                                            <i class="fa fa-pencil text-white px-2"></i>
                                        </button> --}}
                                        <form method="POST" action="{{ route('room_type.destroy', $bookings->id) }}"
                                            class="delete-product-type-form">
                                            {{ csrf_field() }}
                                            {{ method_field('DELETE') }}

                                            {{-- <button type="button" class="btn btn-sm btn-success p-2"
                                                data-productType="{{ json_encode($room->id) }}"
                                                onclick="openEditModalProductType(this)">
                                                <i class="fa fa-pencil text-white px-2"></i>
                                            </button> --}}

                                            <a class="btn btn-sm btn-success p-2"
                                                href="{{ route('payment.edit', $bookings->id) }}"
                                                class="menu-link px-3"><i class="fa fa-pencil text-white px-2"></i></a>

                                            {{-- <button type="button"
                                                class="btn btn-sm btn-danger p-2 product-type-destroy"
                                                onclick="confirmDelete(this)">
                                                <i class="fa fa-trash text-white px-2"></i>
                                            </button> --}}
                                        </form>



                                    </td>
                                    <td>
                                        <select class="status-select" data-id="{{ $bookings->id }}">
                                            <option value="Pending"
                                                {{ $bookings->Booking_status == 'Pending' ? 'selected' : '' }}>Pending
                                            </option>
                                            <option value="Approved"
                                                {{ $bookings->Booking_status == 'Approved' ? 'selected' : '' }}>
                                                Approved</option>
                                            <option value="Cancelled"
                                                {{ $bookings->Booking_status == 'Cancelled' ? 'selected' : '' }}>
                                                Cancelled</option>
                                        </select>
                                    </td>

                                </tr>
                                @php
                                    $sl++;
                                @endphp
                            @endforeach
                        </tbody>
                        <!--end::Table body-->
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


    <!--begin::Modal - Create App-->
    <div class="modal fade" id="kt_modal_edit_card" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <!--begin::Modal content-->
            <div class="modal-content">
                <!--begin::Modal header-->
                <div class="modal-header">
                    <!--begin::Modal title-->
                    <h2>Payment Receive</h2>
                    <!--end::Modal title-->
                    <!--begin::Close-->
                    <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                        <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                        <span class="svg-icon svg-icon-1">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1"
                                    transform="rotate(-45 6 17.3137)" fill="currentColor"></rect>
                                <rect x="7.41422" y="6" width="16" height="2" rx="1"
                                    transform="rotate(45 7.41422 6)" fill="currentColor"></rect>
                            </svg>
                        </span>
                        <!--end::Svg Icon-->
                    </div>
                    <!--end::Close-->
                </div>
                <!--end::Modal header-->
                <!--begin::Modal body-->
                <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                    <!--begin::Form-->
                    <form id="kt_modal_edit_card_form_room_type" class="form fv-plugins-bootstrap5 fv-plugins-framework"
                        method="POST" action="{{ route('payment.store') }}" enctype="multipart/form-data">
                        @csrf
                        {{-- @method('PUT') --}}
                        <!--begin::Input group-->




                        <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                            <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                <span class="required">Amount</span>
                            </label>
                            <input type="text" class="form-control form-control-sm" name="amount">
                            <div class="fv-plugins-message-container invalid-feedback"></div>
                        </div>

                        <div class="text-center pt-15">
                            <a href="{{ route('booking.index') }}" id="kt_modal_new_card_cancel"
                                class="btn btn-light me-5">Cancel</a>

                            <button type="submit" id="kt_modal_new_card_submit" class="btn btn-primary">
                                <span class="indicator-label">Save</span>
                                <span class="indicator-progress">Please wait...
                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                            </button>
                        </div>

                        <!--end::Actions-->
                    </form>
                    <!--end::Form-->
                </div>
                <!--end::Modal body-->
            </div>
            <!--end::Modal content-->
        </div>
    </div>
    <!--end::Modal - Create App-->


</x-default-layout>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        // Function to update the color of the dropdown
        function updateDropdownColor(selectElement) {
            const status = $(selectElement).val();
            $(selectElement).removeClass('pending approved cancelled'); // Remove all status classes

            if (status === 'Pending') {
                $(selectElement).addClass('pending');
            } else if (status === 'Approved') {
                $(selectElement).addClass('approved');
            } else if (status === 'Cancelled') {
                $(selectElement).addClass('cancelled');
            }
        }

        // Initialize colors for all dropdowns
        $('.status-select').each(function() {
            updateDropdownColor(this);
        });

        // Handle change event
        $('.status-select').change(function() {
            let bookingId = $(this).data('id');
            let newStatus = $(this).val();

            // Update color dynamically
            updateDropdownColor(this);

            // Send the AJAX request to update the status in the database
            $.ajax({
                url: '{{ route('bookings.updateStatus') }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    id: bookingId,
                    status: newStatus,
                },
                success: function(response) {
                    // Show success message with SweetAlert2
                    Swal.fire({
                        icon: 'success',
                        title: 'Status Updated!',
                        text: response.message,
                        timer: 2000, // Auto-close after 2 seconds
                        showConfirmButton: false
                    });
                },
                error: function(xhr) {
                    // Show error message with SweetAlert2
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Something went wrong. Please try again.',
                    });
                }
            });
        });
    });
</script>

{{-- <script type="text/javascript">
    // product type 
    function openEditModalProductType(button) {

        const paymentData = JSON.parse(button.getAttribute('data-productType'));

        const formAction = $('#kt_modal_edit_card').find('#kt_modal_edit_card_form_room_type').attr('action');
        const paymentId = paymentData.id;
        const updatedFormAction = formAction.replace(':paymentId', paymentId);
        $('#kt_modal_edit_card').find('#kt_modal_edit_card_form_room_type').attr('action', updatedFormAction);

        $('#kt_modal_edit_card').find('input[name="amount"]').val(paymentData.amount);

        $('#kt_modal_edit_card').modal('show');
    }

    // SweetAlert2 Confarm message after form on click ========= 
    document.getElementById("kt_modal_new_card_submit_store").addEventListener('click', function(event) {
        ConfarmMsg("kt_modal_new_card_form", "Are You sure.. Want to Save?", "save", event)
    });
    document.getElementById("kt_modal_new_card_submit").addEventListener('click', function(event) {
        ConfarmMsg("kt_modal_edit_card_form_room_type", "Are You sure.. Want to Update?", "Update", event)
    });
</script> --}}

<x-default-layout>
    <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
        <!--begin::Content wrapper-->
        <div class="d-flex flex-column flex-column-fluid">
            <!--begin::Toolbar-->
            <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
                <!--begin::Toolbar container-->
                <div id="kt_app_toolbar_container" class="app-container d-flex flex-stack">
                    <!--begin::Page title-->
                    <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                        <!--begin::Title-->
                        <h3 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                            New Booking</h3>
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
                    <form id="kt_ecommerce_add_category_form"
                        class="form d-flex flex-column flex-lg-row fv-plugins-bootstrap5 fv-plugins-framework"
                        method="POST" action="{{ route('booking_store') }}" enctype="multipart/form-data">
                        @csrf

                        <!--begin::Main column-->
                        <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
                            <!--begin::General options-->
                            <div class="card card-flush py-4">
                                <!--begin::Card header-->
                                {{-- <div class="card-header">
                                    <div class="card-title">
                                        <h2>General</h2>
                                    </div>
                                </div> --}}
                                <!--end::Card header-->
                                <div class="row">

                                    <div class="col-12 col-md-6">
                                        <div class="card-body pt-0 pb-3">
                                            <div class="fv-row fv-plugins-icon-container">
                                                <label class="required form-label">Customer Type</label>
                                                <select class="form-select form-select-sm" data-control="select2"
                                                    name="customer_type" required>
                                                    <option value="">Select Customer Type</option>
                                                    @foreach ($customerTypes as $key => $value)
                                                        <option value="{{ $key }}">{{ $value }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- <div class="col-12 col-md-6">
                                        <div class="card-body pt-0 pb-3">
                                            <div class="fv-row fv-plugins-icon-container">
                                                <label class="required form-label">Room</label>
                                                <select class="form-select form-select-sm" data-control="select2" name="room_id" required>
                                                    <option value="">Select Room</option>
                                                    @foreach ($roomList as $key => $value)
                                                        <option value="{{ $key }}">{{ $value }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div> --}}


                                    <div class="col-12 col-md-6">
                                        <div class="card-body pt-0 pb-3">
                                            <div class="fv-row fv-plugins-icon-container">
                                                <label class="required form-label">Room</label>
                                                <select class="form-select form-select-sm" data-control="select2"
                                                    name="room_id" required disabled>
                                                    <option value="">Select Room</option>
                                                    @foreach ($roomList as $key => $value)
                                                        <option value="{{ $key }}"
                                                            {{ $key == $selectedRoomId ? 'selected' : '' }}>
                                                            {{ $value }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <input type="hidden" name="room_id" value="{{ $selectedRoomId }}">
                                            </div>
                                        </div>
                                    </div>



                                    <div class="col-12 col-md-6">
                                        <div class="card-body pt-0 pb-3">
                                            <div class="fv-row fv-plugins-icon-container">
                                                <label class="required form-label">Mobile</label>
                                                <input type="text" id="customer_mobile" name="customer_mobile"
                                                    class="form-control form-control-sm mb-2"
                                                    placeholder="Customer Mobile" value="" required>
                                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="col-12 col-md-6">
                                        <div class="card-body pt-0 pb-3">
                                            <div class="fv-row fv-plugins-icon-container">
                                                <label class="form-label">Customer NID</label>
                                                <input type="text" id="nid_number" name="nid_number"
                                                    class="form-control form-control-sm mb-2" placeholder="Customer NID"
                                                    value="">
                                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- <div class="col-12 col-md-6">
                                        <div class="card-body pt-0 pb-3">
                                            <div class="fv-row fv-plugins-icon-container">
                                                <label class="form-label">Gender</label>
                                                <select class="form-select form-select-sm" data-control="select2" name="customer_gender">
                                                    <option value="" selected>--Select Gender--</option>
                                                    <option value="1">Male</option>
                                                    <option value="0">Female</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div> --}}

                                    <div class="col-12 col-md-6">
                                        <div class="card-body pt-0 pb-3">
                                            <div class="fv-row fv-plugins-icon-container">
                                                <label class="required form-label">Name</label>
                                                <input type="text" id="customer_name" name="customer_name"
                                                    class="form-control form-control-sm mb-2"
                                                    placeholder="Customer Name" value="" required>
                                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="col-12 col-md-6">
                                        <div class="card-body pt-0 pb-3">
                                            <div class="fv-row fv-plugins-icon-container">
                                                <label class="required form-label">Customer Address</label>
                                                <input type="text" id="customer_address" name="customer_address"
                                                    class="form-control form-control-sm mb-2"
                                                    placeholder="Customer Address" value="" required>
                                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <div class="card-body pt-0 pb-3">
                                            <div class="fv-row fv-plugins-icon-container">
                                                <label class=" form-label">Check In Date</label>
                                                <input type="date" name="check_in_date"
                                                    class="form-control form-control-sm mb-2"
                                                    placeholder="Check In Date" value="{{ $date }}" disabled>
                                                <input type="hidden" name="check_in_date" id="check_in_date"
                                                    class="form-control form-control-sm mb-2"
                                                    placeholder="Check In Date" value="{{ $date }}">
                                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <div class="card-body pt-0 pb-3">
                                            <div class="fv-row fv-plugins-icon-container">
                                                <label class=" form-label">Check Out Date</label>
                                                <input type="hidden" name="check_out_date"
                                                    class="form-control form-control-sm mb-2"
                                                    placeholder="Check Out Date"
                                                    value="{{ \Carbon\Carbon::parse($date)->addDay()->toDateString() }}"
                                                    disabled>
                                                <input type="date" name="check_out_date" id="check_out_date"
                                                    class="form-control form-control-sm mb-2"
                                                    placeholder="Check Out Date"
                                                    value="{{ \Carbon\Carbon::parse($date)->addDay()->toDateString() }}">
                                                <input type="hidden" name="check_out_date_ava"
                                                    id="check_out_date_ava" class="form-control form-control-sm mb-2"
                                                    placeholder="Check Out Date"
                                                    value="{{ \Carbon\Carbon::parse($date)->addDay(6)->toDateString() }}"
                                                    disabled>
                                                <span id="room_booking_check"></span>
                                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <div class="card-body pt-0 pb-3">
                                            <div class="fv-row fv-plugins-icon-container">
                                                <label class="required form-label">Paid Amount</label>
                                                <input type="text" id="paid_amount" name="paid_amount"
                                                    class="form-control form-control-sm mb-2"
                                                    placeholder="Paid Amount" value="" required>
                                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- <div class="col-12 col-md-6">
                                        <div class="card-body pt-0 pb-3">
                                            <div class="fv-row fv-plugins-icon-container">
                                                <label class=" form-label">Payment Status</label>
                                                <select class="form-select form-select-sm" data-control="select2"
                                                    name="payment_status">
                                                    <option value="">--Payment Status--</option>
                                                    <option value="1" selected>Paid</option>
                                                    <option value="0">Due</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div> --}}

                                    {{-- <div class="col-12 col-md-6">
                                        <div class="card-body pt-0 pb-3">
                                            <div class="fv-row fv-plugins-icon-container">
                                                <label class=" form-label">Status</label>
                                                <select class="form-select form-select-sm" data-control="select2"
                                                    name="status">
                                                    <option value="">--Select Status--</option>
                                                    <option value="1" selected>Active</option>
                                                    <option value="0">Disable</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div> --}}

                                </div>

                            </div>
                            <!--end::General options-->
                            <div class="d-flex justify-content-end mb-10">
                                <!--begin::Button-->
                                <a href="{{ route('booking.create') }}" id="kt_ecommerce_add_product_cancel"
                                    class="btn btn-sm btn-success me-5">Cancel</a>
                                <!--end::Button-->
                                <!--begin::Button-->
                                <button type="submit" id="kt_ecommerce_add_category_submit"
                                    class="btn btn-sm btn-primary">
                                    <span class="indicator-label">Save</span>
                                    <span class="indicator-progress">Please wait...
                                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                </button>
                                <!--end::Button-->
                            </div>
                        </div>
                        <!--end::Main column-->
                    </form>
                </div>
                <!--end::Content container-->
            </div>
            <!--end::Content-->
        </div>
        <!--end::Content wrapper-->


    </div>
</x-default-layout>




<script type="text/javascript">
    $(document).ready(function() {

        $('#customer_mobile').on('change', function() {
            let customer_mobile = $(this).val();
            // console.log(customer_mobile);
            if (customer_mobile) {
                let url = '{{ route('get_customer', ['customer_mobile' => ':customer_mobile']) }}';
                url = url.replace(':customer_mobile', customer_mobile);
                console.log(url);
                $.ajax({
                    url: url,
                    type: "GET",
                    // data: {
                    //     "_token": "{{ csrf_token() }}"
                    // },
                    dataType: "json",
                    success: function(data) {
                        console.log('data:', data);
                        if (data) {
                            console.log(data.customer_name);
                            $('#customer_name').val(data.customer_name);
                            $('#nid_number').val(data.nid_number);
                            $('#customer_address').val(data.customer_address);
                        } else {
                            $('#jsdataerror').text('Not Found');
                        }
                    },
                    error: function() {
                        $('#jsdataerror').text('Error fetching data');
                    }
                });
            } else {
                $('#jsdataerror').text('Not Found');
            }
        });


        function CallBack(pdfdata) {
            let url;
            var id = {!! json_encode($selectedRoomId) !!};
            // var id = '4';
            var startDate = $('#check_in_date').val();
            var endDate = $('#check_out_date').val();
            var endDate_2 = $('#check_out_date_ava').val();

            url =
                '{{ route('room_booking_search', ['id' => ':id', 'startDate' => ':startDate', 'endDate_2' => ':endDate_2']) }}';
            url = url.replace(':startDate', startDate);
            url = url.replace(':endDate_2', endDate_2);
            url = url.replace(':id', id);

            if (pdfdata == 'list') {
                $.ajax({
                    url: url,
                    type: 'GET',
                    data: {
                        "_token": "{{ csrf_token() }}",
                    },
                    dataType: 'json',
                    success: function(data) {
                        // Clear the existing content
                        $('#room_booking_check').empty();

                        console.log(data);

                        if (data.length > 0) {
                            // Wrap all booking cards inside a flex container
                            let flexContainer = $(
                                '<div class="d-flex flex-wrap gap-3 text-center"></div>'
                                ); // Using Bootstrap's flex utilities

                            $.each(data, function(key, value) {
                                let d = data[key];
                                // Define isBookedDisplay and actionButton
                                let isBookedDisplay = d.is_booked === 'Booked' ?
                                    `<span style="color: red;">${d.is_booked}</span>` :
                                    d.is_booked;

                                // Construct the booking card
                                let bookingCard =
                                    `<div class="card" style="width: 8rem;">
                                        <div class="">
                                            <p class="card-text">${d.date}</p>
                                            <p class="card-text">${isBookedDisplay}</p>
                                        </div>
                                    </div>`;

                                // Append the card to the flex container
                                flexContainer.append(bookingCard);
                            });

                            // Append the flex container to the room_booking_check
                            $('#room_booking_check').append(flexContainer);
                        } else {
                            $('#jsdataerror').text('No records found');
                            console.log('No records found');
                        }
                    },
                });
            } else if (pdfdata == 'pdfurl') {
                $('#downloadPdf').attr('href', url);
            } else {
                $('#jsdataerror').text('Please select a date');
            }
        }


        CallBack("list");


    });






    $('#searchBtn').on('click', function() {
        CallBack("list");
    });

    $('#downloadPdf').on('click', function() {
        CallBack("pdfurl");
    });
</script>

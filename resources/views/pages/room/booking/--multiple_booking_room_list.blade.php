<x-default-layout>
    <style>
        .card .card-header {
            min-height: 40px;
        }

        .table> :not(caption)>*>* {
            padding: 0.3rem !important;
        }
    </style>

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
    </style>

    <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
        <!--begin::Content wrapper-->
        <div class="d-flex flex-column flex-column-fluid">

            <!--begin::Content-->
            <div id="kt_app_content" class="app-content flex-column-fluid">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!--begin::Content container-->
                <div id="kt_app_content_container" class="app-container">

                    <div class="d-flex flex-row">

                        {{-- Room list --}}
                        <!--begin::Main column-->
                        <div class="col-6 col-md-6 me-lg-10">
                            <!--begin::Toolbar-->
                            <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
                                <!--begin::Toolbar container-->
                                <div id="kt_app_toolbar_container" class="app-container d-flex flex-stack">
                                    <!--begin::Page title-->
                                    <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                                        <!--begin::Title-->
                                        <h1
                                            class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                                            Room List
                                        </h1>
                                    </div>
                                    <!--end::Page title-->
                                </div>
                                <!--end::Toolbar container-->
                            </div>
                            <!--end::Toolbar-->
                            <!--begin::General options-->
                            <div class="card card-flush py-4">
                                <div class="row">

                                    <div class="col-12 col-md-12">

                                        <div class="card-body pt-0 pb-2">
                                            <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                                                <!--begin::Card title-->
                                                {{-- <div class="card-title">
                                        <h3>New Booking</h3>
                                    </div> --}}
                                                <!--begin::Card toolbar-->

                                                <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                                                    <div class="form-group col-md-3">
                                                        <label for="order">{{ __('Start Date') }}</label>
                                                        <input id="start_date" type="date"
                                                            value="{{ date('Y-m-d') }}"
                                                            class="form-control form-control-sm form-control-solid"
                                                            id="" name="start_date" />
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                        <label for="order">{{ __('End Date') }}</label>
                                                        {{-- <input id="end_date" type="date" value="{{ date('Y-m-d') }}"
                                                            class="form-control form-control-sm form-control-solid"
                                                            id="" name="end_date" /> --}}
                                                        <input id="end_date" type="date"
                                                            value="{{ now()->addDays(29)->format('Y-m-d') }}"
                                                            class="form-control form-control-sm form-control-solid"
                                                            name="end_date" />

                                                    </div>
                                                </div>
                                                <div class="d-flex flex-row-reverse pt-0 pt-md-5">
                                                    <button type="submit" id="searchBtn"
                                                        class="btn btn-sm btn-primary px-5 mt-0">{{ __('Search') }}</button>
                                                </div>

                                            </div>

                                            <div class="row g-5 g-xl-8 px-10 py-10">
                                                <div class="container">
                                                    <div class="row gy-3" id="room_wise_booking_list_container">
                                                        <!-- Bookings will be dynamically appended here -->
                                                    </div>
                                                </div>
                                            </div>


                                        </div>

                                    </div>

                                </div>
                                <!--end::Main column-->
                            </div>
                        </div>
                        {{-- Room list end --}}


                        <div class="col-6 col-md-6 me-lg-10">

                            <!--begin::Toolbar-->
                            <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
                                <!--begin::Toolbar container-->
                                <div id="kt_app_toolbar_container" class="app-container d-flex flex-stack">
                                    <!--begin::Page title-->
                                    <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                                        <!--begin::Title-->
                                        <h1
                                            class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                                            Booking Details
                                        </h1>
                                    </div>
                                    <!--end::Page title-->
                                </div>
                                <!--end::Toolbar container-->
                            </div>
                            <!--end::Toolbar-->

                            <!--begin::General options-->
                            <div class="card card-flush py-4">
                                <div class="row">

                                    <form id="kt_ecommerce_booking_submit"
                                        class="form d-flex flex-column flex-lg-row fv-plugins-bootstrap5 fv-plugins-framework"
                                        method="POST" action="{{ route('multiple_booking_store') }}"
                                        enctype="multipart/form-data">

                                        @csrf

                                        <div class="col-12 col-md-12">
                                            <div class="card-body pt-0">
                                                <table id="booking_data"
                                                    class="table table-striped table-bordered table-hover">
                                                    <thead class="table-dark">
                                                        <tr>
                                                            <th scope="col">ID</th>
                                                            <th scope="col">Room No</th>
                                                            <th scope="col">Check In</th>
                                                            <th scope="col">Check End</th>
                                                            {{-- <th scope="col">Check Out</th> --}}
                                                            <th scope="col">Total Day</th>
                                                            <th scope="col">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>

                                                    </tbody>
                                                </table>

                                            </div>

                                        </div>

                                </div>
                                <!--end::Main column-->

                            </div>

                            <!--begin::Toolbar-->
                            <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
                                <!--begin::Toolbar container-->
                                <div id="kt_app_toolbar_container" class="app-container d-flex flex-stack">
                                    <!--begin::Page title-->
                                    <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                                        <!--begin::Title-->
                                        <h1
                                            class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                                            Customer Details
                                        </h1>
                                    </div>
                                    <!--end::Page title-->
                                </div>
                                <!--end::Toolbar container-->
                            </div>
                            <!--end::Toolbar-->

                            <!--begin::General options-->
                            <div class="card card-flush py-4">
                                <div class="row">

                                    <div class="col-12 col-md-12">

                                        <div class="card-body pt-0">

                                            <div class="fv-row fv-plugins-icon-container pb-5">
                                                <label class="required form-label">Customer Type</label>
                                                <select class="form-select form-select-sm" data-control="select2"
                                                    name="customer_type" required>
                                                    <option value="">Select Customer Type</option>
                                                    @foreach ($customerTypes as $key => $value)
                                                        <option value="{{ $key }}">{{ $value }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="fv-row fv-plugins-icon-container">
                                                <label class="required form-label">Customer Mobile</label>
                                                <input type="text" id="customer_mobile" name="customer_mobile"
                                                    class="form-control form-control-sm mb-2"
                                                    placeholder="Customer Mobile"
                                                    onkeyup="loadCustomerDetails(this.value);" value="" required>
                                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                            </div>

                                            <div class="fv-row fv-plugins-icon-container">
                                                <label class="required form-label">Customer Name</label>
                                                <input type="text" id="customer_name" name="customer_name"
                                                    class="form-control form-control-sm mb-2"
                                                    placeholder="Customer Name" value="" required>
                                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                            </div>


                                            <div class="fv-row fv-plugins-icon-container">
                                                <label class="form-label">Customer NID</label>
                                                <input type="text" id="nid_number" name="nid_number"
                                                    class="form-control form-control-sm mb-2"
                                                    placeholder="Customer NID" value="">
                                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                            </div>

                                            <div class="fv-row fv-plugins-icon-container">
                                                <label class="required form-label">Customer Address</label>
                                                <input type="text" id="customer_address" name="customer_address"
                                                    class="form-control form-control-sm mb-2"
                                                    placeholder="Customer Address" value="" required>
                                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                            </div>

                                            <div class="fv-row fv-plugins-icon-container">
                                                <label class="required form-label">Paid Amount</label>
                                                <input type="text" id="paid_amount" name="paid_amount"
                                                    class="form-control form-control-sm mb-2"
                                                    placeholder="Paid Amount" value="" required>
                                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                            </div>

                                            {{-- <div class="fv-row fv-plugins-icon-container">
                                                <label class=" form-label">Payment Status</label>
                                                <select class="form-select form-select-sm" data-control="select2"
                                                    name="payment_status">
                                                    <option value="">--Payment Status--</option>
                                                    <option value="1" selected>Paid</option>
                                                    <option value="0">Due</option>
                                                </select>
                                            </div> --}}

                                        </div>

                                    </div>

                                </div>
                                <!--end::Main column-->
                            </div>



                            <div class="d-flex justify-content-end mb-10 mt-10">
                                <!--begin::Button-->
                                <a href="{{ route('booking.create') }}" id="kt_ecommerce_add_product_cancel"
                                    class="btn btn-sm btn-success me-5">Cancel</a>
                                <!--end::Button-->
                                <!--begin::Button-->
                                <button type="submit" id="kt_ecommerce_booking_submit"
                                    class="btn btn-sm btn-primary">
                                    <span class="indicator-label">Save</span>
                                    <span class="indicator-progress">Please wait...
                                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                </button>
                                <!--end::Button-->
                            </div>



                        </div>

                        </form>
                    </div>


                </div>



            </div>
            <!--end::Content container-->


        </div>
        <!--end::Content-->

    </div>
    <!--end::Content wrapper-->


</x-default-layout>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', () => {
        const dateInput = document.getElementById('start_date');
        const today = new Date().toISOString().split('T')[0];
        dateInput.setAttribute('min', today);
    });

    document.addEventListener('DOMContentLoaded', () => {
        const dateInput = document.getElementById('end_date');
        const today = new Date().toISOString().split('T')[0];
        dateInput.setAttribute('min', today);
    });


    // get startDate and endDate
    var startDate = 0;
    var endDate = 0;

    // start_date on change action
    $('#start_date').on('change', function() {
        startDate = $('#start_date').val();
        endDate = $('#end_date').val();
        const start_date = new Date(startDate);
        const end_date = new Date(endDate);
        // Check if the start date is after the end date
        if (start_date > end_date) {
            alert('The start date cannot be after the end date.');
            $('#start_date').val(endDate);
            startDate = $('#start_date').val();
        }
        // InvoiceList Call Data
        InvoiceList(startDate, endDate);
    });

    // end_date on change action
    $('#end_date').on('change', function() {
        startDate = $('#start_date').val();
        endDate = $('#end_date').val();
        const start_date = new Date(startDate);
        const end_date = new Date(endDate);
        // Check if the start date is after the end date
        if (start_date > end_date) {
            alert('The end date cannot be before the start date.');
            $('#end_date').val(startDate);
            endDate = $('#start_date').val();
        }
        // InvoiceList Call Data
        InvoiceList(startDate, endDate);
    });
</script>

<script>
    // Assuming d.date contains the date in 'YYYY-MM-DD' format
    const rawDate = d.date; // Example: '2025-01-14'
    if (rawDate) {
        const dateObj = new Date(rawDate);
        const options = {
            day: 'numeric',
            month: 'long',
            year: 'numeric'
        };
        const formattedDate = dateObj.toLocaleDateString('en-US', options);
        document.getElementById('formattedDate').textContent = formattedDate;
    } else {
        console.error('Date is invalid or undefined');
    }
</script>

<script type="text/javascript">
    var name = 'Perfume PLC';
    name += `\n Finish Good Wise Sales Report`;
    var reportTittle = name;

    var dataTableOptions = {
        dom: '<"top"fB>rt<"bottom"lip>',
        buttons: [{
            extend: 'collection',
            text: 'Export',
            className: 'btn btn-sm btn-light-primary',
            buttons: [{
                    extend: 'excel',
                    text: 'Excel',
                    title: `${reportTittle}`,
                    className: 'btn btn-sm btn-light-primary',
                },
                {
                    extend: 'copy',
                    text: 'Copy',
                    title: `${reportTittle}`,
                    className: 'btn btn-sm btn-light-primary',
                },
                {
                    extend: 'csv',
                    text: 'CSV',
                    title: `${reportTittle}`,
                    className: 'btn btn-sm btn-light-primary',
                },
                {
                    extend: 'pdf',
                    text: 'PDF',
                    title: `${reportTittle}`,
                    className: 'btn btn-sm btn-light-primary',
                },
                {
                    extend: 'print',
                    text: 'Print',
                    title: `${reportTittle}`,
                    className: 'btn btn-sm btn-light-primary',
                },
            ],
        }, ],
        paging: true,
        ordering: false,
        searching: true,
        responsive: false,
        lengthMenu: [10, 25, 50, 100],
        pageLength: 50,
        language: {
            lengthMenu: 'Show _MENU_ entries',
            search: 'Search:',
            paginate: {
                first: 'First',
                last: 'Last',
                next: 'Next',
                previous: 'Previous',
            },
        },
    };

    function CallBack(pdfdata) {
        let url;
        var startDate = $('#start_date').val();
        var endDate = $('#end_date').val();

        url = '{{ route('booking_search', ['startDate' => ':startDate', 'endDate' => ':endDate']) }}';
        url = url.replace(':startDate', startDate);
        url = url.replace(':endDate', endDate);

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
                    $('#room_wise_booking_list_container').empty();

                    if (data.length > 0) {
                        let displayedDates = new Set(); // Keep track of displayed dates

                        $.each(data, function(key, value) {
                            let d = data[key];

                            const rawDate = d.date;
                            const dateObj = new Date(rawDate);
                            const options = {
                                day: 'numeric',
                                month: 'long',
                                year: 'numeric'
                            };
                            const formattedDate = dateObj.toLocaleDateString('en-US', options);
                            // document.getElementById('formattedDate').textContent = formattedDate;

                            // If the date hasn't been displayed yet, add a header for the date
                            if (!displayedDates.has(d.date)) {
                                displayedDates.add(d.date); // Mark this date as displayed

                                let dateHeader = `
                                <div class="col-12">
                                    <h4 class="text-center" style="color: green;">${formattedDate}</h4>
                                </div>
                            `;
                                $('#room_wise_booking_list_container').append(dateHeader);
                            }

                            // Construct the dynamic route for 'booking_add'
                            let bookingUrl =
                                `{{ route('booking_add', ['id' => ':id', 'date' => ':date']) }}`;
                            bookingUrl = bookingUrl.replace(':id', d.room_id).replace(':date', d
                                .date);

                            let roomNumber = d.is_booked === 'Booked' ?
                                `<p style="color: white; margin-bottom: 0;">${d.room_number}</p>` :
                                `<p style="margin-bottom: 0;">${d.room_number}</p>`;

                            // Define isBookedDisplay and actionButton
                            let isBookedDisplay = d.is_booked === 'Booked' ?
                                `<span style="color: red;">${d.is_booked}</span>` :
                                d.is_booked;

                            // let actionButton = d.is_booked === 'Booked' ?
                            //     `<button class="btn btn-primary btn-sm" style="color: red; background-color: red;" disabled>${roomNumber}</button>` :
                            //     `<a href="${bookingUrl}" class="btn btn-primary btn-sm">${roomNumber}</a>`;

                            let actionButton = d.is_booked === 'Booked' ?
                                `<button class="btn btn-primary btn-sm" style="background-color: red;" disabled>${roomNumber}</button>` :
                                `<a class="btn btn-primary btn-sm" onclick="addBooking(${d.room_id}, '${d.room_number}', '${d.date}', '${d.date}')">${roomNumber}</a>`;


                            // Construct the booking card
                            let bookingCard = `
                            <div class="col-md-1 mx-5">
                                ${actionButton}
                            </div>
                            `;
                            // Append the card to the container
                            $('#room_wise_booking_list_container').append(bookingCard);
                        });
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



    function addBooking(id, roomNumber, checkIn, checkOut) {
        let rowExists = false;
        let existingRow;

        // Loop through the table to check if the room already exists
        $('#booking_data tbody tr').each(function() {
            let existingRoom = $(this).find("td:eq(1)").text(); // Get Room Number
            if (existingRoom === roomNumber) {
                rowExists = true;
                existingRow = $(this);
                return false; // Exit loop early
            }
        });

        // Calculate next day from check-in
        let checkInDate = new Date(checkIn);
        checkInDate.setDate(checkInDate.getDate() + 1);
        let nextDay = formatDate(checkInDate);

        if (rowExists) {
            // If room exists, update the check-in and check-out dates if needed
            let existingCheckIn = formatDate(existingRow.find("td:eq(2)").text());
            let existingCheckOut = formatDate(existingRow.find(".check-out-date").val());

            // Update only if the new dates extend the existing stay
            if (new Date(checkIn) < new Date(existingCheckIn)) {
                existingRow.find("td:eq(2)").text(checkIn); // Update Check-In Date
            }

            if (new Date(checkOut) > new Date(existingCheckOut)) {
                existingRow.find(".check-out-date").val(checkOut); // Update Check-Out Date
            }

            // Update the next-day column dynamically
            let existingNextDay = formatDate(existingRow.find("td:eq(4)").text());
            if (new Date(nextDay) !== new Date(existingNextDay)) {
                existingRow.find("td:eq(4)").text(nextDay); // Update Next-Day Column
            }

            // Update the number of days
            updateDayCount(existingRow);
        } else {
            // If the room is not in the table, add a new row
            let newRow = `
        <tr data-id="${id}">
            <td>${id}<input type="hidden" name="table_room_id[]" value="${id}"></td>
            <td>${roomNumber}<input type="hidden" name="table_room_number[]" value="${roomNumber}"></td>
            <td>${formatDate(checkIn)}<input type="hidden" name="table_check_in_date[]" value="${formatDate(checkIn)}"></td>
            <td><input type="date" class="form-control form-control-sm check-out-date" name="table_check_out_date[]" value="${checkOut}" data-room-id="${id}" data-check-in="${checkIn}"></td>
            <td class="day-count">${calculateDays(checkIn, checkOut)}</td>
            <td><button class="btn btn-sm btn-danger delete-booking">Delete</button></td>
            </tr>
            `;
            $('#booking_data tbody').append(newRow);
        }
    }
    
    // <td><input type="date" class="org-check-out-date" name="" value="${nextDay}"></td>


    // Function to calculate the number of days between check-in and check-out
    // function calculateDays(start, end) {
    //     let startDate = new Date(start);
    //     let endDate = new Date(end);

    //     // Normalize time to midnight (00:00:00) to avoid fractional day differences
    //     startDate.setHours(0, 0, 0, 0);
    //     endDate.setHours(0, 0, 0, 0);

    //     let diffInTime = endDate - startDate;
    //     let diffInDays = diffInTime / (1000 * 60 * 60 * 24);
    //     // Ensures a minimum of 1 day
    //     return Math.max(1, Math.round(diffInDays));
    // }

    function calculateDays(start, end) {
        let startDate = new Date(Date.parse(start));
        let endDate = new Date(Date.parse(end));

        // Normalize time to midnight (00:00:00) to avoid fractional day differences
        startDate.setHours(0, 0, 0, 0);
        endDate.setHours(0, 0, 0, 0);

        let diffInTime = endDate - startDate;
        let diffInDays = diffInTime / (1000 * 60 * 60 * 24);

        // Ensures a minimum of 1 day
        return Math.max(1, Math.round(diffInDays + 1));
    }




    // Function to update day count when check-out date changes
    function updateDayCount(row) {
        let checkIn = row.find("td:eq(2)").text();
        let checkOut = row.find(".check-out-date").val();
        row.find(".day-count").text(calculateDays(checkIn, checkOut));
    }

    function addDays(date, days) {
        let result = new Date(date);
        result.setDate(result.getDate() + days);
        return result;
    }

    function formatDate(date) {
        let d = new Date(date);
        let month = String(d.getMonth() + 1).padStart(2, '0');
        let day = String(d.getDate()).padStart(2, '0');
        let year = d.getFullYear();
        return `${month}/${day}/${year}`;
    }



    // Event listener for check-out date changes
    $(document).on('change', '.check-out-date', function() {
        let row = $(this).closest('tr');
        updateDayCount(row);
    });

    // Event listener to delete a row
    $(document).on('click', '.delete-booking', function() {
        $(this).closest('tr').remove();
    });

    $(document).on('change', '.check-out-date', function bookingDetails() {
        let newCheckOut = $(this).val();
        let checkIn = $(this).data('check-in');
        let roomId = $(this).data('room-id');
        let row = $(this).closest('tr');

        // Validate check-out date (must be after check-in)
        if (new Date(newCheckOut) <= new Date(checkIn)) {
            alert("❌ Check-Out date must be after Check-In date.");
            $(this).val(checkIn);
            return;
        }

        // Construct AJAX URL dynamically
        let url =
            "{{ route('room_booking_search', ['id' => '__ID__', 'startDate' => '__START__', 'endDate_2' => '__END__']) }}"
            .replace('__ID__', roomId)
            .replace('__START__', checkIn)
            .replace('__END__', newCheckOut);

        // Check room availability via AJAX
        $.ajax({
            url: url,
            type: 'GET',
            success: function(response) {
                console.log(response);

                // Check if any date in the response is marked as "Booked"
                let isConflict = response.some(day => day.is_booked === "Booked");

                if (isConflict) {
                    let bookedDates = response
                        .filter(day => day.is_booked === "Booked")
                        .map(day => day.date)
                        .join(', ');

                    alert(
                        `❌ Room is NOT available for the selected dates. \nBooked dates: ${bookedDates}`
                    );

                    // Reset check-out date to previous value

                    $(row).find('.check-out-date').val(checkIn);
                    // $(row).find("td:eq(4)").text(newCheckOut);
                   

                    // let checkIn = new Date($(row).find('.check-in-date').val());

                    // if (!isNaN(checkIn.getTime())) { // Ensure checkIn is a valid date
                    //     let checkOut = addDays(checkIn, 1);
                    //     $(row).find('.check-out-date').val(checkOut.toISOString().split('T')[
                    //         0]); // Format as YYYY-MM-DD
                    // }

                    updateDayCount(row);
                } else {
                    // alert("✅ Room is available for the selected dates.");
                }

            },
            error: function() {
                alert("⚠️ Error checking room availability.");
                $(row).find('.check-out-date').val(checkIn);
            }
        });
    });


    $('#searchBtn').on('click', function() {
        CallBack("list");
    });

    $('#downloadPdf').on('click', function() {
        CallBack("pdfurl");
    });


    $('#kt_ecommerce_booking_submit').submit(function(e) {
        e.preventDefault(); // Prevent default submission for testing
        console.log($(this).serializeArray()); // Check if data is captured
        this.submit(); // Uncomment after confirming data is captured
    });


    // $(document).ready(function() {

    //     $('#customer_mobile').on('change', function() {
    //         let customer_mobile = $(this).val();
    //         // console.log(customer_mobile);
    //         if (customer_mobile) {
    //             let url = '{{ route('get_customer', ['customer_mobile' => ':customer_mobile']) }}';
    //             url = url.replace(':customer_mobile', customer_mobile);
    //             console.log(url);
    //             $.ajax({
    //                 url: url,
    //                 type: "GET",
    //                 // data: {
    //                 //     "_token": "{{ csrf_token() }}"
    //                 // },
    //                 dataType: "json",
    //                 success: function(data) {
    //                     console.log('data:', data);
    //                     if (data) {
    //                         console.log(data.customer_name);
    //                         $('#customer_name').val(data.customer_name);
    //                         $('#nid_number').val(data.nid_number);
    //                         $('#customer_address').val(data.customer_address);
    //                     } else {
    //                         $('#jsdataerror').text('Not Found');
    //                     }
    //                 },
    //                 error: function() {
    //                     $('#jsdataerror').text('Error fetching data');
    //                 }
    //             });
    //         } else {
    //             $('#jsdataerror').text('Not Found');
    //         }
    //     });

    // });

    $(document).ready(function() {
        $('#customer_mobile').on('change', function() {
            let customer_mobile = $(this).val();
            loadCustomerDetails(customer_mobile);
        });
    });


    function loadCustomerDetails(customer_mobile) {
        if (customer_mobile) {
            let url = '{{ route('get_customer', ['customer_mobile' => ':customer_mobile']) }}';
            url = url.replace(':customer_mobile', customer_mobile);
            console.log(url);
            $.ajax({
                url: url,
                type: "GET",
                dataType: "json",
                success: function(data) {
                    console.log('data:', data);
                    if (data) {
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
    }
</script>

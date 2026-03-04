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
    <!--begin::Content-->
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container">
            <!--begin::Products-->
            <div class="card card-flush">


                <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                    <!--begin::Card title-->
                    <div class="card-title">
                        <h3>New Booking</h3>
                    </div>
                    <!--begin::Card toolbar-->

                    <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                        <div class="form-group col-md-3">
                            <label for="order">{{ __('Start Date') }}</label>
                            <input id="start_date" type="date" value="{{ date('Y-m-d') }}"
                                class="form-control form-control-sm form-control-solid" id=""
                                name="start_date" />
                        </div>
                        <div class="form-group col-md-3">
                            <label for="order">{{ __('End Date') }}</label>
                            <input id="end_date" type="date" value="{{ date('Y-m-d') }}"
                                class="form-control form-control-sm form-control-solid" id=""
                                name="end_date" />
                        </div>
                    </div>
                    <div class="d-flex flex-row-reverse pt-0 pt-md-5">
                        <button type="submit" id="searchBtn"
                            class="btn btn-sm btn-primary px-5 mt-0">{{ __('Search') }}</button>
                    </div>

                    {{-- <div class="d-flex flex-row-reverse pt-0 pt-md-5">
                        <a href="" id="downloadPdf" name="downloadPdf" type="button"
                            class="btn btn-sm btn-light-primary mt-0 mt-md-3" target="_blank">
                            PDF Download
                        </a>
                    </div> --}}
                </div>



                <div class="row g-5 g-xl-8 px-10 py-10">

                    @php
                        // echo '<pre>';
                        // print_r($room);
                        // echo '</pre>';
                        // die();
                    @endphp

                    {{-- @foreach ($room as $room_item)
                        <div class="col-xl-2">

                            <a href="{{ route('booking_add', ['id' => $room_item->id]) }}"
                                class="card bg-primary card-xl-stretch mb-5 mb-xl-8"
                                style="background-image:url('assets/media/svg/shapes/widget-bg-1.png')">
                                <!--begin::Body-->
                                <div class="card-body">
                                    <i class="fa-solid fa-home-lg fs-4x text-light"></i>
                                    <div class="text-white fw-bold fs-2 mb-2 mt-5">{{ $room_item->room_number }}</div>
                                    <div class="fw-semibold text-white">Room</div>
                                </div>
                                <!--end::Body-->
                            </a>
                        </div>
                    @endforeach --}}

                    <div class="container">
                        <div class="row gy-3" id="room_wise_booking_list_container">
                            <!-- Bookings will be dynamically appended here -->
                        </div>
                    </div>


                </div>






            </div>
            <!--end::Products-->
        </div>
        <!--end::Content container-->
    </div>
    <!--end::Content-->

</x-default-layout>

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
                                `<p class="card-title" style="color: red;">${d.room_number}</p>` :
                                `<p class="card-title">${d.room_number}</p>`;

                            // Define isBookedDisplay and actionButton
                            let isBookedDisplay = d.is_booked === 'Booked' ?
                                `<span style="color: red;">${d.is_booked}</span>` :
                                d.is_booked;

                            let actionButton = d.is_booked === 'Booked' ?
                                `<button class="btn btn-primary btn-sm" style="color: red; background-color: red;" disabled>Book</button>` :
                                `<a href="${bookingUrl}" class="btn btn-primary btn-sm">Book</a>`;

                            // Construct the booking card
                            let bookingCard = `
                            <div class="col-md-1">
                                <div class="card text-center">
                                    <div class="py-3">
                                        ${roomNumber}
                                        <p class="card-text">${isBookedDisplay}</p>
                                        ${actionButton}
                                    </div>
                                </div>
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

    $('#searchBtn').on('click', function() {
        CallBack("list");
    });

    $('#downloadPdf').on('click', function() {
        CallBack("pdfurl");
    });
</script>

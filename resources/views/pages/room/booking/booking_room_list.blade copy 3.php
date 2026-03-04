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
                            class="btn btn-sm btn-primary px-5 mt-0 mt-md-3">{{ __('Search') }}</button>
                    </div>

                    {{-- <div class="d-flex flex-row-reverse pt-0 pt-md-5">
                        <a href="" id="downloadPdf" name="downloadPdf" type="button"
                            class="btn btn-sm btn-light-primary mt-0 mt-md-3" target="_blank">
                            PDF Download
                        </a>
                    </div> --}}
                </div>



                <div class="row g-5 g-xl-8 px-10">

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

                    <!--begin::Table-->
                    <table
                        class="table table-striped table-bordered align-middle table-row-dashed fs-7 gy-5 mb-0 text-center"
                        id="item_wise_sales_report">
                        <!--begin::Table head-->
                        <thead>
                            <!--begin::Table row-->
                            <tr class="text-start fs-7 text-uppercase gs-0">
                                <th class="min-w-100px text-center">Date</th>
                                <th class="min-w-100px text-center">Image</th>
                                <th class="min-w-50px text-center">Floor</th>
                                <th class="min-w-50px text-center">Room ID</th>
                                <th class="min-w-50px text-center">Room Number</th>
                                <th class="min-w-50px text-center">Is Booked</th>
                                <th class="min-w-50px text-center">Booking</th>
                            </tr>
                            <!--end::Table row-->
                        </thead>
                        <!--end::Table head-->
                        <!--begin::Table body-->
                        <tbody class="fw-semibold text-gray-700">

                        </tbody>
                        <!--end::Table body-->
                    </table>
                    <!--end::Table-->

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
                    className: 'btn btn-sm btn-light-primary'
                },
                {
                    extend: 'copy',
                    text: 'Copy',
                    title: `${reportTittle}`,
                    className: 'btn btn-sm btn-light-primary'
                },
                {
                    extend: 'csv',
                    text: 'CSV',
                    title: `${reportTittle}`,
                    className: 'btn btn-sm btn-light-primary'
                },
                {
                    extend: 'pdf',
                    text: 'PDF',
                    title: `${reportTittle}`,
                    className: 'btn btn-sm btn-light-primary'
                },
                {
                    extend: 'print',
                    text: 'Print',
                    title: `${reportTittle}`,
                    className: 'btn btn-sm btn-light-primary'
                }
            ]
        }],
        paging: true,
        ordering: false,
        searching: true,
        responsive: false,
        lengthMenu: [10, 25, 50, 100],
        pageLength: 25,
        language: {
            lengthMenu: 'Show _MENU_ entries',
            search: 'Search:',
            paginate: {
                first: 'First',
                last: 'Last',
                next: 'Next',
                previous: 'Previous'
            }
        }
    };
    var table = $('#item_wise_sales_report').DataTable(dataTableOptions);

    function CallBack(pdfdata) {
        let url;
        var startDate = $('#start_date').val();
        var endDate = $('#end_date').val();
        // var productID = $('#productID').val();
        url =
            '{{ route('booking_search', ['startDate' => ':startDate', 'endDate' => ':endDate']) }}';

        url = url.replace(':startDate', startDate);
        url = url.replace(':endDate', endDate);

        if (pdfdata == 'list') {
            $.ajax({
                url: url,
                type: "GET",
                data: {
                    "_token": "{{ csrf_token() }}"
                },
                dataType: "json",
                success: function(data) {
                    // console.log('data:', data);
                    table.clear().draw();
                    if (data.length > 0) {
                        // console.log(data);
                        $.each(data, function(key, value) {
                            let d = data[key];

                            // Construct the dynamic route for 'booking_add'
                            let bookingUrl =
                                `{{ route('booking_add', ['id' => ':id', 'date' => ':date']) }}`;
                            bookingUrl = bookingUrl.replace(':id', d.id).replace(':date', d.date);


                            // Apply red color and disable bookingUrl if is_booked is "Booked"
                            let isBookedDisplay = d.is_booked === "Booked" ?
                                `<span style="color: red;">${d.is_booked}</span>` :
                                d.is_booked;

                            // Disable booking link if the room is booked
                            let actionButton = d.is_booked === "Booked" ?
                                `<button class="btn btn-secondary" style="color: red;" disabled>Booked</button>` :
                                `<a href="${bookingUrl}" class="btn btn-primary">Book</a>`;


                            console.log(d);
                            // Add the row to the table
                            table.row.add([
                                d.date,
                                `<img src="{{ asset('Storage/') }}/${d.image_path}" alt="Room Image" style="width: 100px; height: 100px;">`,
                                d.floor,
                                d.id,
                                d.room_number,
                                isBookedDisplay,
                                actionButton
                            ]).draw();
                        });
                        table.buttons().enable();
                    } else {
                        $('#jsdataerror').text('No records found');
                        console.log('No records found');
                    }
                }
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

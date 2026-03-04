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
    </style>

    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container  d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h3>Manage Attendance</h3>
            </div>
        </div>
    </div>
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container ">
            <!--begin::Products-->
            <div class="card card-flush">
                <div class="card-body">
                    <div class="row d-flex">
                        <div class="col-md-2">
                            <div class="fv-row mb-0 fv-plugins-icon-container">
                                <select class="form-select form-select-sm" name="employee_code" id="employee_code"
                                    data-control="select2" data-hide-search="false">
                                    <option selected value="0">All Employee</option>
                                    @foreach ($employees as $key => $value)
                                        <option value="{{ $key }}">{{ $value }} - {{ $key }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="fv-row mb-0 fv-plugins-icon-container">
                                <input id="start_date" type="date" name="dob"
                                    class="form-control form-control-sm ps-5 p-2"
                                    value="{{ date('Y-m-d', strtotime($startDate)) }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="fv-row mb-0 fv-plugins-icon-container">
                                <input id="end_date" type="date" name="end_date"
                                    class="form-control form-control-sm ps-5 p-2"
                                    value="{{ date('Y-m-d', strtotime($startDate)) }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="fv-row mb-0 fv-plugins-icon-container">
                                <button type="submit" id="searchBtn"
                                    class="btn btn-sm btn-primary px-5">{{ __('Search') }}</button>
                            </div>
                        </div>
                        <div class="col-md-2 ms-auto">
                            <div class="d-flex flex-row-reverse">
                                <a href="" id="downloadPdf" name="downloadPdf" type="button"
                                    class="btn btn-sm btn-light-primary" target="_blank">
                                    PDF Download
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <!--begin::Card header-->
                <div id="tableDiv" class="card-body pt-0 table-responsive">
                    <table class="table table-striped table-bordered align-middle table-row-dashed fs-7 gy-5 mb-0"
                        id="kt_ecommerce_report_customer_orders_table">
                        <thead>
                            <tr class="text-start fs-7 text-uppercase gs-0">
                                <th class="min-w-50px"> ID</th>
                                <th class="min-w-100px"> Name</th>
                                <th class="min-w-100px"> Department</th>
                                <th class="min-w-100px"> Designation</th>
                                <th class="min-w-50px"> Date</th>
                                <th class="min-w-100px">Day</th>
                                <th class="min-w-100px">Time In</th>
                                <th class="min-w-100px">Time Out</th>
                                <th class="min-w-130px">Time ( <span class="text-danger">Late</span> / <span
                                        class="text-success">Early</span> )</th>
                                <th class="min-w-100px">Work Time</th>
                            </tr>
                        </thead>
                        <tbody class="fw-semibold text-gray-700" id="attendance_list">

                        </tbody>
                    </table>
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Products-->
        </div>
        <!--end::Content container-->
    </div>
    <!--end::Content-->
</x-default-layout>
<script>
    var startDate = $('#start_date').val();
    var endDate = $('#end_date').val();
    var name = 'Perfume PLC';
    name += `\n Attendance Date :  ${startDate} - ${startDate}`;
    var reportTittle = name;

    var dataTableOptions = {
        dom: '<"top"fB>rt<"bottom"lip>',
        // dom: 'Bfrtip',
        buttons: [{
            extend: 'collection',
            text: 'Export',
            className: 'btn btn-sm btn-light-primary',

            buttons: [
                {
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
    
    var table = $('#kt_ecommerce_report_customer_orders_table').DataTable(dataTableOptions);

    function CallBack(pdfdata) {
        let url;
        var employeeID = $('#employee_code').val();
        var startDate = $('#start_date').val();
        var endDate = $('#end_date').val();

        url =
            '{{ route('daily_attendance_search', ['employeeID' => ':employeeID', 'startDate' => ':startDate', 'endDate' => ':endDate', 'pdf' => ':pdf']) }}';

        url = url.replace(':employeeID', employeeID);
        url = url.replace(':startDate', startDate);
        url = url.replace(':endDate', endDate);
        url = url.replace(':pdf', pdfdata);

        if (pdfdata == 'list') {
            $.ajax({
                url: url,
                type: "GET",
                data: {
                    "_token": "{{ csrf_token() }}"
                },
                dataType: "json",
                success: function(data) {
                    //console.log('data:', data);
                    table.clear().draw();
                    if (data.length > 0) {
                        $('#jsdataerror').text('');
                        $.each(data, function(key, value) {
                            let d = data[key];

                            let inTimeFormatted = "";
                            if (/^\d{2}:\d{2}/.test(d.InTime)) {
                                inTimeFormatted = formatTimeToAMPM(d.InTime);
                            } else {
                                inTimeFormatted = d.InTime;
                            }
                            // if (d.InTime === "Out Of Office" || d.InTime === "Punishment Absence" || d.InTime === "Leave") {
                            //     inTimeFormatted = d.InTime;
                            // } else {
                            //     inTimeFormatted = formatTimeToAMPM(d.InTime);
                            // }
                            let outTimeFormatted = formatTimeToAMPM(d.OutTime);

                            let lateTimeValue = (d.LateTime === null) ? null : parseTimeToMinutes(d
                                .LateTime);
                            let lateTimeClass = (lateTimeValue !== null && lateTimeValue < 0) ?
                                'color: red;' : 'color: green;';
                            let inTimeClass = (inTimeFormatted !== null && inTimeFormatted ===
                                'Absent') ? 'color: red;' : 'color: black;';

                            table.row.add([d.ID, d.EmployeeName, d.department_name, d
                                .designation_name, d._date, d.Day,
                                (inTimeFormatted !== null) ?
                                `<span style="${inTimeClass}">${inTimeFormatted}</span>` :
                                '',
                                outTimeFormatted,
                                (lateTimeValue !== null) ?
                                `<span style="${lateTimeClass}">${d.LateTime}</span>` : '',
                                d.WorkTime
                            ]).draw();

                            // Apply class to the LateTime column cells
                            if (lateTimeValue !== null) {
                                table.column(4).nodes().to$().addClass(lateTimeClass);
                            }
                        });
                        table.buttons().enable();

                        function parseTimeToMinutes(time) {
                            if (!time) return null;
                            const isNegative = time.startsWith('-');
                            const parts = isNegative ? time.slice(1).split(':') : time.split(':');
                            let hours = parseInt(parts[0], 10);
                            let minutes = parseInt(parts[1], 10);
                            let seconds = parseInt(parts[2], 10);

                            // Convert time to total minutes, considering the negative sign
                            let totalMinutes = hours * 60 + minutes + seconds / 60;
                            return isNegative ? -totalMinutes : totalMinutes;
                        }

                        function formatTimeToAMPM(time) {
                            console.log(time);
                            let formattedTime = "";
                            if (time) {
                                let timeParts = time.split(":");
                                let hours = parseInt(timeParts[0]);
                                let minutes = timeParts[1];
                                let seconds = timeParts[2];

                                let period = hours < 12 ? "AM" : "PM";
                                hours = (hours % 12) || 12; // Convert 0 to 12 for midnight
                                formattedTime = `${hours}:${minutes} ${period}`;
                            }
                            return formattedTime;
                        }

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




    $('#selectBranch').on('change', function() {
        $('#employee_code').empty();
        var branchID = $(this).val();
        var url = '{{ route('branch_wais_employee', ':branchID') }}';
        url = url.replace(':branchID', branchID);

        if (branchID) {
            $.ajax({
                url: url,
                type: "GET",
                data: {
                    "_token": "{{ csrf_token() }}"
                },
                dataType: "json",
                success: function(data) {
                    $('#employee_code').append(
                        `<option selected value="0" >All Employee</option>`
                    );
                    if (data) {
                        $.each(data, function(key, value) {
                            $('#employee_code').append(
                                `<option value="` + value.employee_code + `">` + value
                                .employee_name +
                                `</option>`
                            );
                        });
                    } else {
                        $('#jsdataerror').text('Not Found');
                    }
                }
            });
        } else {
            $('#jsdataerror').text('Not Found');
        }
    });
</script>

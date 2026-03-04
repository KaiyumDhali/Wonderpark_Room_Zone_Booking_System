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
                <div id="attendanceReport"></div>

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
    var name = 'Global Shoes Ltd';
    name += `\n Attendance Date :  ${startDate} - ${startDate}`;
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
                    title: `${reportTittle}`, // Set custom title for Copy
                    className: 'btn btn-sm btn-light-primary',
                },
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
                    console.log('data:', data);
                    displayAttendanceReport(data);

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

 
    // Function to display the attendance report
    function displayAttendanceReport(attendanceData) {
       let count=0;
        var dates = [];
        var employeeAttendance = {};

        // Extract dates and organize attendance data by employee
        attendanceData.forEach(function(entry) {
            var employee = entry.EmployeeName;
            var date = entry._date;
            var inTime = entry.InTime;
           
            // Add date to the list of dates if not already present
            if (!dates.includes(date)) {
                dates.push(date);
            }

            // Initialize or update employee attendance for the date
            if (!employeeAttendance[employee]) {
                employeeAttendance[employee] = {};
            }
            employeeAttendance[employee][date] = inTime;
        });

        // Sort dates in ascending order
        dates.sort();

        // Construct the table
        var reportHtml = '<table class="table table-striped table-bordered align-middle table-row-dashed fs-7 gy-5 mb-0"><tr class="text-start fs-7 text-uppercase gs-0"><th>Employee</th>';
        dates.forEach(function(date) {
            count++
            reportHtml += '<th>' + count  + '</th>';
        });
        reportHtml += '</tr>';

        // Iterate over employees
        for (var employee in employeeAttendance) {
            reportHtml += '<tr><td>' + employee + '</td>';
            // Iterate over attendance for each date
            dates.forEach(function(date) {
                var inTime = employeeAttendance[employee][date] || ''; // If no inTime available, show empty string
                reportHtml += '<td>' + inTime + '</td>';
            });
            reportHtml += '</tr>';
        }

        reportHtml += '</table>';
        $('#attendanceReport').html(reportHtml);
    }
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

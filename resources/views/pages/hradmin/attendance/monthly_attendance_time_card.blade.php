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

        table.dataTable thead th,
        table.dataTable thead td,
        table.dataTable tfoot th,
        table.dataTable tfoot td {
            text-align: center;
        }
    </style>

    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container  d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h3>Monthly Attendance Time Card</h3>
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
                        <div class="col-md-2 py-2">
                            <div class="fv-row mb-0 fv-plugins-icon-container">
                                <label class="fs-6 fw-semibold form-label mt-0">
                                    <span class=""> Select Month</span>
                                </label>
                                <div class="input-group input-group-sm" id="kt_td_picker_year_month_only_search"
                                    data-td-target-input="nearest" data-td-target-toggle="nearest">

                                    <input name="selectedmonth" id="kt_td_picker_year_month_only_search_input"
                                        type="text" class="form-control"
                                        data-td-target="#kt_td_picker_year_month_only_search" />
                                    <span class="input-group-text" data-td-target="#kt_td_picker_year_month_only_search"
                                        data-td-toggle="datetimepicker">
                                        <i class="ki-duotone ki-calendar fs-2">
                                            <span class="path1"></span><span class="path2"></span>
                                        </i>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2 py-2">
                            <div class="fv-row mb-0 fv-plugins-icon-container pt-0 pt-md-5">
                                <button type="submit" id="searchBtn"
                                    class="btn btn-sm btn-primary px-5 mt-0 mt-md-3">{{ __('Search') }}</button>
                            </div>
                        </div>
                        <div class="col-md-2 ms-auto py-2">
                            <div class="d-flex flex-row-reverse pt-0 pt-md-5">
                                <a href="" id="downloadPdf" name="downloadPdf" type="button"
                                    class="btn btn-sm btn-light-primary mt-0 mt-md-3" target="_blank">
                                    PDF Download
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <!--begin::Card header-->
                <div id="monthly_time_card" class="card-body pt-0 table-responsive">

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
    // monthly search 
    const element2 = document.getElementById("kt_td_picker_year_month_only_search");
    // Get the current date
    const currentDate = new Date();
    // Subtract one month from the current date
    currentDate.setMonth(currentDate.getMonth() - 1);
    // Set defaultDate to the previous month
    new tempusDominus.TempusDominus(element2, {
        localization: {
            locale: "dhaka",
            startOfTheWeek: 1,
            format: "yyyy-MM"
        },
        display: {
            viewMode: "months",
            components: {
                decades: false,
                year: true,
                month: true,
                date: false,
                hours: false,
                minutes: false,
                seconds: false
            }
        },
        defaultDate: currentDate
    });

    var month = $('#kt_td_picker_year_month_only_search_input').val();
    var name = 'Perfume PLC';
    name += `\n Attendance Time Card Month of ${month} `;
    var reportTittle = name;

    var dataTableOptions = {
        dom: '<"top"fB>rt<"bottom"lip>',
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

    // Function to display the attendance report
    function displayAttendanceReport(attendanceData) {
        let count = 0;
        var dates = [];
        var employeeAttendance = {};

        // Extract dates and organize attendance data by employee
        attendanceData.forEach(function(entry) {
            var employee = entry.EmployeeName;
            var date = entry._date;
            var inTime = entry.InTime;
            var OutTime = entry.OutTime;

            // Add date to the list of dates if not already present
            if (!dates.includes(date)) {
                dates.push(date);
            }

            // Initialize or update employee attendance for the date
            if (!employeeAttendance[employee]) {
                employeeAttendance[employee] = {};
            }
            // Store both inTime and OutTime separately
            employeeAttendance[employee][date] = {
                inTime: inTime,
                outTime: OutTime
            };
        });

        // Sort dates in ascending order
        dates.sort();

        // Construct the table
        var reportHtml =
            '<table id="monthly_time_card_table" class="table table-striped table-bordered align-middle table-row-dashed fs-7 gy-5 mb-0"><thead><tr class="text-start fs-7 text-uppercase gs-0"><th>Employee</th><th>Count</th>';
        dates.forEach(function(date) {
            count++;
            reportHtml += '<th class="min-w-50px">' + count + '</th>'; // Use date as column header
        });
        reportHtml += '</tr></thead><tbody class="text-center">';

        // Iterate over employees
        for (var employee in employeeAttendance) {
            reportHtml += '<tr><td>' + employee + '</td><td>' + count + '</td>';
            // Iterate over attendance for each date
            dates.forEach(function(date) {
                var attendance = employeeAttendance[employee][date] || {
                    inTime: '',
                    outTime: ''
                };
                var inTime = attendance.inTime;
                var outTime = attendance.outTime;
                reportHtml += '<td>' + inTime + '<br>' + (inTime.match(/^\d{2}:\d{2}/) ? outTime || '' : '') + '<br>' + (inTime.match(/^\d{2}:\d{2}/) ? 'P' : '') + '</td>';
            });
            reportHtml += '</tr>';
        }

        reportHtml += '</tbody></table>';
        $('#monthly_time_card').html(reportHtml);

        // Initialize DataTable
        $('#monthly_time_card_table').DataTable(dataTableOptions);
    }



    function CallBack(pdfdata) {
        var month = $('#kt_td_picker_year_month_only_search_input').val();
        var startDate = month + "-01";
        var endDate = new Date(month + "-01");
        endDate.setMonth(endDate.getMonth() + 1);
        endDate.setDate(0);
        endDate = endDate.toISOString().slice(0, 10);

        let url;
        var employeeID = 0;
        var startDate = startDate;
        var endDate = endDate;

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
        } else if (pdfdata == 'pdfurlmonthly') {
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
        CallBack("pdfurlmonthly");
    });
</script>

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
                <h3>Leave Report</h3>
            </div>
        </div>
    </div>
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container ">
            <!--begin::Products-->
            <div class="card card-flush">
                <!--begin::Card header-->
                <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                    <!--begin::Card title-->
                    <div class="card-title">
                        {{-- <div class="form-check form-check-custom form-check-success form-check-solid pe-10">
                            <input id="employeeCheck" class="form-check-input" type="checkbox" value="" />
                            <label class="form-check-label" for="">
                                Employee Wise
                            </label>
                        </div>
                        <div class="d-flex align-items-center position-relative my-1 ">
                            <div class="card-body p-2">
                            <select class="form-select form-select-sm" id="selectBranch" name="branch_id" required>
                                <option value="0">All Employee</option>
                                @foreach ($allEmpBranch as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                            </div>
                        </div>
                        <div id="employee_wise_div" class="d-none">
                            <div class="d-flex align-items-center position-relative my-1 ">
                                <div class="card-body p-2">
                                    <div class="product fv-row fv-plugins-icon-container">
                                        <select id="selectEmployee" name="employee_id" class="form-select form-select-sm" required>
                                            <option value="">Select Employee</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center position-relative my-1">
                            <div class="card-body p-2">
                                <div class="product fv-row fv-plugins-icon-container">
                                    <input id="start_date" type="date" name="dob"
                                        class="form-control form-control-sm ps-5 p-2"
                                        value="">
                                </div>
                            </div>
                        </div>
                        <div id="end_date_div" class="d-none">
                            <div class="card-body p-2">
                                <div class="product fv-row fv-plugins-icon-container">
                                    <input id="end_date" type="date" name="end_date"
                                        class="form-control form-control-sm ps-5 p-2"
                                        value="">
                                </div>
                            </div>
                        </div> --}}
                        <div id="year_div">
                            <div class="card-body p-2">
                                <div class="product fv-row fv-plugins-icon-container">
                                    <select class="form-select form-select-sm" id="year" name="year">
                                        @for ($year = date('Y'); $year <= date('Y') + 7; $year++)
                                            <option value="{{ $year }}" {{ date('Y') == $year ? 'selected' : '' }}>{{ $year }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center position-relative my-1 ">
                            <div class="card-body p-2">
                            <select class="form-select form-select-sm" id="selectBranch" name="branch_id" required>
                                <option value="0">All Employee</option>
                                @foreach ($allEmpBranch as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                            </div>
                        </div>
                        <div id="employee_wise_div">
                            <div class="d-flex align-items-center position-relative my-1 ">
                                <div class="card-body p-2">
                                    <div class="product fv-row fv-plugins-icon-container">
                                        <select id="selectEmployee" name="employee_id" class="form-select form-select-sm" required>
                                            <option value="">Select Employee</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="month_div">
                            <div class="card-body p-2">
                                <div class="product fv-row fv-plugins-icon-container">
                                    <select class="form-select form-select-sm" id="month" name="month">
                                        <option value="">Month</option>
                                        <option value="january" {{ date('F') == 'January' ? 'selected' : '' }}>January</option>
                                        <option value="february" {{ date('F') == 'February' ? 'selected' : '' }}>February</option>
                                        <option value="march" {{ date('F') == 'March' ? 'selected' : '' }}>March</option>
                                        <option value="april" {{ date('F') == 'April' ? 'selected' : '' }}>April</option>
                                        <option value="may" {{ date('F') == 'May' ? 'selected' : '' }}>May</option>
                                        <option value="june" {{ date('F') == 'June' ? 'selected' : '' }}>June</option>
                                        <option value="july" {{ date('F') == 'July' ? 'selected' : '' }}>July</option>
                                        <option value="august" {{ date('F') == 'August' ? 'selected' : '' }}>August</option>
                                        <option value="september" {{ date('F') == 'September' ? 'selected' : '' }}>September</option>
                                        <option value="october" {{ date('F') == 'October' ? 'selected' : '' }}>October</option>
                                        <option value="november" {{ date('F') == 'November' ? 'selected' : '' }}>November</option>
                                        <option value="december" {{ date('F') == 'December' ? 'selected' : '' }}>December</option>
                                    </select>                                    
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex align-items-center position-relative my-1 ">
                            <div class="card-body p-2">
                                <div class="product fv-row fv-plugins-icon-container">
                                    <button type="submit" id="searchBtn"
                                        class="btn btn-sm btn-primary px-5">{{ __('Search') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-toolbar flex-row-fluid justify-content-end gap-5">

                        <a href="" id="downloadPdf" name="downloadPdf" type="button"
                            class="btn btn-sm btn-light-primary" target="_blank">
                            PDF Download
                        </a>

                        @can('create attendance')
                        @endcan
                    </div>
                </div>
                <div id="tableDiv" class="card-body pt-0">
                    <table class="table table-striped table-bordered align-middle table-row-dashed fs-7 gy-5 mb-0"
                        id="kt_ecommerce_report_customer_orders_table">
                        <thead>
                            <tr class="text-start fs-7 text-uppercase gs-0">
                                <th class="min-w-50px">Sl</th>
                                {{-- <th class="min-w-50px">id</th> --}}
                                <th class="min-w-50px">code</th>
                                <th class="min-w-100px">employee name</th>
                                <th class="min-w-100px">department</th>
                                <th class="min-w-100px">designation</th>
                                <th class="min-w-50px">Casual</th>
                                <th class="min-w-50px">No of Late</th>
                                <th class="min-w-50px">Late Leave</th>
                                <th class="min-w-50px">Total Casual</th>
                                <th class="min-w-50px">Sick</th>
                                <th class="min-w-50px">Annual</th>
                                <th class="min-w-50px">Special</th>
                                <th class="min-w-100px">Leave Availed</th>
                                <th class="min-w-100px">Leave Balance</th>
                                <th class="min-w-100px">Total Leave</th>
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
    // var checkbox = document.querySelector("input[id=employeeCheck]");
    // var employeeWiseDiv = document.querySelector("#employee_wise_div");
    // var endDateDiv = document.querySelector("#end_date_div");

    var year = $('#year').val();
    var month = $('#month').val();

    var name = 'Shahjalal Equity Management Ltd.';
    name += `\n Leave Month of :  ${month} ${year}`;
    
    var reportTittle = name;

    // checkbox.addEventListener('change', function() {
    //     if (this.checked) {
    //         employeeWiseDiv.classList.remove("d-none");
    //         endDateDiv.classList.remove("d-none");
    //         employeeWiseDiv.style.display = "Flex";

    //     } else {
    //         employeeWiseDiv.classList.add("d-none");
    //         endDateDiv.classList.add("d-none");
    //     }
    //     console.log(reportTittle);
    // })

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
        responsive: true,
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
        // var year = '2023';
        // var month = 'October';
        var year = $('#year').val();
        var month = $('#month').val();

        // var employeeID = $('#selectEmployee').val();
        // var branchID = $('#selectBranch').val();

        url = '{{ route('leave_report_search', ['year' => ':year', 'month' => ':month', 'pdf' => ':pdf']) }}';

        url = url.replace(':year', year);
        url = url.replace(':month', month);
        url = url.replace(':pdf', pdfdata);

        // url = url.replace(':employeeID', employeeID);
        // url = url.replace(':branchID', branchID);

        if (pdfdata == 'list') {
            $.ajax({
                url: url,
                type: "GET",
                data: {
                    "_token": "{{ csrf_token() }}"
                },
                dataType: "json",
                success: function(data) {
                    // console.log(data);
                    table.clear().draw();
                    if (data.length > 0) {
                        $('#jsdataerror').text('');
                        $.each(data, function(key, value) {
                            
                            let d = data[key];
                            
                            let late_leave = Math.floor(d.LateCount / 4);
                            let total_casual = late_leave + parseInt(d.casual);
                            let leave_availed = late_leave + parseInt(d.Availed);
                            let leave_balance = parseInt(d.total_leave) - leave_availed;
                            // d.eid,
                            table.row.add([d.eorder, d.employee_code, d.employee_name, d.department_name, d.designation_name, d.casual, d.LateCount, late_leave, total_casual, d.sick, d.annual, d.special, leave_availed, leave_balance, d.total_leave])
                            .draw();
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
            console.log('Please select a date');
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
        $('#selectEmployee').empty();
        var branchID = $(this).val();
        var url = '{{route("branch_wais_employee",':branchID')}}';
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
                    $('#selectEmployee').append(
                        `<option selected value="" >Select Employee</option>`
                    );
                    if (data) {
                        console.log(data);
                        $.each(data, function(key, value) {
                            $('#selectEmployee').append(
                                `<option value="` + value.employee_code + `">` + value.employee_name +
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

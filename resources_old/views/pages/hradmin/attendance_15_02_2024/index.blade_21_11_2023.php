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
                <!--begin::Card header-->
                <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                    <!--begin::Card title-->
                    <div class="card-title">
                        <div class="form-check form-check-custom form-check-success form-check-solid pe-10">
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
                                        value="{{ date('Y-m-d', strtotime($startDate)) }}">
                                </div>
                            </div>
                        </div>
                        <div id="end_date_div" class="d-none">
                            <div class="card-body p-2">
                                <div class="product fv-row fv-plugins-icon-container">
                                    <input id="end_date" type="date" name="end_date"
                                        class="form-control form-control-sm ps-5 p-2"
                                        value="{{ date('Y-m-d', strtotime($startDate)) }}">
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

                        {{-- <div class="d-flex align-items-center position-relative my-1">
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
                                class="form-control form-control-sm w-250px ps-14 p-2" placeholder="Search" />
                        </div>
                        <!--begin::Export dropdown-->
                        <button type="button" class="btn btn-sm btn-light-primary" data-kt-menu-trigger="click"
                            data-kt-menu-placement="bottom-end">
                            <!--begin::Svg Icon | path: icons/duotune/arrows/arr078.svg-->
                            <span class="svg-icon svg-icon-2">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <rect opacity="0.3" x="12.75" y="4.25" width="12" height="2" rx="1"
                                        transform="rotate(90 12.75 4.25)" fill="currentColor" />
                                    <path
                                        d="M12.0573 6.11875L13.5203 7.87435C13.9121 8.34457 14.6232 8.37683 15.056 7.94401C15.4457 7.5543 15.4641 6.92836 15.0979 6.51643L12.4974 3.59084C12.0996 3.14332 11.4004 3.14332 11.0026 3.59084L8.40206 6.51643C8.0359 6.92836 8.0543 7.5543 8.44401 7.94401C8.87683 8.37683 9.58785 8.34458 9.9797 7.87435L11.4427 6.11875C11.6026 5.92684 11.8974 5.92684 12.0573 6.11875Z"
                                        fill="currentColor" />
                                    <path opacity="0.3"
                                        d="M18.75 8.25H17.75C17.1977 8.25 16.75 8.69772 16.75 9.25C16.75 9.80228 17.1977 10.25 17.75 10.25C18.3023 10.25 18.75 10.6977 18.75 11.25V18.25C18.75 18.8023 18.3023 19.25 17.75 19.25H5.75C5.19772 19.25 4.75 18.8023 4.75 18.25V11.25C4.75 10.6977 5.19771 10.25 5.75 10.25C6.30229 10.25 6.75 9.80228 6.75 9.25C6.75 8.69772 6.30229 8.25 5.75 8.25H4.75C3.64543 8.25 2.75 9.14543 2.75 10.25V19.25C2.75 20.3546 3.64543 21.25 4.75 21.25H18.75C19.8546 21.25 20.75 20.3546 20.75 19.25V10.25C20.75 9.14543 19.8546 8.25 18.75 8.25Z"
                                        fill="currentColor" />
                                </svg>
                            </span>
                            <!--end::Svg Icon-->Export Report
                        </button>
                        <div id="kt_ecommerce_report_customer_orders_export_menu"
                            class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-200px py-4"
                            data-kt-menu="true">
                            <!--begin::Menu item-->
                            <div class="menu-item px-3">
                                <a href="#" class="menu-link px-3" data-kt-ecommerce-export="copy">Copy to
                                    clipboard</a>
                            </div>
                            <!--end::Menu item-->
                            <!--begin::Menu item-->
                            <div class="menu-item px-3">
                                <a href="#" class="menu-link px-3" id= "exportExcel"
                                    data-kt-ecommerce-export="excel">Export as
                                    Excel</a>
                            </div>
                            <!--end::Menu item-->
                            <!--begin::Menu item-->
                            <div class="menu-item px-3">
                                <a href="#" class="menu-link px-3" data-kt-ecommerce-export="csv">Export as
                                    CSV</a>
                            </div>
                            <!--end::Menu item-->
                            <!--begin::Menu item-->
                            <div class="menu-item px-3">
                                <a href="#" class="menu-link px-3" data-kt-ecommerce-export="pdf">Export as
                                    PDF</a>
                            </div>
                            <!--end::Menu item-->
                        </div> --}}

                        @can('create attendance')
                        @endcan
                    </div>
                </div>
                <div id="tableDiv" class="card-body pt-0">
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
    var checkbox = document.querySelector("input[id=employeeCheck]");
    var employeeWiseDiv = document.querySelector("#employee_wise_div");
    var endDateDiv = document.querySelector("#end_date_div");

    var startDate = $('#start_date').val();
    var endDate = $('#end_date').val();
    var name = 'Shahjalal Equity Management Ltd';
    name += `\n Date :  ${startDate}`;
    var reportTittle = name;

    checkbox.addEventListener('change', function() {
        if (this.checked) {
            employeeWiseDiv.classList.remove("d-none");
            endDateDiv.classList.remove("d-none");
            employeeWiseDiv.style.display = "Flex";

        } else {
            employeeWiseDiv.classList.add("d-none");
            endDateDiv.classList.add("d-none");
        }
        // console.log(reportTittle);
    })

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
        var startDate = $('#start_date').val();
        var endDate = $('#end_date').val();
        var employeeID = $('#selectEmployee').val();
        var branchID = $('#selectBranch').val();

        if (checkbox.checked === false) {
            url = '{{ route('daily_attendance_search', ['branchID' => ':branchID', 'startDate' => ':startDate', 'pdf' => ':pdf']) }}';
        }

        if (checkbox.checked === true) {

            url = '{{ route('employee_attendance_search', ['branchID' => ':branchID', 'startDate' => ':startDate', 'endDate' => ':endDate', 'employeeID' => ':employeeID', 'pdf' => ':pdf']) }}';

            if (employeeID == "") {
                alert("Please Select Employee..");
                return;
            }
        }
        url = url.replace(':startDate', startDate);
        url = url.replace(':endDate', endDate);
        url = url.replace(':employeeID', employeeID);
        url = url.replace(':branchID', branchID);
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

                    table.clear().draw();

                    if (data.length > 0) {
                        $('#jsdataerror').text('');
                        $.each(data, function(key, value) {

                            let d = data[key];
                            table.row.add([d.ID, d.EmployeeName, d.department_name, d
                                .designation_name, d._date, d.Day, d.InTime, d.OutTime
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
                        // console.log(data);
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

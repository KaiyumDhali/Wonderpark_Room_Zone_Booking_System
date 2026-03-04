<x-default-layout>
    <style>
        .table> :not(caption)>*>* {
            padding: 0.3rem !important;
        }
    </style>
    <div class="col-xl-12 py-10">
        <div class="card card-flush h-lg-100" id="kt_contacts_main">
            <div class="card-header py-0" id="kt_chat_contacts_header">
                <div class="card-title">
                    <!--begin::Svg Icon | path: icons/duotune/communication/com005.svg-->
                    <span class="svg-icon svg-icon-1 me-2">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M20 14H18V10H20C20.6 10 21 10.4 21 11V13C21 13.6 20.6 14 20 14ZM21 19V17C21 16.4 20.6 16 20 16H18V20H20C20.6 20 21 19.6 21 19ZM21 7V5C21 4.4 20.6 4 20 4H18V8H20C20.6 8 21 7.6 21 7Z"
                                fill="currentColor"></path>
                            <path opacity="0.3"
                                d="M17 22H3C2.4 22 2 21.6 2 21V3C2 2.4 2.4 2 3 2H17C17.6 2 18 2.4 18 3V21C18 21.6 17.6 22 17 22ZM10 7C8.9 7 8 7.9 8 9C8 10.1 8.9 11 10 11C11.1 11 12 10.1 12 9C12 7.9 11.1 7 10 7ZM13.3 16C14 16 14.5 15.3 14.3 14.7C13.7 13.2 12 12 10.1 12C8.10001 12 6.49999 13.1 5.89999 14.7C5.59999 15.3 6.19999 16 7.39999 16H13.3Z"
                                fill="currentColor"></path>
                        </svg>
                    </span>
                    <h3>Employee Leave Entry</h3>
                </div>
            </div>
            <!--end::Card header-->
            <!--begin::Card body-->
            <div class="card-body py-0">
                <form id="leaveForm" class="form fv-plugins-bootstrap5 fv-plugins-framework" method="POST"
                    action="{{ route('employee_leave_entry.store') }}" enctype="multipart/form-data">
                    @csrf
                    <!-- <div class="row row-cols-1 row-cols-sm-4 rol-cols-md-1 row-cols-lg-4"> -->
                    <div class="row">
                        {{-- <div class="col-md-2">
                            <div class="fv-row mb-0 fv-plugins-icon-container">
                                <label class="fs-6 fw-semibold form-label mt-0">
                                    <span class="required">Select Year</span>
                                </label>
                                <select id="leave_year" name="leave_year" class="form-select form-select-sm" required>
                                    <option value="">Select Year</option>
                                    <?php
                                    $year_start = date('2022'); // 1990
                                    $year_end = date('Y'); // current Year
                                    $selected_year = date('Y'); // selected year
                                    
                                    for ($i_year = $year_start; $i_year <= $year_end; $i_year++) {
                                        $selected = $selected_year == $i_year ? ' selected' : '';
                                        echo '<option value="' . $i_year . '"' . $selected . '>' . $i_year . '</option>' . "\n";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div> --}}
                        <div class="col-md-2">
                            <div class="fv-row mb-0 fv-plugins-icon-container">
                                <label class="fs-6 fw-semibold form-label mt-0">
                                    <span class="required">Select Company </span>
                                </label>
                                <select id="changeBranch" name="branch_id" class="form-select form-select-sm" required>
                                    <option value="">Select Company </option>
                                    @foreach ($allEmpBranch as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="fv-row mb-0 fv-plugins-icon-container">
                                <label class="fs-6 fw-semibold form-label mt-0">
                                    <span class="required">Select Employee</span>
                                </label>
                                <select id="selectEmployee" name="employee_id" class="form-select form-select-sm"
                                    required>
                                    <option value="">Select Employee</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-2">
                            <div class="fv-row mb-0 fv-plugins-icon-container">
                                <label class="fs-6 fw-semibold form-label mt-0">
                                    <span class="required">Select Leave Type</span>
                                </label>
                                <select id="leave_type" name="leave_type" class="form-select form-select-sm" required>
                                    <option value="">Select Type</option>
                                    <option value="Casual">Casual Leave</option>
                                    <option value="Sick">Sick Leave</option>
                                    <option value="Annual">Annual Leave</option>
                                    <option value="Special">Special Leave</option>
                                </select>

                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="fv-row mb-7">
                                <label class="fs-6 fw-semibold form-label mt-0">
                                    <span class="required">Leave Start Date</span>
                                </label>

                                <input type="date" class="form-control form-control-sm" name="leave_start_date"
                                    id="leave_start_date" value="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="fv-row mb-7">
                                <label class="fs-6 fw-semibold form-label mt-0">
                                    <span class="required">Leave End Date</span>
                                </label>
                                <input type="date" class="form-control form-control-sm" name="leave_end_date"
                                    id="leave_end_date" value="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>
                       
                        

                        <div class="col-md-1">
                            <div class="fv-row mb-7 mt-8">
                                <button type="submit" data-kt-contacts-type="submit" class="btn btn-sm btn-primary"
                                    id="save">
                                    <span class="indicator-label">Save</span>
                                    <span class="indicator-progress">Please wait...
                                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="card card-flush" id="leave_entry_div">
        <!--begin::Card body-->
        <div class="card-body py-3">
            <!--begin::Table-->
            <div id="kt_ecommerce_category_table_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered align-middle table-row-dashed fs-6 gy-5 mb-0">
                        <!--begin::Table head-->
                        <thead>
                            <!--begin::Table row-->
                            <tr class="text-start  fs-6 text-uppercase gs-0">
                                <th class="min-w-10px sorting"> ID</th>
                                <th class="min-w-10px sorting"> Code</th>
                                <th class="min-w-20px sorting">
                                    Name</th>
                                <th class="min-w-30px sorting">
                                    Company</th>
                                <th class="min-w-10px sorting">
                                    Department</th>
                                <th class="min-w-10px sorting">
                                    Designation</th>
                                {{-- <th class="min-w-20px sorting">
                                    Reporting Date</th> --}}
                                <th class="min-w-20px sorting">
                                    Type</th>
                                <th class="min-w-20px sorting">
                                    Total Leave</th>
                            </tr>
                        </thead>
                        <tbody class="fw-semibold text-gray-700" id="leave_settings">
                            @foreach($employeeDetails as $employeeDetail)
                            <tr>
                                <td>
                                    <a>{{$employeeDetail->employee->id}}</a>
                                </td>
                                <td>
                                    <a>{{$employeeDetail->employee->employee_code}}</a>
                                </td>
                                <td>
                                    <a>{{$employeeDetail->employee->employee_name}}</a>
                                </td>
                                <td>
                                    <a>{{$employeeDetail->empBranch->branch_name}}</a>
                                </td>
                                <td>
                                    <a>{{$employeeDetail->empDepartment->department_name}}</a>
                                </td>
                                <td>
                                    <a>{{$employeeDetail->employee->empDesignation->designation_name}}</a>
                                </td>
                                <td>
                                    <a>{{$employeeDetail->leave_type}}</a>
                                </td>
                                <td>
                                    <a>{{$employeeDetail->total_leave_days}}</a>
                                </td>
                                
                                
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-default-layout>

<script type="text/javascript">
    {
        const intRx = /\d/,
            integerChange = (event) => {
                console.log(event.key, )
                if (
                    (event.key.length > 1) ||
                    event.ctrlKey ||
                    ((event.key === "-") && (!event.currentTarget.value.length)) ||
                    intRx.test(event.key)
                ) return;
                event.preventDefault();
            };

        for (let input of document.querySelectorAll(
                'input[type="number"][step="1"]'
            )) input.addEventListener("keydown", integerChange);
    }

    var data3;
    $('#leave_start_date').on('change', function() {
        var startDate = $(this).val();
        var endDate = $('#leave_end_date').val();

        const start_date = new Date(startDate);
        const end_date = new Date(endDate);

        // Check if the start date is after the end date
        if (start_date > end_date) {
            alert('The start date cannot be after the end date.');
            $('#leave_start_date').val(endDate);
            startDate = $('#leave_end_date').val();

            // Count the number of days between start date and end date
            const one_day = 1000 * 60 * 60 * 24; // milliseconds in one day
            const days_between = Math.round((end_date - end_date) / one_day);

            // Set the value of the "Total Leave Days" input field
            $('#total_leave_days').val(days_between + 1); // add 1 to include the start date

        } else {
            // Count the number of days between start date and end date
            const one_day = 1000 * 60 * 60 * 24; // milliseconds in one day
            const days_between = Math.round((end_date - start_date) / one_day);

            // Set the value of the "Total Leave Days" input field
            $('#total_leave_days').val(days_between + 1); // add 1 to include the start date
        }
    });

    $('#leave_end_date').on('change', function() {
        var endDate = $(this).val();
        var startDate = $('#leave_start_date').val();

        const start_date = new Date(startDate);
        const end_date = new Date(endDate);

        // Check if the start date is after the end date
        if (start_date > end_date) {
            alert('The end date cannot be before the start date.');
            $('#leave_end_date').val(startDate);
            endDate = $('#leave_start_date').val();

            // Count the number of days between start date and end date
            const one_day = 1000 * 60 * 60 * 24; // milliseconds in one day
            const days_between = Math.round((start_date - start_date) / one_day);

            // Set the value of the "Total Leave Days" input field
            $('#total_leave_days').val(days_between + 1); // add 1 to include the start date

            // Set the value of the "reporting_date" input field to the day after the end date
            const next_day = new Date(start_date.getTime() + one_day);
            const formatted_next_day = next_day.toISOString().split('T')[0];
            $('#reporting_date').val(formatted_next_day);
        } else {
            // Count the number of days between start date and end date
            const one_day = 1000 * 60 * 60 * 24; // milliseconds in one day
            const days_between = Math.round((end_date - start_date) / one_day);

            // Set the value of the "Total Leave Days" input field
            $('#total_leave_days').val(days_between + 1); // add 1 to include the start date

            // Set the value of the "reporting_date" input field to the day after the end date
            const next_day = new Date(end_date.getTime() + one_day);
            const formatted_next_day = next_day.toISOString().split('T')[0];
            $('#reporting_date').val(formatted_next_day);
        }
    });

    $('#changeBranch').on('change', function() {
        $('#selectEmployee').empty();

        $('#total_leave_div').addClass('d-none');
        $('#total_due_leave_div').addClass('d-none');

        $('#leave_entry_div').addClass('d-none');
        $('#leave_settings').empty();

        var branchID = $(this).val();
        if (branchID) {
            $.ajax({
                url: 'leaveEntryBranchDetails/' + branchID,
                type: "GET",
                data: {
                    "_token": "{{ csrf_token() }}"
                },
                dataType: "json",
                success: function(response) {
                    data3 = response.employees;
                    const data2 = response.employeeDetails;

                    // Do something with the data
                    console.log('Data 1:', data3);
                    console.log('Data 2:', data2);

                    $('#selectEmployee').append(
                        `<option value="">Select Employee</option>`
                    );

                    if (data3) {
                        $.each(data3, function(key, value) {
                            $('#selectEmployee').append(
                                `<option value="` + value.employee.id + `">` + value
                                .employee.employee_name +
                                `</option>`
                            );
                        });
                    } else {
                        $('#jsdataerror').text('Not Found');
                    }
                    if (data2) {
                        $('#leave_entry_div').addClass('d-none');
                        $('#leave_settings').empty();

                        $.each(data2, function(key, value) {

                            // var total_leave;
                            // if (value.employee_total_leave == null) {
                            //     total_leave = value.total_leave;
                            // } else if (value.final_status == 1) {
                            //     total_leave = (value.employee_total_leave.total_leave - value.total_days).toFixed(2);
                            // } else {
                            //     total_leave = value.employee_total_leave.total_leave;
                            // }
                            // $('#total_due_leave').val(total_leave);

                            var final_status = value.final_status == 0 ? "Pending" :
                                "Approved";
                            var management_status = value.management_status == 0 ?
                                "Pending" :
                                "Approved";
                            var hr_status = value.hr_status == 0 ? "Pending" : "Approved";
                            var department_status = value.department_status == 0 ?
                                "Pending" :
                                "Approved";

                            if (value.id) {
                                $('#leave_entry_div').removeClass('d-none');
                            } else {
                                $('#leave_entry_div').addClass('d-none');
                            }

                            $('#leave_settings').append(
                                `<tr>
                                            <td>
                                                <div class="d-flex">
                                                    <div >
                                                        <a id ="leave_id" class="text-gray-700 fs-6  mb-1" >` +
                                value.id + `</a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    <div >
                                                        <a class="text-gray-700 fs-6  mb-1"
                                                           >` +
                                value.employee_id.employee_name +
                                `</a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    <div >
                                                        <a class="text-gray-700 fs-6  mb-1"
                                                           >` +
                                value.leave_application_date +
                                `</a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    <div >
                                                        <a class="text-gray-700 fs-6  mb-1"
                                                           >` +
                                value.leave_start_date +
                                `</a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    <div >
                                                        <a class="text-gray-700 fs-6  mb-1"
                                                           >` +
                                value.leave_end_date +
                                `</a>
                                                    </div>
                                                </div>
                                            </td>
                                            
                                            <td>
                                                <div class="d-flex">
                                                    <div >
                                                        <a class="text-gray-700 fs-6  mb-1"
                                                           >` +
                                value.leave_type +
                                `</a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    <div >
                                                        <a class="text-gray-700 fs-6  mb-1"
                                                           >` +
                                value.total_days +
                                `</a>
                                                    </div>
                                                </div>
                                            </td>
                                            
                                            </tr>`);
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

    $('#selectEmployee').on('change', function() {
        var employeeID = $(this).val();
        $('#total_leave_div').removeClass('d-none');
        $('#total_leave_days').val(1);

        $('#total_due_leave_div').removeClass('d-none');
        $('#total_due_leave').val(0);

        $('#selectAlternativeEmployee').empty();
        $('#selectAlternativeEmployee').append(
            `<option value="">Select Alternative Employee</option>`
        );
        if (data3) {
            $.each(data3, function(key, value) {
                if (employeeID != value.employee.id) {
                    $('#selectAlternativeEmployee').append(
                        `<option value="` + value.employee.id + `">` + value.employee
                        .employee_name +
                        `</option>`
                    );
                }
            });
        } else {
            $('#jsdataerror').text('Not Found');
        }
        if (employeeID) {
            $.ajax({
                url: 'employeeLeaveEntryDetails/' + employeeID,
                type: "GET",
                data: {
                    "_token": "{{ csrf_token() }}"
                },
                dataType: "json",
                success: function(leaveEntryDetails) {
                    if (leaveEntryDetails) {
                        $('#leave_entry_div').addClass('d-none');
                        $('#leave_settings').empty();
                        var initialLeave = 0;
                        var nowLeave;
                        var leaveTotal;
                        $.each(leaveEntryDetails, function(key, value) {

                            var final_status = value.final_status == 0 ? "Pending" :
                                "Approved";
                            var management_status = value.management_status == 0 ?
                                "Pending" :
                                "Approved";
                            var hr_status = value.hr_status == 0 ? "Pending" : "Approved";
                            var department_status = value.department_status == 0 ?
                                "Pending" :
                                "Approved";

                            if (value.final_status == 1) {
                                nowLeave = parseInt(value.total_days);
                                if (initialLeave == 0) {
                                    leaveTotal = parseInt(value.total_leave);
                                } else {
                                    leaveTotal = initialLeave;
                                }
                                initialLeave = leaveTotal - nowLeave;
                                leaveTotal =
                                    initialLeave; // Convert to an integer with base 10

                            } else {
                                var leaveTotal = value.total_leave;
                            }
                            $('#total_due_leave').val(leaveTotal);


                            if (value.id) {
                                $('#leave_entry_div').removeClass('d-none');
                            } else {
                                $('#leave_entry_div').addClass('d-none');
                            }

                            $('#leave_settings').append(
                                `<tr>
                                            <td>
                                                <div class="d-flex">
                                                    <div >
                                                        <a id = "leave_id" class="text-gray-700 fs-6  mb-1"
                                                           >` +
                                value.id +
                                `</a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    <div >
                                                        <a class="text-gray-700 fs-6  mb-1"
                                                           >` +
                                value.employee.employee_name +
                                `</a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    <div >
                                                        <a class="text-gray-700 fs-6  mb-1"
                                                           >` +
                                value.leave_application_date +
                                `</a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    <div >
                                                        <a class="text-gray-700 fs-6  mb-1"
                                                           >` +
                                value.leave_start_date +
                                `</a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    <div >
                                                        <a class="text-gray-700 fs-6  mb-1"
                                                           >` +
                                value.leave_end_date +
                                `</a>
                                                    </div>
                                                </div>
                                            </td>
                                            
                                            <td>
                                                <div class="d-flex">
                                                    <div >
                                                        <a class="text-gray-700 fs-6  mb-1"
                                                           >` +
                                value.leave_type +
                                `</a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    <div >
                                                        <a class="text-gray-700 fs-6  mb-1"
                                                           >` +
                                value.total_days +
                                `</a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    <div >
                                                        <a id = "leave_id" class="text-gray-700 fs-6  mb-1"
                                                           >` +
                                value.alternative_employee_id.employee_name +
                                `</a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    <div >
                                                        <a class="text-gray-700 fs-6  mb-1"
                                                           >` +
                                value.reason_for_leave + `</a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    <div >
                                                        <a class="text-gray-700 fs-6  mb-1"
                                                           >` +
                                value.remarks + `</a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    <div >
                                                        <a class="text-gray-700 fs-6  mb-1"
                                                           >` +
                                department_status + `</a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    <div >
                                                        <a class="text-gray-700 fs-6  mb-1"
                                                           >` +
                                hr_status + `</a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    <div >
                                                        <a class="text-gray-700 fs-6  mb-1"
                                                           >` +
                                management_status + `</a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    <div >
                                                        <a class="text-gray-700 fs-6  mb-1"
                                                           >` +
                                final_status + `</a>
                                                    </div>
                                                </div>
                                            </td>
                                            </tr>`);
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

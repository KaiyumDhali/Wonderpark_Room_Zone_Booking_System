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
                    <div class="row mb-5">
                        <div class="col-md-2 col-12">
                            <div class="fv-row mb-0 fv-plugins-icon-container">
                                <label class="fs-6 fw-semibold form-label mt-0">
                                    <span class="required">Select Year</span>
                                </label>
                                <select id="leave_year" name="leave_year" class="form-select form-select-sm" required>
                                    <option value="">Select Year</option>
                                    <?php
                                    $year_start = date('Y'); // 1990
                                    $year_end = date('Y'); // current Year
                                    $selected_year = date('Y'); // selected year
                                    
                                    for ($i_year = $year_start; $i_year <= $year_end; $i_year++) {
                                        $selected = $selected_year == $i_year ? ' selected' : '';
                                        echo '<option value="' . $i_year . '"' . $selected . '>' . $i_year . '</option>' . "\n";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2 col-12">
                            <div class="fv-row mb-0 fv-plugins-icon-container">
                                <label class="fs-6 fw-semibold form-label mt-0">
                                    <span class="required">Branch</span>
                                </label>
                                <input type="text" id="branchID" name="branch_id"
                                    class="form-control form-control-sm" value="{{ $employee->branch_id }}" hidden>
                                <input type="text" name="branch_name" class="form-control form-control-sm"
                                    value="{{ $employee->branch_name }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-2 col-12">
                            <div class="fv-row mb-0 fv-plugins-icon-container">
                                <label class="fs-6 fw-semibold form-label mt-0">
                                    <span class="required">Select Employee</span>
                                </label>
                                <input type="text" id="employeeID" name="employee_id"
                                    class="form-control form-control-sm" value="{{ $employee->id }}" hidden>
                                <input type="text" name="employee_name" class="form-control form-control-sm"
                                    value="{{ $employee->employee_name }}" readonly>
                            </div>
                        </div>

                        <div class="col-md-2 col-12">
                            <div class="fv-row mb-0 fv-plugins-icon-container">
                                <label class="fs-6 fw-semibold form-label mt-0">
                                    <span class="required">Alternative Emp</span>
                                </label>
                                <select id="selectAlternativeEmployee" name="alternative_employee_id"
                                    class="form-select form-select-sm" required>
                                    <option value="">Alternative Emp</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2 col-12">
                            <div class="fv-row mb-0 fv-plugins-icon-container">
                                <label class="fs-6 fw-semibold form-label mt-0">
                                    <span class="required">Leave Type</span>
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
                        {{-- @can('create leave entry') --}}
                        <div class="col-md-2 col-12">
                            <div class="fv-row mb-7">
                                <label class="fs-6 fw-semibold form-label mt-0">
                                    <span class="required">Application Date</span>
                                </label>
                                <input type="date" class="form-control form-control-sm" name="leave_application_date"
                                    id="leave_application_date" value="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>
                        <div class="col-md-2 col-12">
                            <div class="fv-row mb-7">
                                <label class="fs-6 fw-semibold form-label mt-0">
                                    <span class="required">Leave Start Date</span>
                                </label>

                                <input type="date" class="form-control form-control-sm" name="leave_start_date"
                                    id="leave_start_date" value="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>
                        <div class="col-md-2 col-12">
                            <div class="fv-row mb-7">
                                <label class="fs-6 fw-semibold form-label mt-0">
                                    <span class="required">Leave End Date</span>
                                </label>
                                <input type="date" class="form-control form-control-sm" name="leave_end_date"
                                    id="leave_end_date" value="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>
                        <div class="col-md-2 col-12">
                            <div class="fv-row mb-7">
                                <label class="fs-6 fw-semibold form-label mt-0">
                                    <span class="">Reason For Leave</span>
                                </label>
                                <input type="text" class="form-control form-control-sm" name="reason_for_leave"
                                    id="reason_for_leave">
                            </div>
                        </div>
                        {{-- <div class="col-md-2">
                            <div class="fv-row mb-7">
                                <label class="fs-6 fw-semibold form-label mt-0">
                                    <span class="">Remarks</span>
                                </label>
                                <input type="text" class="form-control form-control-sm" name="remarks"
                                    id="leave_remarks">
                            </div>
                        </div> --}}

                        <div class="col-md-1 col-12">
                            <div class="fv-row mb-7">
                                <label class="fs-6 fw-semibold form-label mt-0">
                                    <span class="">Total</span>
                                </label>
                                <input type="text" class="form-control form-control-sm form-control-solid" readonly
                                    name="total_leave_days" id="total_leave_days">
                            </div>
                        </div>
                        <div class="col-md-2 col-12 d-none" id="type_due_leave_div">
                            <div class="fv-row mb-7">
                                <label class="fs-6 fw-semibold form-label mt-0">
                                    <span id="type_leave"></span>
                                </label>
                                <input type="text" class="form-control form-control-sm form-control-solid" readonly
                                    name="total_due_leave" id="type_due_leave">
                            </div>
                        </div>
                        <div class="col-md-2 col-12">
                            <div class="fv-row mb-7">
                                <label class="fs-6 fw-semibold form-label mt-0">
                                    <span class="">Due Leave</span>
                                </label>
                                <input type="text" class="form-control form-control-sm form-control-solid" readonly
                                    name="total_due_leave" id="total_due_leave">

                                <input type="hidden" class="form-control form-control-sm form-control-solid" readonly
                                    name="leaveSettingId" id="leaveSettingId">
                            </div>
                        </div>

                        <div class="col-md-1 col-12">
                            <div class="fv-row mb-7 mt-8">
                                <button type="submit" data-kt-contacts-type="submit" class="btn btn-sm btn-primary"
                                    id="save">
                                    <span class="indicator-label">Save</span>
                                    <span class="indicator-progress">Please wait...
                                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                </button>
                            </div>
                        </div>
                        {{-- @endcan --}}
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="card card-flush" id="leave_entry_div">
        <!--begin::Card body-->
        <div class="card-body">
            <!--begin::Table-->
            <div id="kt_ecommerce_category_table_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered align-middle table-row-dashedgy-5 mb-0">
                        <!--begin::Table head-->
                        <thead>
                            <!--begin::Table row-->
                            <tr class="text-start text-uppercase gs-0">
                                {{-- <th class="min-w-10px sorting"> ID</th> --}}
                                {{-- <th class="min-w-20px sorting">
                                    Name</th> --}}
                                <th class="min-w-30px sorting">
                                    Application</th>
                                <th class="min-w-10px sorting">
                                    Start Date</th>
                                <th class="min-w-10px sorting">
                                    End Date</th>
                                <th class="min-w-20px sorting">
                                    Leave Type</th>
                                <th class="min-w-20px sorting">
                                    Total Leave</th>
                                <th class="min-w-20px sorting">
                                    Alternative
                                </th>
                                <th class="min-w-20px sorting">
                                    Reason</th>
                                <th class="min-w-20px sorting">
                                    Remarks</th>
                                <th class="min-w-20px sorting">
                                    Dept Status</th>
                                <th class="min-w-20px sorting">
                                    HR Status</th>
                                <th class="min-w-20px sorting">
                                    Mgt Status</th>
                                <th class="min-w-20px sorting">
                                    Final Status</th>
                            </tr>
                        </thead>
                        <tbody class="fw-semibold text-gray-700" id="leave_settings">

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

    $(document).ready(function() {
        var branchID = $('#branchID').val();
        var employeeID = $('#employeeID').val();

        $('#total_leave_days').val(1);
        $('#total_due_leave').val(0);

        if (branchID) {
            $.ajax({
                url: 'leaveEntryBranchDetails/' + branchID,
                type: "GET",
                data: {
                    "_token": "{{ csrf_token() }}"
                },
                dataType: "json",
                success: function(response) {
                    var data3 = response.employees;
                    if (data3) {
                        $.each(data3, function(key, value) {
                            if (employeeID != value.employee.id) {
                                $('#selectAlternativeEmployee').append(
                                    `<option value="` + value.employee.id + `">` + value
                                    .employee
                                    .employee_name +
                                    `</option>`
                                );
                            }
                        });
                    } else {
                        $('#jsdataerror').text('Not Found');
                    }
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
                        $('#leave_settings').empty();
                        var initialLeave = 0;
                        var nowLeave;
                        var leaveTotal;

                        // Loop through each leave entry detail
                        $.each(leaveEntryDetails, function(key, value) {
                            // Determine the final status
                            var final_status = value.final_status == 0 ? "Pending" :
                                "Approved";
                            var management_status = value.management_status == 0 ?
                                "Pending" : "Approved";
                            var hr_status = value.hr_status == 0 ? "Pending" : "Approved";
                            var department_status = value.department_status == 0 ?
                                "Pending" : "Approved";
                            var leave_application_date = value.leave_application_date ?
                                value.leave_application_date : (new Date(value
                                    .late_of_leave_month).toLocaleDateString('en-US', {
                                    month: 'long',
                                    year: 'numeric'
                                }) + " late leave");

                            // Calculate leave totals based on final status
                            if (value.final_status == 1) {
                                var nowLeave = parseInt(value.total_days);
                                if (initialLeave == 0) {
                                    leaveTotal = parseInt(value.total_leave);
                                } else {
                                    leaveTotal = initialLeave;
                                }
                                initialLeave = leaveTotal - nowLeave;
                                leaveTotal = initialLeave;
                            } else {
                                leaveTotal = parseInt(value.total_leave);
                            }

                            // Update the total due leave field
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
                                            <div>
                                                <a class="text-gray-700 mb-1">${leave_application_date || '' }</a>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex">
                                            <div>
                                                <a class="text-gray-700 mb-1">${value.leave_start_date || '' }</a>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex">
                                            <div>
                                                <a class="text-gray-700 mb-1">${value.leave_end_date || ''}</a>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex">
                                            <div>
                                                <a class="text-gray-700 mb-1">${value.leave_type || ''}</a>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex">
                                            <div>
                                                <a class="text-gray-700 mb-1">${value.total_days || ''}</a>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex">
                                            <div>
                                                <a id="leave_id" class="text-gray-700 mb-1">${value.alternative_employee_id?.employee_name || ''}</a>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex">
                                            <div>
                                                <a class="text-gray-700 mb-1">${value.reason_for_leave || ''}</a>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex">
                                            <div>
                                                <a class="text-gray-700 mb-1">${value.remarks || ''}</a>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex">
                                            <div>
                                                <a class="text-gray-700 mb-1">${value.leave_start_date ? department_status || '' : ''}</a>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex">
                                            <div>
                                                <a class="text-gray-700 mb-1">${value.leave_start_date ? hr_status || '' : ''}</a>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex">
                                            <div>
                                                <a class="text-gray-700 mb-1">${value.leave_start_date ? management_status || '' : ''}</a>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex">
                                            <div>
                                                <a class="text-gray-700 mb-1">${final_status || ''}</a>
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

    $('#leave_type').on('change', function() {
        var leave_type = $(this).val();
        var employeeID = $('#employeeID').val();

        $('#type_due_leave_div').removeClass('d-none');
        $('#type_due_leave').val(0);

        $('#type_leave').text(leave_type + ' Due');

        if (employeeID) {
            $.ajax({
                url: 'employeeLeaveEntryDetails/' + employeeID,
                type: "GET",
                data: {
                    "_token": "{{ csrf_token() }}"
                },
                dataType: "json",
                success: function(data) {
                    if (data) {
                        var initialLeave = 0;
                        var nowLeave = 0;
                        var leaveTotal = 0;
                        $.each(data, function(key, value) {
                            if (initialLeave == 0) {
                                if (leave_type == "Casual") {
                                    leaveTotal = parseInt(value.casual_leave);
                                }
                                if (leave_type == "Sick") {
                                    leaveTotal = parseInt(value.sick_leave);
                                }
                                if (leave_type == "Annual") {
                                    leaveTotal = parseInt(value.annual_leave);
                                }
                                if (leave_type == "Special") {
                                    leaveTotal = parseInt(value.special_leave);
                                }
                            }
                            if (value.leave_type == leave_type) {
                                if (value.final_status == '1') {
                                    nowLeave += parseInt(value.total_days);
                                }
                            }
                            $('#type_due_leave').val(leaveTotal - nowLeave);
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

<x-default-layout>
    <style>
    .table> :not(caption)>*>* {
        padding: 0.3rem !important;
    }
    </style>
    <div class="col-xl-12 pt-10 pb-5">
        <div class="card card-flush h-lg-100" id="kt_contacts_main">
            <div class="card-header py-0" id="kt_chat_contacts_header">
                <div class="card-title">
                    <!--begin::Svg Icon | path: icons/duotune/communication/com005.svg-->
                    <span class="svg-icon svg-icon-1 me-2">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M20 14H18V10H20C20.6 10 21 10.4 21 11V13C21 13.6 20.6 14 20 14ZM21 19V17C21 16.4 20.6 16 20 16H18V20H20C20.6 20 21 19.6 21 19ZM21 7V5C21 4.4 20.6 4 20 4H18V8H20C20.6 8 21 7.6 21 7Z"
                                fill="currentColor"></path>
                            <path opacity="0.3"
                                d="M17 22H3C2.4 22 2 21.6 2 21V3C2 2.4 2.4 2 3 2H17C17.6 2 18 2.4 18 3V21C18 21.6 17.6 22 17 22ZM10 7C8.9 7 8 7.9 8 9C8 10.1 8.9 11 10 11C11.1 11 12 10.1 12 9C12 7.9 11.1 7 10 7ZM13.3 16C14 16 14.5 15.3 14.3 14.7C13.7 13.2 12 12 10.1 12C8.10001 12 6.49999 13.1 5.89999 14.7C5.59999 15.3 6.19999 16 7.39999 16H13.3Z"
                                fill="currentColor"></path>
                        </svg>
                    </span>
                    <h3>Employee Leave Approved Department </h3>
                </div>
            </div>
            <!--end::Card header-->
            <!--begin::Card body-->
            <div class="card-body pb-0 pt-0">
                <div class="row">
                    <!-- <div class="col-md-2">
                        <div class="fv-row mb-0 fv-plugins-icon-container">
                            <label class="fs-7 fw-semibold form-label mt-0">
                                <span class="required">Select Year</span>
                            </label>
                            <select id="leave_year" name="leave_year" class="form-select form-select-sm" required>
                                <option value="">Select Year</option>
                                <?php 
                                        $year_start  = date('Y'); // 1990
                                        $year_end = date('Y'); // current Year
                                        $selected_year = date('Y'); // selected year

                                        for ($i_year = $year_start; $i_year <= $year_end; $i_year++) {
                                            $selected = $selected_year == $i_year ? ' selected' : '';
                                            echo '<option value="'.$i_year.'"'.$selected.'>'.$i_year.'</option>'."\n";
                                        }
                                    ?>
                            </select>
                        </div>
                    </div> -->
                    <!-- <div class="col-md-2">
                        <div class="fv-row mb-0 fv-plugins-icon-container">
                            <label class="fs-7 fw-semibold form-label mt-0">
                                <span class="required">Select Employee Branch</span>
                            </label>
                            <select id="changeBranch" name="branch_id" class="form-select form-select-sm" required>
                                <option value="">Select Employee Branch</option>
                                @foreach($allEmpBranch as $key => $value)
                                <option value="{{ $key }}">{{ $value}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="fv-row mb-0 fv-plugins-icon-container">
                            <label class="fs-7 fw-semibold form-label mt-0">
                                <span class="required">Select Employee</span>
                            </label>
                            <select id="selectEmployee" name="employee_id" class="form-select form-select-sm" required>
                                <option value="">Select Employee</option>
                            </select>
                        </div>
                    </div> -->
                    <div class="d-flex flex-row bd-highlight mb-3">
                        <div class="p-2 bd-highlight">
                            <a href="{{ route('employee_leave_approve_dept.index') }}"
                            class="btn btn-sm btn-primary px-3">Pendimg leave</a>
                        </div>
                        <div class="p-2 bd-highlight">
                            <a href="{{ route('employee_leave_approve_dept_list') }}"
                                class="btn btn-sm btn-success px-3">Approved leave</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-flush" id="pendimgLeaveDive">
        <!--begin::Card body-->
        <div class="card-body">
            <!--begin::Table-->
            <div id="kt_ecommerce_category_table_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered align-middle table-row-dashed fs-7 gy-5 mb-0">
                        <!--begin::Table head-->
                        <thead>
                            <!--begin::Table row-->
                            <tr class="text-start  fs-7 text-uppercase gs-0">
                                <th class="min-w-10px sorting"> ID</th>
                                <th class="min-w-125px sorting">
                                    Name</th>
                                <th class="min-w-30px sorting">
                                    Application</th>
                                <th class="min-w-10px sorting">
                                    Start Date</th>
                                <th class="min-w-10px sorting">
                                    End Date</th>
                                <th class="min-w-20px sorting">
                                    Reporting Date</th>
                                <th class="min-w-70px sorting" style="width: 70px;">
                                    Total</th>
                                <th class="min-w-150px sorting">
                                    Alternative
                                </th>
                                <th class="min-w-20px sorting">
                                    Reason</th>
                                <th class="min-w-20px sorting">
                                    Remarks</th>
                                <th class="min-w-120px sorting" style="width: 120px;">
                                    Dept Status</th>
                                <th class="min-w-80px sorting" style="width: 80px;">
                                    HR Status</th>
                                <th class="min-w-100px sorting" style="width: 100px;">
                                    Mang Status</th>
                                <th class="min-w-100px sorting" style="width: 100px;">
                                    Final Status</th>
                                @can('create leave approved department')
                                <th class="min-w-20px sorting">
                                    Action</th>
                                @endcan
                            </tr>
                        </thead>
                        <tbody class="fw-semibold text-gray-700">
                            @foreach($employeeDetails as $employeeDetail)
                            <form id="leaveForm" class="form fv-plugins-bootstrap5 fv-plugins-framework" method="POST"
                                action="{{ route('employee_leave_approve_dept.update', $employeeDetail->id) }}"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <tr>
                                    <td>
                                        <div class="d-flex">
                                            <div>
                                                <a id="leave_id"
                                                    class="text-gray-700 fs-7  mb-1">{{$employeeDetail->id}}</a>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex">
                                            <div>
                                                <a
                                                    class="text-gray-700 fs-7  mb-1">{{$employeeDetail->employeeId->employee_name}}</a>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex">
                                            <div>
                                                <a
                                                    class="text-gray-700 fs-7  mb-1">{{$employeeDetail->leave_application_date}}</a>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex">
                                            <input type="date" class="form-control form-control-sm"
                                                name="leave_start_date" id="leave_start_date"
                                                value="{{ date('Y-m-d', strtotime($employeeDetail->leave_start_date)) }}"
                                                required>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex">
                                            <input type="date" class="form-control form-control-sm"
                                                name="leave_end_date" id="leave_end_date"
                                                value="{{ date('Y-m-d', strtotime($employeeDetail->leave_end_date)) }}"
                                                required>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex">
                                            <input type="date" class="form-control form-control-sm form-control-solid"
                                                name="reporting_date" id="reporting_date"
                                                value="{{ date('Y-m-d', strtotime('+1 day')) }}" readonly required>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex">
                                            <input type="text"
                                                class="form-control form-control-sm form-control-solid text-gray-700 fs-7 max-w-10px"
                                                name="total_leave_days" id="total_leave_days"
                                                value="{{$employeeDetail->total_days}}" readonly required>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex">
                                            <select id="" name="employee_name"
                                                class="form-select form-select-sm" required>
                                                <!-- <option value="">Select Employee Branch</option> -->
                                                @foreach($branchEmployeeDetails as $branchEmployeeDetail)
                                                    @if($branchEmployeeDetail->branch_id == $employeeDetail->branch_id)
                                                    <option value="{{ $branchEmployeeDetail->id }}" {{$employeeDetail->alternative_employee_id==$branchEmployeeDetail->id ? 'selected' : ''}} >{{ $branchEmployeeDetail->employee_name}}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex">
                                            <input type="text" class="form-control form-control-sm form-control-solid"
                                                name="" id="reason_for_leave"
                                                value="{{$employeeDetail->reason_for_leave}}" readonly>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex">
                                            <input type="text" class="form-control form-control-sm" name="remarks"
                                                value="{{$employeeDetail->remarks}}">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex">
                                            <select class="form-select form-select-sm" name="department_status"
                                                required>
                                                <option value="1"
                                                    {{ $employeeDetail->department_status==1?'selected':'' }}>Approved
                                                </option>
                                                <option value="0"
                                                    {{ $employeeDetail->department_status==0?'selected':'' }}>Pending
                                                </option>
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex">
                                            <div
                                                class="badge badge-light-{{$employeeDetail->hr_status==1 ? 'success' : 'info'}}">
                                                {{ $employeeDetail->hr_status==1?'Approved':'Pending' }}</div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex">
                                            <div
                                                class="badge badge-light-{{$employeeDetail->management_status==1 ? 'success' : 'info'}}">
                                                {{ $employeeDetail->management_status==1?'Approved':'Pending' }}</div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex">
                                            <div
                                                class="badge badge-light-{{$employeeDetail->final_status==1 ? 'success' : 'info'}}">
                                                {{ $employeeDetail->final_status==1?'Approved':'Pending' }}</div>
                                        </div>
                                    </td>
                                    @can('create leave approved department')
                                    <td>
                                        <div class="d-flex">
                                            <div>
                                                <button type="submit" data-kt-contacts-type="submit"
                                                    class="btn btn-sm btn-warning py-1" id="save">
                                                    <span class="indicator-label">Update</span>
                                                    <span class="indicator-progress">Please wait...
                                                        <span
                                                            class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                                </button>
                                            </div>

                                        </div>
                                    </td>
                                    @endcan
                                </tr>
                            </form>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="card card-flush d-none" id="leave_entry_div">
        <!--begin::Card body-->
        <div class="card-body py-3">
            <!--begin::Table-->
            <div id="kt_ecommerce_category_table_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered align-middle table-row-dashed fs-7 gy-5 mb-0">
                        <!--begin::Table head-->
                        <thead>
                            <!--begin::Table row-->
                            <tr class="text-start  fs-7 text-uppercase gs-0">
                                <th class="min-w-10px sorting"> ID</th>
                                <th class="min-w-20px sorting">
                                    Name</th>
                                <th class="min-w-30px sorting">
                                    Application</th>
                                <th class="min-w-10px sorting">
                                    Start Date</th>
                                <th class="min-w-10px sorting">
                                    End Date</th>
                                <th class="min-w-20px sorting">
                                    Reporting Date</th>
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

$('#changeBranch').on('change', function() {
    $('#pendimgLeaveDive').addClass('d-none');


    $('#selectEmployee').empty();
    $('#selectAlternativeEmployee').empty();

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
                const data = response.employees;
                const data2 = response.employeeDetails;

                // Do something with the data
                console.log('Data 1:', data);
                console.log('Data 2:', data2);

                $('#selectEmployee').append(
                    `<option value="">Select Employee</option>`
                );
                $('#selectAlternativeEmployee').append(
                    `<option value="">Select Alternative Employee</option>`
                );
                if (data) {
                    $.each(data, function(key, value) {
                        $('#selectEmployee').append(
                            `<option value="` + value.id + `">` + value.employee_name +
                            `</option>`
                        );
                        $('#selectAlternativeEmployee').append(
                            `<option required value="` + value.id + `">` + value
                            .employee_name +
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

                        var total_leave;
                        if (value.employee_total_leave == null) {
                            total_leave = value.total_leave;
                        } else {
                            total_leave = value.employee_total_leave.total_leave;
                        }
                        $('#total_due_leave').val(total_leave);

                        var final_status = value.final_status == 0 ? "Pending" : "Approved";
                        var management_status = value.management_status == 0 ? "Pending" :
                            "Approved";
                        var hr_status = value.hr_status == 0 ? "Pending" : "Approved";
                        var department_status = value.department_status == 0 ? "Pending" :
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
                                                        <a id ="leave_id" class="text-gray-700 fs-7  mb-1" >` +
                            value.id + `</a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    <div >
                                                        <a class="text-gray-700 fs-7  mb-1"
                                                           >` +
                            value.employee_id.employee_name +
                            `</a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    <div >
                                                        <a class="text-gray-700 fs-7  mb-1"
                                                           >` +
                            value.leave_application_date +
                            `</a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    <div >
                                                        <a class="text-gray-700 fs-7  mb-1"
                                                           >` +
                            value.leave_start_date +
                            `</a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    <div >
                                                        <a class="text-gray-700 fs-7  mb-1"
                                                           >` +
                            value.leave_end_date +
                            `</a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    <div >
                                                        <a class="text-gray-700 fs-7  mb-1"
                                                           >` +
                            value.reporting_date +
                            `</a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    <div >
                                                        <a class="text-gray-700 fs-7  mb-1"
                                                           >` +
                            value.total_days +
                            `</a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    <div >
                                                        <a id = "leave_id" class="text-gray-700 fs-7  mb-1"
                                                           >` +
                            value.alternative_employee_id.employee_name +
                            `</a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    <div >
                                                        <a class="text-gray-700 fs-7  mb-1"
                                                           >` +
                            value.reason_for_leave + `</a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    <div >
                                                        <a class="text-gray-700 fs-7  mb-1"
                                                           >` +
                            value.remarks + `</a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    <div >
                                                        <a class="text-gray-700 fs-7  mb-1"
                                                           >` +
                            department_status + `</a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    <div >
                                                        <a class="text-gray-700 fs-7  mb-1"
                                                           >` +
                            hr_status + `</a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    <div >
                                                        <a class="text-gray-700 fs-7  mb-1"
                                                           >` +
                            management_status + `</a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    <div >
                                                        <a class="text-gray-700 fs-7  mb-1"
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

$('#selectEmployee').on('change', function() {
    $('#total_leave_div').removeClass('d-none');
    $('#total_leave_days').val(1);

    $('#total_due_leave_div').removeClass('d-none');
    $('#total_due_leave').val(0);

    var employeeID = $(this).val();
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
                    $('#leave_entry_div').addClass('d-none');
                    $('#leave_settings').empty();

                    $.each(data, function(key, value) {

                        var total_leave;
                        if (value.employee_total_leave == null) {
                            total_leave = value.total_leave;
                        } else {
                            total_leave = value.employee_total_leave.total_leave;
                        }
                        $('#total_due_leave').val(total_leave);

                        var final_status = value.final_status == 0 ? "Pending" : "Approved";
                        var management_status = value.management_status == 0 ? "Pending" :
                            "Approved";
                        var hr_status = value.hr_status == 0 ? "Pending" : "Approved";
                        var department_status = value.department_status == 0 ? "Pending" :
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
                                                        <a id = "leave_id" class="text-gray-700 fs-7  mb-1"
                                                           >` +
                            value.id +
                            `</a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    <div >
                                                        <a class="text-gray-700 fs-7  mb-1"
                                                           >` +
                            value.employee_id.employee_name +
                            `</a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    <div >
                                                        <a class="text-gray-700 fs-7  mb-1"
                                                           >` +
                            value.leave_application_date +
                            `</a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    <div >
                                                        <a class="text-gray-700 fs-7  mb-1"
                                                           >` +
                            value.leave_start_date +
                            `</a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    <div >
                                                        <a class="text-gray-700 fs-7  mb-1"
                                                           >` +
                            value.leave_end_date +
                            `</a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    <div >
                                                        <a class="text-gray-700 fs-7  mb-1"
                                                           >` +
                            value.reporting_date +
                            `</a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    <div >
                                                        <a class="text-gray-700 fs-7  mb-1"
                                                           >` +
                            value.total_days +
                            `</a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    <div >
                                                        <a id = "leave_id" class="text-gray-700 fs-7  mb-1"
                                                           >` +
                            value.alternative_employee_id.employee_name +
                            `</a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    <div >
                                                        <a class="text-gray-700 fs-7  mb-1"
                                                           >` +
                            value.reason_for_leave + `</a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    <div >
                                                        <a class="text-gray-700 fs-7  mb-1"
                                                           >` +
                            value.remarks + `</a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    <div >
                                                        <a class="text-gray-700 fs-7  mb-1"
                                                           >` +
                            department_status + `</a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    <div >
                                                        <a class="text-gray-700 fs-7  mb-1"
                                                           >` +
                            hr_status + `</a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    <div >
                                                        <a class="text-gray-700 fs-7  mb-1"
                                                           >` +
                            management_status + `</a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    <div >
                                                        <a class="text-gray-700 fs-7  mb-1"
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
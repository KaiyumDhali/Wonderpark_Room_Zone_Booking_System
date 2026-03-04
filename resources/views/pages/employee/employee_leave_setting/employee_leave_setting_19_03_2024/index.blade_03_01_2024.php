<x-default-layout>
    <style>
        .table> :not(caption)>*>* {
            padding: 0.3rem !important;
        }
    </style>
    <div class="col-xl-12 py-10">

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

        <div class="card p-5 mb-3">
            <!--begin::Card title-->
            <div class="card-title">
                <div class="form-check form-check-custom form-check-success form-check-solid pe-10">
                    <input id="employeeCheck" class="form-check-input" type="checkbox" value="" />
                    <label class="form-check-label pt-2" for="">
                        <h3>Single Employee Leave Setting</h3>
                    </label>
                </div>
            </div>
        </div>
        <div class="card card-flush h-lg-100 d-none" id="single_employee_setting">
            <div class="card-header pt-7" id="kt_chat_contacts_header">
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
                    <h3>Single Employee Leave Setting</h3>
                </div>
            </div>
            <!--end::Card header-->
            <!--begin::Card body-->
            <div class="card-body py-0">
                <form id="singleLeaveForm" class="form fv-plugins-bootstrap5 fv-plugins-framework" method="POST"
                    action="{{ route('employee_leave_setting.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="row pb-5">
                        <div class="col-md-1">
                            <div class="fv-row mb-0 fv-plugins-icon-container">
                                <label class="fs-6 fw-semibold form-label mt-0">
                                    <span class="required">Select Year</span>
                                </label>
                                <select id="leave_year" name="leave_year" class="form-select form-select-sm" required>
                                    <option selected disabled>Select Year</option>
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
                        <div class="col-md-1">
                            <div class="fv-row mb-0 fv-plugins-icon-container">
                                <label class="fs-6 fw-semibold form-label mt-0">
                                    <span class="required">Company </span>
                                </label>
                                <select id="changeBranch" class="changeBranch form-select form-select-sm readonly"
                                    name="branch_id" required>
                                    <option selected disabled>Company </option>
                                    @foreach ($allEmpBranch as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="fv-row mb-0 fv-plugins-icon-container">
                                <label class="fs-6 fw-semibold form-label mt-0">
                                    <span class="required"> Department</span>
                                </label>
                                <select id="changeDepartmentEmployee"
                                    class="selectDepartment form-select form-select-sm" name="department_id" required>
                                    <option selected disabled> Department</option>
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
                                    <option selected disabled>Select Employee</option>
                                </select>
                            </div>
                        </div>
                        @can('create leave setting')
                            {{-- <input type="hidden" id="inputUpdate" name="_method" value=""> --}}
                            <div class="col-md-1">
                                <div class="fv-row mb-7">
                                    <label class="fs-6 fw-semibold form-label mt-0">
                                        <span class="required">Casual Leave</span>
                                    </label>
                                    <input type="number" class="form-control form-control-sm" name="casual_leave"
                                        id="casual_leave_single" step="1" pattern="^[-\d]\d*$" required>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="fv-row mb-7">
                                    <label class="fs-6 fw-semibold form-label mt-0">
                                        <span class="required">Sick Leave</span>
                                    </label>
                                    <input type="number" class="form-control form-control-sm" name="sick_leave"
                                        id="sick_leave_single" step="1" pattern="^[-\d]\d*$" required>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="fv-row mb-7">
                                    <label class="fs-6 fw-semibold form-label mt-0">
                                        <span class="">Annual Leave</span>
                                    </label>
                                    <input type="number" class="form-control form-control-sm" name="annual_leave"
                                        id="annual_leave_single" step="1" pattern="^[-\d]\d*$">
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="fv-row mb-7">
                                    <label class="fs-6 fw-semibold form-label mt-0">
                                        <span class="">Special Leave</span>
                                    </label>
                                    <input type="number" class="form-control form-control-sm" name="special_leave"
                                        id="special_leave_single" step="1" pattern="^[-\d]\d*$">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="fv-row mb-7">
                                    <label class="fs-6 fw-semibold form-label mt-0">
                                        <span class="">Remarks</span>
                                    </label>
                                    <input type="text" class="form-control form-control-sm" name="remarks"
                                        id="leave_remarks_single">
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="fv-row mb-7 mt-8">
                                    <button type="submit" data-kt-contacts-type="submit" class="btn btn-sm btn-primary">
                                        <span class="indicator-label">Save</span>
                                        <span class="indicator-progress">Please wait...
                                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                    </button>
                                </div>
                            </div>
                        @endcan
                    </div>
                </form>
            </div>
        </div>
        <div class="card card-flush h-lg-100 " id="employee_setting">
            <div class="card-header pt-7" id="kt_chat_contacts_header">
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
                    <h3>Employee Leave Setting</h3>
                </div>
            </div>
            <!--end::Card header-->
            <!--begin::Card body-->
            <div class="card-body py-0">
                <form id="leaveForm" class="form fv-plugins-bootstrap5 fv-plugins-framework" method="POST"
                    action="{{ route('employee_leave_setting.store') }}" enctype="multipart/form-data">
                    @csrf
                    <!-- <div class="row row-cols-1 row-cols-sm-4 rol-cols-md-1 row-cols-lg-4"> -->
                    <div class="row pb-5">
                        <div class="col-md-1">
                            <div class="fv-row mb-0 fv-plugins-icon-container">
                                <label class="fs-6 fw-semibold form-label mt-0">
                                    <span class="required">Select Year</span>
                                </label>
                                <input type="text" class="form-control form-control-sm" name="leave_year"
                                    value="{{ date('Y') }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="fv-row mb-0 fv-plugins-icon-container">
                                <label class="fs-6 fw-semibold form-label mt-0">
                                    <span class="required">Select Company </span>
                                </label>
                                <select id="changeBranch" class="changeBranch form-select form-select-sm readonly"
                                    name="branch_id" required>
                                    <option selected disabled>Select Company </option>
                                    @foreach ($allEmpBranch as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="fv-row mb-0 fv-plugins-icon-container">
                                <label class="fs-6 fw-semibold form-label mt-0">
                                    <span class="required">Select Department</span>
                                </label>
                                <select id="changeDepartment" class="selectDepartment form-select form-select-sm"
                                    name="department_id" required>
                                    <option selected disabled>Select Department</option>
                                </select>
                            </div>
                        </div>
                        @can('create leave setting')
                            <input type="hidden" id="inputUpdate" name="_method" value="">
                            <div class="col-md-1">
                                <div class="fv-row mb-7">
                                    <label class="fs-6 fw-semibold form-label mt-0">
                                        <span class="required">Casual Leave</span>
                                    </label>
                                    <input type="number" class="form-control form-control-sm" name="casual_leave"
                                        id="casual_leave" step="1" pattern="^[-\d]\d*$" required>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="fv-row mb-7">
                                    <label class="fs-6 fw-semibold form-label mt-0">
                                        <span class="">Sick Leave</span>
                                    </label>
                                    <input type="number" class="form-control form-control-sm" name="sick_leave"
                                        id="sick_leave" step="1" pattern="^[-\d]\d*$">
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="fv-row mb-7">
                                    <label class="fs-6 fw-semibold form-label mt-0">
                                        <span class="">Annual Leave</span>
                                    </label>
                                    <input type="number" class="form-control form-control-sm" name="annual_leave"
                                        id="annual_leave" step="1" pattern="^[-\d]\d*$">
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="fv-row mb-7">
                                    <label class="fs-6 fw-semibold form-label mt-0">
                                        <span class="">Special Leave</span>
                                    </label>
                                    <input type="number" class="form-control form-control-sm" name="special_leave"
                                        id="special_leave" step="1" pattern="^[-\d]\d*$">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="fv-row mb-7">
                                    <label class="fs-6 fw-semibold form-label mt-0">
                                        <span class="">Remarks</span>
                                    </label>
                                    <input type="text" class="form-control form-control-sm" name="remarks"
                                        id="leave_remarks">
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
                                    <button type="submit" data-kt-contacts-type="submit" class="btn btn-sm btn-primary"
                                        id="update">
                                        <span class="indicator-label">Update</span>
                                        <span class="indicator-progress">Please wait...
                                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                    </button>
                                </div>
                            </div>
                        @endcan
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="card card-flush " id="leave_settings_div">
        <!--begin::Card body-->
        <div class="card-body">
            <!--begin::Table-->
            <div id="kt_ecommerce_category_table_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered align-middle table-row-dashed fs-7 gy-5 mb-0"
                        id="kt_ecommerce_category_table">
                        <!--begin::Table head-->
                        <thead>
                            <!--begin::Table row-->
                            <tr class="text-start fs-7 text-uppercase gs-0">
                                <th class="min-w-50px sorting" tabindex="0"
                                    aria-controls="kt_ecommerce_category_table" rowspan="1" colspan="1"
                                    style="width: 50px;" aria-label="Category: activate to sort column ascending">
                                    Leave ID </th>
                                <th class="min-w-50px sorting" tabindex="0"
                                    aria-controls="kt_ecommerce_category_table" rowspan="1" colspan="1"
                                    style="width: 50px;" aria-label="Category: activate to sort column ascending">
                                    Employee Name </th>
                                <th class="min-w-50px sorting" tabindex="0"
                                    aria-controls="kt_ecommerce_category_table" rowspan="1" colspan="1"
                                    style="width: 50px;" aria-label="Category: activate to sort column ascending">
                                    Department </th>
                                <th class="min-w-50px sorting" tabindex="0"
                                    aria-controls="kt_ecommerce_category_table" rowspan="1" colspan="1"
                                    style="width: 50px;" aria-label="Category: activate to sort column ascending">
                                    Casual Leave</th>
                                <th class="min-w-50px sorting" tabindex="0"
                                    aria-controls="kt_ecommerce_category_table" rowspan="1" colspan="1"
                                    style="width: 50px;" aria-label="Category: activate to sort column ascending">
                                    Sick Leave</th>
                                <th class="min-w-50px sorting" tabindex="0"
                                    aria-controls="kt_ecommerce_category_table" rowspan="1" colspan="1"
                                    style="width: 50px;" aria-label="Category: activate to sort column ascending">
                                    Annual Leave</th>
                                <th class="min-w-50px sorting" tabindex="0"
                                    aria-controls="kt_ecommerce_category_table" rowspan="1" colspan="1"
                                    style="width: 50px;" aria-label="Category: activate to sort column ascending">
                                    Special Leave</th>
                                <th class="min-w-50px sorting" tabindex="0"
                                    aria-controls="kt_ecommerce_category_table" rowspan="1" colspan="1"
                                    style="width: 50px;" aria-label="Category: activate to sort column ascending">
                                    Total Leave</th>
                                <th class="min-w-50px sorting" tabindex="0"
                                    aria-controls="kt_ecommerce_category_table" rowspan="1" colspan="1"
                                    style="width: 50px;" aria-label="Category: activate to sort column ascending">
                                    Remarks</th>

                                @can('create leave setting')
                                <th class="min-w-50px sorting" tabindex="0"
                                    aria-controls="kt_ecommerce_category_table" rowspan="1" colspan="1"
                                    style="width: 50px;" aria-label="Category: activate to sort column ascending">
                                    Action
                                </th>
                                @endcan
                            </tr>
                        </thead>
                        <tbody class="fw-semibold text-gray-700" id="leave_settings">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-650px" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Leave Setting</h5>
                    <button type="button" class="btn btn-sm btn-icon btn-active-color-primary"
                        data-bs-dismiss="modal">
                        <span class="svg-icon svg-icon-1">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1"
                                    transform="rotate(-45 6 17.3137)" fill="currentColor"></rect>
                                <rect x="7.41422" y="6" width="16" height="2" rx="1"
                                    transform="rotate(45 7.41422 6)" fill="currentColor"></rect>
                            </svg>
                        </span>
                    </button>
                </div>
                {{-- <form id="editForm"> --}}
                <form id="kt_modal_edit_card_form_leave_setting"
                    class="form fv-plugins-bootstrap5 fv-plugins-framework" method="POST"
                    action="{{ route('update_leave_settings', ':editLeaveID') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="modal-body">
                        <div class="row">
                            <!-- First Column -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="hidden" id="editLeaveID" name="editLeaveID">

                                    <label for="editEmployeeName">Employee Name</label>
                                    <input type="text" class="form-control form-control-sm bg-light"
                                        id="editEmployeeName" name="employeeName" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="editCasualLeave">Casual Leave</label>
                                    <input type="text" class="form-control form-control-sm" id="editCasualLeave"
                                        name="casualLeave">
                                </div>
                                <div class="form-group">
                                    <label for="editAnnualLeave">Annual Leave</label>
                                    <input type="text" class="form-control form-control-sm" id="editAnnualLeave"
                                        name="annualLeave">
                                </div>
                            </div>

                            <!-- Second Column -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="editDepartmentName">Department Name</label>
                                    <input type="text" class="form-control form-control-sm bg-light"
                                        id="editDepartmentName" name="departmentName" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="editSickLeave">Sick Leave</label>
                                    <input type="text" class="form-control form-control-sm" id="editSickLeave"
                                        name="sickLeave">
                                </div>
                                <div class="form-group">
                                    <label for="editSpecialLeave">Special Leave</label>
                                    <input type="text" class="form-control form-control-sm" id="editSpecialLeave"
                                        name="specialLeave">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="editRemarks">Remarks</label>
                                    <input type="text" class="form-control form-control-sm" id="editRemarks"
                                        name="remarks">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="reset" class="btn btn-sm btn-light me-3"
                            data-bs-dismiss="modal">Discard</button>

                        <button type="submit" id="saveChanges" class="btn btn-sm btn-primary">
                            <span class="indicator-label">Submit</span>
                            <span class="indicator-progress">Please wait...
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</x-default-layout>

<script type="text/javascript">
    var checkbox = document.querySelector("input[id=employeeCheck]");
    var singleEmployeeSetting = document.querySelector("#single_employee_setting");
    var employeeSetting = document.querySelector("#employee_setting")
    checkbox.addEventListener('change', function() {
        if (this.checked) {
            singleEmployeeSetting.classList.remove("d-none");
            employeeSetting.classList.add("d-none");
        } else {
            singleEmployeeSetting.classList.add("d-none");
            employeeSetting.classList.remove("d-none");
        }
        console.log(reportTittle);
    })

    {
        $('#update').hide();
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

    $("#update").on("click", function(e) {
        e.preventDefault();
        var leaveId = $('#leave_id').text();
        var url = '{{ route('employee_leave_setting.update', ':leaveId') }}';
        url = url.replace(':leaveId', leaveId);
        $('#inputUpdate').val('PUT');
        $('#leaveForm').attr('action', url).submit();
    });

    function CallBack() {
        $.ajax({
            url: 'allEmployeeLeaveSetting',
            type: "GET",
            data: {
                "_token": "{{ csrf_token() }}"
            },
            dataType: "json",
            success: function(data) {
                // console.log(data);
                if (data.length > 0) {
                    $('#leave_settings').empty();
                    var totalLeave = 0;
                    $.each(data, function(key, value) {

                        var casual_leave = parseInt(value.casual_leave);
                        var sick_leave = parseInt(value.sick_leave);
                        var annual_leave = parseInt(value.annual_leave);
                        var special_leave = parseInt(value.special_leave);
                        totalLeave = casual_leave + sick_leave + annual_leave +
                            special_leave;

                        if (data.length > 0) {
                            console.log(value.id);
                            $('#casual_leave').val(casual_leave);
                            $('#sick_leave').val(sick_leave);
                            $('#annual_leave').val(annual_leave);
                            $('#special_leave').val(special_leave);
                            $('#leave_remarks').val(value.remarks);

                            $('#leave_settings_div').removeClass('d-none');
                            $('#update').show();
                            $('#save').hide();
                        }

                        $('#leave_settings').append(`
                            <tr>
                                <td>
                                    <div class="d-flex">
                                        <div class="ms-5">
                                            <a id="leave_id" class="text-gray-800 text-hover-primary fw-bold mb-1" >${value.id}</a>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <div class="ms-5">
                                            <a class="text-gray-800 text-hover-primary fw-bold mb-1" >${value.employee.employee_name}</a>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <div class="ms-5">
                                            <a class="text-gray-800 text-hover-primary fw-bold mb-1" >${value.emp_department.department_name}</a>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <div class="ms-5">
                                            <a class="text-gray-800 text-hover-primary fw-bold mb-1" >${casual_leave}</a>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <div class="ms-5">
                                            <a class="text-gray-800 text-hover-primary fw-bold mb-1" >${sick_leave}</a>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <div class="ms-5">
                                            <a class="text-gray-800 text-hover-primary fw-bold mb-1" >${annual_leave}</a>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <div class="ms-5">
                                            <a class="text-gray-800 text-hover-primary fw-bold mb-1" >${special_leave}</a>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <div class="ms-5">
                                            <a class="text-gray-800 text-hover-primary fw-bold mb-1" >${totalLeave}</a>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <div class="ms-5">
                                            <a class="text-gray-800 text-hover-primary fw-bold mb-1" >${value.remarks}</a>
                                        </div>
                                    </div>
                                </td>
                                @can('create leave setting')
                                <td class="text-start">
                                    <button type="button" class="btn btn-sm btn-success p-2 edit-button"
                                            data-leave-id="${value.id}"
                                            data-employee-name="${value.employee.employee_name}"
                                            data-department-name="${value.emp_department.department_name}"
                                            data-casual-leave="${casual_leave}"
                                            data-sick-leave="${sick_leave}"
                                            data-annual-leave="${annual_leave}"
                                            data-special-leave="${special_leave}"
                                            data-total-leave="${totalLeave}"
                                            data-remarks="${value.remarks}">
                                        Edit ID
                                    </button>
                                </td>
                                @endcan

                            </tr>
                        `);

                        $('#is_previous_due_s').val(totalLeave);
                    });
                } else {
                    //$('#jsdataerror').text('Not Found');
                    console.log("Null");
                    $('#leave_settings_div').addClass('d-none');
                    $('#update').hide();
                    $('#save').show();
                }

            }
        });
    };
    CallBack();

    $('.changeBranch').on('change', function() {
        var branchID = $(this).val();
        if (branchID) {
            $.ajax({
                url: 'branchDetails/' + branchID,
                type: "GET",
                data: {
                    "_token": "{{ csrf_token() }}"
                },
                dataType: "json",

                success: function(response) {
                    const data = response.departments;
                    const data2 = response.employeeDetails;
                    console.log(data2);
                    $('.selectDepartment').empty();
                    $('.selectDepartment').append(
                        `<option selected value="">Select Department</option>`
                    );
                    if (data) {
                        $.each(data, function(key, value) {
                            $('.selectDepartment').append(
                                `<option value="` + value.id + `">` + value
                                .department_name +
                                `</option>`
                            );
                        });
                    } else {
                        $('#jsdataerror').text('Not Found');
                    }
                    if (data2.length > 0) {
                        $('#leave_settings').empty();
                        var totalLeave = 0;
                        $.each(data2, function(key, value) {

                            var casual_leave = parseInt(value.casual_leave);
                            var sick_leave = parseInt(value.sick_leave);
                            var annual_leave = parseInt(value.annual_leave);
                            var special_leave = parseInt(value.special_leave);
                            totalLeave = casual_leave + sick_leave + annual_leave +
                                special_leave;

                            if (data2.length > 0) {
                                console.log(value.id);
                                $('#casual_leave').val(casual_leave);
                                $('#sick_leave').val(sick_leave);
                                $('#annual_leave').val(annual_leave);
                                $('#special_leave').val(special_leave);
                                $('#leave_remarks').val(value.remarks);

                                $('#leave_settings_div').removeClass('d-none');
                                $('#update').show();
                                $('#save').hide();
                            }

                            $('#leave_settings').append(`
                            <tr>
                                <td>
                                    <div class="d-flex">
                                        <div class="ms-5">
                                            <a id="leave_id" class="text-gray-800 text-hover-primary fw-bold mb-1" >${value.id}</a>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <div class="ms-5">
                                            <a class="text-gray-800 text-hover-primary fw-bold mb-1" >${value.employee.employee_name}</a>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <div class="ms-5">
                                            <a class="text-gray-800 text-hover-primary fw-bold mb-1" >${value.emp_department.department_name}</a>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <div class="ms-5">
                                            <a class="text-gray-800 text-hover-primary fw-bold mb-1" >${casual_leave}</a>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <div class="ms-5">
                                            <a class="text-gray-800 text-hover-primary fw-bold mb-1" >${sick_leave}</a>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <div class="ms-5">
                                            <a class="text-gray-800 text-hover-primary fw-bold mb-1" >${annual_leave}</a>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <div class="ms-5">
                                            <a class="text-gray-800 text-hover-primary fw-bold mb-1" >${special_leave}</a>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <div class="ms-5">
                                            <a class="text-gray-800 text-hover-primary fw-bold mb-1" >${totalLeave}</a>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <div class="ms-5">
                                            <a class="text-gray-800 text-hover-primary fw-bold mb-1" >${value.remarks}</a>
                                        </div>
                                    </div>
                                </td>
                                @can('create leave setting')
                                <td class="text-start">
                                    <button type="button" class="btn btn-sm btn-success p-2 edit-button"
                                            data-leave-id="${value.id}"
                                            data-employee-name="${value.employee.employee_name}"
                                            data-department-name="${value.emp_department.department_name}"
                                            data-casual-leave="${casual_leave}"
                                            data-sick-leave="${sick_leave}"
                                            data-annual-leave="${annual_leave}"
                                            data-special-leave="${special_leave}"
                                            data-total-leave="${totalLeave}"
                                            data-remarks="${value.remarks}">
                                        Edit ID
                                    </button>
                                </td>
                                @endcan
                            </tr>
                        `);

                            $('#is_previous_due_s').val(totalLeave);
                        });
                    } else {
                        $('#leave_settings_div').addClass('d-none');
                        $('#update').hide();
                        $('#save').show();
                    }
                }
            });
        } else {
            $('#jsdataerror').text('Not Found');
        }
    });

    $('#changeDepartmentEmployee').on('change', function() {
        $('#selectEmployee').empty();

        $('#casual_leave').val(0);
        $('#sick_leave').val(0);
        $('#annual_leave').val(0);
        $('#special_leave').val(0);
        $('#leave_remarks').val(" ");
        $('#leave_settings_div').addClass('d-none');

        var departmentID = $(this).val();
        if (departmentID) {
            $.ajax({
                url: 'departmentEmployee/' + departmentID,
                type: "GET",
                data: {
                    "_token": "{{ csrf_token() }}"
                },
                dataType: "json",
                success: function(data) {
                    $('#selectEmployee').append(
                        `<option selected disabled>Select Employee</option>`
                    );
                    if (data) {

                        // console.log('data: 'data);
                        $.each(data, function(key, value) {
                            $('#selectEmployee').append(
                                `<option value="` + value.id + `">` + value
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
    $('#selectEmployee').on('change', function() {
        $('#casual_leave_single').val(0);
        $('#sick_leave_single').val(0);
        $('#annual_leave_single').val(0);
        $('#special_leave_single').val(0);
        $('#leave_remarks_single').val(" ");
        // console.log(typeof null);
    });
    $('#changeDepartment').on('change', function() {
        $('#casual_leave').val(0);
        $('#sick_leave').val(0);
        $('#annual_leave').val(0);
        $('#special_leave').val(0);
        $('#leave_remarks').val(" ");
        $('#update').hide();
        $('#save').hide();
        var departmentID = $(this).val();
        if (departmentID) {
            $.ajax({
                url: 'departmentDetails/' + departmentID,
                type: "GET",
                data: {
                    "_token": "{{ csrf_token() }}"
                },
                dataType: "json",
                success: function(data) {
                    // console.log(data);
                    if (data.length > 0) {
                        $('#leave_settings').empty();
                        var totalLeave = 0;
                        $.each(data, function(key, value) {

                            var casual_leave = parseInt(value.casual_leave);
                            var sick_leave = parseInt(value.sick_leave);
                            var annual_leave = parseInt(value.annual_leave);
                            var special_leave = parseInt(value.special_leave);
                            totalLeave = casual_leave + sick_leave + annual_leave +
                                special_leave;

                            if (data.length > 0) {
                                console.log(value.id);
                                $('#casual_leave').val(casual_leave);
                                $('#sick_leave').val(sick_leave);
                                $('#annual_leave').val(annual_leave);
                                $('#special_leave').val(special_leave);
                                $('#leave_remarks').val(value.remarks);

                                $('#leave_settings_div').removeClass('d-none');
                                $('#update').show();
                                $('#save').hide();
                            }

                            $('#leave_settings').append(`
                            <tr>
                                <td>
                                    <div class="d-flex">
                                        <div class="ms-5">
                                            <a id="leave_id" class="text-gray-800 text-hover-primary fw-bold mb-1" >${value.id}</a>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <div class="ms-5">
                                            <a class="text-gray-800 text-hover-primary fw-bold mb-1" >${value.employee.employee_name}</a>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <div class="ms-5">
                                            <a class="text-gray-800 text-hover-primary fw-bold mb-1" >${value.emp_department.department_name}</a>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <div class="ms-5">
                                            <a class="text-gray-800 text-hover-primary fw-bold mb-1" >${casual_leave}</a>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <div class="ms-5">
                                            <a class="text-gray-800 text-hover-primary fw-bold mb-1" >${sick_leave}</a>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <div class="ms-5">
                                            <a class="text-gray-800 text-hover-primary fw-bold mb-1" >${annual_leave}</a>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <div class="ms-5">
                                            <a class="text-gray-800 text-hover-primary fw-bold mb-1" >${special_leave}</a>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <div class="ms-5">
                                            <a class="text-gray-800 text-hover-primary fw-bold mb-1" >${totalLeave}</a>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <div class="ms-5">
                                            <a class="text-gray-800 text-hover-primary fw-bold mb-1" >${value.remarks}</a>
                                        </div>
                                    </div>
                                </td>
                                @can('create leave setting')
                                <td class="text-start">
                                    <button type="button" class="btn btn-sm btn-success p-2 edit-button"
                                            data-leave-id="${value.id}"
                                            data-employee-name="${value.employee.employee_name}"
                                            data-department-name="${value.emp_department.department_name}"
                                            data-casual-leave="${casual_leave}"
                                            data-sick-leave="${sick_leave}"
                                            data-annual-leave="${annual_leave}"
                                            data-special-leave="${special_leave}"
                                            data-total-leave="${totalLeave}"
                                            data-remarks="${value.remarks}">
                                        Edit ID
                                    </button>
                                </td>
                                @endcan
                            </tr>
                        `);

                            $('#is_previous_due_s').val(totalLeave);
                        });
                    } else {
                        //$('#jsdataerror').text('Not Found');
                        console.log("Null");
                        $('#leave_settings_div').addClass('d-none');
                        $('#update').hide();
                        $('#save').show();
                    }

                }
            });
        } else {
            $('#jsdataerror').text('Not Found');
        }
    });

    // Open the modal when the edit button is clicked
    $(document).on('click', '.edit-button', function() {
        var data = $(this).data();

        $('#editLeaveID').val(data.leaveId);
        $('#editEmployeeName').val(data.employeeName);
        $('#editDepartmentName').val(data.departmentName);
        $('#editCasualLeave').val(data.casualLeave);
        $('#editSickLeave').val(data.sickLeave);
        $('#editAnnualLeave').val(data.annualLeave);
        $('#editSpecialLeave').val(data.specialLeave);
        $('#editTotalLeave').val(data.totalLeave);
        $('#editRemarks').val(data.remarks);

        // Add 'editLeaveID' to the form data
        $('#kt_modal_edit_card_form_leave_setting').attr('action', function(index, oldAction) {
            return oldAction.replace(':editLeaveID', data.leaveId);
        });

        $('#editModal').modal('show');
    });

    $(document).on('click', '#saveChanges', function() {
        // Submit the form with the id "kt_modal_edit_card_form_leave_setting"
        $('#kt_modal_edit_card_form_leave_setting').submit();
    });
</script>

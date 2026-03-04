<x-default-layout>
    <style>
        .table> :not(caption)>*>* {
            padding: 0.3rem !important;
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
    <div class="col-xl-12 py-5">
        <div class="card card-flush h-lg-100" id="kt_contacts_main">
            <div class="card-header py-0" id="kt_chat_contacts_header">
                <div class="card-title">
                    <!--begin::Svg Icon | path: icons/duotune/communication/com005.svg-->
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
                    <h3>Transaction Entry</h3>
                </div>
            </div> <span class="svg-icon svg-icon-1 me-2">

                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body py-0">
                    <form id="kt_ecommerce_settings_general_form"
                        class="form fv-plugins-bootstrap5 fv-plugins-framework" method="POST"
                        action="{{ route('employee_ledger.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="row row-cols-1 row-cols-sm-4 rol-cols-md-1 row-cols-lg-4 pb-3">
                            <div class="col">
                                <div class="fv-row mb-0 fv-plugins-icon-container">
                                    <label class="fs-6 fw-semibold form-label mt-0">
                                        <span class="required">Employee Name</span>
                                        <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip"
                                            aria-label="Enter the contact's email."
                                            data-bs-original-title="Enter the contact's email."
                                            data-kt-initialized="1"></i>
                                    </label>
                                    <select id="changeEmployee" class="form-control form-control-sm" name="employee_id">
                                        <option selected disabled>Select Employee</option>
                                        @foreach ($employees as $employee)
                                            <option value="{{ $employee->id }}">{{ $employee->employee_name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="fv-plugins-message-container invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="fv-row mb-0">
                                    <label class="fs-6 fw-semibold form-label mt-0">
                                        <span>Mobile Number</span>
                                    </label>
                                    <input type="text" class="form-control form-control-solid form-control-sm"
                                        name="" readonly id="employee_mobile_s" value="">
                                </div>
                            </div>
                            {{-- <div class="col">
                            <div class="fv-row mb-0">
                                <label class="fs-6 fw-semibold form-label mt-0">
                                    <span>Job Status</span>
                                </label>
                                <input type="text" class="form-control form-control-solid form-control-sm"
                                    name="" readonly id="employee_job_status_s" value="">
                            </div>
                        </div> --}}
                            <div class="col">
                                <div class="fv-row mb-0">
                                    <label class="fs-6 fw-semibold form-label mt-0">
                                        <span>Employee Salary</span>
                                    </label>
                                    <input type="text" class="form-control form-control-solid form-control-sm"
                                        name="" readonly id="employee_salary_s" value="">
                                </div>
                            </div>
                            <div class="col">
                                <div class="fv-row mb-0">
                                    <label class="fs-6 fw-semibold form-label mt-0">
                                        <span>Due Amount</span>
                                    </label>
                                    <input type="text" class="form-control form-control-solid form-control-sm"
                                        name="" readonly id="is_previous_due_s" value="">
                                </div>
                            </div>

                            @can('create employee ledger')
                                <div class="col">
                                    <div class="fv-row mb-0 fv-plugins-icon-container">
                                        <label class="fs-6 fw-semibold form-label mt-0">
                                            <span class="required">Payment Type</span>
                                        </label>
                                        <select class="form-select form-select-sm" name="ledger_title_id" required>
                                            {{-- <option selected disabled>Select Payment Type</option> --}}
                                            <option value="1">Advance</option>
                                            <option value="0">Advance Return</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="fv-row mb-7">
                                        <label class="fs-6 fw-semibold form-label mt-0">
                                            <span class="required">Amount</span>
                                        </label>
                                        <input type="number" class="form-control form-control-sm" name="input_amount"
                                            value="" required>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="fv-row mb-7">
                                        <label class="fs-6 fw-semibold form-label mt-0">
                                            <span class="">Remarks</span>
                                        </label>
                                        <input type="text" class="form-control form-control-sm" name="remarks"
                                            value="">
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="fv-row mb-7 mt-8">
                                        <button type="submit" data-kt-contacts-type="submit"
                                            class="btn btn-sm btn-primary">
                                            <span class="indicator-label">Save</span>
                                            <span class="indicator-progress">Please wait...
                                                <span
                                                    class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                        </button>
                                    </div>
                                </div>
                            @endcan
                        </div>
                    </form>
                </div>
        </div>
    </div>

    <div class="card card-flush " id="ledger_list_div_s">
        <div class="card-header py-0">
            <div class="card-title">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M20 14H18V10H20C20.6 10 21 10.4 21 11V13C21 13.6 20.6 14 20 14ZM21 19V17C21 16.4 20.6 16 20 16H18V20H20C20.6 20 21 19.6 21 19ZM21 7V5C21 4.4 20.6 4 20 4H18V8H20C20.6 8 21 7.6 21 7Z"
                        fill="currentColor"></path>
                    <path opacity="0.3"
                        d="M17 22H3C2.4 22 2 21.6 2 21V3C2 2.4 2.4 2 3 2H17C17.6 2 18 2.4 18 3V21C18 21.6 17.6 22 17 22ZM10 7C8.9 7 8 7.9 8 9C8 10.1 8.9 11 10 11C11.1 11 12 10.1 12 9C12 7.9 11.1 7 10 7ZM13.3 16C14 16 14.5 15.3 14.3 14.7C13.7 13.2 12 12 10.1 12C8.10001 12 6.49999 13.1 5.89999 14.7C5.59999 15.3 6.19999 16 7.39999 16H13.3Z"
                        fill="currentColor"></path>
                </svg>
                <h3>Employee Ledger</h3>
            </div>
        </div>
        <!--begin::Card body-->
        <div class="card-body pt-0">
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
                                    Ledger ID </th>
                                <th class="min-w-50px sorting" tabindex="0"
                                    aria-controls="kt_ecommerce_category_table" rowspan="1" colspan="1"
                                    style="width: 50px;" aria-label="Category: activate to sort column ascending">
                                    Employee Name </th>
                                <th class="min-w-50px sorting" tabindex="0"
                                    aria-controls="kt_ecommerce_category_table" rowspan="1" colspan="1"
                                    style="width: 50px;" aria-label="Category: activate to sort column ascending">
                                    Ledger Date</th>
                                <th class="min-w-50px sorting" tabindex="0"
                                    aria-controls="kt_ecommerce_category_table" rowspan="1" colspan="1"
                                    style="width: 50px;" aria-label="Category: activate to sort column ascending">
                                    Debit</th>
                                <th class="min-w-50px sorting" tabindex="0"
                                    aria-controls="kt_ecommerce_category_table" rowspan="1" colspan="1"
                                    style="width: 50px;" aria-label="Category: activate to sort column ascending">
                                    Credit</th>
                                <th class="min-w-50px sorting" tabindex="0"
                                    aria-controls="kt_ecommerce_category_table" rowspan="1" colspan="1"
                                    style="width: 50px;" aria-label="Category: activate to sort column ascending">
                                    Balance</th>
                                <th class="min-w-50px sorting" tabindex="0"
                                    aria-controls="kt_ecommerce_category_table" rowspan="1" colspan="1"
                                    style="width: 50px;" aria-label="Category: activate to sort column ascending">
                                    Remarks</th>
                            </tr>
                        </thead>
                        <tbody class="fw-semibold text-gray-700" id="ledger_list_s">
                            {{-- @if ($employeeDetails && count($employeeDetails) > 0)
                                @foreach ($employeeDetails as $employeeDetail)
                                    <tr>
                                        <td>{{ $employeeDetail->id }}</td>
                                        <td>{{ $employeeDetail->employee_id }}</td>
                                    </tr>
                                @endforeach
                            @endif --}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-default-layout>

<script type="text/javascript">
    $('#changeEmployee').on('change', function() {
        var employeeID = $(this).val();
        if (employeeID) {
            $.ajax({
                url: 'employeeDetails/' + employeeID,
                type: "GET",
                data: {
                    "_token": "{{ csrf_token() }}"
                },
                dataType: "json",
                success: function(response) {

                    const data = response.employeeDetails;
                    const data2 = response.employeeSalary;

                    console.log('Data 1:', data);
                    console.log('Data 2:', data2);

                    if (data2) {
                        var salary = data2.amount;
                        $('#employee_salary_s').val(salary);
                    } else {
                        var salary = 0;
                        $('#employee_salary_s').val(salary);
                    }

                    if (data) {

                        //$('#ledger_list_div_s').removeClass('d-none');
                        $('#ledger_list_s').empty();
                        var totalDue = 0;
                        $.each(data, function(key, value) {

                            if (value.ledger_id) {
                                $('#ledger_list_div_s').removeClass('d-none');
                            } else {
                                $('#ledger_list_div_s').addClass('d-none');
                            }
                            var mobile = value.mobile;
                            var job_status = value.job_status;
                            totalDue = totalDue + (value.credit - value.debit);

                            $('#employee_mobile_s').val(mobile);
                            $('#employee_job_status_s').val(job_status);

                            $('#ledger_list_s').append(
                                `<tr>
                                                                        <td>
                                                                            <div class="d-flex">
                                                                                <div class="ms-5">
                                                                                    <a class="text-gray-800 text-hover-primary  fw-bold mb-1"
                                                                                       data-kt-ecommerce-category-filter="category_name">` +
                                value.ledger_id +
                                `</a>
                                                                                </div>
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div class="d-flex">
                                                                                <div class="ms-5">
                                                                                    <a class="text-gray-800 text-hover-primary  fw-bold mb-1"
                                                                                       data-kt-ecommerce-category-filter="category_name">` +
                                value.employee_name +
                                `</a>
                                                                                </div>
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div class="d-flex">
                                                                                <div class="ms-5">
                                                                                    <a class="text-gray-800 text-hover-primary  fw-bold mb-1"
                                                                                       data-kt-ecommerce-category-filter="category_name">` +
                                value.ledger_date +
                                `</a>
                                                                                </div>
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div class="d-flex">
                                                                                <div class="ms-5">
                                                                                    <a class="text-gray-800 text-hover-primary  fw-bold mb-1"
                                                                                       data-kt-ecommerce-category-filter="category_name">` +
                                value.debit +
                                `</a>
                                                                                </div>
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div class="d-flex">
                                                                                <div class="ms-5">
                                                                                    <a class="text-gray-800 text-hover-primary  fw-bold mb-1"
                                                                                       data-kt-ecommerce-category-filter="category_name">` +
                                value.credit +
                                `</a>
                                                                                </div>
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div class="d-flex">
                                                                                <div class="ms-5">
                                                                                    <a class="text-gray-800 text-hover-primary  fw-bold mb-1"
                                                                                       data-kt-ecommerce-category-filter="category_name">` +
                                totalDue +
                                `</a>
                                                                                </div>
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div class="d-flex">
                                                                                <div class="ms-5">
                                                                                    <a class="text-gray-800 text-hover-primary  fw-bold mb-1"
                                                                                       data-kt-ecommerce-category-filter="category_name">` +
                                value.remarks + `</a>
                                                                                </div>
                                                                            </div>
                                                                        </td></tr>`);

                            $('#is_previous_due_s').val(totalDue);
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

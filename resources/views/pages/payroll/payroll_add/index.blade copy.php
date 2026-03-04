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
                <h3>Payroll Add</h3>
            </div>
        </div>
        <!--end::Card header-->
        <!--begin::Card body-->
        <div class="card-body py-0">
            <div class="row pb-5">
                <div class="col-md-2">
                    <div class="fv-row mb-0 fv-plugins-icon-container">
                        <label class="fs-6 fw-semibold form-label mt-0">
                            <span class=""> Employee</span>
                        </label>
                        <select class="form-select form-select-sm" name="employee_code" id="employee_code"
                            data-control="select2" data-hide-search="false">
                            <option selected value="0">All Employee</option>
                            @foreach ($allEmployee as $key => $value)
                                <option value="{{ $key }}">{{ $value }} - {{ $key }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="fv-row mb-0 fv-plugins-icon-container">
                        <label class="fs-6 fw-semibold form-label mt-0">
                            <span class=""> Type</span>
                        </label>
                        <select class="form-select form-select-sm" name="type_id" id="type_id" data-control="select2"
                            data-hide-search="false">
                            <option selected value="0">All Employee Type</option>
                            @foreach ($allEmpType as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="fv-row mb-0 fv-plugins-icon-container">
                        <label class="fs-6 fw-semibold form-label mt-0">
                            <span class=""> Department</span>
                        </label>
                        <select class="form-select form-select-sm" name="department_id" id="department_id"
                            data-control="select2" data-hide-search="false">
                            <option selected value="0">All Employee Department</option>
                            @foreach ($allEmpDepartment as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="fv-row mb-0 fv-plugins-icon-container">
                        <label class="fs-6 fw-semibold form-label mt-0">
                            <span class=""> Section</span>
                        </label>
                        <select class="form-select form-select-sm" name="section_id" id="section_id"
                            data-control="select2" data-hide-search="false">
                            <option selected value="0">All Employee Section</option>
                            @foreach ($allEmpSection as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="fv-row mb-0 fv-plugins-icon-container">
                        <label class="fs-6 fw-semibold form-label mt-0">
                            <span class=""> Line</span>
                        </label>
                        <select class="form-select form-select-sm" name="line_id" id="line_id" data-control="select2"
                            data-hide-search="false">
                            <option selected value="0">All Employee Line</option>
                            @foreach ($allEmpLine as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="fv-row mb-0 fv-plugins-icon-container">
                        <label class="fs-6 fw-semibold form-label mt-0">
                            <span class=""> Designation</span>
                        </label>
                        <select class="form-select form-select-sm" name="designation_id" id="designation_id"
                            data-control="select2" data-hide-search="false">
                            <option selected value="0">All Employee Designation</option>
                            @foreach ($allEmpDesignation as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="fv-row mb-0 fv-plugins-icon-container">
                        <label class="fs-6 fw-semibold form-label mt-0">
                            <span class=""> Salary Section</span>
                        </label>
                        <select class="form-select form-select-sm" name="salary_section_id" id="salary_section_id"
                            data-control="select2" data-hide-search="false">
                            <option selected value="0">All Salary Section</option>
                            @foreach ($allEmpSalarySection as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                {{-- <div class="col-md-1">
                        <div class="fv-row mb-7 mt-8">
                            <button type="submit" data-kt-contacts-type="submit" class="btn btn-sm btn-primary"
                                id="save">
                                <span class="indicator-label">Save</span>
                                <span class="indicator-progress">Please wait...
                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                            </button>
                        </div>
                    </div> --}}
            </div>

            <div id="tableDiv" class="card-body pt-5 px-0">
                <div id="messageContainer" class="alert my-5" style="display: none;"></div>

                <form id="myForm">

                    <div class="row py-5">
                        @foreach ($payrollHead as $key => $value)
                            <div class="col pt-8">
                                <div class="product fv-row fv-plugins-icon-container">
                                    <label class="form-check form-switch form-check-custom form-check-solid">
                                        <input class="form-check-input w-30px h-20px" type="checkbox" value="{{ $key }}" name="payroll_head_id" />
                                        <span class="form-check-label text-muted fs-6">{{ $value }}</span>
                                    </label>
                                </div>
                            </div>
                        @endforeach

                        <div class="col">
                            <label for="required" class="form-label">Effective Date</label>
                            <input type="date" class="form-control form-control-sm" name="effective_date" required/>
                        </div>
                        {{-- <div class="col-md-2">
                            <div class="fv-row mb-7">
                                <label class="fs-6 fw-semibold form-label mt-0">
                                    <span class="">Remarks</span>
                                </label>
                                <input type="text" class="form-control form-control-sm" name="remarks"
                                    id="remarks">
                            </div>
                        </div> --}}
                        <div class="col pt-3">
                            <button type="button" class="btn btn-sm btn-success px-5 mt-5"
                                onclick="submitSelectedRows()">Selected Submit</button>
                        </div>
                    </div>


                    <table class="table table-striped table-bordered align-middle table-row-dashed fs-7 gy-5 mb-0"
                        id="selected_employees_list">
                        <thead>
                            <tr class="text-start fs-7 text-uppercase gs-0">
                                <th class="w-80px"><input type="checkbox" id="select-all-checkbox"></th>
                                {{-- <th class="w-60px">Sl</th> --}}
                                <th class="w-60px">Code/ID</th>
                                <th class="min-w-150px">Name</th>
                                <th class="min-w-150px">Type</th>
                                <th class="min-w-150px">Department</th>
                                <th class="min-w-150px">Section</th>
                                <th class="min-w-150px">Line</th>
                                <th class="min-w-150px">Designation</th>
                                <th class="min-w-150px"> Sal. Section </th>
                            </tr>
                        </thead>
                        <tbody class="fw-semibold text-gray-700" id="">
                            <!-- Your table data will be dynamically added here using JavaScript -->
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
    </div>

</x-default-layout>
<meta name="csrf-token" content="{{ csrf_token() }}">

<script>
    // Add event listener to the "Select All" checkbox
    $('#select-all-checkbox').click(function() {
        $('.row-checkbox').prop('checked', this.checked);
    });

    function submitSelectedRows() {
        // Get all selected checkboxes
        let selectedRows = $('#selected_employees_list').find('.row-checkbox:checked');

        // Check if at least one row is selected
        if (selectedRows.length === 0) {
            alert('Please select at least one row.');
            return;
        }

        // Extract data from selected rows
        let selectedData = [];
        selectedRows.each(function() {
            let row = $(this).closest('tr');
            let rowData = {
                'employee_id': row.find('[name="selected_users[]"]').val(),
                'payroll_head_id': $('[name="payroll_head_id"]').val(),
                'effective_date': $('[name="effective_date"]').val(),
            };
            selectedData.push(rowData);
        });
        console.log('selectedData: ', selectedData);
        // Get CSRF token from meta tag
        const csrfToken = $('meta[name="csrf-token"]').attr('content');

        // Send the data to the Laravel controller using AJAX
        let url = '{{ route('payroll.store') }}';

        $.ajax({
            url: url,
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
            },
            data: {
                selectedData: selectedData
            },
            success: function(response) {
                $('#messageContainer').removeClass('alert-danger').addClass('alert-success').html(
                    'Success message here.').show();

                // Hide the message container after 5 seconds
                setTimeout(function() {
                    $('#messageContainer').hide();
                }, 5000);

                console.log(response);
            },
            error: function(error) {
                $('#messageContainer').removeClass('alert-success').addClass('alert-danger').html(
                    'Error message here.').show();

                // Hide the message container after 5 seconds
                setTimeout(function() {
                    $('#messageContainer').hide();
                }, 5000);
                console.error(error);
            }
        });
    }

    $(document).ready(function() {
        let table;

        $('select[name="employee_code"]').on('change', function() {
            CallBack();
        });
        $('select[name="type_id"]').on('change', function() {
            CallBack();
        });

        $('select[name="department_id"]').on('change', function() {
            CallBack();
        });

        $('select[name="section_id"]').on('change', function() {
            CallBack();
        });

        $('select[name="line_id"]').on('change', function() {
            CallBack();
        });

        $('select[name="designation_id"]').on('change', function() {
            CallBack();
        });

        $('select[name="salary_section_id"]').on('change', function() {
            CallBack();
        });

        var dataTableOptions = {
            // DataTable options...
        };
        table = $('#selected_employees_list').DataTable(dataTableOptions);

        function CallBack() {
            let employee_code = $('#employee_code').val();
            let type_id = $('#type_id').val();
            let department_id = $('#department_id').val();
            let section_id = $('#section_id').val();
            let line_id = $('#line_id').val();
            let designation_id = $('#designation_id').val();
            let salary_section_id = $('#salary_section_id').val();

            let url =
                '{{ route('employee_shorting', ['employee_code' => ':employee_code', 'type_id' => ':type_id', 'department_id' => ':department_id', 'section_id' => ':section_id', 'line_id' => ':line_id', 'designation_id' => ':designation_id', 'salary_section_id' => ':salary_section_id']) }}';
            url = url.replace(':employee_code', employee_code);
            url = url.replace(':type_id', type_id);
            url = url.replace(':department_id', department_id);
            url = url.replace(':section_id', section_id);
            url = url.replace(':line_id', line_id);
            url = url.replace(':designation_id', designation_id);
            url = url.replace(':salary_section_id', salary_section_id);
            $.ajax({
                url: url,
                type: "GET",
                data: {
                    "_token": "{{ csrf_token() }}"
                },
                dataType: "json",
                success: function(data) {
                    // console.log('data-table:', data);
                    table.clear().draw();
                    if (data.length > 0) {
                        $('#jsdataerror').text('');
                        $.each(data, function(key, value) {
                            let d = data[key];
                            table.row.add([
                                '<input type="checkbox" class="row-checkbox" name="selected_users[]" value="' +
                                d.id + '">',
                                d.employee_code, d.employee_name, d.type_name, d
                                .department_name, d.section_name, d.line_name, d
                                .designation_name, d.salary_section_name
                            ]);
                        });
                        table.draw();
                    } else {
                        $('#jsdataerror').text('No records found');
                        console.log('No records found');
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        }

        CallBack();
    });
</script>

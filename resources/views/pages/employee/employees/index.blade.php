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

    <!--begin::Content-->
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container">
            <!--begin::Products-->
            <div class="card card-flush">
                <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                    <!--begin::Card title-->
                    <div class="card-title">
                        <h3>All Employee List</h3>
                    </div>
                    <!--begin::Card toolbar-->
                    <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                        @can('create employee')
                            <a href="{{ route('employees.create') }}" class="btn btn-sm btn-primary">Add Employee</a>
                        @endcan
                    </div>
                </div>
                <!--begin::Card body-->
                <div class="card-body">
                    <div class="row d-flex">
                        <div class="col-md-2 py-2">
                            <div class="fv-row mb-0 fv-plugins-icon-container">
                                <label class="fs-6 fw-semibold form-label mt-0">
                                    <span class=""> Employee</span>
                                </label>
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

                        <div class="col-md-2 py-2">
                            <div class="fv-row mb-0 fv-plugins-icon-container">
                                <label class="fs-6 fw-semibold form-label mt-0">
                                    <span class=""> Type</span>
                                </label>
                                <select class="form-select form-select-sm" name="type_id" id="type_id"
                                    data-control="select2" data-hide-search="false">
                                    <option selected value="0">All Employee Type</option>
                                    @foreach ($allEmpType as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2 py-2">
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
                        <div class="col-md-2 py-2">
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
                        <div class="col-md-2 py-2">
                            <div class="fv-row mb-0 fv-plugins-icon-container">
                                <label class="fs-6 fw-semibold form-label mt-0">
                                    <span class=""> Line</span>
                                </label>
                                <select class="form-select form-select-sm" name="line_id" id="line_id"
                                    data-control="select2" data-hide-search="false">
                                    <option selected value="0">All Employee Line</option>
                                    @foreach ($allEmpLine as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2 py-2">
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
                        <div class="col-md-2 py-2">
                            <div class="fv-row mb-0 fv-plugins-icon-container">
                                <label class="fs-6 fw-semibold form-label mt-0">
                                    <span class=""> Salary Section</span>
                                </label>
                                <select class="form-select form-select-sm" name="salary_section_id"
                                    id="salary_section_id" data-control="select2" data-hide-search="false">
                                    <option selected value="0">All Employee Salary Section</option>
                                    @foreach ($allEmpSalarySection as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
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
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body pt-0">
                    <!--begin::Table-->
                    <table class="table table-striped table-bordered align-middle table-row-dashed fs-7 gy-5 mb-0"
                        id="employee_table">
                        <!--begin::Table head-->
                        <thead>
                            <!--begin::Table row-->
                            <tr class="text-start fs-7 text-uppercase gs-0">
                                <th class="min-w-50px">Code</th>
                                <th class="min-w-100px">Name</th>
                                <th class="min-w-50px">Type</th>
                                <th class="min-w-50px">Department</th>
                                <th class="min-w-100px">Section</th>
                                <th class="min-w-100px">Line</th>
                                <th class="min-w-100px">Designation</th>
                                <th class="min-w-100px">Salary Section</th>
                                <th class="min-w-100px">Actions</th>
                            </tr>
                            <!--end::Table row-->
                        </thead>
                        <!--end::Table head-->
                        <!--begin::Table body-->
                        <tbody class="fw-semibold text-gray-700">

                        </tbody>
                        <!--end::Table body-->
                    </table>
                    <!--end::Table-->
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Products-->
        </div>
        <!--end::Content container-->
    </div>
    <!--end::Content-->
</x-default-layout>

<script type="text/javascript">
    var name = 'Perfume PLC';
    name += `\n Employee List`;
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
    var table = $('#employee_table').DataTable(dataTableOptions);

    function CallBack(pdfdata) {
        let url;
        var employeeCode = $('#employee_code').val();
        var typeId = $('#type_id').val();
        var departmentId = $('#department_id').val();
        var sectionId = $('#section_id').val();
        var lineId = $('#line_id').val();
        var designationId = $('#designation_id').val();
        var salarySectionId = $('#salary_section_id').val();
        url =
            '{{ route('employees_search', ['employeeCode' => ':employeeCode', 'typeId' => ':typeId', 'departmentId' => ':departmentId', 'sectionId' => ':sectionId', 'lineId' => ':lineId', 'designationId' => ':designationId', 'salarySectionId' => ':salarySectionId', 'pdf' => ':pdf']) }}';

        url = url.replace(':employeeCode', employeeCode);
        url = url.replace(':typeId', typeId);
        url = url.replace(':departmentId', departmentId);
        url = url.replace(':sectionId', sectionId);
        url = url.replace(':lineId', lineId);
        url = url.replace(':designationId', designationId);
        url = url.replace(':salarySectionId', salarySectionId);
        url = url.replace(':pdf', pdfdata);

        // console.log(url);
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

                        var editRoute = @json(route('employees.edit', ['employee' => '_id_']));
                        var showRoute = @json(route('employees.show', ['employee' => '_id_']));

                        $('#jsdataerror').text('');
                        $.each(data, function(key, value) {
                            var editUrl = editRoute.replace('_id_', value.id);
                            var showUrl = showRoute.replace('_id_', value.id);

                            let d = data[key];

                            let action = `<td class="text-end">
                                        @can('write employee')
                                            <a href="${editUrl}" class="btn btn-sm btn-success hover-scale p-2" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                                                <i class="bi bi-check2-square fs-2 ms-1"></i>
                                            </a>
                                        @endcan
                                        <a href="${showUrl}" class="btn btn-sm btn-info hover-scale p-2" data-bs-toggle="tooltip" data-bs-placement="top" title="View">
                                            <i class="bi bi-eye fs-2 ms-1"></i>
                                        </a>
                                    </td>`
                            // console.log(d);                            
                            table.row.add([d.employee_code, d.employee_name, d.type_name, d
                                .department_name, d.section_name, d.line_name, d
                                .designation_name, d.salary_section_name, action
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
        }
    }
    CallBack("list");

    $('#searchBtn').on('click', function() {
        CallBack("list");
    });

    $('#downloadPdf').on('click', function() {
        CallBack("pdfurl");
    });
</script>

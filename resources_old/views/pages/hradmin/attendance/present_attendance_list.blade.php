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
                <h3>Present List</h3>
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
                                    <span class=""> Start Date</span>
                                </label>
                                <input id="start_date" type="date" name="dob"
                                    class="form-control form-control-sm ps-5 p-2"
                                    value="{{ date('Y-m-d', strtotime($startDate)) }}">
                            </div>
                        </div>
                        <div class="col-md-2 py-2">
                            <label class="fs-6 fw-semibold form-label mt-0">
                                <span class=""> End Date</span>
                            </label>
                            <div class="fv-row mb-0 fv-plugins-icon-container">
                                <input id="end_date" type="date" name="end_date"
                                    class="form-control form-control-sm ps-5 p-2"
                                    value="{{ date('Y-m-d', strtotime($startDate)) }}">
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
                <div id="tableDiv" class="card-body pt-0 table-responsive">
                    <table class="table table-striped table-bordered align-middle table-row-dashed fs-7 gy-5 mb-0"
                        id="absent_attendance_list">
                        <thead>
                            <tr class="text-start fs-7 text-uppercase gs-0">
                                <th class="min-w-50px"> ID</th>
                                <th class="min-w-100px"> Name</th>
                                <th class="min-w-100px"> Designation</th>
                                <th class="min-w-100px"> Department</th>
                                <th class="min-w-100px"> Section</th>
                                <th class="min-w-100px"> Line</th>
                                <th class="min-w-50px"> Date</th>
                                <th class="min-w-50px"> IN Time</th>
                                <th class="min-w-50px"> OUT Time</th>
                                <th class="min-w-100px">Remarks</th>
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
    var startDate = $('#start_date').val();
    var endDate = $('#end_date').val();
    var name = 'Perfume PLC';
    name += `\n Attendance Date :  ${startDate} - ${startDate}`;
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
    var table = $('#absent_attendance_list').DataTable(dataTableOptions);

    function CallBack(pdfdata) {
        let url;
        var employeeCode = $('#employee_code').val();
        var startDate = $('#start_date').val();
        var endDate = $('#end_date').val();
        var typeId = $('#type_id').val();
        var departmentId = $('#department_id').val();
        var sectionId = $('#section_id').val();
        var lineId = $('#line_id').val();
        var designationId = $('#designation_id').val();
        url =
            '{{ route('present_attendance_search', ['employeeCode' => ':employeeCode', 'typeId' => ':typeId', 'departmentId' => ':departmentId', 'sectionId' => ':sectionId', 'lineId' => ':lineId', 'designationId' => ':designationId', 'startDate' => ':startDate', 'endDate' => ':endDate', 'pdf' => ':pdf']) }}';

        url = url.replace(':employeeCode', employeeCode);
        url = url.replace(':typeId', typeId);
        url = url.replace(':departmentId', departmentId);
        url = url.replace(':sectionId', sectionId);
        url = url.replace(':lineId', lineId);
        url = url.replace(':designationId', designationId);
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
                    table.clear().draw();
                    if (data.length > 0) {
                        $('#jsdataerror').text('');
                        $.each(data, function(key, value) {
                            let d = data[key];
                            // console.log(d);
                            let remarks;
                            if (d.remarks === 'absences') {
                                remarks = '<span style="color: red">Absent</span>';
                            } else if (d.remarks === 'out_of_office') {
                                remarks = '<span style="color: green">Out of Office</span>';
                            } else if (d.remarks === null) {
                                remarks = '';
                            } else {
                                remarks = d.remarks; // For other cases
                            }
                            table.row.add([d.employee_code, d.employee_name, d.designation_name, d.department_name,
                             d.section_name, d.line_name, d._date, d.InTime, d.OutTime, remarks 
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

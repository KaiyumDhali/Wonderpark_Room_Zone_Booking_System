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

    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container  d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h3>All Leave Entry List</h3>
            </div>
        </div>
    </div>
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container ">
            <!--begin::Products-->
            <div class="card card-flush">
                <!--begin::Card header-->
                <div class="card-header align-items-center py-5 ps-7">
                    <!--begin::Card title-->
                    <div class="card-title">
                        <div class="d-flex align-items-center position-relative my-1 ">
                            <div class="card-body p-2">
                                <select class="form-select form-select-sm" data-control="select2" id="selectSection" name="section_id" required>
                                    <option value="0">Select All Section</option>
                                    @foreach ($allEmpSection as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="d-flex align-items-center position-relative my-1 ">
                            <div class="card-body p-2">
                                <div class="product fv-row fv-plugins-icon-container">
                                    <select class="form-select form-select-sm" data-control="select2" id="selectEmployee" name="employee_id" 
                                        required>
                                        <option value="0">Select All Employee</option>
                                    </select>
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
                        <div class="d-flex align-items-center position-relative my-1">
                            <div class="card-body p-2">
                                <div class="product fv-row fv-plugins-icon-container">
                                    <input id="end_date" type="date" name="end_date"
                                        class="form-control form-control-sm ps-5 p-2"
                                        value="{{ date('Y-m-d', strtotime($endDate)) }}">
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
                    </div>
                </div>

                {{-- leave list table  --}}
                <div id="tableDiv" class="card-body pt-0 table-responsive">
                    <table class="table table-striped table-bordered align-middle table-row-dashed fs-7 gy-5 mb-0"
                        id="kt_ecommerce_report_customer_orders_table">
                        <thead>
                            <tr class="fs-7 text-uppercase gs-0">
                                <th class="text-center min-w-50px"> ID</th>
                                <th class="text-center min-w-100px"> Section</th>
                                <th class="text-center min-w-100px"> Name</th>
                                <th class="text-center min-w-100px">Alternative</th>
                                <th class="text-center min-w-100px">Type</th>
                                <th class="text-center min-w-100px"> Application</th>
                                <th class="text-center min-w-100px"> Start Date</th>
                                <th class="text-center min-w-50px"> End Date</th>
                                <th class="text-center min-w-100px">Total Leave</th>
                                <th class="text-center min-w-100px">Remarks</th>
                                <th class="text-center min-w-100px">Final Status</th>
                                <th class="text-center min-w-100px">Action</th>
                            </tr>
                        </thead>
                        <tbody class="fw-semibold text-gray-700 text-center" id="leave_settings">

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

<script type="text/javascript">

    var startDate = $('#start_date').val();
    var endDate = $('#end_date').val();
    var name = 'Perfume PLC';
    name += `\n Date :  ${startDate} - ${startDate}`;
    var reportTittle = name;

    // data table function
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
    var table = $('#kt_ecommerce_report_customer_orders_table').DataTable(dataTableOptions);

    // defult open data serach get 
    function CallBack(pdfdata) {
        let url;
        var sectionID = $('#selectSection').val();
        var employeeID = $('#selectEmployee').val();
        var startDate = $('#start_date').val();
        var endDate = $('#end_date').val();
        
        url = '{{ route('employee_leave_entry_list_search', ['sectionID' => ':sectionID', 'employeeID' => ':employeeID', 'startDate' => ':startDate', 'endDate' => ':endDate', 'pdf' => ':pdf']) }}';

        url = url.replace(':sectionID', sectionID);
        url = url.replace(':employeeID', employeeID);
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
                    table.clear().draw();
                    if (data.length > 0) {

                        // console.log('data : ', data);
                        $('#jsdataerror').text('');
                        $.each(data, function(key, value) {
                            let d = data[key];
                            let deleteButton = '';
                            
                            // Check FinalStatus and set the delete button accordingly
                            if (d.FinalStatus === 'Pending') {
                                deleteButton = '<button class="btn btn-danger btn-sm deleteBtn delete-leave" data-leave-id="' + value.leaveID + '">Delete</button>';
                            } else if (d.FinalStatus === 'Approved') {
                                deleteButton = '<button class="btn btn-danger btn-sm deleteBtn delete-leave" disabled data-leave-id="' + value.leaveID + '">Delete</button>';
                            }
                            table.row.add([d.leaveID, d.SectionName, d.EmployeeName, d.AlternativeEmployeeName, d.LeaveType, d.LeaveApplicationDate, d.LeaveStartDate, d.LeaveEndDate, d.TotalDays, d.Remarks,
                                d.FinalStatus, deleteButton
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

    // pdf open
    $('#downloadPdf').on('click', function() {
        CallBack("pdfurl");
    });

    // section on change employee
    $('#selectSection').on('change', function() {
        $('#selectEmployee').empty();
        var sectionID = $(this).val();
        var url = '{{ route('sectionWaisEmployee', ':sectionID') }}';
        url = url.replace(':sectionID', sectionID);

        if (sectionID) {
            $.ajax({
                url: url,
                type: "GET",
                data: {
                    "_token": "{{ csrf_token() }}"
                },
                dataType: "json",
                success: function(data) {
                    $('#selectEmployee').append(
                        `<option selected value="0" >All Employee</option>`
                    );
                    if (data) {
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

    // leave list delete button on click action
    $('#kt_ecommerce_report_customer_orders_table').on('click', '.deleteBtn', function() {
        // Add event listener for delete button
        var row = table.row($(this).closest('tr'));
        var rowData = row.data();
        var leaveId = $(this).data('leave-id');
        console.log('leaveId: ', leaveId);
        // Perform deletion action, e.g., show confirmation dialog
        if (confirm('Are you sure you want to delete this record?')) {
            // Assuming you have an API endpoint to handle deletion, you can make an AJAX request here.
            let url;
            url = '{{ route('employee_leave_entry_list_destroy', ['id' => ':id']) }}';
            url = url.replace(':id', leaveId);

            $.ajax({
                url: url,
                type: 'POST', // or 'DELETE' depending on your route definition
                data: {
                    '_token': '{{ csrf_token() }}',
                    '_method': 'DELETE'
                },
                success: function(result) {
                    // Remove the row from the DataTable on successful deletion
                    row.remove().draw();
                    console.log('Record deleted successfully.');
                },
                error: function(error) {
                    console.error('Error deleting record:', error);
                }
            });
            
            // If you don't have an API endpoint and just want to remove the row from the table:
            row.remove().draw();
        }
    });
</script>

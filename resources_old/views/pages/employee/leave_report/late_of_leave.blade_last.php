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
                <h3>Late of Leave Report</h3>
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
                        <div id="year_div">
                            <div class="card-body p-2">
                                <div class="product fv-row fv-plugins-icon-container">
                                    <select class="form-select form-select-sm" id="year" name="year">
                                        @for ($year = '2023'; $year <= date('Y') + 7; $year++)
                                            <option value="{{ $year }}" {{ date('Y') == $year ? 'selected' : '' }}>{{ $year }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div id="month_div">
                            <div class="card-body p-2">
                                <div class="product fv-row fv-plugins-icon-container">
                                    <select class="form-select form-select-sm" id="month" name="month">
                                        <option value="">Month</option>
                                        <option value="january" {{ date('F') == 'January' ? 'selected' : '' }}>January</option>
                                        <option value="february" {{ date('F') == 'February' ? 'selected' : '' }}>February</option>
                                        <option value="march" {{ date('F') == 'March' ? 'selected' : '' }}>March</option>
                                        <option value="april" {{ date('F') == 'April' ? 'selected' : '' }}>April</option>
                                        <option value="may" {{ date('F') == 'May' ? 'selected' : '' }}>May</option>
                                        <option value="june" {{ date('F') == 'June' ? 'selected' : '' }}>June</option>
                                        <option value="july" {{ date('F') == 'July' ? 'selected' : '' }}>July</option>
                                        <option value="august" {{ date('F') == 'August' ? 'selected' : '' }}>August</option>
                                        <option value="september" {{ date('F') == 'September' ? 'selected' : '' }}>September</option>
                                        <option value="october" {{ date('F') == 'October' ? 'selected' : '' }}>October</option>
                                        <option value="november" {{ date('F') == 'November' ? 'selected' : '' }}>November</option>
                                        <option value="december" {{ date('F') == 'December' ? 'selected' : '' }}>December</option>
                                    </select>                                    
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
                        @can('create attendance')
                        @endcan
                    </div>
                </div>
                
                <div id="tableDiv" class="card-body pt-0">
                    <form id="submit-form" action="{{ route('late_of_leave_add') }}" method="post">
                        @csrf
                        <button type="button" class="btn btn-sm btn-primary" onclick="submitForm()">Submit Selected Rows</button>
                        <input type="hidden" name="selected_data" value="">
                    </form>
                    <table class="table table-striped table-bordered align-middle table-row-dashed fs-7 gy-5 mb-0" id="late_of_leave">
                        <thead>
                            <tr class="text-start fs-7 text-uppercase gs-0">
                                <th class="min-w-50px">Sl</th>
                                <th class="min-w-50px">Code</th>
                                <th class="min-w-100px">Employee Name</th>
                                <th class="min-w-100px">Department</th>
                                <th class="min-w-100px">Designation</th>
                                <th class="min-w-50px">Total Casual Leave</th>
                                <th class="min-w-50px">Balance Casual Leave</th>
                                <th class="min-w-50px">No of Late</th>
                                <th class="min-w-50px">Late of Leave</th>
                                <th class="min-w-100px"></th>
                            </tr>
                        </thead>
                        <tbody class="fw-semibold text-gray-700" id="attendance_list">
                            <!-- Your table data will be dynamically added here using JavaScript -->
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
<meta name="csrf-token" content="{{ csrf_token() }}">

<script>
    var year = $('#year').val();
    var month = $('#month').val();

    var name = 'Shahjalal Equity Management Ltd.';
    name += `\n Leave Month of :  ${month} ${year}`;
    
    var reportTittle = name;

    var dataTableOptions = {
        dom: '<"top"fB>rt<"bottom"lip>',
        buttons: [{
            extend: 'collection',
            text: 'Approved',
            className: 'btn btn-sm btn-light-primary',
            buttons: [ 
                {
                    text: 'Update Selected',
                    action: function () {
                        // Handle the update logic for selected rows based on radio button selection
                        updateSelectedRows();
                    }
                }
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

    // var table = $('#late_of_leave').DataTable(dataTableOptions);

    // Initialize DataTable
    var table = $('#late_of_leave').DataTable();

    // Enable multiple row selection
    $('#late_of_leave tbody').on('click', 'tr', function() {
        $(this).toggleClass('selected');
    });

    // Select all checkbox in the header
    $('#late_of_leave thead th:last').html('<input type="checkbox" id="select-all">');

    // Handle 'Select All' checkbox click
    $('#select-all').on('click', function() {
        var rows = table.rows().nodes();
        $(rows).toggleClass('selected', this.checked);
    });

    function CallBack(pdfdata) {
        let url;
        var year = $('#year').val();
        var month = $('#month').val();

        url = '{{ route('late_of_leave_report_search', ['year' => ':year', 'month' => ':month', 'pdf' => ':pdf']) }}';

        url = url.replace(':year', year);
        url = url.replace(':month', month);
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
                        $('#jsdataerror').text('');
                        $.each(data, function(key, value) {
                            let d = data[key];

                            console.log(d);
                            let late_leave = Math.floor(d.Late_Count / 4);
                            table.row.add([
                                d.eorder, 
                                d.employee_code, 
                                d.employee_name, 
                                d.department_name, 
                                d.designation_name, 
                                d.Total_Casual_Leave, 
                                d.Deu_Casual_leave, 
                                d.Late_Count, 
                                late_leave,
                                '<input type="checkbox" class="row-checkbox" name="selected_users[]" value="' + d.eorder + '">' 
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


// // Function to submit the form with selected rows
// function submitForm() {
//     var selectedRows = table.rows('.selected').data();

//     if (selectedRows.length > 0) {
//         // Prepare the selected data for submission
//         var formData = new FormData();

//         $.each(selectedRows, function(index, row) {
//             // Add each column data to the FormData
//             formData.append('eorder[]', row[0]);
//             formData.append('employee_code[]', row[1]);
//             // Repeat for other columns
//         });

//         // Include CSRF token in the headers
//         formData.append('_token', getCsrfToken());

//         // Submit the form with FormData
//         $.ajax({
//             url: $('#submit-form').attr('action'),
//             type: 'POST',
//             data: formData,
//             processData: false,
//             contentType: false,
//             success: function(response) {
//                 // Handle success response
//                 console.log(response);
//             },
//             error: function(error) {
//                 // Handle error response
//                 console.error(error);
//             }
//         });
//     } else {
//         alert('Please select at least one row.');
//     }
// }




    // Function to submit the form with selected rows
    function submitForm() {
        var selectedRows = table.rows('.selected').data();

        if (selectedRows.length > 0) {
            // Prepare the selected data for submission
            var selectedData = [];
            $.each(selectedRows, function(index, row) {
                selectedData.push(row[0]); // Assuming the first column (Sl) contains unique identifiers
                selectedData.push(row[1]); // Assuming the first column (Sl) contains unique identifiers
                selectedData.push(row[3]); // Assuming the first column (Sl) contains unique identifiers
                selectedData.push(row[4]); // Assuming the first column (Sl) contains unique identifiers
                selectedData.push(row[5]); // Assuming the first column (Sl) contains unique identifiers
            });

            // Add the selected data to a hidden input field in the form
            $('#submit-form').append('<input type="hidden" name="selected_data" value="' + selectedData.join(',') + '">');

            // Submit the form
            $('#submit-form').submit();
        } else {
            alert('Please select at least one row.');
        }
    }

</script>

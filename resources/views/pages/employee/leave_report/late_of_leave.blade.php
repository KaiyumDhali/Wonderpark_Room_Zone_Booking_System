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
                                    <select class="form-select form-select-sm" data-control="select2" id="year" name="year">
                                        @for ($year = '2023'; $year <= date('Y') + 7; $year++) <option
                                            value="{{ $year }}" {{ date('Y') == $year ? 'selected' : '' }}>
                                            {{ $year }}</option>
                                            @endfor
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div id="month_div">
                            <div class="card-body p-2">
                                <div class="product fv-row fv-plugins-icon-container">
                                    <select class="form-select form-select-sm" data-control="select2" id="month" name="month">
                                        <option value="">Month</option>
                                        <option value="january" {{ date('F') == 'January' ? 'selected' : '' }}>January
                                        </option>
                                        <option value="february" {{ date('F') == 'February' ? 'selected' : '' }}>
                                            February</option>
                                        <option value="march" {{ date('F') == 'March' ? 'selected' : '' }}>March
                                        </option>
                                        <option value="april" {{ date('F') == 'April' ? 'selected' : '' }}>April
                                        </option>
                                        <option value="may" {{ date('F') == 'May' ? 'selected' : '' }}>May</option>
                                        <option value="june" {{ date('F') == 'June' ? 'selected' : '' }}>June</option>
                                        <option value="july" {{ date('F') == 'July' ? 'selected' : '' }}>July</option>
                                        <option value="august" {{ date('F') == 'August' ? 'selected' : '' }}>August
                                        </option>
                                        <option value="september" {{ date('F') == 'September' ? 'selected' : '' }}>
                                            September</option>
                                        <option value="october" {{ date('F') == 'October' ? 'selected' : '' }}>October
                                        </option>
                                        <option value="november" {{ date('F') == 'November' ? 'selected' : '' }}>
                                            November</option>
                                        <option value="december" {{ date('F') == 'December' ? 'selected' : '' }}>
                                            December</option>
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
                    {{-- <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                        <a href="" id="downloadPdf" name="downloadPdf" type="button"
                            class="btn btn-sm btn-light-primary" target="_blank">
                            PDF Download
                        </a>
                        @can('create attendance')
                        @endcan
                    </div> --}}
                </div>                
                <div id="tableDiv" class="card-body pt-0">
                    <div id="messageContainer" class="alert my-5" style="display: none;"></div>

                    <form id="myForm">
                        <button type="button" class="btn btn-sm btn-success px-5" onclick="submitSelectedRows()">Selected Approved</button>

                        <table class="table table-striped table-bordered align-middle table-row-dashed fs-7 gy-5 mb-0"
                            id="late_of_leave">
                            <thead>
                                <tr class="text-start fs-7 text-uppercase gs-0">
                                    <th class="w-80px"><input type="checkbox" id="select-all-checkbox"></th>
                                    {{-- <th class="w-60px">Sl</th> --}}
                                    <th class="w-60px">Code</th>
                                    <th class="min-w-150px">Name</th>
                                    {{-- <th class="min-w-150px">Department</th>
                                    <th class="min-w-150px">Designation</th> --}}
                                    <th class="w-150px">Total Casual</th>
                                    <th class="w-150px">Balance Casual</th>
                                    <th class="w-150px">No of Late</th>
                                    <th class="w-150px">Late of Leave</th>
                                </tr>
                            </thead>
                            <tbody class="fw-semibold text-gray-700" id="attendance_list">
                                <!-- Your table data will be dynamically added here using JavaScript -->
                            </tbody>
                        </table>
                    </form>
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

    // var name = 'Shahjalal Equity Management Ltd.';
    // name += `\n Leave Month of :  ${month} ${year}`;
    // var reportTittle = name;

    // Initialize DataTable
    // var table = $('#late_of_leave').DataTable();
    var table = $('#late_of_leave').DataTable({
        pageLength: 100,
        // other DataTable options and configurations can go here
    });

    // Add event listener to the "Select All" checkbox
    $('#select-all-checkbox').click(function() {
        $('.row-checkbox').prop('checked', this.checked);
    });

    function validateNumberInput(input) {
        // Remove any non-digit characters
        input.value = input.value.replace(/[^0-9]/g, '');

        // Ensure the input is a positive number
        if (input.value < 0) {
            input.value = '';
        }
    }
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
                        // Add data to the table
                        $.each(data, function(key, value) {
                            let d = data[key];
                            let late_leave = Math.floor(d.Late_Count / 4);
                            table.row.add([
                                '<input type="checkbox" class="row-checkbox" name="selected_users[]" value="' + d.eid + '">',
                                d.employee_code,d.employee_name,d.Total_Casual_Leave,d.Deu_Casual_leave,
                                '<input type="text" class="form-control form-control-sm" name="late_count[]" readonly  value="' + d.Late_Count + '">',
                                '<input type="text" class="form-control form-control-sm" name="late_leave[]" readonly  oninput="validateNumberInput(this)" value="' + late_leave + '">'
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

    
    function submitSelectedRows() {
        // Get all selected checkboxes
        let selectedRows = $('#late_of_leave').find('.row-checkbox:checked');

        // Check if at least one row is selected
        if (selectedRows.length === 0) {
            alert('Please select at least one row.');
            return;
        }

        // Extract data from selected rows
        let selectedData = [];
        selectedRows.each(function () {
            let row = $(this).closest('tr');
            let rowData = {
                'selected_users': row.find('[name="selected_users[]"]').val(),
                'late_count': row.find('[name="late_count[]"]').val(),
                'late_leave': row.find('[name="late_leave[]"]').val(),
                'month': $('#month').val(),
                'year': $('#year').val(),
                // Add other fields as needed
            };
            selectedData.push(rowData);
            console.log(selectedData);
        });
        console.log('selectedData: ', selectedData);
        // Get CSRF token from meta tag
        const csrfToken = $('meta[name="csrf-token"]').attr('content');

        // Send the data to the Laravel controller using AJAX
        $.ajax({
            url: '{{ route("late_of_leave_add") }}',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
            },
            data: { selectedData: selectedData },
            success: function (response) {
                $('#messageContainer').removeClass('alert-danger').addClass('alert-success').html('Success message here.').show();

                // Hide the message container after 5 seconds
                setTimeout(function () {
                    $('#messageContainer').hide();
                }, 5000);

                console.log(response);
            },
            error: function (error) {
                $('#messageContainer').removeClass('alert-success').addClass('alert-danger').html('Error message here.').show();

                // Hide the message container after 5 seconds
                setTimeout(function () {
                    $('#messageContainer').hide();
                }, 5000);
                console.error(error);
            }
        });
    }

</script>

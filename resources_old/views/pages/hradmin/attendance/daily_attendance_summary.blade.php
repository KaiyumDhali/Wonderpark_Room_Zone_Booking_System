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

        table.dataTable thead th,
        table.dataTable thead td,
        table.dataTable tfoot th,
        table.dataTable tfoot td {
            text-align: center;
        }
    </style>

    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container  d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h3>Daily Attendance Summary</h3>
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
                                    <span class=""> Start Date</span>
                                </label>
                                <input id="start_date" type="date" name="dob"
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
                        id="daily_attendance_summary">
                        <thead>
                            <tr style="text-align: center; vertical-align: middle;">
                                <th class="min-w-100px" rowspan="2"> Section</th>
                                <th class="min-w-100px" rowspan="2"> Male</th>
                                <th class="min-w-100px" rowspan="2"> Female</th>
                                <th class="min-w-100px" rowspan="2"> Total</th>
                                <th style="text-align: center; vertical-align: middle;" colspan="2">Present</th>
                                <th style="text-align: center; vertical-align: middle;" colspan="2">Absent</th>
                                <th class="min-w-100px" rowspan="2"> Total Present</th>
                                <th class="min-w-100px" rowspan="2"> Total Absent</th>
                                <th class="min-w-100px" rowspan="2"> Present (%)</th>
                                <th class="min-w-100px" rowspan="2"> Absent (%)</th>
                            </tr>
                            <tr class="text-start fs-7 text-uppercase gs-0">
                                <th class="min-w-100px"> Male</th>
                                <th class="min-w-100px"> Female</th>

                                <th class="min-w-100px"> Male</th>
                                <th class="min-w-100px"> Female</th>
                            </tr>
                        </thead>
                        <tbody class="fw-semibold text-gray-700 text-center" id="attendance_list">

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
    var name = 'Perfume PLC';
    name += `\n Attendance Summary Date :  ${startDate} `;
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
    var table = $('#daily_attendance_summary').DataTable(dataTableOptions);

    function CallBack(pdfdata) {
        let url;
        var startDate = $('#start_date').val();
        var sectionId = $('#section_id').val();
        url =
            '{{ route('daily_attendance_summary_search', ['sectionId' => ':sectionId', 'startDate' => ':startDate', 'pdf' => ':pdf']) }}';

        url = url.replace(':sectionId', sectionId);
        url = url.replace(':startDate', startDate);
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
                    //console.log('data:', data);
                    table.clear().draw();
                    if (data.length > 0) {
                        $('#jsdataerror').text('');

                        // Initialize variables to store column sums
                        let maleCountSum = 0;
                        let femaleCountSum = 0;
                        let totalSum = 0;
                        let malePresentSum = 0;
                        let femalePresentSum = 0;
                        let totalPSum = 0;
                        let maleAbsentSum = 0;
                        let femaleAbsentSum = 0;
                        let totalASum = 0;
                        let ppSum = 0;
                        let apSum = 0;

                        $.each(data, function(key, value) {
                            let d = data[key];

                            // Add values to column sums
                            maleCountSum += parseFloat(d.male_count);
                            femaleCountSum += parseFloat(d.female_count);
                            totalSum += parseFloat(d.total);
                            malePresentSum += parseFloat(d.male_present);
                            femalePresentSum += parseFloat(d.female_present);
                            totalPSum += parseFloat(d.TotalP);
                            maleAbsentSum += parseFloat(d.male_absent);
                            femaleAbsentSum += parseFloat(d.female_absent);
                            totalASum += parseFloat(d.TotalA);
                            
                            // Format PP and AP values to two decimal places
                            let formattedPP = parseFloat(d.PP).toFixed(2);
                            let formattedAP = parseFloat(d.AP).toFixed(2);

                            table.row.add([
                                d.section_name,
                                d.male_count,
                                d.female_count,
                                d.total,
                                d.male_present,
                                d.female_present,
                                d.male_absent,
                                d.female_absent,
                                d.TotalP,
                                d.TotalA,
                                `<span style="color: green">${formattedPP}</span>`,
                                `<span style="color: red">${formattedAP}</span>`,
                            ]).draw();
                        });

                        ppSum = totalPSum*100/totalSum;
                        apSum = totalASum*100/totalSum;
                        // Add the sum rows to the table
                        table.row.add([
                            'Total:',
                            maleCountSum,
                            femaleCountSum,
                            totalSum,
                            malePresentSum,
                            femalePresentSum,
                            maleAbsentSum,
                            femaleAbsentSum,
                            totalPSum,
                            totalASum,
                            `<span style="color: green">${ppSum.toFixed(2)}</span>`,
                            `<span style="color: red">${apSum.toFixed(2)}</span>`,
                        ]).draw();
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

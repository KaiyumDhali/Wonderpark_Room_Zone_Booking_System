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

        .table> :not(caption)>*>* {
            padding: 0.3rem !important;
        }

        .input-group {
            width: 100%;
            height: 0px !important;
            padding-bottom: 35px !important;
            /* padding: 0px 0px !important;
            margin: 0px !important; */
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

    <!--begin::Toolbar Title-->
    <div id="kt_app_toolbar" class="app-toolbar">
        <!--begin::Toolbar container-->
        <div id="kt_app_toolbar_container" class="app-container  d-flex flex-stack">
            <!--begin::Page title-->
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <!--begin::Title-->
                <h3>Monthly Salary Sheet</h3>
                <!--end::Title-->
            </div>
            <!--end::Page title-->
        </div>
        <!--end::Toolbar container-->
    </div>
    <!--end::Toolbar Title-->

    {{-- Start Monthly Salary Sheet Generate --}}
    @can('create monthly salary')
    <div class="col-xl-12 py-5">
        <div class="card">
            <div class="card-header py-5 gap-2 gap-md-5">
                <div class="card-title">
                    <h2>Salary Generate</h2>
                </div>
                <div class="card-title">
                    <form action="{{ route('monthly_salaries.store') }}" method="POST" id="salary_sheet_form">
                        @csrf
                        <div class="d-flex">
                            {{-- <div class="">
                                <select class="form-select form-select-sm" data-control="select2" data-control="select2" id="selectBranch" name="branch_id" required>
                                    <option value="">Select Branch</option>
                                    @foreach ($allEmpBranch as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div> --}}
                            <div class="mx-4">
                                <div class="input-group input-group-sm" id="kt_td_picker_year_month_only"
                                    data-td-target-input="nearest" data-td-target-toggle="nearest">
                                    <input name="selectedmonth" id="kt_td_picker_year_month_only_input" type="text"
                                        class="form-control" data-td-target="#kt_td_picker_year_month_only" />
                                    <span class="input-group-text" data-td-target="#kt_td_picker_year_month_only"
                                        data-td-toggle="datetimepicker">
                                        <i class="ki-duotone ki-calendar fs-2">
                                            <span class="path1"></span><span class="path2"></span>
                                        </i>
                                    </span>
                                </div>
                            </div>
                            <div class="float-end">
                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip"
                                    title="{{ __('Generate') }}" data-original-title="{{ __('Generate Salary') }}"
                                    onclick="checkDateAndSubmit()">
                                    {{ __('Generate') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endcan
    {{-- End Monthly Salary Sheet Generate --}}

    <!--begin::Content-->
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="">
            <!--begin::Products-->
            <div class="card card-flush">
                <!--begin::Card header-->
                <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                    <!--begin::PDF Download Card-->
                    <div class="card-title">
                        <div class="d-flex align-items-center position-relative my-1">
                            <a href="" id="downloadPdf" name="downloadPdf" type="button"
                                class="btn btn-sm btn-light-primary" target="_blank">
                                PDF Download
                            </a>
                        </div>
                        <div id="kt_ecommerce_report_customer_orders_export" class="d-none"></div>
                    </div>
                    <!--end::PDF Download Card-->

                    <!--begin::Search Card-->
                    <div class="card-title">
                        {{-- <div class="d-flex align-items-center position-relative my-1 ">
                            <div class="card-body p-2">
                                <select class="form-select form-select-sm" data-control="select2" id="selectBranchSearch" name="branch_id" required>
                                    <option value="0">All Branch</option>
                                    @foreach ($allEmpBranch as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div> --}}
                        {{-- <div class="d-flex align-items-center position-relative my-1 ">
                            <div class="card-body p-2">
                                <div class="product fv-row fv-plugins-icon-container">
                                    <select id="selectEmployee" name="employee_id" class="form-select form-select-sm"
                                        required>
                                        <option value="0">All Employee</option>
                                    </select>
                                </div>
                            </div>
                        </div> --}}
                        <div class="d-flex align-items-center position-relative my-1 ">
                            <div class="card-body p-2">
                                <div class="input-group input-group-sm" id="kt_td_picker_year_month_only_search"
                                    data-td-target-input="nearest" data-td-target-toggle="nearest">
                                    <input name="selectedmonth" id="kt_td_picker_year_month_only_search_input" type="text"
                                        class="form-control" data-td-target="#kt_td_picker_year_month_only_search" />
                                    <span class="input-group-text" data-td-target="#kt_td_picker_year_month_only_search"
                                        data-td-toggle="datetimepicker">
                                        <i class="ki-duotone ki-calendar fs-2">
                                            <span class="path1"></span><span class="path2"></span>
                                        </i>
                                    </span>
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
                    <!--end::Search Card -->
                </div>
                <!--end::Card header-->

                <!--begin::Card body-->
                <div class="card-body pt-0">
                    <!--begin::Table-->
                    <table class="table table-striped table-bordered align-middle table-row-dashed fs-7 gy-5 mb-0"
                        id="kt_ecommerce_report_customer_orders_table">
                        <!--begin::Table head-->
                        <thead>
                            <tr style="text-align: center; vertical-align: middle;">
                                <th class="min-w-50px" rowspan="2">ID</th>
                                <th class="min-w-100px" rowspan="2">Employee Id</th>
                                <th class="min-w-100px" rowspan="2">Employee Name</th>
                                {{-- <th class="min-w-100px" rowspan="2">Designation</th> --}}
                                <th style="text-align: center; vertical-align: middle;" colspan="7">Income</th>
                                <th style="text-align: center; vertical-align: middle;" colspan="3">Deduction</th>
                                <th class="text-start min-w-80px" rowspan="2">Net Payable</th>
                                <th class="text-start min-w-80px" rowspan="2">Remarks</th>
                            </tr>
                            <!--begin::Table row-->
                            <tr class="text-start fs-7 text-uppercase gs-0"
                                style="text-align: center; vertical-align: middle;">
                                <th class="min-w-80px">Basic</th>
                                <th class="min-w-80px">House Rent</th>
                                <th class="min-w-80px">Medical</th>
                                <th class="min-w-80px">Gross Salary</th>
                                <th class="min-w-80px">P/F</th>
                                <th class="min-w-80px">Other</th>
                                <th class="min-w-80px">Total Payable</th>
                                <th class="text-start min-w-80px">Tax</th>
                                <th class="text-start min-w-80px">P/F</th>
                                <th class="text-start min-w-80px">Other</th>

                            </tr>
                            <!--end::Table row-->
                        </thead>
                        <!--end::Table head-->
                        <!--begin::Table body-->
                        <tbody class="fw-semibold text-gray-700" id="attendance_list">

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
    
    // monthly search 
    const element2 = document.getElementById("kt_td_picker_year_month_only_search");
    // Get the current date
    const currentDate = new Date();
    // Subtract one month from the current date
    currentDate.setMonth(currentDate.getMonth() - 1);
    // Set defaultDate to the previous month
    new tempusDominus.TempusDominus(element2, {
        localization: {
            locale: "dhaka",
            startOfTheWeek: 1,
            format: "yyyy-MM"
        },
        display: {
            viewMode: "months",
            components: {
                decades: false,
                year: true,
                month: true,
                date: false,
                hours: false,
                minutes: false,
                seconds: false
            }
        },
        defaultDate: currentDate
    });

    var month = $('#kt_td_picker_year_month_only_search_input').val();
    //var month = '2023-11';
    var name = 'Shahjalal Equity Management Ltd';
    name += `\n Date :  ${month}`;
    var reportTittle = name;

    var dataTableOptions = {
        dom: '<"top"fB>rt<"bottom"lip>',
        buttons: [{
            extend: 'collection',
            text: 'Export',
            className: 'btn btn-sm btn-light-primary',

            buttons: [{
                    extend: 'excel',
                    text: 'Excel',
                    title: `${reportTittle}`,
                    className: 'btn btn-sm btn-light-primary',
                },
                {
                    extend: 'copy',
                    text: 'Copy',
                    title: `${reportTittle}`, // Set custom title for Copy
                    className: 'btn btn-sm btn-light-primary',
                },
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
    var table = $('#kt_ecommerce_report_customer_orders_table').DataTable(dataTableOptions);


    function CallBack(pdfdata) {
        let url;
        // var employeeID = $('#selectEmployee').val();
        // var branchID = $('#selectBranchSearch').val();
        var month = $('#kt_td_picker_year_month_only_search_input').val();
        url = '{{ route('monthly_salaries_list', ['month' => ':month', 'pdf' => ':pdf']) }}';
        // url = url.replace(':employeeID', employeeID);
        // url = url.replace(':branchID', branchID);
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
                    console.log('data:', data);
                    table.clear().draw();
                    if (data.length > 0) {
                        $('#jsdataerror').text('');
                        $.each(data, function(key, value) {
                            let d = data[key];

                            // Income
                            var baseSalary = parseFloat(d.Base_Salary) || 0;
                            var houseRent = parseFloat(d.House_Rent) || 0;
                            var medical = parseFloat(d.Medical) || 0;
                            var pf = parseFloat(d.PF) || 0;
                            var others = parseFloat(d.iOthers) || 0;
                            // Deduction
                            var TAX = parseFloat(d.TAX) || 0;
                            var dPF = parseFloat(d.dPF) || 0;
                            var dOthers = parseFloat(d.dOthers) || 0;
                            // Calculate total payable
                            var Total_Payable = baseSalary + houseRent + medical + pf + others;
                            var Gross_Salary = baseSalary + houseRent + medical;
                            var Net_Payable = Total_Payable - TAX - dPF - dOthers;
                            var Remarks = ""

                            table.row.add([d.employee_order, d.employee_code, d.employee_name,
                                d.Base_Salary, d.House_Rent, d.Medical, Gross_Salary, d.PF, d.iOthers,
                                Total_Payable, d.TAX, d.dPF, d.dOthers, Net_Payable, Remarks
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


    // Get the element with the ID "kt_td_picker_year_month_only"
    const element = document.getElementById("kt_td_picker_year_month_only");
    // Create a Tempus Dominus instance with the updated configuration
    new tempusDominus.TempusDominus(element, {
        localization: {
            locale: "dhaka",
            startOfTheWeek: 1,
            format: "yyyy-MM"
        },
        display: {
            viewMode: "months",
            components: {
                decades: false,
                year: true,
                month: true,
                date: false,
                hours: false,
                minutes: false,
                seconds: false
            }
        },
    });
    // Function to check the date and submit the form
    function checkDateAndSubmit() {
        const selectedMonthInput = document.getElementById("kt_td_picker_year_month_only_input");
        const selectedMonthValue = selectedMonthInput.value;

        // const selectBranchGenerateInput = document.getElementById("selectBranch");
        // const selectBranchGenerate = selectBranchGenerateInput.value;

        // if (!selectBranchGenerate) {
        //     alert("Please select a Branch before submitting.");
        // }

        if (!selectedMonthValue) {
            alert("Please select a date before submitting.");
        } else {
            // Date is selected, submit the form
            document.getElementById("salary_sheet_form").submit();
        }
    }
</script>

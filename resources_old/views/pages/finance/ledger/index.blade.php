<x-default-layout>
    <style>
        .card .card-header {
            min-height: 40px;
        }

        .table> :not(caption)>*>* {
            padding: 0.3rem !important;
        }

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
    <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
        <!--begin::Content wrapper-->
        <div class="d-flex flex-column flex-column-fluid">
            <!--begin::Toolbar-->
            <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
                <!--begin::Toolbar container-->
                <div id="kt_app_toolbar_container" class="app-container d-flex flex-stack">
                    <!--begin::Page title-->
                    <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                        <!--begin::Title-->
                        <h3 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                            General Ledger</h3>
                        <!--end::Title-->
                    </div>
                    <!--end::Page title-->
                </div>
                <!--end::Toolbar container-->
            </div>
            <!--end::Toolbar-->
            <!--begin::Content-->
            <div id="kt_app_content" class="app-content flex-column-fluid">
                <!--begin::Content container-->
                <div id="kt_app_content_container" class="app-container">


                    <!--begin::Main column-->
                    <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
                        <!--begin::General options-->
                        <div class="card card-flush py-4">
                            <!--begin::Card header-->
                            {{-- <div class="card-header">
                                    <div class="card-title">
                                        <h2>General</h2>
                                    </div>
                                </div> --}}
                            <!--end::Card header-->
                            <div class="row">

                                <div class="col-12 col-md-3">
                                    <div class="card-body pt-0 pb-3">
                                        <div class="fv-row fv-plugins-icon-container">
                                            <label class=" form-label">Select Account Head</label>
                                            <select class="form-select form-select-sm" id="account_head"
                                                name="account_head" data-control="select2" data-hide-search="false">
                                                <option value="" selected>--Select Account Head--</option>
                                                @foreach ($accountList as $key => $value)
                                                    <option value="{{ $key }}">{{ $value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <div class="card-body pt-0 pb-3">
                                        <div class="fv-row fv-plugins-icon-container">
                                            <label class=" form-label">Start Date</label>
                                            <input type="date" id="start_date" name="start_date"
                                                class="form-control form-control-sm mb-2" placeholder="Group Name"
                                                value="">
                                            <div class="fv-plugins-message-container invalid-feedback"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <div class="card-body pt-0 pb-3">
                                        <div class="fv-row fv-plugins-icon-container">
                                            <label class=" form-label">End Date</label>
                                            <input type="date" id="end_date" name="end_date"
                                                class="form-control form-control-sm mb-2" placeholder="end_date"
                                                value="">
                                            <div class="fv-plugins-message-container invalid-feedback"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <div class="card-body pt-0 pb-3">
                                        <div class="fv-row fv-plugins-icon-container">
                                            <button type="submit" id="viewBtn"
                                                class="btn btn-sm btn-primary me-5 mt-8">{{ __('View') }}</button>
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>
                        <!--end::General options-->
                        <div class="d-flex justify-content-end">
                            <!--begin::Button-->
                            {{-- <a href="" id="kt_ecommerce_add_product_cancel"
                                class="btn btn-sm btn-success me-5">Cancel</a> --}}
                            <!--end::Button-->
                            <!--begin::Button-->
                            <!--                                <button type="submit" id="kt_ecommerce_add_category_submit" class="btn btn-sm btn-primary">
                                                                <span class="indicator-label">View</span>
                                                                <span class="indicator-progress">Please wait...
                                                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                                            </button>-->



                            <!--end::Button-->





                        </div>
                    </div>
                    <!--end::Main column-->
                </div>
                <!--end::Content container-->
            </div>
            <!--end::Content-->
        </div>
        <!--end::Content wrapper-->

    </div>

    <!--======================================================================-->

    <!--begin::Content-->
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container">
            <!--begin::Products-->
            <div class="card card-flush">

                <!--begin::Card header-->
                <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                    <!--begin::Card title-->
                    <div class="card-title">
                        <div class="d-flex align-items-center position-relative my-1 ">
                            <div class="card-body p-2">
                                <div class="product fv-row fv-plugins-icon-container">
                                    <h3>Ledger Details</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-toolbar flex-row-fluid justify-content-end gap-5 d-none" id="PDFDownloadDiv">

                        <a href="" id="downloadPdf" name="downloadPdf" type="button"
                            class="btn btn-sm btn-light-primary" target="_blank">
                            PDF Download
                        </a>

                        @can('create attendance')
                        @endcan
                    </div>
                </div>
                {{-- <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                    <!--begin::Card title-->
                    <div class="card-title">
                        <!--begin::Export buttons-->
                        <div id="kt_ecommerce_report_customer_orders_export" class="d-none"></div>
                        <!--end::Export buttons-->
                    </div>
                    <!--end::Card title-->
                </div> --}}
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body pt-0">
                    <!--begin::Table-->
                    <table class="table table-striped table-bordered align-middle table-row-dashed fs-7 gy-5 mb-0"
                        id="ledger_table">
                        <!--begin::Table head-->
                        <thead>
                            <!--begin::Table row-->
                            <tr class="text-start fs-7 text-uppercase gs-0">
                                <th class="min-w-100px">Voucher Date</th>
                                <th class="min-w-100px">Voucher No </th>
                                <th class="min-w-100px">Voucher Type</th>
                                <th class="min-w-100px">Narration</th>
                                <th class="min-w-100px">Debit (BDT)</th>
                                <th class="min-w-100px">Credit (BDT)</th>
                                <th class="min-w-100px">Balance (BDT)</th>
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
    var name = 'Global shoes Ltd';
    name += `\n Employee List`;
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
    var table = $('#ledger_table').DataTable(dataTableOptions);

    function CallBack(pdfdata) {
        let url;
        var accountHead = $('#account_head').val();
        var startDate = $('#start_date').val();
        var endDate = $('#end_date').val();

        url =
            '{{ route('general_ledger_search', ['accountHead' => ':accountHead', 'startDate' => ':startDate', 'endDate' => ':endDate', 'pdf' => ':pdf']) }}';

        url = url.replace(':accountHead', accountHead);
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
                    $('#PDFDownloadDiv').removeClass('d-none');

                    table.clear().draw();
                    if (data.length > 0) {
                        $('#jsdataerror').text('');
                        let balance = 0;
                        $.each(data, function(key, value) {
                            let d = data[key];
                            // Determine the type based on both type and balance_type
                            let type = '';
                            if (d.type === 'SV') {
                                if (d.balance_type === 'Dr') {
                                    type = 'Sales';
                                } else if (d.balance_type === 'Cr') {
                                    type = 'Received';
                                }
                            } else if (d.type === 'PV') {
                                if (d.balance_type === 'Cr') {
                                    type = 'Purchase';
                                } else if (d.balance_type === 'Dr') {
                                    type = 'Payment';
                                }
                            } else if (d.type === 'CR') {
                                type = 'Received';
                            } else if (d.type === 'CP') {
                                type = 'Payment';
                            } else {
                                type = d.type; // Default to original type if not matched
                            }
                            // Map the type to its corresponding string
                            type = '<span style="font-size: 14px; font-style: italic; font-weight: bold">' + type + '</span></br>' + d.to_acc_name ;

                            let debit = d.balance_type === 'Dr' ? parseFloat(d.amount) : 0; // Convert amount to float
                            let credit = d.balance_type === 'Cr' ? parseFloat(d.amount) : 0; // Convert amount to float
                            balance += debit - credit; // Accumulate balance
                            
                            let balanceFormat = (balance >= 0) ? formatCurrency(balance) + ' Dr' : formatCurrency(Math.abs(balance)) + ' Cr';

                            table.row.add([d.date, d.voucher_no, type,  d.narration, formatCurrency(debit),
                            formatCurrency(credit),  balanceFormat
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

    $('#viewBtn').on('click', function() {
        CallBack("list");
    });

    $('#downloadPdf').on('click', function() {
        CallBack("pdfurl");
    });
</script>

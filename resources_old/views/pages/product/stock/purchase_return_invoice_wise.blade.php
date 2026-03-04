<x-default-layout>
    <style>
        .card .card-header {
            min-height: 40px;
        }

        .table> :not(caption)>*>* {
            padding: 0.3rem !important;
        }
    </style>

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
                        <h3>Invoice Wise Purchase Return</h3>
                    </div>
                    <!--begin::Card toolbar-->
                    <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                        <div class="form-group col-md-3">
                            <label for="order">{{ __('Start Date') }}</label>
                            <input id="start_date" type="date" value="{{ date('Y-m-d') }}"
                                class="form-control form-control-sm form-control-solid" id=""
                                name="start_date" />
                        </div>
                        <div class="form-group col-md-3">
                            <label for="order">{{ __('End Date') }}</label>
                            <input id="end_date" type="date" value="{{ date('Y-m-d') }}"
                                class="form-control form-control-sm form-control-solid" id=""
                                name="end_date" />
                        </div>
                    </div>
                    <div class="d-flex flex-row-reverse pt-0 pt-md-5">
                        <button type="submit" id="searchBtn"
                            class="btn btn-sm btn-primary px-5 mt-0 mt-md-3">{{ __('Search') }}</button>
                    </div>
                    <div class="d-flex flex-row-reverse pt-0 pt-md-5">
                        <a href="" id="downloadPdf" name="downloadPdf" type="button"
                            class="btn btn-sm btn-light-primary mt-0 mt-md-3" target="_blank">
                            PDF Download
                        </a>
                    </div>
                </div>

                <!--begin::Card body-->
                <div class="card-body pt-0">
                    <!--begin::Table-->
                    <table class="table table-striped table-bordered align-middle table-row-dashed fs-7 gy-5 mb-0"
                        id="invoice_wise_purchase_report">
                        <!--begin::Table head-->
                        <thead>
                            <!--begin::Table row-->
                            <tr class="text-start fs-7 text-uppercase gs-0">
                                <th class="min-w-20px">SL</th>
                                <th class="min-w-50px">Purchase Date</th>
                                <th class="min-w-50px">Invoice</th>
                                <th class="min-w-50px">Supplier Name</th>
                                <th class="min-w-20px">Total Amount</th>
                                <th class="min-w-20px">Discount</th>
                                <th class="min-w-20px">Net Sales</th>
                                <th class="min-w-300px text-end">Action</th>
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
    // get startDate and endDate
    var startDate = 0;
    var endDate = 0;

    // start_date on change action
    $('#start_date').on('change', function() {
        startDate = $('#start_date').val();
        endDate = $('#end_date').val();
        const start_date = new Date(startDate);
        const end_date = new Date(endDate);
        // Check if the start date is after the end date
        if (start_date > end_date) {
            alert('The start date cannot be after the end date.');
            $('#start_date').val(endDate);
            startDate = $('#start_date').val();
        }
        // InvoiceList Call Data
        InvoiceList(startDate, endDate);
    });

    // end_date on change action
    $('#end_date').on('change', function() {
        startDate = $('#start_date').val();
        endDate = $('#end_date').val();
        const start_date = new Date(startDate);
        const end_date = new Date(endDate);
        // Check if the start date is after the end date
        if (start_date > end_date) {
            alert('The end date cannot be before the start date.');
            $('#end_date').val(startDate);
            endDate = $('#start_date').val();
        }
        // InvoiceList Call Data
        InvoiceList(startDate, endDate);
    });

    var name = 'Perfume PLC';
    name += `\n Invoice Wise Purchase Return`;
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
        columnDefs: [
            {
                targets: -1, // Target the last column (Actions)
                className: 'text-end', // Apply the text-end class for alignment
            }
        ],
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
    var table = $('#invoice_wise_purchase_report').DataTable(dataTableOptions);

    function InvoiceList(pdfdata) {
        let url;
        var startDate = $('#start_date').val();
        var endDate = $('#end_date').val();
        url = '{{ route('purchase_invoice_return_list_search', ['startDate' => ':startDate', 'endDate' => ':endDate', 'pdf' => ':pdf']) }}';
        url = url.replace(':startDate', startDate);
        url = url.replace(':endDate', endDate);
        url = url.replace(':pdf', pdfdata);
        console.log(url);

        if (pdfdata == 'list') {
            $.ajax({
                url: url,
                type: "GET",
                data: {
                    "_token": "{{ csrf_token() }}"
                },
                dataType: "json",
                success: function(data) {
                    console.log('invoice data:', data);

                    table.clear().draw();
                    if (data) {
                        $.each(data, function(key, value) {
                            let invoiceNo = value.invoice_no;
                            let stockDate = value.stock_date;
                            let supplier = value.account_name;
                            let totalAmount = value.total_amount;
                            // let discountAmount = value.total_discount;
                            let discountAmount = value.total_discount ? parseFloat(value
                                .total_discount) : 0.00;
                            let purchaseAmount = parseFloat(totalAmount) + parseFloat(discountAmount);
                            purchaseAmount = purchaseAmount.toFixed(2);

                            // Convert stockDate string to a JavaScript Date object
                            let dateObj = new Date(stockDate);
                            // Format the date in AM/PM format
                            let formattedStockDate = formatDateAMPM(dateObj);

                            // let dateObj = new Date(stockDate);
                            // let formattedStockDate = dateObj.toISOString().split('T')[0];

                            // URLs
                            let showUrl =
                                `{{ route('purchase_invoice_return_details', ['invoiceNo' => '_invoiceNo_']) }}`
                                .replace('_invoiceNo_', invoiceNo);
                            let pdfUrl =
                                `{{ route('purchase_invoice_return_details_Pdf', ['data' => '_invoiceNo_']) }}`
                                .replace('_invoiceNo_', invoiceNo);

                            // Determine buttons
                            let buttons = `
                                <td class="text-end">
                                    <a href="${showUrl}" class="btn btn-sm btn-info hover-scale p-2 mx-2" data-bs-toggle="tooltip" data-bs-placement="top" title="View">
                                        Show Invoice
                                    </a>
                                    <a href="${pdfUrl}" class="btn btn-sm btn-success hover-scale p-2 mx-2" data-bs-toggle="tooltip" data-bs-placement="top" title="PDF" target="_blank">
                                        PDF Invoice
                                    </a>
                                </td>
                            `;

                            // Add row to DataTable
                            let rowIndex = table.rows().count() + 1;
                            table.row.add([rowIndex, formattedStockDate, invoiceNo, supplier,
                                formatCurrency(purchaseAmount), formatCurrency(discountAmount),
                                formatCurrency(totalAmount), buttons]).draw();
                            // Reinitialize Bootstrap tooltips
                            $('[data-bs-toggle="tooltip"]').tooltip();
                        });

                        // Enable DataTable buttons
                        table.buttons().enable();
                    } else {
                        $('#jsdataerror').text('No records found');
                        console.log('No records found');
                    }
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                    $('#jsdataerror').text('Error occurred while fetching data');
                }
            });
        } else if (pdfdata == 'pdfurl') {
            $('#downloadPdf').attr('href', url);
        } else {
            $('#jsdataerror').text('Please select a date');
        }
    }
    InvoiceList('list');

    $('#searchBtn').on('click', function() {
        InvoiceList("list");
    });
    $('#downloadPdf').on('click', function() {
        InvoiceList('pdfurl');
    });
</script>

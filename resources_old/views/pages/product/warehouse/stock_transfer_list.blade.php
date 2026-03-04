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
                        <h3>Warehouse Stock Transfer List</h3>
                    </div>
                    <!--begin::Card toolbar-->
                    <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                        
                        <div class="form-group col-md-2">
                            <label for="order">{{ __('Start Date') }}</label>
                            <input id="start_date" type="date" value="{{ date('Y-m-d') }}"
                                class="form-control form-control-sm form-control-solid" id=""
                                name="start_date" />
                        </div>
                        <div class="form-group col-md-2">
                            <label for="order">{{ __('End Date') }}</label>
                            <input id="end_date" type="date" value="{{ date('Y-m-d') }}"
                                class="form-control form-control-sm form-control-solid" id=""
                                name="end_date" />
                        </div>
                        <div class="form-group col-md-3">
                            <label>{{ __('Select Warehouse') }}</label>
                            <select id="warehouseId" class="form-select form-select-sm" name="warehouse_id"
                                data-control="select2" data-hide-search="false">
                                <option selected value="0">ALL Warehouse</option>
                                @foreach ($warehouses as $warehouse)
                                    <option value="{{ $warehouse->id }}">
                                        {{ $warehouse->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <label>{{ __('Select Status') }}</label>
                            <select id="statusId" class="form-select form-select-sm" name="status"
                                data-control="select2" data-hide-search="true">
                                <option selected value="0">Pending</option>
                                <option value="1">Approved</option>
                            </select>
                        </div>
                    </div>
                    <div class="d-flex flex-row-reverse pt-0 pt-md-4">
                        <button type="submit" id="searchBtn"
                            class="btn btn-sm btn-primary px-5 mt-0 mt-md-3">{{ __('Search') }}</button>
                    </div>
                    <div class="d-flex flex-row-reverse pt-0 pt-md-4">
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
                        id="invoice_wise_sales_report">
                        <!--begin::Table head-->
                        <thead>
                            <!--begin::Table row-->
                            <tr class="text-start fs-7 text-uppercase gs-0">
                                <th class="min-w-20px">SL</th>
                                <th class="min-w-150px">Date</th>
                                <th class="min-w-50px">Invoice</th>
                                <th class="min-w-100px">From Warehouse</th>
                                <th class="min-w-100px">To Warehouse</th>
                                <th class="min-w-20px">Status</th>
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
    var name = 'Perfume PLC';
    name += `\n Transfer Pending List`;
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
        columnDefs: [{
            targets: -1, // Target the last column (Actions)
            className: 'text-end', // Apply the text-end class for alignment
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

    var table = $('#invoice_wise_sales_report').DataTable(dataTableOptions);

    function InvoiceList(pdfdata) {
        let url;
        var startDate = $('#start_date').val();
        var endDate = $('#end_date').val();
        var warehouseId = $('#warehouseId').val();
        var statusId = $('#statusId').val();
        url =
            '{{ route('stock_transfer_list_search', ['startDate' => ':startDate', 'endDate' => ':endDate', 'warehouseId' => ':warehouseId', 'statusId' => ':statusId', 'pdf' => ':pdf']) }}';
        url = url.replace(':startDate', startDate);
        url = url.replace(':endDate', endDate);
        url = url.replace(':warehouseId', warehouseId);
        url = url.replace(':statusId', statusId);
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
                    if (data) {
                        // Clear existing rows in DataTable
                        table.clear().draw();

                        $.each(data, function(key, d) {
                            let invoiceNo = d.invoice_no;
                            // URLs
                            let showUrl =
                                `{{ route('stock_transfer_invoice_details', ['invoiceNo' => '_invoiceNo_']) }}`
                                .replace('_invoiceNo_', invoiceNo);

                            // let returnUrl =
                            //     `{{ route('sales_invoice_return', ['invoiceNo' => '_invoiceNo_']) }}`
                            //     .replace('_invoiceNo_', invoiceNo);
                            // let editUrl =
                            //     `{{ route('sales_invoice_edit', ['invoiceNo' => '_invoiceNo_']) }}`
                            //     .replace('_invoiceNo_', invoiceNo);
                            
                            // let pdfUrl =
                            //     `{{ route('sales_invoice_details_pdf', ['data' => '_invoiceNo_']) }}`
                            //     .replace('_invoiceNo_', invoiceNo);

                            // Determine buttons
                            // <a href="${editUrl}" class="btn btn-sm btn-primary hover-scale p-2 mx-2" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                            //     Edit Invoice
                            // </a>
                            // <a href="${showUrl}" class="btn btn-sm btn-info hover-scale p-2 mx-2" data-bs-toggle="tooltip" data-bs-placement="top" title="View">
                            //     Show Invoice
                            // </a>
                            // <a href="${pdfUrl}" class="btn btn-sm btn-success hover-scale p-2 mx-2" data-bs-toggle="tooltip" data-bs-placement="top" title="PDF" target="_blank">
                            //     PDF Invoice
                            // </a>
                            let buttons = `
                                <td class="text-end">
                                    <a href="${showUrl}" class="btn btn-sm btn-info hover-scale p-2 mx-2" data-bs-toggle="tooltip" data-bs-placement="top" title="View">
                                        View
                                    </a>
                                </td>
                            `;

                            let status = `
                               <span class="${d.status == 1? 'text-success':'text-warning'}">${d.status == 1? 'Approved':'Pending'}<span/>
                            `;
                            // Add row to DataTable
                            let rowIndex = table.rows().count() + 1;
                            table.row.add([
                                rowIndex,
                                d.stock_date,
                                d.invoice_no,
                                d.from_warehouse_name,
                                d.to_warehouse_name,
                                status,
                                buttons
                            ]).draw();
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

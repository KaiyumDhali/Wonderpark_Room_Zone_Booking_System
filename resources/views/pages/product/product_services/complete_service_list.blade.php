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
                        <h3>Complete Service List</h3>
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
                        id="PendingProductServiceList">
                        <!--begin::Table head-->
                        <thead>
                            <!--begin::Table row-->
                            <tr class="text-start fs-7 text-uppercase gs-0">
                                <th style="width: 25px;">SL</th>
                                <th style="width: 80px;">Invoice</th>
                                <th style="width: 80px;">Service Invoice</th>
                                <th style="width: 140px;">Service Date</th>
                                <th style="width: 140px;">Actual Service Date</th>
                                <th style="width: 100px;">Service No</th>
                                <th style="width: 210px;">Customer Name</th>
                                <th style="width: 140px;">Product Name</th>
                                <th style="width: 110px;">Service Status</th>
                                <th style="width: 100px;">Done By</th>
                                <th style="width: 250px;">Action</th>
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
    var name = 'Company Name';
    name += `\n Pending Product Service List`;
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

    var table = $('#PendingProductServiceList').DataTable(dataTableOptions);

    function InvoiceList(pdfdata) {
        let url;
        var startDate = $('#start_date').val();
        var endDate = $('#end_date').val();
        url =
            '{{ route('complete_product_services_search', ['startDate' => ':startDate', 'endDate' => ':endDate', 'pdf' => ':pdf']) }}';
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

                            let d = data[key];

                            let invoiceNo =d.service_invoice

                            let ID = d.psd_id;
                            // URLs
                            let serviceNotCompleteUrl =
                                `{{ route('not_complete_product_services', ['id' => '_id_']) }}`
                                .replace('_id_', ID);

                            let pdfUrl =
                                `{{ route('sales_invoice_details_pdf', ['data' => '_invoiceNo_']) }}`
                                .replace('_invoiceNo_', invoiceNo);

                            // Determine buttons
                            let buttons = `
                            <td class="text-right d-flex">
                                <a href="${serviceNotCompleteUrl}" class="btn btn-sm btn-success hover-scale p-2 mx-2" data-bs-toggle="tooltip" data-bs-placement="top" title="Send to Pending List">
                                    Send to Pending
                                </a>
                                <a href="${pdfUrl}" class="btn btn-sm btn-primary hover-scale p-2 mx-2" data-bs-toggle="tooltip" data-bs-placement="top" title="Invoice PDF" target="_blank">
                                    Invoice PDF
                                </a>
                            </td>
                            `;
                            // Corrected service_status with ternary operator
                            let service_status = `
                                <td class="text-right d-flex">
                                    <p class="text-info">
                                        ${d.service_status == 0 ? 'Pending' : 'Done'}
                                    </p>
                                </td>
                            `;
                            // Add row to DataTable 
                            // buttons
                            let rowIndex = table.rows().count() + 1;
                            table.row.add([rowIndex, d.invoice_no, invoiceNo, d.service_date, d.actual_service_date, d.service_number, d.customer_name, d.product_name, service_status, d.done_by, buttons
                            ]).draw();
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

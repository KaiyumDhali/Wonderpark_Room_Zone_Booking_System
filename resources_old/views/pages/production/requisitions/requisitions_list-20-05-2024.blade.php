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

    <div class="container-fluid">
        <!-- Page Heading -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
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
                        <h3>Requisition List</h3>
                    </div>
                    <!--begin::Card toolbar-->
                    <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                        <div class="form-group col-md-3">
                            <label for="order">{{ __('Start Date') }}</label>
                            <input id="start_date" type="date" value="{{ date('Y-m-d', strtotime($startDate)) }}"
                                class="form-control form-control-sm form-control-solid" id=""
                                name="start_date" />
                        </div>
                        <div class="form-group col-md-3">
                            <label for="order">{{ __('End Date') }}</label>
                            <input id="end_date" type="date" value="{{ date('Y-m-d', strtotime($endDate)) }}"
                                class="form-control form-control-sm form-control-solid" id=""
                                name="end_date" />
                        </div>
                    </div>
                </div>

                <!--begin::Card body-->
                <div class="card-body pt-0">
                    <!--begin::Table-->
                    <table class="table table-striped table-bordered align-middle table-row-dashed fs-7 gy-5 mb-0"
                        id="requisition_list">
                        <!--begin::Table head-->
                        <thead>
                            <!--begin::Table row-->
                            <tr class="text-start fs-7 text-uppercase gs-0">
                                <th class="min-w-20px">SL</th>
                                <th class="min-w-50px">Invoice</th>
                                <th class="min-w-50px">Requisition Date</th>
                                <th class="min-w-150px">Status</th>
                                <th class="min-w-20px">Action</th>
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
    name += `\n Requisition List`;
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
    var table = $('#requisition_list').DataTable(dataTableOptions);

    // received startDate and endDate then InvoiceList get data 
    function InvoiceList(startDate, endDate) {
        let url;
        url = "{{ route('requisition_invoice_date_search', ['startDate' => ':startDate', 'endDate' => ':endDate']) }}";
        url = url.replace(':startDate', startDate);
        url = url.replace(':endDate', endDate);
        console.log(url);
        // if (startDate && endDate) {
        $.ajax({
            url: url,
            type: "GET",
            data: {
                "_token": "{{ csrf_token() }}"
            },
            dataType: "json",
            success: function(response) {
                table.clear().draw();
                if (response.invoices) {
                    let approved_index = response.approved_index;
                    $('#jsdataerror').text('');
                    $.each(response.invoices, function(key, value) {
                        let requisitionDate = value[0].stock_date;
                        let invoiceNo = value[0].invoice_no;
                        let statusValue = value[0].status;
                        let statusValueApproved = statusValue + 1;

                        // Determine status text
                        let statusTextMap = {
                            0: 'Pending',
                            1: 'Production Manager Approved',
                            2: 'Production Manager and CEO Approved',
                            3: 'Production Manager, CEO and Store Incharge Approved'
                        };
                        let statusText = statusTextMap[statusValue] || 'Unknown Status';

                        // URLs
                        let statusApprovedUrl =
                            `{{ route('requisition_invoice_report_status_approved', ['invoiceNo' => '_invoiceNo_', 'statusValueApproved' => '_statusValueApproved_']) }}`
                            .replace('_invoiceNo_', invoiceNo)
                            .replace('_statusValueApproved_', statusValueApproved);
                        let showUrl =
                            `{{ route('requisition_invoice_report_search', ['invoiceNo' => '_invoiceNo_']) }}`
                            .replace('_invoiceNo_', invoiceNo);

                        // Determine buttons
                        let buttons = `
                            <td class="text-right">
                                <a href="${showUrl}" class="btn btn-sm btn-info hover-scale p-2" data-bs-toggle="tooltip" data-bs-placement="top" title="View">
                                    <i class="bi bi-eye fs-2 ms-1"></i>
                                </a>
                            `;
                            if (statusValueApproved === approved_index) {
                            buttons += `
                                    <a href="${statusApprovedUrl}" class="btn btn-sm btn-success hover-scale p-2" data-bs-toggle="tooltip" data-bs-placement="top" title="Approved">
                                        <i class="bi bi-check2-square fs-2 ms-1"></i>
                                    </a>
                                `;
                            }
                            buttons += 
                            '</td>';

                        // Add row to DataTable
                        let rowIndex = table.rows().count() + 1;
                        table.row.add([rowIndex, invoiceNo, requisitionDate, statusText, buttons])
                            .draw();
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
        // } else {
        //     $('#jsdataerror').text('Invalid date range');
        // }
    }

    InvoiceList(startDate, endDate);
</script>

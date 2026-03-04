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

    @if ($productionIn)
        <div class="row p-5 pt-0">
            <!-- Produceable Products Content -->
            <div class="col-md-12 px-5">
                <div class="row px-5">
                    <div class="col-md-12 card shadow">
                        <div class="card-body py-3">
                            <div class="row">
                                <div class="col-md-6 px-0 mx-0">
                                    <div class="d-sm-flex align-items-center justify-content-between">
                                        <h1 class="h3 mb-0 text-gray-800">{{ __(' Product to be Produced ') }}</h1>
                                    </div>
                                    <table class="table table-striped table-bordered mt-4">
                                        <thead>
                                            <tr class="text-center">
                                                <th>Product ID</th>
                                                <th>Product Name</th>
                                                <th>Unit</th>
                                                <th>Quantity</th>
                                                <th>Unit Price (BDT)</th>
                                                <th>Total Price (BDT)</th>
                                            </tr>
                                        </thead>
                                        <tbody class="fw-semibold text-gray-600">
                                            @foreach ($productionIn as $stock)
                                                <tr>
                                                    <td class="text-center">{{ $stock->product_id }}</td>
                                                    <td class="text-left">{{ $stock->product->product_name }}</td>
                                                    <td class="text-center">{{ $stock->product->unit->unit_name }}</td>
                                                    <td class="text-center">{{ $stock->stock_in_quantity }}</td>
                                                    <td class="text-end">{{ $stock->product->purchase_price }}</td>
                                                    <td class="text-end">
                                                        {{ number_format($stock->product->purchase_price * $stock->stock_in_quantity, 2) }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-md-6 px-0 mx-0" style="text-align: right">

                                    <a href="{{ route('requisition_invoice_report_pdf', $productionIn[0]->invoice_no) }}"
                                        class="btn btn-sm btn-primary px-5 my-3" style=""
                                        target="_blank">{{ __('PDF Download') }}</a>

                                    <h4 class="mb-2"> Requisition No (#{{ $productionIn[0]->invoice_no }}) </h4>
                                    <p class="mb-0">
                                        Requisition Date : {{ $productionIn[0]->stock_date }}
                                        <br>
                                        Requisition By : {{ $productionIn[0]->done_by }}
                                    </p>
                                    @php
                                        $status = $productionIn[0]->status;
                                        $approvedBy = $status == 0 ? 'Pending' : ($status == 1 ? 'Production Manager' : ($status == 2 ? 'Production Manager and CEO' : ($status == 3 ? 'Production Manager, CEO, and Store Incharge' : '')));
                                    @endphp
                                    @if ($status >= 0 && $status <= 3)
                                        <p class="mt-0 mb-2">Approved By : {{ $approvedBy }}</p>
                                    @endif

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Consumable Products Content -->
            <div class="col-md-12 p-5">
                <div class="row px-5">
                    <div class="col-md-12 card shadow px-5">
                        <div class="d-sm-flex align-items-center justify-content-between pt-3">
                            <h1 class="h3 mb-0 text-gray-800">{{ __(' Requisition items ') }}</h1>
                        </div>
                        <table class="table table-striped table-bordered mt-4">
                            <thead>
                                <tr class="text-center">
                                    <th>Material ID</th>
                                    <th>Material Name</th>
                                    <th>Unit</th>
                                    <th>Quantity</th>
                                    <th>Unit Cost (BDT)</th>
                                    <th>Total Cost (BDT)</th>
                                </tr>
                            </thead>
                            <tbody class="fw-semibold text-gray-600">
                                @php
                                    $consumeOut_total = 0;
                                @endphp

                                @foreach ($consumeOut as $stock)
                                    <tr>
                                        <td class="text-center">{{ $stock->product_id }}</td>
                                        <td class="text-left">{{ $stock->product->product_name }}</td>
                                        <td class="text-center">{{ $stock->product->unit->unit_name }}</td>
                                        <td class="text-center">{{ $stock->total_quantity }}</td>
                                        <td class="text-end">{{ $stock->product->purchase_price }}</td>
                                        <td class="text-end">
                                            {{-- {{ number_format($consumeOut_sum_total=$stock->product->purchase_price*$stock->stock_out_quantity, 2)}} --}}
                                            {{ number_format($consumeOut_sum_total = $stock->total_price, 2) }}
                                        </td>
                                    </tr>
                                    @php
                                        $consumeOut_total += $consumeOut_sum_total;
                                    @endphp
                                @endforeach

                            </tbody>
                            <tfooter>
                                <tr>
                                    <th colspan="5" class="fs-5 text-dark text-end">Total Requisition Price</th>
                                    <th class="text-end"> {{ number_format($consumeOut_total, 2) }}</th>
                                </tr>
                            </tfooter>
                        </table>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end">
                <a class="btn btn-sm btn-success px-5 mt-5 me-5" href="{{ route('production.requisition_list') }}">Back
                    Requisition List</a>
            </div>
        </div>
    @endisset


</x-default-layout>

{{-- <script type="text/javascript">
    $("#searchBtn").prop('disabled', true);
    $('#invoice_list').on('change', function() {
        $("#searchBtn").prop('disabled', false);
    });

    // get startDate and endDate
    var startDate = 0;
    var endDate = 0;

    // start_date on change action
    $('#start_date').on('change', function() {
        $("#invoice_list").empty();
        // get on change date
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
        $("#invoice_list").empty();
        // get on change date
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
    var table = $('#employee_table').DataTable(dataTableOptions);

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
                success: function(data) {
                    console.log(data);
                    table.clear().draw();
                    $('#invoice_list').empty(); // Clear previous options
                    $('#invoice_list').append('<option selected disabled>Select Invoice</option>');

                    if (data) {
                        $('#jsdataerror').text('');
                        $.each(data, function(key, value) {
                            let invoiceNo = value[0].invoice_no;
                            let statusText = value[0].status === 0 ? 'Pending' : 'Approved';

                            $('#invoice_list').append(`<option value="${invoiceNo}">${invoiceNo}</option>`);

                            let showUrl = `{{ route('requisition_Invoice_report_search', ['invoiceNo' => '_invoiceNo_']) }}`.replace('_invoiceNo_', invoiceNo);

                            let approved = `
                                <td class="text-right">
                                    <a href="${showUrl}" class="btn btn-sm btn-success hover-scale p-2" data-bs-toggle="tooltip" data-bs-placement="top" title="Approved">
                                        <i class="bi bi-check2-square fs-2 ms-1"></i>
                                    </a>
                                </td>
                            `;
                            let view = `
                                <td class="text-right">
                                    <a href="${showUrl}" class="btn btn-sm btn-info hover-scale p-2" data-bs-toggle="tooltip" data-bs-placement="top" title="View">
                                        <i class="bi bi-eye fs-2 ms-1"></i>
                                    </a>
                                </td>
                            `;
                            let rowIndex = table.rows().count() + 1;
                            // Add row to DataTable
                            table.row.add([rowIndex, invoiceNo, statusText, approved, view]).draw();
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

</script> --}}

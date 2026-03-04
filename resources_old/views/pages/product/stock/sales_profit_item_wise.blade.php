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
                <div class="card-header align-items-center py-2 gap-2 gap-md-5">
                    <!--begin::Card title-->
                    <div class="card-title">
                        <h3>Item Wise Sales Profit</h3>
                    </div>
                    <!--begin::Card toolbar-->
                    {{-- <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                        @can('create employee')
                            <a href="{{ route('employees.create') }}" class="btn btn-sm btn-primary">Add Employee</a>
                        @endcan
                    </div> --}}
                </div>
                <!--begin::Card body-->
                <div class="card-body">
                    <div class="row d-flex">
                        <div class="col-md-2 py-2">
                            <div class="fv-row mb-0 fv-plugins-icon-container">
                                <label for="order">{{ __('Start Date') }}</label>
                                <input id="start_date" type="date" value="{{ date('Y-m-d', strtotime($startDate)) }}"
                                    class="form-control form-control-sm form-control-solid" name="start_date" />
                            </div>
                        </div>
                        <div class="col-md-2 py-2">
                            <div class="fv-row mb-0 fv-plugins-icon-container">
                                <label for="order">{{ __('End Date') }}</label>
                                <input id="end_date" type="date" value="{{ date('Y-m-d', strtotime($endDate)) }}"
                                    class="form-control form-control-sm form-control-solid" id=""
                                    name="end_date" />
                            </div>
                        </div>
                        <div class="col-md-2 py-2">
                            <div class="fv-row mb-0 fv-plugins-icon-container">
                                <label>{{ __('Select Product') }}</label>
                                <select id="productID" class="form-control form-control-sm" name="product_id" data-control="select2" data-hide-search="false" required>
                                    <option selected selected value="0">All Product</option>
                                    @foreach($products as $key => $value)
                                        <option value="{{ $key }}">{{ $value}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        {{-- <div class="col-md-2 py-2">
                            <div class="fv-row mb-0 fv-plugins-icon-container">
                                <label>{{ __('Select Invoice') }}</label>
                                <select id="invoice" class="form-control form-control-sm" name="invoice_no" data-control="select2" data-hide-search="false" required>
                                    <option selected selected value="0">Select Invoice</option>
                                    @foreach($invoices as $invoice)
                                        <option value="{{ $invoice }}">{{ $invoice}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div> --}}

                        <div class="col-md-2 py-2">
                            <div class="fv-row mb-0 fv-plugins-icon-container pt-0 pt-md-3">
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
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body pt-0">
                    <!--begin::Table-->
                    <div class="table-responsive">
                    <table class="table table-striped table-bordered align-middle table-row-dashed fs-7 gy-5 mb-0"
                        id="customer_wise_sales_report">
                        <!--begin::Table head-->
                        <thead>
                            <!--begin::Table row-->
                            <tr class="fs-7 text-uppercase gs-0">
                                <th class="text-center w-30px">Product Id</th>
                                <th class="text-center w-100px">Product Name</th>
                                <th class="text-center w-100px">Total Sales Qty</th>
                                <th class="text-center w-100px">AVG Purchase Price</th>
                                <th class="text-center w-100px">Total Purchase Amount</th>
                                <th class="text-center w-100px">AVG Sales Price</th>
                                <th class="text-center w-100px">Total Sales Amount</th>
                                <th class="text-center w-100px">Total Profit</th>
                            </tr>
                            <!--end::Table row-->
                        </thead>
                        <!--end::Table head-->
                        <!--begin::Table body-->
                        <tbody class="fw-semibold text-gray-700">

                        </tbody>
                        <!--end::Table body-->
                    </table>
                    </div>
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
    name += `\n Customer Wise Sales Report`;
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
    var table = $('#customer_wise_sales_report').DataTable(dataTableOptions);

    function CallBack(pdfdata) {
        let url;
        var startDate = $('#start_date').val();
        var endDate = $('#end_date').val();
        var productID = $('#productID').val();
        // var invoice = $('#invoice').val();
        url =
            '{{ route('item_wise_profit_search', ['startDate' => ':startDate', 'endDate' => ':endDate', 'productID' => ':productID', 'pdf' => ':pdf']) }}';

        url = url.replace(':startDate', startDate);
        url = url.replace(':endDate', endDate);
        url = url.replace(':productID', productID);
        // url = url.replace(':invoice', invoice);
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
                    console.log('data:', data);
                    table.clear().draw();
                    if (data.length > 0) {                       
                        $('#jsdataerror').text('');
                        $.each(data, function(key, value) {
                            let d = data[key];
                            // 'product_id', 'total_sales_quantity', 'avg_purchase_price', 'total_purchase_amount', 'avg_sales_price', 'total_sales_amount', 'total_profit'
                            let totalProfit = '<div style="text-align: right;">' + formatCurrency(d.total_profit) + '</div>';
                           
                            table.row.add([d.product_id, d.product_name, d.total_sales_quantity, formatCurrency(d.avg_purchase_price), formatCurrency(d.total_purchase_amount), formatCurrency(d.avg_sales_price), formatCurrency(d.total_sales_amount), totalProfit]).draw();
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
</script>

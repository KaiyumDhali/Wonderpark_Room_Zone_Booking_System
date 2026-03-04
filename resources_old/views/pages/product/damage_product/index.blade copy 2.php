<x-default-layout>
    <style>
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
    {{-- <div class="col-xl-12 px-5">
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
    </div> --}}





    <!--begin::Toolbar-->
    {{-- <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <!--begin::Toolbar container-->
        <div id="kt_app_toolbar_container" class="app-container d-flex flex-stack">
            <!--begin::Page title-->
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <!--begin::Title-->
                <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">Damage Product List</h1>
                <!--end::Title-->

            </div>
            <!--end::Page title-->
        </div>
        <!--end::Toolbar container-->
    </div> --}}
    <!--end::Toolbar-->

    <!--begin::Content-->
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container">
            <!--begin::Products-->
            <div class="card card-flush">

                <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                    <!--begin::Card title-->
                    <div class="card-title">
                        <h3>Damage Product List</h3>
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

                        <div class="form-group col-md-3">
                            <label>{{ __('Select Product') }}</label>
                            <select id="productId" class="form-select form-select-sm" name="product_id"
                                data-control="select2" data-hide-search="false">
                                <option selected value="0">ALL Product</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}">
                                        {{ $product->product_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>


                        {{-- <div class="form-group col-md-2">
                            <label for="order">{{ __('Order Status') }}</label>
                            <select class="form-select form-select-sm" data-control="select2" name="type"
                                id="type" onchange="CallBack()">
                                <option value="2" selected>All Status</option>
                                <option value="0">Pending</option>
                                <option value="1">Approved</option>
                            </select>
                        </div> --}}
                    </div>
                    <div class="d-flex flex-row-reverse pt-0 pt-md-5">
                        <button type="submit" id="searchBtn"
                            class="btn btn-sm btn-primary">{{ __('Search') }}</button>
                    </div>
                    <div class="d-flex flex-row-reverse pt-0 pt-md-5">
                        <a href="" id="downloadPdf" name="downloadPdf" type="button"
                            class="btn btn-sm btn-light-primary" target="_blank">
                            PDF Download
                        </a>
                    </div>
                    <div class="d-flex flex-row-reverse pt-0 pt-md-5">
                        <a href="{{ route('damage.create') }}" class="btn btn-sm btn-primary">Add Damage Product</a>
                    </div>
                </div>



                <!--begin::Card header-->

                {{-- <div class="card-header align-items-center py-5 gap-2 gap-md-5"> --}}


                <!--begin::Card title-->
                {{-- <div class="card-title">
                        <!--begin::Search-->
                        <div class="d-flex align-items-center position-relative my-1">
                            <!--begin::Svg Icon | path: icons/duotune/general/gen021.svg-->
                            <span class="svg-icon svg-icon-1 position-absolute ms-4">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2"
                                        rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor" />
                                    <path
                                        d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z"
                                        fill="currentColor" />
                                </svg>
                            </span>
                            <!--end::Svg Icon-->
                            <input type="text" data-kt-ecommerce-order-filter="search"
                                class="form-control form-control-solid w-250px ps-14 p-2" placeholder="Search Report" />
                        </div>
                        <!--end::Search-->
                        <!--begin::Export buttons-->
                        <div id="kt_ecommerce_report_customer_orders_export" class="d-none"></div>
                        <!--end::Export buttons-->
                    </div> --}}
                <!--end::Card title-->


                <!--begin::Card toolbar-->
                {{-- <div class="card-toolbar flex-row-fluid justify-content-end gap-5"> --}}
                <!--begin::Daterangepicker-->
                {{-- <input class="form-control form-control-solid w-100 mw-250px" placeholder="Pick date range" id="kt_ecommerce_report_customer_orders_daterangepicker" /> --}}
                <!--end::Daterangepicker-->

                <!--begin::Filter-->
                {{-- <div class="w-150px">
                            <select class="form-select p-2 form-select-solid" data-control="select2"
                                data-hide-search="true" data-placeholder="Status"
                                data-kt-ecommerce-order-filter="status">
                                <option value="all"> Status</option>
                                <option value="Active">Active</option>
                                <option value="Disabled">Disabled</option>
                            </select>
                        </div> --}}
                <!--end::Filter-->

                <!--begin::Export dropdown-->
                {{-- <button type="button" class="btn btn-sm btn-light-primary" data-kt-menu-trigger="click"
                            data-kt-menu-placement="bottom-end">
                            <!--begin::Svg Icon | path: icons/duotune/arrows/arr078.svg-->
                            <span class="svg-icon svg-icon-2">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <rect opacity="0.3" x="12.75" y="4.25" width="12" height="2" rx="1"
                                        transform="rotate(90 12.75 4.25)" fill="currentColor" />
                                    <path
                                        d="M12.0573 6.11875L13.5203 7.87435C13.9121 8.34457 14.6232 8.37683 15.056 7.94401C15.4457 7.5543 15.4641 6.92836 15.0979 6.51643L12.4974 3.59084C12.0996 3.14332 11.4004 3.14332 11.0026 3.59084L8.40206 6.51643C8.0359 6.92836 8.0543 7.5543 8.44401 7.94401C8.87683 8.37683 9.58785 8.34458 9.9797 7.87435L11.4427 6.11875C11.6026 5.92684 11.8974 5.92684 12.0573 6.11875Z"
                                        fill="currentColor" />
                                    <path opacity="0.3"
                                        d="M18.75 8.25H17.75C17.1977 8.25 16.75 8.69772 16.75 9.25C16.75 9.80228 17.1977 10.25 17.75 10.25C18.3023 10.25 18.75 10.6977 18.75 11.25V18.25C18.75 18.8023 18.3023 19.25 17.75 19.25H5.75C5.19772 19.25 4.75 18.8023 4.75 18.25V11.25C4.75 10.6977 5.19771 10.25 5.75 10.25C6.30229 10.25 6.75 9.80228 6.75 9.25C6.75 8.69772 6.30229 8.25 5.75 8.25H4.75C3.64543 8.25 2.75 9.14543 2.75 10.25V19.25C2.75 20.3546 3.64543 21.25 4.75 21.25H18.75C19.8546 21.25 20.75 20.3546 20.75 19.25V10.25C20.75 9.14543 19.8546 8.25 18.75 8.25Z"
                                        fill="currentColor" />
                                </svg>
                            </span>
                            <!--end::Svg Icon-->Export Report</button>
                        <!--begin::Menu-->
                        <div id="kt_ecommerce_report_customer_orders_export_menu"
                            class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-200px py-4"
                            data-kt-menu="true">
                            <!--begin::Menu item-->
                            <div class="menu-item px-3">
                                <a href="#" class="menu-link px-3" data-kt-ecommerce-export="copy">Copy to
                                    clipboard</a>
                            </div>
                            <!--end::Menu item-->
                            <!--begin::Menu item-->
                            <div class="menu-item px-3">
                                <a href="#" class="menu-link px-3" data-kt-ecommerce-export="excel">Export as
                                    Excel</a>
                            </div>
                            <!--end::Menu item-->
                            <!--begin::Menu item-->
                            <div class="menu-item px-3">
                                <a href="#" class="menu-link px-3" data-kt-ecommerce-export="csv">Export as
                                    CSV</a>
                            </div>
                            <!--end::Menu item-->
                            <!--begin::Menu item-->
                            <div class="menu-item px-3">
                                <a href="#" class="menu-link px-3" data-kt-ecommerce-export="pdf">Export as
                                    PDF</a>
                            </div>
                            <!--end::Menu item-->
                        </div> --}}
                <!--end::Menu-->
                <!--end::Export dropdown-->

                {{-- @can('create product')
                            <a href="{{ route('damage.create') }}" class="btn btn-sm btn-primary">Add Damage Product</a>
                        @endcan --}}

                {{-- </div> --}}
                <!--end::Card toolbar-->
                {{-- </div> --}}

                <!--end::Card header-->

                <!--begin::Card body-->
                <div class="card-body pt-0">
                    <!--begin::Table-->
                    <table class="table table-bordered align-middle table-row-dashed fs-6 gy-5"
                        id="damage_product_report">
                        <!--begin::Table head-->
                        <thead class="bg-gray-200">
                            <!--begin::Table row-->
                            <tr class="text-start fw-bold fs-7 text-uppercase gs-0">
                                <th class="min-w-100px">Damage Invoice</th>
                                <th class="min-w-100px">Warehouse</th>
                                <th class="min-w-150px">Supplier</th>
                                <th class="min-w-150px">Damage Reason</th>
                                <th style="width: 120px">Damage Date</th>
                                <th class="min-w-100px">Done By</th>
                                <th class="text-end" style="width: 200px">Actions</th>
                            </tr>

                            <!--end::Table row-->
                        </thead>
                        <!--end::Table head-->
                        <!--begin::Table body-->
                        <tbody class="fw-semibold text-gray-600">

                            @foreach ($damage_lists as $damageNo => $items)
                                
                                <tr>
                                    <td>{{ $damageNo }}</td>
                                    <td>{{ $items[0]->warehouse->name }}</td>
                                    <td>{{ $items[0]->supplier->account_name }}</td>
                                    <td>{{ $items[0]->damage_reason }}</td>
                                    <td>{{ $items[0]->damage_date }}</td>
                                    <td>{{ $items[0]->done_by }}</td>
                                    <td class="text-end">
                                        <div class="d-flex flex-row-reverse">
                                            {{-- @can('read damage') --}}

                                            {{-- <a class="btn btn-sm btn-info py-2 mx-1"
                                                href="{{ route('damage_invoice_details', ['damageNo' => $damageNo]) }}">View</a> --}}

                                            <a href="{{ route('damage_invoice_details_pdf', $damageNo) }}"
                                                class="btn btn-sm btn-primary py-2 mx-1"
                                                target="_blank">{{ __('PDF Download') }}</a>

                                            <a class="btn btn-sm btn-info py-2 mx-1"
                                                href="{{ route('damage_invoice_details', ['invoiceNo' => $damageNo]) }}">View</a>


                                            {{-- @endcan
                                            @can('write damage') --}}
                                            {{-- <a class="btn btn-sm btn-success py-2 mx-1"
                                                href="{{ route('damage.edit', $items[0]->id) }}">Edit</a> --}}
                                            {{-- @endcan --}}


                                        </div>
                                    </td>
                                </tr>
                            @endforeach

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
    name += `\n Material Wise Sales Report`;
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
    var table = $('#damage_product_report').DataTable(dataTableOptions);

    function CallBack(pdfdata) {
        let url;
        var startDate = $('#start_date').val();
        var endDate = $('#end_date').val();
        var warehouseId = $('#warehouseId').val() || 0;
        var productId = $('#productId').val() || 0;
        url =
            '{{ route('damage_product_list_search', ['startDate' => ':startDate', 'endDate' => ':endDate', 'warehouseId' => ':warehouseId', 'productId' => ':productId', 'pdf' => ':pdf']) }}';

        url = url.replace(':startDate', startDate);
        url = url.replace(':endDate', endDate);
        url = url.replace(':warehouseId', warehouseId);
        url = url.replace(':productId', productId);
        url = url.replace(':pdf', pdfdata);
        // console.log(url);

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

                        console.log(data);

                        $('#jsdataerror').text('');
                        $.each(data, function(key, value) {
                            let d = data[key];
                            
                            // let balance=d.TotQty-d.OutQty;
                            // let PreQtyCell = '<div style="text-align: right;">' + formatCurrency(d.PreQty) + '</div>';
                            // let InQtyCell = '<div style="text-align: right;">' + formatCurrency(d.InQty) + '</div>';
                            // let TotQtyCell = '<div style="text-align: right;">' + formatCurrency(d.TotQty) + '</div>';
                            // let OutQtyCell = '<div style="text-align: right;">' + formatCurrency(d.OutQty) + '</div>';
                            // let balanceCell = '<div style="text-align: right;">' + formatCurrency(balance) + '</div>';

                            table.row.add([d.id, d.damage_no, d.name d.supplier_name, d.damage_reason, d.damage_reason, d.done_by
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
</script>

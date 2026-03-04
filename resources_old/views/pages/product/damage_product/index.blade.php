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

    
    <!--begin::Content-->
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container">
            <!--begin::Products-->
            <div class="card card-flush">

                <div class="card-header align-items-center py-2 gap-2 gap-md-5">
                    <!--begin::Card title-->
                    <div class="card-title">
                        <h3>Damage Product List</h3>
                    </div>

                </div>

                <div class="card-header align-items-center py-5 gap-2 gap-md-5">

                    <!--begin::Card toolbar-->
                    <div class="card-toolbar flex-row-fluid justify-content-end gap-4">

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
                                <th class="min-w-50px">ID</th>
                                <th class="min-w-100px">Damage Invoice</th>
                                <th class="min-w-100px">Warehouse</th>
                                <th class="min-w-150px">Supplier</th>
                                <th class="min-w-150px">Damage Reason</th>
                                <th style="width: 120px">Damage Date</th>
                                <th class="min-w-100px">Done By</th>
                                <th class="text-end" style="width: 100px">Actions</th>
                            </tr>

                            <!--end::Table row-->
                        </thead>
                        <!--end::Table head-->
                        <!--begin::Table body-->
                        <tbody class="fw-semibold text-gray-600">

                            {{-- @foreach ($damage_lists as $damageNo => $items)
                                <tr>
                                    <td>{{ $items[0]->id }}</td>
                                    <td>{{ $damageNo }}</td>
                                    <td>{{ $items[0]->warehouse->name }}</td>
                                    <td>{{ $items[0]->supplier->account_name }}</td>
                                    <td>{{ $items[0]->damage_reason }}</td>
                                    <td>{{ $items[0]->damage_date }}</td>
                                    <td>{{ $items[0]->done_by }}</td>
                                    <td class="text-end">
                                        <div class="d-flex flex-row-reverse">
                                            <a href="{{ route('damage_invoice_details_pdf', $damageNo) }}"
                                                class="btn btn-sm btn-primary py-2 mx-1"
                                                target="_blank">{{ __('PDF Download') }}</a>

                                            <a class="btn btn-sm btn-info py-2 mx-1"
                                                href="{{ route('damage_invoice_details', ['invoiceNo' => $damageNo]) }}">View</a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach --}}

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
    name += `\n Damage Product Report`;
    var reportTittle = name;

    var dataTableOptions = {
    dom: '<"top"f>rt<"bottom"lip>', // Removed 'B' from dom to disable buttons
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
        let damage_no;
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
                        // console.log(data);
                        $('#jsdataerror').text('');
                        $.each(data, function(key, value) {
                            let d = data[key];
                            let viewUrl = `{{ route('damage_invoice_details', ':damage_no') }}`
                                .replace(':damage_no', d.damage_no);
                            let pdfUrl = `{{ route('damage_invoice_details_pdf', ':damage_no') }}`
                                .replace(':damage_no', d.damage_no);

                            // Create the actions column
                            let action = `<td class="text-end">
                                                <a href="${viewUrl}" class="btn btn-sm btn-info hover-scale py-2 px-3" data-bs-toggle="tooltip" data-bs-placement="top" title="Show">
                                                    View
                                                </a>
                                                <a href="${pdfUrl}" class="btn btn-sm btn-danger hover-scale py-2 px-3" data-bs-toggle="tooltip" data-bs-placement="top" title="PDF" target="_blank">
                                                    PDF
                                                </a>
                                            </td>`;

                            table.row.add([d.id, d.damage_no, d.name, d.supplier_name,
                                d.damage_reason, d.damage_date, d.done_by, action
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

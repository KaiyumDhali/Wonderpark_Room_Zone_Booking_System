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
<div id="kt_app_content" class="app-content flex-column-fluid">
    <!--begin::Content container-->
    <div id="kt_app_content_container" class="">
        <!--begin::Products-->
        <div class="card card-flush">
            <div class="card-header align-items-center py-2 gap-2 gap-md-5">
                <!--begin::Card title-->
                <div class="card-title">
                    <h3>Finish Good Wise Stock Report</h3>
                </div>
            </div>
            <!--begin::Card body-->
            <div class="card-body">
                <div class="row d-flex">
                    <div class="col-md-2 py-2">
                        <div class="fv-row mb-0 fv-plugins-icon-container">
                            <label for="order">{{ __('Start Date') }}</label>
                            <input id="start_date" type="date" value="{{ date('Y-m-d', strtotime($currentDate)) }}"
                                class="form-control form-control-sm form-control-solid" name="start_date" />
                        </div>
                    </div>
                    <div class="col-md-2 py-2">
                        <div class="fv-row mb-0 fv-plugins-icon-container">
                            <label for="order">{{ __('End Date') }}</label>
                            <input id="end_date" type="date" value="{{ date('Y-m-d', strtotime($currentDate)) }}"
                                class="form-control form-control-sm form-control-solid" id=""
                                name="end_date" />
                        </div>
                    </div>


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
                        id="finish_good_wise_sales_report">
                        <!--begin::Table head-->
                        <thead>
                            <!--begin::Table row-->
                            <tr class="text-start fs-7 text-uppercase gs-0">
                                <th class="min-w-50px text-center">ID</th>
                                <th class="min-w-100px text-center">Product Name</th>
                                <th class="min-w-50px text-center">Unit</th>
                                <th class="min-w-50px text-center">Previous Qty</th>
                                <th class="min-w-100px text-center">In Qty</th>
                                <th class="min-w-100px text-center">Total Qty</th>
                                <th class="min-w-100px text-center">Out Qty</th>
                                <th class="min-w-100px text-center">Balance</th>
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
@push('scripts')
    <script type="text/javascript">
        var name = 'Perfume PLC';
        name += `\n Finish Good Wise Sales Report`;
        var reportTittle = name;

        var dataTableOptions = {
            dom: '<"top"f>rt<"bottom"lip>', // Removed the 'B' for buttons replase fB only use f
            // buttons: [{
            //     extend: 'collection',
            //     text: 'Export',
            //     className: 'btn btn-sm btn-light-primary',

            //     buttons: [{
            //             extend: 'excel',
            //             text: 'Excel',
            //             title: `${reportTittle}`,
            //             className: 'btn btn-sm btn-light-primary'
            //         },
            //         {
            //             extend: 'copy',
            //             text: 'Copy',
            //             title: `${reportTittle}`,
            //             className: 'btn btn-sm btn-light-primary'
            //         },
            //         {
            //             extend: 'csv',
            //             text: 'CSV',
            //             title: `${reportTittle}`,
            //             className: 'btn btn-sm btn-light-primary'
            //         },
            //         {
            //             extend: 'pdf',
            //             text: 'PDF',
            //             title: `${reportTittle}`,
            //             className: 'btn btn-sm btn-light-primary'
            //         },
            //         {
            //             extend: 'print',
            //             text: 'Print',
            //             title: `${reportTittle}`,
            //             className: 'btn btn-sm btn-light-primary'
            //         }
            //     ]
            // }],
            paging: true,
            ordering: false,
            searching: false,
            responsive: false,
            lengthMenu: [10, 25, 50, 100],
            pageLength: 25,
            // buttons: false, // Kept this to ensure no buttons are shown
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
        var table = $('#finish_good_wise_sales_report').DataTable(dataTableOptions);

        function CallBack(pdfdata) {
            let url;
            var startDate = $('#start_date').val();
            var endDate = $('#end_date').val();
            url =
                '{{ route('finish_good_wise_stock_search', ['startDate' => ':startDate', 'endDate' => ':endDate', 'pdf' => ':pdf']) }}';

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
                        console.log('data:', data);
                        table.clear().draw();
                        if (data.length > 0) {

                            console.log(data);

                            $('#jsdataerror').text('');
                            $.each(data, function(key, value) {
                                let d = data[key];
                                let balance = d.TotQty - d.OutQty;

                                let PreQtyCell = '<div style="text-align: right;">' + formatCurrency(d
                                    .PreQty) + '</div>';
                                let InQtyCell = '<div style="text-align: right;">' + formatCurrency(d
                                    .InQty) + '</div>';
                                let TotQtyCell = '<div style="text-align: right;">' + formatCurrency(d
                                    .TotQty) + '</div>';
                                let OutQtyCell = '<div style="text-align: right;">' + formatCurrency(d
                                    .OutQty) + '</div>';
                                let balanceCell = '<div style="text-align: right;">' + formatCurrency(
                                    balance) + '</div>';

                                table.row.add([d.id, d.product_name, d.unit_id, PreQtyCell, InQtyCell,
                                    TotQtyCell, OutQtyCell, balanceCell
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
@endpush

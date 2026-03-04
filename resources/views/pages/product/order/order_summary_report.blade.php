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

    <!--begin::Content-->
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="">

            <div class="card card-flush">
                <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                    <!--begin::Card title-->
                    <div class="card-title">
                        <h3>Order Summary Report</h3>
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
                            class="btn btn-sm btn-primary">{{ __('Search') }}</button>
                    </div>
                    <div class="d-flex flex-row-reverse pt-0 pt-md-5">
                        <a href="" id="downloadPdf" name="downloadPdf" type="button"
                            class="btn btn-sm btn-light-primary" target="_blank">
                            PDF Download
                        </a>
                    </div>

                </div>
                <div class="card-body pt-0 table-responsive">
                    <table class="table table-striped table-bordered align-middle table-row-dashed fs-7 gy-5 mb-0"
                        id="orders_summary_table">
                        <thead>
                            <tr class="text-start fs-7 text-uppercase gs-0">
                                <th class="min-w-50px text-center">SN</th>
                                <th class="min-w-50px text-center">Product ID</th>
                                <th class="min-w-100px text-center">Product Name</th>
                                <th class="min-w-100px text-center">Unit Name</th>
                                <th class="min-w-100px text-center">Order Count</th>
                                <th class="min-w-100px text-center">Total Stock Out</th>
                            </tr>
                        </thead>
                        <tbody class="fw-semibold text-gray-700 text-center">

                        </tbody>
                    </table>
                </div>
                <!--end::Card body-->
            </div>

        </div>
        <!--end::Content container-->
    </div>
    <!--end::Content-->
</x-default-layout>

<script type="text/javascript">
    var name = 'Sonali Paper & Board Mills Ltd.';
    name += `\n Order Summary Report`;
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

    var table = $('#orders_summary_table').DataTable(dataTableOptions);

    function OrderList(pdfdata) {
        let url;
        var startDate = $('#start_date').val();
        var endDate = $('#end_date').val();
        url =
            '{{ route('orderSummaryReports', ['startDate' => ':startDate', 'endDate' => ':endDate', 'pdf' => ':pdf']) }}';
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
                    console.log('Challan data:', data);
                    let id = 1;
                    table.clear().draw();
                    if (data.length > 0) {
                        $.each(data, function(key, value) {
                            let d = data[key];
                            table.row.add([id, d.product_id, d.product_name, d.unit_name, d.order_count,
                                d.total_stock_out
                            ]).draw();
                            id++;
                        });
                        table.buttons().enable();
                    } else {
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
    $(document).ready(function() {
        OrderList('list');
    });

    $('#searchBtn').on('click', function() {
        OrderList("list");
    });

    $('#downloadPdf').on('click', function() {
        OrderList('pdfurl');
    });

</script>

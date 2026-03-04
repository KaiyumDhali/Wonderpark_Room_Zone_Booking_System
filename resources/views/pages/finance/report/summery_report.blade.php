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

    <!--begin::Content-->
    <div id="kt_app_content" class="app-content flex-column-fluid my-5">
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container">
            <!--begin::Products-->
            <div class="card card-flush">
                <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                    <!--begin::Card title-->
                    <div class="card-title">
                        <h2>Total Summery Report</h2>
                    </div>
                    <!--begin::Card toolbar-->
                    <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                        <div class="form-group col-md-3">
                            <label for="order">{{ __('Start Date') }}</label>
                            <input id="start_date" type="date" value="{{ date('Y-m-d') }}"
                                class="form-control form-control-sm form-control-solid" name="start_date" />
                        </div>
                        <div class="form-group col-md-3">
                            <label for="order">{{ __('End Date') }}</label>
                            <input id="end_date" type="date" value="{{ date('Y-m-d') }}"
                                class="form-control form-control-sm form-control-solid" name="end_date" />
                        </div>
                    </div>
                    <a href="" id="downloadPdf" name="downloadPdf" type="button"
                        class="btn btn-sm btn-light-primary mt-5" target="_blank">
                        PDF Download
                    </a>
                </div>

                <!--begin::Card body-->
                <div class="card-body pt-0">
                    <div class="row">
                        <div class="col-md-3 my-5">
                            <div class="card p-10">

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="col-md-12">
                                            <h2 id="totalSales">0.00 ৳</h2>
                                            <h4>Total Sales</h4>
                                        </div>

                                        
                                        <div class="col-md-12 d-flex">
                                            <div class="col-md-6">
                                                <h6 id="productPrice">0.00 ৳</h6>
                                                <h5>Product Price</h5>
                                            </div>

                                            <div class="col-md-6">
                                                <h6 id="profit">0.00 ৳</h6>
                                                <h5>Profit</h5>
                                            </div>
                                        </div>

                                    </div>
                                </div>






                            </div>
                        </div>
                        <div class="col-md-3 my-5">
                            <div class="card p-10">
                                <h2 id="totalPurchase">0.00 ৳</h2>
                                <h4>Total Purchase</h4>
                            </div>
                        </div>
                        <div class="col-md-3 my-5">
                            <div class="card p-10">
                                <h2 id="totalExpense">0.00 ৳</h2>
                                <h4>Total Expense</h4>
                            </div>
                        </div>
                        <div class="col-md-3 my-5">
                            <div class="card p-10">
                                <h2 id="currentStockAmount">0.00 ৳</h2>
                                <h4>Current Stock Balance</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Products-->
        </div>
        <!--end::Content container-->
    </div>
    <!--end::Content-->
</x-default-layout>
<script>
    // get startDate and endDate
    var startDate = $('#start_date').val();
    var endDate = $('#end_date').val();

    // start_date on change action
    $('#start_date').on('change', function() {
        // get on change date
        startDate = $('#start_date').val();
        endDate = $('#end_date').val();

        const start_date = new Date(startDate);
        const end_date = new Date(endDate);

        // Check if the start date is after the end date
        if (start_date > end_date) {
            Swal.fire({
                icon: 'warning',
                title: 'Invalid Date Range',
                text: 'The start date cannot be after the end date.',
                confirmButtonText: 'OK'
            }).then(() => {
                // Reset start date to end date value
                $('#start_date').val(endDate);
                startDate = $('#start_date').val();
            });
        }

        // fetchGetData Call Data
        fetchGetData(startDate, endDate, 'list');
    });

    // end_date on change action
    $('#end_date').on('change', function() {
        // get on change date
        startDate = $('#start_date').val();
        endDate = $('#end_date').val();

        const start_date = new Date(startDate);
        const end_date = new Date(endDate);

        // Check if the start date is after the end date
        if (start_date > end_date) {
            Swal.fire({
                icon: 'warning',
                title: 'Invalid Date Range',
                text: 'The end date cannot be before the start date.',
                confirmButtonText: 'OK'
            }).then(() => {
                // Reset end date to start date value
                $('#end_date').val(startDate);
                endDate = $('#start_date').val();
            });
        }

        // fetchGetData Call Data
        fetchGetData(startDate, endDate, 'list');
    });

    function fetchGetData(startDate, endDate, pdfdata) {
        let url =
            "{{ route('summary_report_search', ['startDate' => ':startDate', 'endDate' => ':endDate', 'pdf' => ':pdf']) }}";
        url = url.replace(':startDate', startDate)
            .replace(':endDate', endDate)
            .replace(':pdf', pdfdata);

        if (pdfdata === 'list') {
            $.ajax({
                url: url,
                type: "GET",
                data: {
                    "_token": "{{ csrf_token() }}"
                },
                dataType: "json",
                success: function(response) {
                    console.log('fetchGetData:', response);

                    if (response && !Array.isArray(response)) {
                        // Display the values directly if a single object is returned
                        $('#jsdataerror').text('');
                        $('#totalPurchase').text((response.totalSummeryReport[0]?.total_purchase_amount ||
                            '0.00') + ' ৳');
                        $('#totalSales').text((response.totalSummeryReport[0]?.total_sales_amount ||
                            '0.00') + ' ৳');
                        $('#totalExpense').text((response.totalSummeryReport[0]?.total_expense_amount ||
                            '0.00') + ' ৳');
                        $('#currentStockAmount').text((response.totalSummeryReport[0]
                            ?.current_stock_amount || '0.00') + ' ৳');
                        $('#productPrice').text((response.totalProfit[0]?.total_purchase || '0.00') + ' ৳');
                        $('#profit').text((response.totalProfit[0]?.total_profit || '0.00') + ' ৳');

                    } else if (Array.isArray(response) && response.totalSummeryReport[0]?.length > 0) {
                        // In case the server returns an array, take the first item
                        const data = response[0];
                        $('#jsdataerror').text('');
                        $('#totalPurchase').text((data.totalSummeryReport[0]?.total_purchase_amount ||
                            '0.00') + ' ৳');
                        $('#totalSales').text((data.totalSummeryReport[0]?.total_sales_amount || '0.00') +
                            ' ৳');
                        $('#totalExpense').text((data.totalSummeryReport[0]?.total_expense_amount ||
                            '0.00') + ' ৳');
                        $('#currentStockAmount').text((data.totalSummeryReport[0]?.current_stock_amount ||
                            '0.00') + ' ৳');
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
        } else if (pdfdata === 'pdfurl') {
            $('#downloadPdf').attr('href', url);
        } else {
            $('#jsdataerror').text('Please select a date');
        }
    }
    // Example usage
    fetchGetData(startDate, endDate, 'list');

    // function fetchGetData(startDate, endDate, pdfdata) {
    //     let url;
    //     url =
    //         "{{ route('summary_report_search', ['startDate' => ':startDate', 'endDate' => ':endDate', 'pdf' => ':pdf']) }}";
    //     url = url.replace(':startDate', startDate);
    //     url = url.replace(':endDate', endDate);
    //     url = url.replace(':pdf', pdfdata);
    //     if (pdfdata == 'list') {
    //         $.ajax({
    //             url: url,
    //             type: "GET",
    //             data: {
    //                 "_token": "{{ csrf_token() }}"
    //             },
    //             dataType: "json",
    //             success: function(data) {
    //                 console.log('fetchGetData :', data);

    //                 if (data) {
    //                     $('#jsdataerror').text('');
    //                     $('#totalPurchase').text(data.total_purchase_amount);
    //                     $('#totalSales').text(data.total_sales_amount);                        
    //                 } else {
    //                     $('#jsdataerror').text('No records found');
    //                     console.log('No records found');
    //                 }
    //             },
    //             error: function(xhr, status, error) {
    //                 console.log(xhr.responseText);
    //                 $('#jsdataerror').text('Error occurred while fetching data');
    //             }
    //         });
    //     } else if (pdfdata == 'pdfurl') {
    //         $('#downloadPdf').attr('href', url);
    //     } else {
    //         $('#jsdataerror').text('Please select a date');
    //     }
    // }
    // fetchGetData(startDate, endDate, 'list');

    $('#downloadPdf').on('click', function() {
        startDate = $('#start_date').val();
        endDate = $('#end_date').val();
        fetchGetData(startDate, endDate, "pdfurl");
    });
</script>

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

    {{-- message show --}}
    <div class="col-xl-12 px-10">
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

    <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
        <!--begin::Content wrapper-->
        <div class="d-flex flex-column flex-column-fluid">
            <!--begin::Toolbar-->
            <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
                <!--begin::Toolbar container-->
                <div id="kt_app_toolbar_container" class="app-container d-flex flex-stack">
                    <!--begin::Page title-->
                    <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                        <!--begin::Title-->
                        <h3 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                            Payment Voucher</h3>
                        <!--end::Title-->
                    </div>
                    <!--end::Page title-->
                </div>
                <!--end::Toolbar container-->
            </div>
            <!--end::Toolbar-->
            <!--begin::Content-->
            <div id="kt_app_content" class="app-content flex-column-fluid">
                <!--begin::Content container-->
                <div id="kt_app_content_container" class="app-container">
                    <form id="kt_ecommerce_add_category_form"
                        class="form d-flex flex-column flex-lg-row fv-plugins-bootstrap5 fv-plugins-framework"
                        method="POST" action="{{ route('payment_voucher_store') }}" enctype="multipart/form-data">
                        @csrf
                        <!--begin::Main column-->
                        <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
                            <!--begin::General options-->
                            <div class="card card-flush py-4">
                                <!--begin::Card header-->
                                <div class="card-header">
                                    <div class="card-title">
                                        <h2>Voucher Entry</h2>
                                    </div>
                                </div>
                                <!--end::Card header-->
                                <div class="row">
                                    <div class="col-12 col-md-3">
                                        <div class="card-body pt-0 pb-3">
                                            <div class="fv-row fv-plugins-icon-container">
                                                <label class="required form-label">Date</label>
                                                <input type="date" name="date"
                                                    class="form-control form-control-sm mb-2" placeholder="Date"
                                                    value="" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <div class="card-body pt-0 pb-3">
                                            <div class="fv-row fv-plugins-icon-container">
                                                <label class="required form-label">Account Name</label>
                                                <select id="changeAccountsName" class="form-select form-select-sm"
                                                    name="accounts_name" data-control="select2" data-hide-search="false"
                                                    required>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <div class="card-body pt-0 pb-2">
                                            <div class="fv-row fv-plugins-icon-container">
                                                <label class="required form-label">Payment To</label>
                                                <select id="receivedTo" class="form-select form-select-sm"
                                                    name="received_to" data-control="select2" data-hide-search="false"
                                                    required>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <div class="card-body pt-0 pb-3">
                                            <div class="fv-row fv-plugins-icon-container">
                                                <label class="required form-label">Amount</label>
                                                <input type="number" name="amount"
                                                    class="form-control form-control-sm mb-2" placeholder="Amount"
                                                    value="" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="card-body pt-0 pb-3">
                                            <div class="fv-row fv-plugins-icon-container">
                                                <label class="form-label">Narration</label>
                                                <input type="text" name="narration"
                                                    class="form-control form-control-sm mb-2" placeholder="narration"
                                                    value="">
                                            </div>
                                        </div>
                                    </div>
                                    {{-- <div class="col-12 col-md-3">
                                        <div class="card-body pt-0 pb-2">
                                            <div class="fv-row fv-plugins-icon-container">
                                                <label class=" form-label">Payment Type</label>
                                                <select class="form-select form-select-sm" name="status" >
                                                    <option value="" selected>--Select Type--</option>
                                                    <option value="1">Cr</option>
                                                    <option value="0">Dr</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div> --}}
                                    <div class="col-12 col-md-3">
                                        <div class="card-body pt-0 pb-2">
                                            <div
                                                class="fv-row fv-plugins-icon-container d-flex justify-content-start mt-8">
                                                <!--begin::Button-->
                                                <button type="submit" id="kt_ecommerce_add_category_submit"
                                                    class="btn btn-sm btn-success">
                                                    <span class="indicator-label">Save Voucher Entry</span>
                                                    <span class="indicator-progress">Please wait...
                                                        <span
                                                            class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                                </button>
                                                <!--end::Button-->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--end::Main column-->
                    </form>
                </div>
                <!--end::Content container-->
            </div>
            <!--end::Content-->
        </div>
        <!--end::Content wrapper-->
    </div>
    
    <!--begin:: List Content-->
    <div id="kt_app_content" class="app-content flex-column-fluid my-5">
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container">
            <!--begin::Products-->
            <div class="card card-flush">
                <div class="card-header align-items-center py-0 gap-2 gap-md-5">
                    <!--begin::Card title-->
                    <div class="card-title">
                        <h3>Payment Voucher List</h3>
                    </div>
                    <!--begin::Card toolbar-->
                    <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                        <div class="form-group col-md-3">
                            <label for="order">{{ __('Start Date') }}</label>
                            <input id="start_date" type="date" value="{{ date('Y-m-d', strtotime($startDate)) }}"
                            class="form-control form-control-sm form-control-solid" id="" name="start_date" />
                        </div>
                        <div class="form-group col-md-3">
                            <label for="order">{{ __('End Date') }}</label>
                            <input id="end_date" type="date" value="{{ date('Y-m-d', strtotime($endDate)) }}"
                            class="form-control form-control-sm form-control-solid" id="" name="end_date" />
                        </div>
                        
                    </div>
                </div>
                
                <!--begin::Card body-->
                <div class="card-body pt-0" id="payment_voucher_table_div">
                    <!--begin::Table-->
                    <table class="table table-striped table-bordered align-middle table-row-dashed fs-7 gy-5 mb-0"
                        id="payment_voucher_table">
                        <!--begin::Table head-->
                        <thead>
                            <!--begin::Table row-->
                            <tr class="text-start fs-7 text-uppercase gs-0">
                                <th class="text-center min-w-20px">SL</th>
                                <th class="text-center min-w-80px">Voucher NO</th>
                                <th class="text-center min-w-80px">Voucher Date</th>
                                <th class="text-center min-w-150px">Account Name</th>
                                <th class="text-center min-w-150px">Payment To</th>
                                <th class="text-center min-w-80px">Amount</th>
                                <th class="text-center min-w-20px">View</th>
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
    <!--end:: List Content-->
</x-default-layout>

<script>
    $(document).ready(function() {
        var accountNameListData;

        function CallBack() {
            let url = '{{ route('account_name_list') }}';

            $.ajax({
                url: url,
                type: "GET",
                data: {
                    "_token": "{{ csrf_token() }}"
                },
                dataType: "json",
                success: function(response) {
                    accountNameListData = response.accountNameList;

                    // Populate options in changeAccountsName select
                    $('#changeAccountsName').append(
                        '<option value="" selected>-- Select Account Name --</option>');
                    if (accountNameListData && Object.keys(accountNameListData).length > 0) {
                        $.each(accountNameListData, function(id, accountName) {
                            $('#changeAccountsName').append(
                                `<option value="${id}">${accountName}</option>`
                            );
                        });
                    } else {
                        $('#jsdataerror').text('Account names not found or empty.');
                    }

                    // Populate options in receivedTo select
                    $('#receivedTo').append(
                        '<option value="" selected>-- Select Received To --</option>');
                    if (accountNameListData && Object.keys(accountNameListData).length > 0) {
                        $.each(accountNameListData, function(id, accountName) {
                            $('#receivedTo').append(
                                `<option value="${id}">${accountName}</option>`);
                        });
                    } else {
                        $('#jsdataerror').text('Account names not found or empty.');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching account names:', error);
                    $('#jsdataerror').text('Error fetching account names.');
                }
            });
        }
        CallBack(); // Call the AJAX function when the document is ready

        // on change AccountsName 
        $('#changeAccountsName').on('change', function() {
            var AccountsNameID = $(this).val();
            $('#receivedTo').empty();
            $('#receivedTo').append(
                '<option value="" selected>-- Select Received To --</option>'
            );
            if (accountNameListData) {
                $.each(accountNameListData, function(id, accountName) {
                    if (AccountsNameID != id) {
                        $('#receivedTo').append(
                            `<option value="${id}">${accountName}</option>`);
                    }
                });
            } else {
                $('#jsdataerror').text('Not Found');
            }
        });
    });

    // get startDate and endDate
    var startDate = 0;
    var endDate = 0;

    // start_date on change action
    $('#start_date').on('change', function() {
        $("#voucher_list").empty();
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
        $("#voucher_list").empty();
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

    var name = 'Global shoes Ltd.';
    name += `\n Payment Voucher`;
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
    var table = $('#payment_voucher_table').DataTable(dataTableOptions);

    // received startDate and endDate then InvoiceList get data 
    function InvoiceList(startDate, endDate) {
        let url;
        url = "{{ route('payment_voucher_date_search', ['startDate' => ':startDate', 'endDate' => ':endDate']) }}";
        url = url.replace(':startDate', startDate);
        url = url.replace(':endDate', endDate);
        console.log(url);
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
                $('#voucher_list').empty(); // Clear previous options
                $('#voucher_list').append('<option selected disabled>Select Invoice</option>');

                if (data) {
                    $('#jsdataerror').text('');
                    $.each(data, function(key, value) {
                        let voucherNo = value[0].voucher_no;
                        let voucherDate = value[0].voucher_date;
                        let to_acc_name = value[0].to_acc_name;
                        let to_acc_name_2 = value[1].to_acc_name;
                        let amount = formatCurrency(value[0].amount);
                        $('#voucher_list').append(`<option value="${voucherNo}">${voucherNo}</option>`);
                        let paymentVoucherUrl = `{{ route('voucher_report_pdf', ['voucherNo' => '_voucherNo_', 'voucherType' => 'paymentVoucher']) }}`.replace('_voucherNo_', voucherNo);
                        let debitVoucherUrl = `{{ route('voucher_report_pdf', ['voucherNo' => '_voucherNo_', 'voucherType' => 'debitVoucher']) }}`.replace('_voucherNo_', voucherNo);
                        let paymentJournalVoucherUrl = `{{ route('voucher_report_pdf', ['voucherNo' => '_voucherNo_', 'voucherType' => 'paymentJournalVoucher']) }}`.replace('_voucherNo_', voucherNo);
                        
                        let view = `
                            <td class="d-flex flex-row">
                                <a href="${paymentVoucherUrl}" class="btn btn-sm btn-success hover-scale m-1" data-bs-toggle="tooltip" data-bs-placement="top" title="View" target="_blank">
                                    Payment Voucher
                                </a>
                                <a href="${debitVoucherUrl}" class="btn btn-sm btn-info hover-scale m-1" data-bs-toggle="tooltip" data-bs-placement="top" title="View" target="_blank">
                                    Credit Voucher
                                </a>
                                <a href="${paymentJournalVoucherUrl}" class="btn btn-sm btn-primary hover-scale m-1" data-bs-toggle="tooltip" data-bs-placement="top" title="View" target="_blank">
                                    Journal Voucher
                                </a>
                            </td>
                        `;

                        let rowIndex = table.rows().count() + 1;
                        // Add row to DataTable

                        let amountCell = '<div style="text-align: right;">' + amount + '</div>';
                        let viewCell = '<div class="d-flex flex-row" style="text-align: center;">' + view + '</div>';

                        table.row.add([rowIndex, voucherNo, formatDate(voucherDate), to_acc_name, to_acc_name_2, amountCell, viewCell]).draw();
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
    }
    InvoiceList(startDate, endDate);
</script>

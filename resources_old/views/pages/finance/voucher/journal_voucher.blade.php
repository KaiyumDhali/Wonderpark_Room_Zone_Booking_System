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
                        <h3 class="page-heading d-flex text-dark fs-1 flex-column justify-content-center my-0">
                            Journal Voucher</h3>
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
                    <form id="journalVoucherForm"
                        class="form d-flex flex-column flex-lg-row fv-plugins-bootstrap5 fv-plugins-framework"
                        method="POST" action="{{ route('journal_voucher_store') }}" enctype="multipart/form-data"
                        onsubmit="return validateForm()">
                        @csrf
                        <!--begin::Main column-->
                        <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
                            <!--begin::General options-->
                            <div class="card card-flush py-4">
                                <!--begin::Card header-->
                                {{-- <div class="card-header">
                                    <div class="card-title">
                                        <h2>Journal Entry</h2>
                                    </div>
                                </div> --}}
                                <!--end::Card header-->
                                <div class="row px-10 py-5">
                                    <div class="col-12 col-md-2">
                                        <label class="form-label">Voucher Date</label>
                                        <input type="date" name="voucher_date"
                                            class="form-control form-control-sm mb-2" placeholder="Date" value="">
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <label class="form-label">Account Name</label>
                                        <select id="accountName" class="form-select form-select-sm" name=""
                                            data-control="select2" data-hide-search="false">
                                            <option selected disabled>Select Account</option>
                                            @foreach ($accountList as $key => $value)
                                                <option value="{{ $key }}">{{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <label class="form-label">Amount</label>
                                        <input type="number" id="amount" name=""
                                            class="form-control form-control-sm mb-2" placeholder="Amount"
                                            value="">
                                    </div>
                                    <div class="col-12 col-md-2">
                                        <label class="form-label">Type</label>
                                        <select class="form-select form-select-sm" data-control="select2" id="balanceType" name="">
                                            <option value="Cr">Cr</option>
                                            <option value="Dr">Dr</option>
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-2">
                                        <!--begin::Button-->
                                        <button type="button" id="addEntry" class="btn btn-sm btn-primary mt-8">
                                            <span class="indicator-label">Add Entry</span>
                                        </button>
                                        <!--end::Button-->
                                    </div>
                                </div>
                                <!-- Entry List -->
                                <div class="row">
                                    <div class="col-12">
                                        <div class="card-body pt-0 pb-3">
                                            <div class="fv-row fv-plugins-icon-container">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>Sl No</th>
                                                            <th>Account Name</th>
                                                            <th>Amount</th>
                                                            <th>Type</th>
                                                            <th>Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="entryList">
                                                        <!-- Entries will be appended here -->
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- narration Field -->
                                <div class="row">
                                    <div class="col-12">
                                        <div class="card-body pt-0 pb-3">
                                            <div class="fv-row fv-plugins-icon-container">
                                                <label class="form-label">Narration</label>
                                                <textarea name="narration" class="form-control form-control-sm mb-2" placeholder="Narration" rows="3"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Submit Button -->
                                <div class="row">
                                    <div class="col-12">
                                        <div class="card-body pt-0 pb-2">
                                            <div
                                                class="fv-row fv-plugins-icon-container d-flex justify-content-start mt-8">
                                                <!--begin::Button-->
                                                <button type="submit" id="submitVoucher"
                                                    class="btn btn-sm btn-success">
                                                    <span class="indicator-label">Submit Journal Voucher</span>
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
    <!--begin::Content-->
    <div id="kt_app_content" class="app-content flex-column-fluid my-5">
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container">
            <!--begin::Products-->
            <div class="card card-flush">
                <div class="card-header align-items-center py-0 gap-2 gap-md-5">
                    <!--begin::Card title-->
                    <div class="card-title">
                        <h3>Journal Voucher List</h3>
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
                    <a href="" id="downloadPdf" name="downloadPdf" type="button"
                        class="btn btn-sm btn-light-primary mt-5" target="_blank">
                        PDF Download
                    </a>
                </div>

                <!--begin::Card body-->
                <div class="card-body pt-0">
                    <!--begin::Table-->
                    <table class="table table-striped table-bordered align-middle table-row-dashed fs-7 gy-5 mb-0"
                        id="journal_voucher_table">
                        <!--begin::Table head-->
                        <thead>
                            <!--begin::Table row-->
                            <tr class="text-start fs-7 text-uppercase gs-0">
                                <th class="text-center min-w-20px ">SL</th>
                                <th class="text-center min-w-80px">Voucher NO</th>
                                <th class="text-center min-w-80px">Voucher Date</th>
                                <th class="text-center min-w-150px">Account Name</th>
                                <th class="text-center min-w-150px">Received From</th>
                                <th class="text-center min-w-80px">Amount</th>
                                <th class="text-center min-w-80px">Type</th>
                                {{-- <th class="text-center">Action</th> --}}
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
<script>
    document.getElementById('addEntry').addEventListener('click', function() {
        const accountName = document.getElementById('accountName').selectedOptions[0].text;
        const accountValue = document.getElementById('accountName').value;
        const amount = document.getElementById('amount').value;
        const balanceType = document.getElementById('balanceType').value;

        if (accountValue && amount) {
            const entryList = document.getElementById('entryList');
            const rows = entryList.querySelectorAll('tr');
            let accountExists = false;
            let typeExists = false;

            // Check for existing entries
            rows.forEach(row => {
                const existingAccountValue = row.querySelector('input[name="account_id[]"]').value;
                const existingType = row.querySelector('input[name="balance_type[]"]').value;

                // Check for duplicate account ID
                if (existingAccountValue === accountValue) {
                    accountExists = true;
                }

                // Check for existing Cr or Dr entries with the same balance type
                if (existingType === balanceType) {
                    typeExists = true;
                }
            });

            // Show alerts if necessary
            if (accountExists) {
                Swal.fire({
                    icon: 'error',
                    title: 'Duplicate Account',
                    text: `The account "${accountName}" is already added.`
                });
                return;
            }

            if (typeExists) {
                Swal.fire({
                    icon: 'error',
                    title: 'Duplicate Entry',
                    text: `An entry for ${balanceType} is already added.`
                });
                return;
            }

            // Add the new entry if no duplicates were found
            const rowCount = rows.length + 1;
            const row = document.createElement('tr');
            row.innerHTML = `
            <td>${rowCount}</td>
            <td>${accountName}</td>
            <td>${amount}</td>
            <td>${balanceType}</td>
            <td>
                <button type="button" class="btn btn-sm btn-danger remove-entry">Remove</button>
                <input type="hidden" name="account_names[]" value="${accountName}">
                <input type="hidden" name="account_id[]" value="${accountValue}">
                <input type="hidden" name="amount[]" value="${amount}">
                <input type="hidden" name="balance_type[]" value="${balanceType}">
            </td>
        `;
            entryList.appendChild(row);

            // Clear inputs after adding
            document.getElementById('amount').value = '';

            // Update Serial Numbers
            updateSerialNumbers();
        }
    });

    document.getElementById('entryList').addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-entry')) {
            e.target.closest('tr').remove();
            updateSerialNumbers();
        }
    });

    function updateSerialNumbers() {
        const rows = document.querySelectorAll('#entryList tr');
        rows.forEach((row, index) => {
            row.cells[0].innerText = index + 1;
        });
    }

    function validateForm() {
        const amounts = document.getElementsByName('amount[]');
        const types = document.getElementsByName('balance_type[]');

        let totalCr = 0;
        let totalDr = 0;

        for (let i = 0; i < amounts.length; i++) {
            const amount = parseFloat(amounts[i].value);
            const type = types[i].value;

            if (type === 'Cr') {
                totalCr += amount;
            } else if (type === 'Dr') {
                totalDr += amount;
            }
        }

        if (totalCr !== totalDr) {
            Swal.fire({
                icon: 'error',
                title: 'Mismatch',
                text: 'The total of Debit and Credit amounts must be equal.'
            });
            return false;
        }

        return true;
    }


    // -------------------------------------------------------------
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
        InvoiceList(startDate, endDate, 'list');
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
        InvoiceList(startDate, endDate, 'list');
    });

    var name = 'Perfume PLC.';
    name += `\n Journal Voucher`;
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
    var table = $('#journal_voucher_table').DataTable(dataTableOptions);

    function InvoiceList(startDate, endDate, pdfdata) {
        let url;
        url =
            "{{ route('journal_voucher_date_search', ['startDate' => ':startDate', 'endDate' => ':endDate', 'pdf' => ':pdf']) }}";
        url = url.replace(':startDate', startDate);
        url = url.replace(':endDate', endDate);
        url = url.replace(':pdf', pdfdata);
        if (pdfdata == 'list') {
            $.ajax({
                url: url,
                type: "GET",
                data: {
                    "_token": "{{ csrf_token() }}"
                },
                dataType: "json",
                success: function(data) {
                    console.log('InvoiceList :', data);
                    table.clear().draw();
                    $('#voucher_list').empty(); // Clear previous options
                    $('#voucher_list').append('<option selected disabled>Select Invoice</option>');

                    if (data) {
                        $('#jsdataerror').text('');
                        $.each(data, function(key, value) {
                            let voucherNo = value.voucher_no;
                            let voucherDate = value.voucher_date;
                            let to_acc_name = value.to_acc_name;
                            let to_acc_name_2 = value.acid_name;
                            let balance_type = value.balance_type;
                            let amount = formatCurrency(value.amount);
                            $('#voucher_list').append(
                                `<option value="${voucherNo}">${voucherNo}</option>`);
                            let receivedVoucherUrl =
                                `{{ route('voucher_report_pdf', ['voucherNo' => '_voucherNo_', 'voucherType' => 'receivedVoucher']) }}`
                                .replace('_voucherNo_', voucherNo);
                            let creditVoucherUrl =
                                `{{ route('voucher_report_pdf', ['voucherNo' => '_voucherNo_', 'voucherType' => 'creditVoucher']) }}`
                                .replace('_voucherNo_', voucherNo);
                            let receivedJournalVoucher =
                                `{{ route('voucher_report_pdf', ['voucherNo' => '_voucherNo_', 'voucherType' => 'receivedJournalVoucher']) }}`
                                .replace('_voucherNo_', voucherNo);

                            let view = `
                            <td class="d-flex flex-row">
                                <a href="${receivedVoucherUrl}" class="btn btn-sm btn-success hover-scale m-1" data-bs-toggle="tooltip" data-bs-placement="top" title="View" target="_blank">
                                    Money Receipt
                                </a>
                                <a href="${creditVoucherUrl}" class="btn btn-sm btn-info hover-scale m-1" data-bs-toggle="tooltip" data-bs-placement="top" title="View" target="_blank">
                                    Credit Voucher
                                </a>
                                <a href="${receivedJournalVoucher}" class="btn btn-sm btn-primary hover-scale m-1" data-bs-toggle="tooltip" data-bs-placement="top" title="View" target="_blank">
                                    Journal Voucher
                                </a>
                            </td>
                        `;
                            let rowIndex = table.rows().count() + 1;
                            // Add row to DataTable

                            let amountCell = '<div style="text-align: right;">' + amount + '</div>';
                            let viewCell =
                                '<div class="d-flex flex-row" style="text-align: center;">' +
                                view + '</div>';

                            table.row.add([rowIndex, voucherNo, formatDate(voucherDate),
                                to_acc_name,
                                to_acc_name_2, amountCell, balance_type
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
    InvoiceList(startDate, endDate, 'list');

    $('#downloadPdf').on('click', function() {
        startDate = $('#start_date').val();
        endDate = $('#end_date').val();
        InvoiceList(startDate, endDate, "pdfurl");
    });
</script>

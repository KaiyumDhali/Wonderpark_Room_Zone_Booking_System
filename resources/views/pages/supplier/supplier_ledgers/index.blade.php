<x-default-layout>
    {{-- <style>
        .table > :not(caption) > * > * {
            padding: 0.3rem !important;
        }
    </style> --}}
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
    <div class="col-xl-12 py-5">
        <div class="card card-flush" id="kt_contacts_main">
            <div class="card-header py-0" id="kt_chat_contacts_header">
                <div class="card-title">
                    <!--begin::Svg Icon | path: icons/duotune/communication/com005.svg-->
                    <span class="svg-icon svg-icon-1 me-2">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M20 14H18V10H20C20.6 10 21 10.4 21 11V13C21 13.6 20.6 14 20 14ZM21 19V17C21 16.4 20.6 16 20 16H18V20H20C20.6 20 21 19.6 21 19ZM21 7V5C21 4.4 20.6 4 20 4H18V8H20C20.6 8 21 7.6 21 7Z"
                                fill="currentColor"></path>
                            <path opacity="0.3"
                                d="M17 22H3C2.4 22 2 21.6 2 21V3C2 2.4 2.4 2 3 2H17C17.6 2 18 2.4 18 3V21C18 21.6 17.6 22 17 22ZM10 7C8.9 7 8 7.9 8 9C8 10.1 8.9 11 10 11C11.1 11 12 10.1 12 9C12 7.9 11.1 7 10 7ZM13.3 16C14 16 14.5 15.3 14.3 14.7C13.7 13.2 12 12 10.1 12C8.10001 12 6.49999 13.1 5.89999 14.7C5.59999 15.3 6.19999 16 7.39999 16H13.3Z"
                                fill="currentColor"></path>
                        </svg>
                    </span>
                    <h3>Supplier Ledger</h3>
                </div>
            </div>
            <!--end::Card header-->
            <!--begin::Card body-->
            <div class="card-body py-0">
                <form id="kt_ecommerce_settings_general_form" class="form fv-plugins-bootstrap5 fv-plugins-framework"
                    method="POST" action="{{ route('supplier_ledgers.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="row row-cols-1 row-cols-sm-4 rol-cols-md-1 row-cols-lg-4">
                        <div class="col">
                            <div class="fv-row mb-0 fv-plugins-icon-container">
                                <label class="fs-6 fw-semibold form-label mt-0">
                                    <span class="required">Supplier Name</span>
                                    <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip"
                                        aria-label="Enter the contact's email."
                                        data-bs-original-title="Enter the contact's email." data-kt-initialized="1"></i>
                                </label>
                                <select id="changeSupplier" class="form-select form-select-sm" name="supplier_id">
                                    <option selected disabled>Select Supplier</option>
                                    @foreach ($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}">{{ $supplier->supplier_name }}</option>
                                    @endforeach
                                </select>
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="fv-row mb-0">
                                <label class="fs-6 fw-semibold form-label mt-0">
                                    <span>Mobile Number</span>
                                </label>
                                <input type="text" class="form-control form-control-solid form-control-sm"
                                    name="supplier_mobile" readonly id="supplier_mobile_s" value="">
                            </div>
                        </div>
                        <div class="col">
                            <div class="fv-row mb-0">
                                <label class="fs-6 fw-semibold form-label mt-0">
                                    <span>Due Amount</span>
                                </label>
                                <input type="text" class="form-control form-control-solid form-control-sm"
                                    name="" readonly id="is_previous_due_s" value="">
                            </div>
                        </div>
                        <!--begin::Col-->
                        <div class="col">
                            <!--begin::Input group-->
                            <div class="fv-row mb-7 fv-plugins-icon-container">
                                <!--begin::Label-->
                                <label class="fs-6 fw-semibold form-label mt-0">
                                    <span>Supplier Address</span>
                                </label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" class="form-control form-control-solid form-control-sm" name="supplier_address "
                                    id="supplier_address_s" value="" readonly>

                                <!--end::Input-->
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                            </div>
                            <!--end::Input group-->
                        </div>
                        <!--end::Col-->
                    </div>
                    @can('create supplier ledger')
                    <div class="row row-cols-1 row-cols-sm-4 rol-cols-md-1 row-cols-lg-4">
                        <div class="col">
                            <div class="fv-row mb-7">
                                <label class="fs-6 fw-semibold form-label mt-0">
                                    <span class="required">Received Amount</span>
                                </label>
                                <input type="number" class="form-control form-control-sm"
                                    name="debit" value="" required>
                            </div>
                        </div>
                        <div class="col">
                            <div class="fv-row mb-7">
                                <label class="fs-6 fw-semibold form-label mt-0">
                                    <span>Remarks</span>
                                </label>
                                <input type="text" class="form-control form-control-sm"
                                    name="remarks" value="">
                            </div>
                        </div>
                        <div class="col">
                            <div class="fv-row mb-7 mt-8">
                                <button type="submit" data-kt-contacts-type="submit" class="btn btn-sm btn-primary">
                                    <span class="indicator-label">Save</span>
                                    <span class="indicator-progress">Please wait...
                                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                </button>
                            </div>
                        </div>
                    </div>
                    @endcan
                </form>
            </div>
        </div>
    </div>

    {{-- <div class="card card-flush d-none" id="ledger_list_div_s">
        <!--begin::Card body-->
        <div class="card-body ">
            <!--begin::Table-->
            <div id="kt_ecommerce_category_table_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered align-middle table-row-dashed fs-7 gy-5 mb-0"
                        id="kt_ecommerce_category_table">
                        <!--begin::Table head-->
                        <thead>
                            <!--begin::Table row-->
                            <tr class="text-start fs-7 text-uppercase gs-0">
                                <th class="min-w-50px sorting" tabindex="0"
                                    aria-controls="kt_ecommerce_category_table" rowspan="1" colspan="1"
                                    style="width: 50px;" aria-label="Category: activate to sort column ascending">
                                    Ledger ID </th>
                                <th class="min-w-50px sorting" tabindex="0"
                                    aria-controls="kt_ecommerce_category_table" rowspan="1" colspan="1"
                                    style="width: 50px;" aria-label="Category: activate to sort column ascending">
                                    Ledger Date</th>
                                <th class="min-w-50px sorting" tabindex="0"
                                    aria-controls="kt_ecommerce_category_table" rowspan="1" colspan="1"
                                    style="width: 50px;" aria-label="Category: activate to sort column ascending">
                                    Invoice No</th>
                                <th class="min-w-50px sorting" tabindex="0"
                                    aria-controls="kt_ecommerce_category_table" rowspan="1" colspan="1"
                                    style="width: 50px;" aria-label="Category: activate to sort column ascending">
                                    Debit</th>
                                <th class="min-w-50px sorting" tabindex="0"
                                    aria-controls="kt_ecommerce_category_table" rowspan="1" colspan="1"
                                    style="width: 50px;" aria-label="Category: activate to sort column ascending">
                                    Credit</th>
                                <th class="min-w-50px sorting" tabindex="0"
                                    aria-controls="kt_ecommerce_category_table" rowspan="1" colspan="1"
                                    style="width: 50px;" aria-label="Category: activate to sort column ascending">
                                    Balance</th>
                                <th class="min-w-50px sorting" tabindex="0"
                                    aria-controls="kt_ecommerce_category_table" rowspan="1" colspan="1"
                                    style="width: 50px;" aria-label="Category: activate to sort column ascending">
                                    Remarks</th>
                            </tr>
                        </thead>
                        <tbody class="fw-semibold text-gray-700" id="ledger_list_s">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div> --}}

    <div class="card card-flush d-none" id="ledger_list_div_s">
        <div class="card-header py-0">
            <div class="card-title">
                <i class="bi bi-card-list fs-1 pe-2" ></i>
                <h3><span id="supplier_ledger"></span> Ledger </h3>
            </div>
            <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                <div class="card-title">
                    <div class="d-flex align-items-center position-relative my-1">
                        <div class="card-body p-2">
                            <div class="product fv-row fv-plugins-icon-container">
                                {{-- <input id="start_date" type="date" name="start_date"
                                    class="form-control form-control-sm ps-5 p-2"
                                    value="{{ date('Y-m-d') }}"> --}}

                                <input id="start_date" type="date" name="start_date" class="form-control form-control-sm ps-5 p-2" value="{{ now()->format('Y-m-d') }}">
                            </div>
                        </div>
                    </div>
                    <div id="end_date_div">
                        <div class="card-body p-2">
                            <div class="product fv-row fv-plugins-icon-container">
                                {{-- <input id="end_date" type="date" name="end_date"
                                    class="form-control form-control-sm ps-5 p-2"
                                    value="{{ date('Y-m-d') }}"> --}}
                                <input id="end_date" type="date" name="end_date" class="form-control form-control-sm ps-5 p-2" value="{{ now()->format('Y-m-d') }}">
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex align-items-center position-relative my-1 ">
                        <div class="card-body p-2">
                            <div class="product fv-row fv-plugins-icon-container">
                                <button type="submit" id="searchBtn"
                                    class="btn btn-sm btn-primary px-5">{{ __('Search') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
                <a href="" id="downloadPdf" name="downloadPdf" type="button"
                    class="btn btn-sm btn-light-primary" target="_blank">
                    PDF Download
                </a>
            </div>
        </div>
        <div id="tableDiv" class="card-body pt-0">
            <table class="table table-striped table-bordered align-middle table-row-dashed fs-7 gy-5 mb-0"
                id="supplier_ledger_table">
                <thead>
                    <tr class="text-start fs-7 text-uppercase gs-0">
                        <th class="min-w-50px"> ID</th>
                        {{-- <th class="min-w-100px"> Employee ID</th> --}}
                        <th class="min-w-100px"> Invoice</th>
                        <th class="min-w-100px"> Date</th>
                        <th class="min-w-100px"> Credit</th>
                        <th class="min-w-100px"> Debit</th>
                        <th class="min-w-100px"> Balance</th>
                        <th class="min-w-100px">Remarks</th>
                    </tr>
                </thead>
                <tbody class="fw-semibold text-gray-700" id="">

                </tbody>
            </table>
        </div>
    </div>
</x-default-layout>

<script type="text/javascript">
    var startDate = $('#start_date').val();
    var endDate = $('#end_date').val();
    var name = 'Perfume PLC';
    name += `\n Transaction  Date :  ${startDate} - ${startDate}`;
    var reportTittle = name;

    $('#changeSupplier').on('change', function() {
        var supplierID = $(this).val();
        var supplierName = $('#changeSupplier option:selected').text();
        $('#supplier_ledger').text(supplierName);
        $('#is_previous_due_s').val(0);

        if (supplierID) {
            $.ajax({
                url: 'supplierLedgerDetails/' + supplierID,
                type: "GET",
                data: {
                    "_token": "{{ csrf_token() }}"
                },
                dataType: "json",
                success: function(response) {
                    const data = response.supplierDetails;
                    const supplierData = response.supplier;

                    var supplierMobile;
                    var supplierAddress;
                    if (supplierData) {
                        console.log(supplierData);

                        supplierMobile = supplierData.supplier_mobile;
                        supplierAddress = supplierData.supplier_address;

                        console.log(supplierMobile, supplierAddress);

                        $('#supplier_mobile_s').val(supplierMobile);
                        $('#supplier_address_s').val(supplierAddress);
                    } else {
                        $('#supplier_mobile_s').val('');
                        $('#supplier_address_s').val('');
                    }

                    if (data) {
                        $('#ledger_list_div_s').removeClass('d-none');
                        table.clear().draw();
                        if (data.length > 0) {
                            console.log(data);
                            var balance = 0;

                            $.each(data, function(key, value) {
                                let d = data[key];
                                balance = balance + (d.credit - d.debit);
                                table.row.add([d.id, d.invoice_no, d.ledger_date, d.credit, d.debit, balance, d.remarks,
                                ]).draw();
                            });
                            $('#is_previous_due_s').val(balance);

                            table.buttons().enable();
                        } else {
                            console.log('No records found');
                        }
                    } else {
                    $('#jsdataerror').text('Not Found');
                    }
                }
            });
        } else {
            $('#jsdataerror').text('Not Found');
        }
    });

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

    var table = $('#supplier_ledger_table').DataTable(dataTableOptions);
    function CallBack(pdfdata) {
        var supplierID = $('#changeSupplier').val();
        var startDate = $('#start_date').val();
        var endDate = $('#end_date').val();
        
        let url;
        url = '{{ route('supplier_ledger_search', ['startDate' => ':startDate', 'endDate' => ':endDate', 'supplierID' => ':supplierID', 'pdf' => ':pdf']) }}';
        url = url.replace(':startDate', startDate);
        url = url.replace(':endDate', endDate);
        url = url.replace(':supplierID', supplierID);
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
                    table.clear().draw();
                    if (data.length > 0) {
                        //console.log(data);
                        var balance = 0;

                        $.each(data, function(key, value) {
                            let d = data[key];
                            balance = balance + (d.credit - d.debit);
                            table.row.add([d.id, d.invoice_no, d.ledger_date, d.credit, d.debit, balance, d.remarks,
                            ]).draw();
                        });
                        $('#is_previous_due_s').val(balance);
                        table.buttons().enable();
                    } else {
                        console.log('No records found');
                    }
                }
            });
        } else if (pdfdata == 'pdfurl') {
            $('#downloadPdf').attr('href', url);
        } else {
            console.log('Please select a date');
        }
    }
    //CallBack("list");

    $('#searchBtn').on('click', function() {
        CallBack("list");
    });

    $('#downloadPdf').on('click', function() {
        CallBack("pdfurl");
    });

</script>

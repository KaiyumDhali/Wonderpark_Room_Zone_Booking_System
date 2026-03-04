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
                        <h3>Supplier Wise Purchase Report</h3>
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
                                <label>{{ __('Select Supplier Name') }}</label>
                                <select id="account_name" class="form-control form-control-sm" name="account_name" data-control="select2" data-hide-search="false" required>
                                    <option selected selected value="0">Select Supplier Name</option>
                                    @foreach($acNames as $key => $value)
                                        <option value="{{ $key }}">{{ $value}}</option>
                                    @endforeach
                                </select>
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
                        id="date_wise_sales_report">
                        <!--begin::Table head-->
                        <thead>
                            <!--begin::Table row-->
                            <tr class="fs-7 text-uppercase gs-0">
                                <th class="text-center w-40px">Date</th>
                                <th class="text-center w-400px">Party Name</th>
                                <th class="text-center w-80px">Voucher Type</th>
                                <th class="text-center w-100px">Voucher No</th>
                                <th class="text-center w-100px">Debit Amount</th>
                                <th class="text-center w-100px">Credit Amount</th>
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
    name += `\n Supplier Wise Purchase Register`;
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
    var table = $('#date_wise_sales_report').DataTable(dataTableOptions);

    function CallBack(pdfdata) {
        let url;
        var startDate = $('#start_date').val();
        var endDate = $('#end_date').val();
        var accountName = $('#account_name').val();
        url =
            '{{ route('supplier_wise_purchase_search', ['startDate' => ':startDate', 'endDate' => ':endDate', 'accountName' => ':accountName', 'pdf' => ':pdf']) }}';

        url = url.replace(':startDate', startDate);
        url = url.replace(':endDate', endDate);
        url = url.replace(':accountName', accountName);
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
                            let type = '';
                            if (d.type === 'PV') {
                                if (d.balance_type === 'Dr') {
                                    type = 'Payment';
                                } else if (d.balance_type === 'Cr') {
                                    type = 'Purchase';
                                }
                            } 
                            
                            let debit = '';
                            if (d.balance_type === 'Dr') {
                                debit = formatCurrency(d.amount);
                            } else {
                                debit ='';
                            }
                            
                            let credit = '';
                            if (d.balance_type === 'Cr') {
                                credit = formatCurrency(d.amount);
                            } else {
                                credit ='';
                            }
                            
                            let particulars = '<span style="font-size: 14px; font-style: italic; font-weight: bold">' + d.account_name + '</span></br>' + d.narration; 
                            
                            // Add inline styles for right alignment
                            let debitCell = '<div style="text-align: right;">' + debit + '</div>';
                            let creditCell = '<div style="text-align: right;">' + credit + '</div>';

                            table.row.add([
                                formatDate(d.transaction_date),
                                particulars,
                                type,
                                d.voucher_no,
                                debitCell,
                                creditCell
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

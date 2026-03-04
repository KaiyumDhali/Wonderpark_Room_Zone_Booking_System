<x-default-layout>
    <style>
    .card .card-header {
        min-height: 40px;
    }

    .table> :not(caption)>*>* {
        padding: 0.3rem !important;
    }
    </style>
    <div class="container-fluid">
        <!-- Page Heading -->
        @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        <form id="" method="POST" action="{{ route('purchase_search') }}" enctype="multipart/form-data">
            @csrf
            <div class="row pt-10">
                <!-- Content Row -->
                <div class="col-12 p-5">
                    <div class="row">
                        <div class="col-md-12 card shadow">
                            <div class="card-header py-0">
                                <div class="d-sm-flex align-items-center justify-content-between">
                                    <h1 class="h3 mb-0 text-gray-800">{{ __('Product Purchase Report') }}</h1>
                                </div>
                                {{-- <label class="badge text-warning" id="jsdataerror"></label> --}}
                            </div>
                            <div class="card-body py-3">
                                <div class="col-md-12 pb-3">

                                    <div class="row">
                                        <div class="form-group col-3">
                                            <label for="order">{{ __('Start Date') }}</label>
                                            <input id="start_date" type="date" value="{{ date('Y-m-d', strtotime($startDate)) }}"
                                                   class="form-control form-control-sm form-control-solid" id=""
                                                   name="start_date" />
                                        </div>
                                        <div class="form-group col-3">
                                            <label for="order">{{ __('End Date') }}</label>
                                            <input id="end_date" type="date" value="{{ date('Y-m-d', strtotime($endDate)) }}"
                                                   class="form-control form-control-sm form-control-solid" id=""
                                                   name="end_date" />
                                        </div>
                                        <div class="form-group col-3">
                                            <label>{{ __('Invoice No') }}</label>
                                            <select id="invoice_list" class="form-control form-control-sm"
                                                    name="invoice_list" required>
                                                <option selected disabled>Select Invoice</option>
                                                @foreach($invoices as $invoice)
                                                <option value="{{ $invoice[0]->invoice_no }}">{{ $invoice[0]->invoice_no}}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group col-1">
                                            <button type="submit" id="searchBtn" class="btn btn-sm btn-primary px-5 mt-5">{{ __('Search') }}</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </form>
    </div>
    @if($stocks)
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="">
            <!--begin::Order details page-->
            <div class="d-flex flex-column gap-5 gap-lg-5">
                <!--begin::Tab content-->
                <div class="tab-content">
                    <!--begin::Tab pane-->
                    <div class="tab-pane fade show active" id="kt_ecommerce_sales_order_summary" role="tab-panel">
                        <!--begin::Orders-->
                        <div class="d-flex flex-column gap-5 gap-lg-5">
                            <div class="d-flex flex-column flex-xl-row gap-5 gap-lg-5">
                                <!--begin::Payment address-->
                                <div class="card card-flush flex-row-fluid overflow-hidden pt-3" style="text-align: left">
                                    <!--begin::Card header-->
                                    <div class="card-header">
                                        <div class="card-title">
                                            <h2>Supplier Details </h2>
                                        </div>
                                    </div>
                                    <!--end::Card header-->
                                    <!--begin::Card body-->
                                    <div class="card-body py-0 pt-2">Supplier ID : {{$stocks[0]->supplier_finance_account->id}}
                                        <br>Supplier Name : {{$stocks[0]->supplier_finance_account->account_name}}
                                        <br>Address : {{$stocks[0]->supplier_finance_account->account_address}}
                                        <br>Mobile No : {{$stocks[0]->supplier_finance_account->account_mobile}}
                                    </div>
                                    <!--end::Card body-->
                                </div>
                                <!--end::Payment address-->
                                <!--begin::Shipping address-->
                                <div class="card card-flush py-0 flex-row-fluid overflow-hidden"
                                     style="text-align: right">
                                    <!--begin::Card body-->
                                    <div class="card-body pt-5">
                                        <div class="form-group col-2">
                                        </div>
                                        <a href="{{ route('purchase_search_pdf',$stocks[0]->invoice_no) }}" class="btn btn-sm btn-primary px-5 my-3" target="_blank">{{ __('PDF Download') }}</a>
                                        <h2 class="mb-0"> Invoice No (#{{$stocks[0]->invoice_no}}) </h2>
                                        <br>Invoice Date : {{$stocks[0]->stock_date}}
                                        <!-- <br>Prepared By : admin  -->
                                    </div>
                                    <!--end::Card body-->
                                </div>
                                <!--end::Shipping address-->
                            </div>
                            <!--begin::Product List-->
                            <div class="card card-flush py-4 flex-row-fluid overflow-hidden">
                                <!--begin::Card header-->
                                <!-- <div class="card-header pb-4">
                                    <div class="card-title">
                                        <h2> Invoice #{{$stocks[0]->invoice_no}}</h2>
                                    </div>
                                </div> -->
                                <!--end::Card header-->
                                <!--begin::Card body-->
                                <div class="card-body py-0">
                                    <div class="table-responsive">
                                        <!--begin::Table-->
                                        <table class="table table-striped table-bordered align-middle table-row-dashed fs-6 gy-5 mb-0">
                                            <!--begin::Table head-->
                                            <thead>
                                                <tr class="text-start text-uppercase text-center">
                                                    <th class="min-w-50px">SL</th>
                                                    <th class="min-w-175px">Product ID</th>
                                                    <th class="min-w-175px">Product Name</th>
                                                    <th class="min-w-70px">Unit</th>
                                                    <th class="min-w-70px">Quantity</th>
                                                    <th class="min-w-100px">Unit Price (BDT)</th>
                                                    <th class="min-w-100px">Discount (BDT)</th>
                                                    <th class="min-w-100px">Total (BDT)</th>
                                                </tr>
                                            </thead>
                                            <!--end::Table head-->
                                            <!--begin::Table body-->
                                            <tbody class="fw-semibold text-gray-600">
                                                <!--begin::Products-->
                                            <span class="d-none">{{$initialTotal = 0, $initialDiscount = 0}}</span>
                                                @foreach($stocks as $stock)
                                            <tr class="text-center">
                                                <td class="">{{$loop->iteration}}</td>
                                                <td class="">{{$stock->product_id}}</td>
                                                <td class="text-start">{{$stock->product->product_name}}</td>
                                                <td class="text-center">{{$stock->product->unit->unit_name}}</td>
                                                <td class="">{{$stock->stock_in_quantity}}</td>
                                                <td class="text-end">
                                                        {{number_format($stock->stock_in_unit_price, 2)}}</td>
                                                <td class="text-end">
                                                        {{ number_format($stock->stock_in_discount,2) }}</td>
                                                    {{-- <td class="text-end">{{$productTotal =(($stock->stock_in_quantity * $stock->stock_in_unit_price) - $stock->stock_in_discount)}}
                                                    </td> --}}
                                                <td class="text-end">
                                                        {{ number_format($productTotal =($stock->stock_in_quantity * $stock->stock_in_unit_price),2) }}
                                                </td>
                                            <span
                                                class="d-none">{{ $initialTotal = $initialTotal + $productTotal, $initialDiscount = $initialDiscount + $stock->stock_in_discount }}</span>
                                            </tr>
                                                @endforeach
                                            <!--end::Products-->
                                            <!--begin::Subtotal-->
                                            <tr class="">
                                                <td colspan="7" class="fs-5 text-dark text-end ">Subtotal</td>
                                                <td colspan="7" class="fs-5 text-dark text-end">
                                                        {{ number_format($initialTotal,2) }}</td>
                                            </tr>
                                            <!--end::Subtotal-->
                                            <!--begin::VAT-->
                                            <tr>
                                                <td colspan="7" class="fs-5 text-dark text-end">Discount</td>
                                                <td colspan="7" class="fs-5 text-dark text-end">
                                                        {{ number_format($initialDiscount,2) }}</td>
                                            </tr>
                                            <!--end::VAT-->
                                            <!--begin::Grand total-->
                                            <tr>
                                                <td colspan="7" class="fs-4 text-dark text-end">Net Amount</td>
                                                <td colspan="7" class="text-dark fs-4 fw-bolder text-end">
                                                        {{ number_format($initialTotal - $initialDiscount, 2)}}</td>
                                            </tr>
                                            <tr>
                                                <td colspan="7" class="fs-4 text-dark text-end">Paid Amount</td>
                                                <td colspan="7" class="text-dark fs-4 fw-bolder text-end">
                                                        {{ number_format($supplierPayment,2) }}</td>
                                            </tr>
                                            <tr>
                                                <td colspan="7" class="fs-4 text-dark text-end">Due Amount</td>
                                                <td colspan="7" class="text-dark fs-4 fw-bolder text-end">
                                                        {{ number_format(($initialTotal - $initialDiscount) - $supplierPayment, 2) }}
                                                </td>
                                            </tr>
                                            <!--end::Grand total-->
                                            </tbody>
                                            <!--end::Table head-->
                                        </table>
                                        <!--end::Table-->
                                    </div>
                                </div>
                                <!--end::Card body-->
                            </div>
                            <!--end::Product List-->
                        </div>
                        <!--end::Orders-->
                    </div>
                    <!--end::Tab pane-->
                </div>
                <!--end::Tab content-->
            </div>
            <!--end::Order details page-->
        </div>
        <!--end::Content container-->
    </div>
    @endisset
</x-default-layout>

<script type="text/javascript">

$("#searchBtn").prop('disabled', true);
    $('#invoice_list').on('change', function() {
        $("#searchBtn").prop('disabled', false);
});

$('#start_date').on('change', function() {
    $("#invoice_list").empty();

    var startDate = $(this).val();
    var endDate = $('#end_date').val();

    const start_date = new Date(startDate);
    const end_date = new Date(endDate);

    // Check if the start date is after the end date
    if (start_date > end_date) {
        alert('The start date cannot be after the end date.');
        $('#start_date').val(endDate);
        startDate = $('#start_date').val();
    }

    // console.log(startDate);
    // console.log(endDate);
    var url = '{{route("purchase_date_search",["startDate" => ":startDate", "endDate" => ":endDate"])}}';
    // console.log(url);
    url = url.replace(':startDate', startDate);
    url = url.replace(':endDate', endDate);

    if (endDate) {
        $.ajax({
            url: url,
            type: "GET",
            data: {
                "_token": "{{ csrf_token() }}"
            },
            dataType: "json",
            success: function(data) {
                $('#invoice_list').append(
                    `<option selected disabled>Select Invoice</option>`
                );
                console.log(data);
                if (data) {
                    $('#jsdataerror').text('');
                    $.each(data, function(key, value) {
                        $('#invoice_list').append(
                            `<option value="` + value[0].invoice_no + `">` + value[0].invoice_no +
                            `</option>`
                        );
                    });
                } else {
                    $('#jsdataerror').text('Not Found');
                }
            }
        });
    } else {
        $('#jsdataerror').text('Not Found');
    }
});

$('#end_date').on('change', function() {
    $("#invoice_list").empty();

    var endDate = $(this).val();
    var startDate = $('#start_date').val();

    const start_date = new Date(startDate);
    const end_date = new Date(endDate);

    // Check if the start date is after the end date
    if (start_date > end_date) {
        alert('The end date cannot be before the start date.');
        $('#end_date').val(startDate);
        endDate = $('#start_date').val();
    }
    
    // console.log(startDate);
    // console.log(endDate);
    var url = '{{route("purchase_date_search",["startDate" => ":startDate", "endDate" => ":endDate"])}}';
    // console.log(url);
    url = url.replace(':startDate', startDate);
    url = url.replace(':endDate', endDate);

    if (endDate) {
        $.ajax({
            url: url,
            type: "GET",
            data: {
                "_token": "{{ csrf_token() }}"
            },
            dataType: "json",
            success: function(data) {
                $('#invoice_list').append(
                    `<option selected disabled>Select Invoice</option>`
                );
                console.log(data);
                if (data) {
                    $('#jsdataerror').text('');
                    $.each(data, function(key, value) {
                        $('#invoice_list').append(
                            `<option value="` + value[0].invoice_no + `">` + value[0].invoice_no +
                            `</option>`
                        );
                    });
                } else {
                    $('#jsdataerror').text('Not Found');
                }
            }
        });
    } else {
        $('#jsdataerror').text('Not Found');
    }
});
</script>
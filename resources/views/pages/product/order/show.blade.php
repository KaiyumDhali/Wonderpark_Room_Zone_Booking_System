<x-default-layout>
    <style>
        .card .card-header {
            min-height: 40px;
        }

        .table> :not(caption)>*>* {
            padding: 0.3rem !important;
        }
    </style>

    @if ($orders)
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <!--begin::Content container-->
            <!-- <div id="kt_app_content_container" class="app-container container-xxl"> -->
            <div id="kt_app_content_container" class="app-container">
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
                                    <div class="card card-flush flex-row-fluid overflow-hidden pb-3"
                                        style="text-align: left">
                                        <div class="card-header pt-5">
                                            <div class="card-title">
                                                <h3>Customer Details </h3>
                                            </div>
                                        </div>
                                        <!--begin::Card body-->
                                        <div class="card-body py-0" style="font-size: 14px; font-weight: 500;">Customer ID : {{ $orders->customer->id }}
                                            <br>Customer Name : {{ $orders->customer->account_name }}
                                            <br>Address : {{ $orders->customer->account_address }}
                                            <br>Mobile No : {{ $orders->customer->account_mobile }}
                                        </div>
                                        <!--end::Card body-->
                                    </div>
                                    <!--end::Payment address-->
                                    <!--begin::Shipping address-->
                                    <div class="card card-flush flex-row-fluid overflow-hidden"
                                        style="text-align: right">
                                        <div class="card-body pt-5" style="font-size: 14px; font-weight: 500;">
                                            <div class="card-title">
                                                <a href="{{ route('orderDetailsPdf',$orders->id) }}" class="btn btn-sm btn-primary px-5 my-3" target="_blank">{{ __('PDF Download') }}</a>
                                            </div>
                                            <h3>Order No (#{{ $orders->order_no }}) </h3>
                                            Order Date : {{ $orders->order_date }}
                                            <br>Delivery Date : {{ $orders->delivery_date }}
                                            <br>Prepared By : {{ $orders->done_by }}
                                            <br>Order Status : {{ $orders->status == 0 ? 'Pending' : 'Approved' }}
                                            <!-- <br>Prepared By : admin -->
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
                                        <h2> Invoice #{{-- $stocks[0]->invoice_no --}}</h2>
                                    </div>
                                </div> -->
                                    <!--end::Card header-->
                                    <!--begin::Card body-->
                                    <div class="card-body py-0">
                                        <div class="table-responsive">
                                            <!--begin::Table-->
                                            <table
                                                class="table table-striped table-bordered align-middle table-row-dashed fs-6 gy-5 mb-0">
                                                <!--begin::Table head-->
                                                <thead>
                                                    <tr class="text-start text-uppercase text-center">
                                                        <th class="min-w-50px">SL</th>
                                                        <th class="min-w-175px">Product ID</th>
                                                        <th class="min-w-175px">Product Name</th>
                                                        <th class="min-w-175px">Unit</th>
                                                        <th class="min-w-70px">Quantity</th>
                                                        <th class="min-w-100px">Unit Price</th>
                                                        <th class="min-w-100px">Discount</th>
                                                        <th class="min-w-100px ">Total</th>
                                                    </tr>
                                                </thead>
                                                <!--end::Table head-->

                                                <!--begin::Table body-->
                                                <tbody class="fw-semibold text-gray-600">
                                                    <!--begin::Products-->
                                                    <span class="d-none">{{ $initialTotal = 0, $initialDiscount = 0 }}</span>
                                                    @foreach ($orders->orderdetail as $order)
                                                        <tr class="text-center">
                                                            <td class="">{{ $loop->iteration }}</td>
                                                            <td class="">{{ $order->product_id }}</td>
                                                            <td class="text-start">{{ $order->product->product_name }}</td>
                                                            <td class="text-start">{{ $order->product->unit->unit_name }}</td>
                                                            <td class="">{{ $order->stock_out_quantity }}</td>
                                                            <td class="text-end">
                                                                {{ number_format($order->stock_out_unit_price, 2) }}
                                                            </td>
                                                            <td class="text-end">
                                                                {{ number_format($order->stock_out_discount, 2) }}</td>
                                                            <td class="text-end">
                                                                {{ number_format($productTotal = $order->stock_out_quantity * $order->stock_out_unit_price, 2) }}
                                                            </td>
                                                            <span
                                                                class="d-none">{{ $initialTotal = $initialTotal + $productTotal, $initialDiscount = $initialDiscount + $order->stock_out_discount }}</span>
                                                        </tr>
                                                    @endforeach
                                                    <!--end::Products-->
                                                    <!--begin::Subtotal-->
                                                    <tr class="">
                                                        <td colspan="6" class="fs-5 text-dark text-end ">Subtotal
                                                        </td>
                                                        <td colspan="6" class="fs-5 text-dark text-end">
                                                            {{ number_format($initialTotal, 2) }}
                                                        </td>
                                                    </tr>
                                                    <!--end::Subtotal-->
                                                    <!--begin::VAT-->
                                                    <tr>
                                                        <td colspan="6" class="fs-5 text-dark text-end">Discount</td>
                                                        <td colspan="6" class="fs-5 text-dark text-end">
                                                            {{ number_format($initialDiscount, 2) }}
                                                        </td>
                                                    </tr>
                                                    <!--end::VAT-->
                                                    <!--begin::Grand total-->
                                                    <tr>
                                                        <td colspan="6" class="fs-4 text-dark text-end">Net Amount
                                                        </td>
                                                        <td colspan="6" class="text-dark fs-4 fw-bolder text-end">
                                                            {{ number_format($initialTotal - $initialDiscount, 2) }}
                                                        </td>
                                                    </tr>
                                                    {{-- @if ($orders->file !== null)
                                                    <tr>
                                                        <td colspan="2" class="fs-4 text-dark text-end">
                                                        </td>
                                                        <td colspan="" class="fs-4 text-dark text-end">
                                                            <img src="{{ Storage::url($orders->file) }}" height="200" width="" alt="Attach File" />
                                                        </td>
                                                        <td colspan="2" class="fs-4 text-dark text-end">
                                                        </td>
                                                    </tr>
                                                    @endif --}}
                                                    <!--end::Grand total-->
                                                </tbody>
                                                <!--end::Table head-->


                                            </table>
                                            <!--end::Table-->
                                        </div>
                                        <p class="pt-5"><b>In Words: {{ numberToWord($initialTotal - $initialDiscount) }}</b> </p>
                                        <p><b>Remarks: </b>{{$orders->remarks}} </p>
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

<!--<script type="text/javascript">
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
        var url = '{{-- route("sales_date_search",["startDate" => ":startDate", "endDate" => ":endDate"]) --}}';
        // console.log(url);
        url = url.replace(':startDate', startDate);
        url = url.replace(':endDate', endDate);

        if (endDate) {
            $.ajax({
                url: url,
                type: "GET",
                data: {
                    "_token": "{{-- csrf_token() --}}"
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
                                `<option value="` + value[0].invoice_no + `">` + value[
                                    0].invoice_no +
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
        var url = '{{-- route("sales_date_search",["startDate" => ":startDate", "endDate" => ":endDate"]) --}}';
        // console.log(url);
        url = url.replace(':startDate', startDate);
        url = url.replace(':endDate', endDate);

        if (endDate) {
            $.ajax({
                url: url,
                type: "GET",
                data: {
                    "_token": "{{-- csrf_token() --}}"
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
                                `<option value="` + value[0].invoice_no + `">` + value[
                                    0].invoice_no +
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
</script>-->

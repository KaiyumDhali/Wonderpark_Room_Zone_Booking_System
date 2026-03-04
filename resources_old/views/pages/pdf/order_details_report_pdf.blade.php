<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Sales Order Details</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
        integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">

    <style>
        .double-underline {
            border-bottom: 4px double;
        }
    </style>
</head>

<body style="font-size:12px">
    @include('pages.pdf.partials.header_pdf')

    <h5 class="pb-0 mb-0 pt-0 pb-3 text-center">Sales Order</h5>
    <h6 class="my-0">Customer Details</h6>
    <table style="font-size: 14px; font-style:bold;">
        <thead>
            <tr>
                <td>Customer ID</td>
                <td>:</td>
                <td>{{$orders->customer->id}}</td>
            </tr>
            <tr>
                <td scope="col">Customer Name</td>
                <td>:</td>
                <td>{{$orders->customer->account_name}}</td>
            </tr>
            <tr>
                <td scope="col">Address</td>
                <td>:</td>
                <td>{{$orders->customer->account_address}}</td>
            </tr>
            <tr>
                <td scope="col">Mobile No</td>
                <td>:</td>
                <td>{{$orders->customer->account_mobile}}</td>
            </tr>
        </thead>
    </table>

    <table style="position: absolute; font-size: 14px; font-style:bold; margin-top: -90px;" align="right">
        <thead style="text-align: right;">
            <tr>
                <td>
                    Order No : ({{ $orders->order_no }})
                </td>
            </tr>
            <tr>
                <td scope="col" style="text-align: right; font-weight: bold;">Order Date : {{ $orders->order_date }}</td>
            </tr>
            <tr>
                <td scope="col" style="text-align: right; font-weight: bold;">Delivery Date : {{ $orders->delivery_date }}</td>
            </tr>
            <tr>
                <td scope="col" style="text-align: right; font-weight: bold;">Prepared By : {{ $orders->done_by }}</td>
            </tr>
        </thead>
    </table>

    <table class="table table-bordered table-striped table-sm table-hover pt-3">
        <!--begin::Table head-->
        <thead>
            <tr class="text-start text-uppercase text-center text-light bg-secondary ">
                <th class="min-w-50px">SL</th>
                <th class="min-w-175px">Product ID</th>
                <th class="min-w-175px">Product Name</th>
                <th class="min-w-70px">Unit</th>
                <th class="min-w-70px">Quantity</th>
                <th class="min-w-100px">Unit Price</th>
                <th class="min-w-100px">Discount</th>
                <th class="min-w-100px ">Total Amount</th>
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
                    <td class="text-left">{{ $order->product->product_name }}</td>
                    <td class="text-center">{{ $order->product->unit->unit_name }}</td>
                    <td class="">{{ $order->stock_out_quantity }}</td>
                    <td class="text-right">
                        {{ formatCurrency($order->stock_out_unit_price) }}</td>
                    <td class="text-right">
                        {{ formatCurrency($order->stock_out_discount) }}</td>
                    <td class="text-right">
                        {{ formatCurrency($productTotal = $order->stock_out_quantity * $order->stock_out_unit_price) }}
                    </td>
                    <span
                        class="d-none text-right">{{ $initialTotal = $initialTotal + $productTotal, $initialDiscount = $initialDiscount + $order->stock_out_discount }}</span>
                </tr>
            @endforeach
            <!--end::Products-->
            <!--begin::Subtotal-->
            <tr class="" style="font-size:14px">
                <td colspan="7" class="" style="text-align: right; font-weight: bold;">Subtotal</td>
                <td colspan="7" class="text-right" style="font-weight: bold;">
                    {{ formatCurrency($initialTotal) }}
                </td>
            </tr>
            <!--end::Subtotal-->
            <!--begin::VAT-->
            <tr style="font-size:14px">
                <td colspan="7" class="" style="text-align: right; font-weight: bold;">Discount</td>
                <td colspan="7" class="text-right" style="font-weight: bold;">
                    {{ formatCurrency($initialDiscount) }}
                </td>
            </tr>
            <!--end::VAT-->
            <!--begin::Grand total-->
            <tr style="font-size:14px">
                <td colspan="7" class="" style="text-align: right; font-weight: bold;">Net Amount</td>
                <td colspan="7" class="text-right" style="font-weight: bold;">
                    {{ formatCurrency($initialTotal - $initialDiscount) }}</td>
            </tr>
            <!--end::Grand total-->
        </tbody>
        <!--end::Table head-->
    </table>

    <p><b>In Words: {{ numberToWord($initialTotal - $initialDiscount) }}</b> </p>
    <p><b>Remarks: </b>{{$orders->remarks}} </p>
    <br>
    {{-- <p> <b>for {{ $data['company_name'] }}</b> </p> --}}
    <br><br>
    <div style="width: 130px;">
        <p style="border-top: 1px solid #000000; font-weight: bold;">Authorised Signatory</p>
    </div>

    @include('pages.pdf.partials.footer_pdf')

    
</body>

</html>

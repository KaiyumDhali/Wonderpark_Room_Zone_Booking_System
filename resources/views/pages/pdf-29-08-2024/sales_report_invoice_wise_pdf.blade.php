@php
    $imagePath = public_path(Storage::url($data['company_logo_one']));
    $imageDataUri = 'data:' . mime_content_type($imagePath) . ';base64,' . base64_encode(file_get_contents($imagePath));
@endphp


<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>{{ $data['company_name'] }}</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
        integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">

    <style>
        .double-underline {
            border-bottom: 4px double;
        }
    </style>
</head>

<body style="font-size:12px">
    {{-- include header --}}
    @include('pages.pdf.partials.header_pdf')

    <div class="text-center">
        <h5 class="pb-0 mb-0 pt-0">Sales Invoice</h5>
    </div>

    <h6 class="my-0">Customer Details</h6>
    <table style="font-size: 14px; font-style:bold;">
        <thead>
            <tr>
                <td>Customer ID</td>
                <td>:</td>
                <td>{{$stocks[0]->customer_finance_account->id}}</td>
            </tr>
            <tr>
                <td scope="col">Customer Name</td>
                <td>:</td>
                <td>{{$stocks[0]->customer_finance_account->account_name}}</td>
            </tr>
            <tr>
                <td scope="col">Address</td>
                <td>:</td>
                <td>{{$stocks[0]->customer_finance_account->account_address}}</td>
            </tr>
            <tr>
                <td scope="col">Mobile No</td>
                <td>:</td>
                <td>{{$stocks[0]->customer_finance_account->account_mobile}}</td>
            </tr>
        </thead>
    </table>

    <table style="position: absolute; font-size: 14px; font-style:bold; margin-top: -70px;" align="right">
        <thead style="text-align: right;">
            <tr>
                <td>
                    Invoice No : ({{ $stocks[0]->invoice_no }})
                </td>
                {{-- <td>
                    ({{ $stocks[0]->invoice_no }})
                </td> --}}
            </tr>
            <tr>
                <td>
                    Delivery Challan : ({{ $stocks[0]->delivery_challan_no }})
                </td>
                {{-- <td>
                    ({{ $stocks[0]->invoice_no }})
                </td> --}}
            </tr>
            <tr>
                <td scope="col" style="text-align: right; font-weight: bold;">Invoice Date : {{ $stocks[0]->stock_date }}</td>
                {{-- <td>{{ $stocks[0]->stock_date }}</td> --}}
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
            @foreach ($stocks as $stock)
                <tr class="text-center">
                    <td class="">{{ $loop->iteration }}</td>
                    <td class="">{{ $stock->product_id }}</td>
                    <td class="text-left">{{ $stock->product->product_name }}</td>
                    <td class="text-center">{{ $stock->product->unit->unit_name }}</td>
                    <td class="">{{ $stock->stock_out_quantity }}</td>
                    <td class="text-right">
                        {{ formatCurrency($stock->stock_out_unit_price) }}</td>
                    <td class="text-right">
                        {{ formatCurrency($stock->stock_out_discount) }}</td>
                    {{-- <td class="text-end">{{$productTotal =(($stock->stock_in_quantity * $stock->stock_in_unit_price) - $stock->stock_in_discount)}}
                                                    </td> --}}
                    <td class="text-right">
                        {{ formatCurrency($productTotal = $stock->stock_out_quantity * $stock->stock_out_unit_price) }}
                    </td>
                    <span
                        class="d-none text-right">{{ $initialTotal = $initialTotal + $productTotal, $initialDiscount = $initialDiscount + $stock->stock_out_discount }}</span>
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
            <tr style="font-size:14px">
                <td colspan="7" class="" style="text-align: right; font-weight: bold;">Paid Amount</td>
                <td colspan="7" class="text-right" style="font-weight: bold;">
                    {{ formatCurrency($customerPayment) }} </td>
            </tr>
            <tr style="font-size:14px">
                <td colspan="7" class="" style="text-align: right; font-weight: bold;">Due Amount</td>
                <td colspan="7" class="text-right" style="font-weight: bold;">
                    <span
                        class="double-underline">{{ formatCurrency($initialTotal - $initialDiscount - $customerPayment) }}
                        </spam>
                </td>
            </tr>
            <!--end::Grand total-->
        </tbody>
        <!--end::Table head-->
    </table>

    <p><b>In Words: {{ numberToWord($initialTotal - $initialDiscount) }}</b> </p>
    <p><b>Remarks: </b>{{$stocks[0]->remarks}} </p>

    @if ($customerPayment)
        <p><b>Narration: </b>{{ $paymentNarration }} </p>
    @endif

    <br>
    <p> <b>for {{ $data['company_name'] }}</b> </p>
    <br><br>
    <div style="width: 130px;">
        <p style="border-top: 1px solid #000000; font-weight: bold;">Authorised Signatory</p>
    </div>

    {{-- include footer --}}
    @include('pages.pdf.partials.footer_pdf')
</body>

</html>

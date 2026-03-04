<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Purchase Invoice</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
        integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">

    <style>
        .double-underline {
            border-bottom: 4px double;
        }

        .pagenum:before {
            content: counter(page);
        }
    </style>
</head>

<body style="font-size:12px">
    {{-- include header --}}
    @include('pages.pdf.partials.header_pdf')

    <div class="text-center">
        <h5 class="pb-0 mb-0 pt-0 pdf_title">Purchase Return Invoice</h5>
    </div>

    <h6 class="my-0">Supplier Details</h6>
    <table style="font-size: 14px; font-style:bold;">
        <thead class="">
            <tr class="">
                <td>Supplier ID</td>
                <td>:</td>
                <td>{{$stocks[0]->supplier_finance_account->id}}</td>
            </tr>
            <tr class="">
                <td scope="col">Supplier Name</td>
                <td>:</td>
                <td>{{$stocks[0]->supplier_finance_account->account_name}}</td>
            </tr>
            <tr class="">
                <td scope="col">Address</td>
                <td>:</td>
                <td>{{$stocks[0]->supplier_finance_account->account_address}}</td>
            </tr>
            <tr class="">
                <td scope="col">Mobile No</td>
                <td>:</td>
                <td>{{$stocks[0]->supplier_finance_account->account_mobile}}</td>
            </tr>
        </thead>
    </table>
    <table style="position: absolute; font-size: 14px; font-style:bold; margin-top: -50px;" align="right">
        <thead style="text-align: right;">
            <tr>
                <td>
                    Return Invoice No : ({{ $stocks[0]->invoice_no }})
                </td>
            </tr>
            <tr>
                <td scope="col" style="text-align: right; font-weight: bold;">Invoice Date : {{ $stocks[0]->stock_date }}</td>
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
                <th class="min-w-100px">Total (BDT)</th>
            </tr>
        </thead>
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
                    <td class="text-right">
                        {{ formatCurrency($productTotal = $stock->stock_out_total_amount) }}
                    </td>
                    <span
                        class="d-none">{{ $initialTotal = $initialTotal + $productTotal, $initialDiscount = $initialDiscount + $stock->stock_out_discount }}</span>
                </tr>
            @endforeach
            <!--end::Products-->
            <!--begin::Subtotal-->
            <tr class="">
                <td colspan="7" class="" style="text-align: right; font-weight: bold;">Return Amount</td>
                <td colspan="7" class="text-right" style="font-weight: bold; text-align: right;">
                    {{ formatCurrency($initialTotal) }}</td>
            </tr>
            <!--end::Subtotal-->
            <!--begin::VAT-->
            {{-- <tr>
                <td colspan="7" class="" style="text-align: right; font-weight: bold;">INV Discount</td>
                <td colspan="7" class="text-right" style="font-weight: bold;">0.00</td>
            </tr>
            <!--end::VAT-->
            <!--begin::Grand total-->
            <tr>
                <td colspan="7" class="" style="text-align: right; font-weight: bold;">Net Amount</td>
                <td colspan="7" class="text-right" style="font-weight: bold;">
                    {{ formatCurrency($initialTotal) }}</td>
            </tr>
            <tr>
                <td colspan="7" class="" style="text-align: right; font-weight: bold;">Paid Amount</td>
                <td colspan="7" class="text-right" style="font-weight: bold;">
                    {{ formatCurrency($supplierPayment) }}</td>
            </tr>
            <tr>
                <td colspan="7" class="" style="text-align: right; font-weight: bold;">Due Amount</td>
                <td colspan="7" class="text-bold text-right" style="font-weight: bold;">
                    <span
                        class="double-underline">{{ formatCurrency($initialTotal - $supplierPayment) }}</span>
                </td>
            </tr> --}}
            <!--end::Grand total-->
        </tbody>

    </table>

    <p><b>In Word:</b> {{ numberToWord($initialTotal) }}</p>
    @if ($stocks[0]->remarks)
        <p><b>Remarks: </b>{{$stocks[0]->remarks}} </p>
    @endif
    {{-- @if ($paymentNarration)
        <p><b>Narration:</b> {{ $paymentNarration }}</p>
    @else
        @if ($stocks[0]->remarks)
            <p><b>Remarks: </b>{{$stocks[0]->remarks}} </p>
        @endif
    @endif --}}
   

    {{-- include footer --}}
    @include('pages.pdf.partials.footer_pdf')

</body>

</html>

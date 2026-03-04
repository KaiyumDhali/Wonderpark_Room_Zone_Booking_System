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

   .pagenum:before {
        content: counter(page);
    }
        </style>
    </head>

    <body style="font-size:12px">
        <table class="table table-borderless table-sm m-0">
            <tbody>
                <tr class="text-center">
                    <td>
                        <h4>{{ $data['company_name'] }}</h4>
                        <h6 class="">Product Purchase Report</h6>
                    </td>
                </tr>
                <tr>
                {{-- <h6 class="">Employee: {{ $data['employeeInfo']->employee_name }}</h6> --}}
                {{-- <h6 class="">Date: {{ $data['start_date'] }} to {{ $data['end_date'] }}</h6> --}}
                </tr>
            </tbody>
        </table>


        <h6 class="">Supplier Details</h6>
        <table class="">
            <thead class="">
                <tr class="">
                    <td>Supplier ID :</td>
                    <td>{{ $stocks[0]->supplier->id }}</td>
                </tr>
                <tr class="">
                    <td scope="col">Supplier Name :</td>
                    <td>{{ $stocks[0]->supplier->supplier_name }}</td>
                </tr>
                <tr class="">
                    <td scope="col">Address :</td>
                    <td>{{ $stocks[0]->supplier->supplier_address }}</td>
                </tr>
                <tr class="">
                    <td scope="col">Mobile No :</td>
                    <td>{{ $stocks[0]->supplier->supplier_mobile }}</td>
                </tr>
            </thead>
        </table>

        <table class="" style="position: absolute; margin-top: -50px;" align="right">
            <thead class="" style="text-align: right;">
                <tr class="">
                    <td><h6>Invoice No :</h6></td>
                    <td><h6>(#{{ $stocks[0]->invoice_no }})</h6></td>
                </tr>
                <tr class="">
                    <td scope="col" style="font-weight: bold;">Invoice Date :</td>
                    <td>{{ $stocks[0]->stock_date }}</td>
                </tr>

            </thead>
        </table>

        <table class="table table-bordered table-striped table-sm table-hover pt-3">
            <!--begin::Table head-->
            <thead >
                <tr class="text-start text-uppercase text-center text-light bg-success ">
                    <th class="min-w-50px">SL</th>
                    <th class="min-w-175px">Product ID</th>
                    <th class="min-w-175px">Product Name</th>
                    <th class="min-w-70px">Unit</th>
                    <th class="min-w-70px">Quantity</th>
                    <th class="min-w-100px">Unit Price</th>
                    <th class="min-w-100px">Discount</th>
                    <th class="min-w-100px">Total</th>
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
                <td class="text-center">{{$stock->product->unit->unit_name}}</td>
                <td class="">{{ $stock->stock_in_quantity }}</td>
                <td class="text-right">
                        {{ number_format($stock->stock_in_unit_price, 2) }}</td>
                <td class="text-right">
                        {{ number_format($stock->stock_in_discount, 2) }}</td>
                    {{-- <td class="text-end">{{$productTotal =(($stock->stock_in_quantity * $stock->stock_in_unit_price) - $stock->stock_in_discount)}}
                                                    </td> --}}
                <td class="text-right">
                        {{ number_format($productTotal = $stock->stock_in_quantity * $stock->stock_in_unit_price, 2) }}
                </td>
            <span
                class="d-none">{{ $initialTotal = $initialTotal + $productTotal, $initialDiscount = $initialDiscount + $stock->stock_in_discount }}</span>
        </tr>
            @endforeach
        <!--end::Products-->
        <!--begin::Subtotal-->
        <tr class="">
            <td colspan="7" class="" style="text-align: right; font-weight: bold;">Subtotal</td>
            <td colspan="7" class="text-right" style="font-weight: bold; text-align: right;">
                    {{ number_format($initialTotal, 2) }}</td>
        </tr>
        <!--end::Subtotal-->
        <!--begin::VAT-->
        <tr>
            <td colspan="7" class="" style="text-align: right; font-weight: bold;">Discount</td>
            <td colspan="7" class="text-right" style="font-weight: bold;">
                    {{ number_format($initialDiscount, 2) }}</td>
        </tr>
        <!--end::VAT-->
        <!--begin::Grand total-->
        <tr>
            <td colspan="7" class="" style="text-align: right; font-weight: bold;">Net Amount</td>
            <td colspan="7" class="text-right" style="font-weight: bold;">
                    {{ number_format($initialTotal - $initialDiscount, 2) }}</td>
        </tr>
        <tr>
            <td colspan="7" class="" style="text-align: right; font-weight: bold;" >Paid Amount</td>
            <td colspan="7" class="text-right" style="font-weight: bold;">
                    {{ number_format($supplierPayment, 2) }}</td>
        </tr>
        <tr>
            <td colspan="7" class="" style="text-align: right; font-weight: bold;">Due Amount</td>
            <td colspan="7" class="text-bold text-right" style="font-weight: bold;">
                <span class="double-underline">{{ number_format($initialTotal - $initialDiscount - $supplierPayment, 2) }}</span>
            </td>
        </tr>
        <!--end::Grand total-->
    </tbody>

</table>

<footer class="footer fixed-bottom text-center">
    
    <table class="table">
        <tr>
            <td style="border: none; border-bottom: none; border-top: none;">
                Design and Developed By: NRB Telecom Ltd.
            </td>
            <td style="border: none; border-bottom: none; border-top: none;">
                        @php
                            echo date("l, F j, Y h:i A");
                        @endphp
            </td>
            <td style="border: none; border-bottom: none; border-top: none;">
                Page  <span class="pagenum"></span>
            </td>
        </tr>
    </table>
</footer>


</body>

</html>

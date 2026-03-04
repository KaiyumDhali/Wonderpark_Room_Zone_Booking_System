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

    <script>
        window.onload = function() {
            window.print();
            // Optionally close the tab after printing
            setTimeout(() => window.close(), 2000);
        };
    </script>

</head>

<body style="font-size:12px" >
    {{-- include header --}}
    @include('pages.pdf.partials.header_pdf')

    <div class="text-center my-1">

        @if ($productServiceDetail)
            <h5 class=" pdf_title">Service Invoice</h5>
        @else
            <h5 class=" pdf_title">Consume Invoice</h5>
        @endif
    </div>

    <table style="width: 100%"  class="">
        <tr>
            <td style="width: 50%">
                <h6 class="my-0">Employee Details</h6>
                <table style="font-size: 14px; font-style:bold;">
                    <thead>
                        <tr>
                            <td>Employee ID</td>
                            <td>:</td>
                            <td>{{ $stocks[0]->employee_finance_account->id }}</td>
                        </tr>
                        <tr>
                            <td scope="col">Employee Name</td>
                            <td>:</td>
                            <td>{{ $stocks[0]->employee_finance_account->employee_name }}</td>
                        </tr>
                        <tr>
                            <td scope="col">Address</td>
                            <td>:</td>
                            <td>{{ $stocks[0]->employee_finance_accountpresent_address }}</td>
                        </tr>
                        <tr>
                            <td scope="col">Mobile No</td>
                            <td>:</td>
                            <td>{{ $stocks[0]->employee_finance_account->mobile }}</td>
                        </tr>
                    </thead>
                </table>
            </td>
            <td style="width: 50%">
                <table style="font-size: 14px; font-style:bold;" align="right">
                    <thead style="text-align: right;">
                        <tr>
                            <td>
                                Issue No : ({{ $stocks[0]->invoice_no }})
                            </td>
                        </tr>
                        <tr class="d-none">
                            <td>
                                Delivery Challan : ({{ $stocks[0]->delivery_challan_no }})
                            </td>
                        </tr>
                        <tr>
                            <td scope="col" style="text-align: right; font-weight: bold;">
                                        Issue Date : {{ \Carbon\Carbon::parse($stocks[0]->stock_date)->format('Y-m-d') }}
                                    </td>

                        </tr>
                        @if ($productServiceDetail)
                            <tr>
                                <td scope="col" style="text-align: right; font-weight: bold;">
                                    Service Product : {{ $productServiceDetail->product_name }}
                                </td>
                            </tr>
                            <tr>
                                <td scope="col" style="text-align: right; font-weight: bold;">
                                    Service Location : {{ $productServiceDetail->service_location }}
                                </td>
                            </tr>
                        @endif

                    </thead>
                </table>
            </td>
        </tr>
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
                <th class="min-w-100px d-none">Unit Price</th>
                <th class="min-w-100px d-none">Discount</th>
                <th class="min-w-100px d-none">Total Amount</th>
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
                    <td class="text-right d-none">
                        {{ formatCurrency($stock->stock_out_unit_price) }}</td>
                    <td class="text-right d-none">
                        {{ formatCurrency($stock->stock_out_discount) }}</td>
                    <td class="text-right d-none">
                        {{ formatCurrency($productTotal = $stock->stock_out_total_amount) }}
                    </td>
                    <span
                        class="d-none text-right">{{ $initialTotal = $initialTotal + $productTotal, $initialDiscount = $initialDiscount + $stock->stock_out_discount }}</span>
                </tr>
            @endforeach
            <!--end::Products-->
            <!--begin::Subtotal-->
            <tr class="d-none" style="font-size:14px">
                <td colspan="7" class="" style="text-align: right; font-weight: bold;">Subtotal</td>
                <td colspan="7" class="text-right d-none" style="font-weight: bold;">
                    {{ formatCurrency($initialTotal) }}
                </td>
            </tr>
            <!--end::Subtotal-->
            <!--begin::VAT-->
            {{-- <tr style="font-size:14px">
                <td colspan="7" class="" style="text-align: right; font-weight: bold;">Discount</td>
                <td colspan="7" class="text-right" style="font-weight: bold;">
                    {{ formatCurrency($initialDiscount) }}
                </td>
            </tr> --}}
            <tr class="d-none">
                <td colspan="7" class="" style="text-align: right; font-weight: bold;">INV Discount</td>
                <td colspan="7" class="text-right" style="font-weight: bold;">0.00</td>
            </tr>
            <!--end::VAT-->
            <!--begin::Grand total-->
            <tr  class="d-none" style="font-size:14px">
                <td colspan="7" class="" style="text-align: right; font-weight: bold;">Net Amount (Taka)</td>
                <td colspan="7" class="text-right" style="font-weight: bold;">
                    {{ formatCurrency($initialTotal) }}</td>
            </tr>
            <tr class="d-none" style="font-size:14px">
                <td colspan="7" class="" style="text-align: right; font-weight: bold;">Paid Amount</td>
                <td colspan="7" class="text-right" style="font-weight: bold;">
                    {{ formatCurrency($customerPayment) }} </td>
            </tr>
            <tr class="d-none" style="font-size:14px">
                <td colspan="7" class="" style="text-align: right; font-weight: bold;">Due Amount (Taka)</td>
                <td colspan="7" class="text-right" style="font-weight: bold;">
                    <span class="double-underline">{{ formatCurrency($initialTotal - $customerPayment) }}
                        </spam>
                </td>
            </tr>
            <!--end::Grand total-->
        </tbody>
        <!--end::Table head-->
    </table>

    <p class="d-none"><b>In Words: {{ numberToWord($initialTotal) }}</b> </p>

    {{-- @if ($stocks[0]->remarks)
        <p ><b>Remarks: </b>{{$stocks[0]->remarks}} </p>
    @endif --}}

    @if ($customerPayment)
        <p class="d-none"><b>Narration:</b> {{ $paymentNarration }}</p>
    @else
        @if ($stocks[0]->remarks)
            <p><b>Remarks: </b>{{ $stocks[0]->remarks }} </p>
        @endif
    @endif

    <br>
    <p class="d-none"> <b>for {{ $data['company_name'] }}</b> </p>
    <br><br>
    <div style="text-align: left; padding-top: 30px;" class="d-none">
        <div style="display: inline-block; width: 130px; padding-right: 20px">
            <p  style="border-top: 1px solid #000000; font-weight: bold; text-align: center;">Authorised Signatory </p>
        </div>
        <div style="display: inline-block; width: 130px; padding: 0px 20px;">
            <p style="border-top: 1px solid #000000; font-weight: bold; text-align: center;">Customer Signatory</p>
        </div>

    </div>

    {{-- include footer --}}
    @include('pages.pdf.partials.footer_pdf')
</body>

</html>

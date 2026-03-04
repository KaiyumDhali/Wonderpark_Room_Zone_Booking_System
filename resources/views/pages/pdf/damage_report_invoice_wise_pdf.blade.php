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

    {{-- <script>
        window.onload = function() {
            window.print();
            // Optionally close the tab after printing
            setTimeout(() => window.close(), 2000);
        };
    </script> --}}

</head>

<body style="font-size:12px">
    {{-- include header --}}
    @include('pages.pdf.partials.header_pdf')

    <div class="text-center py-0 my-0">

        <h5 class="py-0 my-0 pdf_title">Damage Invoice</h5>

        <table style="width: 100%">
            <tr>
                <td style="width: 50%">
                    <h6 class="my-0">Supplier Details</h6>
                    <table style="font-size: 14px; font-style:bold;">
                        <thead>
                            <tr>
                                <td>Supplier ID</td>
                                <td>:</td>
                                <td>{{ $damage_products[0]->supplier->id }}</td>
                            </tr>
                            <tr>
                                <td scope="col">Supplier Name</td>
                                <td>:</td>
                                <td>{{ $damage_products[0]->supplier->account_name }}</td>
                            </tr>
                            <tr>
                                <td scope="col">Address</td>
                                <td>:</td>
                                <td>{{ $damage_products[0]->supplier->account_address }}</td>
                            </tr>
                            <tr>
                                <td scope="col">Mobile No</td>
                                <td>:</td>
                                <td>{{ $damage_products[0]->supplier->account_mobile }}</td>
                            </tr>
                        </thead>
                    </table>

                </td>
                <td style="width: 50%">
                    <table style="font-size: 14px; font-style:bold;" align="right">
                        <thead style="text-align: right;">
                            <tr>
                                <td>
                                    Invoice No (#{{ $damage_products[0]->damage_no }})
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Damage Date: {{ $damage_products[0]->damage_date }}
                                </td>
                            </tr>
                            <tr>
                                <td scope="col" style="text-align: right; font-weight: bold;">Damage Reason:
                                    {{ $damage_products[0]->damage_reason }}</td>
                            </tr>

                            <tr>
                                <td scope="col" style="text-align: right; font-weight: bold;">
                                    Warehouse: {{ $damage_products[0]->warehouse->name }}
                                </td>
                            </tr>

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
                    <th class="min-w-100px">Unit Price</th>
                    <th class="min-w-100px ">Total Amount</th>
                </tr>
            </thead>
            <!--end::Table head-->
            <!--begin::Table body-->
            <tbody class="fw-semibold text-gray-600">
                <!--begin::Products-->

                <span class="d-none">{{ $initialTotal = 0 }}</span>
                @foreach ($damage_products as $damage_product)
                    <tr class="text-center">
                        <td class="">{{ $loop->iteration }}</td>
                        <td class="">{{ $damage_product->product_id }}</td>
                        <td class="text-start">{{ $damage_product->product->product_name }}
                        </td>
                        <td class="text-center">
                            {{ $damage_product->product->unit->unit_name }}</td>
                        <td class="">{{ $damage_product->damage_quantity }}</td>
                        <td class="text-right" style="text-align: right;">
                            {{ formatCurrency($damage_product->purchasePrice) }}</td>
                        {{-- <td class="text-end">
                        {{ formatCurrency($damage_product->stock_out_discount) }}</td> --}}
                        <td class="text-end" style="text-align: right;">
                            {{ formatCurrency($productTotal = $damage_product->purchasePrice * $damage_product->damage_quantity) }}
                        </td>
                        <span class="d-none">{{ $initialTotal = $initialTotal + $productTotal }}</span>
                    </tr>
                @endforeach

                <!--end::Products-->
                <!--begin::Subtotal-->
                <tr class="" style="font-size:14px">
                    <td colspan="6" class="" style="text-align: right; font-weight: bold;">Total</td>
                    <td colspan="6" class="text-right" style="font-weight: bold;">
                        {{ formatCurrency($initialTotal) }}
                    </td>
                </tr>


                <!--end::Grand total-->
            </tbody>
            <!--end::Table head-->
        </table>

        <br>
        <br><br>
        <div style="text-align: left; padding-top: 30px;">
            <div style="display: inline-block; width: 130px; padding-right: 20px">
                <p style="border-top: 1px solid #000000; font-weight: bold; text-align: center;">Authorised Signatory
                </p>
            </div>
            <div style="display: inline-block; width: 130px; padding: 0px 20px;">
                <p style="border-top: 1px solid #000000; font-weight: bold; text-align: center;">Supplier Signatory</p>
            </div>

        </div>

        {{-- include footer --}}
        @include('pages.pdf.partials.footer_pdf')
</body>

</html>

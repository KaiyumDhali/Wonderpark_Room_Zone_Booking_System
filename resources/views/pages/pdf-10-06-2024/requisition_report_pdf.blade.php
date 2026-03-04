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
        <table class="table table-borderless table-sm d">
            <tbody>
                <tr class="text-center">
                    <td>
                        <h4>{{ $data['company_name'] }}</h4>
                        <h6 class="">Material Requisition</h6>
                    </td>
                </tr>
            </tbody>
        </table>
        {{-- <div class="col-md-6" style="text-align: right">

            <h5 class="mb-0"> Requisition No (#{{$productionIn[0]->invoice_no}}) </h5>
            <br>
            <h6>Requisition Date : {{$productionIn[0]->stock_date}}</h6>
            <!-- <br>Prepared By : admin -->
        </div> --}}

        <div class="col-md-6 px-0 mx-0" style="text-align: right">
            <h6 class="mb-0"> Requisition No (#{{$productionIn[0]->invoice_no}}) </h6>
            <p class="mb-0">
                Requisition Date : {{ $productionIn[0]->stock_date }}
                <br>
                Requisition By : {{ $productionIn[0]->done_by }}
            </p>
            @php
                $status = $productionIn[0]->status;
                $approvedBy = $status == 0 ? 'Pending' : ($status == 1 ? 'Production Manager' : ($status == 2 ? 'Production Manager and CEO' : ($status == 3 ? 'Production Manager, CEO, and Store Incharge' : '')));
            @endphp
            @if ($status >= 0 && $status <= 3)
                <p class="mt-0 mb-2">Approved By : {{ $approvedBy }}</p>
            @endif
        </div>


        <div class="d-sm-flex align-items-center justify-content-between">
            <h5 class="">{{ __(' Product to be Produced ') }}</h5>
        </div>
        <table class="table table-bordered table-striped table-sm table-hover pt-0">
            <thead>
                <tr class="text-center text-uppercase text-center text-light bg-success">
                    <th style="font-weight: bold;">Product ID</th>
                    <th>Product Name</th>
                    <th>Unit</th>
                    <th>Quantity</th>
                    <th>Unit Price (BDT)</th>
                    <th>Total Price (BDT)</th>
                </tr>
            </thead>
            <tbody class="">
                @foreach($productionIn as $stock)
                <tr>
                    <td class="text-center">{{$stock->product_id}}</td>
                    <td class="text-left">{{$stock->product->product_name}}</td>
                    <td class="text-center">{{$stock->product->unit->unit_name}}</td>
                    <td class="text-center">{{$stock->stock_in_quantity}}</td>
                    <td class="text-right">{{ number_format($stock->product->purchase_price, 2)}}</td>
                    <td class="text-right">
                        {{ number_format($stock->product->purchase_price*$stock->stock_in_quantity, 2)}}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="d-sm-flex align-items-center justify-content-between pt-3">
            <h5 class="">{{ __(' Requisition items ') }}</h5>
        </div>
        <table class="table table-bordered table-striped table-sm table-hover pt-0">
            <thead>
                <tr class="text-center text-uppercase text-center text-light bg-success">
                    <th>Material ID</th>
                    <th>Material Name</th>
                    <th>Unit</th>
                    <th>Quantity</th>
                    <th>Unit Cost (BDT)</th>
                    <th>Total Cost (BDT)</th>
                </tr>
            </thead>
            <tbody class="text-center">
                @php
                $consumeOut_total = 0;
                @endphp

                @foreach($consumeOut as $stock)
                <tr>
                    <td class="">{{$stock->product_id}}</td>
                    <td class="text-left">{{$stock->product->product_name}}</td>
                    <td class="">{{$stock->product->unit->unit_name}}</td>
                    <td class="">{{$stock->total_quantity}}</td>
                    <td class="text-right">{{ number_format($stock->product->purchase_price, 2)}}</td>
                    <td class="text-right ">
                    {{-- number_format($consumeOut_sum_total=$stock->product->purchase_price*$stock->stock_out_quantity, 2)--}}
                        {{number_format($consumeOut_sum_total=$stock->total_price, 2)}}
                    </td>
                </tr>
                @php
                $consumeOut_total += $consumeOut_sum_total;
                @endphp
                @endforeach
                <tr style="font-size: 15px">
                    <th colspan="5" style="text-align: right;" class="fs-5 text-dark text-right">Total Requisition Price</th>
                    <th class="text-right text-uppercase text-dark text-right"><span class="double-underline"> {{ number_format($consumeOut_total, 2) }}</span></th>
                </tr>
            </tbody>
        </table>


        <table class="table text-center" style="border: none; border-top: none; margin-top: 80px;">
            <tr>
                <td style="border-top: 1px solid #000000; font-weight: bold;">
                    Checked by
                </td>
                <td style="border: none;"></td>
                <td style="border-top: 1px solid #000000; font-weight: bold;">
                    Verified by
                </td>
                <td style="border: none;"></td>
                <td style="border-top: 1px solid #000000; font-weight: bold;">
                    Approved by
                </td>
            </tr>
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

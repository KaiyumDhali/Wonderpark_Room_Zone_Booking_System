<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>{{ $data['company_name'] }}</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
        integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <style>
        .d {
            border-collapse: collapse;
            width: 100%;
            height: 10px;
        }

        th {
            font-weight: bold;
        }

        .double-underline {
            border-bottom: 4px double;
        }

        .pagenum:before {
            content: counter(page);
        }
    </style>

</head>

<body style="font-size:12px">

    @include('pages.pdf.partials.header_pdf')

    <div class="customerCopy">
        <div class="backgroundImage"></div>
        <div class="content">
            <!-- Your content goes here -->
            {{-- <h6 class="text-center pb-4">{{ $data['title'] }}</h6> --}}
            <div class="text-center">
                <h5 class="pb-0 mb-0 pt-0 pdf_title">{{ $data['title'] }}</h5>
            </div>
            <table class="pt-3">
                <thead class="">
                    <tr class="">
                        <td>
                            <h6>Voucher No :</h6>
                        </td>
                        <td>
                            <h6>(#{{ $voucherNo[0]->voucher_no }})</h6>
                        </td>
                    </tr>
                </thead>
            </table>

            <table class="" style="position: absolute; margin-top: -30px;" align="right">
                <thead class="" style="text-align: right;">
                    <tr class="">
                        <td scope="col" style="text-align: right; font-weight: bold;">Voucher Date :</td>
                        <td> @formatDate($voucherNo[0]->voucher_date) </td>
                    </tr>
                </thead>
            </table>

            <table class="table table-bordered">
                <thead class="text-start text-uppercase text-center text-light bg-secondary">
                    <tr style="font-weight: bold; text-align: center;">
                        <th scope="col" style="width: 5px;">SL</th>
                        <th scope="col">Party Name</th>
                        {{-- <th scope="col" style="width: 5px;">L.P.</th> --}}
                        <th scope="col" style="width: 150px;">Debit</th>
                        <th scope="col" style="width: 150px;">Credit</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $index = 1;
                        $dr_balance = 0;
                        $cr_balance = 0;
                    @endphp
                    @foreach ($voucherNo as $voucherData)
                        @if ($voucherData->to_acc_name != 'Sales Account' && $voucherData->acid != 14)
                            <tr>
                                <th scope="row" style="font-weight: bold; text-align: center;">{{$index}}</th>
                                <td>{{$voucherData->to_acc_name}}</td>
                                {{-- <td></td> --}}
                                <td style="font-weight: bold; text-align: right;">
                                    @if ($voucherData->balance_type == 'Dr')
                                        
                                    @elseif($voucherData->balance_type == 'Cr')
                                        @formatCurrency($voucherData->amount)/-
                                        @php $dr_balance += $voucherData->amount @endphp
                                    @endif
                                </td>
                                <td style="font-weight: bold; text-align: right;">
                                    @if ($voucherData->balance_type == 'Cr')
                                        
                                    @elseif($voucherData->balance_type == 'Dr')
                                        @formatCurrency($voucherData->amount)/-
                                        @php $cr_balance += $voucherData->amount @endphp
                                    @endif
                                </td>
                            </tr>
                        @endif
                        
                        @php $index++ @endphp
                    @endforeach
                    <tr>
                        <td></td>
                        {{-- <td colspan="3" style="font-weight: bold; text-align: center;">Total</td> --}}
                        <td style="font-weight: bold; text-align: center;">Total</td>
                        {{-- <td></td> --}}
                        <td style="font-weight: bold; text-align: right;">
                            <span > = @formatCurrency($dr_balance)/-</span></td>
                        <td style="font-weight: bold; text-align: right;">
                            <span > = @formatCurrency($cr_balance)/-</span></td>
                    </tr>
                </tbody>
            </table>

            <div class="d-sm-flex align-items-center justify-content-between pt-3">
                {{-- <p class="text">Amount TK: {{ formatCurrency($voucherNo[0]->amount) }} </p> --}}
                <p class="text" style="font-weight: bold;">Total Taka in Words: {{ numberToWord($voucherNo[0]->amount) }}</p>
                <p class="text"><strong>Purpose:</strong> {{ $voucherNo[0]->narration }}</p>
            </div>

            <table class="table text-center" style="border: none; border-top: none; margin-top: 100;">
                <tr>
                    <td style="border-top: 1px solid #000000; font-weight: bold;">
                        Prepared by
                    </td>
                    <td style="border: none;"></td>
                    <td style="border-top: 1px solid #000000; font-weight: bold;">
                        Checked By
                    </td>
                    <td style="border: none;"></td>
                    <td style="border-top: 1px solid #000000; font-weight: bold;">
                        Approved by
                    </td>
                </tr>
            </table>
        </div>
    </div>

    @include('pages.pdf.partials.footer_pdf')


</body>

</html>

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
    <div class="customerCopy">
        <div class="backgroundImage"></div>
        <div class="content">
            @include('pages.pdf.partials.header_pdf')
            <!-- Your content goes here -->
            <div class="text-center">
                <h5 class="pb-0 mb-0 pt-0 pdf_title">{{ $data['title'] }} (Customer Copy)</h5>
            </div>

            <table class="table table-borderless table-sm d">

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
                <div style="border: 1px solid black;">
                    <table class="" style="position: absolute; margin-top: -30px;" align="right">
                        <thead class="" style="text-align: right;">
                            <tr class="">
                                <td scope="col" style="text-align: right; font-weight: bold;">Voucher Date :</td>
                                <td> @formatDate($voucherNo[0]->voucher_date) </td>
                            </tr>
                        </thead>
                    </table>
                    @foreach ($voucherNo as $voucherData)
                        @if ($voucherData->to_acc_name != 'Purchase Account' && $voucherData->balance_type == 'Cr')
                            <div class="d-sm-flex align-items-center justify-content-between px-3 pt-3">
                                <p class="text">Payment to <strong>{{ $voucherData->to_acc_name }}</strong></p>
                                <p><strong>Narration:</strong> {{ $voucherNo[0]->narration }}</p>
                                <p class="text"> <strong>Amount TK:</strong>
                                    {{ formatCurrency($voucherData->amount) }}/- <br>
                                    <strong>Total Taka in Words:</strong> {{ numberToWord($voucherData->amount) }}
                                </p>
                            </div>
                        @endif
                    @endforeach
                </div>
                <table class="table text-center" style="border: none; border-top: none; margin-top: 80px;">
                    <tr>
                        <td style="border-top: 1px solid #000000; font-weight: bold;">
                            Prepared by
                        </td>
                        <td style="border: none;"></td>
                        {{-- <td style="border-top: 1px solid #000000; font-weight: bold;">
                    Verified by
                </td> --}}
                        <td style="border: none;"></td>
                        <td style="border-top: 1px solid #000000; font-weight: bold;">
                            Received by
                        </td>
                    </tr>
                </table>
        </div>
    </div>

    <hr style="margin: 30px 0px">

    <div class="officeCopy" style="">
        @include('pages.pdf.partials.header_pdf')
        <div class="text-center">
            <h5 class="pb-0 mb-0 pt-0 pdf_title">{{ $data['title'] }} (Office Copy)</h5>
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
        <div style="border: 1px solid black;">
            <table class="" style="position: absolute; margin-top: -30px;" align="right">
                <thead class="" style="text-align: right;">
                    <tr class="">
                        <td scope="col" style="text-align: right; font-weight: bold;">Voucher Date :</td>
                        <td> @formatDate($voucherNo[0]->voucher_date) </td>
                    </tr>

                </thead>
            </table>

            @foreach ($voucherNo as $voucherData)
                @if ($voucherData->to_acc_name != 'Purchase Account' && $voucherData->balance_type == 'Cr')
                    <div class="d-sm-flex align-items-center justify-content-between px-3 pt-3">
                        <p class="text">Payment to <strong>{{ $voucherData->to_acc_name }}</strong></p>
                        <p><strong>Narration:</strong> {{ $voucherNo[0]->narration }}</p>
                        <p class="text"> <strong>Amount TK:</strong> {{ formatCurrency($voucherData->amount) }}/-
                            <br>
                            <strong>Total Taka in Words:</strong> {{ numberToWord($voucherData->amount) }}
                        </p>
                    </div>
                @endif
            @endforeach
        </div>
        <table class="table text-center" style="border: none; border-top: none; margin-top: 80px;">
            <tr>
                <td style="border-top: 1px solid #000000; font-weight: bold;">
                    Prepared by
                </td>
                <td style="border: none;"></td>
                {{-- <td style="border-top: 1px solid #000000; font-weight: bold;">
                    Verified by
                </td> --}}
                <td style="border: none;"></td>
                <td style="border-top: 1px solid #000000; font-weight: bold;">
                    Received by
                </td>
            </tr>
        </table>
    </div>

</body>

</html>

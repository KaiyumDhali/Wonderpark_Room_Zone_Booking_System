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
    @include('pages.pdf.partials.header_pdf')

    <div class="text-center">
        <h5 class="pb-0 mb-0 pt-0 pdf_title">Payment REGISTER</h5>
        @if ($data['start_date'] == $data['end_date'])
            <p>{{ \Carbon\Carbon::parse($data['start_date'])->translatedFormat('d F Y') }}</p>
        @else
            <p>Form {{ \Carbon\Carbon::parse($data['start_date'])->translatedFormat('d F Y') }} to {{ \Carbon\Carbon::parse($data['end_date'])->translatedFormat('d F Y') }}</p>
        @endif
    </div>
    
    <table class="table table-bordered table-striped table-sm table-hover pt-0 pb-5">
        @if ($paymentVouchers)
            <thead class="text-light bg-secondary ">
                <tr class="text-uppercase text-center" style="font-size: 13px; font-weight: bold">
                    <th scope="col" class="text-center align-middle" width="20px">Voucher Date</th>
                    <th scope="col" class="text-center align-middle" width="200px">Particulars</th>
                    <th scope="col" class="text-center align-middle" width="20px">Voucher Type</th>
                    <th scope="col" class="text-center align-middle" width="20px">Voucher No</th>
                    <th scope="col" class="text-center align-middle" width="80px">Debit Amount</th>
                    <th scope="col" class="text-center align-middle" width="80px">Credit Amount</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $dr_balance = 0;
                    $cr_balance = 0;
                @endphp
                @foreach ($paymentVouchers as $paymentVoucher)
                    <tr>
                        <td>@formatDate($paymentVoucher->voucher_date)</td>
                        <td> <strong>{{ $paymentVoucher->acid_name }}</strong> <br> {{ $paymentVoucher->narration }}</td>
                        <td>{{ $paymentVoucher->payment_type }}</td>
                        <td>{{ $paymentVoucher->voucher_no }}</td>
                        <td style="text-align: right;">
                            @if ($paymentVoucher->balance_type == 'Dr')
                                @formatCurrency($paymentVoucher->amount)
                                @php $dr_balance += $paymentVoucher->amount @endphp
                            @elseif($paymentVoucher->balance_type == 'Cr')
                                @formatCurrency(0)
                            @endif
                        </td>
                        <td style="text-align: right;">
                            @if ($paymentVoucher->balance_type == 'Cr')
                                @formatCurrency($paymentVoucher->amount)
                                @php $cr_balance += $paymentVoucher->amount @endphp
                            @elseif($paymentVoucher->balance_type == 'Dr')
                                @formatCurrency(0)
                            @endif
                        </td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="4" style="font-weight: bold; text-align: right;">Total:</td>
                    <td style="font-weight: bold; text-align: right;"><span
                            class="double-underline">@formatCurrency($dr_balance)</span></td>
                    <td style="font-weight: bold; text-align: right;"><span
                            class="double-underline">@formatCurrency($cr_balance)</span></td>
                </tr>
            </tbody>
        @else
            <tr class="text-center">
                <td colspan="5">No Statistics History Found !!!</td>
            </tr>
        @endif
    </table>

    @include('pages.pdf.partials.footer_pdf')


</body>

</html>

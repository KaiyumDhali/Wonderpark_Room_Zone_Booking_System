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
                    <h6 class="py-0 my-0">{{ $data['company_name'] }}</h6>
                    <p class="py-0">
                        {{ $data['company_address'] }}
                        <br>
                        {{ $data['company_mobile'] }}
                    </p>
                    <h6 class="pb-0 mb-0 pt-0">Received Voucher Report</h6>
                    <p>{{ \Carbon\Carbon::parse($data['start_date'])->translatedFormat('d F Y') }} to
                        {{ \Carbon\Carbon::parse($data['end_date'])->translatedFormat('d F Y') }}</p>
                </td>
            </tr>
        </tbody>
    </table>
    <table class="" style="position: absolute; margin-top: -130px;" align="start">
        <tbody>
            <tr class="text-center">
                <td>
                    <img src="{{ $imageDataUri }}" alt="{{ $data['company_name'] }}" height="60" />
                </td>
            </tr>
        </tbody>
    </table>


    <table class="table table-bordered table-striped table-sm table-hover pt-0 pb-5">
        @if ($receivedVouchers)
            <thead class="text-light bg-secondary ">
                <tr class="text-uppercase text-center" style="font-size: 13px; font-weight: bold">
                    <th scope="col" class="text-center align-middle">Voucher Date</th>
                    <th scope="col" class="text-center align-middle">Voucher No</th>
                    <th scope="col" class="text-center align-middle" width="200px">Particulars</th>
                    <th scope="col" class="text-center align-middle">Voucher Type</th>
                    <th scope="col" class="text-center align-middle"  width="80px">Debit Amount</th>
                    <th scope="col" class="text-center align-middle"  width="80px">Credit Amount</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $dr_balance = 0;
                    $cr_balance = 0;
                @endphp
                @foreach ($receivedVouchers as $receivedVoucher)
                    <tr>
                        <td>@formatDate($receivedVoucher->voucher_date)</td>
                        <td>{{ $receivedVoucher->voucher_no }}</td>
                        <td>{{ $receivedVoucher->acid_name }}</td>
                        <td>Bank Receipt</td>
                        <td style="text-align: right;">
                            @if ($receivedVoucher->balance_type == 'Dr')
                                @formatCurrency($receivedVoucher->amount)
                                @php $dr_balance += $receivedVoucher->amount @endphp
                            @elseif($receivedVoucher->balance_type == 'Cr')
                                @formatCurrency(0)
                            @endif
                        </td>
                        <td style="text-align: right;">
                            @if ($receivedVoucher->balance_type == 'Cr')
                                @formatCurrency($receivedVoucher->amount)
                                @php $cr_balance += $receivedVoucher->amount @endphp
                            @elseif($receivedVoucher->balance_type == 'Dr')
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

    <footer class="footer fixed-bottom text-center">
        <table class="table">
            <tr>
                <td style="border: none; border-bottom: none; border-top: none;">
                    Design and Developed By: NRB Telecom Ltd.
                </td>
                <td style="border: none; border-bottom: none; border-top: none;">
                    @php
                        echo date('l, F j, Y h:i A');
                    @endphp
                </td>
            </tr>
        </table>
    </footer>

    <script type="text/php">
        if (isset($pdf)) {
            $font = $fontMetrics->get_font("helvetica", "normal");
            $size = 8;
            $pdf->page_text(500, 775, "Page {PAGE_NUM} of {PAGE_COUNT}", $font, $size, array(0,0,0));
        }
    </script>

</body>

</html>

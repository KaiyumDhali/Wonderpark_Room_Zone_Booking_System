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
        /* .customerCopy {
            margin: 0;
            padding: 0;
            background-image: url('{{ $imageDataUri }}');
            background-position: center;
            background-repeat: no-repeat;
            background-size: auto;
            opacity: 0.2;
        }
        .officeCopy {
            margin: 0;
            padding: 0;
            background-image: url('{{ $imageDataUri }}');
            background-position: center;
            background-repeat: no-repeat;
            background-size: auto;
            opacity: 0.2; /* This will affect the entire element including its children */
        }  */
    </style>

</head>

<body style="font-size:12px">
    <div class="customerCopy">
    <div class="backgroundImage"></div>
    <div class="content">
        <!-- Your content goes here -->
        <table class="table table-borderless table-sm d">
            <tbody>
                <tr class="text-center">
                    <td>
                        <img src="{{ $imageDataUri }}" alt="{{ $data['company_name'] }}" height="60" />
                        {{-- <h4>{{ $data['company_name'] }}</h4> --}}
                        {{-- <img src="{{ asset($data['company_logo_one']) }}" class="h-75px" alt="Company Logo"> --}}
                        <h6 class="">Received Voucher (Customer Copy)</h6>
                    </td>
                </tr>
            </tbody>
        </table>
        <hr>
        <table class="">
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
                    <td>{{ $voucherNo[0]->voucher_date }}</td>
                </tr>

            </thead>
        </table>

        <div class="d-sm-flex align-items-center justify-content-between pt-3">
            <p class="text">Received with Thanks from {{ $voucherNo[1]->to_acc_name }}
                <br>
                {{ $voucherNo[0]->narration }}
            </p>
            <p class="text">Amount TK: {{ $voucherNo[0]->amount }}</p>
            <p class="text">In Word: {{ numberToWord($voucherNo[0]->amount) }}</p>
        </div>

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
    </div>
    </div>

    <hr style="margin: 70px">

    <div class="officeCopy" style="">
        <table class="table table-borderless table-sm d">
            <tbody>
                <tr class="text-center">
                    <td>
                        <img src="{{ $imageDataUri }}" alt="{{ $data['company_name'] }}" height="60" />
                        {{-- <h4>{{ $data['company_name'] }}</h4> --}}
                        <h6 class="">Received Voucher (Office Copy)</h6>
                    </td>
                </tr>
            </tbody>
        </table>
        <hr>
        <table class="">
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
                    <td>{{ $voucherNo[0]->voucher_date }}</td>
                </tr>

            </thead>
        </table>

        <div class="d-sm-flex align-items-center justify-content-between pt-3">
            <p>Received with Thanks from {{ $voucherNo[1]->to_acc_name }}
                <br>
                {{ $voucherNo[0]->narration }}
            </p>

            <p>Amount TK: {{ $voucherNo[0]->amount }}</p>
            <p>In Word: {{ numberToWord($voucherNo[0]->amount) }}</p>

        </div>

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
    </div>


    <footer class="footer fixed-bottom text-center">
        {{-- <img src="assets/media/logos/plc_footer_l.png" alt="logo" width="80%" height="60px;"> --}}
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
                <td style="border: none; border-bottom: none; border-top: none;">
                    Page <span class="pagenum"></span>
                </td>
            </tr>
        </table>
    </footer>
</body>

</html>

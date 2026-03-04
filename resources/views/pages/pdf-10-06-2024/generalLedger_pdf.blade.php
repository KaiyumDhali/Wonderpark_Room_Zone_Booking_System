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
        body {
            margin: 0;
            padding: 0;
            background-image: url('{{ $imageDataUri }}');
            background-position: center;
            background-repeat: no-repeat;
            background-size: auto;
            font-family: Arial, sans-serif;
            color: #ffffff;
            opacity: 0.2;
        }
    </style>

</head>

<body style="font-size:12px">
    <table class="table table-borderless table-sm m-0">
        <tbody>
            <tr class="text-center">
                <td>
                    <img src="{{ $imageDataUri }}" alt="{{ $data['company_name'] }}" height="60" />
                    {{-- <img src="assets/media/logos/default-dark.jpeg" alt="SEML" height="40" /> --}}
                    {{-- <img src="{{ Storage::url($data['company_logo_one']) }}" alt="{{ $data['company_name'] }}" height="40" /> --}}
                    {{-- <h4>{{ $data['company_name'] }}</h4> --}}
                    <h6 class="">General Ledger Report</h6>
                    <h6 class="">Date: {{ $data['start_date'] }} to {{ $data['end_date'] }}</h6>
                </td>
            </tr>

        </tbody>
    </table>



    <table class="table table-bordered table-striped table-sm table-hover pt-0">
        @if ($generalLedger)
            <thead class="text-light bg-success ">
                <tr class="text-uppercase text-center">
                    <th scope="col">Voucher Date</th>
                    <th scope="col">Voucher No </th>
                    <th scope="col">ToAccName</th>
                    <th scope="col">Narration</th>
                    <th scope="col">Debit</th>
                    <th scope="col">Credit</th>
                    <th scope="col">Balance</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $balance = 0;
                    $dr_balance = 0;
                    $cr_balance = 0;
                @endphp

                @foreach ($generalLedger as $general_ledgers)
                    <tr>
                        <td>
                            {{ $general_ledgers->date }}
                        </td>
                        <td>
                            {{ $general_ledgers->voucher_no }}
                        </td>
                        <td>
                            {{ $general_ledgers->to_acc_name }}
                        </td>
                        <td>
                            {{ $general_ledgers->narration }}
                        </td>
                        <td style="text-align: right;">
                            @if ($general_ledgers->balance_type == 'Dr')
                                {{ $general_ledgers->amount }}
                                @php $dr_balance += $general_ledgers->amount @endphp
                            @elseif($general_ledgers->balance_type == 'Cr')
                                {{ 0 }}
                            @endif
                        </td>
                        <td style="text-align: right;">
                            @if ($general_ledgers->balance_type == 'Cr')
                                {{ $general_ledgers->amount }}
                                @php $cr_balance += $general_ledgers->amount @endphp
                            @elseif($general_ledgers->balance_type == 'Dr')
                                {{ 0 }}
                            @endif
                        </td>
                        <td style="text-align: right;">
                            {{ $balance = $dr_balance - $cr_balance }}
                        </td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="4" style="font-weight: bold; text-align: right;">Total:</td>
                    <td style="font-weight: bold; text-align: right;"><span
                            class="double-underline">{{ $dr_balance }}</span></td>
                    <td style="font-weight: bold; text-align: right;"><span
                            class="double-underline">{{ $cr_balance }}</span></td>
                    <td style="font-weight: bold; text-align: right;"><span
                            class="double-underline">{{ $balance }}</span></td>
                </tr>

            </tbody>
        @else
            <tr class="text-center">
                <td colspan="5">No Statistics History Found !!!</td>
            </tr>
        @endif
    </table>



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

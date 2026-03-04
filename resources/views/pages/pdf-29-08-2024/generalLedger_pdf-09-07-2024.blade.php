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

        /* body {
            margin: 0;
            padding: 0;
            background-image: url('{{ $imageDataUri }}');
            background-position: center;
            background-repeat: no-repeat;
            background-size: auto;
            font-family: Arial, sans-serif;
            color: #ffffff;
            opacity: 0.2;
        } */
    </style>

</head>

<body style="font-size:12px">
    <table class="table table-borderless table-sm m-0">
        <tbody>
            <tr class="text-center">
                <td>
                    <img src="{{ $imageDataUri }}" alt="{{ $data['company_name'] }}" height="60" />
                    <p>{{ $data['company_address'] }}</p>

                    {{-- <img src="assets/media/logos/default-dark.jpeg" alt="SEML" height="40" /> --}}
                    {{-- <img src="{{ Storage::url($data['company_logo_one']) }}" alt="{{ $data['company_name'] }}" height="40" /> --}}
                    {{-- <h4>{{ $data['company_name'] }}</h4> --}}
                    {{-- <h6 class="">Date: {{ $data['start_date'] }} to {{ $data['end_date'] }}</h6> --}}
                    <h6 class="mb-0">
                        General Ledger Report <br>
                    </h6>
                    <p>
                        {{ \Carbon\Carbon::parse($data['start_date'])->translatedFormat('d F Y') }} to {{ \Carbon\Carbon::parse($data['end_date'])->translatedFormat('d F Y') }}
                    </p>
                </td>
            </tr>

        </tbody>
    </table>

    <table class="table table-borderless table-sm m-0">
        <tbody>
            <tr>
                <p class="" style="font-size: 15px">
                    Name: {{ $data['customerInfo']->account_name }}
                    <br>
                    @if ($data['customerInfo']->account_mobile)
                        Mobile: {{ $data['customerInfo']->account_mobile }}
                        <br>
                    @endif
                    
                    @if ($data['customerInfo']->account_email)
                        Email: {{ $data['customerInfo']->account_email }}
                        <br>
                    @endif
                    @if ($data['customerInfo']->account_address)
                    Address: {{ $data['customerInfo']->account_address }}
                    @endif
                </p>
            </tr>
        </tbody>
    </table>

    <table class="table table-bordered table-striped table-sm table-hover pt-0 pb-5">
        @if ($generalLedger)
            <thead class="text-light bg-secondary ">
                <tr class="text-uppercase text-center" style="font-size: 13px; font-weight: bold">
                    <th scope="col" class="text-center align-middle">Voucher Date</th>
                    <th scope="col" class="text-center align-middle">Voucher No</th>
                    <th scope="col" class="text-center align-middle">Voucher Type</th>
                    <th scope="col" class="text-center align-middle">Narration</th>
                    <th scope="col" class="text-center align-middle">Debit (BDT)</th>
                    <th scope="col" class="text-center align-middle">Credit (BDT)</th>
                    <th scope="col" class="text-center align-middle">Balance (BDT)</th>
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
                            @formatDate($general_ledgers->date)
                            {{-- {{ $general_ledgers->date }} --}}
                        </td>
                        <td>
                            {{ $general_ledgers->voucher_no }}
                        </td>
                        <td>
                            @php
                                // Determine the type based on both type and balance_type
                                $type = '';
                                if ($general_ledgers->type === 'SV') {
                                    if ($general_ledgers->balance_type === 'Dr') {
                                        $type = 'Sales';
                                    } elseif ($general_ledgers->balance_type === 'Cr') {
                                        $type = 'Received';
                                    }
                                } elseif ($general_ledgers->type === 'PV') {
                                    if ($general_ledgers->balance_type === 'Cr') {
                                        $type = 'Purchase';
                                    } elseif ($general_ledgers->balance_type === 'Dr') {
                                        $type = 'Payment';
                                    }
                                } elseif ($general_ledgers->type === 'CR') {
                                    $type = 'Received';
                                } elseif ($general_ledgers->type === 'CP') {
                                    $type = 'Payment';
                                } else {
                                    $type = $general_ledgers->type; // Default to original type if not matched
                                }
                            @endphp
                            <span style="font-size: 14px; font-style: italic; font-weight: bold">{{ $type }}</span> <br>
                            {{ $general_ledgers->to_acc_name }}
                        </td>
                        <td>
                            {{ $general_ledgers->narration }}
                        </td>
                        <td style="text-align: right;">
                            @if ($general_ledgers->balance_type == 'Dr')
                                @formatCurrency($general_ledgers->amount)
                                {{-- {{ number_format($general_ledgers->amount, 2, '.', ',') }} --}}
                                @php $dr_balance += $general_ledgers->amount @endphp
                            @elseif($general_ledgers->balance_type == 'Cr')
                                @formatCurrency(0)
                            @endif
                        </td>
                        <td style="text-align: right;">
                            @if ($general_ledgers->balance_type == 'Cr')
                                @formatCurrency($general_ledgers->amount)
                                {{-- {{ number_format($general_ledgers->amount, 2, '.', ',') }} --}}
                                @php $cr_balance += $general_ledgers->amount @endphp
                            @elseif($general_ledgers->balance_type == 'Dr')
                                @formatCurrency(0)
                            @endif
                        </td>
                        <td style="text-align: right;">
                            @php $balance = $dr_balance - $cr_balance; @endphp
                            @formatCurrency($balance)
                            {{-- {{ number_format($balance, 2, '.', ',') }} --}}
                        </td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="4" style="font-weight: bold; text-align: right;">Total:</td>
                    <td style="font-weight: bold; text-align: right;"><span
                            class="double-underline">@formatCurrency($dr_balance)</span></td>
                    <td style="font-weight: bold; text-align: right;"><span
                            class="double-underline">@formatCurrency($cr_balance)</span></td>
                    <td style="font-weight: bold; text-align: right;"><span
                            class="double-underline">@formatCurrency($balance)</span></td>
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

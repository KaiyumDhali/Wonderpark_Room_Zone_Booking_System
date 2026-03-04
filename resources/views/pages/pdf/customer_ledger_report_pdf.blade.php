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
                    {{-- <img src="assets/media/logos/default-dark.jpeg" alt="SEML" height="40" /> --}}
                    {{-- <img src="{{ Storage::url($data['company_logo_one']) }}" alt="{{$data['company_name']}}" height="40" /> --}}
                    {{-- <h4>{{ $data['company_name'] }}</h4> --}}
                    <img src="{{ $imageDataUri }}" alt="{{ $data['company_name'] }}" height="60" />
                    <h6 class="">Customer Ledger Report</h6>
                    <h6 class="">Date: {{ $data['start_date'] }} to {{ $data['end_date'] }}</h6>
                </td>
            </tr>
            <tr>
                <p class="" style="font-size: 12px">
                    Customer: {{ $data['customerInfo']->customer_name }} ({{ $data['customerInfo']->customer_code }})
                    <br>
                    Mobile: {{ $data['customerInfo']->customer_mobile }}
                    <br>
                    @if ($data['customerInfo']->customer_email)
                        Email: {{ $data['customerInfo']->customer_email }}
                        <br>
                    @endif
                    Address: {{ $data['customerInfo']->customer_address }}
                </p>
            </tr>
        </tbody>
    </table>


    <table class="table table-bordered table-striped table-sm table-hover pt-0">
        @if ($customerLedgers)
            <thead class="text-light bg-secondary ">
                <tr class="text-uppercase text-center">
                    <th scope="col">T.ID</th>
                    <th scope="col">Invoice</th>
                    <th scope="col">Date</th>
                    <th scope="col">Remarks</th>
                    <th scope="col">Debit</th>
                    <th scope="col">Credit</th>
                    <th scope="col">Balance (BDT)</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $totalCredit = 0;
                    $totalDebit = 0;
                    $balance = 0;
                @endphp
                @foreach ($customerLedgers as $key => $customerLedger)
                    {{-- @php
                    $balance = $balance + ($customerLedger->debit - $customerLedger->credit);
                    @endphp --}}
                    @php
                        $totalCredit += $customerLedger->credit;
                        $totalDebit += $customerLedger->debit;
                        $balance += $customerLedger->debit - $customerLedger->credit;
                    @endphp
                    <tr>
                        <td>{{ $customerLedger->id == -1 ? 0 : $customerLedger->id }}</td>
                        {{-- <td>{{ $customerLedger->id }}</td> --}}
                        <td>{{ $customerLedger->invoice_no }}</td>
                        <td>{{ $customerLedger->ledger_date }}</td>
                        <td>{{ $customerLedger->remarks }}</td>
                        <td class="text-right">{{ number_format($customerLedger->debit, 2) }}</td>
                        <td class="text-right">{{ number_format($customerLedger->credit, 2) }}</td>
                        <td class="text-right">{{ number_format($balance, 2) }}</td>
                    </tr>
                @endforeach

                <tr class="text-light bg-secondary">
                    <td class="text-right" colspan="4"><strong>Total:</strong></td>
                    <td class="text-right"><strong><span
                                class="double-underline">{{ number_format($totalDebit, 2) }}</span></strong>
                    </td>
                    <td class="text-right"><strong><span
                                class="double-underline">{{ number_format($totalCredit, 2) }}</span></strong></td>
                    <td class="text-right"><strong><span
                                class="double-underline">{{ number_format($balance, 2) }}</span></strong></td>
                    {{-- <td></td> --}}
                </tr>
            </tbody>
        @else
            <tr class="text-center">
                <td colspan="5">No Statistics History Found !!!</td>
            </tr>
        @endif
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

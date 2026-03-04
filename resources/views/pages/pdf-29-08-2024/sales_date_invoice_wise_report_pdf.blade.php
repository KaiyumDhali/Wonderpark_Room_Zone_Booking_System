@php
    $runningDrBalance = 0;
    $runningCrBalance = 0;
    $rowsPerPage = 12;
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

        .page-break {
            page-break-before: always;
        }

        .page-header {
            display: block;
            position: fixed;
            top: 0;
            width: 100%;
            text-align: center;
            margin-top: -40px;
        }
    </style>
</head>

<body style="font-size:12px">
    {{-- include header --}}
    @include('pages.pdf.partials.header_pdf')

    <div class="text-center">
        @if ($data['start_date'] == $data['end_date'])
            <h6 class="pb-0 mb-0 pt-0">Daily Sales Register</h6>
            <p>{{ \Carbon\Carbon::parse($data['start_date'])->translatedFormat('d F Y') }}</p>
        @else
            <h6 class="pb-0 mb-0 pt-0">Date Wise Sales Register</h6>
            <p>{{ \Carbon\Carbon::parse($data['start_date'])->translatedFormat('d F Y') }} to
                {{ \Carbon\Carbon::parse($data['end_date'])->translatedFormat('d F Y') }}</p>
        @endif
    </div>

    @php
        $currentRow = 1;
    @endphp

    <table class="table table-bordered table-striped table-sm table-hover pt-0">
        @if ($dateWiseSalesSearch)
            <thead class="text-light bg-secondary">
                <tr class="text-uppercase text-center" style="font-size: 12px; font-weight: bold">
                    <th scope="col" class="text-center align-middle" width="65px">Sales Date</th>
                    <th scope="col" class="text-center align-middle" width="60px">Invoice</th>
                    <th scope="col" class="text-center align-middle" width="60px">Customer Name</th>
                    <th scope="col" class="text-center align-middle" width="60px">Total Amount</th>
                    <th scope="col" class="text-center align-middle" width="40px">Discount</th>
                    <th scope="col" class="text-center align-middle" width="40px">Net Sales(BDT)</th>
            </thead>
            <tbody>
                @php
                    $sum_total_amount = 0;
                    $sum_discount = 0;
                    $sum_net_Sales = 0;
                @endphp

                @foreach ($dateWiseSalesSearch as $dateWiseSales)
                    @php
                        $discount = $dateWiseSales->total_discount ? $dateWiseSales->total_discount : 0;
                        $total_amount = $dateWiseSales->total_amount ? $dateWiseSales->total_amount : 0;
                        $salesAmount = $total_amount + $discount;

                    @endphp
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($dateWiseSales->transaction_date)->format('d/m/Y') }}</td>
                        <td>{{ $dateWiseSales->invoice_no }}</td>
                        <td>
                            <span
                                style="font-size: 12px; font-style: italic; font-weight: bold">{{ $dateWiseSales->account_name }}</span>
                        </td>
                        <td style="text-align: right;">
                            {{ formatCurrency($salesAmount) }}
                        </td>
                        <td style="text-align: right;">
                            {{ formatCurrency($discount) }}
                        </td>
                        <td style="text-align: right;">
                            {{ formatCurrency($total_amount) }}
                        </td>
                    </tr>
                    @php
                        $sum_total_amount += $salesAmount;
                        $sum_discount += $discount;
                        $sum_net_Sales += $total_amount;
                    @endphp
                @endforeach

                <tr>
                    <td colspan="3" style="font-weight: bold; text-align: right;">Total:</td>
                    <td style="font-weight: bold; text-align: right;">
                        @if (!empty($sum_total_amount))
                            <span class="double-underline">{{ formatCurrency($sum_total_amount) }}</span>
                        @endif
                    </td>
                    <td style="font-weight: bold; text-align: right;">
                        @if (!empty($sum_discount))
                            <span class="double-underline">{{ formatCurrency($sum_discount) }}</span>
                        @endif
                    </td>
                    <td style="font-weight: bold; text-align: right;">
                        @if (!empty($sum_net_Sales))
                            <span class="double-underline">{{ formatCurrency($sum_net_Sales) }}</span>
                        @endif
                    </td>
                </tr>
                {{-- <tr>
                    <td colspan="4" style="font-weight: bold; text-align: right;">Total:</td>
                    <td style="font-weight: bold; text-align: right;">
                        @if (!empty($runningDrBalance))
                            <span class="double-underline">{{ formatCurrency($runningDrBalance) }}</span>
                        @endif
                    </td>
                    <td style="font-weight: bold; text-align: right;">
                        @if (!empty($runningCrBalance))
                            <span class="double-underline">{{ formatCurrency($runningCrBalance) }}</span>
                        @endif
                    </td>
                </tr> --}}
            </tbody>
        @else
            <tr class="text-center">
                <td colspan="5">No Statistics History Found !!!</td>
            </tr>
        @endif
    </table>

    {{-- include footer --}}
    @include('pages.pdf.partials.footer_pdf')

</body>

</html>

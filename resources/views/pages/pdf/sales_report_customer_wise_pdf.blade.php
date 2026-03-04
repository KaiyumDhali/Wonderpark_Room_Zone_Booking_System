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
            <h5 class="pb-0 mb-0 pt-0 pdf_title">Daily Sales Register</h5>
            <p>{{ \Carbon\Carbon::parse($data['start_date'])->translatedFormat('d F Y') }}</p>
        @else
            <h5 class="pb-0 mb-0 pt-0 pdf_title">Date Wise Sales Register</h5>
            <p>Form {{ \Carbon\Carbon::parse($data['start_date'])->translatedFormat('d F Y') }} to {{ \Carbon\Carbon::parse($data['end_date'])->translatedFormat('d F Y') }}</p>
        @endif
    </div>

    @php
        $currentRow = 1;
    @endphp

    <table class="table table-bordered table-striped table-sm table-hover pt-0">
        @if ($dateWiseSalesSearch)
            <thead class="text-light bg-secondary">
                <tr class="text-uppercase text-center" style="font-size: 12px; font-weight: bold">
                    <th scope="col" class="text-center align-middle" width="65px">Date</th>
                    <th scope="col" class="text-center align-middle">Party Name</th>
                    <th scope="col" class="text-center align-middle" width="60px">Voucher Type</th>
                    <th scope="col" class="text-center align-middle" width="60px">Voucher No</th>
                    <th scope="col" class="text-center align-middle" width="40px">Debit Amount</th>
                    <th scope="col" class="text-center align-middle" width="40px">Credit Amount</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $dr_balance = 0;
                    $cr_balance = 0;
                @endphp

                @foreach ($dateWiseSalesSearch as $dateWiseSales)
                    @if ($currentRow % $rowsPerPage == 0 && $currentRow != 1)
                        <tr>
                            <td colspan="4" style="font-weight: bold; text-align: center;">Carried Over:</td>
                            <td style="font-weight: bold; text-align: right;">
                                @if (!empty($dr_balance))
                                    <span class="double-underline">{{ formatCurrency($dr_balance) }}</span>
                                @endif
                            </td>
                            <td style="font-weight: bold; text-align: right;">
                                @if (!empty($cr_balance))
                                    <span class="double-underline">{{ formatCurrency($cr_balance) }}</span>
                                @endif
                            </td>
                        </tr>

                        <tr class="page-break"></tr>

                        <div class="page-header">
                            <h6 class="pb-0 mb-0 pt-0">{{ $data['company_name'] }}</h6>
                            <p>Sales Register:
                                {{ \Carbon\Carbon::parse($data['start_date'])->translatedFormat('d F Y') }} to
                                {{ \Carbon\Carbon::parse($data['end_date'])->translatedFormat('d F Y') }}</p>
                        </div>

                        <tr>
                            <td colspan="4" style="font-weight: bold; text-align: center;">Brought Forward</td>
                            <td style="font-weight: bold; text-align: right;">
                                @if (!empty($dr_balance))
                                    <span>{{ formatCurrency($dr_balance) }}</span>
                                @endif
                            </td>
                            <td style="font-weight: bold; text-align: right;">
                                @if (!empty($cr_balance))
                                    <span>{{ formatCurrency($cr_balance) }}</span>
                                @endif
                            </td>
                        </tr>
                    @endif

                    @php
                        // Determine the type based on both type and balance_type
                        $type = '';
                        if ($dateWiseSales->type === 'SV') {
                            if ($dateWiseSales->balance_type === 'Dr') {
                                $type = 'Sales';
                                $dr_balance += $dateWiseSales->amount;
                                $runningDrBalance += $dateWiseSales->amount;
                            } elseif ($dateWiseSales->balance_type === 'Cr') {
                                $type = 'Received';
                                $cr_balance += $dateWiseSales->amount;
                                $runningCrBalance += $dateWiseSales->amount;
                            }
                        } else {
                            $type = $dateWiseSales->type; // Default to original type if not matched
                        }
                    @endphp
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($dateWiseSales->transaction_date)->format('d/m/Y') }}</td>
                        <td>
                            <span
                                style="font-size: 14px; font-style: italic; font-weight: bold">{{ $dateWiseSales->account_name }}</span>
                            <br>
                            {{-- {{ $dateWiseSales->narration }} --}}
                            {{ \Illuminate\Support\Str::limit($dateWiseSales->narration, 90, '...') }}

                        </td>
                        <td>{{ $type }}</td>
                        <td>{{ $dateWiseSales->voucher_no }}</td>
                        <td style="text-align: right;">
                            @if ($dateWiseSales->balance_type == 'Dr')
                                {{ formatCurrency($dateWiseSales->amount) }}
                            @endif
                        </td>
                        <td style="text-align: right;">
                            @if ($dateWiseSales->balance_type == 'Cr')
                                {{ formatCurrency($dateWiseSales->amount ? $dateWiseSales->amount : 0) }}
                            @endif
                        </td>
                    </tr>
                    @php
                        $currentRow++;
                    @endphp
                @endforeach

                <tr>
                    <td colspan="4" style="font-weight: bold; text-align: right;">Total:</td>
                    <td style="font-weight: bold; text-align: right;">
                        @if (!empty($dr_balance))
                            <span class="double-underline">{{ formatCurrency($dr_balance) }}</span>
                        @endif
                    </td>
                    <td style="font-weight: bold; text-align: right;">
                        @if (!empty($cr_balance))
                            <span class="double-underline">{{ formatCurrency($cr_balance) }}</span>
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

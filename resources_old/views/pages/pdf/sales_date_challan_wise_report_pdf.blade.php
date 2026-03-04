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
            <h5 class="pb-0 mb-0 pt-0 pdf_title">Daily Delivery Challan</h5>
            <p>{{ \Carbon\Carbon::parse($data['start_date'])->translatedFormat('d F Y') }}</p>
        @else
            <h5 class="pb-0 mb-0 pt-0 pdf_title">Date Wise Delivery Challan</h5>
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
                    <th scope="col" class="text-center align-middle" width="50px">Sales Date</th>
                    <th scope="col" class="text-center align-middle" width="80px">Delivery Challan</th>
                    <th scope="col" class="text-center align-middle" width="50px">Invoice No</th>
                    <th scope="col" class="text-center align-middle" width="70px">Customer Name</th>
                    <th scope="col" class="text-center align-middle" width="90px">Customer Address</th>
                    <th scope="col" class="text-center align-middle" width="40px">Customer Mobile</th>
                    
            </thead>
            <tbody>
                @foreach ($dateWiseSalesSearch as $dateWiseSales)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($dateWiseSales->transaction_date)->format('d/m/Y') }}</td>
                        <td>{{ $dateWiseSales->delivery_challan_no }}</td>
                        <td>{{ $dateWiseSales->invoice_no }}</td>
                        <td>
                            <span style="font-size: 12px; font-style: italic; font-weight: bold">{{ $dateWiseSales->account_name }}</span>
                        </td>
                        <td>
                            <span style="font-size: 12px;">{{ $dateWiseSales->account_address }}</span>
                        </td>
                        <td>
                            <span style="font-size: 12px;">{{ $dateWiseSales->account_mobile }}</span>
                        </td>
                    </tr>
                @endforeach
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

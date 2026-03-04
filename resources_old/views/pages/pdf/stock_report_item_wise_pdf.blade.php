{{-- @php
    $imagePath = public_path(Storage::url($data['company_logo_one']));
    $imageDataUri = 'data:' . mime_content_type($imagePath) . ';base64,' . base64_encode(file_get_contents($imagePath));
@endphp --}}

<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Item Wise Stock Report</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
        integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
</head>

<body style="font-size:12px">
    {{-- include header --}}
    @include('pages.pdf.partials.header_pdf')
    <div class="text-center">
        <h5 class="pb-0 mb-0 pt-0 pdf_title">Item Wise Stock Report</h5>

        @if ($data['start_date'] == $data['end_date'])
            <p>{{ \Carbon\Carbon::parse($data['start_date'])->translatedFormat('d F Y') }}</p>
        @else
            <p>Form {{ \Carbon\Carbon::parse($data['start_date'])->translatedFormat('d F Y') }} to {{ \Carbon\Carbon::parse($data['end_date'])->translatedFormat('d F Y') }}</p>
        @endif
    </div>
    <table class="table table-bordered table-striped table-sm table-hover">
        <!--begin::Table head-->
        <thead>
            <tr class="text-start text-uppercase text-center text-light bg-secondary">
                <th class="min-w-100px text-center">Date</th>
                <th class="min-w-50px text-center">Invoice No</th>
                <th class="min-w-100px text-center">Product Name</th>
                <th class="min-w-50px text-center">Unit</th>
                <th class="min-w-100px text-center">In Qty</th>
                <th class="min-w-100px text-center">Out Qty</th>
                <th class="min-w-100px text-center">Blance</th>
            </tr>
        </thead>
        {{-- <tbody class="fw-semibold text-gray-600">
            @php
                $cumulativeBalance = 0
            @endphp
            @foreach ($stockReportItemWise as $key => $stock)
                @php
                    $invoice_no = $stock->invoice_no !== null ? $stock->invoice_no : 'Opening Balance';
                    $InQty = $stock->InQty !== null ? $stock->InQty : 0;
                    $OutQty = $stock->OutQty !== null ? $stock->OutQty : 0;
                    $OpeningBalance = $stock->OpeningBalance !== null ? $stock->OpeningBalance : 0;
                    $stockDate = $stock->stock_date;
                    // Calculate the balance for the current row
                    $balance = $OpeningBalance + $InQty - $OutQty;
                    // Update the cumulative balance
                    $cumulativeBalance += $balance;

                    $balanceCell;
                    $formattedStockDate;
                    if ($stock->product_id !== null) {
                        $balanceCell = '<div style="text-align: right;">' + formatCurrency($cumulativeBalance) + '</div>';
                        // Convert stockDate string to a JavaScript Date object
                        $dateObj = new Date($stockDate);
                        $formattedStockDate = formatDateAMPM($dateObj);
                    } else {
                        $balanceCell = '<div style="text-align: right;">' + formatCurrency($OpeningBalance) + '</div>';
                        $formattedStockDate = $data['start_date'];
                    }
                @endphp
                <tr>
                    <td class="text-center">
                        <a class="text-dark text-hover-primary">{{ $stock->id }}</a>
                    </td>
                    <td class="text-left">{{ $formattedStockDate }}</td>
                    <td class="text-left">{{ $invoice_no }}</td>
                    <td class="text-left">{{ $stock->product_name }}</td>
                    <td style="text-align: right;">{{ $InQty }}</td>
                    <td style="text-align: right;">{{ $OutQty }}</td>
                    <td style="text-align: right;">{{ $balanceCell }}</td>
                </tr>
            @endforeach
        </tbody> --}}
        <tbody class="fw-semibold text-gray-600">
            @php
                $cumulativeBalance = 0;
            @endphp
            @foreach ($stockReportItemWise as $stock)
                @php
                    // Define variables for InQty, OutQty, OpeningBalance, and Invoice
                    $invoice_no = $stock->invoice_no !== null ? $stock->invoice_no : 'Opening Balance';
                    $InQty = $stock->InQty !== null ? $stock->InQty : 0;
                    $OutQty = $stock->OutQty !== null ? $stock->OutQty : 0;
                    $OpeningBalance = $stock->OpeningBalance !== null ? $stock->OpeningBalance : 0;
        
                    // Calculate balance for the current row
                    $balance = $OpeningBalance + $InQty - $OutQty;
                    $cumulativeBalance += $balance;
        
                    // Format stock_date or use the start date if null
                    $stockDate = $stock->stock_date ? \Carbon\Carbon::parse($stock->stock_date)->format('Y-m-d h:i A') : $data['start_date'];
        
                    // Format balance cell depending on whether it's an opening balance
                    $balanceCell = $stock->product_id !== null ? number_format($cumulativeBalance, 2) : number_format($OpeningBalance, 2);
                @endphp
                <tr>
                    <td class="text-left">{{ $stockDate }}</td>
                    <td class="text-left">{{ $invoice_no }}</td>
                    <td class="text-left">{{ $stock->product_name }}</td>
                    <td class="text-left">{{ $stock->unit_name }}</td>
                    <td class="text-right">{{ number_format($InQty, 2) }}</td>
                    <td class="text-right">{{ number_format($OutQty, 2) }}</td>
                    <td class="text-right">{{ $balanceCell }}</td>
                </tr>
            @endforeach
        </tbody>
        
    </table>
    {{-- include footer --}}
    @include('pages.pdf.partials.footer_pdf')

</body>

</html>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Item Wise Sales Profit</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
        integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
</head>

<body style="font-size:12px">
    {{-- include header --}}
    @include('pages.pdf.partials.header_pdf')
    <div class="text-center">
        <h5 class="pb-0 mb-0 pt-0 pdf_title">Item Wise Sales Profit</h5>

        @if ($data['start_date'] == $data['end_date'])
            <p>{{ \Carbon\Carbon::parse($data['start_date'])->translatedFormat('d F Y') }}</p>
        @else
            <p>Form {{ \Carbon\Carbon::parse($data['start_date'])->translatedFormat('d F Y') }} to
                {{ \Carbon\Carbon::parse($data['end_date'])->translatedFormat('d F Y') }}</p>
        @endif
    </div>
    <table class="table table-bordered table-striped table-sm table-hover">
        <!--begin::Table head-->
        <thead>
            <tr class="text-start text-uppercase text-center text-light bg-secondary">
                <th class="text-center w-30px">Id</th>
                <th class="text-center w-150px">Product Name</th>
                <th class="text-center w-50px">Total Qty</th>
                <th class="text-center" style="width: 80px">AVG Pur. Price</th>
                <th class="text-center" style="width: 80px">Total Purchase</th>
                <th class="text-center" style="width: 80px">AVG Sales Price</th>
                <th class="text-center" style="width: 80px">Total Sales</th>
                <th class="text-center" style="width: 80px">Total Profit</th>
            </tr>
        </thead>
        <tbody class="fw-semibold text-gray-600">
            @php
                $sum_total_amount = 0;
            @endphp
            @foreach ($salesProfitItemWise as $profit)
                <tr>
                    <td class="text-left">{{ $profit->product_id }}</td>
                    <td class="text-left">{{ $profit->product_name }}</td>
                    <td class="text-right">{{ number_format($profit->total_sales_quantity, 2) }}</td>
                    <td class="text-right">{{ number_format($profit->avg_purchase_price, 3) }}</td>
                    <td class="text-right">{{ number_format($profit->total_purchase_amount, 3) }}</td>
                    <td class="text-right">{{ number_format($profit->avg_sales_price, 3) }}</td>
                    <td class="text-right">{{ number_format($profit->total_sales_amount, 3) }}</td>
                    <td class="text-right">{{ number_format($profit->total_profit, 3) }}</td>
                </tr>
                @php
                    $sum_total_amount += $profit->total_profit;
                @endphp
            @endforeach
            <tr>
                <td colspan="7" style="font-weight: bold; text-align: right;">Total:</td>
                <td style="font-weight: bold; text-align: right;">
                    <span class="double-underline">{{ formatCurrency($sum_total_amount) }}</span>
                </td>
            </tr>
        </tbody>

    </table>
    {{-- include footer --}}
    @include('pages.pdf.partials.footer_pdf')

</body>

</html>

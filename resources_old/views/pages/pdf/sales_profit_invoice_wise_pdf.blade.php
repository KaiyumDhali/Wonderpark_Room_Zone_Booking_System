<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Invoice Wise Sales Profit</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
        integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
</head>

<body style="font-size:12px">
    {{-- include header --}}
    @include('pages.pdf.partials.header_pdf')
    <div class="text-center">
        <h5 class="pb-0 mb-0 pt-0 pdf_title">Invoice Wise Sales Profit</h5>

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
                <th class="text-center" style="width: 75px">Date</th>
                <th class="text-center" style="width: 75px">Invoice No</th>
                <th class="text-center" style="width: 40px">Quantity</th>
                <th class="text-center" style="width: 110px">Purchase Price</th>
                <th class="text-center" style="width: 80px">Discount</th>
                <th class="text-center" style="width: 110px">Sales Price</th>
                <th class="text-center" style="min-width: 110px;">Profit</th>               
            </tr>
        </thead>
        <tbody class="fw-semibold text-gray-600">
            @php
                $sum_total_amount = 0;
            @endphp
            @foreach ($SalesProfitInvoiceWise as $profit)
                <tr>
                    <td class="text-left">{{ $profit->sales_date }}</td>
                    <td class="text-left">{{ $profit->invoice_no }}</td>
                    <td class="text-right">{{ number_format($profit->quantity, 2) }}</td>
                    <td class="text-right">{{ formatCurrency($profit->net_purchase_price) }}</td>
                    <td class="text-right">{{ formatCurrency($profit->net_discount) }}</td>
                    <td class="text-right">{{ formatCurrency($profit->net_sales_price) }}</td>
                    <td class="text-right">{{ formatCurrency($profit->net_profit) }}</td>
                </tr>
                @php
                    $sum_total_amount += $profit->net_profit;
                @endphp
            @endforeach
            <tr>
                <td colspan="6" style="font-weight: bold; text-align: right;">Total:</td>
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

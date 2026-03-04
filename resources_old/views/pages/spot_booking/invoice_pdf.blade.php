
<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
     <title>Invoice {{ $invoiceSummary['invoice'] }}</title>

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
    <style>
    body {
        font-family: DejaVu Sans, sans-serif;
        font-size: 10.5px;
        margin: 0;
        color: #000;
        line-height: 1.3;
    }

    h2, h3, h4 {
        margin: 0 0 6px;
        font-weight: bold;
    }

    h2 { font-size: 16px; }
    h3 { font-size: 13px; }
    h4 { font-size: 12px; }

   

    th {
        background: #f2f2f2;
        font-weight: bold;
        font-size: 10.5px;
    }

    td {
        font-size: 10.5px;
    }

    .text-end { text-align: right; }
    .text-center { text-align: center; }

    .no-border td {
        border: none;
        padding: 3px 4px;
    }

    .summary-box {
        width: 45%;
        margin-left: auto;
        border: 1px solid #444;
        padding: 6px;
        padding-top: 10px;
    }

    .summary-box table {
        margin: 0;
    }

    .summary-box td {
        border: none;
        padding: 3px 4px;
        font-size: 10.5px;
    }

    .grand {
        font-size: 12px;
        font-weight: bold;
        border-top: 2px solid #000;
        padding-top: 4px;
    }

    .paid { color: #0a7d00; font-weight: bold; }
    .due { color: #b00000; font-weight: bold; }

    .page {
        padding: 18px 25px; /* 🔥 side margin reduce */
        box-sizing: border-box;
    }

    .terms-page {
        position: relative;
        min-height: 100vh;
        padding: 18px 25px 160px;
        page-break-before: always;
    }

    .signature-wrapper {
        position: absolute;
        bottom: 25px;
        left: 25px;
        right: 25px;
    }

    ul, ol {
        margin: 4px 0 0 16px;
        padding: 0;
        font-size: 10.5px;
    }

    li {
        margin-bottom: 4px;
    }
</style>
</head>

<body style="font-size:12px">
    @include('pages.pdf.partials.header_pdf')
        <div class="text-center">
       
            <h5 class="pb-0 mb-0 pt-0 pdf_title">Invoice</h5>

    </div>
    
<table class="no-border" style="width:100%;">
    <tr>
        <!-- LEFT SIDE -->
        <td style="width:50%; text-align:left; vertical-align:top;">
            <strong>Invoice No:</strong> {{ $invoiceSummary['invoice'] }}<br>
            <strong>Date:</strong>
            {{ \Carbon\Carbon::parse($invoiceSummary['date'])->format('d M Y') }}
        </td>

        <!-- RIGHT SIDE -->
        <td style="width:50%; text-align:right; vertical-align:top;">
            <strong>Customer:</strong> {{ $invoiceSummary['customer_name'] ?? 'N/A' }}<br>
            <strong>Mobile:</strong> {{ $invoiceSummary['customer_mobile'] ?? 'N/A' }}
        </td>
    </tr>
</table>


<!-- SPOT TABLE -->
<h4>Spot Details</h4>
<table style="width:100%; border-collapse:collapse; table-layout:fixed;">
    <thead>
        <tr>
            <th style="width:50%; border:1px solid #444; background:#f2f2f2; padding:4px 6px; text-align:left;">Spot</th>
            <th style="width:15%; border:1px solid #444; padding:4px 6px; text-align:center;">Qty</th>
            <th style="width:15%; border:1px solid #444; padding:4px 6px; text-align:right;">Price</th>
            <th style="width:20%; border:1px solid #444; padding:4px 6px; text-align:right;">Net Amount</th>
        </tr>
    </thead>
    @php
    $priceTotal = 0;
@endphp

    <tbody>
        @foreach($bookings as $row)
        @if($row->spot_id)
        @php
            $spotDiscount = ($row->total_price * ($row->spot_discount_percent ?? 0)) / 100;
            $spotNet = $row->total_price - $spotDiscount;
            $priceTotal += $row->total_price;
        @endphp
        <tr>
            <td style="border:1px solid #444; padding:4px 6px;">{{ $row->spot_title }}</td>
            <td style="border:1px solid #444; padding:4px 6px; text-align:center;">1</td>
            <td style="border:1px solid #444; padding:4px 6px; text-align:right;">{{ number_format($row->total_price,2) }}</td>
            <td style="border:1px solid #444; padding:4px 6px; text-align:right;">{{ number_format($spotNet,2) }}</td>
        </tr>
        @endif
        @endforeach
    </tbody>
     <tfoot>
    <tr>
        <!-- Label -->
        <th colspan="2" class="text-end">Total</th>

    
        <th class="text-end">
            {{ number_format($priceTotal, 2) }}
        </th>

        <!-- Net Amount column total -->
        <th class="text-end text-primary">
            {{ number_format($invoiceSummary['spot_total'], 2) }}
        </th>
    </tr>
</tfoot>

</table>

<!-- PACKAGE TABLE -->
<h4>Guest Package Details</h4>
<table style="width:100%; border-collapse:collapse; table-layout:fixed;">
    <thead>
        <tr>
            <th style="width:50%; border:1px solid #444; background:#f2f2f2; padding:4px 6px; text-align:left;">Package</th>
            <th style="width:15%; border:1px solid #444; padding:4px 6px; text-align:center;">Persons</th>
            <th style="width:15%; border:1px solid #444; padding:4px 6px; text-align:right;">Price</th>
            <th style="width:20%; border:1px solid #444; padding:4px 6px; text-align:right;">Total</th>
        </tr>
    </thead>
    @php
    $packagePriceTotal = 0;
@endphp

    <tbody>
        @foreach ($bookings as $row)
        @if($row->package_name)
        @php
    $packagePriceTotal += $row->package_price;
@endphp
        <tr>
            <td style="border:1px solid #444; padding:4px 6px;">{{ $row->package_name }}</td>
            <td style="border:1px solid #444; padding:4px 6px; text-align:center;">{{ $row->package_persons }}</td>
            <td style="border:1px solid #444; padding:4px 6px; text-align:right;">{{ number_format($row->package_price,2) }}</td>
            <td style="border:1px solid #444; padding:4px 6px; text-align:right;">{{ number_format($row->total_price,2) }}</td>
        </tr>
        @endif
        @endforeach
    </tbody>
  <tfoot>
    <tr>
        <th colspan="2" class="text-end">Total</th>

        <!-- 🔥 Price column total -->
        <th class="text-end">
            {{ number_format($packagePriceTotal, 2) }}
        </th>

        <!-- Package grand total -->
        <th class="text-end text-primary">
            {{ number_format($invoiceSummary['package_total'], 2) }}
        </th>
    </tr>
</tfoot>

</table>

<!-- SERVICES TABLE -->
@if($services->count())
<h4>Additional Services</h4>
<table style="width:100%; border-collapse:collapse; table-layout:fixed;">
    <thead>
        <tr>
            <th style="width:50%; border:1px solid #444; background:#f2f2f2; padding:4px 6px; text-align:left;">Service</th>
            <th style="width:15%; border:1px solid #444; padding:4px 6px; text-align:center;">Qty</th>
            <th style="width:15%; border:1px solid #444; padding:4px 6px; text-align:right;">Price</th>
            <th style="width:20%; border:1px solid #444; padding:4px 6px; text-align:right;">Total</th>
        </tr>
    </thead>
    @php
    $ServicePriceTotal = 0;
@endphp

    <tbody>
        @foreach ($services as $service)
        @php
    $ServicePriceTotal += $service->price;
@endphp
        <tr>
            <td style="border:1px solid #444; padding:4px 6px;">{{ $service->service_title }}</td>
            <td style="border:1px solid #444; padding:4px 6px; text-align:center;">{{ $service->quantity }}</td>
            <td style="border:1px solid #444; padding:4px 6px; text-align:right;">{{ number_format($service->price,2) }}</td>
            <td style="border:1px solid #444; padding:4px 6px; text-align:right;">{{ number_format($service->total_price,2) }}</td>
        </tr>
        @endforeach
    </tbody>
      <tfoot>
    <tr>
        <th colspan="2" class="text-end">Total</th>

       
        <th class="text-end">
            {{ number_format($ServicePriceTotal, 2) }}
        </th>

        <!-- Package grand total -->
        <th class="text-end text-primary">
           {{ number_format($invoiceSummary['service_total'], 2) }}
        </th>
    </tr>
</tfoot>

</table>
@endif


<!-- ================= SUMMARY BOX ================= -->
<div class="summary-box" style="margin-top:20px; text-align:right;">
<table class="no-border"style="width:100%;">
    <tr>
    <td class="text-end">Sub Total</td>
    <td class="text-end text-primary">
        {{ number_format($invoiceSummary['sub_total'], 2) }}
    </td>
</tr>

<tr>
    <td class="text-end">
        Discount
    </td>
    <td class="text-end">
        -{{ number_format($invoiceSummary['discount_amount'], 2) }}
    </td>
</tr>
<tr>
    <td colspan="2" style="padding:4px 0;">
        <div style="
            width:65%;
            margin-left:auto;
            border-top:1px solid #000;
        "></div>
    </td>
</tr>
<tr>
    <td class="text-end">Net Total</td>
    <td class="text-end">
        {{ number_format($invoiceSummary['net_total'], 2) }}
    </td>
</tr>

<tr>
    <td class="text-end paid">Received</td>
    <td class="text-end paid">
        {{ number_format($invoiceSummary['received_amount'], 2) }}
    </td>
</tr>

<!-- 🔥 BORDER BETWEEN RECEIVED & DUE -->
<tr>
    <td colspan="2" style="padding:4px 0;">
        <div style="
            width:100%;
            margin-left:auto;
            border-top:2px solid #000;
        "></div>
    </td>
</tr>

<tr>
    <td class="text-end due">Due Amount</td>
    <td class="text-end due">
        {{ number_format($invoiceSummary['due_amount'], 2) }}
    </td>
</tr>


</table>
</div>

<p class="text-end" style="margin-top:10px;">
    Status:
    @if ($invoiceSummary['due_amount'] == 0)
        <span class="paid">PAID</span>
    @else
        <span class="due">PARTIALLY PAID</span>
    @endif
</p>
<div class="signature-wrapper">
        <table width="100%" style="border:none;">
            <tr>
                <td width="50%" align="center" style="border:none;">
                    Customer Signature With Date
                    <div style="border-top:1px solid #000; width:80%; margin:60px auto 0;"></div>
                </td>
                <td width="50%" align="center" style="border:none;">
                    Manager Signature With Date
                    <div style="border-top:1px solid #000; width:80%; margin:60px auto 0;"></div>
                </td>
            </tr>
        </table>
    </div>
</div>

<!-- ================= PAGE 2 : FACILITIES ================= -->
<div class="terms-page">
@if($allFacilities->count())
<h4 style="border-bottom:1px solid #000; padding-bottom:5px;">Included Facilities</h4>
<ul>
    @foreach($allFacilities as $fac)
        <li>{{ $fac }}</li>
    @endforeach
</ul>
@endif
</div>

<!-- ================= PAGE 3 : TERMS + SIGNATURE ================= -->
@if ($terms->count())
<div class="terms-page">
    <h4 style="border-bottom:1px solid #000; padding-bottom:5px;">Terms & Conditions</h4>
    <ol style="font-size:12px;">
    @foreach ($terms as $term)
        <li style="margin-bottom:6px;">
            <strong>{{ $term->term_title }}</strong><br>
            {{ $term->term_description }}
        </li>
    @endforeach
    </ol>

    <div class="signature-wrapper">
        <table width="100%" style="border:none;">
            <tr>
                <td width="50%" align="center" style="border:none;">
                    Customer Signature With Date
                    <div style="border-top:1px solid #000; width:80%; margin:60px auto 0;"></div>
                </td>
                <td width="50%" align="center" style="border:none;">
                    Manager Signature With Date
                    <div style="border-top:1px solid #000; width:80%; margin:60px auto 0;"></div>
                </td>
            </tr>
        </table>
    </div>
</div>
@endif

</body>
</html>

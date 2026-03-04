<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Invoice {{ $invoiceSummary['invoice'] }}</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

    <style>
        
        body { font-family: DejaVu Sans, sans-serif; font-size: 10.5px; color:#000; line-height:1.3; }
        h4 { font-size:12px; margin-bottom:6px; font-weight:bold; }
        table { border-collapse: collapse; width:100%; }
        th, td {  padding:4px 6px; font-size:10.5px; }
        th { background:#f2f2f2; }
        .text-right { text-align:right; }
        .text-center { text-align:center; }
        .summary-box { width:45%; margin-left:auto;  padding:0px; }
        .grand { font-weight:bold; border-top:2px solid #000; padding-top:4px; }
        .paid { color: #0a7d00; font-weight:bold; }
        .due { color: #b00000; font-weight:bold; }
        .signature-wrapper { margin-top:60px; }
    </style>
</head>

<body style="font-size:12px">
    @include('pages.pdf.partials.header_pdf')
        <div class="text-center">
       
            <h5 class="pb-0 mb-0 pt-0 pdf_title">Invoice</h5>

    </div>


    <!-- Invoice Header -->
    <table class="no-border" style="margin-bottom:10px;">
        <tr>
            <td style="border:none; width:50%;">
                <strong>Invoice No:</strong> {{ $invoiceSummary['invoice'] }}<br>
                <strong>Date:</strong> {{ \Carbon\Carbon::parse($invoiceSummary['date'])->format('d M Y') }}
            </td>
            <td style="border:none; width:50%; text-align:right;">
                <strong>Customer:</strong> {{ $invoiceSummary['customer_name'] ?? 'N/A' }}<br>
                <strong>Mobile:</strong> {{ $invoiceSummary['customer_mobile'] ?? 'N/A' }}
            </td>
        </tr>
    </table>

    <!-- ROOM / BOOKING DETAILS -->
    <h4>Room Booking Details</h4>
    <table>
        <thead>
            <tr>
                <th>Room</th>
                <th class="text-center" >Check In Date</th>
                <th class="text-center">Check Out Date</th>
                <!-- <th>Start</th> -->
                <!-- <th>End</th> -->
                <th class="text-center">Total Days</th>
                <th class="text-right">Price/Day</th>
                <th class="text-right">Net Amount</th>
            </tr>
        </thead>
        <tbody>
   @php
    $roomSubTotal = 0;
@endphp

@foreach($bookings as $booking)
    @php
        if ($booking->total_days == 0) $booking->total_days = 1;
        if ($booking->check_in_date == $booking->check_out_date) {
    $booking->check_out_date = \Carbon\Carbon::parse($booking->check_out_date)->addDay()->toDateString();
}

        $price = $booking->total_amount; // already room total
        $roomSubTotal += $price;

        $netAmount = $price; // per room, discount applied once later
    @endphp
    <tr>
        <td>{{ $booking->room->name ?? 'Room '.$booking->room_id }}</td>
        <td class="text-center">{{ $booking->check_in_date }}</td>
        <td class="text-center">{{ $booking->check_out_date }}</td>
        <td class="text-center">{{ $booking->total_days }}</td>
       <td class="text-right">
    {{ number_format($booking->room->price_per_night ?? 0, 2) }}
</td>

        <td class="text-right">{{ number_format($netAmount, 2) }}</td>
    </tr>
@endforeach
        </tbody>
        <tfoot>
    <tr>
        <th colspan="5" class="text-right">Room Total</th>
        <th class="text-right">{{ number_format($roomSubTotal,2) }}</th>
    </tr>

</tfoot>
    </table>

    <!-- PAYMENT SUMMARY -->
    <div class="summary-box py-2">
        <table class="no-border">
            <tr>
                <td class="text-right" style="padding-left: 45px;">Sub Total:</td>
                <td class="text-right">{{ number_format($roomSubTotal,2) }}</td>
            </tr>
            <tr>
                <td class="text-right">Discount:</td>
                <td class="text-right">-{{ number_format($invoiceSummary['discount_amount'],2) }}</td>
            </tr>
            <tr>
                <td class="text-right">After Discount Net Total:</td>
                <td class="text-right">{{ number_format($roomSubTotal - $invoiceSummary['discount_amount'],2) }}</td>
            </tr>
       
            <tr>
                <td class="text-right paid">Paid:</td>
                <td class="text-right paid">
                    {{ number_format($paymentDetails->sum('amount'),2) }}
                </td>
            </tr>
            <tr>
                <td class="text-right due">Due:</td>
                <td class="text-right due">
                    {{ number_format(($roomSubTotal - $invoiceSummary['discount_amount']) - $paymentDetails->sum('amount'),2) }}
                </td>
            </tr>
        </table>
    </div>

    <!-- Signatures -->
    <!-- Signatures -->
<div class="signature-wrapper" style="position: absolute; bottom: 20px; width: 100%;">
    <table width="100%" style="border:none;">
        <tr>
            <td width="50%" align="center" style="border:none;">
                Customer Signature
                <div style="border-top:1px solid #000; width:80%; margin:60px auto 0;"></div>
            </td>
            <td width="50%" align="center" style="border:none;">
                Manager Signature
                <div style="border-top:1px solid #000; width:80%; margin:60px auto 0;"></div>
            </td>
        </tr>
    </table>
</div>
@if($terms->count())
    <div style="page-break-before: always;"></div>

    <h4 style="margin-top:15px;">Terms & Conditions</h4>
    <ol style="font-size:10px; padding-left:15px;">
        @foreach($terms as $term)
            <li style="margin-bottom:4px;">
                <strong>{{ $term->term_title }}</strong><br>
                {!! nl2br(e($term->term_description)) !!}
            </li>
        @endforeach
    </ol>
@endif


<div class="signature-wrapper" style="position: absolute; bottom: 20px; width: 100%;">
    <table width="100%" style="border:none;">
        <tr>
            <td width="50%" align="center" style="border:none;">
                Customer Signature
                <div style="border-top:1px solid #000; width:80%; margin:60px auto 0;"></div>
            </td>
            <td width="50%" align="center" style="border:none;">
                Manager Signature
                <div style="border-top:1px solid #000; width:80%; margin:60px auto 0;"></div>
            </td>
        </tr>
    </table>
</div>
</body>
</html>

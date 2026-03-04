@php
    $imagePath = public_path(Storage::url($data['company_logo_one']));
    $imageDataUri = 'data:' . mime_content_type($imagePath) . ';base64,' . base64_encode(file_get_contents($imagePath));
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
    <table class="table table-borderless table-sm m-0">
        <tbody>
            <tr class="text-center">
                <td>
                    <h6 class="py-0 my-0">{{ $data['company_name'] }}</h6>
                    <p class="py-0">
                        {{ $data['company_address'] }}
                        <br>
                        {{ $data['company_mobile'] }}
                    </p>
                    <h6 class="pb-0 mb-0 pt-0">Order Summary Report</h6>
                    <p style="font-weight: bold;">
                        @if ($data['start_date'] == $data['end_date'])
                        {{ \Carbon\Carbon::parse($data['start_date'])->translatedFormat('d F Y') }}
                        @else
                        {{ \Carbon\Carbon::parse($data['start_date'])->translatedFormat('d F Y') }} to
                        {{ \Carbon\Carbon::parse($data['end_date'])->translatedFormat('d F Y') }}
                        @endif
                    </p>
                </td>
            </tr>
        </tbody>
    </table>
    <table class="" style="position: absolute; margin-top: -130px;" align="start">
        <tbody>
            <tr class="text-center">
                <td>
                    <img src="{{ $imageDataUri }}" alt="{{ $data['company_name'] }}" height="60" />
                </td>
            </tr>
        </tbody>
    </table>
    @php
        $currentRow = 1;
    @endphp

    <table class="table table-bordered table-striped table-sm table-hover pt-0">
        @if ($orderSummaryReport)
        <thead class="text-light bg-secondary">
            <tr class="text-uppercase text-center" style="font-size: 12px; font-weight: bold">
                <th scope="col" class="text-center align-middle" width="30px">SL</th>
                <th scope="col" class="text-center align-middle" width="30px">PID</th>
                <th scope="col" class="text-center align-middle">PRODUCT NAME</th>
                <th scope="col" class="text-center align-middle" width="50px">UNIT</th>
                <th scope="col" class="text-center align-middle" width="90px">ORDER COUNT</th>
                <th scope="col" class="text-center align-middle" width="90px">Quantity</th>
            </tr>
        </thead>

            <tbody>
                @php
                    $id = 1;
                @endphp
                @foreach ($orderSummaryReport as $orderSummary)
                    <tr>
                        <td style="text-align: center">{{ $id }}</td>
                        <td style="text-align: center">{{ $orderSummary->product_id }}</td>
                        <td>{{ $orderSummary->product_name }}</td>
                        <td style="text-align: center">{{ $orderSummary->unit_name }}</td>
                        <td style="text-align: center">{{ $orderSummary->order_count }}</td>
                        <td style="text-align: center">{{ $orderSummary->total_stock_out }}</td>
                    </tr>
                   
                    @php
                        $id ++;
                    @endphp
                @endforeach
                
            </tbody>
        @else
            <tr class="text-center">
                <td colspan="5">No Statistics History Found !!!</td>
            </tr>
        @endif
    </table>

    <div class="footer fixed-bottom text-start" style="margin-bottom: -20px;">
        <div style="-ms-flex-line-pack: ">
            <p>Design and Developed By: NRB Telecom Ltd. <span style="padding-left: 70px;">Print Date:
                    @php
                        echo date('l, F j, Y h:i A');
                    @endphp</span></p>
        </div>
    </div>

    <script type="text/php">
        if (isset($pdf)) {
            $font = $fontMetrics->get_font("helvetica", "normal");
            $size = 9;
            $pdf->page_text(500, 800, "Page {PAGE_NUM} of {PAGE_COUNT}", $font, $size, array(0,0,0));
        }
    </script>

</body>

</html>

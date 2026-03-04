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
    </style>

</head>

<body style="font-size:12px">
    <table class="table table-borderless table-sm d">
        <tbody>
            <tr class="text-center">
                <td>
                    {{-- <h4>{{ $data['company_name'] }}</h4> --}}
                    <img src="{{ $imageDataUri }}" alt="{{ $data['company_name'] }}" height="60" />
                    <p class="pb-0">{{ $data['company_address'] }}</p>
                    <h6 class="">Bill Of Materials</h6>
                </td>
            </tr>
        </tbody>
    </table>
    <h6 style="text-align: right">Date : {{ $productionIn->created_at }}</h6>

    <div class="d-sm-flex align-items-center justify-content-between">
        <h5 class="">{{ __(' Product to be Produced ') }}</h5>
    </div>
    <table class="table table-bordered table-striped table-sm table-hover pt-0">
        <thead>
            <tr class="text-center text-uppercase text-center text-light bg-secondary">
                <th style="width: 100px">Product ID</th>
                <th>Product Name</th>
                <th style="width: 100px">Unit</th>
                <th style="width: 100px">Quantity</th>
            </tr>
        </thead>
        <tbody class="">
            <tr>
                <td class="text-center">{{ $productionIn->id }}</td>
                <td class="text-left">{{ $productionIn->product->product_name }}</td>
                <td class="text-center">{{ $productionIn->unit->unit_name }}</td>
                <td class="text-center">{{ $productionIn->quantity }}</td>
            </tr>
        </tbody>
    </table>

    <!-- Consumable Products Content -->
    @if ($consumeDetails && $consumeDetails->count() > 0)
    <div class="d-sm-flex align-items-center justify-content-between pt-3">
        <h5 class="">Requisition items: </h5>
    </div>
    <table class="table table-bordered table-striped table-sm table-hover pt-0">
        <thead>
            <tr class="text-center text-uppercase text-center text-light bg-secondary">
                <th style="width:90px">Material ID</th>
                <th>Material Name</th>
                <th style="width:70px">Unit</th>
                <th style="width:90px">Quantity</th>
            </tr>
        </thead>
        <tbody class="fw-semibold text-gray-600">
            @foreach ($consumeDetails as $consumeDetail)
                <tr>
                    <td class="text-center">{{ $consumeDetail->product->id }}</td>
                    <td class="text-left">{{ $consumeDetail->product->product_name }}</td>
                    <td class="text-center">{{ $consumeDetail->unit->unit_name }}</td>
                    <td class="text-center">{{ $consumeDetail->quantity }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @endif
    
    <!-- By Products Content -->
    @if ($wastageDetails && $wastageDetails->count() > 0)
    <div class="d-sm-flex align-items-center justify-content-between pt-3">
            <h5 class="">By Product / Wastage Product:</h5>
        </div>
        <table class="table table-bordered table-striped table-sm table-hover pt-0">
            <thead>
                <tr class="text-center text-uppercase text-center text-light bg-secondary">
                    <th style="width:90px">Material ID</th>
                    <th>Material Name</th>
                    <th style="width:70px">Unit</th>
                    <th style="width:90px">Quantity</th>
                </tr>
            </thead>
            <tbody class="fw-semibold text-gray-600">
                @foreach ($wastageDetails as $wastageDetail)
                    <tr>
                        <td class="text-center">{{ $wastageDetail->product->id }}</td>
                        <td class="text-left">{{ $wastageDetail->product->product_name }}</td>
                        <td class="text-center">{{ $wastageDetail->unit->unit_name }}</td>
                        <td class="text-center">{{ $wastageDetail->quantity }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif


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
            </tr>
        </table>
    </footer>

    <script type="text/php">
        if (isset($pdf)) {
            $font = $fontMetrics->get_font("helvetica", "normal");
            $size = 8;
            $pdf->page_text(500, 775, "Page {PAGE_NUM} of {PAGE_COUNT}", $font, $size, array(0,0,0));
        }
    </script>

</body>

</html>

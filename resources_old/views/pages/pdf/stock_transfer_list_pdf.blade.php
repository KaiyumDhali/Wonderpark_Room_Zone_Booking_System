{{-- @php
    $imagePath = public_path(Storage::url($data['company_logo_one']));
    $imageDataUri = 'data:' . mime_content_type($imagePath) . ';base64,' . base64_encode(file_get_contents($imagePath));
@endphp --}}

<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Stock Transfer Pending List</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
        integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
</head>

<body style="font-size:12px">
    {{-- include header --}}
    @include('pages.pdf.partials.header_pdf')
    <div class="text-center">
        <h5 class="pb-0 mb-0 pt-0 pdf_title">Stock Transfer List</h5>

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
                <th class="min-w-20px text-center">SL</th>
                <th class="min-w-150px">Date</th>
                <th class="min-w-50px">Invoice</th>
                <th class="min-w-100px">From Warehouse</th>
                <th class="min-w-100px">To Warehouse</th>
                <th class="min-w-20px">Status</th>
            </tr>
        </thead>
        
        <tbody class="fw-semibold text-gray-600">
           @php
               $i = 1;
           @endphp
            @foreach ($results as $result)
                <tr>
                    <td class="text-left">{{ $i }}</td>
                    <td class="text-left">{{ $result->stock_date }}</td>
                    <td class="text-left">{{ $result->invoice_no }}</td>
                    <td class="text-left">{{ $result->from_warehouse_name }}</td>
                    <td class="text-left">{{ $result->to_warehouse_name }}</td>
                    <td class="text-left">{{ $result->status == 1? 'Approved' : 'Pending' }}</td>
                    
                </tr>
                @php
               $i++;
           @endphp
            @endforeach
            
        </tbody>

    </table>
    {{-- include footer --}}
    @include('pages.pdf.partials.footer_pdf')

</body>

</html>

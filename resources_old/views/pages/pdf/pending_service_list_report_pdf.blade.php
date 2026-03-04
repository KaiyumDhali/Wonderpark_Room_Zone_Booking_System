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
            <h5 class="pb-0 mb-0 pt-0 pdf_title">Pending Service List</h5>
            <p>{{ \Carbon\Carbon::parse($data['start_date'])->translatedFormat('d F Y') }}</p>
        @else
            <h5 class="pb-0 mb-0 pt-0 pdf_title">Pending Service List</h5>
            <p>Form {{ \Carbon\Carbon::parse($data['start_date'])->translatedFormat('d F Y') }} to
                {{ \Carbon\Carbon::parse($data['end_date'])->translatedFormat('d F Y') }}</p>
        @endif
    </div>


    <table class="table table-bordered table-striped table-sm table-hover pt-0">
        @if ($pendingServiceList)
            <thead class="text-light bg-secondary">
                <tr class="text-uppercase text-center" style="font-size: 12px; font-weight: bold">
                    <th scope="col" class="text-center align-middle" width="60px">Invoice</th>
                    <th scope="col" class="text-center align-middle" width="60px">Customer Name</th>
                    <th scope="col" class="text-center align-middle" width="60px">Product Name</th>
                    <th scope="col" class="text-center align-middle" width="65px">Service Date</th>
                    <th scope="col" class="text-center align-middle" width="60px">Service Number</th>
                    <th scope="col" class="text-center align-middle" width="60px">Service Status</th>
                    <th scope="col" class="text-center align-middle" width="60px">Done By</th>
                </tr>
            </thead>

            <tbody>

                @foreach ($pendingServiceList as $pendingServiceLists)
                    <tr>
                        <td style="text-align: center;">{{ $pendingServiceLists->invoice_no }}</td>
                        <td style="text-align: center;">{{ $pendingServiceLists->customer_name }}</td>
                        <td style="text-align: center;">{{ $pendingServiceLists->product_name }}</td>
                        <td style="text-align: center;">{{ $pendingServiceLists->service_date }}</td>
                        <td style="text-align: center;">{{ $pendingServiceLists->service_number }}</td>
                        <td style="text-align: center;">{{ $pendingServiceLists->service_status == 0 ? 'Pending' : 'Assigned' }}</td>
                        <td style="text-align: center;">{{ $pendingServiceLists->done_by }}</td>
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

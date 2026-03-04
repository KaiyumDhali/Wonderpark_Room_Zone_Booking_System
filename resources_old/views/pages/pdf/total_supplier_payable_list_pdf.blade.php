<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Total Supplier Payable List</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
        integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">

    <style>
        .double-underline {
            border-bottom: 4px double;
        }
    </style>
</head>

<body style="font-size:12px">
    {{-- include header --}}
    @include('pages.pdf.partials.header_pdf')

    <div class="text-center">
        <h5 class="pb-0 mb-0 pt-0 pdf_title">Total Supplier Payable</h5>
        <p class="">Report Date: {{ $data['report_date'] }} </p>
    </div>

    <table class="table table-bordered table-striped table-sm table-hover">
        <!--begin::Table head-->
        <thead>
            <tr class="text-start text-uppercase text-light bg-secondary">
                <th class="min-w-50px">ID</th>
                <th class="min-w-100px">Account Name</th>
                <th class="min-w-100px">Mobile Number</th>
                <th class="min-w-100px" style="text-align: right;">Amount (BDT)</th>
            </tr>
        </thead>
        <tbody class="fw-semibold text-gray-600">
            @php
                $id = 1;
                $total_amount = 0;
            @endphp
            @foreach ($supplierPayableList as $key => $supplierPayable)
                <tr>
                    <td class="text-left">
                        <a class="text-dark text-hover-primary">{{ $id }}</a>
                    </td>
                    <td class="text-left">{{ $supplierPayable->account_name }}</td>
                    <td class="text-left">{{ $supplierPayable->account_mobile }}</td>
                    <td style="text-align: right;">({{ formatCurrency($supplierPayable->due) }})</td>
                </tr>
                @php
                    $id++;
                    $total_amount += $supplierPayable->due;
                @endphp
            @endforeach
            <tr>
                <td colspan="3" style="font-weight: bold; text-align: right;">Total:</td>
                <td style="font-weight: bold; text-align: right;">
                    <span class="double-underline">({{ formatCurrency($total_amount) }})</span>
                </td>
            </tr>
        </tbody>

    </table>

    {{-- include footer --}}
    @include('pages.pdf.partials.footer_pdf')

</body>

</html>

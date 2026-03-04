<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>{{$data['company_name']}}</title>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">

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
    <table class="table table-borderless table-sm m-0">
        <tbody>
            <tr class="text-center">
                <td>
                    {{-- <img src="assets/media/logos/default-dark.jpeg" alt="SEML" height="40" /> --}}
                    {{-- <img src="{{ Storage::url($data['company_logo_one']) }}" alt="{{$data['company_name']}}" height="40" /> --}}
                    <h4>{{$data['company_name']}}</h4>
                    <h6 class="">Supplier Ledger Report</h6>
                    <h6 class="">Date: {{ $data['start_date'] }} to {{ $data['end_date'] }}</h6>
                </td>
            </tr>
            <tr>
                <p class="" style="font-size: 12px">
                    Supplier: {{ $data['supplierInfo']->supplier_name }} ({{ $data['supplierInfo']->supplier_code }})
                    <br>
                    Mobile: {{ $data['supplierInfo']->supplier_mobile }}
                    <br>
                    @if ( $data['supplierInfo']->supplier_email )
                    Email: {{ $data['supplierInfo']->supplier_email }}
                    <br>
                    @endif
                    Address: {{ $data['supplierInfo']->supplier_address }}
                </p>
            </tr>
        </tbody>
    </table>


    <table class="table table-bordered table-striped table-sm table-hover pt-0">
        @if ($supplierLedgers)
            <thead class="text-light bg-success ">
                <tr class="text-uppercase text-center">
                    <th scope="col">T.ID</th>
                    <th scope="col">Invoice</th>
                    <th scope="col">Date</th>
                    <th scope="col">Credit</th>
                    <th scope="col">Debit</th>
                    <th scope="col">Balance</th>
                    <th scope="col">Remarks</th>
                </tr>
            </thead>
            <tbody>
                @php
                $totalCredit = 0;
                $totalDebit = 0;
                $balance = 0;
                @endphp
                @foreach ($supplierLedgers as $key => $supplierLedger)
                    {{-- @php
                    $balance = $balance + ($supplierLedger->credit - $supplierLedger->debit);
                    @endphp --}}
                    @php
                        $totalCredit += $supplierLedger->credit;
                        $totalDebit += $supplierLedger->debit;
                        $balance += ($supplierLedger->credit - $supplierLedger->debit);
                    @endphp
                    <tr>
                        {{-- <td>{{ $supplierLedger->id }}</td> --}}
                        <td>{{ $supplierLedger->id == -1 ? 0 : $supplierLedger->id }}</td>
                        <td>{{ $supplierLedger->invoice_no }}</td>
                        <td>{{ $supplierLedger->ledger_date }}</td>
                        <td class="text-right">{{ number_format($supplierLedger->credit, 2) }}</td>
                        <td class="text-right">{{ number_format($supplierLedger->debit, 2) }}</td>
                        <td class="text-right">{{ number_format($balance, 2) }}</td>
                        <td>{{ $supplierLedger->remarks }}</td>
                    </tr>
                @endforeach
                <tr class="text-light bg-success">
                    <td class="text-right" colspan="3"><strong>Total:</strong></td>
                    <td class="text-right"><strong><span class="double-underline">{{ number_format($totalCredit, 2) }}</span></strong></td>
                    <td class="text-right"><strong><span class="double-underline">{{ number_format($totalDebit, 2) }}</span></strong></td>
                    <td class="text-right"><strong><span class="double-underline">{{ number_format($balance, 2) }}</span></strong></td>
                    <td></td>
                </tr>
            </tbody>
        @else
            <tr class="text-center">
                <td colspan="5">No Statistics History Found !!!</td>
            </tr>
        @endif
    </table>

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
                            echo date("l, F j, Y h:i A");
                        @endphp
            </td>
            <td style="border: none; border-bottom: none; border-top: none;">
                Page  <span class="pagenum"></span>
            </td>
        </tr>
    </table>
</footer>

</body>

</html>

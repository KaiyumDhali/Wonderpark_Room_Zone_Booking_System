<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>{{$data['company_name']}}</title>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
</head>

<body style="font-size:10px">
    <table class="table table-borderless table-sm m-0">
        <tbody>
            <tr class="text-center">
                <td>
                    {{-- <img src="assets/media/logos/default-dark.jpeg" alt="SEML" height="40" /> --}}
                    {{-- <img src="{{ Storage::url($data['company_logo_one']) }}" alt="{{$data['company_name']}}" height="40" /> --}}
                    <h4>{{$data['company_name']}}</h4>
                    <h6 class="">Customer Ledger Report</h6>
                    <h6 class="">Date: {{ $data['start_date'] }} to {{ $data['end_date'] }}</h6>
                </td>
            </tr>
            <tr>
                <p class="" style="font-size: 12px">
                    Customer: {{ $data['customerInfo']->customer_name }} ({{ $data['customerInfo']->customer_code }})
                    <br>
                    Mobile: {{ $data['customerInfo']->customer_mobile }}
                    <br>
                    @if ( $data['customerInfo']->customer_email )
                    Email: {{ $data['customerInfo']->customer_email }}
                    <br>
                    @endif
                    Address: {{ $data['customerInfo']->customer_address }}
                </p>
            </tr>
        </tbody>
    </table>


    <table class="table table-bordered table-striped table-sm table-hover pt-0">
        @if ($customerLedgers)
            <thead class="text-light bg-success ">
                <tr class="text-uppercase">
                    <th scope="col">ID</th>
                    {{-- <th scope="col">Employee ID</th> --}}
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
                $balance = 0;
                @endphp
                @foreach ($customerLedgers as $key => $customerLedger)
                    @php
                    $balance = $balance + ($customerLedger->debit - $customerLedger->credit);
                    // $employee_name = $customerLedger->employee->employee_name;
                    @endphp
                    <tr>
                        <td>{{ $customerLedger->id }}</td>
                        <td>{{ $customerLedger->invoice_no }}</td>
                        <td>{{ $customerLedger->ledger_date }}</td>
                        <td>{{ $customerLedger->credit }}</td>
                        <td>{{ $customerLedger->debit }}</td>
                        <td>{{ $balance }}</td>
                        <td>{{ $customerLedger->remarks }}</td>
                    </tr>
                @endforeach
            </tbody>
        @else
            <tr class="text-center">
                <td colspan="5">No Statistics History Found !!!</td>
            </tr>
        @endif
    </table>

</body>

</html>

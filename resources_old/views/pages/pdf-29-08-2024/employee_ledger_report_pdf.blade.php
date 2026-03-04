<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>{{$data['company_name']}}</title>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">

    <style>

    </style>
</head>

<body style="font-size:10px">
    <table class="table table-borderless table-sm m-0">
        <tbody>
            <tr class="text-center">
                <td>
                    <h4>{{$data['company_name']}}</h4>
                    <h6 class="">Employee Ledger Report</h6>
                </td>
            </tr>
            <tr>
                <h6 class="">Employee: {{ $data['employeeInfo']->employee_name }}</h6>
                <h6 class="">Date: {{ $data['start_date'] }} to {{ $data['end_date'] }}</h6>
            </tr>
        </tbody>
    </table>

    <table class="table table-bordered table-striped table-sm table-hover pt-3">
        @if ($employeeLedgers)
            <thead class="text-light bg-secondary ">
                <tr class="text-uppercase">
                    <th scope="col">ID</th>
                    {{-- <th scope="col">Employee ID</th> --}}
                    <th scope="col">Voucher</th>
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
                @foreach ($employeeLedgers as $key => $employeeLedger)
                    @php
                    $balance = $balance + ($employeeLedger->credit - $employeeLedger->debit);
                    // $employee_name = $employeeLedger->employee->employee_name;
                    @endphp
                    <tr>
                        <td>{{ $employeeLedger->id }}</td>
                        {{-- <td>{{ $employeeLedger->employee_id }}</td> --}}
                        <td>{{ $employeeLedger->voucher_no }}</td>
                        <td>{{ $employeeLedger->date }}</td>
                        <td>{{ $employeeLedger->credit }}</td>
                        <td>{{ $employeeLedger->debit }}</td>
                        <td>{{ $balance }}</td>
                        <td>{{ $employeeLedger->remarks }}</td>
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

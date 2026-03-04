<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Perfume PLC.</title>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">

    <style>

    </style>
</head>

<body style="font-size:10px">
    <table class="table table-borderless table-sm m-0">
        <tbody class="text-center">
            <tr>
                {{-- <td>
                    <img src="assets/media/logos/default-dark.jpeg" alt="SEML" width="160px" height="70" />
                </td> --}}
                <td>
                    <h4>Perfume PLC.</h4>
                    <h6 class="pl-3">Employee Leave Month of "<span class="text-capitalize">{{ $data['month'] }} {{ $data['year'] }}</span>" </h6>
                </td>
            </tr>
        </tbody>
    </table>


    <table class="table table-bordered table-striped table-sm table-hover pt-3">
        @if ($employeeDetails)
            <thead class="text-light bg-success ">
                <tr class="text-uppercase">
                    <th scope="col">Code</th>
                    <th scope="col">Name</th>
                    {{-- <th scope="col">Department</th>
                    <th scope="col">Designation</th> --}}
                    <th scope="col">No of Late</th>
                    <th scope="col">Late Leave</th>
                    <th scope="col">Casual</th>
                    <th scope="col">Total Casual</th>
                    <th scope="col">Sick</th>
                    {{-- <th scope="col">Annual</th>
                    <th scope="col">Special</th> --}}
                    <th scope="col">Leave Availed</th>
                    <th scope="col">Leave Balance</th>
                    <th scope="col">Total Leave</th>
                </tr>
            </thead>
            
            <tbody>
                @foreach ($employeeDetails as $key => $employeeDetails)
                    <tr>
                        <td>{{ $employeeDetails->employee_code }}</td>
                        <td>{{ $employeeDetails->employee_name }}</td>
                        {{-- <td>{{ $employeeDetails->department_name }}</td>
                        <td>{{ $employeeDetails->designation_name }}</td> --}}
                        <td>{{ $employeeDetails->NoOfLate }}</td>
                        <td>{{ $employeeDetails->LateOfLeave }}</td>
                        <td>{{ $employeeDetails->casual }}</td>
                        <td>{{ $employeeDetails->TotalCasual }}</td>
                        <td>{{ $employeeDetails->sick }}</td>
                        {{-- <td>{{ $employeeDetails->annual }}</td>
                        <td>{{ $employeeDetails->special }}</td> --}}
                        <td>{{ $employeeDetails->LeaveAvailed }}</td>
                        <td>{{ $employeeDetails->LeaveBalance }}</td>
                        <td>{{ $employeeDetails->total_leave }}</td>
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

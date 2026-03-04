<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Perfume PLC.</title>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">

    <style>
        @page {
            size: Legal landscape;
        }
        /* .title_head{
            padding-right: 30% !important;
        } */
    </style>
</head>

<body style="font-size:10px">
    <table class="table table-borderless table-sm m-0">
        <tbody class="text-center">
            <tr>
                {{-- <td>
                    <img src="assets/media/logos/default-dark.jpeg" alt="SEML" width="160px" height="70px" />
                </td> --}}
                <td class="text-center">
                    <h4 class="pl-2">Perfume PLC.</h4>
                    <h6 class="">Leave Entry Report for The Date {{ $data['start_date'] }} to {{ $data['end_date'] }} </h6>
                </td>
            </tr>
        </tbody>
    </table>


    <table class="table table-bordered table-striped table-sm table-hover pt-3">
        @if ($employeeDetails)
            <thead class="text-light bg-success ">
                <tr class="text-uppercase text-center">
                    <th scope="col">ID</th>
                    <th scope="col">Section</th>
                    <th scope="col">Name</th>
                    <th scope="col">Alternative</th>
                    <th scope="col">Type</th>
                    <th scope="col">Application</th>
                    <th scope="col">Start Date</th>
                    <th scope="col">End Date</th>
                    <th scope="col">Total</th>
                    <th scope="col">Remarks</th>
                    <th scope="col">Final Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($employeeDetails as $key => $employeeDetail)
                    <tr class="text-center">
                        <td>{{ $employeeDetail->leaveID }}</td>
                        <td>{{ $employeeDetail->SectionName }}</td>
                        <td>{{ $employeeDetail->EmployeeName }}</td>
                        <td>{{ $employeeDetail->AlternativeEmployeeName }}</td>
                        <td>{{ $employeeDetail->LeaveType }}</td>
                        <td>{{ $employeeDetail->LeaveApplicationDate }}</td>
                        <td>{{ $employeeDetail->LeaveStartDate }}</td>
                        <td>{{ $employeeDetail->LeaveEndDate }}</td>
                        <td>{{ $employeeDetail->TotalDays }}</td>
                        <td>{{ $employeeDetail->Remarks }}</td>
                        <td>{{ $employeeDetail->FinalStatus }}</td>
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

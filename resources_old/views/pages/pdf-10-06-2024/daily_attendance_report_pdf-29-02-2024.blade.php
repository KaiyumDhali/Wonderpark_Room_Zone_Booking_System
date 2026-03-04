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
                    <h4 class="pl-2">Perfume PLC.</h4>
                    <h6 class="">Attendance Report for The Date {{ $data['start_date'] }} to {{ $data['end_date'] }} </h6>
                </td>
            </tr>
        </tbody>
    </table>


    <table class="table table-bordered table-striped table-sm table-hover pt-3">
        @if ($dailyAttendances)
            <thead class="text-light bg-success ">
                <tr class="text-uppercase">
                    <th scope="col">Employee ID</th>
                    <th scope="col">Employee Name</th>
                    <th scope="col">Department</th>
                    <th scope="col">Designation</th>
                    <th scope="col">Date</th>
                    <th scope="col">Day</th>
                    <th scope="col">Time In</th>
                    <th scope="col">Time Out</th>
                    <th scope="col">Time (Entry)</th>
                    <th scope="col">Work Time</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($dailyAttendances as $key => $dailyAttendance)
                    <?php
                    if ($dailyAttendance->LateTime == Null) {
                        $lateTimeInSeconds = $dailyAttendance->LateTime;
                    }else{
                        $lateTimeInSeconds = strtotime($dailyAttendance->LateTime) - strtotime('00:00:00');
                    }
                    ?>
                    <tr>
                        <td>{{ $dailyAttendance->ID }}</td>
                        <td>{{ $dailyAttendance->EmployeeName }}</td>
                        <td>{{ $dailyAttendance->department_name }}</td>
                        <td>{{ $dailyAttendance->designation_name }}</td>
                        <td>{{ $dailyAttendance->_date }}</td>
                        <td>{{ $dailyAttendance->Day }}</td>
                        <td>{{ $dailyAttendance->InTime }}</td>
                        <td>{{ $dailyAttendance->OutTime }}</td>
                        <td style="color: {{ $lateTimeInSeconds < 0 ? 'red' : 'green' }}">
                            {{ $dailyAttendance->LateTime }}
                        </td>
                        <td>{{ $dailyAttendance->WorkTime }}</td>
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

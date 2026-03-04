<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Perfume PLC.</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
        integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous"> --}}

    <style>
        @page {
            /* size: a4 landscape; */
            size: legal landscape;
        }
        .title_head {
            /* padding-right: 30% !important; */
        }
    </style>
</head>

<body style="font-size:10px">
    <table class="table table-borderless table-sm m-0">
        <tbody class="text-center">
            <tr>
                <td>
                    <h4 class="pl-2">Perfume PLC.</h4>
                    <h6 class="">Monthly Attendance Report for The Date {{ $data['start_date'] }} to
                        {{ $data['end_date'] }} </h6>
                </td>
            </tr>
        </tbody>
    </table>
    <table class="table table-bordered table-striped table-sm table-hover pt-3">
        <thead class="text-center text-light bg-secondary ">
            <tr class="text-uppercase">
                <th>ID</th>
                <th style="max-width: 70px;">Employee Name</th>
                @foreach($dates as $date)
                    <th>{{ date('d', strtotime($date)) }}</th>
                @endforeach
                {{-- <th>Total</th> --}}
            </tr>
        </thead>
        <tbody class="text-center">
            @foreach($employeeAttendances as $employeeAttendance)
                <tr>
                    <td>{{ $employeeAttendance['ID'] }}</td>
                    <td>{{ $employeeAttendance['EmployeeName'] }}</td>
                    @foreach($dates as $date)
                        @if(isset($employeeAttendance['attendances'][$date]))
                        <td>
                            {{ $employeeAttendance['attendances'][$date]->InTime }} <br>
                            {{ preg_match('/^\d{2}:\d{2}/', $employeeAttendance['attendances'][$date]->InTime) ? $employeeAttendance['attendances'][$date]->OutTime : '' }} <br>
                            {{ preg_match('/^\d{2}:\d{2}/', $employeeAttendance['attendances'][$date]->InTime) ? 'P' : '' }}
                        </td>
                        @else
                            <td>No Data</td>
                        @endif
                    @endforeach
                    {{-- <td>{{ $employeeAttendance['WorkTime'] }}</td> --}}
                    {{-- <td>{{ isset($employeeAttendance['WorkTime']) ? $employeeAttendance['WorkTime'] : 'N/A' }}</td> --}}

                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>

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
            size: a4 landscape;
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
                {{-- <td>
                    <img src="assets/media/logos/default-dark.jpeg" alt="SEML" width="160px" height="70" />
                </td> --}}
                <td>
                    <h4 class="pl-2">Perfume PLC.</h4>
                    <h6 class="">Attendance Summary for The Date {{ $data['start_date'] }}</h6>
                </td>
            </tr>
        </tbody>
    </table>


    <table class="table table-bordered table-striped table-sm table-hover pt-3">
        @if ($dailyAttendanceSummarySearch->isNotEmpty())
            <thead class="text-light bg-success text-center">
                <tr style="text-align: center; vertical-align: middle;">
                    <th style="vertical-align: middle;" class="min-w-100px" rowspan="2">Section</th>
                    <th style="vertical-align: middle;" class="min-w-100px" rowspan="2">Male</th>
                    <th style="vertical-align: middle;" class="min-w-100px" rowspan="2">Female</th>
                    <th style="vertical-align: middle;" class="min-w-100px" rowspan="2">Total</th>
                    <th style="text-align: center; vertical-align: middle;" colspan="2">Present</th>
                    <th style="text-align: center; vertical-align: middle;" colspan="2">Absent</th>
                    <th style="vertical-align: middle;" class="min-w-100px" rowspan="2">Total Present</th>
                    <th style="vertical-align: middle;" class="min-w-100px" rowspan="2">Total Absent</th>
                    <th style="vertical-align: middle;" class="min-w-100px" rowspan="2">Present (%)</th>
                    <th style="vertical-align: middle;" class="min-w-100px" rowspan="2">Absent (%)</th>
                </tr>
                <tr class="text-start fs-7 text-uppercase gs-0">
                    <th class="min-w-100px">Male</th>
                    <th class="min-w-100px">Female</th>
                    <th class="min-w-100px">Male</th>
                    <th class="min-w-100px">Female</th>
                </tr>
            </thead>
            <tbody class="text-center">
                @php
                    $maleCountSum = 0;
                    $femaleCountSum = 0;
                    $totalSum = 0;
                    $malePresentSum = 0;
                    $femalePresentSum = 0;
                    $totalPSum = 0;
                    $maleAbsentSum = 0;
                    $femaleAbsentSum = 0;
                    $totalASum = 0;
                    $ppSum = 0;
                    $apSum = 0;
                @endphp
                @foreach ($dailyAttendanceSummarySearch as $dailyAttendanceSummary)
                    @php
                        $maleCountSum += $dailyAttendanceSummary->male_count;
                        $femaleCountSum += $dailyAttendanceSummary->female_count;
                        $totalSum += $dailyAttendanceSummary->total;
                        $malePresentSum += $dailyAttendanceSummary->male_present;
                        $femalePresentSum += $dailyAttendanceSummary->female_present;
                        $totalPSum += $dailyAttendanceSummary->TotalP;
                        $maleAbsentSum += $dailyAttendanceSummary->male_absent;
                        $femaleAbsentSum += $dailyAttendanceSummary->female_absent;
                        $totalASum += $dailyAttendanceSummary->TotalA;
                    @endphp
                    <tr>
                        <td>{{ $dailyAttendanceSummary->section_name }}</td>
                        <td>{{ $dailyAttendanceSummary->male_count }}</td>
                        <td>{{ $dailyAttendanceSummary->female_count }}</td>
                        <td>{{ $dailyAttendanceSummary->total }}</td>
                        <td>{{ $dailyAttendanceSummary->male_present }}</td>
                        <td>{{ $dailyAttendanceSummary->female_present }}</td>
                        <td>{{ $dailyAttendanceSummary->male_absent }}</td>
                        <td>{{ $dailyAttendanceSummary->female_absent }}</td>
                        <td>{{ $dailyAttendanceSummary->TotalP }}</td>
                        <td>{{ $dailyAttendanceSummary->TotalA }}</td>
                        <td style="color:green">
                            {{ number_format($dailyAttendanceSummary->PP, 2) }}
                        </td>
                        <td style="color:red">
                            {{ number_format($dailyAttendanceSummary->AP, 2) }}
                        </td>
                    </tr>
                @endforeach
                @php
                    $ppSum = $totalPSum * 100 / $totalSum;
                    $apSum = $totalASum * 100 / $totalSum;
                @endphp
                <tr id="totalsRow" style="background-color: rgb(170, 226, 170)">
                    <td><strong>Total:</strong></td>
                    <td>{{ $maleCountSum }}</td>
                    <td>{{ $femaleCountSum }}</td>
                    <td>{{ $totalSum }}</td>
                    <td>{{ $malePresentSum }}</td>
                    <td>{{ $femalePresentSum }}</td>
                    <td>{{ $maleAbsentSum }}</td>
                    <td>{{ $femaleAbsentSum }}</td>
                    <td>{{ $totalPSum }}</td>
                    <td>{{ $totalASum }}</td>
                    <td style="color:green">{{ number_format($ppSum, 2) }}</td>
                    <td style="color:red">{{ number_format($apSum, 2) }}</td>
                </tr>
            </tbody>
        @else
            <tr class="text-center">
                <td colspan="5">No Statistics History Found !!!</td>
            </tr>
        @endif
    </table>
    
</body>

</html>
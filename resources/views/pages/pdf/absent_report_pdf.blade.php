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
                    <h6 class="">Abesent Report for The Date {{ $data['start_date'] }} to
                        {{ $data['end_date'] }} </h6>
                </td>
            </tr>
        </tbody>
    </table>


    <table class="table table-bordered table-striped table-sm table-hover pt-3">
        @if ($absentSearch)
            <thead class="text-light bg-secondary ">
                <tr class="text-uppercase">
                    <th scope="col">Employee ID</th>
                    <th scope="col">Employee Name</th>
                    <th scope="col">Designation</th>
                    <th scope="col">Section</th>
                    <th scope="col">Line</th>
                    <th scope="col">Date</th>
                    <th scope="col">Remarks</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($allEmpDepartment as $empDepartment)
                    <tr class="bg-info text-center text-light">
                        <th scope="row" colspan="10">{{ $empDepartment }}</th>
                    </tr>
                    @foreach ($absentSearch as $key => $absent)
                        @if ($empDepartment === $absent->department_name)
                            <tr>
                                <td>{{ $absent->employee_code }}</td>
                                <td>{{ $absent->employee_name }}</td>
                                <td>{{ $absent->designation_name }}</td>
                                <td>{{ $absent->section_name }}</td>
                                <td>{{ $absent->line_name }}</td>
                                <td>{{ $absent->_date }}</td>
                                <td style="color:red">
                                    {{ $absent->remarks }}
                                </td>
                            </tr>
                        @endif
                    @endforeach
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

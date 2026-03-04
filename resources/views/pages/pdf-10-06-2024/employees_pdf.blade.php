<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>{{$data['company_name']}}</title>


    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
        integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">

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
                    <h4 class="pl-2">{{$data['company_name']}}</h4>
                    <h6 class="">Employees List</h6>
                </td>
            </tr>
        </tbody>
    </table>


    <table class="table table-bordered table-striped table-sm table-hover pt-3">
        @if ($employeesSearch)
            <thead class="text-light bg-success ">
                <tr class="text-uppercase">
                    <th scope="col">Code</th>
                    <th scope="col">Employee Name</th>
                    <th scope="col">Employee Type </th>
                    <th scope="col">Department</th>
                    <th scope="col">Section</th>
                    <th scope="col">Line</th>
                    <th scope="col">Designation</th>
                    <th scope="col">Salary Section</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($allEmpDepartment as $empDepartment)
                    <tr class="bg-info text-center text-light">
                        <th scope="row" colspan="10">{{ $empDepartment }}</th>
                    </tr>
                    @foreach ($employeesSearch as $key => $employee)
                        @if ($empDepartment === $employee->department_name)
                            <tr>
                                <td>{{ $employee->employee_code }}</td>
                                <td>{{ $employee->employee_name }}</td>
                                <td>{{ $employee->type_name }}</td>
                                <td>{{ $employee->department_name }}</td>
                                <td>{{ $employee->section_name }}</td>
                                <td>{{ $employee->line_name }}</td>
                                <td>{{ $employee->designation_name }}</td>
                                <td>{{ $employee->salary_section_name }}</td>
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

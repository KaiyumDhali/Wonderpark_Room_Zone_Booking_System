<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>{{ $data['company_name'] }}</title>


    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
        integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">

    <style>
        @page {
            size: a4 landscape;
        }

        .title_head {
            /* padding-right: 30% !important; */
        }

        .container {
            width: 100%;
            margin: 0;
            padding: 0;
        }

        .row {
            width: 100%;
        }

        .col-md-12 {
            width: 100%;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 5px;
            /* border: 1px solid black; */
        }

        th {
            text-align: left;
        }
    </style>
</head>

<body style="font-size:10px">

    <div class="col-12">
        <h6>
            <span>Profile Histories</span>
        </h6>
    </div>

    <div class="">
        <div class="">
            <div class="col-12">
                <table>
                    <tr>
                        <td>
                            <table class="table table-bordered table-striped table-sm table-hover pt-3">
                                <tbody>
                                    <tr>
                                        <th scope="row" class="w-50"><strong>Type Name:</strong></th>
                                        <td class="w-50">{{ $employee->type_name }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row" class="w-50"><strong>Department Name:</strong></th>
                                        <td class="w-50">{{ $employee->department_name }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row" class="w-50"><strong>Section Name:</strong></th>
                                        <td class="w-50">{{ $employee->section_name }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row" class="w-50"><strong>Line Name:</strong></th>
                                        <td class="w-50">{{ $employee->line_name }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row" class="w-50"><strong>Designation Name:</strong></th>
                                        <td class="w-50">{{ $employee->designation_name }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row" class="w-50"><strong>Grade Name:</strong></th>
                                        <td class="w-50">{{ $employee->grade_name }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row" class="w-50"><strong>Salary Section:</strong></th>
                                        <td class="w-50">{{ $employee->salary_section_name }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row" class="w-50"><strong>AC Number:</strong></th>
                                        <td class="w-50">{{ $employee->ac_number }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row" class="w-50"><strong>Gross Salary:</strong></th>
                                        <td class="w-50">{{ $employee->gross_salary }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row" class="w-50"><strong>Joining Date:</strong></th>
                                        <td class="w-50">{{ $employee->joining_date }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row" class="w-50"><strong>Job Status:</strong></th>
                                        <td class="w-50">{{ $employee->job_status }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row" class="w-50"><strong>Father Name:</strong></th>
                                        <td class="w-50">{{ $employee->father_name }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row" class="w-50"><strong>Mother Name:</strong></th>
                                        <td class="w-50">{{ $employee->mother_name }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row" class="w-50"><strong>Present Address:</strong></th>
                                        <td class="w-50">{{ $employee->present_address }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row" class="w-50"><strong>Permanent Address:</strong></th>
                                        <td class="w-50">{{ $employee->permanent_address }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                        <td>
                            <table class="table table-bordered table-striped table-sm table-hover pt-3">
                                <tbody>
                                    <tr>
                                        <th scope="row" class="w-50"><strong>Employee Code:</strong></th>
                                        <td class="w-50">{{ $employee->employee_code }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row" class="w-50"><strong>Employee Name:</strong></th>
                                        <td class="w-50">{{ $employee->employee_name }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row" class="w-50"><strong>Gender:</strong></th>
                                        <td class="w-50">{{ $employee->gender }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row" class="w-50"><strong>DOB:</strong></th>
                                        <td class="w-50">{{ $employee->dob }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row" class="w-50"><strong>NID:</strong></th>
                                        <td class="w-50">{{ $employee->nid }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row" class="w-50"><strong>Blood Group:</strong></th>
                                        <td class="w-50">{{ $employee->blood_group }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row" class="w-50"><strong>Marital Status:</strong></th>
                                        <td class="w-50">{{ $employee->marital_status }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row" class="w-50"><strong>Spouse Name:</strong></th>
                                        <td class="w-50">{{ $employee->spouse_name }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row" class="w-50"><strong>Religion:</strong></th>
                                        <td class="w-50">{{ $employee->religion }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row" class="w-50"><strong>Mobile Number One:</strong></th>
                                        <td class="w-50">{{ $employee->mobile }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row" class="w-50"><strong>Mobile Number Two:</strong></th>
                                        <td class="w-50">{{ $employee->mobile_two }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row" class="w-50"><strong>Emergency Contact Person:</strong>
                                        </th>
                                        <td class="w-50">{{ $employee->emergency_contact_person }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row" class="w-50"><strong>Emergency Contact Number:</strong>
                                        </th>
                                        <td class="w-50">{{ $employee->emergency_contact_number }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row" class="w-50"><strong>Order:</strong></th>
                                        <td class="w-50">{{ $employee->order }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row" class="w-50"><strong>Status:</strong></th>
                                        <td class="w-50">{{ $employee->status == 1 ? 'Active' : 'Disable' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>



    <div class="col-12">
        <h6>
            <span>Job Responsibility</span>
        </h6>
    </div>


    <div class="col-md-12">
        <table class="table table-bordered table-striped table-sm table-hover pt-3">
            <tr>
                <th>Responsibility Name</th>
                {{-- <th>Start Date</th>
                                                <th>End Date</th> --}}
            </tr>
            @foreach ($employee_job_responsibilities as $employee_job_responsibility)
                <tr>
                    <td>{{ $employee_job_responsibility->job_responsibility }}</td>
                    {{-- <td>{{ $employee_job_responsibility->start_date }}</td>
                                                    <td>{{ $employee_job_responsibility->end_date }}</td> --}}
                </tr>
            @endforeach
        </table>
    </div>


    <div class="col-12">
        <h6>
            <span>Education Histories</span>
        </h6>
    </div>

    <div class="col-md-12">
        <table class="table table-bordered table-striped table-sm table-hover pt-3">

            <tr>
                <th>Exam</th>
                <th>Institution</th>
                <th>Passingyear</th>
                <th>Result</th>
            </tr>
            @foreach ($employee_educations as $employee_education)
                <tr>
                    <td>{{ $employee_education->exam }}</td>
                    <td>{{ $employee_education->institution }}</td>
                    <td>{{ $employee_education->passingyear }}</td>
                    <td>{{ $employee_education->result }}</td>
                </tr>
            @endforeach
        </table>
    </div>


    <div class="col-12">
        <h6>
            <span>Job Histories</span>
        </h6>
    </div>


    <div class="col-md-12">
        <table class="table table-bordered table-striped table-sm table-hover pt-3">
            <tr>
                <th>Company Name</th>
                <th>Designation</th>
                <th>Start Date</th>
                <th>End Date</th>
            </tr>
            @foreach ($employee__job_histories as $employee__job_historie)
                <tr>
                    <td>{{ $employee__job_historie->company_name }}</td>
                    <td>{{ $employee__job_historie->designation }}</td>
                    <td>{{ $employee__job_historie->start_date }}</td>
                    <td>{{ $employee__job_historie->end_date }}</td>
                </tr>
            @endforeach
        </table>
    </div>



</body>

</html>

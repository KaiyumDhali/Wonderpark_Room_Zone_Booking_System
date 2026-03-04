@php
    $photoPath = public_path(Storage::url($employee->photo));
    $signaturePath = public_path(Storage::url($employee->signature));
    $nidPath = public_path(Storage::url($employee->nid_image));

    $photoBase64 = file_exists($photoPath)
        ? 'data:' . mime_content_type($photoPath) . ';base64,' . base64_encode(file_get_contents($photoPath))
        : null;

    $signatureBase64 = file_exists($signaturePath)
        ? 'data:' . mime_content_type($signaturePath) . ';base64,' . base64_encode(file_get_contents($signaturePath))
        : null;

    $nidBase64 = file_exists($nidPath)
        ? 'data:' . mime_content_type($nidPath) . ';base64,' . base64_encode(file_get_contents($nidPath))
        : null;
@endphp


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>{{ $data['company_name'] }}</title>

    <style>
        @page {
            size: A4 portrait;
            margin: 20px;
        }

        body {
            font-size: 10px;
            font-family: DejaVu Sans, sans-serif;
        }

        h4,
        h5 {
            margin: 6px 0;
        }

        .header {
            text-align: center;
            border-bottom: 1px solid #000;
            margin-bottom: 10px;
            padding-bottom: 5px;
        }

        .info-table,
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }

        .info-table th,
        .info-table td,
        .data-table th,
        .data-table td {
            border: 1px solid #000;
            padding: 4px;
        }

        .info-table th {
            width: 30%;
            text-align: left;
            background: #f1f1f1;
        }

        .section-title {
            background: #e9ecef;
            padding: 4px;
            font-weight: bold;
            border: 1px solid #000;
            margin-top: 10px;
        }

        .photo-box {
            text-align: center;
        }

        .photo-box img {
            margin-bottom: 6px;
            border: 1px solid #000;
        }
    </style>
</head>

<body>

    {{-- ================= HEADER ================= --}}
    <div class="header">
        <h4>{{ $data['company_name'] }}</h4>
        <h5>Employee Profile Report</h5>
    </div>

    {{-- ================= BASIC INFO ================= --}}
    <table width="100%">
        <tr>
            <td width="75%">
                <table class="info-table">
                    <tr>
                        <th>Employee Code</th>
                        <td>{{ $employee->employee_code }}</td>
                    </tr>
                    <tr>
                        <th>Employee Name</th>
                        <td>{{ $employee->employee_name }}</td>
                    </tr>
                    <tr>
                        <th>Department</th>
                        <td>{{ $employee->department_name }}</td>
                    </tr>
                    <tr>
                        <th>Designation</th>
                        <td>{{ $employee->designation_name }}</td>
                    </tr>
                    <tr>
                        <th>Type</th>
                        <td>{{ $employee->type_name }}</td>
                    </tr>
                    <tr>
                        <th>Joining Date</th>
                        <td>{{ $employee->joining_date }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>{{ $employee->status == 1 ? 'Active' : 'Disabled' }}</td>
                    </tr>
                </table>
            </td>

            <td width="25%" class="photo-box">
                @if ($photoBase64)
                    <img src="{{ $photoBase64 }}" width="110" height="120"><br>
                @endif
                @if ($signatureBase64)
                    <img src="{{ $signatureBase64 }}" width="110" height="40"><br>
                @endif
                @if ($nidBase64)
                    <img src="{{ $nidBase64 }}" width="110" height="60">
                @endif
            </td>
        </tr>
    </table>

    {{-- ================= PERSONAL INFO ================= --}}
    <div class="section-title">Personal Information</div>
    <table class="info-table">
        <tr>
            <th>Father Name</th>
            <td>{{ $employee->father_name }}</td>
        </tr>
        <tr>
            <th>Mother Name</th>
            <td>{{ $employee->mother_name }}</td>
        </tr>
        <tr>
            <th>Gender</th>
            <td>{{ $employee->gender }}</td>
        </tr>
        <tr>
            <th>Date of Birth</th>
            <td>{{ $employee->dob }}</td>
        </tr>
        <tr>
            <th>Blood Group</th>
            <td>{{ $employee->blood_group }}</td>
        </tr>
        <tr>
            <th>Religion</th>
            <td>{{ $employee->religion }}</td>
        </tr>
        <tr>
            <th>Marital Status</th>
            <td>{{ $employee->marital_status }}</td>
        </tr>
        <tr>
            <th>Spouse Name</th>
            <td>{{ $employee->spouse_name }}</td>
        </tr>
        <tr>
            <th>Emergency Contact Person</th>
            <td>{{ $employee->emergency_contact_person }}</td>
        </tr>
        <tr>
            <th>Emergency Contact Number</th>
            <td>{{ $employee->emergency_contact_number }}</td>
        </tr>
        <tr>
            <th>Permanent Address</th>
            <td>{{ $employee->permanent_address }}</td>
        </tr>
    </table>

    {{-- ================= JOB RESPONSIBILITY ================= --}}
    <div class="section-title">Job Responsibilities</div>
    <table class="data-table">
        <tr>
            <th>#</th>
            <th>Responsibility</th>
        </tr>
        @foreach ($employee_job_responsibilities as $key => $row)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $row->job_responsibility }}</td>
            </tr>
        @endforeach
    </table>

    {{-- ================= EDUCATION ================= --}}
    <div class="section-title">Education History</div>
    <table class="data-table">
        <tr>
            <th>Exam</th>
            <th>Institution</th>
            <th>Passing Year</th>
            <th>Result</th>
        </tr>
        @foreach ($employee_educations as $row)
            <tr>
                <td>{{ $row->exam }}</td>
                <td>{{ $row->institution }}</td>
                <td>{{ $row->passingyear }}</td>
                <td>{{ $row->result }}</td>
            </tr>
        @endforeach
    </table>

    {{-- ================= JOB HISTORY ================= --}}
    <div class="section-title">Job History</div>
    <table class="data-table">
        <tr>
            <th>Company</th>
            <th>Designation</th>
            <th>Start Date</th>
            <th>End Date</th>
        </tr>
        @foreach ($employee__job_histories as $row)
            <tr>
                <td>{{ $row->company_name }}</td>
                <td>{{ $row->designation }}</td>
                <td>{{ $row->start_date }}</td>
                <td>{{ $row->end_date }}</td>
            </tr>
        @endforeach
    </table>

</body>

</html>

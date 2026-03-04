<x-default-layout>
    <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
        <!--begin::Content wrapper-->
        <div class="d-flex flex-column flex-column-fluid">
            <!--begin::Toolbar-->
            {{-- <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
                <!--begin::Toolbar container-->
                <div id="kt_app_toolbar_container" class=" d-flex flex-stack">
                    <!--begin::Page title-->
                    <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                        <!--begin::Title-->
                        <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                            Profile Details
                        </h1>
                    </div>
                    <!--end::Page title-->

                    <!--begin::Page title-->
                    <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                        <!--begin::Title-->
                        <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                            <a class="btn btn-sm btn-primary px-5" href="{{ route('employees.edit', $employee->id) }}">Update Profile</a>
                        </h1>
                        <!--end::Title-->
                    </div>
                    <!--end::Page title-->                    
                </div>
                <!--end::Toolbar container-->
            </div> --}}
            <!--end::Toolbar-->

            <div class="row">
                <div class="col-md-3 pb-10 text-left">
                    @if ($employee->photo !== null)
                        <img src="{{ Storage::url($employee->photo) }}" height="120" width="120" alt="Profile Image" />
                    @endif
                    {{-- <br> --}}
                    @if ($employee->signature !== null)
                        <img src="{{ Storage::url($employee->signature) }}" height="80" width="120" alt="" />
                    @endif
                </div>
                <div class="col-md-6 pb-10 text-center">
                    @if ($employee->branch_id == 1)
                        <h1>Shahjalal Equity Management Limited</h1>
                    @elseif($employee->branch_id == 2)
                        <h1>Shahjalal Asset Management Limited</h1>
                    @else
                        <h1>Shahjalal Equity Management Limited</h1>
                    @endif
                    
                    <h2>Employee Profile</h2>
                    <h2>{{ $employee->employee_name }}</h2>
                </div>
                <div class="col-md-3 pb-10 text-end">
                    @if ($employee->branch_id == 1)
                        <img src="{{ asset('assets/media/logos/default-dark.jpeg') }}" alt="SEML" width="210px" />
                    @elseif($employee->branch_id == 2)
                        <img src="{{ asset('assets/media/logos/shahjalalasset_logo.jpg') }}" alt="SAML"
                            width="210px" />
                    @else
                        <img src="{{ asset('assets/media/logos/default-dark.jpeg') }}" alt="SEML" width="210px" />
                    @endif
                </div>
            </div>

            <!--begin:: Profile Histories Content-->
            <div id="kt_app_content" class="app-content flex-column-fluid mb-5">
                <!--begin::Content container-->
                <div id="kt_app_content_container" class="">
                    <!--begin::Main column-->
                    <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
                        <!-- Content Row -->
                        <div class="card shadow">
                            <div class="card-header pt-0">
                                <!--begin::Title-->
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="card-label fw-bold text-gray-800">Profile Histories</span>
                                </h3>
                            </div>

                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <table class="table table-hover table-bordered">
                                            <tbody>
                                                <tr>
                                                    <th scope="row" class="w-50"><strong>Branch Name:</strong></th>
                                                    <td class="w-50">{{ $employee->branch_name }}</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row" class="w-50"><strong>Department Name:</strong>
                                                    </th>
                                                    <td class="w-50">{{ $employee->department_name }}</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row" class="w-50"><strong>Designation Name:</strong>
                                                    </th>
                                                    <td class="w-50">{{ $employee->designation_name }}</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row" class="w-50"><strong>Joining Date:</strong>
                                                    </th>
                                                    <td class="w-50">{{ $employee->joining_date }}</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row" class="w-50"><strong>Job Status:</strong></th>
                                                    <td class="w-50">{{ $employee->job_status }}</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row" class="w-50"><strong>Highest
                                                            Education:</strong></th>
                                                    <td class="w-50">{{ $employee->highest_education }}</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row" class="w-50"><strong>Reference One
                                                            Name:</strong></th>
                                                    <td class="w-50">{{ $employee->reference_one_name }}</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row" class="w-50"><strong>Reference One
                                                            Phone:</strong></th>
                                                    <td class="w-50">{{ $employee->reference_one_phone }}</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row" class="w-50"><strong>Reference One
                                                            Address:</strong></th>
                                                    <td class="w-50">{{ $employee->reference_one_address }}</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row" class="w-50"><strong>Reference Tow
                                                            Name:</strong></th>
                                                    <td class="w-50">{{ $employee->reference_two_name }}</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row" class="w-50"><strong>Reference Tow
                                                            Phone:</strong></th>
                                                    <td class="w-50">{{ $employee->reference_two_phone }}</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row" class="w-50"><strong>Reference Tow
                                                            Address:</strong></th>
                                                    <td class="w-50">{{ $employee->reference_two_address }}</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row" class="w-50"><strong>Present Address:</strong>
                                                    </th>
                                                    <td class="w-50">{{ $employee->present_address }}</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row" class="w-50"><strong>Permanent
                                                            Address:</strong></th>
                                                    <td class="w-50">{{ $employee->permanent_address }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <table class="table table-hover table-bordered">
                                            <tbody>
                                                <tr>
                                                    <th scope="row" class="w-50"><strong>Employee Code:</strong>
                                                    </th>
                                                    <td class="w-50">{{ $employee->employee_code }}</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row" class="w-50"><strong>Employee Name:</strong>
                                                    </th>
                                                    <td class="w-50">{{ $employee->employee_name }}</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row" class="w-50"><strong>Mobile Number:</strong>
                                                    </th>
                                                    <td class="w-50">{{ $employee->mobile }}</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row" class="w-50"><strong>Email Address:</strong>
                                                    </th>
                                                    <td class="w-50">{{ $employee->email }}</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row" class="w-50"><strong>DOB:</strong></th>
                                                    <td class="w-50">{{ $employee->dob }}</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row" class="w-50"><strong>Gender:</strong></th>
                                                    <td class="w-50">
                                                        {{ $employee->gender }}
                                                        {{-- {{ $employee->gender == 1 ? 'Male' : 'Female' }} --}}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th scope="row" class="w-50"><strong>Blood Group:</strong>
                                                    </th>
                                                    <td class="w-50">{{ $employee->blood_group }}</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row" class="w-50"><strong>Marital Status:</strong>
                                                    </th>
                                                    <td class="w-50">
                                                        {{ $employee->marital_status == 1 ? 'Single' : 'Married' }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th scope="row" class="w-50"><strong>Religion:</strong></th>
                                                    <td class="w-50">{{ $employee->religion }}</td>
                                                </tr>

                                                <tr>
                                                    <th scope="row" class="w-50"><strong>NID:</strong></th>
                                                    <td class="w-50">{{ $employee->nid }}</td>
                                                </tr>
                                                {{-- <tr>
                                                    <th scope="row" class="w-50"><strong>Salary:</strong></th>
                                                    <td class="w-50">{{ $employee->salary }}</td>
                                                </tr> --}}
                                                <tr>
                                                    <th scope="row" class="w-50"><strong>Emergency Contact
                                                            Person:</strong></th>
                                                    <td class="w-50">{{ $employee->emergency_contact_person }}</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row" class="w-50"><strong>Emergency Contact
                                                            Number:</strong></th>
                                                    <td class="w-50">{{ $employee->emergency_contact_number }}</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row" class="w-50"><strong>Father Name:</strong>
                                                    </th>
                                                    <td class="w-50">{{ $employee->father_name }}</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row" class="w-50"><strong>Mother Name:</strong>
                                                    </th>
                                                    <td class="w-50">{{ $employee->mother_name }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Content Row -->
                    </div>
                    <!--end::Main column-->
                </div>
                <!--end::Content container-->
            </div>
            <!--end:: Profile Histories Content-->

            <!--begin:: Job Responsibility Content-->
            <div id="kt_app_content" class="app-content flex-column-fluid mb-5">
                <!--begin::Content container-->
                <div id="kt_app_content_container" class="">
                    <!--begin::Main column-->
                    <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
                        <!-- Content Row -->
                        <div class="card shadow">
                            <div class="card-header pt-0">
                                <!--begin::Title-->
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="card-label fw-bold text-gray-800">Job Responsibility</span>
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <table class="table table-hover table-bordered">
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
                                </div>
                            </div>
                        </div>

                        <!-- Content Row -->
                    </div>
                    <!--end::Main column-->
                </div>
                <!--end::Content container-->
            </div>
            <!--end:: Job ResponsibilityContent-->

            <!--begin:: Education Histories Content-->
            <div id="kt_app_content" class="app-content flex-column-fluid mb-5">
                <!--begin::Content container-->
                <div id="kt_app_content_container" class="">
                    <!--begin::Main column-->
                    <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
                        <!-- Content Row -->
                        <div class="card shadow">
                            <div class="card-header pt-0">
                                <!--begin::Title-->
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="card-label fw-bold text-gray-800">Education Histories</span>
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <table class="table table-hover table-bordered">
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
                                </div>
                            </div>
                        </div>

                        <!-- Content Row -->
                    </div>
                    <!--end::Main column-->
                </div>
                <!--end::Content container-->
            </div>
            <!--end:: Education Histories Content-->

            <!--begin::employee_job_histories Content-->
            <div id="kt_app_content" class="app-content flex-column-fluid mb-10">
                <!--begin::Content container-->
                <div id="kt_app_content_container" class="">
                    <!--begin::Main column-->
                    <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
                        <!-- Content Row -->
                        <div class="card shadow">
                            <div class="card-header pt-0">
                                <!--begin::Title-->
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="card-label fw-bold text-gray-800">Job Histories</span>
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <table class="table table-hover table-bordered">
                                            <tr>
                                                <th>Company Name</th>
                                                <th>Designation</th>
                                                <th>Start Date</th>
                                                <th>End Date</th>
                                            </tr>
                                            @foreach ($employee_job_histories as $employee_job_historie)
                                                <tr>
                                                    <td>{{ $employee_job_historie->company_name }}</td>
                                                    <td>{{ $employee_job_historie->designation }}</td>
                                                    <td>{{ $employee_job_historie->start_date }}</td>
                                                    <td>{{ $employee_job_historie->end_date }}</td>
                                                </tr>
                                            @endforeach
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Content Row -->
                    </div>
                    <!--end::Main column-->
                </div>
                <!--end::Content container-->
            </div>
            <!--end::Content-->
        </div>
        <!--end::Content wrapper-->
    </div>
</x-default-layout>

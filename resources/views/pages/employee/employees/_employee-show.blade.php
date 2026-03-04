<x-default-layout>
    <div class="app-main flex-column flex-row-fluid" id="kt_app_main">

        <!--begin::Content wrapper-->
        <div class="d-flex flex-column flex-column-fluid pb-10">
            <div class="p-5 mx-5">
                <!--begin:::Tabs-->
                <ul id="myTabjustified" role="tablist"
                    class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-semibold mb-8">
                    <!--begin:::Tab item-->
                    <li class="nav-item">
                        <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                            Employee Details
                        </h1>
                    </li>
                    <!--end:::Tab item-->
                </ul>
                <!--end:::Tabs-->

                <div class="d-flex justify-content-end my-5" style="margin-top: -60px!important;">
                    <!--begin::Button-->
                    <a href="{{ route('employee_profile_pdf', $employee->id) }}" id="kt_ecommerce_add_product_cancel" target="_blank"
                        class="btn btn-sm btn-primary me-5">PDF Download</a>
                    <a href="{{ route('employees.edit', $employee->id) }}" id="kt_ecommerce_add_product_cancel"
                        class="btn btn-sm btn-primary me-5">Edit Employee</a>
                    <a class="btn btn-sm btn-success" href="{{ route('employees.index') }}"
                        id="kt_ecommerce_add_employee_cancel">Back</a>

                    <!--end::Button-->
                </div>
            </div>
            <!--begin:: Profile Histories Content-->
            <div id="kt_app_content" class="app-content flex-column-fluid mb-5">
                <!--begin::Content container-->
                <div id="kt_app_content_container" class="app-container">
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
                                    <div class="col-md-12 pb-5 mb-5">
                                        @if ($employee->photo !== null)
                                            <img src="{{ Storage::url($employee->photo) }}" height="150"
                                                width="150" alt="Profile Image" style="margin-right: 50px"/>
                                        @endif
                                        @if ($employee->signature !== null)
                                            <img src="{{ Storage::url($employee->signature) }}" height="80"
                                                width="150" alt="Signature"/>
                                        @endif
                                    </div>
                                    <div class="col-md-6">
                                        <table class="table table-hover table-bordered">
                                            <tbody>
                                                <tr>
                                                    <th scope="row" class="w-50"><strong>Type Name:</strong>
                                                    </th>
                                                    <td class="w-50">{{ $employee->type_name }}</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row" class="w-50"><strong>Department Name:</strong>
                                                    </th>
                                                    <td class="w-50">{{ $employee->department_name }}</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row" class="w-50"><strong>Section Name:</strong>
                                                    </th>
                                                    <td class="w-50">{{ $employee->section_name }}</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row" class="w-50"><strong>Line Name:</strong>
                                                    </th>
                                                    <td class="w-50">{{ $employee->line_name }}</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row" class="w-50"><strong>Designation Name:</strong>
                                                    </th>
                                                    <td class="w-50">{{ $employee->designation_name }}</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row" class="w-50"><strong>Grade Name:</strong>
                                                    </th>
                                                    <td class="w-50">{{ $employee->grade_name }}</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row" class="w-50"><strong>Salary Section:</strong>
                                                    </th>
                                                    <td class="w-50">{{ $employee->salary_section_name }}</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row" class="w-50"><strong>AC Number:</strong>
                                                    </th>
                                                    <td class="w-50">{{ $employee->ac_number }}</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row" class="w-50"><strong>Gross Salary:</strong>
                                                    </th>
                                                    <td class="w-50">{{ $employee->gross_salary }}</td>
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
                                                    <th scope="row" class="w-50"><strong>Father Name:</strong></th>
                                                    <td class="w-50">{{ $employee->father_name }}</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row" class="w-50"><strong>Mother Name:</strong></th>
                                                    <td class="w-50">{{ $employee->mother_name }}</td>
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
                                                    <th scope="row" class="w-50"><strong>Marital Status:</strong>
                                                    </th>
                                                    <td class="w-50">{{ $employee->marital_status }}</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row" class="w-50"><strong>Spouse Name:</strong>
                                                    </th>
                                                    <td class="w-50">{{ $employee->spouse_name }}</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row" class="w-50"><strong>Religion:</strong></th>
                                                    <td class="w-50">{{ $employee->religion }}</td>
                                                </tr>
                                                {{-- <tr>
                                                    <th scope="row" class="w-50"><strong>Email Address:</strong>
                                                    </th>
                                                    <td class="w-50">{{ $employee->email }}</td>
                                                </tr> --}}
                                                <tr>
                                                    <th scope="row" class="w-50"><strong>Mobile Number
                                                            One:</strong></th>
                                                    <td class="w-50">{{ $employee->mobile }}</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row" class="w-50"><strong>Mobile Number
                                                            Two:</strong></th>
                                                    <td class="w-50">{{ $employee->mobile_two }}</td>
                                                </tr>
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
                                                    <th scope="row" class="w-50"><strong>Order:</strong></th>
                                                    <td class="w-50">{{ $employee->order }}</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row" class="w-50"><strong>Status:</strong></th>
                                                    <td class="w-50">{{ $employee->status == 1 ? 'Active' : 'Disable' }}
                                                    </td>
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
                <div id="kt_app_content_container" class="app-container">
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
                    </div>
                    <!--end::Main column-->
                </div>
                <!--end::Content container-->
            </div>
            <!--end:: Job ResponsibilityContent-->

            <!--begin:: Education Histories Content-->
            <div id="kt_app_content" class="app-content flex-column-fluid mb-5">
                <!--begin::Content container-->
                <div id="kt_app_content_container" class="app-container">
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

            <!--begin:: Job Histories Content-->
            <div id="kt_app_content" class="app-content flex-column-fluid mb-10">
                <!--begin::Content container-->
                <div id="kt_app_content_container" class="app-container">
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

                                </div>

                            </div>
                        </div>
                    </div>
                    <!--end::Main column-->
                </div>
                <!--end::Content container-->
            </div>
            <!--end:: Job Histories Content-->


            

        </div>
        <!--end::Content wrapper-->

    </div>
</x-default-layout>

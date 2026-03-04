<x-default-layout>
    <div class="col-xl-12 px-5">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if (session('message'))
            <div class="alert alert-{{ session('alert-type') }}">
                {{ session('message') }}
            </div>
        @endif
    </div>
    <!--begin::Content-->
    <div class="flex-lg-row-fluid ">

        <!--begin:::Tabs-->
        <ul id="myTabjustified" role="tablist"
            class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-semibold mb-8">
            <!--begin:::Tab item-->
            <li class="nav-item">
                <a class="nav-link text-active-primary pb-4 active" data-bs-toggle="tab"
                    href="#kt_ecommerce_customer_overview">Personal Details</a>
            </li>
            <!--end:::Tab item-->
            <!--begin:::Tab item-->
            <li class="nav-item">
                <a class="nav-link text-active-primary pb-4" data-bs-toggle="tab"
                    href="#kt_ecommerce_customer_general">Education</a>
            </li>
            <!--end:::Tab item-->
            <!--begin:::Tab item-->
            <li class="nav-item">
                <a class="nav-link text-active-primary pb-4" data-bs-toggle="tab"
                    href="#kt_ecommerce_customer_advanced">Job History</a>
            </li>
            <!--end:::Tab item-->
            <!--begin:::Tab item-->
            <li class="nav-item">
                <a class="nav-link text-active-primary pb-4" data-bs-toggle="tab" href="#job_responsibility">Job
                    Responsibility</a>
            </li>
            <!--end:::Tab item-->
        </ul>
        <!--end:::Tabs-->

        <div class="d-flex justify-content-end my-5" style="margin-top: -60px!important;">
            <!--begin::Button-->
            <a href="{{ route('employees.index') }}" id="kt_ecommerce_add_product_cancel"
                class="btn btn-sm btn-primary me-5">All Employee List</a>
            <!--end::Button-->
        </div>
        <!--begin:::Tab content-->

        <div class="tab-content" id="myTabContent">

            <!--begin:::Tab Employee Basic Info pane-->
            <div class="tab-pane fade show active" id="kt_ecommerce_customer_overview" role="tabpanel">
                <!--begin::Card-->
                <div class="card pt-4 mb-6 mb-xl-9">
                    <!--begin::Card header-->
                    <div class="card-header border-0">
                        <!--begin::Card title-->
                        <div class="card-title">
                            <h2>Update Employee</h2>
                        </div>
                        <!--end::Card title-->
                    </div>
                    <!--end::Card header-->

                    <!--begin::Card body-->
                    <div class="card-body pt-0 pb-5">
                        <!--begin::Form-->

                        <form id="kt_ecommerce_add_category_form"
                            class="form d-flex flex-column flex-lg-row fv-plugins-bootstrap5 fv-plugins-framework"
                            method="POST" action="{{ route('employees.update', $employee->id) }}"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <!--begin::Aside column-->
                            <div class="col-12 col-md-3 pe-lg-10">
                                <!--begin::Status-->
                                <div class="card card-flush pt-3">

                                    <div class="card-body pt-0 pb-5">
                                        <label class="required form-label">Employee Type</label>
                                        <select class="form-select form-select-sm" data-control="select2" name="type_id" required>
                                            @foreach ($allEmpType as $key => $value)
                                                <option value="{{ $key }}"
                                                    {{ $pmpPosting->type_id == $key ? 'selected' : '' }}>
                                                    {{ $value }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="card-body pt-0 pb-5">
                                        <label class="required form-label">Employee Department</label>
                                        <select class="form-select form-select-sm" data-control="select2" name="department_id" required>
                                            @foreach ($allEmpDepartment as $key => $value)
                                                <option value="{{ $key }}"
                                                    {{ $pmpPosting->department_id == $key ? 'selected' : '' }}>
                                                    {{ $value }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="card-body pt-0 pb-5">
                                        <label class="required form-label">Employee Section </label>
                                        <select class="form-select form-select-sm" data-control="select2" name="section_id" required>
                                            @foreach ($allEmpSection as $key => $value)
                                                <option value="{{ $key }}"
                                                    {{ $pmpPosting->section_id == $key ? 'selected' : '' }}>
                                                    {{ $value }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="card-body pt-0 pb-5">
                                        <label class="required form-label">Employee Line </label>
                                        <select class="form-select form-select-sm" data-control="select2" name="line_id" required>
                                            {{-- <option value="">Select Employee Line</option> --}}
                                            @foreach ($allEmpLine as $key => $value)
                                                <option value="{{ $key }}"
                                                    {{ $pmpPosting->line_id == $key ? 'selected' : '' }}>
                                                    {{ $value }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="card-body pt-0 pb-5">
                                        <label class="required form-label">Employee Designation</label>
                                        <select class="form-select form-select-sm" data-control="select2" name="designation_id" required>
                                            @foreach ($allEmpDesignation as $key => $value)
                                                <option value="{{ $key }}"
                                                    {{ $pmpPosting->designation_id == $key ? 'selected' : '' }}>
                                                    {{ $value }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="card-body pt-0 pb-5">
                                        <label class="required form-label">Employee Grade</label>
                                        <select class="form-select form-select-sm" data-control="select2" name="grade_id" required>
                                            @foreach ($allEmpGrade as $key => $value)
                                                <option value="{{ $key }}"
                                                    {{ $pmpPosting->grade_id == $key ? 'selected' : '' }}>
                                                    {{ $value }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="card-body pt-0 pb-5">
                                        <label class="required form-label">Gross Salary</label>
                                        <input type="number" name="gross_salary"
                                            value="{{ $pmpPosting->gross_salary }}"
                                            class="form-control form-control-sm mb-2" placeholder="Gross Salary"
                                            oninput="this.value = this.value.replace(/[^0-9]/g, '');" required>
                                    </div>
                                    <div class="card-body pt-0 pb-5">
                                        <label class="form-label">A/C Number</label>
                                        <input type="number" name="ac_number" value="{{ $pmpPosting->ac_number }}"
                                            class="form-control form-control-sm mb-2" placeholder="A/C Number"
                                            oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                                    </div>
                                    <div class="card-body pt-0 pb-5">
                                        <label class="required form-label">Salary Section</label>
                                        <select class="form-select form-select-sm" data-control="select2" name="salary_section_id" required>
                                            {{-- <option selected value="">Select Salary Section</option> --}}
                                            @foreach ($allEmpSalarySection as $key => $value)
                                                <option value="{{ $key }}"
                                                    {{ $pmpPosting->salary_section_id == $key ? 'selected' : '' }}>
                                                    {{ $value }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="card-body pt-0 pb-5">
                                        <div class="product fv-row fv-plugins-icon-container">
                                            <label class="form-label"> Joining Date </label>
                                            <input type="date" name="joining_date"
                                                class="form-control form-control-sm mb-2" placeholder="Joining Date"
                                                value="{{ $pmpPosting->joining_date }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--end::Aside column-->
                            <!--begin::Main column-->
                            <div class="col-12 col-md-9 ">


                                <!--begin::General options-->
                                <div class="card card-flush py-4">
                                    <div class="row">
                                        <div class="col-12 col-md-4">
                                            <div class="card-body pt-0 pb-2">
                                                <div class=" fv-row fv-plugins-icon-container">
                                                    <label class="required form-label"> Employee Code </label>
                                                    <input type="text" name="employee_code"
                                                        class="form-control form-control-sm mb-2"
                                                        placeholder="Employee Code"
                                                        value="{{ $employee->employee_code }}" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <div class="card-body pt-0 pb-2">
                                                <div class="product fv-row fv-plugins-icon-container">
                                                    <label class="required form-label"> Employee Name </label>
                                                    <input type="text" name="employee_name"
                                                        class="form-control form-control-sm mb-2"
                                                        placeholder="Employee Name"
                                                        value="{{ $employee->employee_name }}" required>
                                                </div>
                                            </div>
                                        </div>
                                        {{-- <div class="col-12 col-md-4">
                                            <div class="card-body pt-0 pb-2">
                                                <div class="product fv-row fv-plugins-icon-container">
                                                    <label class="required form-label"> Employee Email </label>
                                                    <input type="email" name="email"
                                                        class="form-control form-control-sm mb-2"
                                                        placeholder="Employee Email" value="" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <div class="card-body pt-0 pb-2">
                                                <div class="product fv-row fv-plugins-icon-container">
                                                    <label class="required form-label"> Employee Password
                                                    </label>
                                                    <input type="password" name="password"
                                                        class="form-control form-control-sm mb-2"
                                                        placeholder="Employee Password" value=""
                                                        required>
                                                </div>
                                            </div>
                                        </div> --}}

                                        <div class="col-12 col-md-4">
                                            <div class="card-body pt-0 pb-2">
                                                <div class="product fv-row fv-plugins-icon-container">
                                                    <label class="form-label required">Gender</label>
                                                    <select class="form-select form-select-sm" data-control="select2" name="gender">
                                                        <option selected value=""
                                                            {{ $employee->gender == 'Null' ? 'selected' : '' }}>
                                                            Select Gender</option>
                                                        <option value="Male"
                                                            {{ $employee->gender == 'Male' ? 'selected' : '' }}>Male
                                                        </option>
                                                        <option value="Female"
                                                            {{ $employee->gender == 'Female' ? 'selected' : '' }}>
                                                            Female
                                                        </option>
                                                        <option value="Others"
                                                            {{ $employee->gender == 'Others' ? 'selected' : '' }}>
                                                            Others
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12 col-md-4">
                                            <div class="card-body pt-0 pb-2">
                                                <div class="product fv-row fv-plugins-icon-container">
                                                    <label class="form-label"> DOB </label>
                                                    <input type="date" name="dob"
                                                        class="form-control form-control-sm mb-2"
                                                        placeholder="Employee DOB" value="{{ $employee->dob }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <div class="card-body pt-0 pb-2">
                                                <div class="product fv-row fv-plugins-icon-container">
                                                    <label class="form-label">National ID</label>
                                                    <input type="text" name="nid"
                                                        class="form-control form-control-sm mb-2"
                                                        placeholder="National ID" value="{{ $employee->nid }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <div class="card-body pt-0 pb-2">
                                                <div class="product fv-row fv-plugins-icon-container">
                                                    <label class="form-label">Birth ID</label>
                                                    <input type="text" name="birth_id"
                                                        class="form-control form-control-sm mb-2"
                                                        placeholder="Birth ID" value="{{ $employee->birth_id }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <div class="card-body pt-0 pb-2">
                                                <div class="product fv-row fv-plugins-icon-container">
                                                    <label class="form-label">Blood Group</label>
                                                    <select class="form-select form-select-sm" data-control="select2" name="blood_group">
                                                        <option selected value=""
                                                            {{ $employee->blood_group == 'Null' ? 'selected' : '' }}>
                                                            Select Blood Group</option>
                                                        <option value="A+"
                                                            {{ $employee->blood_group == 'A+' ? 'selected' : '' }}>A+
                                                        </option>
                                                        <option value="A-"
                                                            {{ $employee->blood_group == 'A-' ? 'selected' : '' }}>A-
                                                        </option>
                                                        <option value="B+"
                                                            {{ $employee->blood_group == 'B+' ? 'selected' : '' }}>B+
                                                        </option>
                                                        <option value="B-"
                                                            {{ $employee->blood_group == 'B-' ? 'selected' : '' }}>B-
                                                        </option>
                                                        <option value="O+"
                                                            {{ $employee->blood_group == 'O+' ? 'selected' : '' }}>O+
                                                        </option>
                                                        <option value="O-"
                                                            {{ $employee->blood_group == 'O-' ? 'selected' : '' }}>O-
                                                        </option>
                                                        <option value="AB+"
                                                            {{ $employee->blood_group == 'AB+' ? 'selected' : '' }}>AB+
                                                        </option>
                                                        <option value="AB-"
                                                            {{ $employee->blood_group == 'AB-' ? 'selected' : '' }}>AB-
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12 col-md-4">
                                            <div class="card-body pt-0 pb-2">
                                                <div class="product fv-row fv-plugins-icon-container">
                                                    <label class="form-label">Marital Status</label>
                                                    <select class="form-select form-select-sm" data-control="select2" name="marital_status">
                                                        <option selected value=""
                                                            {{ $employee->gender == 'Null' ? 'selected' : '' }}>
                                                            Select Marital Status</option>
                                                        <option value="Unmarried"
                                                            {{ $employee->marital_status == 'Unmarried' ? 'selected' : '' }}>
                                                            Unmarried</option>
                                                        <option value="Married"
                                                            {{ $employee->marital_status == 'Married' ? 'selected' : '' }}>
                                                            Married</option>
                                                        <option value="Divorced"
                                                            {{ $employee->marital_status == 'Divorced' ? 'selected' : '' }}>
                                                            Divorced</option>
                                                        <option value="Single"
                                                            {{ $employee->marital_status == 'Single' ? 'selected' : '' }}>
                                                            Single</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <div class="card-body pt-0 pb-2">
                                                <div class="product fv-row fv-plugins-icon-container">
                                                    <label class="form-label"> Spouse/Husband</label>
                                                    <input type="text" name="spouse_name"
                                                        class="form-control form-control-sm mb-2"
                                                        placeholder="Employee Religion"
                                                        value="{{ $employee->spouse_name }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <div class="card-body pt-0 pb-2">
                                                <div class="product fv-row fv-plugins-icon-container">
                                                    <label class="form-label"> Religion </label>
                                                    <input type="text" name="religion"
                                                        class="form-control form-control-sm mb-2"
                                                        placeholder="Employee Religion"
                                                        value="{{ $employee->religion }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <div class="card-body pt-0 pb-2">
                                                <div class="product fv-row fv-plugins-icon-container">
                                                    <label class="form-label">Mobile Number One</label>
                                                    <input type="number" name="mobile"
                                                        value="{{ $employee->mobile }}"
                                                        class="form-control form-control-sm mb-2"
                                                        placeholder="Mobile Number"
                                                        oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <div class="card-body pt-0 pb-2">
                                                <div class="product fv-row fv-plugins-icon-container">
                                                    <label class="form-label">Mobile Number Two</label>
                                                    <input type="number" name="mobile_two"
                                                        value="{{ $employee->mobile_two }}"
                                                        class="form-control form-control-sm mb-2"
                                                        placeholder="Mobile Number"
                                                        oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <div class="card-body pt-0 pb-2">
                                                <div class="product fv-row fv-plugins-icon-container">
                                                    <label class="form-label">Emergency Contact Person</label>
                                                    <input type="text" name="emergency_contact_person"
                                                        class="form-control form-control-sm mb-2"
                                                        placeholder="Emergency Contact Person"
                                                        value="{{ $employee->emergency_contact_person }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <div class="card-body pt-0 pb-2">
                                                <div class="product fv-row fv-plugins-icon-container">
                                                    <label class="form-label">Emergency Contact Number</label>
                                                    <input type="text" name="emergency_contact_number"
                                                        class="form-control form-control-sm mb-2"
                                                        placeholder="Emergency Contact Number"
                                                        value="{{ $employee->emergency_contact_number }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <div class="card-body pt-0 pb-2">
                                                <div class="product fv-row fv-plugins-icon-container">
                                                    <label class="form-label">Father Name </label>
                                                    <input type="text" name="father_name"
                                                        class="form-control form-control-sm mb-2"
                                                        placeholder="Employee Father Name"
                                                        value="{{ $employee->father_name }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <div class="card-body pt-0 pb-2">
                                                <div class="product fv-row fv-plugins-icon-container">
                                                    <label class="form-label">Mother Name </label>
                                                    <input type="text" name="mother_name"
                                                        class="form-control form-control-sm mb-2"
                                                        placeholder="Employee Mother Name"
                                                        value="{{ $employee->mother_name }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <div class="card-body pt-0 pb-2">
                                                <div class="product fv-row fv-plugins-icon-container">
                                                    <label class="form-label">Present Address</label>
                                                    <input type="text" name="present_address"
                                                        class="form-control form-control-sm mb-2"
                                                        placeholder="Present Address"
                                                        value="{{ $employee->present_address }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <div class="card-body pt-0 pb-2">
                                                <div class="product fv-row fv-plugins-icon-container">
                                                    <label class="form-label">Permanent Address</label>
                                                    <input type="text" name="permanent_address"
                                                        class="form-control form-control-sm mb-2"
                                                        placeholder="Permanent Address"
                                                        value="{{ $employee->permanent_address }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <div class="card-body pt-0 pb-2">
                                                <div class="product fv-row fv-plugins-icon-container">
                                                    <label class="form-label">Highest Education</label>
                                                    <input type="text" name="highest_education"
                                                        class="form-control form-control-sm mb-2"
                                                        placeholder="Highest Education"
                                                        value="{{ $employee->highest_education }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <div class="card-body pt-0 pb-2">
                                                <div class="product fv-row fv-plugins-icon-container">
                                                    <label class="form-label"> Quit Date </label>
                                                    <input type="date" name="quite_date"
                                                        class="form-control form-control-sm mb-2"
                                                        placeholder="Quit Date" value="{{ $employee->quite_date }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <div class="card-body pt-0 pb-2">
                                                <div class="product fv-row fv-plugins-icon-container">
                                                    <label class="form-label">Quit Type</label>
                                                    <select class="form-select form-select-sm" data-control="select2" name="quite_type">
                                                        <option selected value="">Select Quit Type</option>
                                                        @foreach ($allEmpQuiteType as $key => $value)
                                                            <option value="{{ $key }}"
                                                                {{ $employee->quite_type == $key ? 'selected' : '' }}>
                                                                {{ $value }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <div class="card-body pt-0 pb-2">
                                                <div class="product fv-row fv-plugins-icon-container">
                                                    <label class="form-label">Employee Image</label>
                                                    <input type="file" name="photo"
                                                        class="form-control form-control-sm mb-2"
                                                        placeholder="Employee Image" value="">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <div class="card-body pt-0 pb-2">
                                                <div class="product fv-row fv-plugins-icon-container">
                                                    <label class="form-label">Signature</label>
                                                    <input type="file" name="signature"
                                                        class="form-control form-control-sm mb-2"
                                                        placeholder="Signature" value="">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <div class="card-body pt-0 pb-2">
                                                <div class="product fv-row fv-plugins-icon-container">
                                                    <label class="form-label required">Job Status</label>
                                                    <select class="form-select form-select-sm" data-control="select2" name="job_status"
                                                        required>
                                                        <option value=""
                                                            {{ $employee->job_status == 'Null' ? 'selected' : '' }}>Job Status</option>
                                                        <option value="Permanent"
                                                            {{ $employee->job_status == 'Permanent' ? 'selected' : '' }}>
                                                            Permanent</option>
                                                        <option value="Provisional"
                                                            {{ $employee->job_status == 'Provisional' ? 'selected' : '' }}>
                                                            Provisional</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-3">
                                            <div class="card-body py-5">
                                                <div class="product fv-row fv-plugins-icon-container">
                                                    <label class="form-check form-switch form-check-custom form-check-solid">
                                                        <input class="form-check-input w-30px h-20px" type="checkbox" value="1" name="status" {{ $employee->status==1 ? 'checked' : '' }}/>
                                                        <span class="form-check-label text-muted fs-6">Active</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-3">
                                            <div class="card-body py-5">
                                                <div class="product fv-row fv-plugins-icon-container">
                                                    <label class="form-check form-switch form-check-custom form-check-solid">
                                                        <input class="form-check-input w-30px h-20px" type="checkbox" value="1" name="salary_held_up" {{ $employee->salary_held_up==1 ? 'checked' : '' }}/>
                                                        <span class="form-check-label text-muted fs-6">Salary Held up</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        {{-- <div class="col-12 col-md-3">
                                            <div class="card-body py-5">
                                                <div class="product fv-row fv-plugins-icon-container">
                                                    <label class="form-check form-switch form-check-custom form-check-solid">
                                                        <input class="form-check-input w-30px h-20px" type="checkbox" value="1" name="Overtime" />
                                                        <span class="form-check-label text-muted fs-6">Overtime </span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-3">
                                            <div class="card-body py-5">
                                                <div class="product fv-row fv-plugins-icon-container">
                                                    <label class="form-check form-switch form-check-custom form-check-solid">
                                                        <input class="form-check-input w-30px h-20px" type="checkbox" value="1" name="attendance_bonus" />
                                                        <span class="form-check-label text-muted fs-6">Attendance Bonus </span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-3">
                                            <div class="card-body py-5">
                                                <div class="product fv-row fv-plugins-icon-container">
                                                    <label class="form-check form-switch form-check-custom form-check-solid">
                                                        <input class="form-check-input w-30px h-20px" type="checkbox" value="1" name="mobile_allow" />
                                                        <span class="form-check-label text-muted fs-6">Mobile Allow </span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-3">
                                            <div class="card-body py-5">
                                                <div class="product fv-row fv-plugins-icon-container">
                                                    <label class="form-check form-switch form-check-custom form-check-solid">
                                                        <input class="form-check-input w-30px h-20px" type="checkbox" value="1" name="night_allow" />
                                                        <span class="form-check-label text-muted fs-6">Night Allow </span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-3">
                                            <div class="card-body py-5">
                                                <div class="product fv-row fv-plugins-icon-container">
                                                    <label class="form-check form-switch form-check-custom form-check-solid">
                                                        <input class="form-check-input w-30px h-20px" type="checkbox" value="1" name="bus_using" />
                                                        <span class="form-check-label text-muted fs-6">Bus Using </span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-3">
                                            <div class="card-body py-5">
                                                <div class="product fv-row fv-plugins-icon-container">
                                                    <label class="form-check form-switch form-check-custom form-check-solid">
                                                        <input class="form-check-input w-30px h-20px" type="checkbox" value="1" name="provident_fund" />
                                                        <span class="form-check-label text-muted fs-6">Provident Fund </span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-3">
                                            <div class="card-body py-5">
                                                <div class="product fv-row fv-plugins-icon-container">
                                                    <label class="form-check form-switch form-check-custom form-check-solid">
                                                        <input class="form-check-input w-30px h-20px" type="checkbox" value="1" name="provident_fund" />
                                                        <span class="form-check-label text-muted fs-6">PF Active For Actual </span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-3">
                                            <div class="card-body py-5">
                                                <div class="product fv-row fv-plugins-icon-container">
                                                    <label class="form-check form-switch form-check-custom form-check-solid">
                                                        <input class="form-check-input w-30px h-20px" type="checkbox" value="1" name="income_tax" />
                                                        <span class="form-check-label text-muted fs-6">Income Tax </span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-3">
                                            <div class="card-body py-5">
                                                <div class="product fv-row fv-plugins-icon-container">
                                                    <label class="form-check form-switch form-check-custom form-check-solid">
                                                        <input class="form-check-input w-30px h-20px" type="checkbox" value="1" name="bank_payable" />
                                                        <span class="form-check-label text-muted fs-6">Bank Payable</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-3">
                                            <div class="card-body py-5">
                                                <div class="product fv-row fv-plugins-icon-container">
                                                    <label class="form-check form-switch form-check-custom form-check-solid">
                                                        <input class="form-check-input w-30px h-20px" type="checkbox" value="1" name="salary_held_up" />
                                                        <span class="form-check-label text-muted fs-6">Salary Held up</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div> --}}

                                    </div>
                                </div>
                                <!--end::General options-->

                                <div class="d-flex justify-content-end my-5">
                                    <!--begin::Button-->
                                    <a href="{{ route('employees.index') }}" id="kt_ecommerce_add_product_cancel"
                                        class="btn btn-sm btn-success me-5">Cancel</a>
                                    <!--end::Button-->
                                    <!--begin::Button-->
                                    <button type="submit" id="kt_ecommerce_add_category_submit"
                                        class="btn btn-sm btn-primary">
                                        <span class="indicator-label">Save</span>
                                        <span class="indicator-progress">Please wait...
                                            <span
                                                class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                    </button>
                                    <!--end::Button-->
                                </div>
                            </div>
                            <!--end::Main column-->
                        </form>
                        <!--end::Form-->
                    </div>
                    <!--end::Card body-->
                </div>
                <!--end::Card-->
            </div>
            <!--end:::Tab pane-->

            <!---------------------------- begin::: Academic employee_education Tab pane ----------------------->
            <div class="tab-pane fade" id="kt_ecommerce_customer_general" role="tabpanel">
                <!--begin::Card-->
                <div class="card pt-4 mb-6 mb-xl-9">
                    <!--begin::Card header-->
                    <div class="card-header border-0">
                        <!--begin::Card title-->
                        <div class="card-title">
                            <h2>Academic Details Add</h2>
                        </div>
                        <!--end::Card title-->
                    </div>
                    <!--end::Card header-->
                    <!--begin::Card body-->
                    <div class="card-body pt-0 pb-5">
                        <!--begin::Form-->
                        <form action="{{ route('employee_education.store') }}" class="form" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <input type="hidden" name="employee_id" value="{{ $employee->id }}">
                                <div class="col-12 col-md-2">
                                    <label class="required form-label"> Exam </label>
                                    <input type="text" name="exam" class="form-control form-control-sm mb-2"
                                        placeholder="Exam" required>
                                </div>
                                <div class="col-12 col-md-4">
                                    <label class="required form-label"> Institution </label>
                                    <input type="text" name="institution"
                                        class="form-control form-control-sm mb-2" placeholder="Institution" required>
                                </div>
                                <div class="col-12 col-md-2">
                                    <label class="required form-label"> Passing Year </label>
                                    <input type="date" name="passingyear"
                                        class="form-control form-control-sm mb-2" placeholder="Employee Code"
                                        required>
                                </div>
                                <div class="col-12 col-md-2">
                                    <label class="required form-label"> Result </label>
                                    <input type="text" name="result" class="form-control form-control-sm mb-2"
                                        placeholder="Result" required>
                                </div>
                                <div class="col-12 col-md-2 pt-5">
                                    <button type="submit" id="kt_ecommerce_customer_profile_submit"
                                        class="btn btn-sm btn-light-primary mt-3">
                                        <span class="indicator-label">Insert</span>
                                        <span class="indicator-progress">Please wait...
                                            <span
                                                class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                    </button>
                                </div>
                            </div>
                        </form>
                        <!--end::Form-->
                    </div>
                    <!--end::Card body-->
                </div>
                <!--end::Card-->
                <!--begin::Card-->
                <div class="card pt-4 mb-6 mb-xl-9">
                    <!--begin::Card header-->
                    <div class="card-header border-0">
                        <!--begin::Card title-->
                        <div class="card-title">
                            <h2>Academic Details Update</h2>
                        </div>
                        <!--end::Card title-->
                    </div>
                    <!--end::Card header-->
                    <!--begin::Card body-->
                    <div class="card-body pt-0 pb-5">
                        <!--begin::Form-->
                        <form action="{{ route('employee_education.update', $employee->id) }}" class="form"
                            method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                @foreach ($empEducations as $empEducation)
                                    <input type="hidden" name="education_id[]" value="{{ $empEducation->id }}">
                                    <div class="col-12 col-md-2">
                                        <label class="required form-label"> Exam </label>
                                        <input type="text" name="exam[]"
                                            class="form-control form-control-sm mb-2" placeholder="Exam"
                                            value="{{ $empEducation->exam }}">
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <label class="required form-label"> Institution </label>
                                        <input type="text" name="institution[]"
                                            class="form-control form-control-sm mb-2" placeholder="Institution"
                                            value="{{ $empEducation->institution }}">
                                    </div>
                                    <div class="col-12 col-md-2">
                                        <label class="required form-label"> Passing Year </label>
                                        <input type="date" name="passingyear[]"
                                            class="form-control form-control-sm mb-2" placeholder="Employee Code"
                                            value="{{ $empEducation->passingyear }}">
                                    </div>
                                    <div class="col-12 col-md-2">
                                        <label class="required form-label"> Result </label>
                                        <input type="text" name="result[]"
                                            class="form-control form-control-sm mb-2"
                                            placeholder="Result"value="{{ $empEducation->result }}">
                                    </div>
                                    <div class="col-12 col-md-2 pt-5">
                                        <button type="submit" id="kt_ecommerce_customer_profile_submit"
                                            class="btn btn-sm btn-light-success mt-3">
                                            <span class="indicator-label">Update</span>
                                            <span class="indicator-progress">Please wait...
                                                <span
                                                    class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        </form>
                        <!--end::Form-->
                    </div>
                    <!--end::Card body-->
                </div>
                <!--end::Card-->
            </div>
            <!--end:::Tab pane-->

            <!--begin::: employee_job_history Tab pane-->
            <div class="tab-pane fade" id="kt_ecommerce_customer_advanced" role="tabpanel">
                <!--begin::Card-->
                <div class="card pt-4 mb-6 mb-xl-9">
                    <!--begin::Card header-->
                    <div class="card-header border-0">
                        <!--begin::Card title-->
                        <div class="card-title">
                            <h2>Employee Job History</h2>
                        </div>
                        <!--end::Card title-->
                    </div>
                    <!--end::Card header-->
                    <!--begin::Card body-->
                    <div class="card-body pt-0 pb-5">
                        <!--begin::Form-->
                        <form action="{{ route('employee_job_history.store') }}" class="form" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="employee_id" value="{{ $employee->id }}">
                            <div class="row">

                                <div class="col-12 col-md-2">
                                    <label class="required form-label"> Company Name </label>
                                    <input type="text" name="company_name"
                                        class="form-control form-control-sm mb-2" placeholder="Company Name" required>
                                </div>
                                <div class="col-12 col-md-4">
                                    <label class="required form-label"> Designation </label>
                                    <input type="text" name="designation"
                                        class="form-control form-control-sm mb-2" placeholder="Designation" required>
                                </div>
                                <div class="col-12 col-md-2">
                                    <label class="required form-label"> Start Date </label>
                                    <input type="date" name="start_date" class="form-control form-control-sm mb-2"
                                        placeholder="Start Date" required>
                                </div>
                                <div class="col-12 col-md-2">
                                    <label class="required form-label"> End Date </label>
                                    <input type="date" name="end_date" class="form-control form-control-sm mb-2"
                                        placeholder="End Date" required>
                                </div>
                                <div class="col-12 col-md-2 pt-5">
                                    <button type="submit" id="kt_ecommerce_customer_profile_submit"
                                        class="btn btn-sm mt-3 btn-light-primary">
                                        <span class="indicator-label">Insert</span>
                                        <span class="indicator-progress">Please wait...
                                            <span
                                                class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                    </button>
                                </div>
                            </div>

                        </form>
                        <!--end::Form-->
                    </div>
                    <!--end::Card body-->
                </div>
                <!--end::Card-->
                <!--begin::Card-->
                <div class="card pt-4 mb-6 mb-xl-9">
                    <!--begin::Card header-->
                    <div class="card-header border-0">
                        <!--begin::Card title-->
                        <div class="card-title">
                            <h2>Employee Job History Update</h2>
                        </div>
                        <!--end::Card title-->
                    </div>
                    <!--end::Card header-->
                    <!--begin::Card body-->
                    <div class="card-body pt-0 pb-5">
                        <!--begin::Form-->
                        <form action="{{ route('employee_job_history.update', $employee->id) }}" class="form"
                            method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                @foreach ($employeeJobHistorys as $employeeJobHistory)
                                    <input type="hidden" name="job_history_id[]"
                                        value="{{ $employeeJobHistory->id }}">
                                    <div class="col-12 col-md-2">
                                        <label class="required form-label"> Company Name </label>
                                        <input type="text" name="company_name[]"
                                            class="form-control form-control-sm mb-2" placeholder="Exam"
                                            value="{{ $employeeJobHistory->company_name }}">
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <label class="required form-label"> Designation </label>
                                        <input type="text" name="designation[]"
                                            class="form-control form-control-sm mb-2" placeholder="Institution"
                                            value="{{ $employeeJobHistory->designation }}">
                                    </div>
                                    <div class="col-12 col-md-2">
                                        <label class="required form-label"> Start Date </label>
                                        <input type="date" name="start_date[]"
                                            class="form-control form-control-sm mb-2" placeholder="Employee Code"
                                            value="{{ $employeeJobHistory->start_date }}">
                                    </div>
                                    <div class="col-12 col-md-2">
                                        <label class="required form-label"> End Date </label>
                                        <input type="date" name="end_date[]"
                                            class="form-control form-control-sm mb-2"
                                            placeholder="Result"value="{{ $employeeJobHistory->end_date }}">
                                    </div>
                                    <div class="col-12 col-md-2 pt-5">
                                        <button type="submit" id="kt_ecommerce_customer_profile_submit"
                                            class="btn btn-sm mt-3 btn-light-success">
                                            <span class="indicator-label">Update</span>
                                            <span class="indicator-progress">Please wait...
                                                <span
                                                    class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                        </button>
                                    </div>
                                @endforeach
                            </div>

                        </form>
                        <!--end::Form-->
                    </div>
                    <!--end::Card body-->
                </div>
                <!--end::Card-->
            </div>
            <!--end:::Tab pane-->

            <!--begin::: employee_job_responsibility Tab pane-->
            <div class="tab-pane fade" id="job_responsibility" role="tabpanel">
                <!--begin::Card-->
                <div class="card pt-4 mb-6 mb-xl-9">
                    <!--begin::Card header-->
                    <div class="card-header border-0">
                        <!--begin::Card title-->
                        <div class="card-title">
                            <h2>Employee Job Responsibility</h2>
                        </div>
                        <!--end::Card title-->
                    </div>
                    <!--end::Card header-->
                    <!--begin::Card body-->
                    <div class="card-body pt-0 pb-5">
                        <!--begin::Form-->
                        <form action="{{ route('employee_job_responsibility.store') }}" class="form"
                            method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="employee_id" value="{{ $employee->id }}">
                            <div class="row">
                                <div class="col-12 col-md-10 form-floating">
                                    <textarea class="form-control" placeholder="Job Responsibility" id="floatingTextarea2" name="job_responsibility"
                                        style="height: 100px"></textarea>
                                    <label for="floatingTextarea2">Job Responsibility</label>
                                </div>
                                {{-- <div class="col-12 col-md-2">
                                    <label class="required form-label"> Start Date </label>
                                    <input type="date" name="start_date" class="form-control form-control-sm mb-2"
                                        placeholder="Start Date">
                                </div>
                                <div class="col-12 col-md-2">
                                    <label class="form-label"> End Date </label>
                                    <input type="date" name="end_date" class="form-control form-control-sm mb-2"
                                        placeholder="End Date">
                                </div> --}}
                                <div class="col-12 col-md-2 pt-5">
                                    <button type="submit" id="kt_ecommerce_customer_profile_submit"
                                        class="btn btn-sm mt-3 btn-light-primary">
                                        <span class="indicator-label">Insert</span>
                                        <span class="indicator-progress">Please wait...
                                            <span
                                                class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                    </button>
                                </div>
                            </div>

                        </form>
                        <!--end::Form-->
                    </div>
                    <!--end::Card body-->
                </div>
                <!--end::Card-->

                <!--begin::Card-->
                <div class="card pt-4 mb-6 mb-xl-9">
                    <!--begin::Card header-->
                    <div class="card-header border-0">
                        <!--begin::Card title-->
                        <div class="card-title">
                            <h2>Employee Job Responsibility Update</h2>
                        </div>
                        <!--end::Card title-->
                    </div>
                    <!--end::Card header-->
                    <!--begin::Card body-->
                    <div class="card-body pt-0 pb-5">
                        <!--begin::Form-->
                        <form action="{{ route('employee_job_responsibility.update', $employee->id) }}"
                            class="form" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                @foreach ($employeeJobResponsibilities as $employeeJobResponsibility)
                                    <input type="hidden" name="responsibility_id[]"
                                        value="{{ $employeeJobResponsibility->id }}">
                                    <div class="col-12 col-md-10">
                                        <label class="required form-label"> Job Responsibility </label>
                                        <textarea name="job_responsibility[]" class="form-control form-control-sm" placeholder="job_responsibility">{{ $employeeJobResponsibility->job_responsibility }}</textarea>
                                    </div>
                                    {{-- <div class="col-12 col-md-2">
                                    <label class="required form-label"> Start Date </label>
                                    <input type="date" name="start_date[]" class="form-control form-control-sm mb-2"
                                        placeholder="Employee Code" value="{{ $employeeJobResponsibility->start_date }}">
                                </div>
                                <div class="col-12 col-md-2">
                                    <label class="required form-label"> End Date </label>
                                    <input type="date" name="end_date[]" class="form-control form-control-sm mb-2"
                                        placeholder="Result"value="{{ $employeeJobResponsibility->end_date }}">
                                </div> --}}

                                    <div class="col-12 col-md-2 pt-10">
                                        <button type="submit" id="kt_ecommerce_customer_profile_submit"
                                            class="btn btn-sm mt-3 btn-light-success">
                                            <span class="indicator-label">Update</span>
                                            <span class="indicator-progress">Please wait...
                                                <span
                                                    class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                        </button>
                                    </div>
                                @endforeach
                            </div>

                        </form>
                        <!--end::Form-->
                    </div>
                    <!--end::Card body-->
                </div>
                <!--end::Card-->
            </div>
            <!--end::: employee_job_responsibility Tab pane-->
        </div>
        <!--end:::Tab content-->



    </div>
</x-default-layout>
<script type="text/javascript">
    $('#changeBranch').on('change', function() {
        var branchID = $(this).val();
        var url = '{{ route('branchDepartment', ':branchID') }}';
        url = url.replace(':branchID', branchID);
        console.log(url);
        if (branchID) {
            $.ajax({
                url: url,
                type: "GET",
                data: {
                    "_token": "{{ csrf_token() }}"
                },
                dataType: "json",
                success: function(data) {
                    $('#changeDepartment').empty();

                    $('#changeDepartment').append(
                        `<option selected disabled>Select Department</option>`
                    );
                    if (data) {
                        $.each(data, function(key, value) {
                            $('#changeDepartment').append(
                                `<option value="` + value.id + `">` + value
                                .department_name +
                                `</option>`
                            );
                        });
                    } else {
                        $('#jsdataerror').text('Not Found');
                    }
                }
            });
        } else {
            $('#jsdataerror').text('Not Found');
        }
    });
    $(function() {
        $('a[data-bs-toggle="tab"]').on('show.bs.tab', function(e) {
            localStorage.setItem('activeTab', $(e.target).attr('href'));
        });
        var activeTab = localStorage.getItem('activeTab');
        if (activeTab) {
            $('#myTabjustified a[href="' + activeTab + '"]').tab('show');
        }
    });
</script>

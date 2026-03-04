<x-default-layout>

    <!--begin::Content-->
    <div class="flex-lg-row-fluid ms-lg-15">
        <!--begin:::Tabs-->
        <!--        <ul class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-semibold mb-8">
                    begin:::Tab item
                    <li class="nav-item">
                        <a class="nav-link text-active-primary pb-4 active" data-bs-toggle="tab" href="#kt_ecommerce_customer_overview">Personal Details</a>
                    </li>
                    end:::Tab item
                    begin:::Tab item
                    <li class="nav-item">
                        <a class="nav-link text-active-primary pb-4" data-bs-toggle="tab" href="#kt_ecommerce_customer_general">Education</a>
                    </li>
                    end:::Tab item
                    begin:::Tab item
                    <li class="nav-item">
                        <a class="nav-link text-active-primary pb-4" data-bs-toggle="tab" href="#kt_ecommerce_customer_advanced">Job History</a>
                    </li>
                    end:::Tab item
                </ul>-->
        <!--end:::Tabs-->

        <!--begin:::Tab content-->
        <div class="tab-content" id="myTabContent">
            <!--begin:::Tab pane-->
            <div class="tab-pane fade show active" id="kt_ecommerce_customer_overview" role="tabpanel">


                <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
                    <!--begin::Content wrapper-->
                    <div class="d-flex flex-column flex-column-fluid">

                        <!--begin::Toolbar-->
                        <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
                            <!--begin::Toolbar container-->
                            <div id="kt_app_toolbar_container" class="app-container d-flex flex-stack">
                                <!--begin::Page title-->
                                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                                    <!--begin::Title-->
                                    <h1
                                        class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                                        Add Employee</h1>
                                </div>
                                <!--end::Page title-->
                            </div>
                            <!--end::Toolbar container-->
                        </div>
                        <!--end::Toolbar-->

                        <!--begin::Content-->
                        <div id="kt_app_content" class="app-content flex-column-fluid">
                            <!--begin::Content container-->
                            <div id="kt_app_content_container" class="app-container">
                                <form id="kt_ecommerce_add_category_form"
                                    class="form d-flex flex-column flex-lg-row fv-plugins-bootstrap5 fv-plugins-framework"
                                    method="POST" action="{{ route('employees.store') }}"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <!-- @method('PUT') -->
                                    <!--begin::Aside column-->
                                    <div class="col-12 col-md-3 me-lg-10">
                                        <!--begin::Status-->
                                        <div class="card card-flush py-4">
                                            <div class="card-body pt-0 pb-5">
                                                <label class="required form-label">Select Branch </label>
                                                <select class="form-select form-select-sm" id="changeBranch" name="branch_id" required>
                                                    <option selected value="" >--Select Branch--</option>
                                                    @foreach ($allEmpBranch as $key => $value)
                                                        <option value="{{ $key }}">{{ $value }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="card-body pt-0 pb-5">
                                                <label class="required form-label">Employee Department</label>
                                                <select class="form-select form-select-sm" id="changeDepartment" name="department_id" required>
                                                    <option selected value="" >--Select Employee Department--</option>
                                                </select>
                                            </div>
                                            <div class="card-body pt-0 pb-5">
                                                <label class="required form-label">Employee Designation</label>
                                                <select class="form-select form-select-sm" name="designation_id"
                                                    required>
                                                    <option selected value="" >--Select Employee Designation--</option>
                                                    @foreach ($allEmpDesignation as $key => $value)
                                                        <option value="{{ $key }}">{{ $value }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="card-body pt-0 pb-5">
                                                <div class="product fv-row fv-plugins-icon-container">
                                                    <label class="form-label required">Job Status</label>
                                                    <select class="form-select form-select-sm" name="job_status" required>
                                                        <option selected value="" >--Select Job Status--</option>
                                                        <option value="Permanent">Permanent </option>
                                                        <option value="Provisional">Provonsional </option>
                                                    </select>

                                                    {{-- <input type="text" name="job_status"
                                                        class="form-control form-control-sm mb-2"
                                                        placeholder="Job Status" value=""> --}}
                                                </div>
                                            </div>
                                            <div class="card-body pt-0 pb-5">
                                                <div class="product fv-row fv-plugins-icon-container">
                                                    <label class="form-label"> Joining Date </label>
                                                    <input type="date" name="joining_date"
                                                        class="form-control form-control-sm mb-2"
                                                        placeholder="Joining Date" value="">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card card-flush my-5">
                                            <div class="card-header">
                                                <div class="card-title">
                                                    <h4>Order</h4>
                                                </div>
                                                <div class="card-toolbar">
                                                    <div class="rounded-circle bg-success w-15px h-15px"
                                                        id="kt_ecommerce_add_category_status"></div>
                                                </div>
                                            </div>
                                            <div class="card-body pt-0 pb-5">
                                                <input type="number" name="order" class="form-control form-control-sm mb-2" placeholder="Input Order" value="1" min="0">
                                            </div>
                                        </div>
                                        <div class="card card-flush my-5">
                                            <div class="card-header">
                                                <div class="card-title">
                                                    <h4>Status</h4>
                                                </div>
                                                <div class="card-toolbar">
                                                    <div class="rounded-circle bg-success w-15px h-15px"
                                                        id="kt_ecommerce_add_category_status"></div>
                                                </div>
                                            </div>
                                            <div class="card-body pt-0 pb-5">
                                                <select class="form-select form-select-sm" name="status" required>
                                                    <option value="">--Select Status--</option>
                                                    <option value="1" selected>Active</option>
                                                    <option value="0">Disable</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <!--end::Aside column-->
                                    <!--begin::Main column-->
                                    <div class="col-12 col-md-9 me-lg-10">
                                        <!--begin::General options-->
                                        <div class="card card-flush py-4">
                                            <div class="row">
                                                <div class="col-12 col-md-4">
                                                    <div class="card-body pt-0 pb-2">
                                                        <div class=" fv-row fv-plugins-icon-container">
                                                            <label class="required form-label"> Employee Code </label>
                                                            <input type="text" name="employee_code"
                                                                class="form-control form-control-sm mb-2"
                                                                placeholder="Employee Code" value="" required>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-4">
                                                    <div class="card-body pt-0 pb-2">
                                                        <div class="product fv-row fv-plugins-icon-container">
                                                            <label class="required form-label"> Employee Name </label>
                                                            <input type="text" name="employee_name"
                                                                class="form-control form-control-sm mb-2"
                                                                placeholder="Employee Name" value="" required>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-4">
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
                                                            <label class="required form-label"> Employee Password </label>
                                                            <input type="password" name="password"
                                                                class="form-control form-control-sm mb-2"
                                                                placeholder="Employee Password" value="" required>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                
                                                <div class="col-12 col-md-4">
                                                    <div class="card-body pt-0 pb-2">
                                                        <div class="product fv-row fv-plugins-icon-container">
                                                            <label class="form-label required">Gender</label>
                                                            <select class="form-select form-select-sm" name="gender" required>
                                                                <option selected value="" >--Select Gender--</option>
                                                                <option value="Male">Male</option>
                                                                <option value="Female">Female</option>
                                                                <option value="Others">Others</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-4">
                                                    <div class="card-body pt-0 pb-2">
                                                        <div class="product fv-row fv-plugins-icon-container">
                                                            <label class="form-label">Blood Group</label>
                                                            <select class="form-select form-select-sm"
                                                                name="blood_group">
                                                                <option selected value="">--Select Blood Group--
                                                                </option>
                                                                <option value="A+">A+</option>
                                                                <option value="A-">A-</option>
                                                                <option value="B+">B+</option>
                                                                <option value="B-">B-</option>
                                                                <option value="O+">O+</option>
                                                                <option value="O-">O-</option>
                                                                <option value="AB+">AB+</option>
                                                                <option value="AB-">AB-</option>
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
                                                                placeholder="Employee DOB" value="">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-4">
                                                    <div class="card-body pt-0 pb-2">
                                                        <div class="product fv-row fv-plugins-icon-container">
                                                            <label class="form-label">National ID</label>
                                                            <input type="text" name="nid"
                                                                class="form-control form-control-sm mb-2"
                                                                placeholder="National ID" value="">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-4">
                                                    <div class="card-body pt-0 pb-2">
                                                        <div class="product fv-row fv-plugins-icon-container">
                                                            <label class="form-label">Birth ID</label>
                                                            <input type="text" name="birth_id"
                                                                class="form-control form-control-sm mb-2"
                                                                placeholder="Birth ID" value="">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-4">
                                                    <div class="card-body pt-0 pb-2">
                                                        <div class="product fv-row fv-plugins-icon-container">
                                                            <label class="form-label"> Religion </label>
                                                            <input type="text" name="religion"
                                                                class="form-control form-control-sm mb-2"
                                                                placeholder="Employee Religion" value="">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-4">
                                                    <div class="card-body pt-0 pb-2">
                                                        <div class="product fv-row fv-plugins-icon-container">
                                                            <label class="form-label">Marital Status</label>
                                                            <select class="form-select form-select-sm"
                                                                name="marital_status">
                                                                <option selected value="">--Select Marital Status--
                                                                </option>
                                                                <option value="Unmarried">Unmarried</option>
                                                                <option value="Married">Married</option>
                                                                <option value="Divorced">Divorced </option>
                                                                <option value="Single">Single</option>
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
                                                                placeholder="Employee Religion" value="">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-4">
                                                    <div class="card-body pt-0 pb-2">
                                                        <div class="product fv-row fv-plugins-icon-container">
                                                            <label class="form-label">Mobile Number One</label>
                                                            <input type="number" name="mobile" class="form-control form-control-sm mb-2" placeholder="Mobile Number" oninput="this.value = this.value.replace(/[^0-9]/g, '');">

                                                            {{-- <input type="number" name="mobile"
                                                                class="form-control form-control-sm mb-2"
                                                                placeholder="Mobile Number" value=""> --}}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-4">
                                                    <div class="card-body pt-0 pb-2">
                                                        <div class="product fv-row fv-plugins-icon-container">
                                                            <label class="form-label">Mobile Number Two</label>
                                                            <input type="number" name="mobile_two" class="form-control form-control-sm mb-2" placeholder="Mobile Number" oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                                                        </div>
                                                    </div>
                                                </div>
                                                {{-- <div class="col-12 col-md-4">
                                                    <div class="card-body pt-0 pb-2">
                                                        <div class="product fv-row fv-plugins-icon-container">
                                                            <label class="form-label"> Highest Education </label>
                                                            <input type="text" name="highest_education"
                                                                class="form-control form-control-sm mb-2"
                                                                placeholder="Highest Education" value="">
                                                        </div>
                                                    </div>
                                                </div> --}}
                                                <div class="col-12 col-md-4">
                                                    <div class="card-body pt-0 pb-2">
                                                        <div class="product fv-row fv-plugins-icon-container">
                                                            <label class="form-label">Emergency Contact Person</label>
                                                            <input type="text" name="emergency_contact_person"
                                                                class="form-control form-control-sm mb-2"
                                                                placeholder="Emergency Contact Person" value="">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-4">
                                                    <div class="card-body pt-0 pb-2">
                                                        <div class="product fv-row fv-plugins-icon-container">
                                                            <label class="form-label">Emergency Contact Number</label>
                                                            <input type="text" name="emergency_contact_number"
                                                                class="form-control form-control-sm mb-2"
                                                                placeholder="Emergency Contact Number" value="">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-4">
                                                    <div class="card-body pt-0 pb-2">
                                                        <div class="product fv-row fv-plugins-icon-container">
                                                            <label class="form-label">Father Name </label>
                                                            <input type="text" name="father_name"
                                                                class="form-control form-control-sm mb-2"
                                                                placeholder="Employee Father Name" value="">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-4">
                                                    <div class="card-body pt-0 pb-2">
                                                        <div class="product fv-row fv-plugins-icon-container">
                                                            <label class="form-label">Mother Name </label>
                                                            <input type="text" name="mother_name"
                                                                class="form-control form-control-sm mb-2"
                                                                placeholder="Employee Mother Name" value="">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-4">
                                                    <div class="card-body pt-0 pb-2">
                                                        <div class="product fv-row fv-plugins-icon-container">
                                                            <label class="form-label">Present Address</label>
                                                            <input type="text" name="present_address"
                                                                class="form-control form-control-sm mb-2"
                                                                placeholder="Present Address" value="">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-4">
                                                    <div class="card-body pt-0 pb-2">
                                                        <div class="product fv-row fv-plugins-icon-container">
                                                            <label class="form-label">Permanent Address</label>
                                                            <input type="text" name="permanent_address"
                                                                class="form-control form-control-sm mb-2"
                                                                placeholder="Permanent Address" value="">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-4">
                                                    <div class="card-body pt-0 pb-2">
                                                        <div class="product fv-row fv-plugins-icon-container">
                                                            <label class="form-label">Highest Education</label>
                                                            <input type="text" name="highest_education"
                                                                class="form-control form-control-sm mb-2"
                                                                placeholder="Highest Education" value="">
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                {{-- <div class="col-12 col-md-4">
                                                    <div class="card-body pt-0 pb-2">
                                                        <div class="product fv-row fv-plugins-icon-container">
                                                            <label class="form-label">Salary</label>
                                                            <input type="number" name="salary"
                                                                class="form-control form-control-sm mb-2"
                                                                placeholder="Salary" value="">
                                                        </div>
                                                    </div>
                                                </div> --}}
                                                
                                                {{-- <div class="col-12 col-md-4">
                                                    <div class="card-body pt-0 pb-2">
                                                        <div class="product fv-row fv-plugins-icon-container">
                                                            <label class="form-label">Reference One Name</label>
                                                            <input type="text" name="reference_one_name"
                                                                class="form-control form-control-sm mb-2"
                                                                placeholder="Reference One Name" value="">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-4">
                                                    <div class="card-body pt-0 pb-2">
                                                        <div class="product fv-row fv-plugins-icon-container">
                                                            <label class="form-label">Reference One Phone</label>
                                                            <input type="text" name="reference_one_phone"
                                                                class="form-control form-control-sm mb-2"
                                                                placeholder="Reference One Phone" value="">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-4">
                                                    <div class="card-body pt-0 pb-2">
                                                        <div class="product fv-row fv-plugins-icon-container">
                                                            <label class="form-label">Reference One Address</label>
                                                            <input type="text" name="reference_one_address"
                                                                class="form-control form-control-sm mb-2"
                                                                placeholder="Reference One Address" value="">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-4">
                                                    <div class="card-body pt-0 pb-2">
                                                        <div class="product fv-row fv-plugins-icon-container">
                                                            <label class="form-label">Reference Two Name</label>
                                                            <input type="text" name="reference_two_name"
                                                                class="form-control form-control-sm mb-2"
                                                                placeholder="Reference Two Name" value="">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-4">
                                                    <div class="card-body pt-0 pb-2">
                                                        <div class="product fv-row fv-plugins-icon-container">
                                                            <label class="form-label">Reference Two Phone</label>
                                                            <input type="text" name="reference_two_phone"
                                                                class="form-control form-control-sm mb-2"
                                                                placeholder="Reference Two Phone" value="">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-4">
                                                    <div class="card-body pt-0 pb-2">
                                                        <div class="product fv-row fv-plugins-icon-container">
                                                            <label class="form-label">Reference Two Address</label>
                                                            <input type="text" name="reference_two_address"
                                                                class="form-control form-control-sm mb-2"
                                                                placeholder="Reference Two Address" value="">
                                                        </div>
                                                    </div>
                                                </div> --}}
                                                {{-- <div class="col-12 col-md-4">
                                                    <div class="card-body pt-0 pb-2">
                                                        <div class="product fv-row fv-plugins-icon-container">
                                                            <label class="form-label">Job Status</label>
                                                            <input type="text" name="job_status"
                                                                class="form-control form-control-sm mb-2"
                                                                placeholder="Job Status" value="">
                                                        </div>
                                                    </div>
                                                </div> --}}
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

                                            </div>
                                        </div>
                                        <!--end::General options-->

                                        <div class="d-flex justify-content-end my-5">
                                            <!--begin::Button-->
                                            <a href="{{ route('employees.index') }}"
                                                id="kt_ecommerce_add_product_cancel"
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
                            </div>
                            <!--end::Content container-->
                        </div>
                        <!--end::Content-->
                    </div>
                    <!--end::Content wrapper-->
                </div>
            </div>
            <!--end:::Tab pane-->

            <!--begin:::Tab pane-->
            <div class="tab-pane fade" id="kt_ecommerce_customer_general" role="tabpanel">
                <!--begin::Card-->
                <div class="card pt-4 mb-6 mb-xl-9">
                    <!--begin::Card header-->
                    <div class="card-header border-0">
                        <!--begin::Card title-->
                        <div class="card-title">
                            <h2>Academic Details</h2>
                        </div>
                        <!--end::Card title-->
                    </div>
                    <!--end::Card header-->
                    <!--begin::Card body-->
                    <div class="card-body pt-0 pb-5">
                        <!--begin::Form-->
                        <form action="{{ route('employee_education.store') }}"
                            class="form d-flex flex-column flex-lg-row fv-plugins-bootstrap5 fv-plugins-framework"
                            method="POST" enctype="multipart/form-data">

                            @csrf
                            <div class="row">
                                <div class="col-12 col-md-3">
                                    <div class="card-body pt-0 pb-2">
                                        <div class=" fv-row fv-plugins-icon-container">
                                            <label class="required form-label"> Exam </label>
                                            <input type="text" name="exam"
                                                class="form-control form-control-sm mb-2" placeholder="Exam">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-5">
                                    <div class="card-body pt-0 pb-2">
                                        <div class="product fv-row fv-plugins-icon-container">
                                            <label class="required form-label"> Institution </label>
                                            <input type="text" name="institution"
                                                class="form-control form-control-sm mb-2" placeholder="Institution">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-2">
                                    <div class="card-body pt-0 pb-2">
                                        <div class=" fv-row fv-plugins-icon-container">
                                            <label class="required form-label"> Passing Year </label>
                                            <input type="date" name="passingyear"
                                                class="form-control form-control-sm mb-2" placeholder="Employee Code">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-2">
                                    <div class="card-body pt-0 pb-2">
                                        <div class=" fv-row fv-plugins-icon-container">
                                            <label class="required form-label"> Result </label>
                                            <input type="text" name="result"
                                                class="form-control form-control-sm mb-2" placeholder="Result">
                                        </div>
                                    </div>
                                </div>


                                <div class="d-flex justify-content-end">
                                    <!--begin::Button-->
                                    <button type="submit" id="kt_ecommerce_customer_profile_submit"
                                        class="btn btn-light-primary">
                                        <span class="indicator-label">Save</span>
                                        <span class="indicator-progress">Please wait...
                                            <span
                                                class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                    </button>
                                    <!--end::Button-->
                                </div>
                            </div>
                        </form>
                        <!--end::Form-->
                    </div>
                    <!--end::Card body-->
                </div>
                <!--end::Card-->

            </div>
            <!--end:::Tab pane-->

        </div>
        <!--end:::Tab content-->


</x-default-layout>

<script type="text/javascript">
    $('#changeBranch').on('change', function() {
        var branchID = $(this).val();
        var url = '{{route("branchDepartment",':branchID')}}';
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
                        `<option selected value="" >--Select Employee Department--</option>`
                    );
                    if (data) {
                        $.each(data, function(key, value) {
                            $('#changeDepartment').append(
                                `<option value="` + value.id + `">` + value.department_name +
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

</script>
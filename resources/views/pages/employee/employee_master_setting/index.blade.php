<x-default-layout>
    <style>
        .table> :not(caption)>*>* {
            padding: 0.3rem !important;
        }
    </style>

    <div class="row">
        <!--begin::Message Content-->
        <div class="col-xl-12 px-5">
            @if ($errors->any())
                <div class="alert alert-danger mx-2">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if (session('message'))
                <div class="alert alert-{{ session('alert-type') }} mx-2">
                    {{ session('message') }}
                </div>
            @endif
        </div>
        <!--end::Message Content-->
        {{-- ------------------- employee_type Start ----------------------------- --}}
        <div class="col-md-6 col-12 pb-15">
            <!--begin::Content-->
            <div id="kt_app_content" class="app-content flex-column-fluid">
                <!--begin::Content container-->
                <div id="kt_app_content_container" class="px-5">
                    <!--begin::Products-->
                    <div class="card card-flush">
                        <!--begin::Card header-->
                        <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                            <!--begin::Card title-->
                            <div class="card-title">
                                <h3>Employee Types</h3>
                            </div>
                            <!--begin::Card toolbar-->
                            <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                                @can('create timetable')
                                    <a href="#" class="btn btn-sm btn-flex btn-light-primary" data-bs-toggle="modal"
                                        data-bs-target="#employee_type_card">
                                        Add New
                                    </a>
                                @endcan
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <table
                                class="table table-striped table-bordered align-middle table-row-dashed fs-7 gy-5 mb-0">
                                <thead>
                                    <!--begin::Table row-->
                                    <tr class="text-start fs-7 text-uppercase gs-0">
                                        <th class="min-w-30px"> SL</th>
                                        <th class="min-w-110px"> English Name</th>
                                        <th class="min-w-110px"> Bangla Name</th>
                                        <th class="min-w-50px"> Status</th>
                                        @can('write timetable')
                                            <th class="text-end min-w-50px">Action</th>
                                        @endcan
                                    </tr>
                                    <!--end::Table row-->
                                </thead>
                                <tbody class="fw-semibold text-gray-700">
                                    @foreach ($empTypes as $empType)
                                        <tr>
                                            <td>
                                                {{ $loop->iteration }}
                                            </td>
                                            <td>
                                                {{ $empType->type_name }}
                                            </td>
                                            <td>
                                                {{ $empType->type_name_bangla }}
                                            </td>
                                            <td>
                                                <div
                                                    class="badge badge-light-{{ $empType->status == 1 ? 'success' : 'info' }}">
                                                    {{ $empType->status == 1 ? 'Active' : 'Disabled' }}</div>
                                            </td>
                                            @can('write timetable')
                                                <td class="text-end">
                                                    <form method="POST"
                                                        action="{{ route('employee_type.destroy', $empType->id) }}">
                                                        {{ csrf_field() }}
                                                        {{ method_field('DELETE') }}
                                                        <button type="button" class="btn btn-sm btn-success p-2"
                                                            data-bs-toggle="tooltip" data-bs-placement="top" title="Edit"
                                                            data-empType="{{ json_encode($empType) }}"
                                                            onclick="openEditModalempTypeId(this)">
                                                            <i class="fa fa-pencil text-white px-2"></i>
                                                        </button>
                                                        <button type="submit" class="btn btn-sm btn-danger p-2"
                                                            data-bs-toggle="tooltip" data-bs-placement="top" title="Delete">
                                                            <i class="fa fa-trash text-white px-2"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            @endcan
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!--end::Card body-->
                    </div>
                    <!--end::Products-->
                </div>
                <!--end::Content container-->
            </div>
            <!--end::Content-->
            <!--begin:: New Add Modal - Create App-->
            <div class="modal fade" id="employee_type_card" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered mw-650px">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h2>Add Employee Type</h2>
                            <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                                <i style="font-size: 20px" class="fas fa-times"></i>
                            </div>
                        </div>
                        <div class="modal-body scroll-y mx-5 mx-xl-15 my-5">
                            <form id="new_card_form" class="form fv-plugins-bootstrap5 fv-plugins-framework"
                                method="POST" action="{{ route('employee_type.store') }}"
                                enctype="multipart/form-data">
                                @csrf
                                <!--begin::Input group-->
                                <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                    <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                        <span class="required">Name English</span>
                                    </label>
                                    <input type="text" class="form-control form-control-sm" name="type_name"
                                        required>
                                </div>
                                <!--begin::Input group-->
                                <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                    <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                        <span>Name Bangla</span>
                                    </label>
                                    <input type="text" class="form-control form-control-sm" name="type_name_bangla">
                                </div>
                                <!--begin::Input group-->
                                <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                    <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                        <span>Status</span>
                                    </label>
                                    <select class="form-select form-select-sm" data-control="select2" name="status">
                                        <option value="">--Select Status--</option>
                                        <option value="1" selected>Active</option>
                                        <option value="0">Disable</option>
                                    </select>
                                </div>

                                <div class="text-center pt-5">
                                    <a href="{{ route('employee_type.index') }}" id="new_card_cancel"
                                        class="btn btn-sm btn-success me-5">Cancel</a>

                                    <button type="submit" id="new_card_submit" class="btn btn-sm btn-primary">
                                        <span class="indicator-label">Submit</span>
                                        <span class="indicator-progress">Please wait...
                                            <span
                                                class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                    </button>
                                </div>
                                <!--end::Actions-->
                            </form>
                            <!--end::Form-->
                        </div>
                        <!--end::Modal body-->
                    </div>
                    <!--end::Modal content-->
                </div>
            </div>
            <!--end::Modal - Create App-->
            <!--begin:: Edit Modal - Create App-->
            <div class="modal fade" id="employee_type_edit_card" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered mw-650px">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h2>Edit Employee Type</h2>
                            <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                                <i style="font-size: 20px" class="fas fa-times"></i>
                            </div>
                        </div>
                        <div class="modal-body scroll-y mx-5 mx-xl-15 my-5">
                            <form id="employee_type_edit_card_form"
                                class="form fv-plugins-bootstrap5 fv-plugins-framework" method="POST"
                                action="{{ route('employee_type.update', ':empTypeId') }}"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <!--begin::Input group-->
                                <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                    <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                        <span class="required">Name English</span>
                                    </label>
                                    <input type="text" class="form-control form-control-sm" name="type_name"
                                        required>
                                </div>
                                <!--begin::Input group-->
                                <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                    <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                        <span class="">Name Bangla</span>
                                    </label>
                                    <input type="text" class="form-control form-control-sm"
                                        name="type_name_bangla">
                                </div>
                                <!--begin::Input group-->
                                <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                    <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                        <span>Status</span>
                                    </label>
                                    <select class="form-select form-select-sm" data-control="select2" name="status">
                                        <option selected disabled>--Select Status--</option>
                                        <option value="1" selected>Active</option>
                                        <option value="0">Disable</option>
                                    </select>
                                </div>

                                <div class="text-center pt-5">
                                    <a href="{{ route('employee_type.index') }}" id="edit_card_cancel"
                                        class="btn btn-sm btn-success me-5">Cancel</a>

                                    <button type="submit" id="edit_card_submit" class="btn btn-sm btn-primary">
                                        <span class="indicator-label">Submit</span>
                                        <span class="indicator-progress">Please wait...
                                            <span
                                                class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                    </button>
                                </div>
                            </form>
                            <!--end::Form-->
                        </div>
                        <!--end::Modal body-->
                    </div>
                    <!--end::Modal content-->
                </div>
            </div>
            <!--end::Modal - Create App-->
        </div>
        {{-- ------------------- Section Start ----------------------------- --}}
        <div class="col-md-6 col-12 pb-15">
            <!--begin::Content-->
            <div id="kt_app_content" class="app-content flex-column-fluid">
                <!--begin::Content container-->
                <div id="kt_app_content_container" class="px-5">
                    <!--begin::Products-->
                    <div class="card card-flush">
                        <!--begin::Card header-->
                        <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                            <!--begin::Card title-->
                            <div class="card-title">
                                <h3>Section</h3>
                            </div>
                            <!--begin::Card toolbar-->
                            <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                                @can('create timetable')
                                    <a href="#" class="btn btn-sm btn-flex btn-light-primary"
                                        data-bs-toggle="modal" data-bs-target="#section_card">
                                        Add New
                                    </a>
                                @endcan
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <table
                                class="table table-striped table-bordered align-middle table-row-dashed fs-7 gy-5 mb-0">
                                <thead>
                                    <!--begin::Table row-->
                                    <tr class="text-start fs-7 text-uppercase gs-0">
                                        <th class="min-w-30px"> SL</th>
                                        <th class="min-w-110px"> English Name</th>
                                        <th class="min-w-110px"> Bangla Name</th>
                                        <th class="min-w-50px"> Status</th>
                                        @can('write timetable')
                                            <th class="text-end min-w-50px">Action</th>
                                        @endcan
                                    </tr>
                                    <!--end::Table row-->
                                </thead>
                                <tbody class="fw-semibold text-gray-700">
                                    @foreach ($empSections as $empSection)
                                        <tr>
                                            <td>
                                                {{ $loop->iteration }}
                                            </td>
                                            <td>
                                                {{ $empSection->section_name }}
                                            </td>
                                            <td>
                                                {{ $empSection->section_name_bangla }}
                                            </td>
                                            <td>
                                                <div
                                                    class="badge badge-light-{{ $empSection->status == 1 ? 'success' : 'info' }}">
                                                    {{ $empSection->status == 1 ? 'Active' : 'Disabled' }}</div>
                                            </td>
                                            @can('write timetable')
                                                <td class="text-end">
                                                    <form method="POST"
                                                        action="{{ route('emp_section.destroy', $empSection->id) }}">
                                                        {{ csrf_field() }}
                                                        {{ method_field('DELETE') }}
                                                        <button type="button" class="btn btn-sm btn-success p-2"
                                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                                            title="Edit"
                                                            data-empSection="{{ json_encode($empSection) }}"
                                                            onclick="openEditModalempSectionId(this)">
                                                            <i class="fa fa-pencil text-white px-2"></i>
                                                        </button>
                                                        <button type="submit" class="btn btn-sm btn-danger p-2"
                                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                                            title="Delete">
                                                            <i class="fa fa-trash text-white px-2"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            @endcan
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!--end::Card body-->
                    </div>
                    <!--end::Products-->
                </div>
                <!--end::Content container-->
            </div>
            <!--end::Content-->
            <!--begin:: New Add Modal - Create App-->
            <div class="modal fade" id="section_card" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered mw-650px">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h2>Add Section</h2>
                            <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                                <i style="font-size: 20px" class="fas fa-times"></i>
                            </div>
                        </div>
                        <div class="modal-body scroll-y mx-5 mx-xl-15 my-5">
                            <form id="new_card_form" class="form fv-plugins-bootstrap5 fv-plugins-framework"
                                method="POST" action="{{ route('emp_section.store') }}"
                                enctype="multipart/form-data">
                                @csrf
                                <!--begin::Input group-->
                                <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                    <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                        <span class="required">Name English</span>
                                    </label>
                                    <input type="text" class="form-control form-control-sm" name="section_name"
                                        required>
                                </div>
                                <!--begin::Input group-->
                                <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                    <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                        <span>Name Bangla</span>
                                    </label>
                                    <input type="text" class="form-control form-control-sm"
                                        name="section_name_bangla">
                                </div>
                                <!--begin::Input group-->
                                <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                    <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                        <span>Status</span>
                                    </label>
                                    <select class="form-select form-select-sm" data-control="select2" name="status">
                                        <option value="">--Select Status--</option>
                                        <option value="1" selected>Active</option>
                                        <option value="0">Disable</option>
                                    </select>
                                </div>

                                <div class="text-center pt-5">
                                    <a href="{{ route('employee_type.index') }}" id="new_card_cancel"
                                        class="btn btn-sm btn-success me-5">Cancel</a>

                                    <button type="submit" id="new_card_submit" class="btn btn-sm btn-primary">
                                        <span class="indicator-label">Submit</span>
                                        <span class="indicator-progress">Please wait...
                                            <span
                                                class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                    </button>
                                </div>
                                <!--end::Actions-->
                            </form>
                            <!--end::Form-->
                        </div>
                        <!--end::Modal body-->
                    </div>
                    <!--end::Modal content-->
                </div>
            </div>
            <!--end::Modal - Create App-->
            <!--begin:: Edit Modal - Create App-->
            <div class="modal fade" id="employee_section_edit_card" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered mw-650px">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h2>Edit Employee Type</h2>
                            <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                                <i style="font-size: 20px" class="fas fa-times"></i>
                            </div>
                        </div>
                        <div class="modal-body scroll-y mx-5 mx-xl-15 my-5">
                            <form id="employee_section_edit_card_form"
                                class="form fv-plugins-bootstrap5 fv-plugins-framework" method="POST"
                                action="{{ route('emp_section.update', ':empSectionId') }}"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <!--begin::Input group-->
                                <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                    <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                        <span class="required">Name English</span>
                                    </label>
                                    <input type="text" class="form-control form-control-sm" name="section_name"
                                        required>
                                </div>
                                <!--begin::Input group-->
                                <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                    <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                        <span class="">Name Bangla</span>
                                    </label>
                                    <input type="text" class="form-control form-control-sm"
                                        name="section_name_bangla">
                                </div>
                                <!--begin::Input group-->
                                <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                    <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                        <span>Status</span>
                                    </label>
                                    <select class="form-select form-select-sm" data-control="select2" name="status">
                                        <option selected disabled>--Select Status--</option>
                                        <option value="1" selected>Active</option>
                                        <option value="0">Disable</option>
                                    </select>
                                </div>

                                <div class="text-center pt-5">
                                    <a href="{{ route('employee_type.index') }}" id="edit_card_cancel"
                                        class="btn btn-sm btn-success me-5">Cancel</a>

                                    <button type="submit" id="edit_card_submit" class="btn btn-sm btn-primary">
                                        <span class="indicator-label">Submit</span>
                                        <span class="indicator-progress">Please wait...
                                            <span
                                                class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                    </button>
                                </div>
                            </form>
                            <!--end::Form-->
                        </div>
                        <!--end::Modal body-->
                    </div>
                    <!--end::Modal content-->
                </div>
            </div>
            <!--end::Modal - Create App-->
        </div>
        {{-- ------------------- Department Start ----------------------------- --}}
        <div class="col-md-6 col-12 pb-15">
            <!--begin::Content-->
            <div id="kt_app_content" class="app-content flex-column-fluid">
                <!--begin::Content container-->
                <div id="kt_app_content_container" class="px-5">
                    <!--begin::Products-->
                    <div class="card card-flush">
                        <!--begin::Card header-->
                        <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                            <!--begin::Card title-->
                            <div class="card-title">
                                <h3>Department</h3>
                            </div>
                            <!--begin::Card toolbar-->
                            <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                                @can('create timetable')
                                    <a href="#" class="btn btn-sm btn-flex btn-light-primary" data-bs-toggle="modal"
                                        data-bs-target="#department_card">
                                        Add New
                                    </a>
                                @endcan
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <table
                                class="table table-striped table-bordered align-middle table-row-dashed fs-7 gy-5 mb-0">
                                <thead>
                                    <!--begin::Table row-->
                                    <tr class="text-start fs-7 text-uppercase gs-0">
                                        <th class="min-w-30px"> SL</th>
                                        <th class="min-w-110px"> English Name</th>
                                        <th class="min-w-110px"> Bangla Name</th>
                                        <th class="min-w-50px"> Status</th>
                                        @can('write timetable')
                                            <th class="text-end min-w-50px">Action</th>
                                        @endcan
                                    </tr>
                                    <!--end::Table row-->
                                </thead>
                                <tbody class="fw-semibold text-gray-700">
                                    @foreach ($empDepartments as $empDepartment)
                                        <tr>
                                            <td>
                                                {{ $loop->iteration }}
                                            </td>
                                            <td>
                                                {{ $empDepartment->department_name }}
                                            </td>
                                            <td>
                                                {{ $empDepartment->department_name_bangla }}
                                            </td>
                                            <td>
                                                <div
                                                    class="badge badge-light-{{ $empDepartment->status == 1 ? 'success' : 'info' }}">
                                                    {{ $empDepartment->status == 1 ? 'Active' : 'Disabled' }}</div>
                                            </td>
                                            @can('write timetable')
                                                <td class="text-end">
                                                    <form method="POST"
                                                        action="{{ route('emp_department.destroy', $empDepartment->id) }}">
                                                        {{ csrf_field() }}
                                                        {{ method_field('DELETE') }}
                                                        <button type="button" class="btn btn-sm btn-success p-2"
                                                            data-bs-toggle="tooltip" data-bs-placement="top" title="Edit"
                                                            data-empDepartment="{{ json_encode($empDepartment) }}"
                                                            onclick="openEditModalempDepartmentId(this)">
                                                            <i class="fa fa-pencil text-white px-2"></i>
                                                        </button>
                                                        <button type="submit" class="btn btn-sm btn-danger p-2"
                                                            data-bs-toggle="tooltip" data-bs-placement="top" title="Delete">
                                                            <i class="fa fa-trash text-white px-2"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            @endcan
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!--end::Card body-->
                    </div>
                    <!--end::Products-->
                </div>
                <!--end::Content container-->
            </div>
            <!--end::Content-->
            <!--begin:: New Add Modal - Create App-->
            <div class="modal fade" id="department_card" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered mw-650px">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h2>Add Department</h2>
                            <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                                <i style="font-size: 20px" class="fas fa-times"></i>
                            </div>
                        </div>
                        <div class="modal-body scroll-y mx-5 mx-xl-15 my-5">
                            <form id="new_card_form" class="form fv-plugins-bootstrap5 fv-plugins-framework"
                                method="POST" action="{{ route('emp_department.store') }}"
                                enctype="multipart/form-data">
                                @csrf
                                <!--begin::Input group-->
                                <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                    <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                        <span class="required">Name English</span>
                                    </label>
                                    <input type="text" class="form-control form-control-sm" name="department_name"
                                        required>
                                </div>
                                <!--begin::Input group-->
                                <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                    <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                        <span>Name Bangla</span>
                                    </label>
                                    <input type="text" class="form-control form-control-sm" name="department_name_bangla">
                                </div>
                                <!--begin::Input group-->
                                <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                    <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                        <span>Status</span>
                                    </label>
                                    <select class="form-select form-select-sm" data-control="select2" name="status">
                                        <option value="">--Select Status--</option>
                                        <option value="1" selected>Active</option>
                                        <option value="0">Disable</option>
                                    </select>
                                </div>

                                <div class="text-center pt-5">
                                    <a href="{{ route('employee_type.index') }}" id="new_card_cancel"
                                        class="btn btn-sm btn-success me-5">Cancel</a>

                                    <button type="submit" id="new_card_submit" class="btn btn-sm btn-primary">
                                        <span class="indicator-label">Submit</span>
                                        <span class="indicator-progress">Please wait...
                                            <span
                                                class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                    </button>
                                </div>
                                <!--end::Actions-->
                            </form>
                            <!--end::Form-->
                        </div>
                        <!--end::Modal body-->
                    </div>
                    <!--end::Modal content-->
                </div>
            </div>
            <!--end::Modal - Create App-->
            <!--begin:: Edit Modal - Create App-->
            <div class="modal fade" id="employee_empDepartment_edit_card" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered mw-650px">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h2>Edit Department</h2>
                            <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                                <i style="font-size: 20px" class="fas fa-times"></i>
                            </div>
                        </div>
                        <div class="modal-body scroll-y mx-5 mx-xl-15 my-5">
                            <form id="employee_empDepartment_edit_card_form"
                                class="form fv-plugins-bootstrap5 fv-plugins-framework" method="POST"
                                action="{{ route('emp_department.update', ':empDepartmentId') }}"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <!--begin::Input group-->
                                <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                    <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                        <span class="required">Name English</span>
                                    </label>
                                    <input type="text" class="form-control form-control-sm" name="department_name"
                                        required>
                                </div>
                                <!--begin::Input group-->
                                <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                    <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                        <span class="">Name Bangla</span>
                                    </label>
                                    <input type="text" class="form-control form-control-sm"
                                        name="department_name_bangla">
                                </div>
                                <!--begin::Input group-->
                                <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                    <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                        <span>Status</span>
                                    </label>
                                    <select class="form-select form-select-sm" data-control="select2" name="status">
                                        <option selected disabled>--Select Status--</option>
                                        <option value="1" selected>Active</option>
                                        <option value="0">Disable</option>
                                    </select>
                                </div>

                                <div class="text-center pt-5">
                                    <a href="{{ route('employee_type.index') }}" id="edit_card_cancel"
                                        class="btn btn-sm btn-success me-5">Cancel</a>

                                    <button type="submit" id="edit_card_submit" class="btn btn-sm btn-primary">
                                        <span class="indicator-label">Submit</span>
                                        <span class="indicator-progress">Please wait...
                                            <span
                                                class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                    </button>
                                </div>
                            </form>
                            <!--end::Form-->
                        </div>
                        <!--end::Modal body-->
                    </div>
                    <!--end::Modal content-->
                </div>
            </div>
            <!--end::Modal - Create App-->
        </div>
        {{-- ------------------- empDesignation Start ----------------------------- --}}
        <div class="col-md-6 col-12 pb-15">
            <!--begin::Content-->
            <div id="kt_app_content" class="app-content flex-column-fluid">
                <!--begin::Content container-->
                <div id="kt_app_content_container" class="px-5">
                    <!--begin::Products-->
                    <div class="card card-flush">
                        <!--begin::Card header-->
                        <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                            <!--begin::Card title-->
                            <div class="card-title">
                                <h3>Designation</h3>
                            </div>
                            <!--begin::Card toolbar-->
                            <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                                @can('create timetable')
                                    <a href="#" class="btn btn-sm btn-flex btn-light-primary" data-bs-toggle="modal"
                                        data-bs-target="#designation_card">
                                        Add New
                                    </a>
                                @endcan
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <table
                                class="table table-striped table-bordered align-middle table-row-dashed fs-7 gy-5 mb-0">
                                <thead>
                                    <!--begin::Table row-->
                                    <tr class="text-start fs-7 text-uppercase gs-0">
                                        <th class="min-w-30px"> SL</th>
                                        <th class="min-w-110px"> English Name</th>
                                        <th class="min-w-110px"> Bangla Name</th>
                                        <th class="min-w-50px"> Status</th>
                                        @can('write timetable')
                                            <th class="text-end min-w-50px">Action</th>
                                        @endcan
                                    </tr>
                                    <!--end::Table row-->
                                </thead>
                                <tbody class="fw-semibold text-gray-700">
                                    @foreach ($empDesignations as $empDesignation)
                                        <tr>
                                            <td>
                                                {{ $loop->iteration }}
                                            </td>
                                            <td>
                                                {{ $empDesignation->designation_name }}
                                            </td>
                                            <td>
                                                {{ $empDesignation->designation_name_bangla }}
                                            </td>
                                            <td>
                                                <div
                                                    class="badge badge-light-{{ $empDesignation->status == 1 ? 'success' : 'info' }}">
                                                    {{ $empDesignation->status == 1 ? 'Active' : 'Disabled' }}</div>
                                            </td>
                                            @can('write timetable')
                                                <td class="text-end">
                                                    <form method="POST"
                                                        action="{{ route('emp_designation.destroy', $empDesignation->id) }}">
                                                        {{ csrf_field() }}
                                                        {{ method_field('DELETE') }}
                                                        <button type="button" class="btn btn-sm btn-success p-2"
                                                            data-bs-toggle="tooltip" data-bs-placement="top" title="Edit"
                                                            data-empDesignations="{{ json_encode($empDesignation) }}"
                                                            onclick="openEditModalempDesignationsId(this)">
                                                            <i class="fa fa-pencil text-white px-2"></i>
                                                        </button>
                                                        <button type="submit" class="btn btn-sm btn-danger p-2"
                                                            data-bs-toggle="tooltip" data-bs-placement="top" title="Delete">
                                                            <i class="fa fa-trash text-white px-2"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            @endcan
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!--end::Card body-->
                    </div>
                    <!--end::Products-->
                </div>
                <!--end::Content container-->
            </div>
            <!--end::Content-->
            <!--begin:: New Add Modal - Create App-->
            <div class="modal fade" id="designation_card" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered mw-650px">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h2>Add Designation</h2>
                            <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                                <i style="font-size: 20px" class="fas fa-times"></i>
                            </div>
                        </div>
                        <div class="modal-body scroll-y mx-5 mx-xl-15 my-5">
                            <form id="new_card_form" class="form fv-plugins-bootstrap5 fv-plugins-framework"
                                method="POST" action="{{ route('emp_designation.store') }}"
                                enctype="multipart/form-data">
                                @csrf
                                <!--begin::Input group-->
                                <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                    <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                        <span class="required">Name English</span>
                                    </label>
                                    <input type="text" class="form-control form-control-sm" name="designation_name"
                                        required>
                                </div>
                                <!--begin::Input group-->
                                <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                    <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                        <span>Name Bangla</span>
                                    </label>
                                    <input type="text" class="form-control form-control-sm" name="designation_name_bangla">
                                </div>
                                <!--begin::Input group-->
                                <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                    <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                        <span>Status</span>
                                    </label>
                                    <select class="form-select form-select-sm" data-control="select2" name="status">
                                        <option value="">--Select Status--</option>
                                        <option value="1" selected>Active</option>
                                        <option value="0">Disable</option>
                                    </select>
                                </div>

                                <div class="text-center pt-5">
                                    <a href="{{ route('employee_type.index') }}" id="new_card_cancel"
                                        class="btn btn-sm btn-success me-5">Cancel</a>

                                    <button type="submit" id="new_card_submit" class="btn btn-sm btn-primary">
                                        <span class="indicator-label">Submit</span>
                                        <span class="indicator-progress">Please wait...
                                            <span
                                                class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                    </button>
                                </div>
                                <!--end::Actions-->
                            </form>
                            <!--end::Form-->
                        </div>
                        <!--end::Modal body-->
                    </div>
                    <!--end::Modal content-->
                </div>
            </div>
            <!--end::Modal - Create App-->
            <!--begin:: Edit Modal - Create App-->
            <div class="modal fade" id="employee_empDesignations_edit_card" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered mw-650px">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h2>Edit Department</h2>
                            <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                                <i style="font-size: 20px" class="fas fa-times"></i>
                            </div>
                        </div>
                        <div class="modal-body scroll-y mx-5 mx-xl-15 my-5">
                            <form id="employee_empDesignations_edit_card_form"
                                class="form fv-plugins-bootstrap5 fv-plugins-framework" method="POST"
                                action="{{ route('emp_designation.update', ':empDesignationId') }}"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <!--begin::Input group-->
                                <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                    <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                        <span class="required">Name English</span>
                                    </label>
                                    <input type="text" class="form-control form-control-sm" name="designation_name"
                                        required>
                                </div>
                                <!--begin::Input group-->
                                <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                    <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                        <span class="">Name Bangla</span>
                                    </label>
                                    <input type="text" class="form-control form-control-sm"
                                        name="designation_name_bangla">
                                </div>
                                <!--begin::Input group-->
                                <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                    <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                        <span>Status</span>
                                    </label>
                                    <select class="form-select form-select-sm" data-control="select2" name="status">
                                        <option selected disabled>--Select Status--</option>
                                        <option value="1" selected>Active</option>
                                        <option value="0">Disable</option>
                                    </select>
                                </div>

                                <div class="text-center pt-5">
                                    <a href="{{ route('employee_type.index') }}" id="edit_card_cancel"
                                        class="btn btn-sm btn-success me-5">Cancel</a>

                                    <button type="submit" id="edit_card_submit" class="btn btn-sm btn-primary">
                                        <span class="indicator-label">Submit</span>
                                        <span class="indicator-progress">Please wait...
                                            <span
                                                class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                    </button>
                                </div>
                            </form>
                            <!--end::Form-->
                        </div>
                        <!--end::Modal body-->
                    </div>
                    <!--end::Modal content-->
                </div>
            </div>
            <!--end::Modal - Create App-->
        </div>
        
        {{-- ------------------- Line Start ----------------------------- --}}
        <div class="col-md-6 col-12 pb-15">
            <!--begin::Content-->
            <div id="kt_app_content" class="app-content flex-column-fluid">
                <!--begin::Content container-->
                <div id="kt_app_content_container" class="px-5">
                    <!--begin::Products-->
                    <div class="card card-flush">
                        <!--begin::Card header-->
                        <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                            <!--begin::Card title-->
                            <div class="card-title">
                                <h3>Line</h3>
                            </div>
                            <!--begin::Card toolbar-->
                            <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                                @can('create timetable')
                                    <a href="#" class="btn btn-sm btn-flex btn-light-primary"
                                        data-bs-toggle="modal" data-bs-target="#line_card">
                                        Add New
                                    </a>
                                @endcan
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <table
                                class="table table-striped table-bordered align-middle table-row-dashed fs-7 gy-5 mb-0">
                                <thead>
                                    <!--begin::Table row-->
                                    <tr class="text-start fs-7 text-uppercase gs-0">
                                        <th class="min-w-30px"> SL</th>
                                        <th class="min-w-110px"> English Name</th>
                                        <th class="min-w-110px"> Bangla Name</th>
                                        <th class="min-w-50px"> Status</th>
                                        @can('write timetable')
                                            <th class="text-end min-w-50px">Action</th>
                                        @endcan
                                    </tr>
                                    <!--end::Table row-->
                                </thead>
                                <tbody class="fw-semibold text-gray-700">
                                    @foreach ($empLines as $empLine)
                                        <tr>
                                            <td>
                                                {{ $loop->iteration }}
                                            </td>
                                            <td>
                                                {{ $empLine->line_name }}
                                            </td>
                                            <td>
                                                {{ $empLine->line_name_bangla }}
                                            </td>
                                            <td>
                                                <div
                                                    class="badge badge-light-{{ $empLine->status == 1 ? 'success' : 'info' }}">
                                                    {{ $empLine->status == 1 ? 'Active' : 'Disabled' }}</div>
                                            </td>
                                            @can('write timetable')
                                                <td class="text-end">
                                                    <form method="POST"
                                                        action="{{ route('emp_line.destroy', $empLine->id) }}">
                                                        {{ csrf_field() }}
                                                        {{ method_field('DELETE') }}
                                                        <button type="button" class="btn btn-sm btn-success p-2"
                                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                                            title="Edit" data-empLine="{{ json_encode($empLine) }}"
                                                            onclick="openEditModalempLineId(this)">
                                                            <i class="fa fa-pencil text-white px-2"></i>
                                                        </button>
                                                        <button type="submit" class="btn btn-sm btn-danger p-2"
                                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                                            title="Delete">
                                                            <i class="fa fa-trash text-white px-2"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            @endcan
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!--end::Card body-->
                    </div>
                    <!--end::Products-->
                </div>
                <!--end::Content container-->
            </div>
            <!--end::Content-->
            <!--begin:: New Add Modal - Create App-->
            <div class="modal fade" id="line_card" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered mw-650px">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h2>Add Line</h2>
                            <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                                <i style="font-size: 20px" class="fas fa-times"></i>
                            </div>
                        </div>
                        <div class="modal-body scroll-y mx-5 mx-xl-15 my-5">
                            <form id="new_card_form" class="form fv-plugins-bootstrap5 fv-plugins-framework"
                                method="POST" action="{{ route('emp_line.store') }}" enctype="multipart/form-data">
                                @csrf
                                <!--begin::Input group-->
                                <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                    <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                        <span class="required">Name English</span>
                                    </label>
                                    <input type="text" class="form-control form-control-sm" name="line_name"
                                        required>
                                </div>
                                <!--begin::Input group-->
                                <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                    <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                        <span>Name Bangla</span>
                                    </label>
                                    <input type="text" class="form-control form-control-sm"
                                        name="line_name_bangla">
                                </div>
                                <!--begin::Input group-->
                                <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                    <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                        <span>Status</span>
                                    </label>
                                    <select class="form-select form-select-sm" data-control="select2" name="status">
                                        <option value="">--Select Status--</option>
                                        <option value="1" selected>Active</option>
                                        <option value="0">Disable</option>
                                    </select>
                                </div>

                                <div class="text-center pt-5">
                                    <a href="{{ route('employee_type.index') }}" id="new_card_cancel"
                                        class="btn btn-sm btn-success me-5">Cancel</a>

                                    <button type="submit" id="new_card_submit" class="btn btn-sm btn-primary">
                                        <span class="indicator-label">Submit</span>
                                        <span class="indicator-progress">Please wait...
                                            <span
                                                class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                    </button>
                                </div>
                                <!--end::Actions-->
                            </form>
                            <!--end::Form-->
                        </div>
                        <!--end::Modal body-->
                    </div>
                    <!--end::Modal content-->
                </div>
            </div>
            <!--end::Modal - Create App-->
            <!--begin:: Edit Modal - Create App-->
            <div class="modal fade" id="employee_line_edit_card" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered mw-650px">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h2>Edit Line</h2>
                            <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                                <i style="font-size: 20px" class="fas fa-times"></i>
                            </div>
                        </div>
                        <div class="modal-body scroll-y mx-5 mx-xl-15 my-5">
                            <form id="employee_line_edit_card_form"
                                class="form fv-plugins-bootstrap5 fv-plugins-framework" method="POST"
                                action="{{ route('emp_line.update', ':empLineId') }}" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <!--begin::Input group-->
                                <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                    <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                        <span class="required">Name English</span>
                                    </label>
                                    <input type="text" class="form-control form-control-sm" name="line_name"
                                        required>
                                </div>
                                <!--begin::Input group-->
                                <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                    <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                        <span class="">Name Bangla</span>
                                    </label>
                                    <input type="text" class="form-control form-control-sm"
                                        name="line_name_bangla">
                                </div>
                                <!--begin::Input group-->
                                <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                    <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                        <span>Status</span>
                                    </label>
                                    <select class="form-select form-select-sm" data-control="select2" name="status">
                                        <option selected disabled>--Select Status--</option>
                                        <option value="1" selected>Active</option>
                                        <option value="0">Disable</option>
                                    </select>
                                </div>

                                <div class="text-center pt-5">
                                    <a href="{{ route('employee_type.index') }}" id="edit_card_cancel"
                                        class="btn btn-sm btn-success me-5">Cancel</a>

                                    <button type="submit" id="edit_card_submit" class="btn btn-sm btn-primary">
                                        <span class="indicator-label">Submit</span>
                                        <span class="indicator-progress">Please wait...
                                            <span
                                                class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                    </button>
                                </div>
                            </form>
                            <!--end::Form-->
                        </div>
                        <!--end::Modal body-->
                    </div>
                    <!--end::Modal content-->
                </div>
            </div>
            <!--end::Modal - Create App-->
        </div>
        {{-- ------------------- Grade Start ----------------------------- --}}
        <div class="col-md-6 col-12 pb-15">
            <!--begin::Content-->
            <div id="kt_app_content" class="app-content flex-column-fluid">
                <!--begin::Content container-->
                <div id="kt_app_content_container" class="px-5">
                    <!--begin::Products-->
                    <div class="card card-flush">
                        <!--begin::Card header-->
                        <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                            <!--begin::Card title-->
                            <div class="card-title">
                                <h3>Grade</h3>
                            </div>
                            <!--begin::Card toolbar-->
                            <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                                @can('create timetable')
                                    <a href="#" class="btn btn-sm btn-flex btn-light-primary"
                                        data-bs-toggle="modal" data-bs-target="#grade_card">
                                        Add New
                                    </a>
                                @endcan
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <table
                                class="table table-striped table-bordered align-middle table-row-dashed fs-7 gy-5 mb-0">
                                <thead>
                                    <!--begin::Table row-->
                                    <tr class="text-start fs-7 text-uppercase gs-0">
                                        <th class="min-w-30px"> SL</th>
                                        <th class="min-w-110px"> English Name</th>
                                        <th class="min-w-110px"> Bangla Name</th>
                                        <th class="min-w-50px"> Status</th>
                                        @can('write timetable')
                                            <th class="text-end min-w-50px">Action</th>
                                        @endcan
                                    </tr>
                                    <!--end::Table row-->
                                </thead>
                                <tbody class="fw-semibold text-gray-700">
                                    @foreach ($empGrades as $empGrade)
                                        <tr>
                                            <td>
                                                {{ $loop->iteration }}
                                            </td>
                                            <td>
                                                {{ $empGrade->grade_name }}
                                            </td>
                                            <td>
                                                {{ $empGrade->grade_name_bangla }}
                                            </td>
                                            <td>
                                                <div
                                                    class="badge badge-light-{{ $empGrade->status == 1 ? 'success' : 'info' }}">
                                                    {{ $empGrade->status == 1 ? 'Active' : 'Disabled' }}</div>
                                            </td>
                                            @can('write timetable')
                                                <td class="text-end">
                                                    <form method="POST"
                                                        action="{{ route('emp_grade.destroy', $empGrade->id) }}">
                                                        {{ csrf_field() }}
                                                        {{ method_field('DELETE') }}
                                                        <button type="button" class="btn btn-sm btn-success p-2"
                                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                                            title="Edit" data-empGrade="{{ json_encode($empGrade) }}"
                                                            onclick="openEditModalempGradeId(this)">
                                                            <i class="fa fa-pencil text-white px-2"></i>
                                                        </button>
                                                        <button type="submit" class="btn btn-sm btn-danger p-2"
                                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                                            title="Delete">
                                                            <i class="fa fa-trash text-white px-2"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            @endcan
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!--end::Card body-->
                    </div>
                    <!--end::Products-->
                </div>
                <!--end::Content container-->
            </div>
            <!--end::Content-->
            <!--begin:: New Add Modal - Create App-->
            <div class="modal fade" id="grade_card" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered mw-650px">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h2>Add Grade</h2>
                            <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                                <i style="font-size: 20px" class="fas fa-times"></i>
                            </div>
                        </div>
                        <div class="modal-body scroll-y mx-5 mx-xl-15 my-5">
                            <form id="new_card_form" class="form fv-plugins-bootstrap5 fv-plugins-framework"
                                method="POST" action="{{ route('emp_grade.store') }}"
                                enctype="multipart/form-data">
                                @csrf
                                <!--begin::Input group-->
                                <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                    <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                        <span class="required">Name English</span>
                                    </label>
                                    <input type="text" class="form-control form-control-sm" name="grade_name"
                                        required>
                                </div>
                                <!--begin::Input group-->
                                <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                    <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                        <span>Name Bangla</span>
                                    </label>
                                    <input type="text" class="form-control form-control-sm"
                                        name="grade_name_bangla">
                                </div>
                                <!--begin::Input group-->
                                <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                    <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                        <span>Status</span>
                                    </label>
                                    <select class="form-select form-select-sm" data-control="select2" name="status">
                                        <option value="">--Select Status--</option>
                                        <option value="1" selected>Active</option>
                                        <option value="0">Disable</option>
                                    </select>
                                </div>

                                <div class="text-center pt-5">
                                    <a href="{{ route('employee_type.index') }}" id="new_card_cancel"
                                        class="btn btn-sm btn-success me-5">Cancel</a>

                                    <button type="submit" id="new_card_submit" class="btn btn-sm btn-primary">
                                        <span class="indicator-label">Submit</span>
                                        <span class="indicator-progress">Please wait...
                                            <span
                                                class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                    </button>
                                </div>
                                <!--end::Actions-->
                            </form>
                            <!--end::Form-->
                        </div>
                        <!--end::Modal body-->
                    </div>
                    <!--end::Modal content-->
                </div>
            </div>
            <!--end::Modal - Create App-->
            <!--begin:: Edit Modal - Create App-->
            <div class="modal fade" id="employee_grade_edit_card" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered mw-650px">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h2>Edit Grade</h2>
                            <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                                <i style="font-size: 20px" class="fas fa-times"></i>
                            </div>
                        </div>
                        <div class="modal-body scroll-y mx-5 mx-xl-15 my-5">
                            <form id="employee_grade_edit_card_form"
                                class="form fv-plugins-bootstrap5 fv-plugins-framework" method="POST"
                                action="{{ route('emp_grade.update', ':empGradeId') }}"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <!--begin::Input group-->
                                <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                    <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                        <span class="required">Name English</span>
                                    </label>
                                    <input type="text" class="form-control form-control-sm" name="grade_name"
                                        required>
                                </div>
                                <!--begin::Input group-->
                                <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                    <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                        <span class="">Name Bangla</span>
                                    </label>
                                    <input type="text" class="form-control form-control-sm"
                                        name="grade_name_bangla">
                                </div>
                                <!--begin::Input group-->
                                <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                    <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                        <span>Status</span>
                                    </label>
                                    <select class="form-select form-select-sm" data-control="select2" name="status">
                                        <option selected disabled>--Select Status--</option>
                                        <option value="1" selected>Active</option>
                                        <option value="0">Disable</option>
                                    </select>
                                </div>

                                <div class="text-center pt-5">
                                    <a href="{{ route('employee_type.index') }}" id="edit_card_cancel"
                                        class="btn btn-sm btn-success me-5">Cancel</a>

                                    <button type="submit" id="edit_card_submit" class="btn btn-sm btn-primary">
                                        <span class="indicator-label">Submit</span>
                                        <span class="indicator-progress">Please wait...
                                            <span
                                                class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                    </button>
                                </div>
                            </form>
                            <!--end::Form-->
                        </div>
                        <!--end::Modal body-->
                    </div>
                    <!--end::Modal content-->
                </div>
            </div>
            <!--end::Modal - Create App-->
        </div>
        {{-- ------------------- Salary Section Start ----------------------------- --}}
        <div class="col-md-6 col-12 pb-15">
            <!--begin::Content-->
            <div id="kt_app_content" class="app-content flex-column-fluid">
                <!--begin::Content container-->
                <div id="kt_app_content_container" class="px-5">
                    <!--begin::Products-->
                    <div class="card card-flush">
                        <!--begin::Card header-->
                        <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                            <!--begin::Card title-->
                            <div class="card-title">
                                <h3>Salary Section</h3>
                            </div>
                            <!--begin::Card toolbar-->
                            <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                                @can('create timetable')
                                    <a href="#" class="btn btn-sm btn-flex btn-light-primary"
                                        data-bs-toggle="modal" data-bs-target="#salary_sections_card">
                                        Add New
                                    </a>
                                @endcan
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <table
                                class="table table-striped table-bordered align-middle table-row-dashed fs-7 gy-5 mb-0">
                                <thead>
                                    <!--begin::Table row-->
                                    <tr class="text-start fs-7 text-uppercase gs-0">
                                        <th class="min-w-30px"> SL</th>
                                        <th class="min-w-110px"> English Name</th>
                                        <th class="min-w-110px"> Bangla Name</th>
                                        <th class="min-w-50px"> Status</th>
                                        @can('write timetable')
                                            <th class="text-end min-w-50px">Action</th>
                                        @endcan
                                    </tr>
                                    <!--end::Table row-->
                                </thead>
                                <tbody class="fw-semibold text-gray-700">
                                    @foreach ($empSalarySections as $empSalarySection)
                                        <tr>
                                            <td>
                                                {{ $loop->iteration }}
                                            </td>
                                            <td>
                                                {{ $empSalarySection->salary_section_name }}
                                            </td>
                                            <td>
                                                {{ $empSalarySection->salary_section_name_bangla }}
                                            </td>
                                            <td>
                                                <div class="badge badge-light-{{ $empSalarySection->status == 1 ? 'success' : 'info' }}">
                                                    {{ $empSalarySection->status == 1 ? 'Active' : 'Disabled' }}</div>
                                            </td>
                                            @can('write timetable')
                                                <td class="text-end">
                                                    <form method="POST"
                                                        action="{{ route('emp_salary_section.destroy', $empSalarySection->id) }}">
                                                        {{ csrf_field() }}
                                                        {{ method_field('DELETE') }}
                                                        <button type="button" class="btn btn-sm btn-success p-2"
                                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                                            title="Edit"
                                                            data-empSalarySection="{{ json_encode($empSalarySection) }}"
                                                            onclick="openEditModalempSalarySectionId(this)">
                                                            <i class="fa fa-pencil text-white px-2"></i>
                                                        </button>
                                                        <button type="submit" class="btn btn-sm btn-danger p-2"
                                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                                            title="Delete">
                                                            <i class="fa fa-trash text-white px-2"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            @endcan
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!--end::Card body-->
                    </div>
                    <!--end::Products-->
                </div>
                <!--end::Content container-->
            </div>
            <!--end::Content-->
            <!--begin:: New Add Modal - Create App-->
            <div class="modal fade" id="salary_sections_card" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered mw-650px">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h2>Add Salary Section</h2>
                            <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                                <i style="font-size: 20px" class="fas fa-times"></i>
                            </div>
                        </div>
                        <div class="modal-body scroll-y mx-5 mx-xl-15 my-5">
                            <form id="new_card_form" class="form fv-plugins-bootstrap5 fv-plugins-framework"
                                method="POST" action="{{ route('emp_salary_section.store') }}"
                                enctype="multipart/form-data">
                                @csrf
                                <!--begin::Input group-->
                                <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                    <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                        <span class="required">Name English</span>
                                    </label>
                                    <input type="text" class="form-control form-control-sm"
                                        name="salary_section_name" required>
                                </div>
                                <!--begin::Input group-->
                                <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                    <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                        <span>Name Bangla</span>
                                    </label>
                                    <input type="text" class="form-control form-control-sm"
                                        name="salary_section_name_bangla">
                                </div>
                                <!--begin::Input group-->
                                <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                    <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                        <span>Status</span>
                                    </label>
                                    <select class="form-select form-select-sm" data-control="select2" name="status">
                                        <option value="">--Select Status--</option>
                                        <option value="1" selected>Active</option>
                                        <option value="0">Disable</option>
                                    </select>
                                </div>

                                <div class="text-center pt-5">
                                    <a href="{{ route('employee_type.index') }}" id="new_card_cancel"
                                        class="btn btn-sm btn-success me-5">Cancel</a>

                                    <button type="submit" id="new_card_submit" class="btn btn-sm btn-primary">
                                        <span class="indicator-label">Submit</span>
                                        <span class="indicator-progress">Please wait...
                                            <span
                                                class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                    </button>
                                </div>
                                <!--end::Actions-->
                            </form>
                            <!--end::Form-->
                        </div>
                        <!--end::Modal body-->
                    </div>
                    <!--end::Modal content-->
                </div>
            </div>
            <!--end::Modal - Create App-->
            <!--begin:: Edit Modal - Create App-->
            <div class="modal fade" id="employee_salary_sections_edit_card" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered mw-650px">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h2>Edit Salary Section</h2>
                            <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                                <i style="font-size: 20px" class="fas fa-times"></i>
                            </div>
                        </div>
                        <div class="modal-body scroll-y mx-5 mx-xl-15 my-5">
                            <form id="employee_salary_sections_edit_card_form"
                                class="form fv-plugins-bootstrap5 fv-plugins-framework" method="POST"
                                action="{{ route('emp_salary_section.update', ':empSalarySectionId') }}"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <!--begin::Input group-->
                                <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                    <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                        <span class="required">Name English</span>
                                    </label>
                                    <input type="text" class="form-control form-control-sm"
                                        name="salary_section_name" required>
                                </div>
                                <!--begin::Input group-->
                                <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                    <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                        <span class="">Name Bangla</span>
                                    </label>
                                    <input type="text" class="form-control form-control-sm"
                                        name="salary_section_name_bangla">
                                </div>
                                <!--begin::Input group-->
                                <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                    <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                        <span>Status</span>
                                    </label>
                                    <select class="form-select form-select-sm" data-control="select2" name="status">
                                        <option selected disabled>--Select Status--</option>
                                        <option value="1" selected>Active</option>
                                        <option value="0">Disable</option>
                                    </select>
                                </div>

                                <div class="text-center pt-5">
                                    <a href="{{ route('employee_type.index') }}" id="edit_card_cancel"
                                        class="btn btn-sm btn-success me-5">Cancel</a>

                                    <button type="submit" id="edit_card_submit" class="btn btn-sm btn-primary">
                                        <span class="indicator-label">Submit</span>
                                        <span class="indicator-progress">Please wait...
                                            <span
                                                class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                    </button>
                                </div>
                            </form>
                            <!--end::Form-->
                        </div>
                        <!--end::Modal body-->
                    </div>
                    <!--end::Modal content-->
                </div>
            </div>
            <!--end::Modal - Create App-->
        </div>
        {{-- ------------------- Quite Type Start ----------------------------- --}}
        <div class="col-md-6 col-12 pb-15">
            <!--begin::Content-->
            <div id="kt_app_content" class="app-content flex-column-fluid">
                <!--begin::Content container-->
                <div id="kt_app_content_container" class="px-5">
                    <!--begin::Products-->
                    <div class="card card-flush">
                        <!--begin::Card header-->
                        <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                            <!--begin::Card title-->
                            <div class="card-title">
                                <h3>Quit Type</h3>
                            </div>
                            <!--begin::Card toolbar-->
                            <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                                @can('create timetable')
                                    <a href="#" class="btn btn-sm btn-flex btn-light-primary"
                                        data-bs-toggle="modal" data-bs-target="#quite_type_card">
                                        Add New
                                    </a>
                                @endcan
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <table
                                class="table table-striped table-bordered align-middle table-row-dashed fs-7 gy-5 mb-0">
                                <thead>
                                    <!--begin::Table row-->
                                    <tr class="text-start fs-7 text-uppercase gs-0">
                                        <th class="min-w-30px"> SL</th>
                                        <th class="min-w-110px"> English Name</th>
                                        <th class="min-w-110px"> Bangla Name</th>
                                        <th class="min-w-50px"> Status</th>
                                        @can('write timetable')
                                            <th class="text-end min-w-50px">Action</th>
                                        @endcan
                                    </tr>
                                    <!--end::Table row-->
                                </thead>
                                <tbody class="fw-semibold text-gray-700">
                                    @foreach ($empQuiteTypes as $empQuiteType)
                                        <tr>
                                            <td>
                                                {{ $loop->iteration }}
                                            </td>
                                            <td>
                                                {{ $empQuiteType->quite_type_name }}
                                            </td>
                                            <td>
                                                {{ $empQuiteType->quite_type_name_bangla }}
                                            </td>
                                            <td>
                                                <div
                                                    class="badge badge-light-{{ $empQuiteType->status == 1 ? 'success' : 'info' }}">
                                                    {{ $empQuiteType->status == 1 ? 'Active' : 'Disabled' }}</div>
                                            </td>
                                            @can('write timetable')
                                                <td class="text-end">
                                                    <form method="POST"
                                                        action="{{ route('emp_quite_type.destroy', $empQuiteType->id) }}">
                                                        {{ csrf_field() }}
                                                        {{ method_field('DELETE') }}
                                                        <button type="button" class="btn btn-sm btn-success p-2"
                                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                                            title="Edit"
                                                            data-empQuiteType="{{ json_encode($empQuiteType) }}"
                                                            onclick="openEditModalempQuiteTypeId(this)">
                                                            <i class="fa fa-pencil text-white px-2"></i>
                                                        </button>
                                                        <button type="submit" class="btn btn-sm btn-danger p-2"
                                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                                            title="Delete">
                                                            <i class="fa fa-trash text-white px-2"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            @endcan
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!--end::Card body-->
                    </div>
                    <!--end::Products-->
                </div>
                <!--end::Content container-->
            </div>
            <!--end::Content-->
            <!--begin:: New Add Modal - Create App-->
            <div class="modal fade" id="quite_type_card" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered mw-650px">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h2>Add Quit Type</h2>
                            <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                                <i style="font-size: 20px" class="fas fa-times"></i>
                            </div>
                        </div>
                        <div class="modal-body scroll-y mx-5 mx-xl-15 my-5">
                            <form id="new_card_form" class="form fv-plugins-bootstrap5 fv-plugins-framework"
                                method="POST" action="{{ route('emp_quite_type.store') }}"
                                enctype="multipart/form-data">
                                @csrf
                                <!--begin::Input group-->
                                <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                    <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                        <span class="required">Name English</span>
                                    </label>
                                    <input type="text" class="form-control form-control-sm"
                                        name="quite_type_name" required>
                                </div>
                                <!--begin::Input group-->
                                <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                    <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                        <span>Name Bangla</span>
                                    </label>
                                    <input type="text" class="form-control form-control-sm"
                                        name="quite_type_name_bangla">
                                </div>
                                <!--begin::Input group-->
                                <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                    <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                        <span>Status</span>
                                    </label>
                                    <select class="form-select form-select-sm" data-control="select2" name="status">
                                        <option value="">--Select Status--</option>
                                        <option value="1" selected>Active</option>
                                        <option value="0">Disable</option>
                                    </select>
                                </div>

                                <div class="text-center pt-5">
                                    <a href="{{ route('employee_type.index') }}" id="new_card_cancel"
                                        class="btn btn-sm btn-success me-5">Cancel</a>

                                    <button type="submit" id="new_card_submit" class="btn btn-sm btn-primary">
                                        <span class="indicator-label">Submit</span>
                                        <span class="indicator-progress">Please wait...
                                            <span
                                                class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                    </button>
                                </div>
                                <!--end::Actions-->
                            </form>
                            <!--end::Form-->
                        </div>
                        <!--end::Modal body-->
                    </div>
                    <!--end::Modal content-->
                </div>
            </div>
            <!--end::Modal - Create App-->
            <!--begin:: Edit Modal - Create App-->
            <div class="modal fade" id="employee_quite_type_edit_card" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered mw-650px">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h2>Edit Quit Type</h2>
                            <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                                <i style="font-size: 20px" class="fas fa-times"></i>
                            </div>
                        </div>
                        <div class="modal-body scroll-y mx-5 mx-xl-15 my-5">
                            <form id="employee_quite_type_edit_card_form"
                                class="form fv-plugins-bootstrap5 fv-plugins-framework" method="POST"
                                action="{{ route('emp_quite_type.update', ':empQuiteTypeId') }}"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <!--begin::Input group-->
                                <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                    <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                        <span class="required">Name English</span>
                                    </label>
                                    <input type="text" class="form-control form-control-sm"
                                        name="quite_type_name" required>
                                </div>
                                <!--begin::Input group-->
                                <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                    <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                        <span class="">Name Bangla</span>
                                    </label>
                                    <input type="text" class="form-control form-control-sm"
                                        name="quite_type_name_bangla">
                                </div>
                                <!--begin::Input group-->
                                <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                    <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                        <span>Status</span>
                                    </label>
                                    <select class="form-select form-select-sm" data-control="select2" name="status">
                                        <option selected disabled>--Select Status--</option>
                                        <option value="1" selected>Active</option>
                                        <option value="0">Disable</option>
                                    </select>
                                </div>

                                <div class="text-center pt-5">
                                    <a href="{{ route('employee_type.index') }}" id="edit_card_cancel"
                                        class="btn btn-sm btn-success me-5">Cancel</a>

                                    <button type="submit" id="edit_card_submit" class="btn btn-sm btn-primary">
                                        <span class="indicator-label">Submit</span>
                                        <span class="indicator-progress">Please wait...
                                            <span
                                                class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                    </button>
                                </div>
                            </form>
                            <!--end::Form-->
                        </div>
                        <!--end::Modal body-->
                    </div>
                    <!--end::Modal content-->
                </div>
            </div>
            <!--end::Modal - Create App-->
        </div>
    </div>

</x-default-layout>


<script type="text/javascript">
    // Employee Type Edit Modal Start
    function openEditModalempTypeId(button) {
        const empTypeData = JSON.parse(button.getAttribute('data-empType'));
        // consol.log(empTypeData);
        const formAction = $('#employee_type_edit_card').find('#employee_type_edit_card_form').attr('action');
        const empTypeId = empTypeData.id;
        const updatedFormAction = formAction.replace(':empTypeId', empTypeId);
        $('#employee_type_edit_card').find('#employee_type_edit_card_form').attr('action', updatedFormAction);

        $('#employee_type_edit_card').find('input[name="type_name"]').val(empTypeData.type_name);
        $('#employee_type_edit_card').find('input[name="type_name_bangla"]').val(empTypeData.type_name_bangla);
        $('#employee_type_edit_card').find('select[name="status"]').val(empTypeData.status);

        $('#employee_type_edit_card').modal('show');
    }
    // Employee empDepartment Edit Modal Start
    function openEditModalempDepartmentId(button) {
        const empDepartmentData = JSON.parse(button.getAttribute('data-empDepartment'));
        // consol.log(empDepartmentData);
        const formAction = $('#employee_empDepartment_edit_card').find('#employee_empDepartment_edit_card_form').attr('action');
        const empDepartmentId = empDepartmentData.id;
        const updatedFormAction = formAction.replace(':empDepartmentId', empDepartmentId);
        $('#employee_empDepartment_edit_card').find('#employee_empDepartment_edit_card_form').attr('action', updatedFormAction);

        $('#employee_empDepartment_edit_card').find('input[name="department_name"]').val(empDepartmentData.department_name);
        $('#employee_empDepartment_edit_card').find('input[name="department_name_bangla"]').val(empDepartmentData.department_name_bangla);
        $('#employee_empDepartment_edit_card').find('select[name="status"]').val(empDepartmentData.status);

        $('#employee_empDepartment_edit_card').modal('show');
    }
    // Employee empDesignations Edit Modal Start
    function openEditModalempDesignationsId(button) {
        const empDesignationsData = JSON.parse(button.getAttribute('data-empDesignations'));
        // consol.log(empDesignationsData);
        const formAction = $('#employee_empDesignations_edit_card').find('#employee_empDesignations_edit_card_form').attr('action');
        const empDesignationId = empDesignationsData.id;
        const updatedFormAction = formAction.replace(':empDesignationId', empDesignationId);
        $('#employee_empDesignations_edit_card').find('#employee_empDesignations_edit_card_form').attr('action', updatedFormAction);

        $('#employee_empDesignations_edit_card').find('input[name="designation_name"]').val(empDesignationsData.designation_name);
        $('#employee_empDesignations_edit_card').find('input[name="designation_name_bangla"]').val(empDesignationsData.designation_name_bangla);
        $('#employee_empDesignations_edit_card').find('select[name="status"]').val(empDesignationsData.status);

        $('#employee_empDesignations_edit_card').modal('show');
    }
    // Employee Section Edit Modal Start
    function openEditModalempSectionId(button) {
        const empSectionData = JSON.parse(button.getAttribute('data-empSection'));
        // consol.log(empTypeData);
        const formAction = $('#employee_section_edit_card').find('#employee_section_edit_card_form').attr('action');
        const empSectionId = empSectionData.id;
        const updatedFormAction = formAction.replace(':empSectionId', empSectionId);
        $('#employee_section_edit_card').find('#employee_section_edit_card_form').attr('action', updatedFormAction);

        $('#employee_section_edit_card').find('input[name="section_name"]').val(empSectionData.section_name);
        $('#employee_section_edit_card').find('input[name="section_name_bangla"]').val(empSectionData
            .section_name_bangla);
        $('#employee_section_edit_card').find('select[name="status"]').val(empSectionData.status);

        $('#employee_section_edit_card').modal('show');
    }
    // Employee Line Edit Modal Start
    function openEditModalempLineId(button) {
        const empLineData = JSON.parse(button.getAttribute('data-empLine'));
        // consol.log(empTypeData);
        const formAction = $('#employee_line_edit_card').find('#employee_line_edit_card_form').attr('action');
        const empLineId = empLineData.id;
        const updatedFormAction = formAction.replace(':empLineId', empLineId);
        $('#employee_line_edit_card').find('#employee_line_edit_card_form').attr('action', updatedFormAction);

        $('#employee_line_edit_card').find('input[name="line_name"]').val(empLineData.line_name);
        $('#employee_line_edit_card').find('input[name="line_name_bangla"]').val(empLineData.line_name_bangla);
        $('#employee_line_edit_card').find('select[name="status"]').val(empLineData.status);

        $('#employee_line_edit_card').modal('show');
    }
    // Employee Grade Edit Modal Start
    function openEditModalempGradeId(button) {
        const empGradeData = JSON.parse(button.getAttribute('data-empGrade'));
        // consol.log(empTypeData);
        const formAction = $('#employee_grade_edit_card').find('#employee_grade_edit_card_form').attr('action');
        const empGradeId = empGradeData.id;
        const updatedFormAction = formAction.replace(':empGradeId', empGradeId);
        $('#employee_grade_edit_card').find('#employee_grade_edit_card_form').attr('action', updatedFormAction);

        $('#employee_grade_edit_card').find('input[name="grade_name"]').val(empGradeData.grade_name);
        $('#employee_grade_edit_card').find('input[name="grade_name_bangla"]').val(empGradeData.grade_name_bangla);
        $('#employee_grade_edit_card').find('select[name="status"]').val(empGradeData.status);

        $('#employee_grade_edit_card').modal('show');
    }
    // Employee Salary Section Edit Modal Start
    function openEditModalempSalarySectionId(button) {
        const empSalarySectionData = JSON.parse(button.getAttribute('data-empSalarySection'));
        const formAction = $('#employee_salary_sections_edit_card').find('#employee_salary_sections_edit_card_form')
            .attr('action');
        const empSalarySectionId = empSalarySectionData.id;
        const updatedFormAction = formAction.replace(':empSalarySectionId', empSalarySectionId);
        $('#employee_salary_sections_edit_card').find('#employee_salary_sections_edit_card_form').attr('action',
            updatedFormAction);

        $('#employee_salary_sections_edit_card').find('input[name="salary_section_name"]').val(empSalarySectionData
            .salary_section_name);
        $('#employee_salary_sections_edit_card').find('input[name="salary_section_name_bangla"]').val(
            empSalarySectionData.salary_section_name_bangla);
        $('#employee_salary_sections_edit_card').find('select[name="status"]').val(empSalarySectionData.status);

        $('#employee_salary_sections_edit_card').modal('show');
    }
    // Employee Salary Section Edit Modal Start
    function openEditModalempQuiteTypeId(button) {
        const empQuiteTypeData = JSON.parse(button.getAttribute('data-empQuiteType'));
        const formAction = $('#employee_quite_type_edit_card').find('#employee_quite_type_edit_card_form').attr('action');
        const empQuiteTypeId = empQuiteTypeData.id;
        const updatedFormAction = formAction.replace(':empQuiteTypeId', empQuiteTypeId);
        $('#employee_quite_type_edit_card').find('#employee_quite_type_edit_card_form').attr('action',updatedFormAction);

        $('#employee_quite_type_edit_card').find('input[name="quite_type_name"]').val(empQuiteTypeData.quite_type_name);
        $('#employee_quite_type_edit_card').find('input[name="quite_type_name_bangla"]').val(empQuiteTypeData.quite_type_name_bangla);
        $('#employee_quite_type_edit_card').find('select[name="status"]').val(empQuiteTypeData.status);

        $('#employee_quite_type_edit_card').modal('show');
    }
</script>

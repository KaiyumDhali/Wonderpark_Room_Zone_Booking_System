<x-default-layout>
    <style>
        .table> :not(caption)>*>* {
            padding: 0.3rem !important;
        }
    </style>

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

    <!--begin::Toolbar-->
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <!--begin::Toolbar container-->
        <div id="kt_app_toolbar_container" class="app-container  d-flex flex-stack">
            <!--begin::Page title-->
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <!--begin::Title-->
                <h3>Deduction Head</h3>
                <!--end::Title-->
            </div>
            <!--end::Page title-->
        </div>
        <!--end::Toolbar container-->
    </div>
    <!--end::Toolbar-->

    <!--begin::Content-->
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container ">
            <!--begin::Products-->
            <div class="card card-flush">
                <!--begin::Card header-->
                <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                    <!--begin::Card title-->
                    <div class="card-title">
                        <!--begin::Search-->
                        <div class="d-flex align-items-center position-relative my-1">
                            <!--begin::Svg Icon | path: icons/duotune/general/gen021.svg-->
                            <span class="svg-icon svg-icon-1 position-absolute ms-4">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2"
                                        rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor" />
                                    <path
                                        d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z"
                                        fill="currentColor" />
                                </svg>
                            </span>
                            <!--end::Svg Icon-->
                            <input type="text" data-kt-ecommerce-order-filter="search"
                                class="form-control form-control-sm form-control form-control-sm-solid w-250px ps-14 p-2"
                                placeholder="Search" />
                        </div>
                        <!--end::Search-->
                        <!--begin::Export buttons-->
                        <div id="kt_ecommerce_report_customer_orders_export" class="d-none"></div>
                        <!--end::Export buttons-->
                    </div>
                    <!--end::Card title-->
                    <!--begin::Card toolbar-->
                    <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                        <!--begin::Daterangepicker-->
                        {{-- <input class="form-control form-control-sm form-control form-control-sm-solid w-100 mw-250px" placeholder="Pick date range" id="kt_ecommerce_report_customer_orders_daterangepicker" /> --}}
                        <!--end::Daterangepicker-->
                        <!--begin::Filter-->
                        <div class="w-150px">
                            <select class="form-select p-2 form-select-solid" data-control="select2"
                                data-hide-search="true" data-placeholder="Status"
                                data-kt-ecommerce-order-filter="status">
                                <option value="all"> Status</option>
                                <option value="Active">Active</option>
                                <option value="Disabled">Disabled</option>
                            </select>
                        </div>
                        
                        <!-- <a href="{{ route('payslip_type.create') }}" class="btn btn-sm btn-primary btn-sm">Add
                                                                                Loan Option</a> -->

                        <!-- <a href="#" class="btn btn-sm fw-bold btn-primary" data-bs-toggle="modal"
                                                                                data-bs-target="#kt_modal_create_app">Create</a> -->
                        @can('create deduction head')
                            <a href="#" class="btn btn-sm btn-flex btn-light-primary" data-bs-toggle="modal"
                                data-bs-target="#kt_modal_new_card">
                                <span class="svg-icon svg-icon-3">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="5"
                                            fill="currentColor">
                                        </rect>
                                        <rect x="10.8891" y="17.8033" width="12" height="2" rx="1"
                                            transform="rotate(-90 10.8891 17.8033)" fill="currentColor"></rect>
                                        <rect x="6.01041" y="10.9247" width="12" height="2" rx="1"
                                            fill="currentColor">
                                        </rect>
                                    </svg>
                                </span>
                                Add New
                            </a>
                        @endcan
                    </div>
                    <!--end::Card toolbar-->
                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body pt-0">
                    <!--begin::Table-->
                    <table class="table table-striped table-bordered align-middle table-row-dashed fs-7 gy-5 mb-0"
                        id="kt_ecommerce_report_customer_orders_table">
                        <!--begin::Table head-->
                        <thead>
                            <!--begin::Table row-->
                            <tr class="text-start fs-7 text-uppercase gs-0">
                                <th class="min-w-50px">Deduction Head</th>
                                <th class="min-w-100px"> Created By</th>
                                <th class="min-w-100px">Deduction Head Status</th>
                                @can('write deduction head')
                                    <th class="text-end min-w-100px">Actions</th>
                                @endcan
                            </tr>
                            <!--end::Table row-->
                        </thead>
                        <!--end::Table head-->
                        <!--begin::Table body-->
                        <tbody class="fw-semibold text-gray-700">
                            @foreach ($deductionHeads as $deductionHead)
                                <tr>
                                    <td>
                                        <a>{{ $deductionHead->name }}</a>
                                    </td>
                                    <td>
                                        <a class="text-dark text-hover-primary">{{ $deductionHead->created_by }} </a>
                                    </td>
                                    <td>
                                        <div
                                            class="badge badge-light-{{ $deductionHead->status == 1 ? 'success' : 'info' }}">
                                            {{ $deductionHead->status == 1 ? 'Active' : 'Disabled' }}</div>
                                    </td>
                                    @can('write deduction head')
                                        <td class="text-end">
                                            <button type="button" class="btn btn-sm btn-success p-2"
                                                data-deductionhead="{{ json_encode($deductionHead) }}"
                                                onclick="openEditModal(this)">Edit
                                                <i class="fa fa-pencil text-white px-2"></i>
                                            </button>
                                            <!-- <form method="POST" action="{{ route('deduction_head.destroy', $deductionHead->id) }}">
                                            {{ csrf_field() }}
                                            {{ method_field('DELETE') }}
                                                                
                                                                                                        <button type="submit" class="btn btn-sm btn-danger p-2">
                                                                                                            <i class="fa fa-trash text-white px-2"></i>
                                                                                                        </button>
                                                                                                    </form> -->
                                        </td>
                                    @endcan
                                </tr>
                            @endforeach
                        </tbody>
                        <!--end::Table body-->
                    </table>
                    <!--end::Table-->
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Products-->
        </div>
        <!--end::Content container-->
    </div>
    <!--end::Content-->

    <!--begin::Modal - Create App-->
    <div class="modal fade" id="kt_modal_new_card" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <!--begin::Modal content-->
            <div class="modal-content">
                <!--begin::Modal header-->
                <div class="modal-header">
                    <!--begin::Modal title-->
                    <h2>Add New Deduction Head</h2>
                    <!--end::Modal title-->
                    <!--begin::Close-->
                    <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                        <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                        <span class="svg-icon svg-icon-1">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1"
                                    transform="rotate(-45 6 17.3137)" fill="currentColor"></rect>
                                <rect x="7.41422" y="6" width="16" height="2" rx="1"
                                    transform="rotate(45 7.41422 6)" fill="currentColor"></rect>
                            </svg>
                        </span>
                        <!--end::Svg Icon-->
                    </div>
                    <!--end::Close-->
                </div>
                <!--end::Modal header-->
                <!--begin::Modal body-->
                <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                    <!--begin::Form-->
                    <form id="kt_modal_new_card_form" class="form fv-plugins-bootstrap5 fv-plugins-framework"
                        method="POST" action="{{ route('deduction_head.store') }}" enctype="multipart/form-data">
                        @csrf
                        <!--begin::Input group-->
                        <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                            <!--begin::Label-->
                            <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                <span class="required">Name Deduction Head</span>
                                <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip"
                                    aria-label="Specify a card holder's name"
                                    data-bs-original-title="Specify a card holder's name" data-kt-initialized="1"></i>
                            </label>
                            <!--end::Label-->
                            <input type="text" class="form-control form-control-sm" name="name">
                            <div class="fv-plugins-message-container invalid-feedback"></div>
                        </div>
                        <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                            <label class=" form-label">Status</label>
                            <select class="form-select form-select-sm" name="status">
                                <option value="">--Select Status--</option>
                                <option value="1" selected>Active</option>
                                <option value="0">Disable</option>
                            </select>
                        </div>

                        <div class="text-center pt-15">
                            <!-- <button type="reset" id="kt_modal_new_card_cancel"
                                                                                                        class="btn btn-sm btn-success me-3">Discard</button> -->
                            <a href="{{ route('deduction_head.index') }}" id="kt_modal_new_card_cancel"
                                class="btn btn-sm btn-success me-5">Cancel</a>

                            <button type="submit" id="kt_modal_new_card_submit" class="btn btn-sm btn-primary">
                                <span class="indicator-label">Submit</span>
                                <span class="indicator-progress">Please wait...
                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
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

    <!--begin::Modal - Create App-->
    <div class="modal fade" id="kt_modal_edit_card" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <!--begin::Modal content-->
            <div class="modal-content">
                <!--begin::Modal header-->
                <div class="modal-header">
                    <!--begin::Modal title-->
                    <h2>Edit Deduction Head</h2>
                    <!--end::Modal title-->
                    <!--begin::Close-->
                    <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                        <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                        <span class="svg-icon svg-icon-1">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1"
                                    transform="rotate(-45 6 17.3137)" fill="currentColor"></rect>
                                <rect x="7.41422" y="6" width="16" height="2" rx="1"
                                    transform="rotate(45 7.41422 6)" fill="currentColor"></rect>
                            </svg>
                        </span>
                        <!--end::Svg Icon-->
                    </div>
                    <!--end::Close-->
                </div>
                <!--end::Modal header-->
                <!--begin::Modal body-->
                <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                    <!--begin::Form-->
                    <form id="kt_modal_new_card_form" class="form fv-plugins-bootstrap5 fv-plugins-framework"
                        method="POST" action="{{ route('deduction_head.update', ':deductionHeadId') }}"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <!--begin::Input group-->
                        <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                            <!--begin::Label-->
                            <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                <span class="required">Name Deduction Head</span>
                                <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip"
                                    aria-label="Specify a card holder's name"
                                    data-bs-original-title="Specify a card holder's name" data-kt-initialized="1"></i>
                            </label>
                            <!--end::Label-->
                            <input type="text" class="form-control form-control-sm" name="name">
                            <div class="fv-plugins-message-container invalid-feedback"></div>
                        </div>
                        <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                            <label class=" form-label">Status</label>
                            <select class="form-select form-select-sm" name="status">
                                <option value="">--Select Status--</option>
                                <option value="1" selected>Active</option>
                                <option value="0">Disable</option>
                            </select>
                        </div>

                        <div class="text-center pt-15">
                            <!-- <button type="reset" id="kt_modal_new_card_cancel"
                                                                                                        class="btn btn-sm btn-success me-3">Discard</button> -->

                            <a href="{{ route('loan_option.index') }}" id="kt_modal_new_card_cancel"
                                class="btn btn-sm btn-success me-5">Cancel</a>

                            <button type="submit" id="kt_modal_new_card_submit" class="btn btn-sm btn-primary">
                                <span class="indicator-label">Submit</span>
                                <span class="indicator-progress">Please wait...
                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
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

</x-default-layout>

<script type="text/javascript">
    function openEditModal(button) {
        // Get the paysliptype data from the button's data attribute
        const deductionHeadData = JSON.parse(button.getAttribute('data-deductionhead'));

        // Update the form action with the paysliptype ID
        const formAction = $('#kt_modal_edit_card').find('#kt_modal_new_card_form').attr('action');
        const deductionHeadId = deductionHeadData.id;
        const updatedFormAction = formAction.replace(':deductionHeadId', deductionHeadId);
        $('#kt_modal_edit_card').find('#kt_modal_new_card_form').attr('action', updatedFormAction);

        // Update the form fields with the paysliptype data
        $('#kt_modal_edit_card').find('input[name="name"]').val(deductionHeadData.name);
        $('#kt_modal_edit_card').find('select[name="status"]').val(deductionHeadData.status);

        // Open the modal
        $('#kt_modal_edit_card').modal('show');
    }
</script>

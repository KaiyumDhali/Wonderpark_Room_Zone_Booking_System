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
    <!--end::Message Content-->
    {{-- ------------------- Payroll Head Start ----------------------------- --}}
    <div class="col-md-12 col-12 pb-15">
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
                            <h3>Payroll Head</h3>
                        </div>
                        <!--begin::Card toolbar-->
                        <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                            @can('create payroll head')
                                <a href="#" class="btn btn-sm btn-flex btn-light-primary" data-bs-toggle="modal"
                                    data-bs-target="#payrollHead_card">
                                    Add New
                                </a>
                            @endcan
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <table class="table table-striped table-bordered align-middle table-row-dashed fs-7 gy-5 mb-0">
                            <thead>
                                <!--begin::Table row-->
                                <tr class="text-start fs-7 text-uppercase gs-0">
                                    <th class="min-w-30px"> SL</th>
                                    <th class="min-w-110px"> Name</th>
                                    <th class="min-w-110px"> Type</th>
                                    <th class="min-w-50px"> Status</th>
                                    <th class="min-w-50px"> Created By</th>
                                    @can('write payroll head')
                                        <th class="text-end min-w-50px">Action</th>
                                    @endcan
                                </tr>
                                <!--end::Table row-->
                            </thead>
                            <tbody class="fw-semibold text-gray-700">
                                @foreach ($payrollHeads as $payrollHead)
                                    <tr>
                                        <td>
                                            {{ $loop->iteration }}
                                        </td>
                                        <td>
                                            {{ $payrollHead->name }}
                                        </td>
                                        <td>
                                            <div
                                                class="badge badge-light-{{ $payrollHead->type == 'Income' ? 'success' : 'info' }}">
                                                {{ $payrollHead->type == 'Income' ? 'Income' : 'Deduction' }}</div>
                                        </td>
                                        <td>
                                            <div
                                                class="badge badge-light-{{ $payrollHead->status == 1 ? 'success' : 'info' }}">
                                                {{ $payrollHead->status == 1 ? 'Active' : 'Disabled' }}</div>
                                        </td>
                                        <td>
                                            {{ $payrollHead->created_by }}
                                        </td>
                                        @can('write payroll head')
                                            <td class="text-end">
                                                <form method="POST"
                                                    action="{{ route('payroll_head.destroy', $payrollHead->id) }}">
                                                    {{ csrf_field() }}
                                                    {{ method_field('DELETE') }}
                                                    <button type="button" class="btn btn-sm btn-success p-2"
                                                        data-bs-toggle="tooltip" data-bs-placement="top" title="Edit"
                                                        data-payrollHead="{{ json_encode($payrollHead) }}"
                                                        onclick="openEditModalpayrollHeadId(this)">
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
        <div class="modal fade" id="payrollHead_card" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered mw-650px">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2>Add Payroll Head </h2>
                        <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                            <i style="font-size: 20px" class="fas fa-times"></i>
                        </div>
                    </div>
                    <div class="modal-body scroll-y mx-5 mx-xl-15 my-5">
                        <form id="new_card_form" class="form fv-plugins-bootstrap5 fv-plugins-framework" method="POST"
                            action="{{ route('payroll_head.store') }}" enctype="multipart/form-data">
                            @csrf
                            <!--begin::Input group-->
                            <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                    <span class="required">Name</span>
                                </label>
                                <input type="text" class="form-control form-control-sm" name="name" required>
                            </div>
                            
                            <!--begin::Input group-->
                            <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                    <span>Type</span>
                                </label>
                                <select class="form-select form-select-sm" data-control="select2" name="type">
                                    <option value="">Select Type </option>
                                    <option value="Income" selected>Income</option>
                                    <option value="Deduction">Deduction</option>
                                </select>
                            </div>

                            <!--begin::Input group-->
                            <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                    <span>Status</span>
                                </label>
                                <select class="form-select form-select-sm" data-control="select2" name="status">
                                    <option value="">Select Status</option>
                                    <option value="1" selected>Active</option>
                                    <option value="0">Disable</option>
                                </select>
                            </div>

                            <div class="text-center pt-5">
                                <a href="{{ route('payroll_head.index') }}" id="new_card_cancel"
                                    class="btn btn-sm btn-success me-5">Cancel</a>

                                <button type="submit" id="new_card_submit" class="btn btn-sm btn-primary">
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
        <!--begin:: Edit Modal - Create App-->
        <div class="modal fade" id="payrollHead_edit_card" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered mw-650px">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2>Edit Payroll Head</h2>
                        <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                            <i style="font-size: 20px" class="fas fa-times"></i>
                        </div>
                    </div>
                    <div class="modal-body scroll-y mx-5 mx-xl-15 my-5">
                        <form id="payrollHead_edit_card_form"
                            class="form fv-plugins-bootstrap5 fv-plugins-framework" method="POST"
                            action="{{ route('payroll_head.update', ':payrollHeadId') }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <!--begin::Input group-->
                            <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                    <span class="required">Name</span>
                                </label>
                                <input type="text" class="form-control form-control-sm" name="name" required>
                            </div>
                            
                            <!--begin::Input group-->
                            <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                    <span>Type</span>
                                </label>
                                <select class="form-select form-select-sm" data-control="select2" name="type">
                                    <option selected disabled>Select Type</option>
                                    <option value="Income">Income</option>
                                    <option value="Deduction">Deduction</option>
                                </select>
                            </div>

                            <!--begin::Input group-->
                            <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                    <span>Status</span>
                                </label>
                                <select class="form-select form-select-sm" data-control="select2" name="status">
                                    <option selected disabled>Select Status</option>
                                    <option value="1" selected>Active</option>
                                    <option value="0">Disable</option>
                                </select>
                            </div>

                            <div class="text-center pt-5">
                                <a href="{{ route('payroll_head.index') }}" id="edit_card_cancel"
                                    class="btn btn-sm btn-success me-5">Cancel</a>

                                <button type="submit" id="edit_card_submit" class="btn btn-sm btn-primary">
                                    <span class="indicator-label">Submit</span>
                                    <span class="indicator-progress">Please wait...
                                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
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

</x-default-layout>

<script type="text/javascript">
    // Employee Type Edit Modal Start
    function openEditModalpayrollHeadId(button) {
        const payrollHeadData = JSON.parse(button.getAttribute('data-payrollHead'));
        // consol.log(payrollHeadData);
        const formAction = $('#payrollHead_edit_card').find('#payrollHead_edit_card_form').attr('action');
        const payrollHeadId = payrollHeadData.id;
        const updatedFormAction = formAction.replace(':payrollHeadId', payrollHeadId);
        $('#payrollHead_edit_card').find('#payrollHead_edit_card_form').attr('action', updatedFormAction);

        $('#payrollHead_edit_card').find('input[name="name"]').val(payrollHeadData.name);
        $('#payrollHead_edit_card').find('select[name="type"]').val(payrollHeadData.type);
        $('#payrollHead_edit_card').find('select[name="status"]').val(payrollHeadData.status);

        $('#payrollHead_edit_card').modal('show');
    }
</script>

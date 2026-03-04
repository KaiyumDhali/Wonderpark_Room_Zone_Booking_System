<x-default-layout>
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
                    <h3 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">Update
                    Supplier</h3>
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
                    class="form d-flex flex-column flex-lg-row fv-plugins-bootstrap5 fv-plugins-framework" method="POST"
                    action="{{ route('suppliers.update', $supplier->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <!--begin::Main column-->
                    <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
                        <!--begin::General options-->
                        <div class="card card-flush py-4">
                            <!--begin::Card header-->
                            {{-- <div class="card-header">
                                <div class="card-title">
                                    <h2>General</h2>
                                </div>
                            </div> --}}
                            <!--end::Card header-->
                            <div class="row">
                                {{-- <div class="col-12 col-md-6">
                                    <div class="card-body pt-0 pb-3">
                                        <div class="fv-row fv-plugins-icon-container">
                                            <label class=" form-label">Start Date</label>
                                             <input type="date" name="start_date" class="form-control form-control-sm mb-2"
                                             placeholder="Start Date" value="{{$supplier->start_date}}">
                                            <div class="fv-plugins-message-container invalid-feedback"></div>
                                        </div>
                                    </div>
                                </div> --}}
                                <div class="col-12 col-md-6">
                                    <div class="card-body pt-0 pb-3">
                                        <div class="fv-row fv-plugins-icon-container">
                                            <label class=" form-label">Supplier Code</label>
                                            <input type="text" name="supplier_code" class="form-control form-control-sm mb-2"
                                                placeholder="Supplier Code" value="{{$supplier->supplier_code}}" >
                                            <div class="fv-plugins-message-container invalid-feedback"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="card-body pt-0 pb-3">
                                        <div class="fv-row fv-plugins-icon-container">
                                            <label class="required form-label">Supplier Name</label>
                                            <input type="text" name="supplier_name" class="form-control form-control-sm mb-2"
                                                placeholder="Supplier Name" value="{{$supplier->supplier_name}}" required>
                                            <div class="fv-plugins-message-container invalid-feedback"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="card-body pt-0 pb-3">
                                        <div class="fv-row fv-plugins-icon-container">
                                            <label class=" form-label">Supplier Proprietor Name</label>
                                            <input type="text" name="supplier_proprietor_name" class="form-control form-control-sm mb-2"
                                                placeholder="Supplier Proprietor Name" value="{{$supplier->supplier_proprietor_name}}">
                                            <div class="fv-plugins-message-container invalid-feedback"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <div class="card-body pt-0 pb-3">
                                        <div class="fv-row fv-plugins-icon-container">
                                            <label class="required form-label">Supplier Mobile</label>
                                            <input type="text" name="supplier_mobile" class="form-control form-control-sm mb-2"
                                                placeholder="Supplier Mobile" value="{{$supplier->supplier_mobile}}" required>
                                            <div class="fv-plugins-message-container invalid-feedback"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="card-body pt-0 pb-3">
                                        <div class="fv-row fv-plugins-icon-container">
                                            <label class=" form-label">Supplier Email</label>
                                            <input type="text" name="supplier_email" class="form-control form-control-sm mb-2"
                                                placeholder="Supplier Email" value="{{$supplier->supplier_email}}">
                                            <div class="fv-plugins-message-container invalid-feedback"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <div class="card-body pt-0 pb-3">
                                        <div class="fv-row fv-plugins-icon-container">
                                            <label class="required form-label">Supplier Address</label>
                                            <input type="text" name="supplier_address" class="form-control form-control-sm mb-2"
                                                placeholder="Supplier Address" value="{{$supplier->supplier_address}}" required>
                                            <div class="fv-plugins-message-container invalid-feedback"></div>
                                        </div>
                                    </div>
                                </div>
                                {{-- <div class="col-12 col-md-6">
                                    <div class="card-body pt-0 pb-3">
                                        <div class="fv-row fv-plugins-icon-container">
                                            <label class=" form-label">Representative Name</label>
                                            <input type="text" name="representative_name" class="form-control form-control-sm mb-2"
                                                placeholder="Representative Name" value="{{$supplier->representative_name}}">
                                            <div class="fv-plugins-message-container invalid-feedback"></div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-12 col-md-6">
                                    <div class="card-body pt-0 pb-3">
                                        <div class="fv-row fv-plugins-icon-container">
                                            <label class=" form-label">Representative Mobile</label>
                                            <input type="text" name="representative_mobile" class="form-control form-control-sm mb-2"
                                                placeholder="Representative Mobile" value="{{$supplier->representative_mobile}}">
                                            <div class="fv-plugins-message-container invalid-feedback"></div>
                                        </div>
                                    </div>
                                </div> --}}
                                {{-- <div class="col-12 col-md-6">
                                    <div class="card-body pt-0 pb-3">
                                        <div class="fv-row fv-plugins-icon-container">
                                            <label class=" form-label">Previous Due</label>
                                            <input type="text" name="is_previous_due" class="form-control form-control-sm mb-2"
                                                placeholder="Previous Due" value="">
                                            <div class="fv-plugins-message-container invalid-feedback"></div>
                                        </div>
                                    </div>
                                </div> --}}
                                <div class="col-12 col-md-6">
                                    <div class="card-body pt-0 pb-3">
                                        <div class="fv-row fv-plugins-icon-container">
                                            <label class=" form-label">Status</label>
                                            <select class="form-select form-select-sm" data-control="select2" name="status" >
                                                <option value="1" {{ $supplier->status==1?'selected':'' }}>Active</option>
                                                <option value="0" {{ $supplier->status==0?'selected':'' }}>Disable</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-end">
                            <!--begin::Button-->
                            <a href="{{ route('suppliers.index') }}"
                                id="kt_ecommerce_add_product_cancel" class="btn btn-sm btn-success me-5">Cancel</a>
                            <!--end::Button-->
                            <!--begin::Button-->
                            <button type="submit" id="kt_ecommerce_add_category_submit" class="btn btn-sm btn-primary">
                                <span class="indicator-label">Update </span>
                                <span class="indicator-progress">Please wait...
                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
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
</x-default-layout>
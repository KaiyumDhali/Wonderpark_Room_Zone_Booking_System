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
                        <h3 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">Add
                            Group</h3>
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
                <div id="kt_app_content_container" class="app-container">
                    <form id="kt_ecommerce_add_category_form"
                        class="form d-flex flex-column flex-lg-row fv-plugins-bootstrap5 fv-plugins-framework" method="POST"
                        action="{{ route('finances.store') }}" enctype="multipart/form-data">
                        @csrf
                        
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
                                    
                                    <div class="col-12 col-md-4">
                                        <div class="card-body pt-0 pb-3">
                                            <div class="fv-row fv-plugins-icon-container">
                                                <label class=" form-label">Select Master Group</label>
                                                <select class="form-select form-select-sm" name="group_code" >
                                                    <option value="" selected>--Select Master Group--</option>
                                                    <option value="1">Asset</option>
                                                    <option value="2">Liabilities</option>
                                                    <option value="3">Expenses</option>
                                                    <option value="4">Income</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-12 col-md-4">
                                        <div class="card-body pt-0 pb-3">
                                            <div class="fv-row fv-plugins-icon-container">
                                                <label class=" form-label">Group Name</label>
                                                <input type="text" name="group_name" class="form-control form-control-sm mb-2"
                                                    placeholder="Group Name" value="">
                                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-12 col-md-4">
                                        <div class="card-body pt-0 pb-3">
                                            <div class="fv-row fv-plugins-icon-container">
                                                <label class=" form-label">Under Main Group</label>
                                                <select class="form-select form-select-sm" name="group_parents" >
                                                    <option value="" selected>--Select Master Group--</option>
                                                    <option value="1">Asset</option>
                                                    <option value="2">Liabilities</option>
                                                    <option value="3">Expenses</option>
                                                    <option value="4">Income</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    
    
                                </div>
                                
                            </div>
                            <!--end::General options-->
                            <div class="d-flex justify-content-end mb-10">
                                <!--begin::Button-->
                                <a href=""
                                    id="kt_ecommerce_add_product_cancel" class="btn btn-sm btn-success me-5">Cancel</a>
                                <!--end::Button-->
                                <!--begin::Button-->
                                <button type="submit" id="kt_ecommerce_add_category_submit" class="btn btn-sm btn-primary">
                                    <span class="indicator-label">Save</span>
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
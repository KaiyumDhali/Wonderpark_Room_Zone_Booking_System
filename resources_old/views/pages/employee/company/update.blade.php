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
                        <h3 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">Add Company Setting </h3>
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
                    action="{{ route('emp_company.update', $companySetting->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                        <!--begin::Main column-->
                        <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
                            <!--begin::General options-->
                            <div class="card card-flush py-4">
                                <div class="row">
                                    
                                    <div class="col-12 col-md-6">
                                        <div class="card-body pt-0 pb-2">
                                            <div class="fv-row fv-plugins-icon-container">
                                                <label class="required form-label"> Company Name</label>
                                                <input type="text" name="company_name" class="form-control form-control-sm mb-2"
                                                    placeholder="Company Name" value="{{$companySetting->company_name}}" required>
                                                <div class="fv-plugins-messBranchage-container invalid-feedback"></div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    {{-- <div class="col-12 col-md-6">
                                        <div class="card-body pt-0 pb-2">
                                            <div class="fv-row fv-plugins-icon-container">
                                                <label class="form-label"> Proprietor Name</label>
                                                <input type="text" name="proprietor_name" class="form-control form-control-sm mb-2"
                                                    placeholder="Proprietor Name" value="{{$companySetting->proprietor_name}}" >
                                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                            </div>
                                        </div>
                                    </div> --}}
                                    <div class="col-12 col-md-6">
                                        <div class="card-body pt-0 pb-2">
                                            <div class="fv-row fv-plugins-icon-container">
                                                <label class="form-label"> Company Details</label>
                                                <input type="text" name="company_details" class="form-control form-control-sm mb-2"
                                                    placeholder="Company Details" value="{{$companySetting->company_details}}">
                                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-12 col-md-6">
                                        <div class="card-body pt-0 pb-2">
                                            <div class="fv-row fv-plugins-icon-container">
                                                <label class="form-label"> Company Address</label>
                                                <input type="text" name="company_address" class="form-control form-control-sm mb-2"
                                                    placeholder="Company Address" value="{{$companySetting->company_address}}">
                                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="card-body pt-0 pb-2">
                                            <div class="fv-row fv-plugins-icon-container">
                                                <label class="form-label"> Factory Address</label>
                                                <input type="text" name="factory_address" class="form-control form-control-sm mb-2"
                                                    placeholder="Factory Address" value="{{$companySetting->factory_address}}">
                                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="card-body pt-0 pb-2">
                                            <div class="fv-row fv-plugins-icon-container">
                                                <label class=" form-label"> Cell Number</label>
                                                <input type="text" name="company_mobile" class="form-control form-control-sm mb-2"
                                                    placeholder=" Cell Number" value="{{$companySetting->company_mobile}}">
                                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="card-body pt-0 pb-2">
                                            <div class="fv-row fv-plugins-icon-container">
                                                <label class=" form-label"> Phone Number</label>
                                                <input type="text" name="company_phone" class="form-control form-control-sm mb-2"
                                                    placeholder=" Phone Number" value="{{$companySetting->company_phone}}">
                                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="card-body pt-0 pb-2">
                                            <div class="fv-row fv-plugins-icon-container">
                                                <label class=" form-label"> E-mail</label>
                                                <input type="text" name="company_email" class="form-control form-control-sm mb-2"
                                                    placeholder=" E-mail" value="{{$companySetting->company_email}}">
                                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="card-body pt-0 pb-2">
                                            <div class="fv-row fv-plugins-icon-container">
                                                <label class=" form-label"> Website Url</label>
                                                <input type="text" name="website_url" class="form-control form-control-sm mb-2"
                                                    placeholder=" Website Url" value="{{$companySetting->website_url}}">
                                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="card-body pt-0 pb-2">
                                            <div class="fv-row fv-plugins-icon-container">
                                                <label class=" form-label"> Trade License No</label>
                                                <input type="text" name="trade_license" class="form-control form-control-sm mb-2"
                                                    placeholder=" Trade License No" value="{{$companySetting->trade_license}}">
                                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="card-body pt-0 pb-2">
                                            <div class="fv-row fv-plugins-icon-container">
                                                <label class=" form-label"> e-TIN No</label>
                                                <input type="text" name="tin_no" class="form-control form-control-sm mb-2"
                                                    placeholder=" e-TIN No" value="{{$companySetting->tin_no}}">
                                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="card-body pt-0 pb-2">
                                            <div class="fv-row fv-plugins-icon-container">
                                                <label class=" form-label"> BIN No</label>
                                                <input type="text" name="bin_no" class="form-control form-control-sm mb-2"
                                                    placeholder=" BIN No" value="{{$companySetting->bin_no}}">
                                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="card-body pt-0 pb-2">
                                            <div class="fv-row fv-plugins-icon-container">
                                                <label class=" form-label"> Currency</label>
                                                <input type="text" name="currency" class="form-control form-control-sm mb-2"
                                                    placeholder=" Currency" value="{{$companySetting->currency}}">
                                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <div class="card-body pt-0 pb-2">
                                            <div class="fv-row fv-plugins-icon-container">
                                                <label class=" form-label"> Default Left Menu Logo (170px X 60px)</label>
                                                <input type="file" name="company_logo_one" class="form-control form-control-sm mb-2"
                                                    placeholder=" Company Logo One" value="" >
                                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="card-body pt-0 pb-2">
                                            <div class="fv-row fv-plugins-icon-container">
                                                <label class=" form-label"> Default Left Small Menu Logo (80px X 70px)</label>
                                                <input type="file" name="company_logo_two" class="form-control form-control-sm mb-2"
                                                    placeholder=" Company Logo Two" value="">
                                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="card-body pt-0 pb-2">
                                            <div class="fv-row fv-plugins-icon-container">
                                                <label class=" form-label"> Company Footer Logo (170px X 60px)</label>
                                                <input type="file" name="company_footer_logo" class="form-control form-control-sm mb-2"
                                                    placeholder=" Company Footer Logo " value="">
                                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                   @can('edit discount limit')
                                    <div class="col-12 col-md-6">
                                        <div class="card-body pt-0 pb-2">
                                            <div class="fv-row fv-plugins-icon-container">
                                                <label class="form-label fw-bold">
                                                    Discount Limit (%)
                                                </label>
                                                <input type="number"
                                                    name="discount_limit"
                                                    class="form-control form-control-sm mb-2"
                                                    min="0"
                                                    max="100"
                                                    value="{{ old('discount_limit', $companySetting->discount_limit ?? 5) }}"
                                                    placeholder="Maximum discount percentage">
                                                <div class="fv-plugins-message-container invalid-feedback"></div>

                                                <small class="text-muted">
                                                    This is the maximum discount allowed for bookings
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                    @endcan
                                   @can('edit multiple spot discount limit')
                                    <div class="col-12 col-md-6">
                                        <div class="card-body pt-0 pb-2">
                                            <div class="fv-row fv-plugins-icon-container">
                                                <label class="form-label fw-bold">
                                                   Multiple Spots Discount Limit (%)
                                                </label>
                                                <input type="number"
                                                    name="multiple_spot_discount_limit"
                                                    class="form-control form-control-sm mb-2"
                                                    min="0"
                                                    max="100"
                                                    value="{{ old('multiple_spot_discount_limit', $companySetting->multiple_spot_discount_limit ?? 5) }}"
                                                    placeholder="Maximum discount percentage">
                                                <div class="fv-plugins-message-container invalid-feedback"></div>

                                                <small class="text-muted">
                                                    This is the maximum discount allowed for bookings
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                    @endcan

                                    <div class="col-12 col-md-6">
                                        <div class="card-body pt-0 pb-2">
                                            <div class="fv-row fv-plugins-icon-container">
                                                <label class=" form-label">Status</label>
                                                <select class="form-select form-select-sm" data-control="select2" name="status" >
                                                    <option value="">--Select Status--</option>
                                                    <option value="1" {{ $companySetting->status==1?'selected':'' }}>Active</option>
                                                    <option value="0" {{ $companySetting->status==0?'selected':'' }}>Disable</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
    
                                </div>
                                
                            </div>
                            <!--end::General options-->
                            <div class="d-flex justify-content-end">
                                <!--begin::Button-->
                                <a href="{{ route('emp_company.index') }}"
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
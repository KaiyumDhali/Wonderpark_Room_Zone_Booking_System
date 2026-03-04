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
                        <h3 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                            Customer Update</h3>
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
                        method="POST" action="{{ route('customers.update', $customer->id) }}"
                        enctype="multipart/form-data">
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

                                    <div class="col-12 col-md-6">
                                        <div class="card-body pt-0 pb-3">
                                            <div class="fv-row fv-plugins-icon-container">
                                                <label class="required form-label">Customer Type</label>
                                                <select class="form-select form-select-sm" data-control="select2" name="customer_type" required>
                                                    <option selected value="">Select Customer Type</option>
                                                    @foreach ($customerTypes as $key => $value)
                                                        <option value="{{ $key }}"
                                                            {{ $customer->customer_type == $key ? 'selected' : '' }}>
                                                            {{ $value }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="card-body pt-0 pb-3">
                                            <div class="fv-row fv-plugins-icon-container">
                                                <label class=" form-label">Customer Code</label>
                                                <input type="text" name="customer_code"
                                                    class="form-control form-control-sm mb-2"
                                                    placeholder="Customer Code" value="{{ $customer->customer_code }}">
                                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="card-body pt-0 pb-3">
                                            <div class="fv-row fv-plugins-icon-container">
                                                <label class="required  form-label">Name</label>
                                                <input type="text" name="customer_name"
                                                    class="form-control form-control-sm mb-2"
                                                    placeholder="Customer Name" value="{{ $customer->customer_name }}"
                                                    required>
                                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- <div class="col-12 col-md-6">
                                        <div class="card-body pt-0 pb-3">
                                            <div class="fv-row fv-plugins-icon-container">
                                                <label class="form-label">Customer Gender</label>
                                                <select class="form-select form-select-sm" data-control="select2" name="customer_gender">
                                                    <option value="">--Select Gender--</option>
                                                    <option value="1"
                                                        {{ $customer->customer_gender == 1 ? 'selected' : '' }}>Male</option>
                                                    <option value="0"
                                                        {{ $customer->customer_gender == 0 ? 'selected' : '' }}>Female
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>                                     --}}

                                    <div class="col-12 col-md-6">
                                        <div class="card-body pt-0 pb-3">
                                            <div class="fv-row fv-plugins-icon-container">
                                                <label class="required form-label">Cell Number</label>
                                                <input type="text" name="customer_mobile"
                                                    class="form-control form-control-sm mb-2"
                                                    placeholder="Customer Mobile"
                                                    value="{{ $customer->customer_mobile }}" required>
                                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                            </div>
                                        </div>
                                    </div>
                                    

                                    {{-- <div class="col-12 col-md-6">
                                        <div class="card-body pt-0 pb-3">
                                            <div class="fv-row fv-plugins-icon-container">
                                                <label class="form-label">Customer DOB</label>
                                                <input type="date" name="customer_DOB"
                                                    class="form-control form-control-sm mb-2" placeholder="Customer DOB"
                                                    value="{{ $customer->customer_DOB }}">
                                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                            </div>
                                        </div>
                                    </div> --}}

                                    {{-- <div class="col-12 col-md-6">
                                        <div class="card-body pt-0 pb-3">
                                            <div class="fv-row fv-plugins-icon-container">
                                                <label class="form-label">NID Number</label>
                                                <input type="text" name="nid_number"
                                                    class="form-control form-control-sm mb-2" placeholder="NID Number"
                                                    value="{{ $customer->nid_number }}">
                                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="card-body pt-0 pb-3">
                                            <div class="fv-row fv-plugins-icon-container">
                                                <label class="form-label">Vat Reg No</label>
                                                <input type="text" name="vat_reg_no"
                                                    class="form-control form-control-sm mb-2" placeholder="Vat Reg No"
                                                    value="{{ $customer->vat_reg_no }}">
                                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <div class="card-body pt-0 pb-3">
                                            <div class="fv-row fv-plugins-icon-container">
                                                <label class="form-label">Tin No</label>
                                                <input type="text" name="tin_no"
                                                    class="form-control form-control-sm mb-2" placeholder="Tin No"
                                                    value="{{ $customer->tin_no }}">
                                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                            </div>
                                        </div>
                                    </div> --}}
                                    {{-- <div class="col-12 col-md-6">
                                        <div class="card-body pt-0 pb-3">
                                            <div class="fv-row fv-plugins-icon-container">
                                                <label class="form-label">Trade License</label>
                                                <input type="text" name="trade_license"
                                                    class="form-control form-control-sm mb-2"
                                                    placeholder="Trade License"
                                                    value="{{ $customer->trade_license }}">
                                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="card-body pt-0 pb-3">
                                            <div class="fv-row fv-plugins-icon-container">
                                                <label class="form-label">Discount Rate</label>
                                                <input type="text" name="discount_rate"
                                                    class="form-control form-control-sm mb-2"
                                                    placeholder="Discount Rate"
                                                    value="{{ $customer->discount_rate }}">
                                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="card-body pt-0 pb-3">
                                            <div class="fv-row fv-plugins-icon-container">
                                                <label class="form-label">Security Deposit</label>
                                                <input type="text" name="security_deposit"
                                                    class="form-control form-control-sm mb-2"
                                                    placeholder="Security Deposit"
                                                    value="{{ $customer->security_deposit }}">
                                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="card-body pt-0 pb-3">
                                            <div class="fv-row fv-plugins-icon-container">
                                                <label class="form-label">Credit Limit</label>
                                                <input type="text" name="credit_limit"
                                                    class="form-control form-control-sm mb-2"
                                                    placeholder="Credit Limit" value="{{ $customer->credit_limit }}">
                                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="card-body pt-0 pb-3">
                                            <div class="fv-row fv-plugins-icon-container">
                                                <label class="form-label">Customer Area</label>
                                                <input type="text" name="customer_area"
                                                    class="form-control form-control-sm mb-2"
                                                    placeholder="Customer Area"
                                                    value="{{ $customer->customer_area }}">
                                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                            </div>
                                        </div>
                                    </div> --}}
                                    <div class="col-12 col-md-6">
                                        <div class="card-body pt-0 pb-3">
                                            <div class="fv-row fv-plugins-icon-container">
                                                <label class="required form-label">Customer Address</label>
                                                <input type="text" name="customer_address"
                                                    class="form-control form-control-sm mb-2"
                                                    placeholder="Customer Address"
                                                    value="{{ $customer->customer_address }}">
                                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="card-body pt-0 pb-3">
                                            <div class="fv-row fv-plugins-icon-container">
                                                <label class="form-label"> Email</label>
                                                <input type="text" name="customer_email"
                                                    class="form-control form-control-sm mb-2"
                                                    placeholder="Customer Email"
                                                    value="{{ $customer->customer_email }}">
                                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="card-body pt-0 pb-3">
                                            <div class="fv-row fv-plugins-icon-container">
                                                <label class="form-label">Shipping Address</label>
                                                <input type="text" name="shipping_address"
                                                    class="form-control form-control-sm mb-2"
                                                    placeholder="Shipping Address"
                                                    value="{{ $customer->shipping_address }}">
                                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- <div class="col-12 col-md-6">
                                        <div class="card-body pt-0 pb-3">
                                            <div class="fv-row fv-plugins-icon-container">
                                                <label class="form-label">Shipping ontact</label>
                                                <input type="text" name="shipping_contact"
                                                    class="form-control form-control-sm mb-2"
                                                    placeholder="shipping_contact"
                                                    value="{{ $customer->shipping_contact }}">
                                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                            </div>
                                        </div>
                                    </div> --}}
                                    <div class="col-12 col-md-6">
                                        <div class="card-body pt-0 pb-3">
                                            <div class="fv-row fv-plugins-icon-container">
                                                <label class="form-label">Status</label>
                                                <select class="form-select form-select-sm" data-control="select2" name="status" required>
                                                    <option value="1" {{ $customer->status == 1 ? 'selected' : '' }}>
                                                        Active</option>
                                                    <option value="0" {{ $customer->status == 0 ? 'selected' : '' }}>
                                                        Disable</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                            </div>
                            <!--end::General options-->
                            <div class="d-flex justify-content-end mb-10">
                                <!--begin::Button-->
                                <a href="{{ route('customers.index') }}" id="kt_ecommerce_add_product_cancel"
                                    class="btn btn-sm btn-success me-5">Cancel</a>
                                <!--end::Button-->
                                <!--begin::Button-->
                                <button type="submit" id="kt_ecommerce_add_category_submit"
                                    class="btn btn-sm btn-primary">
                                    <span class="indicator-label">Update</span>
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

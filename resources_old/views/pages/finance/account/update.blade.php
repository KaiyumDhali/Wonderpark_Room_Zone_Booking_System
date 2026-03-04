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
                        <h3 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">Update Account</h3>
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
                          action="{{ route('accounts.update', $accounts->id) }}" enctype="multipart/form-data">
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
                                                <label class=" form-label">Account Head</label>
                                                <input type="text" name="account_name" class="form-control form-control-sm mb-2"
                                                       placeholder="Account Head" value="{{ $accounts->account_name }}">
                                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <div class="card-body pt-0 pb-3">
                                            <div class="fv-row fv-plugins-icon-container">
                                                <label class=" form-label">Group Name</label>
                                                <select class="form-select form-select-sm" name="financegroup_id" disabled="disabled">
                                                    <option value="" selected>--Finance Group--</option>
                                                    @foreach($financeGroupList as $key => $value)
                                                    <option value="{{ $value }}" {{ $accounts->financegroup_id==$key ? 'selected' : ''}}>{{ $value}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                            </div>
                            <!--end::General options-->
                            <div class="d-flex justify-content-end mb-10">
                                <!--begin::Button-->
                                <a href="{{ route('accounts.index') }}"
                                   id="kt_ecommerce_add_product_cancel" class="btn btn-sm btn-success me-5">Cancel</a>
                                <!--end::Button-->
                                <!--begin::Button-->
                                <button type="submit" id="kt_ecommerce_add_category_submit" class="btn btn-sm btn-primary">
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

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
                            Summary Report</h3>
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
                    <!--begin::Main column-->
                    <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
                        <!--begin::General options-->
                        <div class="card card-flush py-4">
                            <div class="row">
                                <div class="col-12 col-md-3">
                                    <div class="card-body pt-0 pb-3">
                                        <div class="fv-row fv-plugins-icon-container">
                                            <label class=" form-label">Start Date</label>
                                            <input type="date" id="start_date" name="start_date"
                                                class="form-control form-control-sm mb-2" placeholder="Group Name"
                                                value="">
                                            <div class="fv-plugins-message-container invalid-feedback"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-3">
                                    <div class="card-body pt-0 pb-3">
                                        <div class="fv-row fv-plugins-icon-container">
                                            <label class=" form-label">End Date</label>
                                            <input type="date" id="end_date" name="end_date"
                                                class="form-control form-control-sm mb-2" placeholder="end_date"
                                                value="">
                                            <div class="fv-plugins-message-container invalid-feedback"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-3">
                                    <div class="card-body pt-0 pb-3">
                                        <div class="fv-row fv-plugins-icon-container">
                                            <button type="submit" id="viewBtn"
                                                class="btn btn-sm btn-primary me-5 mt-8">{{ __('View') }}</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <!--end::General options-->
                    </div>
                    <!--end::Main column-->
                </div>
                <!--end::Content container-->
            </div>
            <!--end::Content-->
        </div>
        <!--end::Content wrapper-->
    </div>



    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"> --}}


    <div class="app-main flex-column flex-row-fluid my-10" id="kt_app_main">
        <!--begin::Content wrapper-->
        <div class="d-flex flex-column flex-column-fluid">

            <!--begin::Content-->
            <div id="kt_app_content" class="app-content flex-column-fluid">
                <!--begin::Content container-->
                <div id="kt_app_content_container" class="app-container">
                    <!--begin::Main column-->
                    <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
                        <!--begin::General options-->
                        <div class="card card-flush py-4">

                            <div class="container my-4">
                                <div class="row">


                                    <!-- Summary Card -->
                                    <div class="col-md-4">
                                        <div class="card shadow-sm">
                                            <div class="card-header bg-primary text-white">
                                                <h5 class="card-title mb-0" style="color: #ffffff">ERP Summary</h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-center mb-3">
                                                    <span>Total Sales</span>
                                                    <h6 class="mb-0">$450,000</h6>
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center mb-3">
                                                    <span>New Orders</span>
                                                    <h6 class="mb-0">150</h6>
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center mb-3">
                                                    <span>Inventory Stock</span>
                                                    <h6 class="mb-0">2,300</h6>
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center mb-3">
                                                    <span>Expenses</span>
                                                    <h6 class="mb-0">$75,000</h6>
                                                </div>
                                                <p class="text-muted small">Updated 10 mins ago</p>
                                            </div>
                                            <div class="card-footer bg-light d-flex justify-content-end">
                                                <button class="btn btn-primary btn-sm me-2">View Details</button>
                                                <button class="btn btn-outline-primary btn-sm">Download Report</button>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Summary Card -->
                                    <div class="col-md-4">
                                        <div class="card shadow-sm">
                                            <div class="card-header bg-primary text-white">
                                                <h5 class="card-title mb-0" style="color: #ffffff">ERP Summary</h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-center mb-3">
                                                    <span>Total Sales</span>
                                                    <h6 class="mb-0">$450,000</h6>
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center mb-3">
                                                    <span>New Orders</span>
                                                    <h6 class="mb-0">150</h6>
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center mb-3">
                                                    <span>Inventory Stock</span>
                                                    <h6 class="mb-0">2,300</h6>
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center mb-3">
                                                    <span>Expenses</span>
                                                    <h6 class="mb-0">$75,000</h6>
                                                </div>
                                                <p class="text-muted small">Updated 10 mins ago</p>
                                            </div>
                                            <div class="card-footer bg-light d-flex justify-content-end">
                                                <button class="btn btn-primary btn-sm me-2">View Details</button>
                                                <button class="btn btn-outline-primary btn-sm">Download Report</button>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Summary Card -->
                                    <div class="col-md-4">
                                        <div class="card shadow-sm">
                                            <div class="card-header bg-primary text-white">
                                                <h5 class="card-title mb-0" style="color: #ffffff">ERP Summary</h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-center mb-3">
                                                    <span>Total Sales</span>
                                                    <h6 class="mb-0">$450,000</h6>
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center mb-3">
                                                    <span>New Orders</span>
                                                    <h6 class="mb-0">150</h6>
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center mb-3">
                                                    <span>Inventory Stock</span>
                                                    <h6 class="mb-0">2,300</h6>
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center mb-3">
                                                    <span>Expenses</span>
                                                    <h6 class="mb-0">$75,000</h6>
                                                </div>
                                                <p class="text-muted small">Updated 10 mins ago</p>
                                            </div>
                                            <div class="card-footer bg-light d-flex justify-content-end">
                                                <button class="btn btn-primary btn-sm me-2">View Details</button>
                                                <button class="btn btn-outline-primary btn-sm">Download Report</button>
                                            </div>
                                        </div>
                                    </div>
                                    

                                </div>
                            </div>

                        </div>
                        <!--end::General options-->
                    </div>
                    <!--end::Main column-->
                </div>
                <!--end::Content container-->
            </div>
            <!--end::Content-->
        </div>
        <!--end::Content wrapper-->
    </div>






</x-default-layout>

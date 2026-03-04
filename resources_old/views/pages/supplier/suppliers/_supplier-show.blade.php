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
                        <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                            Supplier Details </h1>

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
                        <!-- Content Row -->
                        <div class="card shadow">
                    {{-- <div class="card-header">
                        <div class="d-sm-flex align-items-center justify-content-between mb-4">
                            <h1 class="h3 mb-0 text-gray-800">{{ __('Product Details')}}</h1>
                            <a href="{{ route('products.index') }}"
                                class="btn btn-primary btn-sm shadow-sm">{{ __('Go Back') }}</a>
                        </div>
                    </div> --}}
                            <div class="card-body">

                                <div class="row">
                                    <div class="col-md-6">
                                        <table class="table table-hover table-bordered">
                                            <tbody>
                                                <tr>
                                                    <th scope="row" class="w-50"><strong>Supplier Code:</strong></th>
                                                    <td class="w-50">{{ $supplier->supplier_code }}</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row" class="w-50"><strong>Supplier Name:</strong></th>
                                                    <td class="w-50">{{ $supplier->supplier_name }}</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row" class="w-50"><strong>Supplier Proprietor Name:</strong></th>
                                                    <td class="w-50">{{ $supplier->supplier_proprietor_name }}</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row" class="w-50"><strong>Supplier Mobile:</strong></th>
                                                    <td class="w-50">{{ $supplier->supplier_mobile }}</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row" class="w-50"><strong>Supplier Email:</strong></th>
                                                    <td class="w-50">{{ $supplier->supplier_email }}</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row" class="w-50"><strong>Supplier Address:</strong></th>
                                                    <td class="w-50">{{ $supplier->supplier_address }}</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row" class="w-50"><strong>Supplier Status:</strong></th>
                                                    <td class="w-50">
                                                        {{ $supplier->status==1?'Active':'Disabled' }}
                                                    </td>
                                                </tr>

                                            </tbody>
                                        </table>
                                    </div>
                                    {{-- <div class="col-md-6">
                                        <table class="table table-hover table-bordered">
                                            <tbody>
                                                
                                                
                                                <tr>
                                                    <th scope="row" class="w-50"><strong>Supplier Representative Mobile:</strong></th>
                                                    <td class="w-50">{{ $supplier->representative_mobile }}</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row" class="w-50"><strong>Supplier Start Date:</strong></th>
                                                    <td class="w-50">{{ $supplier->start_date }}</td>
                                                </tr>
                                                

                                            </tbody>
                                        </table>
                                    </div> --}}
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <!--begin::Button-->
                            <a class="btn btn-sm btn-success" href="{{ route('suppliers.index') }}"
                               id="kt_ecommerce_add_product_cancel" >Cancel</a>
                            <!--end::Button-->
                        </div>
                        <!-- Content Row -->
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
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
                            Customer Details </h1>
                        <!--end::Title-->
                        <!--begin::Breadcrumb-->
<!--                        <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                            begin::Item
                            <li class="breadcrumb-item text-muted">
                                <a href="../../demo1/dist/index.html" class="text-muted text-hover-primary">Home</a>
                            </li>
                            end::Item
                            begin::Item
                            <li class="breadcrumb-item">
                                <span class="bullet bg-gray-400 w-5px h-2px"></span>
                            </li>
                            end::Item
                            begin::Item
                            <li class="breadcrumb-item text-muted">eCommerce</li>
                            end::Item
                            begin::Item
                            <li class="breadcrumb-item">
                                <span class="bullet bg-gray-400 w-5px h-2px"></span>
                            </li>
                            end::Item
                            begin::Item
                            <li class="breadcrumb-item text-muted">Catalog</li>
                            end::Item
                        </ul>-->
                        <!--end::Breadcrumb-->
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
                                                    <th scope="row" class="w-50"><strong>Customer Type:</strong></th>
                                                    <td class="w-50">{{ $customer->type_name }}</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row" class="w-50"><strong>Customer Code:</strong></th>
                                                    <td class="w-50">{{ $customer->customer_code }}</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row" class="w-50"><strong>Customer Name:</strong></th>
                                                    <td class="w-50">{{ $customer->customer_name }}</td>
                                                </tr>
                                                
                                                <tr>
                                                    <th scope="row" class="w-50"><strong>Customer Mobile:</strong></th>
                                                    <td class="w-50">{{ $customer->customer_mobile }}</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row" class="w-50"><strong>Customer Email:</strong></th>
                                                    <td class="w-50">{{ $customer->customer_email }}</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row" class="w-50"><strong>Customer Address:</strong></th>
                                                    <td class="w-50">{{ $customer->customer_address }}</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row" class="w-50"><strong>Shipping Address:</strong></th>
                                                    <td class="w-50">{{ $customer->shipping_address }}</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row" class="w-50"><strong>Status:</strong></th>
                                                    <td class="w-50">
                                                        {{ $customer->status==1?'Active':'Disabled' }}

                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <table class="table table-hover table-bordered">
                                            <tbody>
                                                {{-- <tr>
                                                    <th scope="row" class="w-50"><strong>Customer Gender:</strong></th>
                                                    <td class="w-50">
                                                    {{ $customer->customer_gender==1?'Male':'Female' }}
                                                    </td>
                                                </tr> --}}
                                                {{-- <tr>
                                                    <th scope="row" class="w-50"><strong>Customer DOB:</strong></th>
                                                    <td class="w-50">{{ $customer->customer_DOB }}</td>
                                                </tr> --}}
                                                {{-- <tr>
                                                    <th scope="row" class="w-50"><strong>Customer Trade License:</strong></th>
                                                    <td class="w-50">{{ $customer->trade_license }}</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row" class="w-50"><strong>Discount Rate:</strong></th>
                                                    <td class="w-50">{{ $customer->discount_rate }}</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row" class="w-50"><strong>Security Deposit:</strong></th>
                                                    <td class="w-50">{{ $customer->security_deposit }}</td>
                                                </tr> 
                                                <tr>
                                                    <th scope="row" class="w-50"><strong>Credit Limit:</strong></th>
                                                    <td class="w-50">{{ $customer->credit_limit }}</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row" class="w-50"><strong>Customer Area:</strong></th>
                                                    <td class="w-50">{{ $customer->customer_area }}</td>
                                                </tr> --}}
                                                
                                                {{-- <tr>
                                                    <th scope="row" class="w-50"><strong>Shipping Contact:</strong></th>
                                                    <td class="w-50">{{ $customer->shipping_contact }}</td>
                                                </tr> --}}
                                                
                                                {{-- <tr>
                                                    <th scope="row" class="w-50"><strong>Customer NID:</strong></th>
                                                    <td class="w-50">{{ $customer->nid_number }}</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row" class="w-50"><strong>Customer Vat Reg No:</strong></th>
                                                    <td class="w-50">{{ $customer->vat_reg_no }}</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row" class="w-50"><strong>Customer Tin No:</strong></th>
                                                    <td class="w-50">{{ $customer->tin_no }}</td>
                                                </tr> --}}

                                            </tbody>
                                        </table>
                                    </div>

                                </div>

                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <!--begin::Button-->
                            <a class="btn btn-sm btn-success" href="{{ route('customers.index') }}"
                               id="kt_ecommerce_add_product_cancel" class="btn btn-light me-5">Back</a>
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
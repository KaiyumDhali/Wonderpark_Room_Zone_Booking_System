<x-default-layout>
    <style>
        .card .card-header {
            min-height: 40px;
        }

        .table> :not(caption)>*>* {
            padding: 0.3rem !important;
        }
    </style>
    <div class="container-fluid">
        <!-- Page Heading -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
    <div class="row">
        <!-- Product add to card Content Row -->
        <div class="col-md-8">
            <form class="px-0" id="salesform" method="POST" action="{{ route('sales.store') }}"
                enctype="multipart/form-data">
                @csrf
                <div class="row">
                    {{-- select product --}}
                    <div class="col-md-12 card shadow">
                        <div class="card-header px-2 mx-0">
                            <div class="d-sm-flex align-items-center justify-content-between">
                                <h1 class="h3 mb-0 text-gray-800">{{ __('Sales') }}</h1>
                            </div>
                        </div>
                        <div class="card-body py-3 px-2">
                            <div class="col-md-12 pb-3">
                                <div class="row">
                                    <div class="form-group col-md-2 pt-0 pb-2">
                                        <label class="required">{{ __('Sales Date') }}</label>
                                        <input type="date" class="form-control form-control-sm form-control-solid"
                                            id="voucher_date" name="voucher_date" value="" required />
                                    </div>

                                    <div class="form-group col-md-4 pt-0 pb-2">
                                        <div class="card-body px-0 pb-2 pt-0 d-flex align-items-center">
                                            <div class="flex-grow-1">
                                                <label class="required">{{ __('Select Customer') }}</label>
                                                <select id="changeCustomer" class="form-control form-control-sm"
                                                    name="customer_id" data-control="select2"
                                                    data-placeholder="Select Customer" required>
                                                    <option></option>
                                                    @foreach ($customerAccounts as $customerAccount)
                                                        <option value="{{ $customerAccount->id }}">
                                                            {{ $customerAccount->account_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <a href="#" class="btn btn-sm btn-flex btn-light-primary ms-2 mt-5"
                                                data-bs-toggle="modal" data-bs-target="#add_customer_modal">
                                                Add
                                            </a>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-3 d-none customerInfo">
                                        <div class="card mt-5 p-2">
                                            <label for="" id="customerAdd4"></label>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-3 d-none customerInfo">
                                        <div class="card mt-5 p-2">
                                            <label for="" id="customerAdd3"></label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group col-md-6 pt-0 pb-2">
                                        <label>{{ __('Select Warehouse') }}</label>
                                        <select id="warehouseId" class="form-select form-select-sm" name="warehouse_id"
                                            data-control="select2" data-hide-search="false">
                                            <option value="">Select Warehouse</option>
                                            @foreach ($warehouses as $warehouse)
                                                <option value="{{ $warehouse->id }}">
                                                    {{ $warehouse->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6 pt-0 pb-2">
                                        <label>{{ __('Select Product') }}</label>
                                        <select id="changeProduct" class="form-select form-select-sm" name="product_id"
                                            data-control="select2" data-hide-search="false">
                                            <option value="">Select Product</option>
                                            @foreach ($products as $product)
                                                <option value="{{ $product->id }}">Name: {{ $product->product_name }}
                                                    (Code: {{ $product->product_code }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group col-md-4 my-3">
                                        <label for="order">{{ __('Product Name') }}</label>
                                        <input type="text" class="form-control form-control-sm form-control-solid"
                                            readonly id="product_name" name="product_name " />
                                    </div>
                                    <div class="form-group col-md-2 my-3">
                                        <label for="order">{{ __('Code') }}</label>
                                        <input type="text" class="form-control form-control-sm form-control-solid"
                                            readonly id="product_code" name="product_code " />
                                    </div>
                                    <div class="form-group col-md-2 my-3">
                                        <label for="order">{{ __('Unit Name') }}</label>
                                        <input type="text" class="form-control form-control-sm form-control-solid"
                                            readonly id="sales_unit" name="sales_unit " />
                                    </div>
                                    <div class="form-group col-md-2 my-3">
                                        <label for="order">{{ __('Price (Per Unit)') }}</label>
                                        <input type="text" class="form-control form-control-sm" id="sales_price"
                                            name="sales_price"
                                            oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                    </div>
                                    <div class="form-group col-md-2 my-3">
                                        <label for="order">{{ __('Present Stock') }}</label>
                                        <input type="text" class="form-control form-control-sm form-control-solid"
                                            id="present_stock" name="present_stock" step="1" min="0"
                                            oninput="this.value = this.value.replace(/\D/g, '');" readonly>
                                    </div>
                                    <div class="form-group col-md-2 my-3">
                                        <label for="order">{{ __('Quantity') }}</label>
                                        <input type="text" class="form-control form-control-sm"
                                            id="productQuantity" name="quantity"
                                            oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                    </div>
                                    <div class="form-group col-md-2 my-3">
                                        <label for="order">{{ __('Amount') }}</label>
                                        <input type="text" class="form-control form-control-sm form-control-solid"
                                            id="quantityAmount" name="Amount" value=0 readonly />
                                    </div>

                                    <div class="form-group col-md-2 my-3">
                                        <label class="">Discount Type</label>
                                        <select class="form-select form-select-sm" name="type"
                                            id="percentageType">
                                            <option value="">--Select Type--</option>
                                            <option value="1" selected>Fixed</option>
                                            <option value="2">Percentage</option>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-2 d-none my-3" id="percentageInputContainer">
                                        <label class=""><span class="">Percentage </span></label>
                                        <input type="text" class="form-control form-control-sm" name="percentage"
                                            id="percentageInput"
                                            oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                    </div>

                                    <div class="form-group col-md-2 my-3" id="amountInputContainer">
                                        <label class=""><span>Discount </span> </label>
                                        <input type="text" class="form-control form-control-sm" id="discount"
                                            name="discount"
                                            oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                    </div>

                                    <div class="form-group col-md-2 my-3">
                                        <button type="button" id="addProduct"
                                            class="btn btn-sm btn-primary px-5 mt-6">{{ __('Add to Cart') }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- add to product --}}
                    <div class="col-md-12 card shadow my-5 px-5 mx-0">
                        <div id="cart_product">
                            <table id="product_add" class="table table-bordered mt-4">
                                <tr class="text-center">
                                    <th>SL</th>
                                    <th>Product Name</th>
                                    <th>Unit</th>
                                    <th class="w-100px">Quantity</th>
                                    <th>Sale Price</th>
                                    <th>Discount</th>
                                    <th>Amount</th>
                                    <th></th>
                                </tr>
                            </table>

                            <table id="sumAmountShow" class="table table-bordered">

                            </table>
                        </div>

                    </div>
                </div>
        </div>
        <!-- Product sales summary Content Row -->
        <div class="col-md-4">
            <div class="card shadow px-10 pb-8 ms-0 ms-md-5">
                <div class="card-header my-3 mx-0 px-2">
                    <div class="d-sm-flex align-items-center justify-content-between">
                        <h1 class="h3 mb-0 text-gray-800">{{ __('Sales Summary') }}</h1>
                    </div>
                </div>
                <div class="form-group">
                    <label for="order">{{ __('Net Total Amount') }}</label>
                    <input type="number" class="form-control form-control-sm form-control-solid" id="netTotalAmount"
                        name="netTotalAmount" value=0 readonly />
                </div>

                <div class="card card-flush h-xl-100 my-3">
                    <!--begin::Header-->
                    <div class="card-header py-0">
                        <!--begin::Title-->
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold text-gray-800">Payment Method</span>
                        </h3>
                        <!--end::Title-->
                    </div>
                    <!--end::Header-->
                    <!--begin::Body-->
                    <div class="card-body pt-0">
                        <!--begin::Nav-->
                        <ul class="nav nav-pills nav-pills-custom my-0">
                            <!--begin::Item-->
                            <li class="nav-item my-3 me-3 me-lg-6">
                                <!--begin::Link-->
                                <a class="paymentType nav-link btn btn-outline btn-flex btn-color-muted btn-active-color-primary flex-column overflow-hidden w-60px h-65px pt-3 pb-2 active"
                                    id="kt_stats_widget_16_tab_link_1" data-bs-toggle="pill"
                                    href="#kt_stats_widget_16_tab_1" data-value="Cash">
                                    <!--begin::Icon-->
                                    <div class="nav-icon">
                                        <i class="fa-solid fa-bangladeshi-taka-sign pb-2"
                                            style="font-size: 20px;"></i>
                                    </div>
                                    <!--end::Icon-->
                                    <!--begin::Title-->
                                    <span class="nav-text text-gray-800 fw-bold fs-7 lh-1">Cash</span>
                                    <!--end::Title-->
                                    <!--begin::Bullet-->
                                    <span
                                        class="bullet-custom position-absolute bottom-0 w-100 h-4px bg-primary"></span>
                                    <!--end::Bullet-->
                                </a>
                                <!--end::Link-->
                            </li>
                            <!--end::Item-->
                            <!--begin::Item-->
                            <li class="nav-item my-3 me-3 me-lg-6">
                                <!--begin::Link-->
                                <a class="paymentType nav-link btn btn-outline btn-flex btn-color-muted btn-active-color-primary flex-column overflow-hidden w-60px h-65px pt-3 pb-2"
                                    id="kt_stats_widget_16_tab_link_2" data-bs-toggle="pill"
                                    href="#kt_stats_widget_16_tab_2" data-value="Bank">
                                    <!--begin::Icon-->
                                    <div class="nav-icon">
                                        <i class="fa-solid fa-building-columns pb-2" style="font-size: 20px;"></i>
                                    </div>
                                    <!--end::Icon-->
                                    <!--begin::Title-->
                                    <span class="nav-text text-gray-800 fw-bold fs-7 lh-1">Bank</span>
                                    <!--end::Title-->
                                    <!--begin::Bullet-->
                                    <span
                                        class="bullet-custom position-absolute bottom-0 w-100 h-4px bg-primary"></span>
                                    <!--end::Bullet-->
                                </a>
                                <!--end::Link-->
                            </li>
                            <!--end::Item-->
                            <!--begin::Item-->
                            <li class="nav-item my-3 me-3 me-lg-6">
                                <!--begin::Link-->
                                <a class="paymentType nav-link btn btn-outline btn-flex btn-color-muted btn-active-color-primary flex-column overflow-hidden w-60px h-65px pt-3 pb-2"
                                    id="kt_stats_widget_16_tab_link_3" data-bs-toggle="pill"
                                    href="#kt_stats_widget_16_tab_3" data-value="mBank">
                                    <!--begin::Icon-->
                                    <i class="fa-solid fa-mobile pb-2" style="font-size: 20px;"></i>
                                    <!--end::Icon-->
                                    <!--begin::Title-->
                                    <span class="nav-text text-gray-800 fw-bold fs-7 lh-1">mBank</span>
                                    <!--end::Title-->
                                    <!--begin::Bullet-->
                                    <span
                                        class="bullet-custom position-absolute bottom-0 w-100 h-4px bg-primary"></span>
                                    <!--end::Bullet-->
                                </a>
                                <!--end::Link-->
                            </li>
                            <!--end::Item-->
                        </ul>
                        <!--end::Nav-->
                        <!--begin::Tab Content-->
                        <div class="tab-content">
                            <div class="form-group">
                                <label>{{ __('Receive Account') }}</label>
                                <select id="changeAccountsName" class="form-control form-control-sm"
                                    name="receive_account" data-control="select2">
                                    <option value="">Select Payment Account</option>
                                    @foreach ($toAccounts as $toAccount)
                                        <option value="{{ $toAccount->id }}">
                                            {{ $toAccount->account_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <!--begin::Tap cash pane-->
                            {{-- <div class="tab-pane fade show active" id="kt_stats_widget_16_tab_1">
                                
                            </div> --}}
                            <!--end::Tap pane-->
                            <!--begin::Tap bank pane-->
                            <div class="tab-pane fade" id="kt_stats_widget_16_tab_2">
                                {{-- <div class="form-group">
                                    <label>{{ __('Select Bank') }}</label>
                                    <select id="" class="form-control form-control-sm" name="bank_name"
                                        data-control="select2" data-placeholder="Select Bank">
                                        <option value="">Select Bank</option>
                                        <option value="Shahjalal Islami Bank Limited">Shahjalal Islami Bank
                                            Limited</option>
                                        <option value="Islami Bank Bangladesh Ltd">Islami Bank Bangladesh Ltd
                                        </option>
                                        <option value="Dutch Bangla Bank">Dutch Bangla Bank</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>{{ __('Select Branch') }}</label>
                                    <select id="" class="form-control form-control-sm" name="branch_name"
                                        data-control="select2" data-placeholder="Select Branch">
                                        <option value="">Select Branch</option>
                                        <option value="Gulshan Branch">Gulshan Branch</option>
                                        <option value="Dhanmondi Branch">Dhanmondi Branch</option>
                                        <option value="Banani Branch">Banani Branch</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="returnAmount">A/C No</label>
                                    <input type="text" class="form-control form-control-sm" name="ac_no"
                                        value="0"
                                        oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" />
                                </div> --}}
                                <div class="form-group">
                                    <label>{{ __('Select Type') }}</label>
                                    <select id="chequeType" class="form-control form-control-sm" name="cheque_type"
                                        data-control="select2" data-placeholder="Select Cheque Type">
                                        <option value="">Select Type</option>
                                        <option value="Cheque">Cheque </option>
                                        <option value="Cash Deposit">Cash Deposit </option>
                                        <option value="Online">Online</option>
                                        <option value="CHT">CHT</option>
                                        <option value="RTGS">RTGS</option>
                                    </select>
                                </div>
                                <div id="bankInfo" class="d-none">
                                    <div class="form-group">
                                        <label for="returnAmount">Cheque No</label>
                                        <input type="text" class="form-control form-control-sm" name="cheque_no"
                                            value="0" />
                                    </div>
                                    <div class="form-group">
                                        <label for="">Cheque Date</label>
                                        <input type="date" class="form-control form-control-sm"
                                            name="cheque_date" />
                                    </div>
                                </div>
                            </div>
                            <!--end::Tap pane-->
                            <!--begin::Tap mobile bank pane-->
                            <div class="tab-pane fade" id="kt_stats_widget_16_tab_3">
                                <div class="form-group">
                                    <label>{{ __('Select Mobile Bank') }}</label>
                                    <select id="" class="form-control form-control-sm"
                                        name="mobile_bank_name" data-control="select2"
                                        data-placeholder="Select Mobile Bank">
                                        <option value="">Select Mobile Bank</option>
                                        <option value="bKash">bKash</option>
                                        <option value="Nagad">Nagad</option>
                                        <option value="Rocket">Rocket</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="">Mobile Number</label>
                                    <input type="text" class="form-control form-control-sm" name="mobile_number"
                                        value="0" />
                                </div>
                                <div class="form-group">
                                    <label for="">Transaction ID</label>
                                    <input type="text" class="form-control form-control-sm" name="transaction_id"
                                        value="0" />
                                </div>
                            </div>
                            <!--end::Tap pane-->

                            <div class="form-group">
                                <label for="givenAmount">{{ __('Receive Amount') }}</label>
                                <input type="text" class="form-control form-control-sm" id="givenAmount"
                                    name="givenAmount" value="0"
                                    oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                            </div>
                            <div class="form-group">
                                <label for="returnAmount"><span id="dueOrReturn"></span> Amount</label>
                                <input type="number" class="form-control form-control-sm form-control-solid"
                                    id="returnAmount" name="returnAmount" value="0" readonly />
                            </div>
                        </div>
                        <!--end::Tab Content-->
                    </div>
                    <!--end: Card Body-->
                </div>
                <div class="form-group">
                    <label for="remarks">{{ __('Remarks') }}</label>
                    <input type="text" class="form-control form-control-sm" id="remarks" name="remarks" />
                </div>
                <button type="submit" id="submitBtn"
                    class="btn btn-sm btn-success px-5 mt-5">{{ __('Sales') }}</button>

            </div>
        </div>
        </form>
    </div>
    </div>

    {{-- add new customer modal --}}
    <div class="modal fade" id="add_customer_modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Add Customer</h2>
                    <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                        <i style="font-size: 20px" class="fas fa-times"></i>
                    </div>
                </div>
                <div class="modal-body scroll-y mx-5 mx-xl-15 my-5">
                    <form id="new_card_form" class="form fv-plugins-bootstrap5 fv-plugins-framework" method="POST"
                        action="{{ route('customers.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="card-body pt-0 pb-3">
                                    <div class="fv-row fv-plugins-icon-container">
                                        <label class="required form-label">Customer Type</label>
                                        <select class="form-select form-select-sm" name="customer_type" required>
                                            <option value="">Select Customer Type</option>
                                            @foreach ($customerTypes as $key => $value)
                                                <option value="{{ $key }}">{{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="col-12 col-md-6">
                                <div class="card-body pt-0 pb-3">
                                    <div class="fv-row fv-plugins-icon-container">
                                        <label class="required form-label">Customer Code</label>
                                        <input type="text" name="customer_code"
                                            class="form-control form-control-sm mb-2" placeholder="Customer Code"
                                            value="" required>
                                        <div class="fv-plugins-message-container invalid-feedback"></div>
                                    </div>
                                </div>
                            </div> --}}
                            <div class="col-12 col-md-6">
                                <div class="card-body pt-0 pb-3">
                                    <div class="fv-row fv-plugins-icon-container">
                                        <label class="required form-label">Name</label>
                                        <input type="text" name="customer_name"
                                            class="form-control form-control-sm mb-2" placeholder="Customer Name"
                                            value="" required>
                                        <input type="text" class="form-control form-control-sm" name="sumite_type"
                                            value="1" hidden>
                                        <div class="fv-plugins-message-container invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="col-12 col-md-6">
                                <div class="card-body pt-0 pb-3">
                                    <div class="fv-row fv-plugins-icon-container">
                                        <label class="form-label">Gender</label>
                                        <select class="form-select form-select-sm" name="customer_gender">
                                            <option value="" selected>--Select Gender--</option>
                                            <option value="1">Male</option>
                                            <option value="0">Female</option>
                                        </select>
                                    </div>
                                </div>
                            </div> --}}
                            <div class="col-12 col-md-6">
                                <div class="card-body pt-0 pb-3">
                                    <div class="fv-row fv-plugins-icon-container">
                                        <label class="required form-label">Cell Number</label>
                                        <input type="text" name="customer_mobile"
                                            class="form-control form-control-sm mb-2" placeholder="Customer Mobile"
                                            value="" required>
                                        <div class="fv-plugins-message-container invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="card-body pt-0 pb-3">
                                    <div class="fv-row fv-plugins-icon-container">
                                        <label class=" form-label"> E-mail </label>
                                        <input type="text" name="customer_email"
                                            class="form-control form-control-sm mb-2" placeholder="Customer Email"
                                            value="">
                                        <div class="fv-plugins-message-container invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="col-12 col-md-6">
                                <div class="card-body pt-0 pb-3">
                                    <div class="fv-row fv-plugins-icon-container">
                                        <label class=" form-label">DOB</label>
                                        <input type="date" name="customer_DOB"
                                            class="form-control form-control-sm mb-2" placeholder="Customer DOB"
                                            value="">
                                        <div class="fv-plugins-message-container invalid-feedback"></div>
                                    </div>
                                </div>
                            </div> --}}
                            {{-- <div class="col-12 col-md-6">
                                <div class="card-body pt-0 pb-3">
                                    <div class="fv-row fv-plugins-icon-container">
                                        <label class=" form-label">NID Number</label>
                                        <input type="text" name="nid_number"
                                            class="form-control form-control-sm mb-2" placeholder="NID Number"
                                            value="">
                                        <div class="fv-plugins-message-container invalid-feedback"></div>
                                    </div>
                                </div>
                            </div> --}}
                            {{-- <div class="col-12 col-md-6">
                                <div class="card-body pt-0 pb-3">
                                    <div class="fv-row fv-plugins-icon-container">
                                        <label class=" form-label">Vat Reg No</label>
                                        <input type="text" name="vat_reg_no"
                                            class="form-control form-control-sm mb-2" placeholder="Vat Reg No"
                                            value="">
                                        <div class="fv-plugins-message-container invalid-feedback"></div>
                                    </div>
                                </div>
                            </div> --}}
                            {{-- <div class="col-12 col-md-6">
                                <div class="card-body pt-0 pb-3">
                                    <div class="fv-row fv-plugins-icon-container">
                                        <label class=" form-label"> e-TIN No </label>
                                        <input type="text" name="tin_no"
                                            class="form-control form-control-sm mb-2" placeholder="Tin No"
                                            value="">
                                        <div class="fv-plugins-message-container invalid-feedback"></div>
                                    </div>
                                </div>
                            </div> --}}
                            {{-- <div class="col-12 col-md-6">
                                <div class="card-body pt-0 pb-3">
                                    <div class="fv-row fv-plugins-icon-container">
                                        <label class=" form-label">Trade License</label>
                                        <input type="text" name="trade_license"
                                            class="form-control form-control-sm mb-2" placeholder="Trade License"
                                            value="">
                                        <div class="fv-plugins-message-container invalid-feedback"></div>
                                    </div>
                                </div>
                            </div> --}}
                            {{-- <div class="col-12 col-md-6">
                                <div class="card-body pt-0 pb-3">
                                    <div class="fv-row fv-plugins-icon-container">
                                        <label class=" form-label">Commission Rate</label>
                                        <input type="text" name="discount_rate"
                                            class="form-control form-control-sm mb-2" placeholder="Discount Rate"
                                            value="">
                                        <div class="fv-plugins-message-container invalid-feedback"></div>
                                    </div>
                                </div>
                            </div> --}}
                            <div class="col-12 col-md-6">
                                <div class="card-body pt-0 pb-3">
                                    <div class="fv-row fv-plugins-icon-container">
                                        <label class=" form-label">Customer Address</label>
                                        <input type="text" name="customer_address"
                                            class="form-control form-control-sm mb-2" placeholder="Customer Address"
                                            value="">
                                        <div class="fv-plugins-message-container invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="col-12 col-md-6">
                                <div class="card-body pt-0 pb-3">
                                    <div class="fv-row fv-plugins-icon-container">
                                        <label class=" form-label">Shipping Address</label>
                                        <input type="text" name="shipping_address"
                                            class="form-control form-control-sm mb-2" placeholder="Shipping Address"
                                            value="">
                                        <div class="fv-plugins-message-container invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="card-body pt-0 pb-3">
                                    <div class="fv-row fv-plugins-icon-container">
                                        <label class=" form-label">Previous Due</label>
                                        <input type="text" name="is_previous_due"
                                            class="form-control form-control-sm mb-2" placeholder="Previous Due"
                                            value="">
                                        <div class="fv-plugins-message-container invalid-feedback"></div>
                                    </div>
                                </div>
                            </div> --}}
                            <div class="col-12 col-md-6">
                                <div class="card-body pt-0 pb-3">
                                    <div class="fv-row fv-plugins-icon-container">
                                        <label class=" form-label">Status</label>
                                        <select class="form-select form-select-sm" name="status">
                                            <option value="">--Select Status--</option>
                                            <option value="1" selected>Active</option>
                                            <option value="0">Disable</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="text-center pt-5">
                            <button class="btn btn-sm btn-success me-5" data-bs-dismiss="modal">Cancel</button>
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
    <!--end::Unit Modal - Create App-->

</x-default-layout>

<script type="text/javascript">
    document.getElementById("salesform").addEventListener("keydown", function(event) {
        // Prevent form submission on Enter key press
        if (event.key === "Enter") {
            event.preventDefault();
        }
    });

    document.getElementById("submitBtn").addEventListener("click", function(event) {
        event.preventDefault(); // Stop the form from submitting immediately

        // Get the elements for validation
        var voucherDate = document.getElementById("voucher_date");
        var customerSelect = document.getElementById("changeCustomer");
        var productInputs = document.querySelectorAll('input[name="table_product_id[]"]');
        var givenAmount = parseFloat(document.getElementById("givenAmount").value) || 0;
        var accountsName = document.getElementById("changeAccountsName");

        // 1st check: Voucher Date
        if (voucherDate.value === "") {
            Swal.fire({
                icon: 'error',
                title: 'No Voucher Date Selected',
                text: 'Please select a voucher date before submitting the form.'
            });
        }
        // 2nd check: Customer
        else if (customerSelect.value === "") {
            Swal.fire({
                icon: 'error',
                title: 'No Customer Selected',
                text: 'Please select a customer before submitting the form.'
            });
        }
        // 3rd check: Product selection (ensure at least one product is selected)
        else if (productInputs.length === 0 || Array.from(productInputs).every(input => input.value === "")) {
            Swal.fire({
                icon: 'error',
                title: 'No Products Selected',
                text: 'Please select at least one product before submitting the form.'
            });
        }
        // 4th check: Given Amount > 0
        else if (givenAmount > 0 && accountsName.value === "") {
            Swal.fire({
                icon: 'error',
                title: 'No Account Selected',
                text: 'Please select an account name before submitting the form.'
            });
        }
        // 5th check: Account Name must be selected
        // else if (accountsName.value === "") {
        //     Swal.fire({
        //         icon: 'error',
        //         title: 'No Account Selected',
        //         text: 'Please select an account name before submitting the form.'
        //     });
        // } 
        else {
            // All checks passed: Show SweetAlert2 confirmation
            Swal.fire({
                title: 'Are you sure?',
                text: "Do you really want to submit the form?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, submit it!',
                cancelButtonText: 'No, cancel',
            }).then((result) => {
                if (result.isConfirmed) {
                    // If Yes is clicked, submit the form
                    document.getElementById("salesform").submit();
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    // If No is clicked, do nothing
                    Swal.fire(
                        'Cancelled',
                        'Your form submission has been cancelled.',
                        'error'
                    );
                }
            });
        }
    });

    // Function to fetch product details based on selected product ID
    function fetchProductDetails(productID) {
        $.ajax({
            url: 'productDetails/' + productID,
            type: "GET",
            data: {
                "_token": "{{ csrf_token() }}"
            },
            dataType: "json",
            success: function(data) {
                if (data) {
                    $('#discount').val('');
                    $('#percentageInput').val('');
                    $('#percentageType').val('1');
                    $('#percentageInputContainer').addClass('d-none');
                    $('#discount').prop('readonly', false);

                    let product = data[0];
                    let present_stock = (product.product_stock_sum_stock_in_quantity - product
                        .product_stock_sum_stock_out_quantity).toFixed(2);

                    $('#product_name').val(product.product_name);
                    $('#product_code').val(product.product_code);
                    $('#sales_unit').val(product.unit.unit_name);
                    $('#sales_price').val(product.sales_price);
                    $('#present_stock').val(present_stock);
                    $('#productQuantity').val(product.pack_size);
                    onChangeCalculateTotalAmount()
                    // $('#quantityAmount').val(product.pack_size * product.sales_price);
                    // product focus
                    $('#productQuantity').focus();

                }
            },
            error: function() {
                console.error("Error fetching product details");
            }
        });
    }
    // Event handler for when the product selection changes
    $('#changeProduct').on('change', function() {
        let productID = $(this).val();
        if (productID) {
            fetchProductDetails(productID);
        }
    });

    // Event handler for changing the #productQuantity, #sales_price
    function onChangeCalculateTotalAmount() {
        let quantity = $('#productQuantity').val();
        let sales_price = $('#sales_price').val();

        if (quantity && sales_price) {
            let totalAmount = quantity * sales_price;
            totalAmount = (totalAmount).toFixed(3);
            // totalAmount = Math.round(totalAmount); // Round to nearest integer
            $('#quantityAmount').val(totalAmount);
        } else {
            $('#quantityAmount').val(0);
        }
    }
    $('#productQuantity, #sales_price').on('keyup', onChangeCalculateTotalAmount);

    // Event handler for changing the discount percentage type
    $('#percentageType').on('change', function() {
        $('#discount').val('');
        if ($(this).val() === '2') {
            $('#percentageInputContainer').removeClass('d-none');
            $('#discount').prop('readonly', true);
        } else {
            $('#percentageInputContainer').addClass('d-none');
            $('#discount').prop('readonly', false);
        }
    });

    // Event handler for updating discount based on percentage input
    $('#percentageInput').on('keyup', function() {
        let percentageValue = $(this).val();
        let totalAmount = parseFloat($('#quantityAmount').val());
        let discountAmount = (percentageValue / 100) * totalAmount;
        $('#discount').val(discountAmount.toFixed(2));
    });

    // Trigger the addProduct function on Enter key press for inputs
    $('input').on('keypress', function(event) {
        if (event.key === "Enter") {
            $('#addProduct').click();
        }
    });

    // -------------------------------- add to card start ---------------------------------------------------------

    let id = 1;
    // Add Product button click event
    $('#addProduct').on('click', function() {
        // Get values for validation
        var voucherDate = $('#voucher_date').val();
        var customer = $('#changeCustomer').val();
        var warehouseId = $('#warehouseId').val();
        var product_id = $('#changeProduct').val();
        var product_name = $('#product_name').val();
        var sales_unit = $('#sales_unit').val();
        var quantity = parseFloat($('#productQuantity').val());
        var sales_price = parseFloat($('#sales_price').val()) || 0;
        var discount = parseFloat($('#discount').val()) || 0;
        var quantityAmount = parseFloat($('#quantityAmount').val()) || 0;
        var totalAmount = parseFloat($('#netTotalAmount').val()) || 0;

        // Validation checks using SweetAlert
        if (!voucherDate) {
            Swal.fire({
                icon: 'error',
                title: 'No Voucher Date Selected',
                text: 'Please select a voucher date before adding a product.'
            });
        } else if (!customer) {
            Swal.fire({
                icon: 'error',
                title: 'No Customer Selected',
                text: 'Please select a customer before adding a product.'
            });
        } else if (!warehouseId) {
            Swal.fire({
                icon: 'error',
                title: 'No Warehouse Selected',
                text: 'Please select a warehouse before adding a product.'
            });
        } else if (!product_id) {
            Swal.fire({
                icon: 'error',
                title: 'No Product Selected',
                text: 'Please select a product before adding it to the list.'
            });
        } else if (!quantity || quantity <= 0) {
            Swal.fire({
                icon: 'error',
                title: 'Invalid Quantity',
                text: 'Please enter a valid product quantity before adding.'
            });
        } else {
            // All fields are valid, proceed with adding the product logic
            let duplicate_found = false;
            let existingRow;

            // Check for duplicate rows
            $('#product_add tr').each(function() {
                if ($(this).find('input[name="table_product_id[]"]').val() === product_id) {
                    duplicate_found = true;
                    existingRow = $(this);
                    return false; // Break loop on finding a duplicate
                }
            });

            if (duplicate_found) {
                // Update the existing row
                let oldQuantity = parseFloat(existingRow.find('.table_quantity').val()) || 0;
                let newQuantity = oldQuantity + quantity;
                let oldDiscount = parseFloat(existingRow.find('.table_discount').val()) || 0;
                let newDiscount = oldDiscount + discount;
                let oldAmount = parseFloat(existingRow.find('.cart_amount').text()) || 0;
                let newAmount = oldAmount + (quantityAmount - discount);
                newAmount = Math.round(newAmount);

                // Update the row with the new data
                existingRow.find('.table_quantity').val(newQuantity).next().val(newQuantity);
                existingRow.find('.table_discount').val(newDiscount).next().val(newDiscount);
                existingRow.find('.table_cart_amount').val(newAmount).next().val(newAmount);
                existingRow.find('.quantity_text').text(newQuantity);
                existingRow.find('.discount_text').text(newDiscount);
                existingRow.find('.cart_amount').text(newAmount);

                // Update net total
                let netTotal = totalAmount - oldAmount + newAmount;
                $('#netTotalAmount').val(netTotal.toFixed(2));

            } else {
                // Add new product row if no duplicate
                let totalAmountSum = quantityAmount;
                let cartAmount = Math.round(totalAmountSum - discount);
                let finalAmount = (totalAmount + cartAmount).toFixed(2);

                $('#product_add').append(`
                <tr class="each_row" data-id="${id}">
                    <td class="text-center">${id}</td>
                    <td>${product_name}<input type="hidden" name="table_product_id[]" value="${product_id}"></td>
                    <td class="text-center">${sales_unit}</td>
                    <td class="text-end"><span class="quantity_text">${quantity}</span><input type="hidden" class="table_quantity" name="table_product_quantity[]" value="${quantity}"></td>
                    <td class="text-end">${sales_price}<input type="hidden" class="table_price" name="table_product_price[]" value="${sales_price}"></td>
                    <td class="text-end"><span class="discount_text">${discount}</span><input type="hidden" class="table_discount" name="table_product_discount[]" value="${discount}"></td>
                    <td id="cart${id}" class="text-end"><span class="cart_amount">${cartAmount}</span><input type="hidden" class="table_cart_amount" name="table_product_cart_amount[]" value="${cartAmount}"></td>
                    <td class="text-center"><button type="button" data-id="${id}" class="add_product_delete btn btn-sm btn-danger py-1 px-3">X</button></td>
                </tr>
            `);
                id++;
                $('#netTotalAmount').val(finalAmount);
            }

            // Clear input fields after adding product
            resetFields();

            // SweetAlert success notification
            // Swal.fire({
            //     icon: 'success',
            //     title: 'Product Added',
            //     text: 'The product has been added successfully!'
            // });
        }
    });

    // Function to reset the form fields after adding a product
    function resetFields() {
        $('#changeProduct').val('').select2('open'); // Re-open the select2 dropdown for product selection
        $('#product_name').val('');
        $('#sales_unit').val('');
        $('#sales_price').val('');
        $('#present_stock').val('');
        $('#productQuantity').val('');
        $('#quantityAmount').val('');
        $('#discount').val('');
        $('#percentageInput').val('');
        $('#percentageType').val('1');
        $('#percentageInputContainer').addClass('d-none');
        $('#discount').prop('readonly', false);
    }

    // Deleting product row logic
    $(document).on('click', '.add_product_delete', function() {
        let rowId = $(this).data('id');
        let row = $(`tr[data-id="${rowId}"]`);
        let cartAmount = parseFloat(row.find('.cart_amount').text()) || 0;
        let netTotal = parseFloat($('#netTotalAmount').val()) || 0;

        // Update net total after removing the row
        $('#netTotalAmount').val((netTotal - cartAmount).toFixed(2));

        // Remove the row from the table
        row.remove();
    });

    // Event handler for updating the cart amount on quantity change
    $('#cart_product').on('keyup', 'input[name="table_product_quantity[]"]', function(e) {
        let quantity = $(this).val();
        let sales_price = parseFloat($(this).closest('.each_row').find('input[name="table_product_price[]"]')
            .val()) || 0;
        let discount = parseFloat($(this).closest('.each_row').find('input[name="table_product_discount[]"]')
            .val()) || 0;

        let newAmount = ((quantity * sales_price) - discount).toFixed(2);
        let currentTotal = parseFloat($(this).closest('.each_row').find('.cart_amount').text()) || 0;

        // Subtract the previous total amount of this row
        let netTotal = parseFloat($('#netTotalAmount').val()) || 0;
        netTotal += parseFloat(newAmount) - currentTotal;

        // Update the total amount
        $('#netTotalAmount').val(netTotal.toFixed(2));

        // Update the cart amount for this row
        $(this).closest('.each_row').find('.cart_amount').text(newAmount);
    });

    // -------------------------------- add to card end -----------------------------------------------------------

    // Event handler for changing the customer and updating customer details
    $('#changeCustomer').on('change', function() {
        let customerID = $(this).val();
        if (customerID) {
            $.ajax({
                url: 'customerDetails/' + customerID,
                type: "GET",
                data: {
                    "_token": "{{ csrf_token() }}"
                },
                dataType: "json",
                success: function(data) {
                    if (data) {
                        $('.customerInfo').removeClass('d-none');
                        $('#jsdataerror').text('');
                        $('#customerAdd').text('Total Debit: ' + data.total_debits);
                        $('#customerAdd2').text('Total Credit: ' + data.total_credits);
                        $('#customerAdd3').text('Net Balance: ' + data.net_balance);
                        $('#customerAdd4').text('Mobile: ' + data.account_mobile);
                        $('#customerAdd5').text('Address: ' + data.account_address);
                    } else {
                        $('#jsdataerror').text('Not Found');
                    }
                },
                error: function() {
                    $('#jsdataerror').text('Error fetching data');
                }
            });
        } else {
            $('#jsdataerror').text('Not Found');
        }
    });

    // Event handler for updating the return amount based on the given amount
    $('#givenAmount').on('keyup', function() {
        let totalAmount = parseFloat($('#netTotalAmount').val()) || 0;
        let givenAmount = parseFloat($(this).val()) || 0;
        let returnAmount = givenAmount - totalAmount;
        $('#returnAmount').val(returnAmount.toFixed(2));
    });

    // Event handler for when the paymentType 
    $('.paymentType').on('click', function() {
        var paymentType = $(this).data('value');
        var acListData;
        let url;
        // Show the appropriate element based on the selected option
        if (paymentType === 'Bank') {
            $('#bankDetails').removeClass('d-none');
            $('#mobileBankDetails').addClass('d-none');
            url = "{{ route('account_list', ['code' => ':code']) }}";
            url = url.replace(':code', 100020004);
        } else if (paymentType === 'mBank') {
            $('#bankDetails').addClass('d-none');
            $('#mobileBankDetails').removeClass('d-none');
            url = "{{ route('account_list', ['code' => ':code']) }}";
            url = url.replace(':code', 100020003);
        } else if (paymentType === 'Cash') {
            $('#bankDetails').addClass('d-none');
            $('#mobileBankDetails').addClass('d-none');
            url = "{{ route('account_list', ['code' => ':code']) }}";
            url = url.replace(':code', 100020002);
        }
        // console.log(url);
        $.ajax({
            url: url,
            type: "GET",
            data: {
                "_token": "{{ csrf_token() }}"
            },
            dataType: "json",
            success: function(response) {
                acListData = response.acList;
                // console.log(acListData);
                // Populate options in changeAccountsName select
                $('#changeAccountsName').empty();

                $('#changeAccountsName').append(
                    '<option value="" selected>Select Account Name</option>');
                if (acListData && Object.keys(acListData).length > 0) {
                    $.each(acListData, function(id, accountName) {
                        $('#changeAccountsName').append(
                            `<option value="${id}">${accountName}</option>`);
                    });
                } else {
                    $('#jsdataerror').text('Account names not found or empty.');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error fetching account names:', error);
                $('#jsdataerror').text('Error fetching account names.');
            }
        });

    })
    // Event handler for when the chequeType = Cheque BankInfo Show
    $('#chequeType').on('change', function() {
        let chequeType = $(this).val();
        if (chequeType == 'Cheque') {
            $('#bankInfo').removeClass('d-none'); // Correct way to remove the 'd-none' class
        } else {
            $('#bankInfo').addClass('d-none');
        }
    });

    // var sessionDeliveryChallan = {!! json_encode(session('delivery_challan')) !!};
    // if (sessionDeliveryChallan) {
    //     window.open("{{ route('sales_challan_details_pdf', '') }}/" + sessionDeliveryChallan, '_blank');
    // }

    var sessionInvoice = {!! json_encode(session('invoice')) !!};

    function openAndPrint(url) {
        // Open the PDF in a new tab
        var newWindow = window.open(url, '_blank');

        if (newWindow) {
            // Wait for a moment to give the PDF a chance to load
            setTimeout(function() {
                // Focus on the new window
                newWindow.focus();

                // Check if we can invoke print
                try {
                    newWindow.print();
                } catch (e) {
                    console.error('Error printing the document:', e);
                    alert(
                        "The print dialog did not open automatically. Please press Ctrl + P to print the invoice.");
                }
            }, 4000); // Wait 4 seconds before trying to print (adjust as necessary)
        } else {
            alert('Please allow popups for this website to print the invoice.');
        }
    }

    if (sessionInvoice) {
        var invoiceUrl = "{{ route('sales_invoice_details_pdf', '') }}/" + sessionInvoice;
        openAndPrint(invoiceUrl);
    } else {
        console.error('No session invoice found.'); // Debugging line
    }
</script>

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
        <form id="salesform" method="POST" action="{{ route('sales.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="row pt-10">
                <!-- Product add to card Content Row -->
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-md-12 card shadow">
                            <div class="card-header px-2 mx-0">
                                <div class="d-sm-flex align-items-center justify-content-between">
                                    <h1 class="h3 mb-0 text-gray-800">{{ __('Sales') }}</h1>
                                </div>
                                {{-- <label class="badge text-warning" id="jsdataerror"></label> --}}
                            </div>
                            <div class="card-body py-3 px-2">
                                <div class="col-md-12 pb-3">

                                    <div class="row">
                                        <div class="form-group col-md-6 my-3">
                                            <label>{{ __('Select Product') }}</label>
                                            <select id="changeProduct" class="form-select form-select-sm"
                                                name="product_id" data-control="select2" data-hide-search="false">
                                                <option>Select Product</option>
                                                @foreach ($products as $product)
                                                    <option value="{{ $product->id }}">Name: {{ $product->product_name }} (Code: {{ $product->product_code }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-4 my-3">
                                            <label for="order">{{ __('Product Name') }}</label>                                            
                                            <input type="text"
                                                class="form-control form-control-sm form-control-solid" readonly
                                                id="product_name" name="product_name " />
                                        </div>
                                        <div class="form-group col-md-2 my-3">
                                            <label for="order">{{ __('Code') }}</label>                                            
                                            <input type="text"
                                                class="form-control form-control-sm form-control-solid" readonly
                                                id="product_code" name="product_code " />
                                        </div>
                                        <div class="form-group col-md-2 my-3">
                                            <label for="order">{{ __('Unit Name') }}</label>
                                            <input type="text"
                                                class="form-control form-control-sm form-control-solid" readonly
                                                id="sales_unit" name="sales_unit " />
                                        </div>
                                        <div class="form-group col-md-2 my-3">
                                            <label for="order">{{ __('Price (Per Unit)') }}</label>
                                            <input type="text" class="form-control form-control-sm" id="sales_price"
                                                name="sales_price"
                                                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                        </div>
                                        <div class="form-group col-md-2 my-3">
                                            <label for="order">{{ __('Present Stock') }}</label>
                                            <input type="text"
                                                class="form-control form-control-sm form-control-solid"
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
                                            <input type="text"
                                                class="form-control form-control-sm form-control-solid"
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

                        <div class="col-md-12 card shadow my-5 px-5">
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
                            <input type="number" class="form-control form-control-sm form-control-solid"
                                id="netTotalAmount" name="netTotalAmount" value=0 readonly />
                        </div>

                        {{-- <div class="form-group">
                            <label>{{ __('Select Customer') }}</label>
                            <select id="changeCustomer" class="form-control form-control-sm" name="customer_id"
                                data-control="select2" data-placeholder="Select Customer" required>
                                <option></option>
                                @foreach ($customerAccounts as $customerAccount)
                                    <option value="{{ $customerAccount->id }}">{{ $customerAccount->account_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div> --}}

                        <div class="card-body px-0 pb-2 d-flex align-items-center">
                            <div class="flex-grow-1 pt-2">
                                <label>{{ __('Select Customer') }}</label>
                                <select id="changeCustomer" class="form-control form-control-sm" name="customer_id"
                                    data-control="select2" data-placeholder="Select Customer" required>
                                    <option></option>
                                    @foreach ($customerAccounts as $customerAccount)
                                        <option value="{{ $customerAccount->id }}">
                                            {{ $customerAccount->account_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <a href="#" class="btn btn-sm btn-flex btn-light-primary ms-2 mt-8"
                                data-bs-toggle="modal" data-bs-target="#add_customer_modal">
                                Add
                            </a>
                        </div>

                        <div class="card p-5 my-3 d-none" id="customerInfo">
                            <label for="" id="customerAdd4"></label>
                            <label for="" id="customerAdd5"></label>
                            <label for="" id="customerAdd"></label>
                            <label for="" id="customerAdd2"></label>
                            <label for="" id="customerAdd3"></label>
                        </div>

                        {{-- <table id="customerAdd" class="table table-success table-striped">

                        </table> --}}
                        <!-- <div class="form-group">
                            <label for="order">{{ __('Invoice No.') }}</label>
                            <input type="text" class="form-control form-control-sm" id="" name="customer_invoice_no" value="" />
                        </div> -->

                        <div class="form-group">
                            <label>{{ __('Receive Account') }}</label>
                            <select id="" class="form-control form-control-sm" name="receive_account"
                                data-control="select2" data-placeholder="Select Payment Account">
                                <option></option>
                                @foreach ($toAccounts as $toAccount)
                                    <option value="{{ $toAccount->id }}">
                                        {{ $toAccount->account_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="card card-flush h-xl-100 my-3">
                            <!--begin::Header-->
                            <div class="card-header pt-0">
                                <!--begin::Title-->
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="card-label fw-bold text-gray-800">Payment Method</span>
                                </h3>
                                <!--end::Title-->
                            </div>
                            <!--end::Header-->
                            <!--begin::Body-->
                            <div class="card-body pt-3">
                                <!--begin::Nav-->
                                <ul class="nav nav-pills nav-pills-custom my-3">
                                    <!--begin::Item-->
                                    <li class="nav-item my-3 me-3 me-lg-6">
                                        <!--begin::Link-->
                                        <a class="nav-link btn btn-outline btn-flex btn-color-muted btn-active-color-primary flex-column overflow-hidden w-80px h-85px pt-5 pb-2 active"
                                            id="kt_stats_widget_16_tab_link_1" data-bs-toggle="pill"
                                            href="#kt_stats_widget_16_tab_1">
                                            <!--begin::Icon-->
                                            <div class="nav-icon">
                                                {{-- <i class="ki-duotone ki-dollar fs-2hx pe-0">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                    <span class="path3"></span>
                                                </i> --}}
                                                <i class="fa-solid fa-bangladeshi-taka-sign pb-2"
                                                    style="font-size: 28px;"></i>
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
                                        <a class="nav-link btn btn-outline btn-flex btn-color-muted btn-active-color-primary flex-column overflow-hidden w-80px h-85px pt-5 pb-2"
                                            id="kt_stats_widget_16_tab_link_2" data-bs-toggle="pill"
                                            href="#kt_stats_widget_16_tab_2">
                                            <!--begin::Icon-->
                                            <div class="nav-icon">
                                                <i class="fa-solid fa-building-columns pb-2"
                                                    style="font-size: 28px;"></i>
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
                                        <a class="nav-link btn btn-outline btn-flex btn-color-muted btn-active-color-primary flex-column overflow-hidden w-80px h-85px pt-5 pb-2"
                                            id="kt_stats_widget_16_tab_link_3" data-bs-toggle="pill"
                                            href="#kt_stats_widget_16_tab_3">
                                            <!--begin::Icon-->
                                            <i class="fa-solid fa-mobile pb-2" style="font-size: 28px;"></i>
                                            <!--end::Icon-->
                                            <!--begin::Title-->
                                            <span class="nav-text text-gray-800 fw-bold fs-7 lh-1">Mobile Bank</span>
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
                                    <!--begin::Tap cash pane-->
                                    <div class="tab-pane fade show active" id="kt_stats_widget_16_tab_1">
                                        {{-- <div class="form-group">
                                            <label for="givenAmount">{{ __('Receive Amount') }}</label>
                                            <input type="text" class="form-control form-control-sm" id="givenAmount"
                                                name="givenAmount" value="0"
                                                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                        </div>
                                        <div class="form-group">
                                            <label for="returnAmount"><span id="dueOrReturn"></span> Amount</label>
                                            <input type="number" class="form-control form-control-sm form-control-solid"
                                                id="returnAmount" name="returnAmount" value="0" readonly />
                                        </div> --}}
                                    </div>
                                    <!--end::Tap pane-->
                                    <!--begin::Tap bank pane-->
                                    <div class="tab-pane fade" id="kt_stats_widget_16_tab_2">
                                        <div class="form-group">
                                            <label>{{ __('Select Bank') }}</label>
                                            <select id="" class="form-control form-control-sm"
                                                name="bank_name" data-control="select2"
                                                data-placeholder="Select Bank">
                                                <option value="">Select Bank</option>
                                                <option value="Shahjalal Islami Bank Limited">Shahjalal Islami Bank
                                                    Limited</option>
                                                <option value="Islami Bank Bangladesh Ltd">Islami Bank Bangladesh Ltd
                                                </option>
                                                <option value="Dutch Bangla Bank">Dutch Bangla Bank</option>

                                                {{-- @foreach ($toAccounts as $toAccount)
                                                    <option value="{{ $toAccount->id }}">
                                                        {{ $toAccount->account_name }}
                                                    </option>
                                                @endforeach --}}
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>{{ __('Select Branch') }}</label>
                                            <select id="" class="form-control form-control-sm"
                                                name="branch_name" data-control="select2"
                                                data-placeholder="Select Branch">
                                                <option value="">Select Branch</option>
                                                <option value="Gulshan Branch">Gulshan Branch</option>
                                                <option value="Dhanmondi Branch">Dhanmondi Branch</option>
                                                <option value="Banani Branch">Banani Branch</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>{{ __('Select Cheque Type') }}</label>
                                            <select id="" class="form-control form-control-sm"
                                                name="cheque_type" data-control="select2"
                                                data-placeholder="Select Cheque Type">
                                                <option value="">Select Cheque Type</option>
                                                <option value="Cash Cheque">Cash Cheque </option>
                                                <option value="Bearer Cheque">Bearer Cheque</option>
                                                <option value="AC Pay Cheque">AC Pay Cheque</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="returnAmount">A/C No</label>
                                            <input type="text" class="form-control form-control-sm" name="ac_no"
                                                value="0"
                                                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" />
                                        </div>
                                        <div class="form-group">
                                            <label for="returnAmount">Cheque No</label>
                                            <input type="text" class="form-control form-control-sm"
                                                name="cheque_no" value="0" />
                                        </div>
                                        <div class="form-group">
                                            <label for="">Cheque Date</label>
                                            <input type="date" class="form-control form-control-sm"
                                                name="cheque_date" />
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
                                            <label for="">Transaction ID</label>
                                            <input type="text" class="form-control form-control-sm"
                                                name="transaction_id" value="0" />
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

                        {{-- <div class="form-group">
                            <label for="givenAmount">{{ __('Receive Amount') }}</label>
                            <input type="text" class="form-control form-control-sm" id="givenAmount"
                                name="givenAmount" value="0"
                                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                        </div> --}}
                        {{-- <div class="form-group">
                            <label for="returnAmount"><span id="dueOrReturn"></span> Amount</label>
                            <input type="number" class="form-control form-control-sm form-control-solid"
                                id="returnAmount" name="returnAmount" value="0" readonly />
                        </div> --}}
                        <div class="form-group">
                            <label for="remarks">{{ __('Remarks') }}</label>
                            <input type="text" class="form-control form-control-sm" id="remarks"
                                name="remarks" />
                        </div>
                        <button type="submit" id=""
                            class="btn btn-sm btn-success px-5 mt-5">{{ __('Sales') }}</button>

                    </div>
                </div>
            </div>
        </form>
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
                            <div class="col-12 col-md-6">
                                <div class="card-body pt-0 pb-3">
                                    <div class="fv-row fv-plugins-icon-container">
                                        <label class="required form-label">Customer Code</label>
                                        <input type="text" name="customer_code"
                                            class="form-control form-control-sm mb-2" placeholder="Customer Code"
                                            value="" required>
                                        <div class="fv-plugins-message-container invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>
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

                            <div class="col-12 col-md-6">
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
                            </div>

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

                            <div class="col-12 col-md-6">
                                <div class="card-body pt-0 pb-3">
                                    <div class="fv-row fv-plugins-icon-container">
                                        <label class=" form-label">DOB</label>
                                        <input type="date" name="customer_DOB"
                                            class="form-control form-control-sm mb-2" placeholder="Customer DOB"
                                            value="">
                                        <div class="fv-plugins-message-container invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="card-body pt-0 pb-3">
                                    <div class="fv-row fv-plugins-icon-container">
                                        <label class=" form-label">NID Number</label>
                                        <input type="text" name="nid_number"
                                            class="form-control form-control-sm mb-2" placeholder="NID Number"
                                            value="">
                                        <div class="fv-plugins-message-container invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="card-body pt-0 pb-3">
                                    <div class="fv-row fv-plugins-icon-container">
                                        <label class=" form-label">Vat Reg No</label>
                                        <input type="text" name="vat_reg_no"
                                            class="form-control form-control-sm mb-2" placeholder="Vat Reg No"
                                            value="">
                                        <div class="fv-plugins-message-container invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="card-body pt-0 pb-3">
                                    <div class="fv-row fv-plugins-icon-container">
                                        <label class=" form-label"> e-TIN No </label>
                                        <input type="text" name="tin_no"
                                            class="form-control form-control-sm mb-2" placeholder="Tin No"
                                            value="">
                                        <div class="fv-plugins-message-container invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="card-body pt-0 pb-3">
                                    <div class="fv-row fv-plugins-icon-container">
                                        <label class=" form-label">Trade License</label>
                                        <input type="text" name="trade_license"
                                            class="form-control form-control-sm mb-2" placeholder="Trade License"
                                            value="">
                                        <div class="fv-plugins-message-container invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="card-body pt-0 pb-3">
                                    <div class="fv-row fv-plugins-icon-container">
                                        <label class=" form-label">Commission Rate</label>
                                        <input type="text" name="discount_rate"
                                            class="form-control form-control-sm mb-2" placeholder="Discount Rate"
                                            value="">
                                        <div class="fv-plugins-message-container invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="card-body pt-0 pb-3">
                                    <div class="fv-row fv-plugins-icon-container">
                                        <label class="required form-label">Customer Address</label>
                                        <input type="text" name="customer_address"
                                            class="form-control form-control-sm mb-2" placeholder="Customer Address"
                                            value="" required>
                                        <div class="fv-plugins-message-container invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
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
                            </div>
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
        // Check if the pressed key is Enter
        if (event.key === "Enter") {
            // Prevent default form submission
            event.preventDefault();
        }
    });


    $('#changeProduct').on('change', function() {
        var productID = $(this).val();

        if (productID) {
            $.ajax({
                url: 'productDetails/' + productID,
                type: "GET",
                data: {
                    "_token": "{{ csrf_token() }}"
                },
                dataType: "json",
                success: function(data) {

                    if (data) {
                        $('#quantityAmount').val(0);
                        $('#productQuantity').val(0);
                        $('#discount').val(0);
                        $.each(data, function(key, value) {
                            var product_name = value.product_name;
                            var product_code = value.product_code;
                            var unit = value.unit.unit_name;
                            var price = value.sales_price;
                            var sum_stock_in = value.product_stock_sum_stock_in_quantity;
                            var sum_stock_out = value.product_stock_sum_stock_out_quantity;
                            var present_stock = (sum_stock_in - sum_stock_out).toFixed(2);

                            // $('#product_barcode').val(DNS1D::getBarcodeHTML(product_code, 'C128', 1.5, 30, 'black'));
                           
                            $('#product_code').val(product_code);
                            $('#product_name').val(product_name);
                            $('#product_code').val(product_code);
                            $('#sales_unit').val(unit);
                            $('#sales_price').val(price);
                            $('#present_stock').val(present_stock);

                            // set foucus
                            $('#productQuantity').focus();
                        });
                    } else {

                    }
                }
            });
        } else {

        }
    });

    $('#productQuantity').on('keyup', function() {
        var quantity = $('#productQuantity').val();
        var purchasePrice = $('#sales_price').val();

        // Check if quantity and purchasePrice are not empty
        if (quantity && purchasePrice) {
            let totalAmount = quantity * purchasePrice;
            $('#quantityAmount').val(totalAmount.toFixed(2));
        } else {
            // Handle the case where either quantity or purchasePrice is empty
            $('#quantityAmount').val(0);
        }
    });

    $('#percentageType').on('change', function() {
        if ($(this).val() === '2') {
            $('#percentageInputContainer').removeClass('d-none');
            $('#discount').prop('readonly', true);
        } else {
            $('#percentageInputContainer').addClass('d-none');

            $('#discount').prop('readonly', false);
        }
    });

    $('#percentageInput').on('keyup', function() {
        const percentageValue = $(this).val();
        var quantityAmount = $('#quantityAmount').val();
        const totalAmount = parseFloat(quantityAmount);
        const discountAmount = (percentageValue / 100) * totalAmount;

        // console.log(percentageValue, employeeSalaryAmount, calculatedAmount);
        $('#discount').val(discountAmount.toFixed(2));
    });

    let id = 1;
    $('#addProduct').on('click', function() {
        // $( "#myselect option:selected" ).text();
        // let product_name = $('#changeProduct option:selected').text();
        let product_id = $('#changeProduct option:selected').val();
        let product_name = $('#product_name').val();
        let sales_unit = $('#sales_unit').val();
        let quantity = $('#productQuantity').val();
        let sales_price = $('#sales_price').val();
        let discount = $('#discount').val();
        let totalAmount = $('#netTotalAmount').val();

        if (product_id > 0) {
            if (quantity > 0) {
                // Check for duplicate consume_product_id
                let duplicate_found = false;
                $('#product_add tr').each(function() {
                    if ($(this).find('input[name="table_product_id[]"]').val() === product_id) {
                        duplicate_found = true;
                        return false; // break out of loop if duplicate found
                    }
                });

                if (duplicate_found) {
                    alert('This product has already been added to the list.');
                } else {
                    // let cartAmount = Math.round((sales_price * quantity) - discount);

                    let totalAmountSum = quantity * sales_price;
                    let total_price = totalAmountSum.toFixed(2)
                    let cartAmountSum = parseFloat(total_price) - discount;
                    let cartAmount = cartAmountSum.toFixed(2);
                    let sumAmount = parseFloat(cartAmount) + parseFloat(totalAmount);
                    let setAmount = $('#netTotalAmount').val(sumAmount.toFixed(2));

                    $('#product_add').append(
                        `<tr class="each_row" data-id="${id}">
                            <td class="text-center">` + id + `</td>
                            <td>` + product_name + `<input type="hidden" name="table_product_id[]" value=` +
                        product_id + ` ></td>
                            <td class="text-center">` + sales_unit + `</td>
                        <td>
                            <input type="text" class="form-control table_quantity py-0 text-end" name="table_product_quantity[]" step="1" min="0"
                            oninput="this.value = this.value.replace(/\D/g, '');" 
                            value=` + quantity + `>
                        </td>
                            <td class="text-end">` + sales_price +
                        `<input type="hidden" class="table_price" name="table_product_price[]" value=` +
                        sales_price + ` ></td>
                            <td class="text-end">` + discount +
                        `<input type="hidden" class="table_discount" name="table_product_discount[]" value=` +
                        discount + ` ></td>
                            <td id="cart${id}" class="cart_amount text-end" >` + cartAmount + `</td>
                            <td class="text-center my-0"> 
                                <button type="button" data-id="${id}" class="add_product_delete btn btn-sm btn-danger py-1 px-3">X</button>
                            </td>
                        </tr>`
                    );
                    ++id;
                    // $('#changeProduct').val('');
                    $('#quantity').val('');
                }
            } else {
                alert("Please Input Quantity Number");
            }
        } else {
            alert("Please Select Product");
        }
    });

    // $('#product_add').on('click', 'input[name="table_product_quantity[]"]', function(e) {
    $('#product_add').on('click', function(e) {
        let totalAmount = parseFloat($('#netTotalAmount').val()) || 0;
        let cartAmount = 0;

        if (e.target.matches('.add_product_delete')) {
            const {
                id
            } = e.target.dataset;
            const selector = `tr[data-id="${id}"]`;
            const cartAmountElement = $('#cart' + id);
            cartAmount = parseFloat(cartAmountElement.text()) || 0;

            // Update totalAmount by subtracting cartAmount
            totalAmount -= cartAmount;

            // Remove the row
            $(selector).remove();
        }

        // Update the netTotalAmount input
        $('#netTotalAmount').val(totalAmount);
    });

    $('#cart_product').on('keyup', 'input[name="table_product_quantity[]"]', function(e) {
        var quantity = $(this).val();

        let sales_price = parseFloat($(this).closest('.each_row').find('input[name="table_product_price[]"]')
            .val()) || 0;
        let discount = parseFloat($(this).closest('.each_row').find('input[name="table_product_discount[]"]')
            .val()) || 0;

        let update_amount_sum = (quantity * sales_price) - discount;
        let update_amount = update_amount_sum.toFixed(2);
        let currentTotal = parseFloat($(this).closest('.each_row').find('.cart_amount').text()) || 0;

        // Subtract the previous total amount of this row
        let getAmount = parseFloat($('#netTotalAmount').val()) || 0;
        let calculateNetTotal = update_amount - currentTotal;
        let finalAmount_sum = getAmount + calculateNetTotal;
        let finalAmount = finalAmount_sum.toFixed(2);
        // Update the total amount
        $('#netTotalAmount').val(finalAmount);

        // Update the cart amount for this row
        $(this).closest('.each_row').find('.cart_amount').text(update_amount);
    });

    $('#changeCustomer').on('change', function() {
        var customerID = $(this).val();
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
                        $('#customerInfo').removeClass('d-none');
                        // console.log(data);
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


    $('#givenAmount').on('keyup', function() {
        var quantity = $('#givenAmount').val();
        var netTotalAmount = $('#netTotalAmount').val();
        var givenAmount = $('#givenAmount').val();
        let returnAmount = (netTotalAmount - givenAmount).toFixed(2);
        if (returnAmount >= 0) {
            $('#dueOrReturn').text('Due');
        } else {
            $('#dueOrReturn').text('Return');
        }
        $('#returnAmount').val(returnAmount);
    });

    // sales success after pdf open 
    var sessionInvoice = {!! json_encode(session('invoice')) !!};
    if (sessionInvoice) {
        window.open("{{ route('sales_invoice_details_pdf', '') }}/" + sessionInvoice, '_blank');
    }

</script>

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
                        <h3 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">General Ledger</h3>
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
                          action="{{ route('general_ledger') }}" enctype="multipart/form-data">
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
                                                <label class=" form-label">Select Account Head</label>
                                                <select class="form-select form-select-sm" name="account_head" >
                                                    <option value="" selected>--Select Account Head--</option>
                                                    @foreach($accountList as $key => $value)
                                                    <option value="{{ $key }}">{{ $value}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-4">
                                        <div class="card-body pt-0 pb-3">
                                            <div class="fv-row fv-plugins-icon-container">
                                                <label class=" form-label">Start Date</label>
                                                <input type="date" name="start_date" class="form-control form-control-sm mb-2"
                                                       placeholder="Group Name" value="">
                                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-4">
                                        <div class="card-body pt-0 pb-3">
                                            <div class="fv-row fv-plugins-icon-container">
                                                <label class=" form-label">End Date</label>
                                                <input type="date" name="end_date" class="form-control form-control-sm mb-2"
                                                       placeholder="end_date" value="">
                                                <div class="fv-plugins-message-container invalid-feedback"></div>
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
                                    <span class="indicator-label">View</span>
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

    <!--======================================================================-->   

    <!--begin::Toolbar-->
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <!--begin::Toolbar container-->
        <div id="kt_app_toolbar_container" class="app-container d-flex flex-stack">
            <!--begin::Page title-->
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <!--begin::Title-->
                <h3>Ledger Details</h3>
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
            <!--begin::Products-->
            <div class="card card-flush">
                <!--begin::Card header-->
                <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                    <!--begin::Card title-->
                    <div class="card-title">

                        <!--begin::Export buttons-->
                        <div id="kt_ecommerce_report_customer_orders_export" class="d-none"></div>
                        <!--end::Export buttons-->
                    </div>
                    <!--end::Card title-->

                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body pt-0">
                    <!--begin::Table-->
                    <table class="table table-striped table-bordered align-middle table-row-dashed fs-7 gy-5 mb-0"
                           id="kt_ecommerce_report_customer_orders_table">
                        <!--begin::Table head-->
                        <thead>
                            <!--begin::Table row-->
                            <tr class="text-start fs-7 text-uppercase gs-0">
                                <th class="min-w-100px">Voucher Date</th>
                                <th class="min-w-100px">Voucher No </th>
                                <th class="min-w-100px">ToAccName</th>
                                <th class="min-w-100px">Narration</th>
                                <th class="min-w-100px">Debit</th>
                                <th class="min-w-100px">Credit</th>
                                <th class="min-w-100px">Balance</th>
                            </tr>
                            <!--end::Table row-->
                        </thead>
                        <!--end::Table head-->
                        <!--begin::Table body-->
                        <tbody class="fw-semibold text-gray-700">
                            @php
                            $balance=0;
                            @endphp

                            @foreach($general_ledger as $general_ledgers)

                            <tr>
                                <td>
                                    {{ $general_ledgers->date }}
                                </td>
                                <td>
                                    {{ $general_ledgers->voucher_no }}
                                </td>
                                <td>
                                    {{ $general_ledgers->to_acc_name }}
                                </td>
                                <td>
                                    {{ $general_ledgers->narration }}
                                </td>
                                <td>
                                    @if($general_ledgers->balance_type=='Dr')
                                    {{ $general_ledgers->amount }}
                                    @php $balance += $general_ledgers->amount @endphp
                                    @endif

                                    @if($general_ledgers->balance_type=='Cr')
                                    {{ 0 }}
                                    @endif



                                </td>
                                <td>
                                    @if($general_ledgers->balance_type=='Cr')
                                    {{ $general_ledgers->amount }}
                                     @php $balance -= $general_ledgers->amount @endphp
                                    @endif

                                    @if($general_ledgers->balance_type=='Dr')
                                    {{ 0 }}
                                    @endif
                                </td>
                                <td>
                                    {{ $balance }}
                                </td>
                            </tr>

                            @endforeach


                        </tbody>
                        <!--end::Table body-->
                    </table>
                    <!--end::Table-->
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Products-->
        </div>
        <!--end::Content container-->
    </div>
    <!--end::Content-->



</x-default-layout>

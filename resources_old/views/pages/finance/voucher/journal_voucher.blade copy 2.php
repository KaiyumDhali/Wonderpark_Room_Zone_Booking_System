<x-default-layout>
    <style>
        .card .card-header {
            min-height: 40px;
        }

        .table> :not(caption)>*>* {
            padding: 0.3rem !important;
        }

        .dataTables_filter {
            float: right;
        }

        .dataTables_buttons {
            float: left;
        }

        .bottom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }
    </style>

    {{-- message show --}}
    <div class="col-xl-12 px-10">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if (session('message'))
            <div class="alert alert-{{ session('alert-type') }}">
                {{ session('message') }}
            </div>
        @endif
    </div>

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
                        <h3 class="page-heading d-flex text-dark fs-1 flex-column justify-content-center my-0">
                            Journal Voucher</h3>
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
                    <form id="journalVoucherform"
                        class="form d-flex flex-column flex-lg-row fv-plugins-bootstrap5 fv-plugins-framework"
                        method="POST" action="{{ route('journal_voucher_store') }}" enctype="multipart/form-data">
                        @csrf
                        <!--begin::Main column-->
                        <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
                            <!--begin::General options-->
                            <div class="card card-flush py-4">
                                <!--begin::Card header-->
                                <div class="card-header">
                                    <div class="card-title">
                                        <h2>Journal Entry</h2>
                                    </div>
                                </div>
                                <!--end::Card header-->
                                <div class="row">
                                    <div class="col-12 col-md-3">
                                        <div class="card-body pt-0 pb-3">
                                            <div class="fv-row fv-plugins-icon-container">
                                                <label class="required form-label">Date</label>
                                                <input type="date" name="date"
                                                    class="form-control form-control-sm mb-2" placeholder="Date"
                                                    value="" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <div class="card-body pt-0 pb-3">
                                            <div class="fv-row fv-plugins-icon-container">
                                                <label class="required form-label">Account Name</label>
                                                <select id="changeAccountsName" class="form-select form-select-sm"
                                                    name="accounts_name" data-control="select2" data-hide-search="false"
                                                    required>
                                                    {{-- <option selected disabled>Select Product Brand</option>
                                                    @foreach ($acList as $key => $value)
                                                        <option value="{{ $key }}">{{ $value}}</option>
                                                    @endforeach --}}
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <div class="card-body pt-0 pb-3">
                                            <div class="fv-row fv-plugins-icon-container">
                                                <label class="required form-label">Amount</label>
                                                <input type="number" name="amount"
                                                    class="form-control form-control-sm mb-2" placeholder="Amount"
                                                    value="" required>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- Journal Type --}}
                                    <div class="col-12 col-md-3">
                                        <div class="card-body pt-0 pb-2">
                                            <div class="fv-row fv-plugins-icon-container">
                                                <label>{{ __('Balance Type') }}</label>
                                                <select id="balanceType" class="form-control form-control-sm"
                                                    name="balance_type" data-control="select2"
                                                    data-placeholder="Select Type">
                                                    <option value="Cr">Cr</option>
                                                    <option value="Dr">Dr</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <div class="card-body pt-0 pb-2">
                                            <div
                                                class="fv-row fv-plugins-icon-container d-flex justify-content-start mt-8">
                                                <!--begin::Button-->
                                                <button type="submit" id="kt_ecommerce_add_category_submit"
                                                    class="btn btn-sm btn-success">
                                                    <span class="indicator-label">Save Voucher Entry</span>
                                                    <span class="indicator-progress">Please wait...
                                                        <span
                                                            class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                                </button>
                                                <!--end::Button-->
                                            </div>
                                        </div>
                                    </div>
                                </div>
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

<script>
   
 
</script>

<x-default-layout>
    <style>
    .table> :not(caption)>*>* {
        padding: 0.3rem !important;
    }
        i.bi, i[class^="fonticon-"], i[class*=" fonticon-"], i[class^="fa-"], i[class*=" fa-"], i[class^="la-"], i[class*=" la-"] {
        line-height: 1;
        font-size: 1.2rem !important;
        color: var(--bs-rating-color-active);
}
    </style>
    <!--begin::Toolbar-->
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <!--begin::Toolbar container-->
        <div id="kt_app_toolbar_container" class="app-container d-flex flex-stack">
            <!--begin::Page title-->
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <!--begin::Title-->
                <h3>Database Backup</h3>
                <!--end::Title-->
            </div>
            <!--end::Page title-->
        </div>
        <!--end::Toolbar container-->
    </div>
    <!--end::Toolbar-->
                        @if (session('success'))
                        <div class="alert alert-success mx-10">
                            {{ session('success') }}
                        </div>
                        @endif
    <!--begin::Content-->
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container ">
            <!--begin::Products-->
            <div class="card card-flush">
                <!--begin::Card header-->
                <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                    <!--begin::Card title-->
                    <div class="card-title">
                        <!--begin::Search-->

                        <!--end::Search-->
                        <!--begin::Export buttons-->
                        <div id="kt_ecommerce_report_customer_orders_export" class="d-none"></div>
                        <!--end::Export buttons-->
                    </div>
                    <!--end::Card title-->
                    <!--begin::Card toolbar-->
                    <div class="card-toolbar flex-row-fluid justify-content-end gap-5">

                        <!--begin::Daterangepicker-->
                        <!-- <input class="form-control form-control-solid w-100 mw-250px" placeholder="Pick date range" id="kt_ecommerce_report_customer_orders_daterangepicker" /> -->
                        <!--end::Daterangepicker-->
                        <!--begin::Filter-->
                        <!-- <div class="w-150px">
                            <select class="form-select p-2 form-select-solid" data-control="select2"
                                data-hide-search="true" data-placeholder="Status"
                                data-kt-ecommerce-order-filter="status">
                                <option value="all"> Status</option>
                                <option value="Active">Active</option>
                                <option value="Disabled">Disabled</option>
                            </select>
                        </div> -->
                        <!--end::Filter-->
                        <!--begin::Export dropdown-->

                        <!--begin::Menu-->

                        <!--end::Menu-->
                        <!--end::Export dropdown-->
                        <a href="{{ route('downloaddb.download_db') }}"
                           class="btn btn-primary btn-sm">Backup Database</a>
                    </div>
                    <!--end::Card toolbar-->
                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body pt-0">
                    <!--begin::Table-->
                    <table class="table table-striped table-bordered align-middle table-row-dashed fs-6 gy-5"
                           id="kt_ecommerce_report_customer_orders_table">
                        <!--begin::Table head-->
                        <thead>
                            <!--begin::Table row-->
                            <tr class="text-start fs-6 text-uppercase gs-0">
                                <th class="min-w-50px">SL</th>
                                <th class="min-w-100px">Backup</th>
                                <th class="min-w-100px">Download/Delete</th>
                            </tr>
                            <!--end::Table row-->
                        </thead>
                        <!--end::Table head-->
                        <!--begin::Table body-->
                        <tbody class="fw-semibold text-gray-600">
                            @foreach ($files as $file)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>{{ $file }}</td>
                                <td>
                                    <a href ="{{route('downloadFile', $file)}}">
                                        <button class="btn btn-sm btn-primary">
                                            <i class="fa fa-download"></i>
                                        </button>
                                    </a>
                                    <a href="{{route('db_destroy', $file)}}">
                                        <button class="btn btn-sm btn-danger">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </a>

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


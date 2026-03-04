<x-default-layout>
    <div class="app-main flex-column flex-row-fluid" id="kt_app_main">

        <!--begin::Content wrapper-->
        <div class="d-flex flex-column flex-column-fluid pb-10">
            <div class="p-5 mx-5">
                <!--begin:::Tabs-->
                <ul id="myTabjustified" role="tablist"
                    class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-semibold mb-8">
                    <!--begin:::Tab item-->
                    <li class="nav-item">
                        <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                            Company Setting Details
                        </h1>
                    </li>
                    <!--end:::Tab item-->
                </ul>
                <!--end:::Tabs-->

                <div class="d-flex justify-content-end my-5" style="margin-top: -60px!important;">
                    <!--begin::Button-->
                    <a href="{{ route('emp_company.edit', $companySetting->id) }}" id="kt_ecommerce_add_product_cancel"
                        class="btn btn-sm btn-primary me-5">Edit Setting Details</a>
                    <a class="btn btn-sm btn-success" href="{{ route('emp_company.index') }}"
                        id="kt_ecommerce_add_employee_cancel">Back</a>

                    <!--end::Button-->
                </div>
            </div>
            <!--begin:: Profile Histories Content-->
            <div id="kt_app_content" class="app-content flex-column-fluid mb-5">
                <!--begin::Content container-->
                <div id="kt_app_content_container" class="app-container">
                    <!--begin::Main column-->
                    <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
                        <!-- Content Row -->
                        <div class="card shadow">
                            {{-- <div class="card-header pt-0">
                                <!--begin::Title-->
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="card-label fw-bold text-gray-800">Profile Histories</span>
                                </h3>
                            </div> --}}
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <table
                                            class="table table-striped table-bordered align-middle table-row-dashed fs-5 gy-5 mb-0">
                                            <tbody class="text-start fw-semibold text-gray-700">
                                                <tr>
                                                    <th>Company Name</th>
                                                    <td>{{ $companySetting->company_name }}</td>
                                                </tr>
                                                {{-- <tr>
                                                    <th>Proprietor Name</th>
                                                    <td>{{ $companySetting->proprietor_name }}</td>
                                                </tr> --}}
                                                <tr>
                                                    <th>Company Details</th>
                                                    <td>{{ $companySetting->company_details }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Company Address</th>
                                                    <td>{{ $companySetting->company_address }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Factory Address</th>
                                                    <td>{{ $companySetting->factory_address }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Cell Number</th>
                                                    <td>{{ $companySetting->company_mobile }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Phone Number</th>
                                                    <td>{{ $companySetting->company_phone }}</td>
                                                </tr>
                                                <tr>
                                                    <th>E-mail</th>
                                                    <td>{{ $companySetting->company_email }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Website URL</th>
                                                    <td>{{ $companySetting->website_url }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Trade License No</th>
                                                    <td>{{ $companySetting->trade_license }}</td>
                                                </tr>
                                                <tr>
                                                    <th>e-TIN No</th>
                                                    <td>{{ $companySetting->tin_no }}</td>
                                                </tr>
                                                <tr>
                                                    <th>BIN No</th>
                                                    <td>{{ $companySetting->bin_no }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Currency</th>
                                                    <td>{{ $companySetting->currency }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Status</th>
                                                    <td>{{ $companySetting->status == 1 ? 'Active' : 'Disabled' }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <table
                                            class="table table-striped table-bordered align-middle table-row-dashed fs-5 gy-5 mb-0">
                                            <tbody class="text-start fw-semibold text-gray-700">
                                                @if ($companySetting->company_logo_one)
                                                <tr>
                                                    <th> Default Left Menu Logo (170px X 60px) </th>
                                                    <td>
                                                        <img src="{{ Storage::url($companySetting->company_logo_one) }}"
                                                            width="220" alt="Company Logo One" />
                                                    </td>
                                                </tr>
                                                @endif
                                                @if ($companySetting->company_logo_two)
                                                <tr>
                                                    <th>Default Left Small Menu Logo (80px X 70px)</th>
                                                    <td>
                                                        <img src="{{ Storage::url($companySetting->company_logo_two) }}"
                                                            width="80" alt="Company Logo Two" />
                                                    </td>
                                                </tr>
                                                @endif
                                                @if ($companySetting->company_footer_logo)
                                                <tr>
                                                    <th>Company Footer Logo (170px X 60px)</th>
                                                    <td>
                                                        <img src="{{ Storage::url($companySetting->company_footer_logo) }}"
                                                            width="220" alt="Company Footer Logo" />
                                                    </td>
                                                </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Content Row -->
                    </div>
                    <!--end::Main column-->
                </div>
                <!--end::Content container-->
            </div>
            <!--end:: Profile Histories Content-->

        </div>
        <!--end::Content wrapper-->

    </div>
</x-default-layout>

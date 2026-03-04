<x-default-layout>
    <div class="app-main flex-column flex-row-fluid" id="kt_app_main">

        <!--begin::Content wrapper-->
        <div class="d-flex flex-column flex-column-fluid pb-10">
            <div class="p-5 mx-5">
                <!--begin:::Tabs-->
                <ul id="myTabjustified" role="tablist"
                    class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-semibold mb-8">
                    <!--begin:::Tab item-->
<!--                    <li class="nav-item">
                        <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                            Announcement Details
                        </h1>
                    </li>-->
                    <!--end:::Tab item-->
                </ul>
                <!--end:::Tabs-->

                <div class="d-flex justify-content-end my-5" style="margin-top: -60px!important;">
                    <!--begin::Button-->
<!--                    <a href="{{-- route('employees.edit', $announcements->id) --}}" id="kt_ecommerce_add_product_cancel"
                        class="btn btn-sm btn-primary me-5">Edit Employee</a>-->
                    <a class="btn btn-sm btn-success" href="{{ route('announcement.index') }}"
                        id="kt_ecommerce_add_employee_cancel">Back</a>

                    <!--end::Button-->
                </div>
            </div>
            <!--begin::Content-->
            <div id="kt_app_content" class="app-content flex-column-fluid mb-5">
                <!--begin::Content container-->
                <div id="kt_app_content_container" class="app-container">
                    <!--begin::Main column-->
                    <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
                        <!-- Content Row -->
                        <div class="card shadow">
                            <div class="card-header pt-0">
                                <!--begin::Title-->
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="card-label fw-bold text-gray-800">Announcement Details</span>
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    
                                    <div class="col-md-12">
                                        <table class="table table-hover table-bordered">
                                            <tbody>
                                                <tr>
                                                    <th scope="row" class="w-50"><strong>Announcement Title:</strong>
                                                    </th>
                                                    <td class="w-50">{{ $announcements->title }}</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row" class="w-50">
                                                        <strong>Notice visible Start Date: </strong>
                                                    </th>
                                                    <td class="w-50">{{ $announcements->start_date }}</td>
                                                </tr>
                                                {{-- <tr>
                                                    <th scope="row" class="w-50"><strong>End Date:</strong>
                                                    </th>
                                                    <td class="w-50">{{ $announcements->end_date }}</td>
                                                </tr> --}}
                                                <tr>
                                                    <th colspan="2" scope="row" class="w-50"><strong>Description:</strong>
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <td colspan="2" class="w-50">{{ $announcements->description }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="col-md-12 mt-10">
                                        <embed src="{{ Storage::url($announcements->file_path) }}" type="application/pdf"
                                       width="100%" height="1000px">
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
            <!--end::Content-->

        </div>
        <!--end::Content wrapper-->

    </div>
</x-default-layout>

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
                            Room Details </h1>
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
                        <!-- Content Row -->
                        <div class="card shadow">
                            
                            <div class="card-body">

                                <div class="row">
                                    <div class="col-md-6">
                                        <table class="table table-hover table-bordered">
                                            <tbody>
                                                
                                                <tr>
                                                    <th scope="row" class="w-50"><strong>Room Type:</strong></th>
                                                    <td class="w-50">
                                                        {{ !empty($room->room_type->type_name) ? $room->room_type->type_name : 'No Room Type' }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th scope="row" class="w-50"><strong>Room Number:</strong></th>
                                                    <td class="w-50">{{ $room->room_number }}</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row" class="w-50"><strong>Person Capacity:</strong>
                                                    </th>
                                                    <td class="w-50">{{ $room->capacity }}</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row" class="w-50"><strong>Price:</strong></th>
                                                    <td class="w-50">{{ $room->price_per_night }}</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row"><strong>Status:</strong> </th>
                                                    <td>{{ $room->status == 1 ? 'Active' : 'Disable' }}</td>
                                                </tr>

                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        {{-- <div class="col-12 col-md-6"> --}}
                                        <div class="card-body pt-0 pb-2">
                                            <div class="product fv-row fv-plugins-icon-container">
                                                {{-- <label class="form-label">Image</label> --}}

                                                <!-- Display existing images if editing -->
                                                @if (isset($room) && $room->roomimage)
                                                    <div class="mt-2 d-flex flex-wrap gap-2">
                                                        @foreach ($room->roomimage as $image)
                                                            <div class="existing-image d-flex align-items-center gap-2">
                                                                <img src="{{ asset(Storage::url($image->image_path)) }}"
                                                                    alt="Image" style="width: 250px; height: auto;">

                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        {{-- </div> --}}
                                    </div>

                                </div>

                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <!--begin::Button-->

                            <a class="btn btn-sm btn-primary me-2" href="{{ route('room.index') }}"
                                id="kt_ecommerce_add_product_cancel">Back</a>

                            <a class="btn btn-sm btn-info" href="{{ route('room.edit', $room->id) }}"
                                id="kt_ecommerce_add_product_cancel">Edit</a>

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

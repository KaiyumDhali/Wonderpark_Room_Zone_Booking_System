<x-default-layout>
    <style>
        .table> :not(caption)>*>* {
            padding: 0.3rem !important;
        }
    </style>
    <!--begin::Toolbar-->
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <!--begin::Toolbar container-->
        <div id="kt_app_toolbar_container" class="app-container  d-flex flex-stack">
            <!--begin::Page title-->
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <!--begin::Title-->
                <h3>Room List</h3>
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
        <div id="kt_app_content_container" class="app-container ">
            <!--begin::Products-->
            <div class="card card-flush">
                <!--begin::Card header-->
                <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                    <!--begin::Card title-->
                    <div class="card-title">
                        <!--begin::Search-->
                        <div class="d-flex align-items-center position-relative my-1">
                            <!--begin::Svg Icon | path: icons/duotune/general/gen021.svg-->
                            <span class="svg-icon svg-icon-1 position-absolute ms-4">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2"
                                        rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor" />
                                    <path
                                        d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z"
                                        fill="currentColor" />
                                </svg>
                            </span>
                            <!--end::Svg Icon-->
                            <input type="text" data-kt-ecommerce-order-filter="search"
                                class="form-control form-control-sm form-control form-control-sm w-250px ps-14 p-2"
                                placeholder="Search" />
                        </div>
                        <!--end::Search-->
                        <!--begin::Export buttons-->
                        <div id="kt_ecommerce_report_customer_orders_export" class="d-none"></div>
                        <!--end::Export buttons-->
                    </div>
                    <!--end::Card title-->
                    <!--begin::Card toolbar-->
                    <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                        {{-- @can('create product type') --}}
                            <a href="{{ route('room.create') }}" class="btn btn-sm btn-flex btn-light-primary">
                                Add New
                            </a>
                        {{-- @endcan --}}
                    </div>
                    <!--end::Card toolbar-->
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
                                <th class="min-w-50px"> SL</th>
                                <th class="min-w-50px"> Room Type</th>
                                <th class="min-w-50px"> Room Number</th>
                                <th class="min-w-50px"> Room Name</th>
                                <th class="min-w-250px"> Capacity</th>
                                <th class="min-w-250px"> Price</th>
                                <th class="min-w-50px"> Status</th>
                                {{-- @can('write product type') --}}
                                    <th class="text-end min-w-100px">Action</th>
                                {{-- @endcan --}}
                            </tr>
                            <!--end::Table row-->
                        </thead>
                        <!--end::Table head-->
                        <!--begin::Table body-->
                        <tbody class="fw-semibold text-gray-700">
                            @foreach ($rooms as $room)
                                <tr>
                                    <td>
                                        {{ $loop->iteration }}
                                    </td>
                                    <td>
                                        {{-- {{ $room->room_type->type_name }} --}}
                                        {{-- {{ $room->room_type->type_name ?? 'Default Room Type' }} --}}
                                        {{ !empty($room->room_type->type_name) ? $room->room_type->type_name : 'No Room Type' }}
                                    </td>
                                    <td>
                                        {{ $room->room_number }}
                                    </td>
                                    <td>
                                        {{ $room->room_name }}
                                    </td>
                                    <td>
                                        {{ $room->capacity }}
                                    </td>
                                    <td>
                                        {{ $room->price_per_night }}
                                    </td>
                                    <td>
                                        <div class="badge badge-light-{{ $room->status == 1 ? 'success' : 'info' }}">
                                            {{ $room->status == 1 ? 'Active' : 'Disabled' }}</div>
                                    </td>
                                    {{-- @can('write product type') --}}
                                        <td class="text-end">
                                            <form method="POST" action="{{ route('room_type.destroy', $room->id) }}"
                                                class="delete-product-type-form">

                                                {{ csrf_field() }}

                                                {{ method_field('DELETE') }}

                                                {{-- <button type="button" class="btn btn-sm btn-success p-2"
                                                    data-productType="{{ json_encode($room->id) }}"
                                                    onclick="openEditModalProductType(this)">
                                                    <i class="fa fa-pencil text-white px-2"></i>
                                                </button> --}}

                                                <a class="btn btn-sm btn-info p-2"
                                                    href="{{ route('room.show', $room->id) }}"
                                                    class="menu-link px-3"><i class="fa fa-eye text-white px-2"></i></a>

                                                <a class="btn btn-sm btn-success p-2"
                                                    href="{{ route('room.edit', $room->id) }}"
                                                    class="menu-link px-3"><i class="fa fa-pencil text-white px-2"></i></a>

                                                {{-- <button type="button"
                                                    class="btn btn-sm btn-danger p-2 product-type-destroy"
                                                    onclick="confirmDelete(this)">
                                                    <i class="fa fa-trash text-white px-2"></i>
                                                </button> --}}


                                            </form>
                                        </td>
                                    {{-- @endcan --}}
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








    <!--begin::Modal - Create App-->
    <div class="modal fade" id="kt_modal_new_card" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <!--begin::Modal content-->
            <div class="modal-content">
                <!--begin::Modal header-->
                <div class="modal-header">
                    <!--begin::Modal title-->
                    <h2>Add New Room</h2>
                    <!--end::Modal title-->
                    <!--begin::Close-->
                    <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                        <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                        <span class="svg-icon svg-icon-1">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1"
                                    transform="rotate(-45 6 17.3137)" fill="currentColor"></rect>
                                <rect x="7.41422" y="6" width="16" height="2" rx="1"
                                    transform="rotate(45 7.41422 6)" fill="currentColor"></rect>
                            </svg>
                        </span>
                        <!--end::Svg Icon-->
                    </div>
                    <!--end::Close-->
                </div>
                <!--end::Modal header-->
                <!--begin::Modal body-->
                <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                    <!--begin::Form-->
                    <form id="kt_modal_new_card_form" class="form fv-plugins-bootstrap5 fv-plugins-framework"
                        method="POST" action="{{ route('room.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                            <label class=" form-label">Room Type</label>
                            <select class="form-select form-select-sm" data-control="select2" name="roomtype_id">
                                <option value="">Select Room Type</option>
                                @foreach ($allRoomType as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                            <!--begin::Label-->
                            <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                <span class="required">Room Number</span>
                            </label>
                            <!--end::Label-->
                            <input type="text" class="form-control form-control-sm" name="room_number">
                            <div class="fv-plugins-message-container invalid-feedback"></div>
                        </div>

                        <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container d-none">
                            <!--begin::Label-->
                            <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                <span class="required">Floor</span>
                            </label>
                            <!--end::Label-->
                            <input type="text" class="form-control form-control-sm" name="floor"
                                value="1">
                            <div class="fv-plugins-message-container invalid-feedback"></div>
                        </div>

                        <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                            <!--begin::Label-->
                            <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                <span class="required">Capacity</span>
                            </label>
                            <!--end::Label-->
                            <input type="text" class="form-control form-control-sm" name="capacity">
                            <div class="fv-plugins-message-container invalid-feedback"></div>
                        </div>
                        <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                            <!--begin::Label-->
                            <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                <span class="required">Price</span>
                            </label>
                            <!--end::Label-->
                            <input type="text" class="form-control form-control-sm" name="price_per_night">
                            <div class="fv-plugins-message-container invalid-feedback"></div>
                        </div>

                        <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                            <!--begin::Label-->
                            <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                <span class="required">Description</span>
                            </label>
                            <!--end::Label-->
                            <input type="text" class="form-control form-control-sm" name="description">
                            <div class="fv-plugins-message-container invalid-feedback"></div>
                        </div>

                        <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                            <label class=" form-label">Status</label>
                            <select class="form-select form-select-sm" data-control="select2" name="status">
                                <option value="">--Select Status--</option>
                                <option value="1" selected>Active</option>
                                <option value="0">Disable</option>
                            </select>
                        </div>

                        <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                            <label class=" form-label">Image</label>
                            <input type="file" class="form-control form-control-sm" id="image" name="image[]"
                                multiple="multiple" accept="image/png, image/jpeg, image/jpg"
                                value="{{ old('image') }}" />
                        </div>

                        <div class="text-center pt-15">
                            <a href="{{ route('room.index') }}" id="kt_modal_new_card_cancel"
                                class="btn btn-light me-5">Cancel</a>

                            <button type="submit" id="kt_modal_new_card_submit_store" class="btn btn-primary">
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
    <!--end::Modal - Create App-->



    <!--begin::Modal - Create App-->
    <div class="modal fade" id="kt_modal_edit_card" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <!--begin::Modal content-->
            <div class="modal-content">
                <!--begin::Modal header-->
                <div class="modal-header">
                    <!--begin::Modal title-->
                    <h2>Edit Type</h2>
                    <!--end::Modal title-->
                    <!--begin::Close-->
                    <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                        <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                        <span class="svg-icon svg-icon-1">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1"
                                    transform="rotate(-45 6 17.3137)" fill="currentColor"></rect>
                                <rect x="7.41422" y="6" width="16" height="2" rx="1"
                                    transform="rotate(45 7.41422 6)" fill="currentColor"></rect>
                            </svg>
                        </span>
                        <!--end::Svg Icon-->
                    </div>
                    <!--end::Close-->
                </div>
                <!--end::Modal header-->
                <!--begin::Modal body-->
                <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                    <!--begin::Form-->
                    <form id="kt_modal_edit_card_form_room_type"
                        class="form fv-plugins-bootstrap5 fv-plugins-framework" method="POST"
                        action="{{ route('room.update', ':productTypeId') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <!--begin::Input group-->

                        <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                            <label class=" form-label">Room Type</label>
                            <select class="form-select form-select-sm" data-control="select2" name="roomtype_id">
                                <option value="">Select Room Type</option>
                                @foreach ($allRoomType as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                            <!--begin::Label-->
                            <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                <span class="required">Room Number</span>
                            </label>
                            <!--end::Label-->
                            <input type="text" class="form-control form-control-sm" name="room_number">
                            <div class="fv-plugins-message-container invalid-feedback"></div>
                        </div>

                        <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container d-none">
                            <!--begin::Label-->
                            <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                <span class="required">Floor</span>
                            </label>
                            <!--end::Label-->
                            <input type="text" class="form-control form-control-sm" name="floor">
                            <div class="fv-plugins-message-container invalid-feedback"></div>
                        </div>

                        <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                            <!--begin::Label-->
                            <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                <span class="required">Capacity</span>
                            </label>
                            <!--end::Label-->
                            <input type="text" class="form-control form-control-sm" name="capacity">
                            <div class="fv-plugins-message-container invalid-feedback"></div>
                        </div>

                        <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                            <!--begin::Label-->
                            <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                <span class="required">Price</span>
                            </label>
                            <!--end::Label-->
                            <input type="text" class="form-control form-control-sm" name="price_per_night">
                            <div class="fv-plugins-message-container invalid-feedback"></div>
                        </div>

                        <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                            <!--begin::Label-->
                            <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                <span class="required">Description</span>
                            </label>
                            <!--end::Label-->
                            <input type="text" class="form-control form-control-sm" name="description">
                            <div class="fv-plugins-message-container invalid-feedback"></div>
                        </div>

                        {{-- <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                            <label class=" form-label">Image</label>
                            <input type="file" class="form-control form-control-sm" id="image" name="image[]"
                                multiple="multiple" accept="image/png, image/jpeg, image/jpg"
                                value="{{ old('image') }}" />
                        </div> --}}

                        <div class="form-group" id="imageContainer">
                            <label for="image">{{ __('Image') }}</label>
                            <input type="file" class="form-control p-1" id="image" name="image[]"
                                multiple="multiple" accept="image/png, image/jpeg, image/jpg" />



                            <!-- Display existing images if editing -->
                            @if (isset($room) && $room->roomimage)
                                <div class="mt-2">
                                    @foreach ($room->roomimage as $image)
                                        {{-- @php
                                        echo '<pre>';
                                        print_r($image);
                                        echo '</pre>';
                                        die();
                                        @endphp --}}
                                        
                                            <div class="existing-image mb-2">
                                                <img src="{{ asset(Storage::url($image->image_path)) }}" alt="Image"
                                                    style="width: 100px; height: auto;">

                                                <a class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Are you sure you want to delete ?')"
                                                    href="{{ route('room_image_destroy', $image->id) }}"><i class="fa fa-trash"></i></a>
                                            </div>
                                        
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                            <label class=" form-label">Status</label>
                            <select class="form-select form-select-sm" data-control="select2" name="status">
                                <option value="">--Select Status--</option>
                                <option value="1" selected>Active</option>
                                <option value="0">Disable</option>
                            </select>
                        </div>

                        <div class="text-center pt-15">
                            <a href="{{ route('room.index') }}" id="kt_modal_new_card_cancel"
                                class="btn btn-light me-5">Cancel</a>

                            <button type="submit" id="kt_modal_new_card_submit" class="btn btn-primary">
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
    <!--end::Modal - Create App-->

</x-default-layout>


<script type="text/javascript">
    // product type 
    // function openEditModalProductType(button) {

    //     const productTypeData = JSON.parse(button.getAttribute('data-productType'));

    //     const formAction = $('#kt_modal_edit_card').find('#kt_modal_edit_card_form_room_type').attr('action');
    //     const productTypeId = productTypeData.id;
    //     const updatedFormAction = formAction.replace(':productTypeId', productTypeId);
    //     $('#kt_modal_edit_card').find('#kt_modal_edit_card_form_room_type').attr('action', updatedFormAction);

    //     $('#kt_modal_edit_card').find('input[name="roomtype_id"]').val(productTypeData.roomtype_id);
    //     $('#kt_modal_edit_card').find('input[name="room_number"]').val(productTypeData.room_number);
    //     $('#kt_modal_edit_card').find('input[name="capacity"]').val(productTypeData.capacity);
    //     $('#kt_modal_edit_card').find('input[name="price_per_night"]').val(productTypeData.price_per_night);
    //     $('#kt_modal_edit_card').find('input[name="description"]').val(productTypeData.description);
    //     $('#kt_modal_edit_card').find('select[name="status"]').val(productTypeData.status);

    //     $('#kt_modal_edit_card').modal('show');
    // }

    // // SweetAlert2 Confarm message after form on click ========= 
    // document.getElementById("kt_modal_new_card_submit_store").addEventListener('click', function(event) {
    //     ConfarmMsg("kt_modal_new_card_form", "Are You sure.. Want to Save?", "save", event)
    // });
    // document.getElementById("kt_modal_new_card_submit").addEventListener('click', function(event) {
    //     ConfarmMsg("kt_modal_edit_card_form_room_type", "Are You sure.. Want to Update?", "Update", event)
    // });


    function openEditModalProductType(button) {
        const productTypeData = JSON.parse(button.getAttribute('data-productType'));
        const formAction = $('#kt_modal_edit_card').find('#kt_modal_edit_card_form_room_type').attr('action');
        const productTypeId = productTypeData.id;
        const updatedFormAction = formAction.replace(':productTypeId', productTypeId);
        $('#kt_modal_edit_card').find('#kt_modal_edit_card_form_room_type').attr('action', updatedFormAction);

        // Correctly set the value for the select element
        $('#kt_modal_edit_card').find('select[name="roomtype_id"]').val(productTypeData.roomtype_id).trigger('change');

        // Set other input values
        $('#kt_modal_edit_card').find('input[name="room_number"]').val(productTypeData.room_number);
        $('#kt_modal_edit_card').find('input[name="floor"]').val(productTypeData.floor);
        $('#kt_modal_edit_card').find('input[name="capacity"]').val(productTypeData.capacity);
        $('#kt_modal_edit_card').find('input[name="price_per_night"]').val(productTypeData.price_per_night);
        $('#kt_modal_edit_card').find('input[name="description"]').val(productTypeData.description);
        $('#kt_modal_edit_card').find('select[name="status"]').val(productTypeData.status).trigger('change');

        $('#kt_modal_edit_card').modal('show');

        // SweetAlert2 Confarm message after form on click ========= 
        document.getElementById("kt_modal_new_card_submit_store").addEventListener('click', function(event) {
            ConfarmMsg("kt_modal_new_card_form", "Are You sure.. Want to Save?", "save", event)
        });
        document.getElementById("kt_modal_new_card_submit").addEventListener('click', function(event) {
            ConfarmMsg("kt_modal_edit_card_form_room_type", "Are You sure.. Want to Update?", "Update", event)
        });

    }
</script>

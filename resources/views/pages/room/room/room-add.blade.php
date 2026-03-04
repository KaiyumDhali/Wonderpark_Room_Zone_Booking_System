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
                            Add Room</h1>
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
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form id="roomAddForm"
                        class="form d-flex flex-column flex-lg-row fv-plugins-bootstrap5 fv-plugins-framework"
                        method="POST" action="{{ route('room.store') }}" enctype="multipart/form-data">
                        @csrf

                        <!--begin::Main column-->
                        <div class="col-12 col-md-12 me-lg-10">
                            <!--begin::General options-->
                            <div class="card card-flush py-4">
                                <div class="row">

                                    <div class="col-12 col-md-6">
                                        <div class="card-body pt-0 pb-2">
                                            <label class="required form-label">Room Type</label>
                                            <select class="form-select form-select-sm" data-control="select2" name="roomtype_id">
                                                <option value="">Select Room Type</option>
                                                @foreach ($allRoomType as $key => $value)
                                                    <option value="{{ $key }}">{{ $value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <div class="card-body pt-0 pb-2">
                                            <div class=" fv-row fv-plugins-icon-container">
                                                <label class=" form-label"> Room Number</label>
                                                <input type="text" name="room_number" class="form-control form-control-sm mb-2"
                                                    placeholder="Room Number" value="">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <div class="card-body pt-0 pb-2">
                                            <div class=" fv-row fv-plugins-icon-container">
                                                <label class=" form-label"> Room Name Bangla</label>
                                                <input type="text" name="room_name" class="form-control form-control-sm mb-2"
                                                    placeholder="Room Name" value="">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6 d-none">
                                        <div class="card-body pt-0 pb-2">
                                            <div class=" fv-row fv-plugins-icon-container">
                                                <label class=" form-label"> Floor</label>
                                                <input type="hidden" name="Floor" class="form-control form-control-sm mb-2"
                                                    placeholder="Floor" value="1">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <div class="card-body pt-0 pb-2">
                                            <div class="product fv-row fv-plugins-icon-container">
                                                <label class="required form-label"> Capacity</label>
                                                <input type="text" name="capacity" class="form-control form-control-sm mb-2"
                                                    placeholder="Capacity" value="" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <div class="card-body pt-0 pb-2">
                                            <div class="product fv-row fv-plugins-icon-container">
                                                <label class=" form-label"> Price</label>
                                                <input type="text" name="price_per_night" class="form-control form-control-sm mb-2"
                                                    placeholder="Price" value="">
                                            </div>
                                        </div>
                                    </div>
  
                                    <div class="col-12 col-md-6">
                                        <div class="card-body pt-0 pb-2">
                                            <div class="product fv-row fv-plugins-icon-container">
                                                <label class=" form-label">Description</label>
                                                <input type="text" name="description" class="form-control form-control-sm mb-2"
                                                    placeholder="Description" value="">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-12 col-md-6">
                                        <div class="card-body pt-0 pb-2">
                                            <div class="product fv-row fv-plugins-icon-container">
                                                <label class=" form-label">Room Thumbnail Image (520px * 360px)</label>
                                                <input type="file" class="form-control form-control-sm mb-2" id="thumbnail_image" name="thumbnail_image"
                                             accept="image/png, image/jpeg, image/jpg"
                                            value="{{ old('thumbnail_image') }}" />
                                            </div>
                                        </div>
                                    </div>
                                    
                                                                          
                                    <div class="col-12 col-md-6">
                                        <div class="card-body pt-0 pb-2">
                                            <label class="required form-label">Status</label>
                                            <select class="form-select form-select-sm" data-control="select2" name="status" required>
                                                <option value="">--Select Status--</option>
                                                <option value="1" selected>Active</option>
                                                <option value="0">Disable</option>
                                            </select>
                                        </div>
                                    </div>
                                   

                                    <div class="col-12 col-md-6">
                                        <div class="card-body pt-0 pb-2">
                                            <div class="product fv-row fv-plugins-icon-container">
                                                <label class=" form-label">Room Gallery Image</label>
                                                <input type="file" class="form-control form-control-sm mb-2" id="image" name="image[]"
                                            multiple="multiple" accept="image/png, image/jpeg, image/jpg"
                                            value="{{ old('image') }}" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--end::General options-->

                            <div class="d-flex justify-content-end my-5">
                                <!--begin::Button-->
                                <a href="{{ route('room.index') }}" id="kt_ecommerce_add_product_cancel"
                                    class="btn btn-sm btn-primary me-5">Cancel</a>
                                <!--end::Button-->
                                <!--begin::Button-->
                                <button type="submit" id="kt_ecommerce_add_category_submit"
                                    class="btn btn-sm btn-success">
                                    <span class="indicator-label">Save</span>
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
    


</x-default-layout>
<script type="text/javascript">
    document.getElementById("productAddForm").addEventListener("keydown", function(event) {
        // Check if the pressed key is Enter
        if (event.key === "Enter") {
            // Prevent default form submission
            event.preventDefault();
        }
    });
    // SweetAlert2 Confarm message after form on click ========= 
    // $('.new_card_submit').addEventListener('click', function(event) {
    //     ConfarmMsg("new_card_form", "Are You sure.. Want to Save?", "save", event)
    // });
    
    // document.getElementById("kt_modal_new_card_submit_store").addEventListener('click', function(event) {
    //     ConfarmMsg("kt_modal_new_card_form", "Are You sure.. Want to Save?", "save", event)
    // });
    // document.getElementById("kt_modal_new_card_submit").addEventListener('click', function(event) {
    //     ConfarmMsg("kt_modal_edit_card_form_product_type", "Are You sure.. Want to Update?", "Update", event)
    // });
    
</script>

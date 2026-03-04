<x-default-layout>
    <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
        <div class="d-flex flex-column flex-column-fluid">
            <!--begin::Toolbar-->
            <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
                <div id="kt_app_toolbar_container" class="app-container d-flex flex-stack">
                    <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                        <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                            Offer Details
                        </h1>
                    </div>
                    {{-- <div class="d-flex align-items-center gap-2 gap-lg-3">
                        <a href="{{ route('about_us.index') }}" class="btn btn-sm btn-light-primary">
                            <i class="ki-duotone ki-arrow-left fs-2"></i>Go Back
                        </a>
                    </div> --}}
                </div>
            </div>
            <!--end::Toolbar-->

            <!--begin::Content-->
            <div id="kt_app_content" class="app-content flex-column-fluid">
                <div id="kt_app_content_container" class="app-container">
                    <div class="card card-flush">
                        <div class="card-body py-5">
                            <div class="row">
                                <div class="col-md-7">
                                    <table class="table table-hover table-bordered">
                                        <tbody>
                                            <tr>
                                                <th class="w-25"><strong>Title:</strong></th>
                                                <td>{{ $offer->title }}</td>
                                            </tr>
                                            <tr>
                                                <th class="w-25"><strong>Description:</strong></th>
                                                <td>{{ $offer->description }}</td>
                                            </tr>
                                            <tr>
                                                <th><strong>Status:</strong></th>
                                                <td>{{ $offer->status == 1 ? 'Active' : 'Disable' }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-md-5">
                                    @if ($offer->image)
                                        <div class="form-group pb-2">
                                            <h5 class="pb-3">Offer Image:</h5>
                                            <img src="{{ asset(Storage::url($offer->image)) }}" height="220"
                                                width="320" alt="About Us Image" />
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="d-flex justify-content-end">
                                <!--begin::Button-->

                                <a class="btn btn-sm btn-primary me-2" href="{{ route('offers.index') }}"
                                    id="kt_ecommerce_add_product_cancel">Back</a>

                                <a class="btn btn-sm btn-info me-2" href="{{ route('offers.edit', $offer->id) }}"
                                    id="kt_ecommerce_add_product_cancel">Edit</a>

                                <form action="{{ route('offers.destroy', $offer->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger px-4"
                                        onclick="return confirm('Are you sure you want to delete this entry?')">
                                        Delete
                                    </button>
                                </form>

                                <!--end::Button-->
                            </div>

                            
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Content-->
        </div>
    </div>
</x-default-layout>

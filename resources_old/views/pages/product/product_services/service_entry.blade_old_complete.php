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



        <div class="row">
            
            <!-- Product add to card Content Row -->
            <div class="col-md-12">


                <form class="px-0" id="serviceAssignForm" method="POST" action="{{ route('product_services_entry_store', $getProductServicesEntry?->id) }}"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">

                        {{-- select product --}}
                        <div class="col-md-12 card shadow">
                            <div class="card-header px-2 py-5 mx-0">
                                <div class="d-sm-flex align-items-center justify-content-between">
                                    <h1 class="h3 mb-0 text-gray-800">{{ __('Service Entry') }}</h1>
                                </div>
                            </div>

                            <div class="card-body py-5 px-2">
                                <div class="col-md-12 pb-3">
                                    <div class="row">
                                        <div class="form-group col-md-2 my-3">
                                            <label for="order">{{ __('Invoice No') }}</label>
                                            <input type="text"
                                                class="form-control form-control-sm form-control-solid" id="invoice_no"
                                                name="invoice_no" value="{{ $getProductServicesEntry?->invoice_no }}"
                                                readonly>
                                        </div>
                                        <div class="form-group col-md-2 my-3">
                                            <label for="order">{{ __('Service Number') }}</label>
                                            <input type="text"
                                                class="form-control form-control-sm form-control-solid"
                                                id="service_number" name="service_number"
                                                value="{{ $getProductServicesEntry?->service_number }}" readonly>
                                        </div>

                                        <div class="form-group col-md-2 my-3">
                                            <label for="order">{{ __('Customer Name') }}</label>
                                            <input type="text"
                                                class="form-control form-control-sm form-control-solid" id=""
                                                name="" value="{{ $getProductServicesEntry?->customer_name }}"
                                                readonly>
                                            <input type="hidden" class="form-control form-control-sm" id="customer_id"
                                                name="customer_id" value="{{ $getProductServicesEntry?->customer_id }}">
                                        </div>
                                        <div class="form-group col-md-2 my-3">
                                            <label for="order">{{ __('Customer Mobile') }}</label>
                                            <input type="text"
                                                class="form-control form-control-sm form-control-solid"
                                                id="customer_mobile" name="customer_mobile"
                                                value="{{ $getProductServicesEntry?->customer_mobile }}" readonly>
                                        </div>
                                        <div class="form-group col-md-4 my-3">
                                            <label for="order">{{ __('Customer Address') }}</label>
                                            <input type="text"
                                                class="form-control form-control-sm form-control-solid"
                                                id="customer_address" name="customer_address"
                                                value="{{ $getProductServicesEntry?->customer_address }}" readonly>
                                        </div>

                                        <div class="form-group col-md-4 my-3">
                                            <label for="order">{{ __('Product Name') }}</label>
                                            <input type="text"
                                                class="form-control form-control-sm form-control-solid" id=""
                                                name="" value="{{ $getProductServicesEntry?->product_name }}"
                                                readonly>
                                            <input type="hidden" class="form-control form-control-sm" id="product_id"
                                                name="product_id" value="{{ $getProductServicesEntry?->product_id }}">
                                        </div>
                                        <div class="form-group col-md-2 my-3">
                                            <label for="order">{{ __('Service Date') }}</label>
                                            <input type="date"
                                                class="form-control form-control-sm form-control-solid"
                                                id="service_date" name="service_date"
                                                value="{{ $getProductServicesEntry?->service_date }}" readonly>
                                        </div>
                                        <div class="form-group col-md-2 my-3">
                                            <label for="order">{{ __('Actual Service Date') }}</label>
                                            <input type="date" class="form-control form-control-sm"
                                                id="actual_service_date" name="actual_service_date"
                                                value="{{ $getProductServicesEntry?->service_date }}">
                                        </div>
                                        <div class="form-group col-md-2 my-3">
                                            <label for="order">{{ __('Service Man Name') }}</label>
                                            <input type="text" class="form-control form-control-sm"
                                                id="service_man_name" name="service_man_name">
                                        </div>
                                        <div class="form-group col-md-2 my-3">
                                            <label for="order">{{ __('Service Man Mobile') }}</label>
                                            <input type="text" class="form-control form-control-sm"
                                                id="service_man_mobile" name="service_man_mobile">
                                        </div>
                                        <div class="form-group col-md-6 my-3">
                                            <label for="order">{{ __('Remark') }}</label>
                                            <textarea class="form-control form-control-sm" id="remarks" name="remarks" rows="5"></textarea>
                                        </div>
                                    </div>


                                    <!--begin::Button-->
                                    <button type="submit" id="kt_ecommerce_add_category_submit"
                                        class="btn btn-sm btn-success">
                                        <span class="indicator-label">Service Entry</span>
                                        <span class="indicator-progress">Please wait...
                                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                    </button>
                                    <!--end::Button-->




                                </div>
                            </div>
                        </div>

                    </div>

                </form>

            </div>


        </div>

        
    </div>




</x-default-layout>


<script type="text/javascript">
    document.getElementById("serviceAssignForm").addEventListener("keydown", function(event) {
        // Check if the pressed key is Enter
        if (event.key === "Enter") {
            // Prevent default form submission
            event.preventDefault();
        }
    });

    $('[id^="serviceQuantity_"]').on('keyup', 'input[name^="service_quantity"]', function(e) {
        const index = $(this).data('index');
        const serviceQuantity = parseInt($(this).val()) ||
            0; // Convert input to integer, default to 0 if not a number
        const appendServicDate = $(`#appendServicDate_${index}`);

        appendServicDate.empty(); // Clear previous fields for this specific index

        for (let i = 0; i < serviceQuantity; i++) {
            let service_number = i + 1;
            let div = `
                <div class="form-group col-md-3 my-3">
                    <label class="required">{{ __('Service Date ') }} ${i + 1}</label>
                    <input type="date" class="form-control form-control-sm"
                           name="service_date[${index}][]" required />
                </div>`;

            //if need pass service_number

            // <input type="hidden" class="form-control form-control-sm"
            //            name="service_number[${index}][]" value="${i + 1}" required />
            appendServicDate.append(div); // Append new fields within this specific quantity section
        }
    });
</script>

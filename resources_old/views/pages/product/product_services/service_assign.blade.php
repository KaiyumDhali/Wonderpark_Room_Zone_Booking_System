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
                <form class="px-0" id="serviceAssignForm" method="POST" action="{{ route('product_services_store') }}"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="row">

                        <div class="col-md-12 card shadow">

                            <div class="card-header px-2 py-5 mx-0">
                                <div class="d-sm-flex align-items-center justify-content-between">
                                    <h1 class="h3 mb-0 text-gray-800">{{ __('Service Assign') }}</h1>
                                </div>
                            </div>
                            <div class="card-body py-5 px-2">
                                <div class="col-md-12 pb-3">
                                    <div class="row">
                                        <div class="form-group col-md-4 my-3">
                                            <label for="order">{{ __('Invoice No') }}</label>
                                            <input type="text" class="form-control form-control-sm" id="invoice_no"
                                                name="invoice_no" value="{{ $stocks[0]?->invoice_no }}">
                                        </div>

                                        <div class="form-group col-md-4 my-3">
                                            <label for="order">{{ __('Customer Name') }}</label>
                                            <input type="text" class="form-control form-control-sm" id="customer_id"
                                                name=""
                                                value="{{ $stocks[0]?->customer_finance_account?->account_name }}">
                                            <input type="hidden" class="form-control form-control-sm" id="customer_id"
                                                name="customer_id"
                                                value="{{ $stocks[0]?->customer_finance_account?->id }}">
                                        </div>
                                    </div>

                                    {{-- @foreach ($stocks as $stock) --}}
                                    @foreach ($stocks as $index => $stock)
                                        <div class="card my-5 p-5">
                                            <div class="row">

                                                {{-- {{ dd($stock) }} --}}

                                                <div class="form-group col-md-3 my-3">
                                                    <label for="order">{{ __('Product Name') }}</label>
                                                    <input type="text" class="form-control form-control-sm"
                                                        id="product_id" name=""
                                                        value="{{ $stock->product->product_name }}">
                                                    <input type="hidden" class="form-control form-control-sm"
                                                        id="product_id" name="product_id[]"
                                                        value="{{ $stock->product->id }}">
                                                </div>

                                                <div class="form-group col-md-3 my-3">
                                                    <label for="order">{{ __('Location') }}</label>
                                                    <input type="text" class="form-control form-control-sm"
                                                        id="location" name="service_location[]"
                                                        value="{{ $stock->customer_finance_account->account_address }}">
                                                </div>

                                                <div class="form-group col-md-6 my-3">
                                                    <label for="order">{{ __('Description') }}</label>
                                                    <textarea class="form-control form-control-sm" id="service_description" name="service_description[]" rows="1"></textarea>
                                                </div>

                                                <div class="form-group col-md-3 my-3">
                                                    <label for="order">{{ __('Service Type') }}</label>
                                                    {{-- <input type="text" class="form-control form-control-sm"
                                                        id="service_type" name="service_type[]" /> --}}
                                                    
                                                    <!--begin::Select2-->
                                                    <select class="form-select form-select-sm" data-control="select2" id="service_type" name="service_type[]">
                                                        <option value="">Select Type</option>
                                                        <option value="1" selected>Monthly</option>
                                                        <option value="2">Yearly</option>
                                                    </select>
                                                </div>

                                                <div class="form-group col-md-3 my-3">
                                                    <label class="required">{{ __('Service Start Date') }}</label>
                                                    <input type="date"
                                                        class="form-control form-control-sm form-control-solid"
                                                        id="service_start_date" name="service_start_date[]"
                                                        value="" required />
                                                </div>

                                                <div class="form-group col-md-3 my-3">
                                                    <label class="required">{{ __('Service End Date') }}</label>
                                                    <input type="date"
                                                        class="form-control form-control-sm form-control-solid"
                                                        id="service_end_date" name="service_end_date[]" value=""
                                                        required />
                                                </div>

                                                <div class="form-group col-md-3 my-3"
                                                    id="serviceQuantity_{{ $index }}">
                                                    <label for="order">{{ __('Service Quantity') }}</label>
                                                    <input type="text" class="form-control form-control-sm"
                                                        name="service_quantity[{{ $index }}]" step="1"
                                                        min="0"
                                                        oninput="this.value = this.value.replace(/\D/g, '');"
                                                        value="0" data-index="{{ $index }}">
                                                </div>

                                                <div class="row" id="appendServicDate_{{ $index }}">

                                                </div>


                                            </div>
                                        </div>
                                    @endforeach

                                    <!--begin::Button-->
                                    <button type="submit" id="kt_ecommerce_add_category_submit"
                                        class="btn btn-sm btn-success">
                                        <span class="indicator-label">Save</span>
                                        <span class="indicator-progress">Please wait...
                                            <span
                                                class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
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

            appendServicDate.append(div); // Append new fields within this specific quantity section
        }
    });
</script>

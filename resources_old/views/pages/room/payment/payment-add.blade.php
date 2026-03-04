<x-default-layout>
    <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
        <!--begin::Content wrapper-->
        <div class="d-flex flex-column flex-column-fluid">





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
                        method="POST" action="{{ route('payment.store') }}" enctype="multipart/form-data">
                        @csrf

                        <!--begin::Main column-->
                        <div class="col-4 col-md-4 me-lg-10">

                            <!--begin::Toolbar-->
                            <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
                                <!--begin::Toolbar container-->
                                <div id="kt_app_toolbar_container" class="app-container d-flex flex-stack">
                                    <!--begin::Page title-->
                                    <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                                        <!--begin::Title-->
                                        <h1
                                            class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                                            Payment Receive</h1>
                                    </div>
                                    <!--end::Page title-->
                                </div>
                                <!--end::Toolbar container-->
                            </div>
                            <!--end::Toolbar-->
                            <!--begin::General options-->
                            <div class="card card-flush py-4">
                                <div class="row">

                                    <div class="col-12 col-md-12">

                                        <div class="card-body pt-0 pb-2">
                                            <div class=" fv-row fv-plugins-icon-container">
                                                {{-- <label class=" form-label">Customer Details</label> --}}
                                                <b>Customer Name :</b>

                                                {{ $firstBooking->customer->customer_name ?? 'N/A' }}
                                                {{-- <b>Room No :</b> {{ $totalReceived[0]->booking->room->room_number ?? 'N/A'}} --}}
                                                <br>
                                                <b>Total Amount :</b> {{ $bookingTotalSum ?? 'N/A' }}
                                                <br>
                                                <b>Receive Amount :</b> {{ $totalReceivedSum ?? 'N/A'}}
                                                <br>
                                                <b>Due Amount :</b> {{ $bookingTotalSum - $totalReceivedSum ?? 'N/A'}}
                                                <br>
                                            </div>
                                        </div>
                                        <div class="card-body pb-2">
                                            <div class=" fv-row fv-plugins-icon-container">
                                                <label class=" form-label">Receive Amount</label>
                                                <input type="text" name="amount"
                                                    class="form-control form-control-sm mb-2" placeholder="Receive Amount"
                                                    value="">
                                                <input type="hidden" name="booking_no"
                                                    class="form-control form-control-sm mb-2" placeholder="booking_no"
                                                    value="{{ $bookingNo }}">
                                            </div>
                                        </div>


                                    


                                    <div class="d-flex justify-content-end my-5 px-10">
                                        <!--begin::Button-->
                                        <a href="{{ route('booking.index') }}" id="kt_ecommerce_add_product_cancel"
                                            class="btn btn-sm btn-primary me-5">Cancel</a>
                                        <!--end::Button-->
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
                                <!--end::Main column-->
                            </div>
                        </div>


                        <div class="col-8 col-md-8 me-lg-10">
                            <!--begin::Toolbar-->
                            <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
                                <!--begin::Toolbar container-->
                                <div id="kt_app_toolbar_container" class="app-container d-flex flex-stack">
                                    <!--begin::Page title-->
                                    <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                                        <!--begin::Title-->
                                        <h1
                                            class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                                            Payment Details</h1>
                                    </div>
                                    <!--end::Page title-->
                                </div>
                                <!--end::Toolbar container-->
                            </div>
                            <!--end::Toolbar-->
                            <!--begin::General options-->
                            <div class="card card-flush py-4">
                                <div class="row">

                                    <div class="col-12 col-md-12">
                                        <div class="card-body pt-0">
                                            {{-- <span>{{$dueAmount}}</span> --}}
                                            <!--begin::Table-->
                                            <table
                                                class="table table-striped table-bordered align-middle table-row-dashed fs-7 gy-5 mb-0 text-center"
                                                id="kt_ecommerce_report_customer_orders_table">
                                                <!--begin::Table head-->
                                                <thead>
                                                    <!--begin::Table row-->
                                                    <tr class="text-start fs-7 text-uppercase gs-0 text-center">
                                                        <th class="min-w-100px">ID</th>
                                                        <th class="min-w-100px">Payment Date</th>
                                                        <th class="min-w-100px">Paid Amount</th>
                                                        {{-- <th class="min-w-100px">Due Amount</th> --}}
                                                    </tr>

                                                    <!--end::Table row-->
                                                </thead>
                                                <!--end::Table head-->
                                                <!--begin::Table body-->
                                                <tbody class="fw-semibold text-gray-700 text-center">
                                                    @php $totalAmount = 0; @endphp
                                                    @foreach ($totalReceived as $totalReceiveds)
                                                        @php $totalAmount += $totalReceiveds->amount; @endphp
                                                        <tr>
                                                            <td>{{ $totalReceiveds->id }}</td>
                                                            <td>{{ $totalReceiveds->created_at->format('Y-m-d') }}</td>
                                                            <td>{{ $totalReceiveds->amount }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>

                                                <!-- Display the total amount -->
                                                <tr>
                                                    <td colspan="2" style="text-align: right;"><strong>Total Receive
                                                            Amount:</strong></td>
                                                    <td><strong>{{ $totalPaid = $totalAmount }}</strong></td>
                                                </tr>

                                                <!--end::Table body-->
                                            </table>
                                            <!--end::Table-->
                                        </div>
                                    </div>



                                </div>
                                <!--end::Main column-->
                            </div>
                        </div>
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

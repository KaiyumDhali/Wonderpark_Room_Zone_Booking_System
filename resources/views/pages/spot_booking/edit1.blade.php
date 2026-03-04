<x-default-layout>

{{-- ================= DATA ================= --}}
<script>
    const spots = @json($spots);
    const packages = @json($packages);
    const bookings = @json($calendarData);
    const bookedSpots = @json($bookedSpots);
    const MAX_DISCOUNT = {{ $discountLimit }};
    const MULTIPLE_SPOT_DISCOUNT = {{ $multipleSpotDiscountLimit }};
    const editBooking = @json($firstBooking);
    const editItems = @json($firstBooking->items ?? []);
    let currentInvoiceSpots = @json($currentInvoiceSpots ?? []);
</script>

<form action="{{ route('spot-bookings.update1', $firstBooking->invoice_number) }}" method="POST">
@csrf
@method('PUT')

<div class="app-container booking-wrap">

    {{-- ================= HEADER (Shadcn Style) ================= --}}
    <div class="booking-header d-flex align-items-center justify-content-between mb-6">
        <div class="d-flex align-items-center gap-4">
            <div class="booking-icon">
                <i class="fa fa-calendar-check text-white fs-3"></i>
            </div>
            <div>
                <h2 class="fw-bold mb-0">Create Spot Booking</h2>
                <p class="text-muted mb-0">Add new spot package booking</p>
            </div>
        </div>
        <a href="{{ route('spot-bookings.index') }}" class="btn btn-outline-secondary">
            <i class="fa fa-arrow-left me-2"></i> Back
        </a>
    </div>

    {{-- ================= CUSTOMER INFO CARD ================= --}}
    <div class="shad-card mb-6">
        <div class="shad-body">
            <div class="row g-5">

                {{-- Customer --}}
               <div class="col-md-5">
    <label class="shad-label required">
        <i class="fa fa-user me-2 text-primary"></i>
        Customer Name
    </label>

    <div class="input-group">
        <div class="flex-grow-1">
        <select id="changeCustomer"
        class="form-control shad-input"
        name="customer_id" 
        required
        data-control="select2"
        data-placeholder="Select Customer">
    <option></option>
@foreach ($customerAccounts as $c)
    <option value="{{ $c->id }}"
        {{ old('customer_id', $firstBooking->customer_name ?? '') == $c->account_name ? 'selected' : '' }}>
        {{ $c->account_name }}
    </option>
@endforeach
</select>

        </div>

        <button type="button"
                class="btn btn-light-primary"
                data-bs-toggle="modal"
                data-bs-target="#add_customer_modal"
                title="Add Customer">
            <i class="fa fa-plus"></i>
        </button>
    </div>
</div>



                {{-- Account --}}
                <div class="col-md-3">
                    <label class="shad-label required">
                        <i class="fa fa-credit-card me-2 text-primary"></i>
                        Receive Account Type
                    </label>
                    <select id="changeAccountsName"
                            class="form-control shad-input"
                            name="receive_account"
                            required data-control="select2"
                            data-placeholder="Select Payment Account">
                        <option></option>
                        @foreach ($toAccounts as $acc)
                           <option value="{{ $acc->id }}"
    {{ old('receive_account', $firstBooking->receive_account) == $acc->id ? 'selected' : '' }}>
    {{ $acc->account_name }}
</option>
                        @endforeach
                    </select>
                </div>

                {{-- Amount --}}
                <div class="col-md-2">
                    <label class="shad-label required">
                        <i class="fa fa-money-bill me-2 text-primary"></i>
                        Receive Amount
                    </label>
                   <input type="number"
       name="receive_amount"
       value="{{ old('receive_amount', $firstBooking->receive_amount) }}">
                </div>

                {{-- Status --}}
                <div class="col-md-2">
                    <label class="shad-label">
                        <i class="fa fa-check-circle me-2 text-primary"></i>
                        Status
                    </label>
                    <select name="status" class="form-select shad-input">
                        <option value="1"
                            {{ old('status', $firstBooking->status) == 1 ? 'selected':'' }}>
                            Confirmed
                        </option>

                        <option value="0"
                            {{ old('status', $firstBooking->status) == 0 ? 'selected':'' }}>
                            Pending
                        </option>
                    </select>
                </div>

            </div>
        </div>
    </div>


    {{-- ================= CALENDAR + SPOTS ================= --}}
    <div class="row g-6 mb-6">

        {{-- Calendar --}}
        <div class="col-md-7">
            <div class="shad-card h-100">
                <div class="shad-head">
                    <h4 class="fw-semibold mb-0">
                        <i class="fa fa-calendar-days me-2 text-primary"></i>
                        Select Booking Date
                    </h4>
                </div>
                <div class="shad-body">
                    <div id="spot_booking_calendar"></div>

                    {{-- Legend --}}
                    <div class="legend-box mt-5">
                        <div class="legend-title">
                            <i class="fa fa-circle-info me-2"></i> Availability Legend
                        </div>
                        <div class="legend-grid">
                            <div class="d-flex align-items-center gap-2">
                                <span class="legend-color free"></span> Available
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <span class="legend-color partial"></span> Partially Booked
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <span class="legend-color full"></span> Fully Booked
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <span class="legend-color past"></span> Past Date
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- Spot Selection --}}
        <div class="col-md-5">
            <div class="shad-card h-100">
                <div class="shad-head">
                    <h4 class="fw-semibold mb-0">
                        <i class="fa fa-map-pin me-2 text-primary"></i>
                        Available Spots
                        <span id="selected_date_text" class="text-muted small ms-2"></span>
                    </h4>
                </div>
                <div class="shad-body ">
                    <div class="row g-4 " id="spot_boxes">
                        <div class="text-muted small text-center py-10">
                            Select a date from the calendar
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>


   {{-- ================= SELECTED PACKAGES ================= --}}
    <div class="shad-card mb-6 d-none" id="selectedPackagesCard">
        <div class="shad-head">
            <h4 class="fw-semibold mb-0">
                <i class="fa fa-box-open me-2 text-primary"></i>
                Selected Spot
            </h4>
        </div>
        <div class="shad-body">
            <div class="row g-4" id="selected_grid"></div>

            <div class="total-row mt-5">
                <span class="text-muted">Spot Total</span>
                <span class="fw-bold text-primary fs-4">৳ <span id="spotTotal">0</span></span>
            </div>

            <input type="hidden" name="booking_date" id="booking_date">
            <input type="hidden" name="items" id="items">
            <input type="hidden" name="total_price" id="total_price">

        </div>
    </div> 

{{-- ================= PACKAGES OF SELECTED SPOT ================= --}}
<div class="shad-card mb-6" id="spotPackagesCard">

    <div class="shad-head">
        <h4 class="fw-semibold mb-0">
            <i class="fa fa-box me-2 text-primary"></i>
            Packages for <span id="spotTitleText"></span>
        </h4>
    </div>
    <div class="shad-body">
        <div class="row g-4" id="spot_packages_grid"></div>
        <div class="total-row mt-5">
                <span class="text-muted">Package Total</span>
                <span class="fw-bold text-primary fs-4">৳ <span id="summaryPackage">0</span></span>
            </div>
    </div>
</div>

    {{-- ================= SERVICES TOGGLE ================= --}}
    <div class="d-flex align-items-center gap-3 mb-4">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" id="toggleServices">
        </div>
        <label for="toggleServices" class="fw-semibold cursor-pointer">
            <i class="fa fa-sparkles me-2 text-warning"></i>
            Add Additional Services
        </label>
    </div>

    {{-- ================= SERVICES CARD ================= --}}
    <div class="shad-card mb-6 d-none" id="additionalServicesCard">
        <div class="shad-head">
            <h4 class="fw-semibold mb-0">
                <i class="fa fa-sparkles me-2 text-warning"></i>
                Additional Services
            </h4>
        </div>
        <div class="shad-body">
            <div class="table-responsive">
                <table class="table shad-table align-middle">
                    <thead>
                        <tr>
                            <th>Service</th>
                            <th width="120">Price</th>
                            <th width="150">Qty</th>
                            <th width="120">Total</th>
                            <th width="80" class="text-center">Select</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($additionalServices as $service)
                        <tr class="service-row">
                            <td class="fw-semibold">{{ $service->title }}</td>

                            <td>
                                <input type="number"
                                    class="form-control shad-input-sm price"
                                    value="{{ $service->price }}"
                                    min="0"
                                    @if($service->editable_status != 1) readonly @endif>
                            </td>

                            <td>
                                <div class="qty-wrap">
                                    <button type="button" class="qty-btn minus">-</button>
                                    <input type="number" class="form-control shad-input-sm qty"
                                        value="1" min="1">
                                    <button type="button" class="qty-btn plus">+</button>
                                </div>
                            </td>

                            <td class="fw-bold text-primary">৳ <span class="row-total">0</span></td>

                            <td class="text-center">
                                <input type="checkbox" class="form-check-input service-check"
                                    data-id="{{ $service->id }}"
                                    data-title="{{ $service->title }}">
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                 <div class="total-row mt-5">
                <span class="text-muted">Additional service Total</span>
                <span class="fw-bold text-primary fs-4">৳ <span id="serviceTotalDisplay">0</span></span>
                

            </div>
            </div>
        </div>
    </div>

    <input type="hidden" name="additional_services" id="additional_services">
    <input type="hidden" name="discount_percent" id="discount_percent">
    <input type="hidden" name="spot_discount_percent" id="spot_discount_percent">


  {{-- ================= DISCOUNT + SUMMARY ================= --}}
<div class="shad-card mb-6">
    <div class="shad-body">
        <div class="row g-5 align-items-start">

            {{-- Discount --}}
            <div class="col-md-6">
                <!-- can leave blank or put something here if needed -->
            </div>

            {{-- Summary --}}
            <div class="col-md-6">
                <!-- Discount Input -->
              

                <!-- Summary Box -->
                <div class="summary-box">

                    <div class="summary-line">
                        <span>Spot Total</span>
                        <span class="fw-semibold">৳ <span id="summarySpot">0</span></span>
                    </div>

                    <div class="summary-line">
                        <span>Package Total</span>
                        <span class="fw-semibold">৳ <span id="Packaget">0</span></span>
                    </div>

                    <div class="summary-line">
                        <span>Service Total</span>
                        <span class="fw-semibold">৳ <span id="summaryService">0</span></span>
                    </div>

                    

                    <div class="summary-total mt-3 py-3">
                        <span class="">
                          Grand Total
                        </span>
                        <span class="grand-total">৳ <span id="grandTotal1">0</span></span>
                    </div>
                <div class="row">
                 <div class="text-danger col-md-12 d-flex align-items-center justify-content-between" id="discountRow">
                    <div>
                        <span>Discount (<span id="discountLabel">0</span>%)</span>
                        <input type="number"
                            id="discountPercent"
                            class="ps-0 ms-2 shad-input-sm1 text-end"
                            value="0"
                            min="0" max="{{ $discountLimit }}">
                    </div>
                    <span id="discountAmountText">৳ 0</span>
                </div>

              
                    <div class="col-md-12 d-flex justify-content-between">
                        <span class="fw-semibold">After Discount Amount</span>
                        <span class="fw-semibold">৳ <span id="afterDiscountAmount">0</span></span>
                    </div>
                 

                    <div class="summary-line mt-2" id="invoiceAdjustmentRow">
                        <span>Invoice Adjustment Discount</span>
                        <span class="text-end">-৳ 
                            <span id="invoiceAdjustmentAmount" style="cursor:pointer;">0</span>
                            <input type="number"
                                   id="discountAmountInput"
                                   class="shad-input-sm1 d-none text-end"
                                   value="0"
                                   min="0"
                                   disabled>
                        </span>
                    </div>


                    <input type="hidden" name="discount_amount" id="discount_amount" value="0">

 <input type="hidden" 
       name="invoice_adjustment_discount" 
       id="invoice_adjustment_discount" 
       value="0">

                    <div class="summary-total mt-3">
                        <span class=" fw-semibold">
                          Net Payable
                        </span>
                        <span class="grand-total fw-semibold">৳ <span id="grandTotal">0</span></span>
                    </div>

                </div>
                </div>

              
            </div>

        </div>
    </div>
</div>

    {{-- ================= SAVE BUTTON ================= --}}
    <button type="submit" id="saveBookingBtn"
        class="btn shad-save-btn w-100 py-3 fw-bold"
        disabled>
        <i class="fa fa-save me-2 text-white"></i> Save Booking
    </button>

</div>
</form>


  {{-- add new customer modal --}}
    <div class="modal fade" id="add_customer_modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Add Customer</h2>
                    <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                        <i style="font-size: 20px" class="fas fa-times"></i>
                    </div>
                </div>
                <div class="modal-body scroll-y mx-5 mx-xl-15 my-5">
                    <form id="new_card_form" class="form fv-plugins-bootstrap5 fv-plugins-framework" method="POST"
                        action="{{ route('customers.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="card-body pt-0 pb-3">
                                    <div class="fv-row fv-plugins-icon-container">
                                        <label class="required form-label">Customer Type</label>
                                        <select class="form-select form-select-sm" data-control="select2"
                                            name="customer_type" required>
                                            <option value="">Select Customer Type</option>
                                            @foreach ($customerTypes as $key => $value)
                                                <option value="{{ $key }}">{{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="col-12 col-md-6">
                                <div class="card-body pt-0 pb-3">
                                    <div class="fv-row fv-plugins-icon-container">
                                        <label class="required form-label">Customer Code</label>
                                        <input type="text" name="customer_code"
                                            class="form-control form-control-sm mb-2" placeholder="Customer Code"
                                            value="" required>
                                        <div class="fv-plugins-message-container invalid-feedback"></div>
                                    </div>
                                </div>
                            </div> --}}
                            <div class="col-12 col-md-6">
                                <div class="card-body pt-0 pb-3">
                                    <div class="fv-row fv-plugins-icon-container">
                                        <label class="required form-label">Name</label>
                                        <input type="text" name="customer_name"
                                            class="form-control form-control-sm mb-2" placeholder="Customer Name"
                                            value="" required>
                                        <input type="text" class="form-control form-control-sm" name="sumite_type"
                                            value="1" hidden>
                                        <div class="fv-plugins-message-container invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="col-12 col-md-6">
                                <div class="card-body pt-0 pb-3">
                                    <div class="fv-row fv-plugins-icon-container">
                                        <label class="form-label">Gender</label>
                                        <select class="form-select form-select-sm" data-control="select2" name="customer_gender">
                                            <option value="" selected>--Select Gender--</option>
                                            <option value="1">Male</option>
                                            <option value="0">Female</option>
                                        </select>
                                    </div>
                                </div>
                            </div> --}}
                            <div class="col-12 col-md-6">
                                <div class="card-body pt-0 pb-3">
                                    <div class="fv-row fv-plugins-icon-container">
                                        <label class="required form-label">Cell Number</label>
                                        <input type="text" name="customer_mobile"
                                            class="form-control form-control-sm mb-2" placeholder="Customer Mobile"
                                            value="" required>
                                        <div class="fv-plugins-message-container invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="card-body pt-0 pb-3">
                                    <div class="fv-row fv-plugins-icon-container">
                                        <label class=" form-label"> E-mail </label>
                                        <input type="text" name="customer_email"
                                            class="form-control form-control-sm mb-2" placeholder="Customer Email"
                                            value="">
                                        <div class="fv-plugins-message-container invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="col-12 col-md-6">
                                <div class="card-body pt-0 pb-3">
                                    <div class="fv-row fv-plugins-icon-container">
                                        <label class=" form-label">DOB</label>
                                        <input type="date" name="customer_DOB"
                                            class="form-control form-control-sm mb-2" placeholder="Customer DOB"
                                            value="">
                                        <div class="fv-plugins-message-container invalid-feedback"></div>
                                    </div>
                                </div>
                            </div> --}}
                            {{-- <div class="col-12 col-md-6">
                                <div class="card-body pt-0 pb-3">
                                    <div class="fv-row fv-plugins-icon-container">
                                        <label class=" form-label">NID Number</label>
                                        <input type="text" name="nid_number"
                                            class="form-control form-control-sm mb-2" placeholder="NID Number"
                                            value="">
                                        <div class="fv-plugins-message-container invalid-feedback"></div>
                                    </div>
                                </div>
                            </div> --}}
                            {{-- <div class="col-12 col-md-6">
                                <div class="card-body pt-0 pb-3">
                                    <div class="fv-row fv-plugins-icon-container">
                                        <label class=" form-label">Vat Reg No</label>
                                        <input type="text" name="vat_reg_no"
                                            class="form-control form-control-sm mb-2" placeholder="Vat Reg No"
                                            value="">
                                        <div class="fv-plugins-message-container invalid-feedback"></div>
                                    </div>
                                </div>
                            </div> --}}
                            {{-- <div class="col-12 col-md-6">
                                <div class="card-body pt-0 pb-3">
                                    <div class="fv-row fv-plugins-icon-container">
                                        <label class=" form-label"> e-TIN No </label>
                                        <input type="text" name="tin_no"
                                            class="form-control form-control-sm mb-2" placeholder="Tin No"
                                            value="">
                                        <div class="fv-plugins-message-container invalid-feedback"></div>
                                    </div>
                                </div>
                            </div> --}}
                            {{-- <div class="col-12 col-md-6">
                                <div class="card-body pt-0 pb-3">
                                    <div class="fv-row fv-plugins-icon-container">
                                        <label class=" form-label">Trade License</label>
                                        <input type="text" name="trade_license"
                                            class="form-control form-control-sm mb-2" placeholder="Trade License"
                                            value="">
                                        <div class="fv-plugins-message-container invalid-feedback"></div>
                                    </div>
                                </div>
                            </div> --}}
                            {{-- <div class="col-12 col-md-6">
                                <div class="card-body pt-0 pb-3">
                                    <div class="fv-row fv-plugins-icon-container">
                                        <label class=" form-label">Commission Rate</label>
                                        <input type="text" name="discount_rate"
                                            class="form-control form-control-sm mb-2" placeholder="Discount Rate"
                                            value="">
                                        <div class="fv-plugins-message-container invalid-feedback"></div>
                                    </div>
                                </div>
                            </div> --}}
                            <div class="col-12 col-md-6">
                                <div class="card-body pt-0 pb-3">
                                    <div class="fv-row fv-plugins-icon-container">
                                        <label class=" form-label">Customer Address</label>
                                        <input type="text" name="customer_address"
                                            class="form-control form-control-sm mb-2" placeholder="Customer Address"
                                            value="">
                                        <div class="fv-plugins-message-container invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="col-12 col-md-6">
                                <div class="card-body pt-0 pb-3">
                                    <div class="fv-row fv-plugins-icon-container">
                                        <label class=" form-label">Shipping Address</label>
                                        <input type="text" name="shipping_address"
                                            class="form-control form-control-sm mb-2" placeholder="Shipping Address"
                                            value="">
                                        <div class="fv-plugins-message-container invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="card-body pt-0 pb-3">
                                    <div class="fv-row fv-plugins-icon-container">
                                        <label class=" form-label">Previous Due</label>
                                        <input type="text" name="is_previous_due"
                                            class="form-control form-control-sm mb-2" placeholder="Previous Due"
                                            value="">
                                        <div class="fv-plugins-message-container invalid-feedback"></div>
                                    </div>
                                </div>
                            </div> --}}
                            <div class="col-12 col-md-6">
                                <div class="card-body pt-0 pb-3">
                                    <div class="fv-row fv-plugins-icon-container">
                                        <label class=" form-label">Status</label>
                                        <select class="form-select form-select-sm" data-control="select2"
                                            name="status">
                                            <option value="">--Select Status--</option>
                                            <option value="1" selected>Active</option>
                                            <option value="0">Disable</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="text-center pt-5">
                            <button class="btn btn-sm btn-success me-5" data-bs-dismiss="modal">Cancel</button>
                           <button type="submit"
        class="btn shad-save-btn w-100 py-3 fw-bold">
    <i class="fa fa-save me-2 text-white"></i>
    Update Booking
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

{{-- ================= STYLES (Shadcn look for Blade) ================= --}}
<style>
    
    .input-group .select2-container {
    width: 100% !important;
}

.input-group {
    flex-wrap: nowrap !important;
}

    .spot-btn{
    position:relative;
}
.spot-btn.disabled{
    opacity:.5;
    /* pointer-events:none; */
}
.spot-btn.selected{
    background:#1aa782;
    color:#fff;
    border-color:#1aa782;
}
.spot-btn.selected .pkg-price{
    color:rgba(255,255,255,.85);
}

    /* =========================
   FULLCALENDAR SHADCN THEME
========================= */
#spot_booking_calendar {
    --fc-border-color: #eee;
    --fc-page-bg-color: transparent;
    --fc-today-bg-color: rgba(26,167,130,.08);
    --fc-neutral-bg-color: transparent;
}

/* Toolbar */
.fc .fc-toolbar {
    margin-bottom: 14px !important;
}
.fc .fc-toolbar-title {
    font-size: 18px !important;
    font-weight: 700 !important;
    color: #111827 !important;
}

/* Buttons */
.fc .fc-button {
    background: #fff !important;
    border: 1px solid #e5e7eb !important;
    color: #374151 !important;
    border-radius: 12px !important;
    padding: 6px 12px !important;
    font-weight: 600 !important;
    box-shadow: 0 1px 2px rgba(0,0,0,.04);
    transition: .2s;
}
.fc .fc-button:hover {
    border-color: rgba(26,167,130,.4) !important;
    color: #1aa782 !important;
}
.fc .fc-button:focus {
    box-shadow: 0 0 0 3px rgba(26,167,130,.12) !important;
}
.fc .fc-button-primary:not(:disabled).fc-button-active {
    background: rgba(26,167,130,.12) !important;
    border-color: rgba(26,167,130,.4) !important;
    color: #1aa782 !important;
}

/* Remove harsh table lines */
.fc-theme-standard td,
.fc-theme-standard th {
    border: none !important;
}

/* Day header row */
.fc .fc-col-header-cell {
    padding: 10px 0 !important;
}
.fc .fc-col-header-cell-cushion {
    color: #6b7280 !important;
    font-weight: 600 !important;
    font-size: 12px !important;
    text-transform: uppercase;
}

/* Day cell base */
.fc .fc-daygrid-day-frame {
    padding: 6px !important;
}
.fc .fc-daygrid-day-top {
    justify-content: center !important;
}
.fc .fc-daygrid-day-number {
    width: 34px;
    height: 34px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 13px;
    color: #374151 !important;
    margin: 0 auto !important;
    transition: .2s;
}

/* Hover effect */
.fc .fc-daygrid-day:hover .fc-daygrid-day-number {
    background: rgba(26,167,130,.12);
    color: #1aa782 !important;
}

/* Today highlight */
.fc .fc-day-today .fc-daygrid-day-number {
    background: rgba(26,167,130,.18);
    color: #1aa782 !important;
}

/* Selected day */
/* Selected day - FORCE white text */
.fc .fc-daygrid-day.selected-day .fc-daygrid-day-number{
    background:#1aa782 !important;
    color:#ffffff !important;
    font-weight:800;
}


/* Past date */
/* .fc .fc-daygrid-day.disabled-date .fc-daygrid-day-number {
    background: #f1f5f9 !important;
    color: #9ca3af !important;
} */

/* Status colors */
.fc .fc-daygrid-day.free-date .fc-daygrid-day-number {
    background: rgba(34,197,94,.14);
    color: #16a34a !important;
}
.fc .fc-daygrid-day.partially-booked-date .fc-daygrid-day-number {
    background: rgba(245,158,11,.18);
    color: #b45309 !important;
}
.fc .fc-daygrid-day.fully-booked-date .fc-daygrid-day-number {
    background: rgba(239,68,68,.18);
    color: #dc2626 !important;
}

/* Make calendar container look card-like */
.fc {
    padding: 8px 6px 14px;
}

/* Remove event dot line */
.fc-daygrid-event-dot {
    display: none !important;
}
    .booking-wrap{max-width:1280px;margin:0 auto;padding:24px;}
    .booking-icon{
        width:52px;height:52px;border-radius:14px;
        background:linear-gradient(135deg,#1aa782,#1fc29b);
        display:flex;align-items:center;justify-content:center;
        box-shadow:0 0 20px rgba(26,167,130,.2);
    }

    /* Shadcn Card */
    .shad-card{
        background:#fff;border:1px solid #eee;border-radius:16px;
        box-shadow:0 6px 18px rgba(0,0,0,.04);
        overflow:hidden;
    }
    .shad-head{
        padding:16px 20px;border-bottom:1px solid #f3f3f3;
        background:linear-gradient(180deg,#fff,#fafafa);
    }
    .shad-body{padding:20px;}

    /* inputs */
    .shad-label{font-weight:600;font-size:13px;margin-bottom:8px;display:block;}
    .shad-input{
        height:44px;border-radius:12px;
        border:1px solid #e8e8e8;
        background:#fff;
        padding:10px 12px;
    }
    .shad-input:focus{border-color:#1aa782;box-shadow:0 0 0 3px rgba(26,167,130,.12);}

    .shad-input-sm{
        height:36px;border-radius:10px;border:1px solid #e8e8e8;
    }
    .shad-input-sm1{
        height:26px;border-radius:6px;border:1px solid #e8e8e8;
    }

    /* Legend */
    .legend-box{background:#f8fafc;border-radius:14px;padding:14px;border:1px solid #eee;}
    .legend-title{font-weight:600;font-size:13px;margin-bottom:10px;color:#222;}
    .legend-grid{display:grid;grid-template-columns:1fr 1fr;gap:10px;font-size:12px;color:#6b7280;}
    .legend-color{width:16px;height:16px;border-radius:6px;display:inline-block;border:1px solid #ddd;}
    .legend-color.free{background:rgba(34,197,94,.15);border-color:rgba(34,197,94,.25)}
    .legend-color.partial{background:rgba(245,158,11,.18);border-color:rgba(245,158,11,.3)}
    .legend-color.full{background:rgba(239,68,68,.18);border-color:rgba(239,68,68,.3)}
    .legend-color.past{background:#f1f5f9;border-color:#e2e8f0}

    /* Fullcalendar day colors */
    /* .fc-daygrid-day.free-date { background:#dcfce7 !important; }
    .fc-daygrid-day.disabled-date { background:#f1f5f9 !important; cursor:not-allowed; opacity:.6; }
    .fc-daygrid-day.partially-booked-date { background:#fef3c7 !important; }
    .fc-daygrid-day.fully-booked-date { background:#fee2e2 !important; } */

    /* spot selection */
    .spot-box{padding:16px;border-radius:16px;border:2px solid #eee;transition:.2s;background:#fff;}
    .spot-box:hover{border-color:rgba(26,167,130,.4);box-shadow:0 10px 20px rgba(0,0,0,.05);}
    .spot-box.active{border-color:#1aa782;background:rgba(26,167,130,.05);box-shadow:0 0 20px rgba(26,167,130,.08);}
    .spot-box.disabled{opacity:.5;cursor:not-allowed;background:#f8fafc;}

    .package-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:10px;margin-top:12px;}
    .pkg-btn{
        border:2px solid #eee;border-radius:12px;padding:12px;text-align:center;
        background:linear-gradient(135deg,rgba(26,167,130,.05),rgba(26,167,130,.1));
    }
    .pkg-btn:hover{border-color:rgba(26,167,130,.4);}
    .pkg-btn.selected{
        background:#1aa782;color:#fff;border-color:#1aa782;
    }
    .pkg-price{font-size:12px;margin-top:6px;font-weight:700;color:#1aa782;}
    .pkg-btn.selected .pkg-price{color:rgba(255,255,255,.85);}

    /* selected packages */
    .selected-card{
        background:linear-gradient(135deg,rgba(26,167,130,.05),rgba(26,167,130,.1));
        border:1px solid rgba(26,167,130,.2);
        padding:16px;border-radius:16px;position:relative;
    }
    .remove-btn{
        position:absolute;top:10px;right:10px;
        border:none;background:transparent;font-size:16px;
        opacity:0;transition:.2s;color:#ef4444;
    }
    .selected-card:hover .remove-btn{opacity:1;}
    .total-row{display:flex;justify-content:space-between;align-items:center;border-top:1px solid #eee;padding-top:14px;}

    /* qty */
    .qty-wrap{display:flex;align-items:center;gap:6px;}
    .qty-btn{
        width:34px;height:34px;border-radius:10px;border:1px solid #eee;
        background:#fff;font-weight:800;cursor:pointer;
    }

    /* summary */
    .summary-box{
        padding:16px;border-radius:16px;background:#f8fafc;border:1px solid #eee;
    }
    .summary-line{display:flex;justify-content:space-between;font-size:13px;margin-bottom:10px;color:#6b7280;}
    .summary-total{
        border-top:1px solid #eee;padding-top:12px;margin-top:12px;
        display:flex;justify-content:space-between;align-items:center;
    }
   
    .percent-sign{position:absolute;right:14px;top:50%;transform:translateY(-50%);color:#888;font-weight:600;}

    /* save button */
    .shad-save-btn{
        background:linear-gradient(135deg,#1aa782,#1fc29b);
        color:white;border-radius:14px;font-size:16px;
        box-shadow:0 12px 20px rgba(26,167,130,.18);
        border:none;
    }
    .shad-save-btn:disabled{opacity:.5;cursor:not-allowed;}

    .spot-check{
    position:absolute;
    top:12px;
    right:12px;
    width:26px;
    height:26px;
    border-radius:50%;
    background:#1aa782;
    color:#fff;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:14px;
    font-weight:900;
    box-shadow:0 4px 10px rgba(26,167,130,.4);
}

.spot-box{
    position:relative;
}

.spot-box.has-package{
    border-color:#1aa782;
    background:rgba(26,167,130,.05);
}
/* =============================
   FORCE selected date text WHITE
============================= */

/* number link */
.fc .fc-daygrid-day.selected-day .fc-daygrid-day-number,
.fc .fc-daygrid-day.selected-day .fc-daygrid-day-number a{
    background:#1aa782 !important;
    color:#ffffff !important;
    font-weight:800 !important;
}

/* hover হলেও white থাকবে */
.fc .fc-daygrid-day.selected-day:hover .fc-daygrid-day-number,
.fc .fc-daygrid-day.selected-day:hover .fc-daygrid-day-number a{
    background:#1aa782 !important;
    color:#ffffff !important;
}

/* today + selected conflict fix */
.fc .fc-day-today.selected-day .fc-daygrid-day-number,
.fc .fc-day-today.selected-day .fc-daygrid-day-number a{
    background:#1aa782 !important;
    color:#ffffff !important;
}

</style>

{{-- Fullcalendar --}}
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>

{{-- ================= SCRIPT LOGIC ================= --}}

<script>
/* ================= GLOBAL ================= */
let selectedSpots = [];
let tickedPackage = null;
let totalCapacity = 0;
let serviceTotal = 0;
let discountPercent = 0;
let manualDiscountAmount = 0;
let useManualDiscount = false;

function round2(num){
    return Math.round((Number(num) + Number.EPSILON) * 100) / 100;
}

function getSpotDiscountPercent(){
    const spotCount = selectedSpots.length;
    if (spotCount <= 1) return 0;
    const effectiveSpotCount = Math.min(spotCount, 4); // cap at 4
    return effectiveSpotCount * MULTIPLE_SPOT_DISCOUNT;
}

function formatMoney(num){
    return (num || 0).toLocaleString();
}

/* ================= CALENDAR ================= */
document.addEventListener('DOMContentLoaded', function(){

    // ===== INITIALIZE CALENDAR =====
    const calendar = new FullCalendar.Calendar(
        document.getElementById('spot_booking_calendar'), {
        initialView:'dayGridMonth',
        height:480,

        dayCellClassNames(arg){
            const d = arg.date;
            const today = new Date(); today.setHours(0,0,0,0);
            const dateStr = d.getFullYear() + '-' +
                            String(d.getMonth() + 1).padStart(2, '0') + '-' +
                            String(d.getDate()).padStart(2, '0');

            if(bookings[dateStr]){
                const total = spots.length;
                const booked = bookings[dateStr].booked;
                if(booked >= total) return ['fully-booked-date'];
                if(booked > 0) return ['partially-booked-date'];
            }
            return ['free-date'];
        },

        dateClick(info){
            const d = info.date;
            document.querySelectorAll('.fc-daygrid-day')
                .forEach(x=>x.classList.remove('selected-day'));
            info.dayEl.classList.add('selected-day');

            const dateStr = d.getFullYear() + '-' +
                            String(d.getMonth() + 1).padStart(2, '0') + '-' +
                            String(d.getDate()).padStart(2, '0');

            document.getElementById('booking_date').value = dateStr;
            document.getElementById('selected_date_text').innerText = `(${dateStr})`;

            renderSpots(dateStr);
        }
    });

    calendar.render();

    // ===== OTHER INIT =====
    bindTopValidation();
    initServiceEvents();
    initDiscountEvents();
    initToggleServices();
    initManualDiscountEvents();
        // === RESTORE SELECTED SPOTS FOR EDIT MODE ===

        // === RESTORE SELECTED SPOTS FOR EDIT MODE ===
        if(currentInvoiceSpots.length > 0){
            currentInvoiceSpots.forEach(spotId => {
                const spot = spots.find(s => parseInt(s.id) === parseInt(spotId));
                if(spot && !selectedSpots.find(s => s.id === spot.id)){
                    selectedSpots.push({
                        id: spot.id,
                        title: spot.title,
                        price: parseFloat(spot.price),
                        max_capacity: parseInt(spot.max_capacity)
                    });
                }
            });
        }

        // এখন call renderSelected & renderSpotsButtons
        renderSelected();
        renderSpotsButtons();
        if(typeof currentInvoiceSpots !== 'undefined' && currentInvoiceSpots.length>0){
            currentInvoiceSpots.forEach(spotId=>{
                const spot = spots.find(s=>parseInt(s.id) === parseInt(spotId));
                if(spot && !selectedSpots.find(s=>s.id === spot.id)){
                    selectedSpots.push({
                        id: spot.id,
                        title: spot.title,
                        price: parseFloat(spot.price),
                        max_capacity: parseInt(spot.max_capacity)
                    });
                }
            });
        }

       // তারপর renderSelected & renderSpotsButtons কল দিন
            renderSelected();
            renderSpotsButtons();
                // ===== EDIT MODE =====
                if(typeof editBooking !== 'undefined' && editBooking){

                    const bookingDateStr = editBooking.booking_date;

                    if(bookingDateStr){
                        // Set hidden input & text
                        document.getElementById('booking_date').value = bookingDateStr;
                        document.getElementById('selected_date_text').innerText = `(${bookingDateStr})`;

                        // Highlight the calendar day
                        const dayCells = document.querySelectorAll('.fc-daygrid-day');
                        dayCells.forEach(cell => {
                            if(cell.dataset.date === bookingDateStr){
                                cell.classList.add('selected-day');
                            }
                        });
                    }

                    // Restore selected spots FIRST (important!)
                    editItems.forEach(item=>{
                    if(item.type === 'spot'){
                        const spot = spots.find(s=>parseInt(s.id) === parseInt(item.id)); // ✅
                        if(spot && !selectedSpots.find(s=>s.id===parseInt(spot.id))){
                            selectedSpots.push({
                                id: parseInt(spot.id),       // ✅ ensure number
                                title: spot.title,
                                price: parseFloat(spot.price),
                                max_capacity: parseInt(spot.max_capacity)
                            });
                        }
                    }
                });
recalcCapacity();
        // THEN render spots for that date
        if(bookingDateStr) renderSpots(bookingDateStr);

        // Force card visible
        const card = document.getElementById('selectedPackagesCard');
        if(selectedSpots.length > 0 && card){
            card.classList.remove('d-none');
        }

        // Restore package
        const pkg = editItems.find(i=>i.type === 'package');
        if(pkg) tickedPackage = {id: pkg.id};

        // Restore services
        editItems.forEach(item=>{
            if(item.type === 'service'){
                document.querySelectorAll('.service-row').forEach(row=>{
                    const chk = row.querySelector('.service-check');
                    if(chk.dataset.id == item.service_id){
                        chk.checked = true;
                        row.querySelector('.price').value = item.price;
                        row.querySelector('.qty').value = item.qty;
                    }
                });
            }
        });

        renderPackagesByCapacity();
        updateServices();
        updateSummary();
    }

});
/* ================= SPOTS ================= */
function renderSpots(dateStr){
    const box = document.getElementById('spot_boxes');
    box.innerHTML = '';

    const totalSpots  = spots.length;
    const bookedCount = bookings[dateStr]?.booked || 0;
    const isFullyBooked = bookedCount >= totalSpots;

    // booked spot ids
    const bookedSpotIds = Array.isArray(bookedSpots[dateStr])
        ? bookedSpots[dateStr].map(id => parseInt(id))
        : [];

    // selected spot ids
    const selectedSpotIds = selectedSpots.map(s => parseInt(s.id));
const currentInvoiceSpots = @json($currentInvoiceSpots); // blade e pass করা হয়েছে
spots.forEach(spot=>{
    const id = parseInt(spot.id);
    const isSelected = selectedSpots.some(s=>parseInt(s.id) === id)
        || currentInvoiceSpots.includes(id); // 🔥 add this

    const isBookedSpot =
        isFullyBooked ||
        (bookedSpotIds.includes(id) && !isSelected);

    box.insertAdjacentHTML('beforeend', `
        <div class="col-4">
            <button type="button"
                class="pkg-btn spot-btn w-100 text-start ${isBookedSpot ? 'disabled' : ''}"
                data-id="${id}">
                <span class="spot-check ${isSelected ? '' : 'd-none'}">✔</span>
                <div class="fw-bold">${spot.title}</div>
                <div class="fw-bold">৳ ${spot.price}</div>
            </button>
        </div>
    `);
});
    document.querySelectorAll('.spot-btn').forEach(btn=>{
        const id = parseInt(btn.dataset.id);

        if(selectedSpots.find(s=>s.id === id)){
            btn.classList.add('selected');
            btn.querySelector('.spot-check').classList.remove('d-none');
        }

        btn.onclick = () => {
            if(btn.classList.contains('disabled')) return;

            const spot = spots.find(s => s.id === id);

            if(btn.classList.contains('selected')){
                btn.classList.remove('selected');
                btn.querySelector('.spot-check').classList.add('d-none');
                removeSpot(spot.id);
            }else{
                btn.classList.add('selected');
                btn.querySelector('.spot-check').classList.remove('d-none');
                addSpot(spot);
            }
        };
    });
}

/* ================= SPOT HANDLING ================= */
function addSpot(spot){
    if(selectedSpots.find(s=>s.id===spot.id)) return;

    selectedSpots.push({
        id: spot.id,
        title: spot.title,
        price: parseFloat(spot.price),
        max_capacity: parseInt(spot.max_capacity)
    });

    recalcCapacity();
    renderPackagesByCapacity();
    renderSelected();
    renderSpotsButtons();
}

function removeSpot(spotId){
    selectedSpots = selectedSpots.filter(s=>s.id!==spotId);
    recalcCapacity();
    validateTickedPackage();
    renderPackagesByCapacity();
    renderSelected();
    renderSpotsButtons();
}

function renderSpotsButtons(){
    document.querySelectorAll('.spot-btn').forEach(btn=>{
        const id = parseInt(btn.dataset.id);
        const isSelected = selectedSpots.find(s=>s.id===id);

        if(isSelected){
            btn.classList.add('selected');
            btn.querySelector('.spot-check').classList.remove('d-none');
        }else{
            btn.classList.remove('selected');
            btn.querySelector('.spot-check').classList.add('d-none');
        }
    });
}

function recalcCapacity(){
    totalCapacity = selectedSpots.reduce((sum,s)=>sum+s.max_capacity,0);
}
/* ================= PACKAGES ================= */
function renderPackagesByCapacity(){
    const grid = document.getElementById('spot_packages_grid');
    grid.innerHTML = '';

    let validPackages = packages.filter(p => parseInt(p.persons) <= totalCapacity);

    // ===== EDIT MODE OVERRIDE =====
    if(typeof editBooking !== 'undefined' && editBooking){
        const pkg = editItems.find(i => i.type === 'package');
        if(pkg && !validPackages.find(p => p.id === pkg.id)){
            const pkgObj = packages.find(p => p.id === pkg.id);
            if(pkgObj) validPackages.push(pkgObj); // ✅ force add
        }
    }

    if(validPackages.length === 0){
        grid.innerHTML = `<div class="text-muted text-center py-5">Select spot(s) to see packages</div>`;
        return;
    }

    validPackages.forEach(pkg => {
        const selected = tickedPackage?.id === pkg.id;
        grid.insertAdjacentHTML('beforeend', `
            <div class="col-md-2">
                <div class="pkg-btn ${selected?'selected':''}" onclick="selectPackage(${pkg.id})">
                    <div class="fw-bold">${pkg.persons} Persons</div>
                    <div>৳ ${formatMoney(pkg.price)}</div>
                    <span class="spot-check" style="${selected?'':'display:none'}">✔</span>
                </div>
            </div>
        `);
    });

    document.getElementById('spotTitleText').innerText =
        selectedSpots.map(s=>s.title).join(', ') + ` (Capacity: ${totalCapacity})`;
}

function selectPackage(pkgId){
    tickedPackage = (tickedPackage?.id === pkgId) ? null : {id:pkgId};
    renderPackagesByCapacity();
    renderSelected();
}

function validateTickedPackage(){
    if(!tickedPackage) return;
    const pkg = packages.find(p=>p.id===tickedPackage.id);
    if(!pkg || pkg.persons > totalCapacity){
        tickedPackage = null;
    }
}


function renderSelected(){
    const grid = document.getElementById('selected_grid');
    const card = document.getElementById('selectedPackagesCard');
    grid.innerHTML = '';

    let rawSpotTotal = 0;

    selectedSpots.forEach(s=>{
        rawSpotTotal += s.price;
        grid.insertAdjacentHTML('beforeend', `
            <div class="col-md-2">
                <div class="selected-card">
                    <button type="button" class="remove-btn" onclick="removeSpot(${s.id})">✕</button>
                    <div class="fw-bold">${s.title}</div>
                    <div>৳ ${formatMoney(s.price)}</div>
                </div>
            </div>
        `);
    });

    card.classList.toggle('d-none', selectedSpots.length===0);

    // ===== SPOT DISCOUNT =====
    const spotDiscount = getSpotDiscountPercent();
    const discountedSpot = round2(rawSpotTotal - (rawSpotTotal * spotDiscount / 100));

    document.getElementById('spotTotal').innerText = formatMoney(discountedSpot);

    // 🔥 VERY IMPORTANT
    document.getElementById('spot_discount_percent').value = spotDiscount;

    updateServices();
    updateSummary();
    updateItems();
    bindTopValidation();
}

/* ================= SERVICES ================= */
function updateServices(){
    serviceTotal = 0;
    let services = [];

    document.querySelectorAll('.service-row').forEach(row=>{
        const chk = row.querySelector('.service-check');
        const priceInput = row.querySelector('.price');
        const qtyInput = row.querySelector('.qty');
        if(!chk || !priceInput || !qtyInput) return;

        const price = parseFloat(priceInput.value || 0);
        const qty = parseInt(qtyInput.value || 1);
        const total = price * qty;

        const totalEl = row.querySelector('.row-total');
        if(totalEl) totalEl.innerText = formatMoney(total);

        if(chk.checked){
            serviceTotal += total;
            services.push({
                service_id: chk.dataset.id || null,
                title: chk.dataset.title || chk.value || 'Service',
                price, qty, total
            });
        }
    });

    document.getElementById('serviceTotalDisplay').innerText = formatMoney(serviceTotal);
    document.getElementById('additional_services').value = JSON.stringify(services);

    updateSummary();
}

/* ================= UPDATE ITEMS (SPOTS + PACKAGE + SERVICES) ================= */
function updateItems(){
    const itemsArr = [];

    // Spots
    selectedSpots.forEach(s=>{
        itemsArr.push({
            id: s.id,
            title: s.title,
            price: s.price,
            max_capacity: s.max_capacity,
            type: 'spot'
        });
    });

    // Package
    if(tickedPackage){
        const pkg = packages.find(p=>p.id==tickedPackage.id);
        if(pkg){
            itemsArr.push({
                id: pkg.id,
                title: `${pkg.persons} Persons Package`,
                price: parseFloat(pkg.price),
                persons: pkg.persons,
                type: 'package',
                spot_id: selectedSpots.length === 1 ? selectedSpots[0].id : null
            });
        }
    }

    // Services
    document.querySelectorAll('.service-row').forEach(row=>{
        const chk = row.querySelector('.service-check');
        if(chk && chk.checked){
            const price = parseFloat(row.querySelector('.price').value || 0);
            const qty = parseInt(row.querySelector('.qty').value || 1);
            itemsArr.push({
                service_id: chk.dataset.id,
                title: chk.dataset.title || chk.value || 'Service',
                price, qty, total: price*qty,
                type: 'service'
            });
        }
    });

    document.getElementById('items').value = JSON.stringify(itemsArr);
}

/* ================= SUMMARY ================= */

function updateSummary(){
    const rawSpot = selectedSpots.reduce((s,x)=>s+x.price,0);
    const spotDisc = getSpotDiscountPercent();
    const spotTotal = round2(rawSpot - (rawSpot * spotDisc / 100));

    const packageTotal = tickedPackage
        ? parseFloat(packages.find(p=>p.id===tickedPackage.id).price)
        : 0;

    const subtotal = spotTotal + packageTotal + serviceTotal;
     const pact =packageTotal;
const percentDiscountAmount = round2(
    subtotal * discountPercent / 100
);

const invoiceDiscount = useManualDiscount ? round2(manualDiscountAmount) : 0;

const totalDiscount = round2(percentDiscountAmount + invoiceDiscount);
const afterDiscountAmount = round2(subtotal - percentDiscountAmount);
const grandTotal = round2(subtotal - totalDiscount);




    // ===== UI UPDATE =====
    document.getElementById('summarySpot').innerText = formatMoney(spotTotal);
    document.getElementById('summaryPackage').innerText = formatMoney(packageTotal);
    document.getElementById('Packaget').innerText = formatMoney(pact);
    document.getElementById('summaryService').innerText = formatMoney(serviceTotal);

    // % label update
    document.getElementById('discountLabel').innerText = discountPercent;

    // % discount amount (first line)
    document.getElementById('discountAmountText').innerText =
        formatMoney(percentDiscountAmount);

    // invoice adjustment line
    document.getElementById('invoiceAdjustmentAmount').innerText =
    formatMoney(manualDiscountAmount);


    // After discount line
document.getElementById('afterDiscountAmount').innerText =
    formatMoney(afterDiscountAmount);

// hidden inputs (REQUEST)
// invoice adjustment discount (REQUEST)
document.getElementById('invoice_adjustment_discount').value =
    invoiceDiscount.toFixed(2);


// discount_amount already contains TOTAL discount (percent + invoice)
document.getElementById('discount_amount').value =
    totalDiscount.toFixed(2);

    document.getElementById('grandTotal1').innerText = formatMoney(subtotal);
    document.getElementById('grandTotal').innerText = formatMoney(grandTotal);
    document.getElementById('total_price').value = grandTotal.toFixed(2);

    validateForm();
}

/* ================= DISCOUNT ================= */
function initDiscountEvents(){
    const discountInput = document.getElementById('discountPercent');
    const invoiceRow = document.getElementById('invoiceAdjustmentRow');

    if(!discountInput) return;

    discountInput.addEventListener('input', function(){
        let val = parseFloat(this.value || 0);
        val = Math.min(Math.max(val,0), MAX_DISCOUNT);

        discountPercent = val;
        this.value = val;
        document.getElementById('discount_percent').value = val;

        // invoice row visibility rule
        if(val > 0){
            invoiceRow.classList.remove('d-none');
        }else{
            invoiceRow.classList.add('d-none');
            manualDiscountAmount = 0;
            useManualDiscount = false;
            document.getElementById('discountAmountInput').value = '';
        }

        updateSummary();
    });
}



/* ================= TOGGLE SERVICES ================= */
function initToggleServices(){
    const toggle = document.getElementById('toggleServices');
    const card = document.getElementById('additionalServicesCard');
    if(!toggle || !card) return;

    toggle.addEventListener('change', function(){
        if(this.checked){
            card.classList.remove('d-none');
        }else{
            card.classList.add('d-none');
            document.querySelectorAll('.service-check').forEach(c=>c.checked=false);
            serviceTotal = 0;
            document.getElementById('additional_services').value = '[]';
            updateServices();
        }
    });
}

/* ================= SERVICE EVENTS ================= */
function initServiceEvents(){
    document.addEventListener('change', function(e){
        if(e.target.matches('.service-check')) updateServices();
    });
    document.addEventListener('input', function(e){
        if(e.target.matches('.price,.qty')) updateServices();
    });
}

/* ================= QUANTITY BUTTONS ================= */
document.addEventListener('click', function(e){
    if(e.target.classList.contains('qty-btn')){
        const row = e.target.closest('.service-row');
        if(!row) return;

        const input = row.querySelector('.qty');
        if(!input) return;

        let val = parseInt(input.value || 1);
        if(e.target.classList.contains('plus')) val++;
        if(e.target.classList.contains('minus')) val = Math.max(1, val-1);

        input.value = val;
        updateServices();
    }
});

/* ================= FORM VALIDATION ================= */
function validateForm(){
    const customer = document.getElementById('changeCustomer');
    const account  = document.getElementById('changeAccountsName');
    const amount   = document.querySelector('input[name="receive_amount"]');
    const date     = document.getElementById('booking_date');
    const saveBtn  = document.getElementById('saveBookingBtn');

    if(!customer || !account || !amount || !date || !saveBtn) return;

    // selectedSpots.length > 0 condition remove করা হয়েছে
    const ok = customer.value && account.value && amount.value && date.value;
    saveBtn.disabled = !ok;
}

function bindTopValidation(){
    const customer = document.getElementById('changeCustomer');
    const account  = document.getElementById('changeAccountsName');
    const amount   = document.querySelector('input[name="receive_amount"]');
    const date     = document.getElementById('booking_date');

    if(customer) customer.addEventListener('input', validateForm);
    if(account) account.addEventListener('input', validateForm);
    if(amount) amount.addEventListener('input', validateForm);
    if(date) date.addEventListener('input', validateForm);

    validateForm();
}
/* ================= MANUAL DISCOUNT ================= */
function initManualDiscountEvents(){
    const text = document.getElementById('invoiceAdjustmentAmount');
    const input = document.getElementById('discountAmountInput');
    const row = document.getElementById('invoiceAdjustmentRow');

    if(!text || !input || !row) return;

    // click to edit
    text.addEventListener('click', ()=>{
        row.classList.remove('d-none');
        text.classList.add('d-none');

        input.disabled = false;        // ✅ enable
        input.classList.remove('d-none');
        input.focus();
    });

    // typing
    input.addEventListener('input', ()=>{
        let val = parseFloat(input.value || 0);
        if(val < 0) val = 0;

        manualDiscountAmount = val;
        useManualDiscount = val > 0;

        updateSummary();
    });

    // blur
    input.addEventListener('blur', ()=>{
        input.classList.add('d-none');
        input.disabled = true;         // ✅ disable again
        text.classList.remove('d-none');

        if(manualDiscountAmount <= 0){
            useManualDiscount = false;
            row.classList.add('d-none');
        }

        updateSummary();
    });
}




document.querySelectorAll('form').forEach(form=>{
    form.addEventListener('submit', function () {
         updateSummary();
        const el = document.getElementById('spot_discount_percent');
        if(el){
            el.value = getSpotDiscountPercent();
        }
    });
});

</script>



</x-default-layout>
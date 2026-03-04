<x-default-layout>
<div class="container">
    <h2>Create Proposal</h2>

    <form method="POST" action="{{ route('proposals.store') }}" id="proposalForm">
        @csrf

        {{-- CLIENT INFO --}}
        <div class="card mb-3">
            <div class="card-body">
                <h5>Client Info</h5>

                <div class="row">
                    <!-- <div class="col-md-6">
                        <label class="form-label">Client Name*</label>
                        <input class="form-control" name="client_name" value="{{ old('client_name') }}" required>
                        @error('client_name') <small class="text-danger">{{ $message }}</small> @enderror
                    </div> -->

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
        name="client_id" 
        required
        data-control="select2"
        data-placeholder="Select Customer">
    <option></option>
    @foreach ($customers as $c)
        <option value="{{ $c->id }}"> <!-- এখানে name বা title নয়, ID দিতে হবে -->
            {{ $c->customer_name }}
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
                    <div class="col-md-6">
                        <label class="form-label">Proposal Title</label>
                        <input class="form-control" name="proposal_title" value="{{ old('proposal_title') }}">
                        @error('proposal_title') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                </div>

                <!-- <div class="row mt-2">
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input class="form-control" name="client_email" value="{{ old('client_email') }}">
                        @error('client_email') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Phone</label>
                        <input class="form-control" name="client_phone" value="{{ old('client_phone') }}">
                        @error('client_phone') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                </div> -->

                <div class="mt-3">
                    <label class="form-label">Intro Text</label>
                    <textarea class="form-control" name="intro_text" rows="4">{{ old('intro_text', $defaultIntro) }}</textarea>
                    @error('intro_text') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="mt-3">
                    <label class="form-label">Terms & Conditions</label>
                    <textarea class="form-control" name="terms_text" rows="4">{{ old('terms_text', $defaultTerms) }}</textarea>
                    @error('terms_text') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="mt-3">
                    <label class="form-label">Notes</label>
                    <textarea class="form-control" name="notes_text" rows="3">{{ old('notes_text') }}</textarea>
                    @error('notes_text') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
            </div>
        </div>

        {{-- =======================
            ROOM SEARCH + SELECTED
        ======================= --}}
        <div class="card mb-3">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">Rooms</h5>
                    <input type="text" class="form-control" id="roomSearch" placeholder="Search room by name/number..." style="max-width: 320px;">
                </div>

                <div class="mt-2 small text-muted">Search then click “Add”. Selected rooms will appear below.</div>

                <div class="table-responsive mt-3">
                    <table class="table table-sm table-striped align-middle" id="roomListTable">
                        <thead>
                        <tr>
                            <th>Room</th>
                            <th class="text-end">Price/Night</th>
                            <th class="text-end">Capacity</th>
                            <th class="text-end">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($rooms as $r)
                            <tr class="room-row"
                                data-id="{{ $r->id }}"
                                data-name="{{ strtolower(($r->room_name ?? '') . ' ' . ($r->room_number ?? '')) }}"
                                data-title="{{ $r->room_name ?? ('Room #'.$r->room_number) }}"
                                data-price="{{ $r->price_per_night }}"
                                data-capacity="{{ $r->capacity }}"
                                data-number="{{ $r->room_number }}"
                            >
                                <td>
                                    <strong>{{ $r->room_name ?? ('Room #'.$r->room_number) }}</strong>
                                    <div class="text-muted small">Room No: {{ $r->room_number }}</div>
                                </td>
                                <td class="text-end">৳{{ number_format($r->price_per_night, 2) }}</td>
                                <td class="text-end">{{ $r->capacity }}</td>
                                <td class="text-end">
                                    <button type="button" class="btn btn-sm btn-outline-primary add-room">Add</button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <hr>

                <h6 class="mb-2">Selected Rooms</h6>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered align-middle" id="selectedRoomsTable">
                        <thead>
                        <tr>
                            <th>Room</th>
                            <th class="text-end" style="width:90px;">Qty</th>
                            <th class="text-end" style="width:110px;">Nights</th>
                            <th class="text-end" style="width:140px;">Unit Price</th>
                            <th class="text-end" style="width:130px;">Line Total</th>
                            <th class="text-end" style="width:90px;">Remove</th>
                        </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>

                @error('rooms') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
        </div>

        {{-- =======================
            SPOT SEARCH + SELECTED
        ======================= --}}
        <div class="card mb-3">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">Spots</h5>
                    <input type="text" class="form-control" id="spotSearch" placeholder="Search spot..." style="max-width: 320px;">
                </div>

                <div class="mt-2 small text-muted">Search then click “Add”. Selected spots will appear below.</div>

                <div class="table-responsive mt-3">
                    <table class="table table-sm table-striped align-middle" id="spotListTable">
                        <thead>
                        <tr>
                            <th>Spot</th>
                            <th class="text-end">Price</th>
                            <th class="text-end">Capacity</th>
                            <th class="text-end">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($spots as $s)
                            <tr class="spot-row"
                                data-id="{{ $s->id }}"
                                data-name="{{ strtolower($s->title ?? '') }}"
                                data-title="{{ $s->title }}"
                                data-price="{{ $s->price }}"
                                data-capacity="{{ $s->max_capacity }}"
                            >
                                <td>
                                    <strong>{{ $s->title }}</strong>
                                    <div class="text-muted small">Cap: {{ $s->max_capacity }} | Area: {{ $s->area_size }}</div>
                                </td>
                                <td class="text-end">৳{{ number_format($s->price, 2) }}</td>
                                <td class="text-end">{{ $s->max_capacity }}</td>
                                <td class="text-end">
                                    <button type="button" class="btn btn-sm btn-outline-primary add-spot">Add</button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <hr>

                <h6 class="mb-2">Selected Spots</h6>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered align-middle" id="selectedSpotsTable">
                        <thead>
                        <tr>
                            <th>Spot</th>
                            <th class="text-end" style="width:90px;">Qty</th>
                            <th class="text-end" style="width:140px;">Unit Price</th>
                            <th class="text-end" style="width:130px;">Line Total</th>
                            <th class="text-end" style="width:90px;">Remove</th>
                        </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>

                @error('spots') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
        </div>

        {{-- =======================
            PACKAGE SEARCH + SINGLE SELECTED
        ======================= --}}
        <div class="card mb-3">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">Package (Only One Select)</h5>
                    <input type="text" class="form-control" id="packageSearch" placeholder="Search package..." style="max-width: 320px;">
                </div>

                <div class="mt-2 small text-muted">Search then click “Select”. Selecting another package will replace the previous one.</div>

                <div class="table-responsive mt-3">
                    <table class="table table-sm table-striped align-middle" id="packageListTable">
                        <thead>
                        <tr>
                            <th>Package</th>
                            <th class="text-end">Persons</th>
                            <th class="text-end">Price</th>
                            <th class="text-end">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($packages as $p)
                            <tr class="package-row"
                                data-id="{{ $p->id }}"
                                data-name="{{ strtolower($p->name ?? '') }}"
                                data-title="{{ $p->name }}"
                                data-persons="{{ $p->persons }}"
                                data-price="{{ $p->price }}"
                            >
                                <td>
                                    <strong>{{ $p->name }}</strong>
                                    <div class="text-muted small">Persons: {{ $p->persons }}</div>
                                </td>
                                <td class="text-end">{{ $p->persons }}</td>
                                <td class="text-end">৳{{ number_format($p->price, 2) }}</td>
                                <td class="text-end">
                                    <button type="button" class="btn btn-sm btn-primary select-package">Select</button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <hr>

                <h6 class="mb-2">Selected Package</h6>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered align-middle" id="selectedPackagesTable">
                        <thead>
                        <tr>
                            <th>Package</th>
                            <th class="text-end" style="width:90px;">Qty</th>
                            <th class="text-end" style="width:140px;">Unit Price</th>
                            <th class="text-end" style="width:130px;">Line Total</th>
                            <th class="text-end" style="width:90px;">Remove</th>
                        </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>

                @error('packages') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
        </div>

        {{-- =======================
            ADDITIONAL SERVICES SEARCH + SELECTED
        ======================= --}}
        <div class="card mb-3">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">Additional Services</h5>
                    <input type="text" class="form-control" id="serviceSearch" placeholder="Search service..." style="max-width: 320px;">
                </div>

                <div class="mt-2 small text-muted">Search then click “Add”. Selected services will appear below.</div>

                <div class="table-responsive mt-3">
                    <table class="table table-sm table-striped align-middle" id="serviceListTable">
                        <thead>
                        <tr>
                            <th>Service</th>
                            <th class="text-end">Price</th>
                            <th class="text-end">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($services as $sv)
                            <tr class="service-row"
                                data-id="{{ $sv->id }}"
                                data-name="{{ strtolower($sv->title ?? '') }}"
                                data-title="{{ $sv->title }}"
                                data-price="{{ $sv->price }}"
                                data-desc="{{ e($sv->description ?? '') }}"
                            >
                                <td>
                                    <strong>{{ $sv->title }}</strong>
                                    @if($sv->description)
                                        <div class="text-muted small">{{ $sv->description }}</div>
                                    @endif
                                </td>
                                <td class="text-end">৳{{ number_format($sv->price, 2) }}</td>
                                <td class="text-end">
                                    <button type="button" class="btn btn-sm btn-outline-primary add-service">Add</button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <hr>

                <h6 class="mb-2">Selected Services</h6>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered align-middle" id="selectedServicesTable">
                        <thead>
                        <tr>
                            <th>Service</th>
                            <th class="text-end" style="width:90px;">Qty</th>
                            <th class="text-end" style="width:140px;">Unit Price</th>
                            <th class="text-end" style="width:130px;">Line Total</th>
                            <th class="text-end" style="width:90px;">Remove</th>
                        </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>

                @error('services') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
        </div>

        {{-- FACILITIES --}}
        <!-- <div class="card mb-3">
            <div class="card-body">
                <h5>Common Facilities (optional)</h5>
                <div class="row">
                    @foreach($facilities as $f)
                        <div class="col-md-4 mb-2">
                            <label class="d-flex align-items-center gap-2">
                                <input type="checkbox" name="facilities[]" value="{{ $f->id }}">
                                {{ $f->facility_name }}
                            </label>
                        </div>
                    @endforeach
                </div>
                @error('facilities') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
        </div> -->

        {{-- PRICE ADJUSTMENTS --}}
        <div class="card mb-3">
            <div class="card-body">
                <h5>Pricing Adjustments</h5>
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label">Discount</label>
                        <input type="number" class="form-control" name="discount" value="{{ old('discount', 0) }}" min="0" step="0.01">
                        @error('discount') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tax</label>
                        <input type="number" class="form-control" name="tax" value="{{ old('tax', 0) }}" min="0" step="0.01">
                        @error('tax') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                </div>
            </div>
        </div>

        <button class="btn btn-primary">Generate Proposal</button>
    </form>
</div>
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
                            <button type="submit" id="new_card_submit" class="btn btn-sm btn-primary">
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
<script>
(function() {
    const selected = {
        rooms: new Map(),
        spots: new Map(),
        packages: new Map(), // single select
        services: new Map(),
    };

    function money(n){ return Number(n || 0).toFixed(2); }

    function filterTable(inputId, rowClass, attr) {
        const q = (document.getElementById(inputId).value || '').toLowerCase().trim();
        document.querySelectorAll('.' + rowClass).forEach(row => {
            const hay = (row.getAttribute(attr) || '');
            row.style.display = hay.includes(q) ? '' : 'none';
        });
    }

    document.getElementById('roomSearch')?.addEventListener('input', () => filterTable('roomSearch','room-row','data-name'));
    document.getElementById('spotSearch')?.addEventListener('input', () => filterTable('spotSearch','spot-row','data-name'));
    document.getElementById('packageSearch')?.addEventListener('input', () => filterTable('packageSearch','package-row','data-name'));
    document.getElementById('serviceSearch')?.addEventListener('input', () => filterTable('serviceSearch','service-row','data-name'));

    function upsertSelectedRow(type, data) {
        const tableBody = document.querySelector(type.tableBodySel);
        const key = data.id.toString();

        if (type.map.has(key)) return;
        type.map.set(key, data);

        const tr = document.createElement('tr');
        tr.setAttribute('data-id', key);
        tr.innerHTML = type.renderRow(data);
        tableBody.appendChild(tr);

        tr.querySelectorAll('input').forEach(inp => {
            inp.addEventListener('input', () => type.recalcRow(tr));
        });

        tr.querySelector('.remove-btn').addEventListener('click', () => {
            type.map.delete(key);
            tr.remove();
        });

        type.recalcRow(tr);
    }

    const ROOM = {
        map: selected.rooms,
        tableBodySel: '#selectedRoomsTable tbody',
        renderRow: (d) => `
            <td>
                <strong>${d.title}</strong>
                <div class="text-muted small">Room No: ${d.number} | Cap: ${d.capacity}</div>
                <input type="hidden" name="rooms[]" value="${d.id}">
            </td>
            <td class="text-end">
                <input type="number" class="form-control form-control-sm text-end" name="room_qty[${d.id}]" value="1" min="1">
            </td>
            <td class="text-end">
                <input type="number" class="form-control form-control-sm text-end" name="room_nights[${d.id}]" value="1" min="1">
            </td>
            <td class="text-end">
                <input type="number" class="form-control form-control-sm text-end" name="room_price[${d.id}]" value="${money(d.price)}" min="0" step="0.01">
            </td>
            <td class="text-end line-total">৳0.00</td>
            <td class="text-end">
                <button type="button" class="btn btn-sm btn-outline-danger remove-btn">Remove</button>
            </td>
        `,
        recalcRow: (tr) => {
            const id = tr.getAttribute('data-id');
            const qty = Number(tr.querySelector(`[name="room_qty[${id}]"]`).value || 1);
            const nights = Number(tr.querySelector(`[name="room_nights[${id}]"]`).value || 1);
            const price = Number(tr.querySelector(`[name="room_price[${id}]"]`).value || 0);
            tr.querySelector('.line-total').innerText = '৳' + money(qty * nights * price);
        }
    };

    const SPOT = {
        map: selected.spots,
        tableBodySel: '#selectedSpotsTable tbody',
        renderRow: (d) => `
            <td>
                <strong>${d.title}</strong>
                <div class="text-muted small">Cap: ${d.capacity}</div>
                <input type="hidden" name="spots[]" value="${d.id}">
            </td>
            <td class="text-end">
                <input type="number" class="form-control form-control-sm text-end" name="spot_qty[${d.id}]" value="1" min="1">
            </td>
            <td class="text-end">
                <input type="number" class="form-control form-control-sm text-end" name="spot_price[${d.id}]" value="${money(d.price)}" min="0" step="0.01">
            </td>
            <td class="text-end line-total">৳0.00</td>
            <td class="text-end">
                <button type="button" class="btn btn-sm btn-outline-danger remove-btn">Remove</button>
            </td>
        `,
        recalcRow: (tr) => {
            const id = tr.getAttribute('data-id');
            const qty = Number(tr.querySelector(`[name="spot_qty[${id}]"]`).value || 1);
            const price = Number(tr.querySelector(`[name="spot_price[${id}]"]`).value || 0);
            tr.querySelector('.line-total').innerText = '৳' + money(qty * price);
        }
    };

    const PACKAGE = {
        map: selected.packages,
        tableBodySel: '#selectedPackagesTable tbody',
        renderRow: (d) => `
            <td>
                <strong>${d.title}</strong>
                <div class="text-muted small">Persons: ${d.persons}</div>
                <input type="hidden" name="packages[]" value="${d.id}">
            </td>
            <td class="text-end">
                <input type="number" class="form-control form-control-sm text-end" name="package_qty[${d.id}]" value="1" min="1">
            </td>
            <td class="text-end">
                <input type="number" class="form-control form-control-sm text-end" name="package_price[${d.id}]" value="${money(d.price)}" min="0" step="0.01">
            </td>
            <td class="text-end line-total">৳0.00</td>
            <td class="text-end">
                <button type="button" class="btn btn-sm btn-outline-danger remove-btn">Remove</button>
            </td>
        `,
        recalcRow: (tr) => {
            const id = tr.getAttribute('data-id');
            const qty = Number(tr.querySelector(`[name="package_qty[${id}]"]`).value || 1);
            const price = Number(tr.querySelector(`[name="package_price[${id}]"]`).value || 0);
            tr.querySelector('.line-total').innerText = '৳' + money(qty * price);
        },
        selectOne: (data) => {
            const tbody = document.querySelector(PACKAGE.tableBodySel);
            tbody.innerHTML = '';
            PACKAGE.map.clear();
            upsertSelectedRow(PACKAGE, data);
        }
    };

    const SERVICE = {
        map: selected.services,
        tableBodySel: '#selectedServicesTable tbody',
        renderRow: (d) => `
            <td>
                <strong>${d.title}</strong>
                ${d.desc ? `<div class="text-muted small">${d.desc}</div>` : ``}
                <input type="hidden" name="services[]" value="${d.id}">
            </td>
            <td class="text-end">
                <input type="number" class="form-control form-control-sm text-end" name="service_qty[${d.id}]" value="1" min="1">
            </td>
            <td class="text-end">
                <input type="number" class="form-control form-control-sm text-end" name="service_price[${d.id}]" value="${money(d.price)}" min="0" step="0.01">
            </td>
            <td class="text-end line-total">৳0.00</td>
            <td class="text-end">
                <button type="button" class="btn btn-sm btn-outline-danger remove-btn">Remove</button>
            </td>
        `,
        recalcRow: (tr) => {
            const id = tr.getAttribute('data-id');
            const qty = Number(tr.querySelector(`[name="service_qty[${id}]"]`).value || 1);
            const price = Number(tr.querySelector(`[name="service_price[${id}]"]`).value || 0);
            tr.querySelector('.line-total').innerText = '৳' + money(qty * price);
        }
    };

    // Add handlers
    document.querySelectorAll('.add-room').forEach(btn => {
        btn.addEventListener('click', () => {
            const row = btn.closest('tr');
            upsertSelectedRow(ROOM, {
                id: row.dataset.id,
                title: row.dataset.title,
                price: row.dataset.price,
                capacity: row.dataset.capacity,
                number: row.dataset.number
            });
        });
    });

    document.querySelectorAll('.add-spot').forEach(btn => {
        btn.addEventListener('click', () => {
            const row = btn.closest('tr');
            upsertSelectedRow(SPOT, {
                id: row.dataset.id,
                title: row.dataset.title,
                price: row.dataset.price,
                capacity: row.dataset.capacity
            });
        });
    });

    // Single-select package
    document.querySelectorAll('.select-package').forEach(btn => {
        btn.addEventListener('click', () => {
            const row = btn.closest('tr');
            PACKAGE.selectOne({
                id: row.dataset.id,
                title: row.dataset.title,
                price: row.dataset.price,
                persons: row.dataset.persons
            });
        });
    });

    // Add service
    document.querySelectorAll('.add-service').forEach(btn => {
        btn.addEventListener('click', () => {
            const row = btn.closest('tr');
            upsertSelectedRow(SERVICE, {
                id: row.dataset.id,
                title: row.dataset.title,
                price: row.dataset.price,
                desc: row.dataset.desc
            });
        });
    });

})();
</script>
</x-default-layout>

<x-default-layout>
    <style>
        /* ===============================
           CLEAN STANDARD UI - BOOKING PAGE
        =============================== */
        :root{
            --bg: #f6f8fb;
            --card: #ffffff;
            --border: #e5e7eb;
            --text: #111827;
            --muted: #6b7280;
            --primary: #2563eb;
            --danger: #ef4444;
            --success: #16a34a;
            --shadow: 0 10px 30px rgba(0,0,0,.06);
            --radius: 14px;
        }

        .booking-page{
            background: var(--bg);
            border-radius: var(--radius);
        }

        .section-title{
            font-size: 16px;
            font-weight: 700;
            color: var(--text);
            margin: 0;
        }
        .section-subtitle{
            font-size: 12px;
            color: var(--muted);
            margin: 2px 0 0;
        }

        .card-clean{
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
        }

        .card-head{
            padding: 14px 16px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            position: sticky;
            top: 0;
            background: var(--card);
            z-index: 5;
            border-top-left-radius: var(--radius);
            border-top-right-radius: var(--radius);
        }

        .filters{
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            align-items: end;
            justify-content: flex-end;
        }

        .filters .form-group label{
            font-size: 12px;
            color: var(--muted);
            margin-bottom: 4px;
        }

        .btn-clean{
            border-radius: 10px !important;
            padding: 8px 14px !important;
            font-weight: 600;
        }

        /* Room list scroll area */
        .room-scroll{
            max-height: 610px;
            overflow: auto;
            padding: 14px 16px 18px;
        }

        .date-header{
            position: sticky;
            top: 2px; /* below card-head */
            z-index: 4;
            background: var(--card);
            padding: 10px 0 8px;
            border-bottom: 1px dashed var(--border);
            margin-bottom: 10px;
        }

        .date-header h4{
            margin: 0;
            font-size: 14px;
            font-weight: 800;
            color: var(--success);
            text-align: center;
        }

        /* Room chips */
        .room-grid{
            display: grid;
            grid-template-columns: repeat(8, minmax(0, 1fr));
            gap: 10px;
            padding-bottom: 12px;
        }

        @media (max-width: 1400px){
            .room-grid{ grid-template-columns: repeat(6, minmax(0, 1fr)); }
        }
        @media (max-width: 1200px){
            .room-grid{ grid-template-columns: repeat(5, minmax(0, 1fr)); }
        }
        @media (max-width: 992px){
            .two-col-stack{ flex-direction: column; }
            .room-grid{ grid-template-columns: repeat(5, minmax(0, 1fr)); }
        }
        @media (max-width: 576px){
            .room-grid{ grid-template-columns: repeat(4, minmax(0, 1fr)); }
        }

        .room-chip{
            width: 100%;
            border: 1px solid var(--border);
            background: #f9fafb;
            color: var(--text);
            border-radius: 12px;
            padding: 10px 0;
            font-weight: 800;
            font-size: 13px;
            cursor: pointer;
            transition: .15s ease;
            user-select: none;
        }
        .room-chip:hover{
            transform: translateY(-1px);
            border-color: #cbd5e1;
            background: #fff;
        }

        .room-chip.is-booked{
            background: #fee2e2;
            border-color: #fecaca;
            color: #991b1b;
            cursor: not-allowed;
            opacity: .95;
        }

        .room-chip.is-selected{
            background: #dbeafe;
            border-color: #93c5fd;
            color: #1d4ed8;
        }

        /* Booking table */
        .table-clean{
            margin: 0;
        }
        .table-clean thead th{
            position: sticky;
            top: 0;
            z-index: 3;
            background: #111827;
            color: #fff;
            font-size: 12px;
            letter-spacing: .3px;
            text-transform: uppercase;
        }
        .table-clean td, .table-clean th{
            padding: .55rem !important;
            vertical-align: middle;
        }
        .table-wrap{
            max-height: 320px;
            overflow: auto;
            border-radius: 12px;
            border: 1px solid var(--border);
        }

        /* Form spacing */
        .form-block{
            padding: 14px 16px 16px;
        }
        .form-block .form-label{
            font-size: 12px;
            color: var(--muted);
            margin-bottom: 5px;
        }
        .form-block .form-control, .form-block .form-select{
            border-radius: 10px;
        }

        /* Sticky bottom actions */
        .sticky-actions{
            position: sticky;
            bottom: 0;
            z-index: 10;
            background: rgba(246,248,251,.85);
            backdrop-filter: blur(8px);
            padding: 12px 0 0;
        }
        .action-bar{
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            padding: 12px 16px;
            border: 1px solid var(--border);
            background: var(--card);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
        }

        .hint{
            font-size: 12px;
            color: var(--muted);
        }
    </style>

    <div class="app-main flex-column flex-row-fluid booking-page" id="kt_app_main">
        <div class="d-flex flex-column flex-column-fluid">
            <div id="kt_app_content" class="app-content flex-column-fluid">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div id="kt_app_content_container" class="app-container">
                    <div class="d-flex flex-row gap-8 two-col-stack">

                        <!-- LEFT: ROOM LIST -->
                        <div class="col-12 col-lg-6">
                            <div class="card-clean">
                                <div class="card-head">
                                    <div>
                                        <p class="section-title">Room List</p>
                                        <p class="section-subtitle">Pick room(s) by date. Booked rooms are disabled.</p>
                                    </div>

                                    <div class="filters">
                                        <div class="form-group">
                                            <label>Start Date</label>
                                            <input id="start_date" type="date" value="{{ date('Y-m-d') }}"
                                                class="form-control form-control-sm form-control-solid" name="start_date" />
                                        </div>
                                        <div class="form-group">
                                            <label>End Date</label>
                                            <input id="end_date" type="date"
                                                value="{{ now()->addDays(29)->format('Y-m-d') }}"
                                                class="form-control form-control-sm form-control-solid"
                                                name="end_date" />
                                        </div>

                                        <button type="button" id="searchBtn" class="btn btn-sm btn-primary btn-clean">
                                            Search
                                        </button>
                                    </div>
                                </div>

                                <div class="room-scroll">
                                    <div id="room_wise_booking_list_container">
                                        <!-- Date headers + room chips will be appended here -->
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- RIGHT: BOOKING + CUSTOMER -->
                        <div class="col-12 col-lg-6">
                            <form id="kt_ecommerce_booking_submit"
                                class="form fv-plugins-bootstrap5 fv-plugins-framework"
                                method="POST" action="{{ route('multiple_booking_store') }}"
                                enctype="multipart/form-data">
                                @csrf

                                <!-- Booking Details -->
                                <div class="card-clean mb-6">
                                    <div class="card-head">
                                        <div>
                                            <p class="section-title">Booking Details</p>
                                            <p class="section-subtitle">Review selected rooms and adjust check-out date.</p>
                                        </div>
                                        <div class="hint">Tip: Check-out must be after check-in.</div>
                                    </div>

                                    <div class="form-block">
                                        <div class="table-wrap">
                                            <table id="booking_data" class="table table-striped table-bordered table-hover table-clean">
                                                <thead>
                                                    <tr>
                                                        <th style="width:70px;">ID</th>
                                                        <th style="width:90px;">Room</th>
                                                        <th style="width:120px;">Check In</th>
                                                        <th style="width:140px;">Check Out</th>
                                                        <th style="width:140px;">Check Out + 1</th>
                                                        <th style="width:90px;">Total Day</th>
                                                        <th style="width:110px;">Price/Night</th>
                                                        <th style="width:120px;">Room Total</th>

                                                        <th style="width:90px;">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <!-- Customer Details -->
                                <div class="card-clean">
                                    <div class="card-head">
                                        <div>
                                            <p class="section-title">Customer Details</p>
                                            <p class="section-subtitle">Fill customer info to complete booking.</p>
                                        </div>
                                    </div>

                                    <div class="form-block">
                                        <div class="row g-4">
                                            <div class="col-md-6">
                                                <label class="required form-label">Customer Type</label>
                                                <select class="form-select form-select-sm" data-control="select2" name="customer_type" required>
                                                    <!-- <option value="">Select Customer Type</option> -->
                                                    @foreach ($customerTypes as $key => $value)
                                                        <option value="{{ $key }}">{{ $value }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="required form-label">Customer Mobile</label>
                                                <input type="text" id="customer_mobile" name="customer_mobile"
                                                    class="form-control form-control-sm"
                                                    placeholder="e.g. 01XXXXXXXXX"
                                                    onkeyup="loadCustomerDetails(this.value);" required>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="required form-label">Customer Name</label>
                                                <input type="text" id="customer_name" name="customer_name"
                                                    class="form-control form-control-sm"
                                                    placeholder="Customer Name" required>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label">Customer NID</label>
                                                <input type="text" id="nid_number" name="nid_number"
                                                    class="form-control form-control-sm"
                                                    placeholder="NID (Optional)">
                                            </div>

                                            <div class="col-md-12">
                                                <label class="required form-label">Customer Address</label>
                                                <input type="text" id="customer_address" name="customer_address"
                                                    class="form-control form-control-sm"
                                                    placeholder="Customer Address" required>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="required form-label">Paid Amount</label>
                                                <input type="text" id="paid_amount" name="paid_amount"
                                                    class="form-control form-control-sm"
                                                    placeholder="Paid Amount" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
<div class="card-clean mb-6" id="summaryCard">
  <div class="card-head">
    <div>
      <p class="section-title">Calculation Summary</p>
      <p class="section-subtitle">Auto calculated from selected rooms</p>
    </div>
    <div class="hint">Updates automatically</div>
  </div>

  <div class="form-block">
    <div class="row g-3">
      <div class="col-6">
        <div class="hint">Total Rooms</div>
        <div class="fw-bold fs-5" id="sum_rooms">0</div>
      </div>
      <div class="col-6 text-end">
        <div class="hint">Total Nights</div>
        <div class="fw-bold fs-5" id="sum_nights">0</div>
      </div>

      <div class="col-12"><hr class="my-2"></div>

      <div class="col-6">
        <div class="hint">Subtotal</div>
        <div class="fw-bold fs-5" id="sum_subtotal">0</div>
      </div>
      <div class="col-6 text-end">
        <div class="hint">Paid</div>
        <div class="fw-bold fs-5" id="sum_paid">0</div>
      </div>

      <div class="col-12"><hr class="my-2"></div>

      <div class="col-6">
        <div class="hint">Discount (optional)</div>
        <input type="number" min="0" id="discount_amount" class="form-control form-control-sm" value="0">
      </div>
      <div class="col-6 text-end">
        <div class="hint">Due</div>
        <div class="fw-bold fs-4 text-danger" id="sum_due">0</div>
      </div>
    </div>
  </div>
</div>

                                <!-- Sticky Action Bar -->
                                <div class="sticky-actions mt-6">
                                    <div class="action-bar">
                                        <a href="{{ route('booking.create') }}" class="btn btn-sm btn-success btn-clean">
                                            Cancel
                                        </a>
                                        <button type="submit" class="btn btn-sm btn-primary btn-clean">
                                            <span class="indicator-label">Save Booking</span>
                                        </button>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div><!-- row -->
                </div>
            </div>
        </div>
    </div>
</x-default-layout>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


<script>
/* =========================
   Helpers
========================= */
function num(v){
  let n = parseFloat(v);
  return isNaN(n) ? 0 : n;
}

function addDays(dateStr, days){
  // dateStr: YYYY-MM-DD
  let d = new Date(dateStr + "T00:00:00");
  d.setDate(d.getDate() + days);
  return d;
}

function toISODate(d){
  // Date -> YYYY-MM-DD
  let y = d.getFullYear();
  let m = String(d.getMonth() + 1).padStart(2, "0");
  let day = String(d.getDate()).padStart(2, "0");
  return `${y}-${m}-${day}`;
}

function formatDateMMDDYYYY(dateStr){
  // YYYY-MM-DD -> MM/DD/YYYY
  let d = new Date(dateStr + "T00:00:00");
  let m = String(d.getMonth() + 1).padStart(2, "0");
  let day = String(d.getDate()).padStart(2, "0");
  let y = d.getFullYear();
  return `${m}/${day}/${y}`;
}

function calculateDays(checkIn, checkOut){
  // both YYYY-MM-DD
  let s = new Date(checkIn + "T00:00:00");
  let e = new Date(checkOut + "T00:00:00");

  // inclusive count (তুমি আগে endDate+1 করতেছ)
  e.setDate(e.getDate() + 1);

  let diff = (e - s) / (1000 * 60 * 60 * 24);
  return Math.max(1, Math.round(diff));
}

/* =========================
   Recalc Row + Summary
========================= */
function recalcRow($row){
  let checkIn = $row.find(".check-in-raw").val();      // YYYY-MM-DD
  let checkOut = $row.find(".check-out-date").val();   // YYYY-MM-DD

  let nights = calculateDays(checkIn, checkOut);
  $row.find(".day-count").text(nights);

  let nextDay = toISODate(addDays(checkOut, 1));
  $row.find(".next-day").text(formatDateMMDDYYYY(nextDay));

  let price = num($row.attr("data-price"));
  $row.find(".price-night").text(price.toFixed(2));

  let total = price * nights;
  $row.find(".room-total").text(total.toFixed(2));
}

function recalcSummary(){
  let totalRooms = 0;
  let totalNights = 0;
  let subtotal = 0;

  $("#booking_data tbody tr").each(function(){
    totalRooms++;
    let nights = num($(this).find(".day-count").text());
    let total = num($(this).find(".room-total").text());
    totalNights += nights;
    subtotal += total;
  });

  let paid = num($("#paid_amount").val());
  let discount = num($("#discount_amount").val());
  let payable = Math.max(0, subtotal - discount);
  let due = Math.max(0, payable - paid);

  $("#sum_rooms").text(totalRooms);
  $("#sum_nights").text(totalNights);
  $("#sum_subtotal").text(payable.toFixed(2));
  $("#sum_paid").text(paid.toFixed(2));
  $("#sum_due").text(due.toFixed(2));
}

/* =========================
   Add Booking Row
========================= */
function addBooking(id, roomNumber, checkIn, pricePerNight){
  // prevent duplicate room
  let exists = false;
  $("#booking_data tbody tr").each(function(){
    if ($(this).attr("data-id") == id){
      exists = true;
      return false;
    }
  });
  if (exists) return;

  // default checkOut = checkIn (তুমি input এ checkIn দিচ্ছ)
  let checkOut = checkIn;
  let nights = calculateDays(checkIn, checkOut);
  let roomTotal = nights * num(pricePerNight);

  let nextDayISO = toISODate(addDays(checkOut, 1));

  let rowHtml = `
    <tr data-id="${id}" data-price="${num(pricePerNight)}">
      <td>${id}<input type="hidden" name="table_room_id[]" value="${id}"></td>
      <td>${roomNumber}<input type="hidden" name="table_room_number[]" value="${roomNumber}"></td>

      <td class="checkin-text">${formatDateMMDDYYYY(checkIn)}
        <input type="hidden" class="check-in-raw" name="table_check_in_date[]" value="${checkIn}">
      </td>

      <td>
        <input type="date"
          class="form-control form-control-sm check-out-date"
          name="table_check_out_date[]"
          value="${checkOut}"
          data-prev="${checkOut}"
          data-room-id="${id}"
          data-check-in="${checkIn}">
      </td>

      <td class="next-day">${formatDateMMDDYYYY(nextDayISO)}</td>
      <td class="day-count">${nights}</td>

      <td class="price-night">${num(pricePerNight).toFixed(2)}
        <input type="hidden" name="table_price_per_night[]" value="${num(pricePerNight)}">
      </td>

      <td class="room-total">${roomTotal.toFixed(2)}</td>

      <td>
        <button type="button" class="btn btn-sm btn-danger delete-booking">Delete</button>
      </td>
    </tr>
  `;

  $("#booking_data tbody").append(rowHtml);
  recalcSummary();
}

/* =========================
   Load Room List (AJAX)
========================= */
function CallBack(listOrPdf){
  let startDate = $("#start_date").val();
  let endDate = $("#end_date").val();

  let url = `{{ route('booking_search',['startDate' => ':startDate', 'endDate' => ':endDate']) }}`;
  url = url.replace(':startDate', startDate);
  url = url.replace(':endDate', endDate);

  if (listOrPdf === "list"){
    $.ajax({
      url: url,
      type: "GET",
      dataType: "json",
      success: function(data){
        $("#room_wise_booking_list_container").empty();

        if (!data || data.length === 0){
          $("#room_wise_booking_list_container").html(`<p class="text-center text-muted mb-0">No records found</p>`);
          return;
        }

        let displayedDates = new Set();
        let $currentGrid = null;

        data.forEach(d => {
          if (!displayedDates.has(d.date)){
            displayedDates.add(d.date);

            const dateObj = new Date(d.date + "T00:00:00");
            const formattedDate = dateObj.toLocaleDateString('en-US', { day:'numeric', month:'long', year:'numeric' });

            $("#room_wise_booking_list_container").append(`
              <div class="date-header"><h4>${formattedDate}</h4></div>
              <div class="room-grid" data-date="${d.date}"></div>
            `);
            $currentGrid = $(`.room-grid[data-date="${d.date}"]`);
          }

          const isBooked = d.is_booked === "Booked";
          const chipClass = isBooked ? "room-chip is-booked" : "room-chip";
          const disabledAttr = isBooked ? "disabled" : "";

          const price = num(d.price_per_night || 0);

          const chip = `
            <button type="button"
              class="${chipClass}"
              ${disabledAttr}
              data-room-id="${d.room_id}"
              data-room-number="${d.room_number}"
              data-date="${d.date}"
              data-price="${price}">
              ${d.room_number}
            </button>
          `;
          $currentGrid.append(chip);
        });
      }
    });
  }
}

/* =========================
   Availability check for checkout change
========================= */
function checkAvailability(roomId, checkIn, checkOut, onOk, onFail){
  let url = `{{ route('room_booking_search', ['id' => '__ID__', 'startDate' => '__START__', 'endDate_2' => '__END__']) }}`;
  url = url.replace('__ID__', roomId).replace('__START__', checkIn).replace('__END__', checkOut);

  $.ajax({
    url,
    type: "GET",
    success: function(response){
      let isConflict = Array.isArray(response) && response.some(day => day.is_booked === "Booked");
      if (isConflict){
        let bookedDates = response
          .filter(day => day.is_booked === "Booked")
          .map(day => day.date)
          .join(", ");
        onFail(bookedDates);
      } else {
        onOk();
      }
    },
    error: function(){
      onFail("Error checking availability");
    }
  });
}

/* =========================
   Init + Events
========================= */
$(document).ready(function(){
  // min date set
  const today = new Date().toISOString().split("T")[0];
  $("#start_date").attr("min", today);
  $("#end_date").attr("min", today);

  // initial list
  CallBack("list");

  // Search button
  $("#searchBtn").on("click", function(){
    CallBack("list");
  });

  // Start/End date change validation
  $("#start_date, #end_date").on("change", function(){
    let s = $("#start_date").val();
    let e = $("#end_date").val();
    if (s && e && new Date(s) > new Date(e)){
      alert("Start date cannot be after end date");
      // auto fix
      if (this.id === "start_date") $("#start_date").val(e);
      if (this.id === "end_date") $("#end_date").val(s);
    }
    // reload room list (optional)
    CallBack("list");
  });

  // room chip click (event delegation)
  $(document).on("click", ".room-chip:not(.is-booked)", function(){
    $(this).addClass("is-selected");

    let id = $(this).data("room-id");
    let roomNumber = $(this).data("room-number");
    let checkIn = $(this).data("date");     // YYYY-MM-DD
    let price = $(this).data("price");      // number

    addBooking(id, roomNumber, checkIn, price);
  });

  // checkout date change (SINGLE listener)
  $(document).on("change", ".check-out-date", function(){
    let $input = $(this);
    let $row = $input.closest("tr");

    let roomId = $input.data("room-id");
    let checkIn = $input.data("check-in");     // YYYY-MM-DD
    let newCheckOut = $input.val();            // YYYY-MM-DD
    let prev = $input.attr("data-prev") || checkIn;

    // validation: must be after check-in
    if (new Date(newCheckOut) <= new Date(checkIn)){
      alert("❌ Check-Out date must be after Check-In date.");
      $input.val(prev);
      recalcRow($row);
      recalcSummary();
      return;
    }

    checkAvailability(
      roomId,
      checkIn,
      newCheckOut,
      function onOk(){
        // save prev & recalc
        $input.attr("data-prev", newCheckOut);
        recalcRow($row);
        recalcSummary();
      },
      function onFail(bookedDates){
        alert(`❌ Room is NOT available.\nBooked dates: ${bookedDates}`);
        $input.val(prev);
        recalcRow($row);
        recalcSummary();
      }
    );
  });

  // delete row
  $(document).on("click", ".delete-booking", function(){
    $(this).closest("tr").remove();
    recalcSummary();
  });

  // paid/discount update
  $(document).on("keyup change", "#paid_amount, #discount_amount", function(){
    recalcSummary();
  });

  // form submit debug
  $("#kt_ecommerce_booking_submit").on("submit", function(e){
    // e.preventDefault();
    // console.log($(this).serializeArray());
  });
});
</script>


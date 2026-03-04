<?php

namespace App\Http\Controllers\Room;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\CustomerType;
use App\Models\PaymentDetail;
use App\Models\Room;
use App\Models\TermsCondition;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
// BookingController.php
use PDF; // যদি mpdf/dompdf/laravel-dompdf ব্যবহার করো
class BookingController extends Controller
{
    public function index()
    {

        // $booking = Booking::with(['customer', 'room'])->get();
        // return view('pages.room.booking.index', compact('booking'));

        // $bookings = Booking::with(['customer', 'room'])->get()->groupBy(groupBy: 'booking_no');

        $bookings = Booking::with(['customer', 'room'])
            ->orderBy('booking_no', 'desc') // order before fetching
            ->get()
            ->groupBy('booking_no');


        // dd($bookings);

        return view('pages.room.booking.index', compact('bookings'));
    }
    public function create()
    {
        // Return a view for creating a new room
        // $allRoomType = RoomType::pluck('type_name', 'id')->all();
        // $customer_mobile='01913865989';
        // $customer = Customer::where('customer_mobile', $customer_mobile)->first();
        // dd($customer);
        // $startDate='2024-01-09';
        // $endDate='2024-01-09';
        // $bookingSearch = DB::connection()->select("CALL sp_GetBookingCheck(?, ?)", array($startDate, $endDate));
        // dd($bookingSearch);
        $room = Room::where('status', 1)->orderBy('room_order', 'asc')->get();
        // dd($room);
        // return view('pages.room.booking.add',compact('room'));
        return view('pages.room.booking.booking_room_list', compact('room'));
    }
    public function multipleBookingRoomList()
    {
        
        $room          = Room::where('status', 1)->orderBy('room_order', 'asc')->get();
        
        $customerTypes = CustomerType::where('status', 1)->pluck('type_name', 'id')->all();
        // dd($room);
        return view('pages.room.booking.multiple_booking_room_list', compact('room', 'customerTypes'));
    }
public function create2(Request $request)
{
    $rooms = $request->input('rooms', []); // array of rooms with id, date, start, end

    $customerTypes = CustomerType::where('status', 1)
                        ->pluck('type_name', 'id');

    return view('pages.room.booking.add', [
        'rooms' => $rooms,
        'customerTypes' => $customerTypes
    ]);
}




//  public function bookingSearch($startDate, $endDate)
//     {
//         $bookingSearch = DB::connection()->select("CALL sp_GetBookingCheck_2(?, ?)", [$startDate, $endDate]);
//         // dd($bookingSearch);
//         // return view('pages.room.booking.booking_room_list', compact('bookingSearch'));
//         return response()->json($bookingSearch);
//     }

public function bookingSearch($startDate, $endDate)
{
    $startDate = date('Y-m-d', strtotime($startDate));
    $endDate   = date('Y-m-d', strtotime($endDate));

    // Generate date range
    $dates = [];
    $current = strtotime($startDate);
    $end = strtotime($endDate);
    while ($current <= $end) {
        $dates[] = date('Y-m-d', $current);
        $current = strtotime('+1 day', $current);
    }

    // Get all rooms
    $rooms = DB::table('rooms')->orderBy('room_order', 'asc')->get();

    // Get all bookings in the range
    $bookings = DB::table('bookings')
        ->where('Booking_status', '!=', 2)
        ->where(function($q) use ($startDate, $endDate) {
            $q->where(function($qb) use ($startDate, $endDate) {
                // Day-wise bookings
                $qb->where('total_days', '>', 0)
                   ->where('check_in_date', '<=', $endDate)
                   ->where('check_out_date', '>=', $startDate);
            })->orWhere(function($qb) use ($startDate, $endDate) {
                // Hourly bookings: only within date range
                $qb->where('total_days', '=', 0)
                   ->where('check_in_date', '>=', $startDate)
                   ->where('check_in_date', '<=', $endDate);
            });
        })->get();

    $result = [];

    foreach ($dates as $date) {
        foreach ($rooms as $room) {
            // Default: available
            $isBooked = false;

            // Check each booking for this room
            foreach ($bookings as $b) {
                if ($b->room_id != $room->id) continue;

                // Hourly booking: only mark booked if current datetime overlaps
                if ((int)$b->total_days === 0 && $b->check_in_date == $date) {
                    $checkIn  = strtotime($date . ' ' . $b->check_in_datetime);
                    $checkOut = strtotime($date . ' ' . $b->check_out_datetime);
                    $now      = time(); // or you can set a specific datetime

                    // Only mark booked if current time is inside this booking
                    if ($now >= $checkIn && $now <= $checkOut) {
                        $isBooked = true;
                        break;
                    }
                }

                // Day-wise booking: full date range
                if ((int)$b->total_days > 0) {
    $checkIn  = strtotime($b->check_in_date . ' 00:00:00');

    // শুধুমাত্র total_days অনুযায়ী দিনগুলো block হবে
    $checkOut = strtotime($b->check_in_date . ' +' . ($b->total_days - 1) . ' days 23:59:59');

    $dayStart = strtotime($date . ' 00:00:00');
    $dayEnd   = strtotime($date . ' 23:59:59');

    if (!($checkOut < $dayStart || $checkIn > $dayEnd)) {
        $isBooked = true;
        break;
    }
}

            }

            // Get first image
            $imagePath = DB::table('room_details')
                ->where('room_id', $room->id)
                ->orderBy('id')
                ->value('image_path');

            $result[] = [
                'date'           => $date,
                'room_id'        => $room->id,
                'room_number'    => $room->room_number,
                'floor'          => $room->floor,
                'image_path'     => $imagePath,
                'is_booked'      => $isBooked ? 'Booked' : 'Available',
                'price_per_night'=> $room->price_per_night ?? 0,
            ];
        }
    }

    return response()->json($result);
}


    public function roomBookingSearch($id, $startDate, $endDate)
    {
        $roomBookingSearch = DB::connection()->select("CALL sp_GetRoomAvailability(?, ?, ?)", [$id, $startDate, $endDate]);
        return response()->json($roomBookingSearch);
    }

    // public function bookingSearch(Request $request)
    // {
    //     $startDate = $request->query('startDate');
    //     $endDate = $request->query('endDate');
    //     // Validate the input dates
    //     if (!$startDate || !$endDate) {
    //         return redirect()->back()->withErrors(['error' => 'Both start and end dates are required.']);
    //     }
    //     if (!strtotime($startDate) || !strtotime($endDate)) {
    //         return redirect()->back()->withErrors(['error' => 'Invalid date format.']);
    //     }
    //     // Fetch bookings within the date range
    //     $bookingSearch = DB::connection()->select("CALL sp_GetBookingCheck(?, ?)", array($startDate, $endDate));
    //     // Return the results
    //     return view('pages.room.booking.booking_room_list', compact('bookingSearch'));
    // }
    /**
     * Store a newly created resource in storage.
     */
  public function store(Request $request)
{
    //dd($request);
    $request->validate([
        'rooms' => 'required|array',
        'rooms.*.id' => 'required|exists:rooms,id',
        'rooms.*.date' => 'required|date',
        'rooms.*.start' => 'required',
        'rooms.*.end' => 'required',
        'customer_name' => 'required|string|max:255',
        'customer_address' => 'required|string|max:255',
        'customer_mobile' => 'required|string|max:15',
        'total_discount' => 'nullable|numeric|min:0',
        'after_discount' => 'nullable|numeric|min:0',
        'total_paid' => 'nullable|numeric|min:0',
    ]);

    /* ===============================
       Customer
    =============================== */
  
        $customer = Customer::updateOrCreate(
            [
                'customer_type'    => 1,
                'customer_name'    => $request->customer_name,
                'nid_number'       => $request->nid_number,
                'customer_mobile'  => $request->customer_mobile,
                'customer_address' => $request->customer_address,
            ]
        );
    /* ===============================
       Booking Number
    =============================== */
    $invoice = DB::table('invoiceno')->lockForUpdate()->first();
    $bookingNumber = 'BO'.str_pad($invoice->booking_no, 6, '0', STR_PAD_LEFT);
    DB::table('invoiceno')->update([
        'booking_no' => $invoice->booking_no + 1
    ]);

    /* ===============================
       Totals
    =============================== */
    $netTotal = 0;
    foreach($request->rooms as $roomInfo) {
        $netTotal += $roomInfo['price_per_night'];
    }

    $discount = $request->total_discount ?? 0;
    $afterDiscount = $netTotal - $discount;
    $paid = $request->total_paid ?? 0;
    $due = $afterDiscount - $paid;

    /* ===============================
       Loop through rooms
    =============================== */
    foreach($request->rooms as $roomInfo) {

        $checkIn  = Carbon::parse($roomInfo['date'].' '.$roomInfo['start']);
        $checkOut = Carbon::parse($roomInfo['date'].' '.$roomInfo['end']);

        // Overlap protection
        $exists = Booking::where('room_id', $roomInfo['id'])
            ->whereNotNull('check_in_datetime')
            ->whereNotNull('check_out_datetime')
            ->where(function ($q) use ($checkIn, $checkOut) {
                $q->where('check_in_datetime', '<', $checkOut)
                  ->where('check_out_datetime', '>', $checkIn);
            })
            ->exists();

        if ($exists) {
            return back()->withErrors([
                'rooms' => 'Room '.$roomInfo['id'].' is already booked for selected time.'
            ])->withInput();
        }

        $room = Room::findOrFail($roomInfo['id']);

        // Hour calculation
        $totalHours = ceil($checkOut->diffInMinutes($checkIn) / 60);
        $totalAmount = $room->price_per_night;

        // Apply room-level discount if any
        $roomDiscount = $roomInfo['discount'] ?? 0;
        $roomAmountAfterDiscount = $totalAmount - $roomDiscount;

        $paymentStatus = (($roomAmountAfterDiscount) <= ($paid / count($request->rooms))) ? 1 : 0;

        // Booking save
        $booking = Booking::create([
            'booking_no'         => $bookingNumber,
            'customer_id'        => $customer->id,
            'room_id'            => $room->id,
            'check_in_date'      => $roomInfo['date'],
            'check_out_date'     => $roomInfo['date'], // hourly, same day
            'check_in_datetime'  => $checkIn,
            'check_out_datetime' => $checkOut,
            'total_days'         => 0,
            'total_amount'       => $totalAmount,
            'discount'       => $discount,
            'payment_status'     => $paymentStatus,
            'Booking_status'     => 0,
            'status'             => 1,
        ]);

        // Payment detail per room
       
     

       
    }

       if ($paid > 0) {
            PaymentDetail::create([
                'booking_no' => $bookingNumber,
                'amount'     => $paid,
            ]);
        }
    return redirect()->route('booking.create')->with([
        'message' => 'Bulk hourly booking successful!',
        'alert-type' => 'success'
    ]);
}


    public function multipleBookingStore(Request $request)
    {

        //dd($request->all());

        $request->validate([
            'customer_name'        => 'required|string|max:255',
            'customer_mobile'      => 'required|string|max:15',
            'customer_address'     => 'required|string|max:500',
            'paid_amount'          => 'nullable|numeric',
            'discount'          => 'nullable|numeric',
            'table_room_id'        => 'required|array',
            'table_check_in_date'  => 'required|array',
            'table_check_out_date' => 'required|array',
            // 'payment_status' => 'required|integer',
        ]);

        // get sales_no
        $bookingNo     = DB::table('invoiceno')->first('booking_no');
        $getBookingNo  = $bookingNo->booking_no;
        $bookingNumber = 'BO' . str_pad($getBookingNo, 6, '0', STR_PAD_LEFT);
        DB::table('invoiceno')->update([
            'booking_no' => $getBookingNo + 1,
        ]);

        $customer = Customer::updateOrCreate(
            [
                'customer_type'    => $request->customer_type,
                'customer_name'    => $request->customer_name,
                'nid_number'       => $request->nid_number,
                'customer_mobile'  => $request->customer_mobile,
                'customer_address' => $request->customer_address,
            ]
        );
        $lastCustomerId = $customer->id;

        // dd($lastCustomerId);

        if ($request->has('table_room_id') && is_array($request->input('table_room_id'))) {
            foreach ($request->input('table_room_id') as $key => $table_room_id) {
                $multipleBooking = new Booking();

                $room_id      = $request->input('table_room_id')[$key];
                $checkInDate  = Carbon::parse($request->input('table_check_in_date')[$key]);
                $checkOutDate = Carbon::parse($request->input('table_check_out_date')[$key]);


                $room = Room::find($room_id);

                $room_rent    = $room->price_per_night;

                $multipleBooking->room_id        = $room_id;

                if ($checkInDate == $checkOutDate) {
                    $multipleBooking->check_in_date  = date('Y-m-d', strtotime($checkInDate));
                    $multipleBooking->check_out_date = date('Y-m-d', strtotime('+1 day', strtotime($checkOutDate)));
                    $total_days   = $checkOutDate->diffInDays($checkInDate) + 1;
                } else {
                    $multipleBooking->check_in_date  = date('Y-m-d', strtotime($checkInDate));
                    $multipleBooking->check_out_date = date('Y-m-d', strtotime('+1 day', strtotime($checkOutDate)));
                    $total_days   = $checkOutDate->diffInDays($checkInDate) + 1;
                }

                $total_amount = $room_rent * $total_days;

                $multipleBooking->total_amount   = $total_amount;
                $multipleBooking->discount   = $request->discount;
                $multipleBooking->total_days     = $total_days;

                $paymentStatus                   = $request->paid_amount < $total_amount ? 0 : 1;
                $multipleBooking->payment_status = $paymentStatus;
                $multipleBooking->customer_id    = $lastCustomerId;
                $multipleBooking->booking_no     = $bookingNumber;
                $multipleBooking->Booking_status     = 1;
                $multipleBooking->check_in_datetime  = Carbon::parse($multipleBooking->check_in_date . ' 10:59:59');
                $multipleBooking->check_out_datetime = Carbon::parse($multipleBooking->check_out_date . ' 10:59:59');


                $multipleBooking->save();
            }
        } else {
            // Handle error: no data passed for room bookings
            return back()->withErrors(['message' => 'No room bookings provided.']);
        }

        // $lastBookingId = $multipleBooking->id;

        $payment = PaymentDetail::create([
            'booking_no' => $bookingNumber,
            'amount'     => $request->paid_amount,
        ]);

        return redirect()->route('multiple_booking')->with([
            'message'    => 'Successfully Booked!',
            'alert-type' => 'success',
        ]);
    }

    // public function multipleBookingStore(Request $request)
    // {
    //     $request->validate([
    //         'customer_name' => 'required|string|max:255',
    //         'customer_mobile' => 'required|string|max:15',
    //         'customer_address' => 'required|string|max:500',
    //         'paid_amount' => 'nullable|numeric',
    //         'payment_status' => 'required|integer',
    //     ]);

    //     // Create or update customer
    //     $customer = Customer::updateOrCreate(
    //         [
    //             'customer_type' => $request->customer_type,
    //             'customer_name' => $request->customer_name,
    //             'nid_number' => $request->nid_number,
    //             'customer_mobile' => $request->customer_mobile,
    //             'customer_address' => $request->customer_address,
    //         ]
    //     );
    //     $lastCustomerId = $customer->id;

    //     // Validate array data before looping
    //     $tableRoomIds = $request->get('table_room_id');
    //     $tableCheckInDates = $request->input('table_check_in_date');
    //     $tableCheckOutDates = $request->input('table_check_out_date');
    //     $paymentStatuses = $request->input('payment_status');

    //     if (
    //         !$tableRoomIds || !is_array($tableRoomIds) ||
    //         count($tableRoomIds) !== count($tableCheckInDates) ||
    //         count($tableRoomIds) !== count($tableCheckOutDates) ||
    //         count($tableRoomIds) !== count($paymentStatuses)
    //     ) {
    //         return back()->withErrors(['error' => 'Invalid booking data provided.']);
    //     }

    //     foreach ($tableRoomIds as $key => $room_id) {
    //         $checkInDate = Carbon::parse($tableCheckInDates[$key]);
    //         $checkOutDate = Carbon::parse($tableCheckOutDates[$key]);

    //         $room = Room::find($tableRoomIds);
    //         if (!$room) {
    //             return back()->withErrors(['error' => 'Room not found.']);
    //         }

    //         $total_days = $checkOutDate->diffInDays($checkInDate);
    //         $room_rent = $room->price_per_night;
    //         $total_amount = $room_rent * $total_days;

    //         $multipleBooking = Booking::create([
    //             'room_id' => $room_id,
    //             'check_in_date' => $checkInDate->format('Y-m-d'),
    //             'check_out_date' => $checkOutDate->format('Y-m-d'),
    //             'total_amount' => $total_amount,
    //             'total_days' => $total_days,
    //             'payment_status' => $request->input('payment_status'),
    //             'customer_id' => $lastCustomerId,
    //         ]);

    //         // Store payment details
    //         PaymentDetail::create([
    //             'booking_id' => $multipleBooking->id,
    //             'amount' => $request->paid_amount,
    //         ]);
    //     }

    //     return redirect()->route('multiple_booking')->with([
    //         'message' => 'Successfully Booked!',
    //         'alert-type' => 'success'
    //     ]);
    // }

    public function show(Room $room)
    {
        return view('pages.room.booking.show', compact('room'));
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Room $room)
    {
        return view('pages.room.booking.update', compact('room'));
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // $request->validate([
        //     'room_name' => 'required|string|max:255',
        //     'room_type_id' => 'required|exists:room_types,id',
        //     'status' => 'required|in:available,unavailable',
        // ]);
        $request->validate([
            'room_number' => [
                'required',
                'string',
                'max:255',
                Rule::unique('rooms')->ignore($id),
            ],
        ]);
        $room = Room::find($id);
        // dd($room);
        $room->update($request->all());
        return redirect()->route('booking.index')->with([
            'message'    => 'Successfully updated!',
            'alert-type' => 'info',
        ]);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Room $room)
    {
        $room->delete();
        return back()->with([
            'message'    => 'Successfully deleted!',
            'alert-type' => 'danger',
        ]);
    }
    public function getCustomer($customer_mobile)
    {
        $customer = Customer::where('customer_mobile', $customer_mobile)->first();
        // return response()->json($customer);
        if ($customer) {
            return response()->json($customer);
        } else {
            return response()->json(['error' => 'Customer not found'], 404);
        }
    }
    // Fetch lock statuses for rooms
    public function updateStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:bookings,id',
            'booking_status' => 'required|in:0,1,2'
        ]);

        $booking = Booking::find($request->id);
        $booking->booking_status = $request->booking_status;
        $booking->save();

        return response()->json(['success' => true]);
    }

  public function hourlySearch(Request $request)
{
    if (!$request->date || !$request->start_time || !$request->end_time) {
        return response()->json([]);
    }

    $date = $request->date;

    $startDateTime = Carbon::parse($request->date.' '.$request->start_time);
    $endDateTime   = Carbon::parse($request->date.' '.$request->end_time);

    // ✅ Order rooms by room_order
    $rooms = Room::orderBy('room_order', 'asc')->get();

    $result = [];

    foreach ($rooms as $room) {

        $isBooked = Booking::where('room_id', $room->id)
            ->where(function ($q) use ($date, $startDateTime, $endDateTime) {

                // DAY-WISE BOOKING
                $q->where(function ($sub) use ($date) {
                    $sub->where('total_days', '>', 0)
                        ->whereDate('check_in_date', '<=', $date)
                        ->whereDate('check_out_date', '>', $date);
                })
                // HOURLY BOOKING
                ->orWhere(function ($sub) use ($startDateTime, $endDateTime) {
                    $sub->where('total_days', 0)
                        ->where('check_in_datetime', '<', $endDateTime)
                        ->where('check_out_datetime', '>', $startDateTime);
                });

            })
            ->exists();

        $result[] = [
            'id'             => $room->id,
            'price_per_night' => $room->price_per_night,
            'room_number'    => $room->room_number,
            'is_booked'      => $isBooked,
            'date'           => $date,
            'start'          => $request->start_time,
            'end'            => $request->end_time,
        ];
    }

    return response()->json($result);
}






public function generateInvoice($bookingNo)
{
    // Bookings for this booking number
    $bookings = Booking::with(['customer', 'room'])
        ->where('booking_no', $bookingNo)
        ->get();

    if ($bookings->isEmpty()) {
        return back()->with('error', 'No bookings found for this invoice.');
    }

    // Payment details
    $paymentDetails = PaymentDetail::where('booking_no', $bookingNo)->get();

    // Booking-level discount (take only first booking's discount, not sum)
    $bookingDiscount = $bookings->first()->discount ?? 0;

    // Invoice calculation
    $subTotal = $bookings->sum('total_amount'); // sum of all rooms
    $netTotal = $subTotal - $bookingDiscount;   // apply discount once
    $receivedAmount = $paymentDetails->sum('amount');
    $dueAmount = $netTotal - $receivedAmount;

    $invoiceSummary = [
        'invoice' => $bookingNo,
        'date' => now()->toDateString(),
        'customer_name' => $bookings->first()->customer->customer_name ?? '',
        'customer_mobile' => $bookings->first()->customer->customer_mobile ?? '',
        'sub_total' => $subTotal,
        'discount_amount' => $bookingDiscount,
        'net_total' => $netTotal,
        'received_amount' => $receivedAmount,
        'due_amount' => $dueAmount,
    ];
$roomIds = $bookings->pluck('room_id')->unique();

$terms = TermsCondition::where('is_active', 1)
    ->where(function ($q) use ($roomIds) {

        // 🔹 Global Common Terms
        $q->where(function ($q1) {
            $q1->where('term_type', 'common')
               ->where('term_type1', 'common');
        })

        // 🔹 OR Room-wise Included Terms
        ->orWhere(function ($q2) use ($roomIds) {
            $q2->where('term_type', 'included')
               ->where('term_type1', 'room')
               ->whereIn('room_id', $roomIds);
        })
        ->orWhere(function ($q3) use ($roomIds) {
            $q3->where('term_type', 'common')
               ->where('term_type1', 'room');
        });
    })
    ->orderBy('sort_order')
    ->get();

    // Generate PDF using dompdf / mpdf
    $pdf = PDF::loadView('pages.room.booking.invoice_pdf', compact('bookings', 'paymentDetails', 'invoiceSummary','terms'));

    return $pdf->stream('Invoice_'.$bookingNo.'.pdf');
}


}

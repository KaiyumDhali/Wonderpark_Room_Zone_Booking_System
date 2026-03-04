<?php

namespace App\Http\Controllers\Room;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\RoomType;
use validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Models\Customer;
use App\Models\CustomerType;
use App\Models\CustomerLedger;
use App\Models\PaymentDetail;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Date;

class BookingController extends Controller
{
    public function index()
    {

        // $booking = Booking::with(['customer', 'room'])->get();
        // return view('pages.room.booking.index', compact('booking'));



        $bookings = Booking::with(['customer', 'room'])->get()->groupBy(groupBy: 'booking_no');
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
        $room = Room::where('status', 1)->get();
        // dd($room);
        // return view('pages.room.booking.add',compact('room'));
        return view('pages.room.booking.booking_room_list', compact('room'));
    }
    public function multipleBookingRoomList()
    {
        $room = Room::where('status', 1)->get();
        $customerTypes = CustomerType::where('status', 1)->pluck('type_name', 'id')->all();
        // dd($room);
        return view('pages.room.booking.multiple_booking_room_list', compact('room', 'customerTypes'));
    }
    public function create2($id, $date)
    {
        // Return a view for creating a new room
        // $allRoomType = RoomType::pluck('type_name', 'id')->all();
        $room = Room::where('status', 1)->get();
        $roomList = Room::where('status', 1)->pluck('room_number', 'id')->all();
        $customerTypes = CustomerType::where('status', 1)->pluck('type_name', 'id')->all();
        // dd($room);
        return view('pages.room.booking.add', compact('room', 'customerTypes', 'roomList'))->with('selectedRoomId', $id)->with('date', $date);
        // return view('pages.room.booking.booking_room_list',compact('room'));
    }
    public function bookingSearch($startDate, $endDate)
    {
        $bookingSearch = DB::connection()->select("CALL sp_GetBookingCheck_2(?, ?)", array($startDate, $endDate));
        // dd($bookingSearch);
        // return view('pages.room.booking.booking_room_list', compact('bookingSearch'));
        return response()->json($bookingSearch);
    }
    public function roomBookingSearch($id, $startDate, $endDate)
    {
        $roomBookingSearch = DB::connection()->select("CALL sp_GetRoomAvailability(?, ?, ?)", array($id, $startDate, $endDate));
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
        // dd($request->all());
        $room_id = $request->input('room_id');
        // Validate the incoming request
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_mobile' => 'required|string|max:15',
            'customer_address' => 'required|string|max:500',
            'room_id' => 'required|integer|exists:rooms,id',
            'check_in_date' => 'required|date|after_or_equal:today',
            'check_out_date' => 'required|date|after_or_equal:check_in_date',
            // 'status' => 'required|integer',
            'paid_amount' => 'nullable|numeric',
            // 'payment_status' => 'required|integer',
        ]);
        $input = $request->all();
        $checkInDate = Carbon::parse($input['check_in_date']);
        $checkOutDate = Carbon::parse($input['check_out_date']);
        $total_days = $checkOutDate->diffInDays($checkInDate) + 1;
        // $book_data = Booking::where('status', 1)->where('room_id', $room_id)->with(['room', 'customer'])->first();
        $room = Room::find($room_id);
        $total_room_rent = $room->price_per_night;
        $total_amount = $total_room_rent * $total_days;
        // $total_room_rent = $book_data->room?->price_per_night;
        // $total_room_rent = $book_data->room?->price_per_night ?? 0;
        // $total_amount = $total_room_rent * $total_days;

        if ($request->paid_amount < $total_amount) {
            $paymentStatus = 0;
        } elseif ($request->paid_amount >= $total_amount) {
            $paymentStatus = 1;
        }

        $customer = Customer::updateOrCreate(
            [
                'customer_type' => $request->customer_type,
                'customer_name' => $request->customer_name,
                'nid_number' => $request->nid_number,
                'customer_mobile' => $request->customer_mobile,
                'customer_address' => $request->customer_address,
            ]
        );
        $lastCustomerId = $customer->id;
        $booking = Booking::create([
            'customer_id' => $lastCustomerId,
            'room_id' => $request->room_id,
            'check_in_date' => $request->check_in_date,
            'check_out_date' => $request->check_out_date,
            'total_days' => $total_days,
            'total_amount' => $total_amount,
            'status' => $request->status,
            'payment_status' => $paymentStatus,
        ]);
        $lastBookingId = $booking->id;
        $payment = PaymentDetail::create([
            'booking_id' => $lastBookingId,
            'amount' => $request->paid_amount,
        ]);
        // dd($payment);
        return redirect()->route('booking.create')->with([
            'message' => 'Successfully Booked!',
            'alert-type' => 'success'
        ]);
    }

    public function multipleBookingStore(Request $request)
    {

        // dd($request->all());

        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_mobile' => 'required|string|max:15',
            'customer_address' => 'required|string|max:500',
            'paid_amount' => 'nullable|numeric',
            // 'payment_status' => 'required|integer',
        ]);

        // get sales_no
        $bookingNo = DB::table('invoiceno')->first('booking_no');
        $getBookingNo = $bookingNo->booking_no;
        $bookingNumber = 'BO' . str_pad($getBookingNo, 6, '0', STR_PAD_LEFT);
        DB::table('invoiceno')->update([
            'booking_no' => $getBookingNo + 1,
        ]);


        $customer = Customer::updateOrCreate(
            [
                'customer_type' => $request->customer_type,
                'customer_name' => $request->customer_name,
                'nid_number' => $request->nid_number,
                'customer_mobile' => $request->customer_mobile,
                'customer_address' => $request->customer_address,
            ]
        );
        $lastCustomerId = $customer->id;

        // dd($lastCustomerId);

        foreach ($request->get('table_room_id') as $key => $room_id) {

            $multipleBooking = new Booking();
            // $multipleBooking = $request->input('table_room_number')[$key];
            $room_id = $request->input('table_room_id')[$key];
            $checkInDate = Carbon::parse($request->input('table_check_in_date')[$key]);
            $checkOutDate = Carbon::parse($request->input('table_check_out_date')[$key]);
            // $checkOutDateFinal = $checkOutDate->addDay();
            $room = Room::find($room_id);

            $total_days = $checkOutDate->diffInDays($checkInDate) + 1;

            $room_rent = $room->price_per_night;
            $total_amount = $room_rent * $total_days;
            $multipleBooking->room_id = $room_id;
            $multipleBooking->check_in_date = date('Y-m-d', strtotime($checkInDate));
            $multipleBooking->check_out_date = date('Y-m-d', strtotime($checkOutDate));
            $multipleBooking->total_amount = $total_amount;
            $multipleBooking->total_days = $total_days;

            if ($request->paid_amount < $total_amount) {
                $paymentStatus = 0;
            } elseif ($request->paid_amount >= $total_amount) {
                $paymentStatus = 1;
            }

            $multipleBooking->payment_status = $paymentStatus;
            $multipleBooking->customer_id = $lastCustomerId;
            $multipleBooking->booking_no = $bookingNumber;


            $multipleBooking->save();
        }

        // $lastBookingId = $multipleBooking->id;

        $payment = PaymentDetail::create([
            'booking_no' => $bookingNumber,
            'amount' => $request->paid_amount,
        ]);



        return redirect()->route('multiple_booking')->with([
            'message' => 'Successfully Booked!',
            'alert-type' => 'success'
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
            'message' => 'Successfully updated!',
            'alert-type' => 'info'
        ]);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Room $room)
    {
        $room->delete();
        return back()->with([
            'message' => 'Successfully deleted!',
            'alert-type' => 'danger'
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
        $booking = Booking::find($request->id);
        if ($booking) {
            $booking->Booking_status = $request->status;
            $booking->save();
            return response()->json(['message' => 'Status updated successfully!']);
        }
        return response()->json(['message' => 'Booking not found.'], 404);
    }
}

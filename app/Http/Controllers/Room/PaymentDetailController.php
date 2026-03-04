<?php

namespace App\Http\Controllers\Room;


use App\Http\Controllers\Controller;
use App\Models\PaymentDetail;
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
use Auth;
use Carbon\Carbon;


class PaymentDetailController extends Controller
{

    public function index()
    {
        //
    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {

        // dd($request->all());

        $request->validate([
            'amount' => 'required|string|max:255',
        ]);

        $paymentDetail = new PaymentDetail();
        $paymentDetail->booking_no = $request->booking_no;
        $paymentDetail->amount = $request->amount;
        $paymentDetail->save();

        $bookingTotalAmount = Booking::where('booking_no', $paymentDetail->booking_no)->sum('total_amount');
        $totalReceivedSum = PaymentDetail::where('booking_no', $paymentDetail->booking_no)->sum('amount');
       

        if (($bookingTotalAmount - $totalReceivedSum) == 0) {
            \DB::table('bookings')
                ->where('booking_no', $paymentDetail->booking_no)
                ->update(['payment_status' => 1]);
        }

        return redirect()->back()->with([
            'message' => 'Successfully Paid!',
            'alert-type' => 'success'
        ]);
    }


    public function show(PaymentDetail $paymentDetail)
    {
        //
    }

    public function edit($id)
    {
        $bookingNo = $id;

        $booking = Booking::where('booking_no', $id)->with('customer', 'room')->get()->groupBy('booking_no');
        $bookingRooms = Booking::where('booking_no', $id)->get();

// assume discount is same for all rooms in this booking
        $discount = $bookingRooms->first()->discount ?? 0;
        $firstBooking = $booking[$id]->first() ?? null;
        $bookingTotalSum = Booking::where('booking_no', $id)->sum('total_amount');
        $bookingTotalAmount = Booking::where('booking_no', $id)->sum('total_amount');
        $totalReceivedSum = PaymentDetail::where('booking_no', $id)->sum('amount');
        $totalReceived = PaymentDetail::where('booking_no', $id)->get();

        return view('pages.room.payment.payment-add', compact('discount','bookingNo', 'bookingTotalSum', 'firstBooking', 'booking', 'bookingTotalAmount', 'totalReceived', 'totalReceivedSum'));
    }

    public function update(Request $request, $id) {}


    public function destroy(PaymentDetail $paymentDetail)
    {
        //
    }
}

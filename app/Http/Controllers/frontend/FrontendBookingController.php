<?php

namespace App\Http\Controllers\frontend;

use App\Models\cr;
use Illuminate\Http\Request;
use App\Models\FrontendModel;
use App\Models\Room;
use App\Models\Customer;
use App\Models\FrontEndUser;
use App\Models\FrontendBookings;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class FrontendBookingController extends Controller
{
    public function frontendbookRoom(Request $request)
    {



        $room_id = $request->input('room_id');
        // Validate the request data
        $validatedData = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:15',
            'customer_address' => 'required|string|max:255',
            'arrival_date' => 'required|date',
            'departure_date' => 'required|date',
        ]);
        // get sales_no
        $bookingNo = DB::table('invoiceno')->first('booking_no');
        $getBookingNo = $bookingNo->booking_no;
        $bookingNumber = 'BO' . str_pad($getBookingNo, 6, '0', STR_PAD_LEFT);
        DB::table('invoiceno')->update([
            'booking_no' => $getBookingNo + 1,
        ]);

        // Check if the room is already booked
        $isBooked = FrontendBookings::where('room_id', $request->room_id)
            ->where('Booking_status', '!=', 2)
            ->where(function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->whereBetween('check_in_date', [$request->arrival_date, $request->departure_date])
                        ->orWhereBetween('check_out_date', [$request->arrival_date, $request->departure_date])
                        ->orWhereRaw('? BETWEEN check_in_date AND check_out_date', [$request->arrival_date]) // Check if check-in is inside another booking
                        ->orWhereRaw('? BETWEEN check_in_date AND check_out_date', [$request->departure_date]); // Check if check-out is inside another booking
                })
                    ->orWhere(function ($q) use ($request) {
                        $q->where('check_in_date', '<=', $request->arrival_date)
                            ->where('check_out_date', '>=', $request->departure_date);
                    });
            })
            ->exists();


        if ($isBooked) {
            return redirect()->back()->withInput()->withErrors([
                'room_id' => 'The selected room is already booked for the given date range.',
            ]);
        }

        // Save or retrieve the user
        $user = Customer::firstOrCreate(


            ['customer_mobile' => $request->customer_phone],
            [
                'customer_name' => $request->customer_name,
                'customer_address' => $request->customer_address,
                'customer_type' => $request->customer_type,
            ]
        );

        $input = $request->all();
        $checkInDate = Carbon::parse($input['arrival_date']);
        $checkOutDate = Carbon::parse($input['departure_date']);

        if ($checkInDate == $checkOutDate) {
            $total_days = $checkOutDate->diffInDays($checkInDate) + 1;
        } else {
            $total_days = $checkOutDate->diffInDays($checkInDate);
        }


        // $total_days = $checkOutDate->diffInDays($checkInDate);
        // if ($total_days > 1) {
        //     $total_days=$total_days + 1;
        // }


        // $book_data = Booking::where('status', 1)->where('room_id', $room_id)->with(['room', 'customer'])->first();
        $room = Room::find($room_id);

        $total_room_rent = $room->price_per_night;
        $total_amount = $total_room_rent * $total_days;

        // Create a new booking
        FrontendBookings::create([
            'room_id' => $request->room_id,
            'customer_id' => $user->id,
            'total_days' => $total_days,
            'total_amount' => $total_amount,
            'Booking_status' => $request->Booking_status,
            'booking_no' => $bookingNumber,
            'check_in_date' => $request->arrival_date,
            'check_out_date' => $request->departure_date,
        ]);

        // Return a success message
        return redirect()->back()->with('success', 'Booking successfully created!');
    }
}

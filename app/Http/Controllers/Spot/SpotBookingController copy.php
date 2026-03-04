<?php

namespace App\Http\Controllers\Spot;

use App\Models\SpotBooking;
use Illuminate\Http\Request;
use App\Models\SpotPackage;
use App\Models\Spot;
use App\Models\SpotDetail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;





class SpotBookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bookings = SpotBooking::with(['spot', 'package'])->latest()->paginate(20);
        return view('pages.spot_booking.index', compact('bookings'));
    }



    /**
     * Show the form for creating a new resource.
     */
    // public function create()
    // {
    //     $spots = Spot::where('status', 1)
    //         ->orderBy('title')
    //         ->get();

    //     $packages = SpotPackage::where('status', 1)
    //         ->orderBy('id')
    //         ->get();

    //     return view('pages.spot_booking.create', compact('spots', 'packages'));
    // }

    public function create()
    {
        return view('pages.spot_booking.create', [
            'spots' => Spot::where('status', 1)->get(),
            'packages' => SpotPackage::where('status', 1)->get(),
        ]);
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        dd($this->$request->all());


        $request->validate([
            'booking_date'     => 'required|date',
            'customer_name'    => 'required|string|max:255',
            'customer_mobile'  => 'required|string|max:20',
            'selected_spots'   => 'required'
        ]);

        $spots = json_decode($request->selected_spots, true);

        foreach ($spots as $spot) {
            SpotBooking::create([
                'spot_id'        => $spot['id'],
                'booking_date'   => $request->booking_date,
                'total_persons'  => 1,
                'total_price'    => $spot['price'],
                'customer_name'  => $request->customer_name,
                'customer_mobile' => $request->customer_mobile,
                'status'         => 1
            ]);
        }

        return redirect()
            ->route('spot-bookings.index')
            ->with('message', 'Spot booking saved successfully!');
    }


    /**
     * Display the specified resource.
     */
    public function show(SpotBooking $spotBooking)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SpotBooking $spotBooking)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SpotBooking $spotBooking)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SpotBooking $spotBooking)
    {
        //
    }
}

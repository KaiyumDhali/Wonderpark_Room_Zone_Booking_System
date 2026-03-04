<?php

namespace App\Http\Controllers\frontend;

use App\Models\cr;
use Illuminate\Http\Request;
use App\Models\FrontendModel;
use App\Models\Customer;
use App\Models\Booking;
use App\Models\CompanySetting;
use App\Models\Slider;
use App\Models\Gallery;
use App\Models\FrontendBookings;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FrontendController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $galleryImages = Gallery::all(); // Fetch all gallery images
        $sliders = Slider::all(); 
        $company=CompanySetting::first();
    $booking = Booking::with(['room'])->get();
    
            //  dd($booking);
    
        
        // Fetch all rooms from the database
        $rooms = FrontendModel::with('details')->limit(3)->get();

        return view('pages.frontend.index.index', compact('rooms','booking','sliders','galleryImages','company'));
    }
    public function roomview()
    {
        // Fetch rooms with their detailed information
        $rooms = FrontendModel::with('details')->get();
       
        $company=CompanySetting::first();
        return view('pages.frontend.index.room', compact('rooms','company'));
    }
    public function aboutview()
    {
        $company=CompanySetting::first();
        return view('pages.frontend.index.about',compact('company'));
    }

    public function contactview()
    {
        $company=CompanySetting::first();
        $contact = CompanySetting::first();
        return view('pages.frontend.index.contact',compact('contact','company'));
    }
    public function roomdetails($id)
    {
        $rooms = FrontendModel::with('details')->findOrFail($id);
        $company=CompanySetting::first();
        // Fetch and transform bookings
        $bookings = FrontendBookings::where('room_id', $id)
            ->get(['check_in_date', 'check_out_date'])
            ->map(function ($booking) {
                return [
                    'start' => $booking->check_in_date,
                    'end' => \Carbon\Carbon::parse($booking->check_out_date)->subDay()->format('Y-m-d'), // Include departure day
                ];
            });

            
    
        // Pass room details and availability to the view
        return view('pages.frontend.index.roomdetails', compact('rooms', 'bookings','company'));
    }
    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(cr $cr)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(cr $cr)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, cr $cr)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(cr $cr)
    {
        //
    }
    public function search(Request $request)
    {
        $company=CompanySetting::first();
        $validatedData = $request->validate([
            'check_in_date' => ['required', 'date'],
            'check_out_date' => ['required', 'date', 'after:check_in_date'],
            'capacity' => ['nullable', 'integer'], // Optional capacity filter
        ]);
    
        $roomsQuery = FrontendModel::with('details')
            ->where('status', 1)
            ->whereDoesntHave('booking', function (Builder $query) use ($validatedData) {
                $query->where(function ($q) use ($validatedData) {
                    $q->where('check_in_date', '<', $validatedData['check_out_date'])
                      ->where('check_out_date', '>', $validatedData['check_in_date']);
                });
            });
    
        // Apply capacity filter only if it's provided
        if (!empty($validatedData['capacity'])) {
            $roomsQuery->where('capacity', '>=', $validatedData['capacity']);
        }
    
        $rooms = $roomsQuery->get();
        
        $searched = true;
        $fields = $validatedData;
    
        return view('pages.frontend.index.room', compact('rooms', 'searched', 'fields','company'));
    }
    
    
}

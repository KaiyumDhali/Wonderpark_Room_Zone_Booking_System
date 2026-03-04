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
use App\Models\Room;
use App\Models\FrontendBookings;
use App\Models\AboutUs;
use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Offer;
use App\Models\Video;
use App\Models\Page;
use App\Models\Ride;
use App\Models\Team;
use App\Models\Spot;
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
        $galleryImages = Gallery::all();
        $sliders = Slider::all();
        $company = CompanySetting::first();
        $booking = Booking::with(['room'])->get();
        $blogs = Blog::get();
        $gallery = Gallery::latest()->take(15)->get();
        $gallery_footer = Gallery::orderBy('id', 'asc')->take(6)->get();
        $offer = Offer::where('status', 1)->orderBy('created_at', 'desc')->first();
        //  dd($offer);

        // Fetch all rooms from the database
        // $rooms = FrontendModel::with('details')->limit(3)->get();

        // $rooms = Room::with('room_type')->paginate(6);

        $rooms = Room::with('room_type')
            ->orderBy('room_order', 'ASC')
            ->paginate(10);

        return view('pages.frontend.home', compact('rooms', 'booking', 'sliders', 'galleryImages', 'company', 'blogs', 'gallery', 'offer', 'gallery_footer'));
    }
    
    public function roomview()
    {
        // Fetch rooms with their detailed information
        $rooms = Room::paginate(6);

        $gallery_footer = Gallery::orderBy('id', 'asc')->take(6)->get();
        $company = CompanySetting::first();
        return view('pages.frontend.index.room', compact('rooms', 'company', 'gallery_footer'));
    }



    // public function room()
    // {
    //     $rooms = Room::paginate(6);
    //     $company = CompanySetting::first();
    //     return view('pages.frontend.room', compact('rooms', 'company'));
    // }

    public function room()
    {
        // $rooms = Room::with('room_type')->paginate(10);
        $rooms = Room::with('room_type')
            ->orderBy('room_order', 'ASC')
            ->paginate(10);

        $company = CompanySetting::first();
        $banner_image = Page::where('status', '=', 1)->where('id', 6)->first();
        $gallery_footer = Gallery::orderBy('id', 'asc')->take(6)->get();
        return view('pages.frontend.room', compact('rooms', 'company', 'banner_image', 'gallery_footer'));
    }


    public function roomDetails($id)
    {
        $rooms = FrontendModel::with('details')->findOrFail($id);
        $company = CompanySetting::first();

        // Fetch bookings where status is not 'canceled' (Booking_status != 2)
        $bookings = FrontendBookings::where('room_id', $id)
            ->where('Booking_status', '!=', 2) // Exclude canceled bookings
            ->get(['check_in_date', 'check_out_date'])
            ->map(function ($booking) {
                return [
                    'start' => $booking->check_in_date,
                    'end' => \Carbon\Carbon::parse($booking->check_out_date)->format('Y-m-d'), // Include departure day
                ];
            });

        $banner_image = Page::where('status', '=', 1)->where('id', 6)->first();

        $gallery_footer = Gallery::orderBy('id', 'asc')->take(6)->get();

        // Pass room details and availability to the view
        return view('pages.frontend.room_details', compact('rooms', 'bookings', 'company', 'banner_image', 'gallery_footer'));
    }


    // delete below 
    public function room_details($id)
    {
        $rooms = FrontendModel::with('details')->findOrFail($id);
        $company = CompanySetting::first();

        // Fetch bookings where status is not 'canceled' (Booking_status != 2)
        $bookings = FrontendBookings::where('room_id', $id)
            ->where('Booking_status', '!=', 2) // Exclude canceled bookings
            ->get(['check_in_date', 'check_out_date'])
            ->map(function ($booking) {
                return [
                    'start' => $booking->check_in_date,
                    'end' => \Carbon\Carbon::parse($booking->check_out_date)->format('Y-m-d'), // Include departure day
                ];
            });


        $gallery_footer = Gallery::orderBy('id', 'asc')->take(6)->get();
        // Pass room details and availability to the view
        return view('pages.frontend.index.roomdetails', compact('rooms', 'bookings', 'company', 'gallery_footer'));
    }


    public function about()
    {
        $company = CompanySetting::first();
        $about_us = AboutUs::orderby('id', 'asc')->get();
        $teams = Team::orderby('id', 'asc')->get();
        $banner_image = Page::where('status', '=', 1)->where('id', 1)->first();
        $gallery_footer = Gallery::orderBy('id', 'asc')->take(6)->get();
        return view('pages.frontend.about_us', compact('company', 'about_us', 'banner_image', 'teams', 'gallery_footer'));
    }

    public function photo()
    {
        $company = CompanySetting::first();
        $gallery = Gallery::get();
        // dd($gallery);
        $banner_image = Page::where('status', '=', 1)->where('id', 2)->first();
        $gallery_footer = Gallery::orderBy('id', 'asc')->take(6)->get();
        return view('pages.frontend.photo', compact('company', 'gallery', 'banner_image', 'gallery_footer'));
    }


    public function video()
    {
        $company = CompanySetting::first();
        $video = Video::get();
        $banner_image = Page::where('status', '=', 1)->where('id', 3)->first();
        $gallery_footer = Gallery::orderBy('id', 'asc')->take(6)->get();
        return view('pages.frontend.video', compact('company', 'video', 'banner_image', 'gallery_footer'));
    }

    public function rides()
    {
        $company = CompanySetting::first();
        $gallery = Gallery::get();
        $rides = Ride::get();
        $banner_image = Page::where('status', '=', 1)->where('id', 7)->first();
        $gallery_footer = Gallery::orderBy('id', 'asc')->take(6)->get();
        return view('pages.frontend.rides', compact('company', 'gallery', 'banner_image', 'rides', 'gallery_footer'));
    }


    public function blog()
    {
        $company = CompanySetting::first();
        $blogs = Blog::get();
        $banner_image = Page::where('status', '=', 1)->where('id', 5)->first();
        $gallery_footer = Gallery::orderBy('id', 'asc')->take(6)->get();
        return view('pages.frontend.blog', compact('company', 'blogs', 'banner_image', 'gallery_footer'));
    }


    public function blogDetails($id)
    {
        $company = CompanySetting::first();
        $blog = Blog::where('id', $id)->first();
        $banner_image = Page::where('status', '=', 1)->where('id', 5)->first();
        $gallery_footer = Gallery::orderBy('id', 'asc')->take(6)->get();
        return view('pages.frontend.blog_details', compact('company', 'blog', 'banner_image', 'gallery_footer'));
    }


    public function spot()
    {
        $company = CompanySetting::first();
        $spots = Spot::orderBy('spot_order', 'asc')->get();

        $banner_image = Page::where('status', '=', 1)->where('id', 8)->first();
        $gallery_footer = Gallery::orderBy('id', 'asc')->take(6)->get();
        return view('pages.frontend.spot', compact('company', 'spots', 'banner_image', 'gallery_footer'));
    }


    public function spotDetails($id)
    {
        $company = CompanySetting::first();
        $spot = Spot::with('spot_detail')->where('id', $id)->first();

        // dd($spot);

        $banner_image = Page::where('status', '=', 1)->where('id', 8)->first();
        $gallery_footer = Gallery::orderBy('id', 'asc')->take(6)->get();
        return view('pages.frontend.spot_details', compact('company', 'spot', 'banner_image', 'gallery_footer'));
    }


    public function contacts()
    {
        $company = CompanySetting::first();
        $contact = CompanySetting::first();
        $banner_image = Page::where('status', '=', 1)->where('id', 4)->first();
        $gallery_footer = Gallery::orderBy('id', 'asc')->take(6)->get();
        return view('pages.frontend.contacts', compact('contact', 'company', 'banner_image', 'gallery_footer'));
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
        $company = CompanySetting::first();
        $validatedData = $request->validate([
            'check_in_date' => ['required', 'date'],
            'check_out_date' => ['required', 'date'],
            'capacity' => ['nullable', 'integer'], // Optional capacity filter
        ]);

        $roomsQuery = FrontendModel::with('details')
            ->where('status', 1)
            ->where(function ($query) use ($validatedData) {
                $query->whereDoesntHave('booking', function (Builder $q) use ($validatedData) {
                    $q->where('booking_status', '!=', 2) // Ignore rooms with booking_status = 2
                        ->where(function ($q) use ($validatedData) {
                            $q->where('check_in_date', '<=', $validatedData['check_out_date'])
                                ->where('check_out_date', '>=', $validatedData['check_in_date']);
                        });
                });
            });

        // Apply capacity filter if provided
        if (!empty($validatedData['capacity'])) {
            $roomsQuery->where('capacity', '>=', $validatedData['capacity']);
        }

        // Paginate the results
        $rooms = $roomsQuery->paginate(6);

        // Passing data to the view
        $searched = true;
        $fields = $validatedData;

        $gallery_footer = Gallery::orderBy('id', 'asc')->take(6)->get();

        return view('pages.frontend.index.room', compact('rooms', 'searched', 'fields', 'company', 'gallery_footer'));
    }
}

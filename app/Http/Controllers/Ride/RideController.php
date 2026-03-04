<?php

namespace App\Http\Controllers\Ride;

use App\Models\Ride;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;



class RideController extends Controller
{

    public function index()
    {
        $rides = Ride::orderby('id', 'asc')->get();
        return view('pages.ride.index', compact('rides'));
    }


    public function create()
    {
        return view('pages.ride.create');
    }

    public function store(Request $request)
    {

        $ride = new Ride();

        if ($request->hasfile('image')) {
            $request->validate([
                'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            ]);
            $image = $request->file('image');
            $randomNumber = mt_rand(100000, 999999);
            $extension = $image->getClientOriginalExtension();
            $name = date('d-m-Y-H-i-s') . '-' . $randomNumber . '.' . $extension;
            $image->storeAs('public/images/ride', $name);
            $ride->image = 'images/ride/' . $name;
        }

        $ride->title = $request->title;
        $ride->description = $request->description;
        $ride->price = $request->price;
        $ride->status = $request->status;

        //        echo '<pre>';
        //        print_r($Ride);
        //        echo '</pre>';
        //        die();

        $ride->save();

        return redirect()->route('rides.index')->with('success', 'Created successfully.');
    }



    public function show($id)
    {
        $data['ride'] = Ride::find($id);
        return view('pages.ride.show', $data);
    }


    public function edit($id)
    {
        $data['rides'] = Ride::find($id);
        return view('pages.ride.edit', $data);
    }


    public function update(Request $request, $id)
    {
        $ride = Ride::find($id);

        if ($request->hasfile('image')) {
            $request->validate([
                'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            ]);
            $image = $request->file('image');
            $randomNumber = mt_rand(100000, 999999);
            $extension = $image->getClientOriginalExtension();
            $name = date('d-m-Y-H-i-s') . '-' . $randomNumber . '.' . $extension;
            $image->storeAs('public/images/ride', $name);
            $ride->image = 'images/ride/' . $name;
        }

        $ride->title = $request->title;
        $ride->description = $request->description;
        $ride->price = $request->price;
        $ride->status = $request->status;
        $ride->save();

        return redirect()->route('rides.index')->with('success', 'Created sucessfully.');
    }


    public function imageDestroy($id)
    {
        $ride = Ride::find($id);

        if ($ride->image != null) {
            // Delete from storage/app/public
            Storage::disk('public')->delete($ride->image);

            $ride->image = null;
            $ride->save();
        }

        return redirect()->route('rides.edit', $ride->id)
            ->with('success', 'Image successfully deleted from storage.');
    }


    public function destroy($id)
    {
        $ride = Ride::find($id);
        if ($ride->image != Null) {
            Storage::disk('public')->delete($ride->image);
        }

        $ride->delete();
        return redirect()->route('rides.index')->with('success', 'Successfully deleted.');
    }
}

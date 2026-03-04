<?php

namespace App\Http\Controllers\Offer;

use App\Models\Offer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class OfferController extends Controller
{

    public function index()
    {
        $offers = Offer::orderby('id', 'asc')->get();
        return view('pages.offer.index', compact('offers'));
    }


    public function create()
    {
        return view('pages.offer.create');
    }

    public function store(Request $request)
    {

        // dd($request->all());

        $offer = new Offer();

        if ($request->hasfile('image')) {
            $request->validate([
                'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            ]);
            $image = $request->file('image');
            $randomNumber = mt_rand(100000, 999999);
            $extension = $image->getClientOriginalExtension();
            $name = date('d-m-Y-H-i-s') . '-' . $randomNumber . '.' . $extension;
            $image->storeAs('public/images/offer', $name);
            $offer->image = 'images/offer/' . $name;
        }

        $offer->title = $request->title;
        $offer->description = $request->description;
        $offer->price = $request->price;
        $offer->status = $request->status;

        //        echo '<pre>';
        //        print_r($Offer);
        //        echo '</pre>';
        //        die();

        $offer->save();

        return redirect()->route('offers.index')->with('success', 'Created successfully.');
    }



    public function show($id)
    {
        $data['offer'] = Offer::find($id);
        return view('pages.offer.show', $data);
    }


    public function edit($id)
    {
        $data['offers'] = Offer::find($id);
        return view('pages.offer.edit', $data);
    }


    public function update(Request $request, $id)
    {
        $offer = Offer::find($id);

        if ($request->hasfile('image')) {
            $request->validate([
                'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            ]);
            $image = $request->file('image');
            $randomNumber = mt_rand(100000, 999999);
            $extension = $image->getClientOriginalExtension();
            $name = date('d-m-Y-H-i-s') . '-' . $randomNumber . '.' . $extension;
            $image->storeAs('public/images/offer', $name);
            $offer->image = 'images/offer/' . $name;
        }
        

        $offer->title = $request->title;
        $offer->description = $request->description;
        $offer->price = $request->price;
        $offer->status = $request->status;
        $offer->save();

        return redirect()->route('offers.index')->with('success', 'Created sucessfully.');
    }


    public function imageDestroy($id)
    {
        $offer = Offer::find($id);

        if ($offer->image != null) {
            // Delete from storage/app/public
            Storage::disk('public')->delete($offer->image);

            $offer->image = null;
            $offer->save();
        }

        return redirect()->route('offers.edit', $offer->id)
            ->with('success', 'Image successfully deleted from storage.');
    }


    //Banner Image Storage Destroy

    //    public function bannerDestroy($id) {
    //        $Offer = Offer::find($id);
    //        if ($Offer->banner_image != Null) {
    //            // dd($Offer->image);
    //            Storage::delete($Offer->banner_image);
    //            $Offer->banner_image = Null;
    //            $Offer->save();
    //        }
    //        return redirect()->route('admin.about_us.edit', $Offer->id)->with('success', 'Image storage successfully deleted.');
    //    }




    public function destroy($id)
    {
        $offer = Offer::find($id);
        if ($offer->image != Null) {
            Storage::disk('public')->delete($offer->image);
        }
        // if ($offer->banner_image != Null) {
        //     Storage::delete($offer->banner_image);
        // }
        $offer->delete();
        return redirect()->route('offers.index')->with('success', 'Successfully deleted.');
    }
}

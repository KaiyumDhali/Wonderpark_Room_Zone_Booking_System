<?php

namespace App\Http\Controllers\AboutUs;

use App\Http\Controllers\Controller;
use App\Models\AboutUs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AboutUsController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function home()
    {
        $about_us = AboutUs::orderby('id', 'asc')->first();
        return view('home.about-us', compact('about_us'));
    }

    public function index()
    {
        $about_us = AboutUs::orderby('id', 'asc')->get();
        return view('pages.about_us.index', compact('about_us'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.about_us.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $aboutUs = new AboutUs();

        if ($request->hasfile('image')) {
            $request->validate([
                'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg,webp|max:2048',
            ]);
            $image = $request->file('image');
            $randomNumber = mt_rand(100000, 999999);
            $extension = $image->getClientOriginalExtension();
            $name = date('d-m-Y-H-i-s') . '-' . $randomNumber . '.' . $extension;
            $image->storeAs('public/images/about_us', $name);
            $aboutUs->image = 'images/about_us/' . $name;
        }

        $aboutUs->title = $request->title;
        $aboutUs->description = $request->description;
        $aboutUs->status = $request->status;

        //        echo '<pre>';
        //        print_r($aboutUs);
        //        echo '</pre>';
        //        die();

        $aboutUs->save();

        return redirect()->route('about_us.index')->with('success', 'Created successfully.');
    }



    public function show($id)
    {
        $data['about_us'] = AboutUs::find($id);
        return view('pages.about_us.show', $data);
    }


    public function edit($id)
    {
        $data['about_us'] = AboutUs::find($id);
        return view('pages.about_us.edit', $data);
    }


    public function update(Request $request, $id)
    {
        $aboutUs = AboutUs::find($id);

        if ($request->hasfile('image')) {
            $request->validate([
                'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg,webp|max:2048',
            ]);
            $image = $request->file('image');
            $randomNumber = mt_rand(100000, 999999);
            $extension = $image->getClientOriginalExtension();
            $name = date('d-m-Y-H-i-s') . '-' . $randomNumber . '.' . $extension;
            $image->storeAs('public/images/about_us', $name);
            $aboutUs->image = 'images/about_us/' . $name;
        }

        $aboutUs->title = $request->title;
        $aboutUs->description = $request->description;
        $aboutUs->status = $request->status;
        $aboutUs->save();

        return redirect()->route('about_us.index')->with('success', 'Created sucessfully.');
    }


    public function imageDestroy($id)
    {
        $aboutUs = AboutUs::find($id);

        if ($aboutUs->image != null) {
            // Delete from storage/app/public
            Storage::disk('public')->delete($aboutUs->image);

            $aboutUs->image = null;
            $aboutUs->save();
        }

        return redirect()->route('about_us.edit', $aboutUs->id)
            ->with('success', 'Image successfully deleted from storage.');
    }


    public function destroy($id)
    {
        $aboutUs = AboutUs::find($id);
        if ($aboutUs->image != Null) {
            Storage::disk('public')->delete($aboutUs->image);
        }
        $aboutUs->delete();
        return redirect()->route('about_us.index')->with('success', 'Successfully deleted.');
    }
}

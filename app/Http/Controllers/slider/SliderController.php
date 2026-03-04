<?php

namespace App\Http\Controllers\Slider;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\Request;

class SliderController extends Controller
{
    public function index()
    {
        $sliders = Slider::all();
        return view('pages.slider.add', compact('sliders'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'image.*' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('image')) {
            foreach ($request->file('image') as $file) {
                $name = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $imagePath = $file->storeAs('public/images/sliders', $name);

                Slider::create([
                    'slider_image' => str_replace('public/', '', $imagePath),
                ]);
            }
        }

        return back()->with('success', 'Sliders uploaded successfully!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'slider_image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $slider = Slider::findOrFail($id);

        if ($request->hasFile('slider_image')) {
            $name = time() . '_' . uniqid() . '.' . $request->file('slider_image')->getClientOriginalExtension();
            $imagePath = $request->file('slider_image')->storeAs('public/images/sliders', $name);

            // Optionally, delete the old image
            if ($slider->slider_image && file_exists(public_path('storage/' . $slider->slider_image))) {
                unlink(public_path('storage/' . $slider->slider_image));
            }

            $slider->update([
                'slider_image' => str_replace('public/', '', $imagePath),
            ]);
        }

        return back()->with('success', 'Slider updated successfully!');
    }


    public function destroy($id)
    {
        $slider = Slider::findOrFail($id);

        // Delete the image file from storage
        if ($slider->slider_image && file_exists(public_path('storage/' . $slider->slider_image))) {
            unlink(public_path('storage/' . $slider->slider_image));
        }

        // Delete the slider record from the database
        $slider->delete();

        return back()->with('success', 'Slider deleted successfully!');
    }
}

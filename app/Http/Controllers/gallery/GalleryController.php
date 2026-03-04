<?php

namespace App\Http\Controllers\gallery;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Gallery;

class GalleryController extends Controller
{
    public function index()
    {
        $gallerys = Gallery::all();
        return view('pages.gallery.index', compact('gallerys'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'image.*' => 'required|image|mimes:jpeg,png,jpg|max:20480',
        ]);

        if ($request->hasFile('image')) {
            foreach ($request->file('image') as $file) {
                $name = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $imagePath = $file->storeAs('public/images/gallery', $name);

                // Save to the correct table and column
                Gallery::create([
                    'image' => 'images/gallery/' . $name,
                ]);
            }
        }

        return back()->with('success', 'Images uploaded successfully!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg|max:20480',
        ]);

        $gallery = Gallery::findOrFail($id);

        if ($request->hasFile('image')) {
            // Generate a unique name for the new image
            $name = time() . '_' . uniqid() . '.' . $request->file('image')->getClientOriginalExtension();
            $imagePath = $request->file('image')->storeAs('public/images/gallery', $name);

            // Delete the old image file if it exists
            if ($gallery->image && file_exists(public_path('storage/' . $gallery->image))) {
                unlink(public_path('storage/' . $gallery->image));
            }

            // Update the database with the new image path
            $gallery->update([
                'image' => 'images/gallery/' . $name,
            ]);
        }

        return back()->with('success', 'Gallery image updated successfully!');
    }


    public function destroy($id)
    {
        $gallery = Gallery::findOrFail($id);

        // Delete the image file from storage
        if ($gallery->image && file_exists(public_path('storage/' . $gallery->image))) {
            unlink(public_path('storage/' . $gallery->image));
        }

        // Delete the gallery record from the database
        $gallery->delete();

        return back()->with('success', 'Gallery image deleted successfully!');
    }
}

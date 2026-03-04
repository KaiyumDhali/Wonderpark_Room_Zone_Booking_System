<?php

namespace App\Http\Controllers\Spot;


use App\Models\Spot;
use App\Models\SpotDetail;
use App\Models\SpotFacility;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;



class SpotController extends Controller
{



  public function index()
{
    $spots = Spot::with('facilities')->orderBy('spot_order', 'asc')->get();
    return view('pages.spot.index', compact('spots'));
}



    public function create()
    {
        return view('pages.spot.create');
    }

   public function store(Request $request)
{
    // 🔹 Validation
    $request->validate([
        'title' => 'required|string|max:255',
        'area_size' => 'required',
        'max_capacity' => 'required',
        'description' => 'required',
        'price' => 'required|numeric',
        'regular_price' => 'required|numeric',
        'status' => 'required|boolean',
        'image' => 'nullable|image|mimes:jpg,jpeg,png|max:20480',
        'spot_gallery_image.*' => 'nullable|image|mimes:jpg,jpeg,png|max:20480',
        'facilities.*' => 'nullable|string|max:255',
    ]);

    DB::beginTransaction();

    try {
        $input = $request->only([
            'title',
            'area_size',
            'max_capacity',
            'description',
            'price',
            'regular_price',
            'status'
        ]);
    $input['spot_order'] = Spot::max('spot_order') + 1;
        // 🔹 Thumbnail Image Upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name = time() . '-' . mt_rand(100000, 999999) . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/images/spot', $name);
            $input['image'] = 'images/spot/' . $name;
        }

        // 🔹 Create Spot
        $spot = Spot::create($input);

        // 🔹 Gallery Images
        if ($request->hasFile('spot_gallery_image')) {
            foreach ($request->file('spot_gallery_image') as $galleryImage) {
                $name2 = time() . '-' . mt_rand(100000, 999999) . '.' . $galleryImage->getClientOriginalExtension();
                $galleryImage->storeAs('public/images/spot', $name2);

                SpotDetail::create([
                    'spot_id' => $spot->id,
                    'image_path' => 'images/spot/' . $name2,
                ]);
            }
        }

        // 🔹 Facilities (Single / Multiple)
        if ($request->facilities) {
            foreach ($request->facilities as $facility) {
                if (!empty($facility)) {
                    SpotFacility::create([
                        'spot_id' => $spot->id,
                        'facility' => $facility,
                    ]);
                }
            }
        }

        DB::commit();

        return redirect()
            ->route('spots.index')
            ->with('success', 'Spot created successfully.');

    } catch (\Exception $e) {
        DB::rollBack();

        return redirect()
            ->back()
            ->withErrors(['error' => $e->getMessage()])
            ->withInput();
    }
}


public function show($id)
{
    $spot = Spot::with(['spot_detail', 'facilities'])->findOrFail($id);
    return view('pages.spot.show', compact('spot'));
}



   public function edit($id)
{
    $data['spots'] = Spot::with(['spot_detail', 'facilities'])->findOrFail($id);
    return view('pages.spot.edit', $data);
}



    public function update(Request $request, $id)
{
    $request->validate([
        'title' => 'required',
        'area_size' => 'required',
        'max_capacity' => 'required',
        'description' => 'required',
        'price' => 'required|numeric',
        'regular_price' => 'required|numeric',
        'status' => 'required',
        'spot_order' => 'required|integer|min:1',
        'facilities.*' => 'nullable|string|max:255',
    ]);

    DB::beginTransaction();

    try {
        $spot = Spot::findOrFail($id);


        $oldOrder = $spot->spot_order;
$newOrder = $request->spot_order;

if ($newOrder != $oldOrder) {

    if ($newOrder > $oldOrder) {
        // 1 → 3 (down)
        Spot::whereBetween('spot_order', [$oldOrder + 1, $newOrder])
            ->decrement('spot_order');
    } else {
        // 3 → 1 (up)
        Spot::whereBetween('spot_order', [$newOrder, $oldOrder - 1])
            ->increment('spot_order');
    }

    $spot->spot_order = $newOrder;
}

        // 🔹 Thumbnail Image
        if ($request->hasFile('image')) {
            if ($spot->image) {
                Storage::disk('public')->delete($spot->image);
            }

            $image = $request->file('image');
            $name = time() . '-' . rand(100000,999999) . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/images/spot', $name);
            $spot->image = 'images/spot/' . $name;
        }

        // 🔹 Spot update
        $spot->update([
            'title' => $request->title,
            'area_size' => $request->area_size,
            'max_capacity' => $request->max_capacity,
            'description' => $request->description,
            'price' => $request->price,
            'regular_price' => $request->regular_price,
            'status' => $request->status,
        ]);

        // 🔹 Gallery Images (add new)
        if ($request->hasFile('spot_gallery_image')) {
            foreach ($request->file('spot_gallery_image') as $galleryImage) {
                $name2 = time() . '-' . rand(100000,999999) . '.' . $galleryImage->getClientOriginalExtension();
                $galleryImage->storeAs('public/images/spot', $name2);

                SpotDetail::create([
                    'spot_id' => $spot->id,
                    'image_path' => 'images/spot/' . $name2,
                ]);
            }
        }

        // 🔹 Facilities Update
        SpotFacility::where('spot_id', $spot->id)->delete();

        if ($request->facilities) {
            foreach ($request->facilities as $facility) {
                if (!empty($facility)) {
                    SpotFacility::create([
                        'spot_id' => $spot->id,
                        'facility' => $facility,
                    ]);
                }
            }
        }

        DB::commit();

        return redirect()->route('spots.index')->with('success', 'Spot updated successfully.');

    } catch (\Exception $e) {
        DB::rollBack();
        return back()->withErrors($e->getMessage());
    }
}



    public function imageDestroy($id)
    {
        $spot = Spot::find($id);

        if ($spot->image != null) {
            // Delete from storage/app/public
            Storage::disk('public')->delete($spot->image);

            $spot->image = null;
            $spot->save();
        }

        return redirect()->route('spots.edit', $spot->id)
            ->with('success', 'Image successfully deleted from storage.');
    }


    // Spot details image destroy
    public function galleryImageDestroy($id)
    {
        $spotDetail = SpotDetail::find($id);

        if ($spotDetail->image_path != Null) {
            Storage::disk('public')->delete($spotDetail->image_path);
        }

        $spotDetail->delete();

        return redirect()->route('spots.index', $spotDetail->id)->with([
            'message' => 'File successfully deleted. !',
            'alert-type' => 'danger'
        ]);
    }

    public function destroy($id)
    {
        $spot = Spot::with('spot_detail')->find($id);

        // Delete spot main image
        if ($spot->image != null) {
            Storage::disk('public')->delete($spot->image);
        }

        // Delete all spot_detail images
        foreach ($spot->spot_detail as $detail) {
            if ($detail->image_path != null && Storage::disk('public')->exists($detail->image_path)) {
                Storage::disk('public')->delete($detail->image_path);
            }
        }

        // Optionally delete spot_detail records first if using foreign key constraints
        $spot->spot_detail()->delete();

        // Finally delete the spot
        $spot->delete();

        return redirect()->route('spots.index')->with('success', 'Successfully deleted.');
    }
}

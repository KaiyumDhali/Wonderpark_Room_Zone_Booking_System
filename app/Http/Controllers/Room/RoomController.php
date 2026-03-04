<?php

namespace App\Http\Controllers\Room;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\RoomDetail;
use App\Models\RoomType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rooms = Room::orderBy('room_order', 'ASC')->get();
        $allRoomType = RoomType::pluck('type_name', 'id')->all();
        return view('pages.room.room.index', compact('rooms', 'allRoomType'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Return a view for creating a new room
        $allRoomType = RoomType::pluck('type_name', 'id')->all();
        return view('pages.room.room.room-add', compact('allRoomType'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            'room_number' => [
                'required',
                'string',
                'max:255',
                Rule::unique('rooms')->ignore($request->id),
            ],
            'thumbnail_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Validate thumbnail
            'image.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048' // Validate gallery images
        ]);

        $input = $request->all();

        // Handle thumbnail image
        if ($request->hasFile('thumbnail_image')) {
            $thumbnail = $request->file('thumbnail_image');
            $thumbnailName = now()->format('d-m-Y-H-i-s') . '-' . $thumbnail->getClientOriginalName();
            $thumbnailPath = $thumbnail->storeAs('public/images/room', $thumbnailName);

            // Save the path to the database
            $input['thumbnail_image'] = str_replace('public/', '', $thumbnailPath);
        }

        // Create Room
        $room = Room::create($input);

        // Handle multiple gallery images
        if ($request->hasFile('image')) {
            foreach ($request->file('image') as $image) {
                $name = now()->format('d-m-Y-H-i-s') . '-' . $image->getClientOriginalName();
                $imagePath = $image->storeAs('public/images/room', $name);

                RoomDetail::create([
                    'room_id' => $room->id,
                    'image_path' => str_replace('public/', '', $imagePath),
                ]);
            }
        }

        return redirect()->route('room.index')->with([
            'message' => 'Room created successfully!',
            'alert-type' => 'success'
        ]);
    }


    /**
     * Display the specified resource.
     */
    public function show(Room $room)
    {
        return view('pages.room.room.room-show', compact('room'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // dd($id);
        $allRoomType = RoomType::pluck('type_name', 'id')->all();
        $room = Room::with('roomimage')->find($id);
        // dd($room);
        return view('pages.room.room.room-update', compact('room', 'allRoomType'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'roomtype_id'       => 'required|integer',
            'room_order'        => 'nullable|integer',
            'room_number'       => 'required|string|max:255',
            'room_name'         => 'required|string|max:255',
            'floor'             => 'required|integer',
            'capacity'          => 'required|integer',
            'price_per_night'   => 'required|numeric',
            'description'       => 'nullable|string',
            'status'            => 'required|in:0,1',
            'thumbnail_image'   => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
        ]);

        $room = Room::findOrFail($id);

        // Update fields
        $room->roomtype_id = $request->roomtype_id;
        $room->room_order = $request->room_order;
        $room->room_number = $request->room_number;
        $room->room_name = $request->room_name;
        $room->floor = $request->floor;
        $room->capacity = $request->capacity;
        $room->price_per_night = $request->price_per_night;
        $room->description = $request->description;
        $room->status = $request->status;



        if ($request->hasFile('thumbnail_image')) {
            // Delete old image if it exists
            if ($room->thumbnail_image && Storage::exists('public/' . $room->thumbnail_image)) {
                Storage::delete('public/' . $room->thumbnail_image);
            }

            $image = $request->file('thumbnail_image');
            $name = now()->format('d-m-Y-H-i-s') . '-' . $image->getClientOriginalName();
            $image_path_folder = $image->storeAs('public/images/room', $name);

            // Save only the relative path (without 'public/')
            $room->thumbnail_image = 'images/room/' . $name;
        }

        $room->save();

        if ($request->hasFile('image')) {
            foreach ($request->file('image') as $image) {
                $name2 = now()->format('d-m-Y-H-i-s') . '-' . $image->getClientOriginalName();
                $imagePath = $image->storeAs('public/images/room', $name2);

                RoomDetail::create([
                    'room_id' => $room->id,
                    'image_path' => 'images/room/' . $name2,
                ]);
            }
        }

        return redirect()->route('room.index')->with(['message' => 'Successfully updated!', 'alert-type' => 'info']);
    }


    // public function update(Request $request, $id)
    // {

    //     dd($request->all());

    //     $request->validate([
    //         'room_number' => [
    //             'required',
    //             'string',
    //             'max:255',
    //             // Rule::unique('rooms')->ignore($id),
    //             Rule::unique('rooms', 'room_number')->ignore($id, 'id'),
    //         ],
    //     ]);

    //     $room = Room::find($id);

    //     // Handle thumbnail image
    //     // if ($request->hasFile('thumbnail_image')) {
    //     //     $thumbnail = $request->file('thumbnail_image');
    //     //     $thumbnailName = now()->format('d-m-Y-H-i-s') . '-' . $thumbnail->getClientOriginalName();
    //     //     $thumbnailPath = $thumbnail->storeAs('public/images/room', $thumbnailName);
    //     //     // Save the path to the database
    //     //     $room['thumbnail_image'] = $thumbnailPath;
    //     // }


    //     if ($request->hasfile('thumbnail_image')) {
    //         $request->validate([
    //             'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
    //         ]);
    //         $image = $request->file('thumbnail_image');
    //         $name = date('d-m-Y-H-i-s') . '-' . $image->getClientOriginalName();
    //         $image_path_folder = $image->storeAs('public/images/room', $name);
    //         $room->image = 'images/room/' . $name;
    //     }

    //     $room->update($request->all());



    //     if ($request->hasFile('image')) {
    //         foreach ($request->file('image') as $image) {
    //             $name = now()->format('d-m-Y-H-i-s') . '-' . $image->getClientOriginalName();
    //             $imagePath = $image->storeAs('public/images/room', $name);

    //             RoomDetail::create([
    //                 'room_id' => $room->id,
    //                 'image_path' => $imagePath,
    //             ]);
    //         }
    //     }

    //     return redirect()->route('room.index')->with([
    //         'message' => 'Successfully updated!',
    //         'alert-type' => 'info'
    //     ]);
    // }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Room $room)
    {
        $room->delete();

        return back()->with([
            'message' => 'Successfully deleted!',
            'alert-type' => 'danger'
        ]);
    }

    // Room details image destroy
    public function imageDestroy($id)
    {
        $roomDetail = RoomDetail::find($id);

        // dd(Storage::url($galleryDetail->image_path));

        // if ($roomDetail->image_path != Null) {
        Storage::disk('public')->delete($roomDetail->image_path);
        $roomDetail->delete();
        // } else {
        //     //            dd('File does not exists.');
        // }
        return redirect()->route('room.index', $roomDetail->room_id)->with([
            'message' => 'File successfully deleted. !',
            'alert-type' => 'danger'
        ]);
    }

    // Room Thumbnail Image destroy
    public function thumbnailImageDestroy($id)
    {
        $room = Room::find($id);

        if ($room->thumbnail_image != Null) {
            Storage::disk('public')->delete($room->thumbnail_image);
            $room->thumbnail_image = Null;
            $room->save();
        }

        return redirect()->route('room.index', $room->room_id)->with([
            'message' => 'File successfully deleted. !',
            'alert-type' => 'danger'
        ]);
    }
}

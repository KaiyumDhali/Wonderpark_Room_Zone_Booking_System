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
        $rooms = Room::all();
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

        // dd($request->all());

        // $request->validate([
        //     'room_number' => 'required|string|max:255',
        //     // 'room_type_id' => 'required|exists:room_types,id', // Assuming a relationship with RoomType
        //     // 'status' => 'required|in:available,unavailable',
        // ]);

        $request->validate([
            'room_number' => [
                'required',
                'string',
                'max:255',
                Rule::unique('rooms')->ignore($request->id),
            ],
        ]);

        $input = $request->all();
        // dd($input);
        $room = Room::create($input);

        // Handle image uploads
        if ($request->hasFile('image')) {
            foreach ($request->file('image') as $image) {
                $name = now()->format('d-m-Y-H-i-s') . '-' . $image->getClientOriginalName();
                $imagePath = $image->storeAs('public/images/room', $name);

                RoomDetail::create([
                    'room_id' => $room->id,
                    'image_path' => $imagePath,
                ]);
            }
        }

        return redirect()->route('room.index')->with([
            'message' => 'Successfully created!',
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

        // dd($allRoomType);

        return view('pages.room.room.room-update', compact('room','allRoomType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {

        $request->validate([
            'room_number' => [
                'required',
                'string',
                'max:255',
                // Rule::unique('rooms')->ignore($id),
                Rule::unique('rooms', 'room_number')->ignore($id, 'id'),
            ],
        ]);

        $room = Room::find($id);

        // dd($room);

        $room->update($request->all());

        if ($request->hasFile('image')) {
            foreach ($request->file('image') as $image) {
                $name = now()->format('d-m-Y-H-i-s') . '-' . $image->getClientOriginalName();
                $imagePath = $image->storeAs('public/images/room', $name);

                RoomDetail::create([
                    'room_id' => $room->id,
                    'image_path' => $imagePath,
                ]);
            }
        }

        return redirect()->route('room.index')->with([
            'message' => 'Successfully updated!',
            'alert-type' => 'info'
        ]);
    }

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


    public function imageDestroy($id)
    {
        $roomDetail = RoomDetail::find($id);

        // dd(Storage::url($galleryDetail->image_path));

        // if ($roomDetail->image_path != Null) {
            Storage::delete($roomDetail->image_path);
            $roomDetail->delete();
        // } else {
        //     //            dd('File does not exists.');
        // }
        return redirect()->route('room.index', $roomDetail->room_id)->with([
            'message' => 'File successfully deleted. !',
            'alert-type' => 'danger'
        ]);
    }



}

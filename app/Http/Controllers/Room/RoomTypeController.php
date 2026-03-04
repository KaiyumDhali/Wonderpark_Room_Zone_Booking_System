<?php

namespace App\Http\Controllers\Room;

use App\Http\Controllers\Controller;
use App\Models\RoomType;
use Illuminate\Http\Request;
use validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;



class RoomTypeController extends Controller
{

    // function __construct()
    // {
    //     $this->middleware('permission:read room type|write room type|create room type', ['only' => ['index', 'show']]);
    //     $this->middleware('permission:create room type', ['only' => ['create', 'store']]);
    //     $this->middleware('permission:write room type', ['only' => ['edit', 'update', 'destroy']]);
    // }


    public function index()
    {
        $roomTypes = RoomType::all();
        return view('pages.room.room_type.index', compact('roomTypes'));
    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        // $request->validate([
        //     'type_name' => 'required|string|max:255',
        // ]);

        $request->validate([
            'type_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('room_types')->ignore($request->id),
            ],
        ]);



        $input = $request->all();
        RoomType::create($input);

        return redirect()->back()->with([
            'message' => 'Successfully created!',
            'alert-type' => 'success'
        ]);


        // return redirect()->route('room_type.index')->with([
        //     'message' => 'successfully create !',
        //     'alert-type' => 'success'
        // ]);
    }


    public function show(RoomType $roomType)
    {
        //
    }


    public function edit(RoomType $roomType)
    {
        //
    }


    public function update(Request $request, $id)
    {
        // $request->validate([
        //     'type_name' => 'required|string|max:255',
        // ]);

        $request->validate([
            'type_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('room_types')->ignore($id),
            ],
        ]);

        $roomType = RoomType::find($id);
        $roomType->type_name = $request->type_name;
        $roomType->description = $request->description;
        $roomType->status = $request->status;
        $roomType->update();

        return redirect()->route('room_type.index')->with([
            'message' => 'successfully update !',
            'alert-type' => 'info'
        ]);
    }


    public function destroy(RoomType $roomType)
    {
        $roomType->delete();
        return back()->with([
            'message' => 'successfully deleted !',
            'alert-type' => 'danger'
        ]);
    }
}

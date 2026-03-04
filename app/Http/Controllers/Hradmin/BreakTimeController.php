<?php
namespace App\Http\Controllers\hradmin;
use App\Http\Controllers\Controller;
use App\Models\BreakTime;
use Illuminate\Http\Request;
use Auth;

class BreakTimeController extends Controller
{

    function __construct() {
        $this->middleware('permission:read timetable|write timetable|create timetable', ['only' => ['index', 'show']]);
        $this->middleware('permission:create timetable', ['only' => ['create', 'store']]);
        $this->middleware('permission:write timetable', ['only' => ['edit', 'update', 'destroy']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $breakTimes = BreakTime::orderBy('id', 'desc')->get();
        return view('pages.hradmin.break_time.index', compact('breakTimes'));
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
        $request->validate([
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        $created_by = Auth::user()->name;

        $breakTime = new BreakTime();
        $breakTime->start_time = $request->input('start_time');
        $breakTime->end_time = $request->input('end_time');
        $breakTime->created_by = $created_by;
        $breakTime->save();

        return back()->with([
            'message' => 'successfully created !',
            'alert-type' => 'success'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(BreakTime $breakTime)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BreakTime $breakTime)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BreakTime $breakTime)
    {
        $request->validate([
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        $created_by = Auth::user()->name;

        $breakTime->start_time = $request->input('start_time');
        $breakTime->end_time = $request->input('end_time');
        $breakTime->created_by = $created_by;
        $breakTime->update();

        return back()->with([
            'message' => 'successfully update !',
            'alert-type' => 'info'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BreakTime $breakTime)
    {
        $breakTime->delete();
        return back()->with([
            'message' => 'successfully deleted !',
            'alert-type' => 'danger'
        ]);
    }
}

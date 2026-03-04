<?php
namespace App\Http\Controllers\hradmin;
use App\Http\Controllers\Controller;
use App\Models\WeekendDay;
use Illuminate\Http\Request;
use Auth;

class WeekendDayController extends Controller
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
        $weekendDays = WeekendDay::orderBy('id', 'desc')->get();
        return view('pages.hradmin.work_time.index', compact('weekendDays'));
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
            'weekend_day' => 'required',
        ]);

        $created_by = Auth::user()->name;

        $weekendDay = new WeekendDay();
        $weekendDay->weekend_day = $request->input('weekend_day');
        $weekendDay->created_by = $created_by;
        $weekendDay->save();

        return back()->with([
            'message' => 'successfully created !',
            'alert-type' => 'success'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(WeekendDay $weekendDay)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(WeekendDay $weekendDay)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, WeekendDay $weekendDay)
    {
        $request->validate([
            'weekend_day' => 'required',
        ]);

        $created_by = Auth::user()->name;

        $weekendDay->weekend_day = $request->input('weekend_day');
        $weekendDay->created_by = $created_by;
        $weekendDay->update();

        return back()->with([
            'message' => 'successfully update !',
            'alert-type' => 'info'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WeekendDay $weekendDay)
    {
        $weekendDay->delete();
        return back()->with([
            'message' => 'successfully deleted !',
            'alert-type' => 'danger'
        ]);
    }
}

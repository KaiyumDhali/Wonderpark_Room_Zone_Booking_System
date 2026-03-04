<?php
namespace App\Http\Controllers\hradmin;
use App\Http\Controllers\Controller;
use App\Models\LateTime;
use Illuminate\Http\Request;
use Auth;

class LateTimeController extends Controller
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
        $lateTimes = LateTime::orderBy('id', 'desc')->get();
        return view('pages.hradmin.late_time.index', compact('lateTimes'));
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
            'late_time' => 'required',
        ]);

        $created_by = Auth::user()->name;

        $lateTime = new LateTime();
        $lateTime->late_time = $request->input('late_time');
        $lateTime->created_by = $created_by;
        $lateTime->save();

        return back()->with([
            'message' => 'successfully created !',
            'alert-type' => 'success'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(LateTime $lateTime)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LateTime $lateTime)
    {
       
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LateTime $lateTime)
    {
        $request->validate([
            'late_time' => 'required',
        ]);

        $created_by = Auth::user()->name;

        $lateTime->late_time = $request->input('late_time');
        $lateTime->created_by = $created_by;
        $lateTime->update();

        return back()->with([
            'message' => 'successfully update !',
            'alert-type' => 'info'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LateTime $lateTime)
    {
        $lateTime->delete();
        return back()->with([
            'message' => 'successfully deleted !',
            'alert-type' => 'danger'
        ]);
    }
}

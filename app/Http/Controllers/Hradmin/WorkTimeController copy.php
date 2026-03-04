<?php
namespace App\Http\Controllers\hradmin;
use App\Http\Controllers\Controller;
use App\Models\WorkTime;
use App\Models\LateTime;

use Illuminate\Http\Request;
use Auth;
class WorkTimeController extends Controller
{

    function __construct() {
        $this->middleware('permission:read holiday|write holiday|create holiday', ['only' => ['index', 'show']]);
        $this->middleware('permission:create holiday', ['only' => ['create', 'store']]);
        $this->middleware('permission:write holiday', ['only' => ['edit', 'update', 'destroy']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $workTimes = WorkTime::orderBy('id', 'desc')->get();
        return view('pages.hradmin.work_time.index', compact('workTimes'));
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

        $workTime = new WorkTime();
        $workTime->start_time = $request->input('start_time');
        $workTime->end_time = $request->input('end_time');
        $workTime->created_by = $created_by;
        $workTime->save();

        return redirect()->route('work_time.index')->with([
            'message' => 'successfully created !',
            'alert-type' => 'success'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(WorkTime $workTime)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(WorkTime $workTime)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, WorkTime $workTime)
    {
        $request->validate([
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        $created_by = Auth::user()->name;

        $workTime->start_time = $request->input('start_time');
        $workTime->end_time = $request->input('end_time');
        $workTime->created_by = $created_by;
        $workTime->update();

        return redirect()->route('work_time.index')->with([
            'message' => 'successfully update !',
            'alert-type' => 'info'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WorkTime $workTime)
    {
        $workTime->delete();
        return back()->with([
            'message' => 'successfully deleted !',
            'alert-type' => 'danger'
        ]);
    }
}

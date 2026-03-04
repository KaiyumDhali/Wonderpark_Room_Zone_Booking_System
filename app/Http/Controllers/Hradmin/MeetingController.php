<?php

namespace App\Http\Controllers\hradmin;

use App\Http\Controllers\Controller;
use App\Models\Meeting;
use App\Models\Termination;
use App\Models\Resignation;
use App\Models\Employee;
use App\Models\EmpDesignation;
use Illuminate\Http\Request;
use Auth;

class MeetingController extends Controller {

    function __construct() {
        $this->middleware('permission:read meeting|write meeting|create meeting', ['only' => ['index', 'show']]);
        $this->middleware('permission:create meeting', ['only' => ['create', 'store']]);
        $this->middleware('permission:write meeting', ['only' => ['edit', 'update', 'destroy']]);
    }

    public function index() {
        $allEmpDesignation = EmpDesignation::pluck('designation_name', 'id')->all();
        $employees = Employee::where('status', 1)->get();

//        $resignations = Resignation::all();

        $meetings = Meeting::select('meetings.*')
                ->with(['employee:id,employee_name'])
                ->orderBy('id', 'desc')
                ->get();

        return view('pages.hradmin.meeting.index', compact('allEmpDesignation', 'employees', 'meetings'));
    }

    public function create() {
        return view('pages.payroll.payslip_type.create');

        // if(\Auth::user()->can('create payslip type'))
        // {
        //     return view('paysliptype.create');
        // }
        // else
        // {
        //     return response()->json(['error' => __('Permission denied.')], 401);
        // }
    }

    public function store(Request $request) {

//                 dd($request->input('employee_id'));

        $request->validate([
            'title' => 'required',
            'date' => 'required|max:100',
            'time' => 'required',
            'note' => 'required',
        ]);

        $created_by = Auth::user()->id;

        $meeting = new Meeting();

        $meeting->title = $request->input('title');
        $meeting->date = $request->input('date');
        $meeting->time = $request->input('time');
        $meeting->note = $request->input('note');
        $meeting->created_by = $created_by;

//dd($termination);
        $meeting->save();

        return redirect()->route('meeting.index')->with([
                    'message' => 'successfully created !',
                    'alert-type' => 'success'
        ]);
    }

    public function show(PayslipType $paysliptype) {
        return redirect()->route('paysliptype.index');
    }

    public function edit(PayslipType $paysliptype) {
        
    }

    public function update(Request $request, Meeting $meeting) {

//                         dd($request->input('title'));

        $request->validate([
            'title' => 'required',
            'date' => 'required|max:100',
            'time' => 'required',
            'note' => 'required',
        ]);

        $created_by = Auth::user()->id;

        $meeting->title = $request->input('title');
        $meeting->date = $request->input('date');
        $meeting->time = $request->input('time');
        $meeting->note = $request->input('note');
        $meeting->created_by = $created_by;

//dd($termination);
        $meeting->update();

        return redirect()->route('meeting.index')->with([
                    'message' => 'successfully created !',
                    'alert-type' => 'success'
        ]);
    }

    public function destroy(Meeting $meeting) {
        $meeting->delete();
        return back()->with([
                    'message' => 'successfully deleted !',
                    'alert-type' => 'danger'
        ]);
    }

}

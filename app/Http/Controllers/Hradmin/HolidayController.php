<?php

namespace App\Http\Controllers\hradmin;

use App\Http\Controllers\Controller;
use App\Models\Holiday;
use App\Models\Announcement;
use App\Models\Termination;
use App\Models\Resignation;
use App\Models\Employee;
use App\Models\EmpDesignation;
use Illuminate\Http\Request;
use Auth;

class HolidayController extends Controller {

    function __construct() {
        $this->middleware('permission:read holiday|write holiday|create holiday', ['only' => ['index', 'show']]);
        $this->middleware('permission:create holiday', ['only' => ['create', 'store']]);
        $this->middleware('permission:write holiday', ['only' => ['edit', 'update', 'destroy']]);
    }

    public function index() {
        $allEmpDesignation = EmpDesignation::pluck('designation_name', 'id')->all();
        $employees = Employee::where('status', 1)->get();

        $holidays = Holiday::select('holidays.*')
                ->with(['employee:id,employee_name'])
                ->orderBy('id', 'desc')
                ->get();

        return view('pages.hradmin.holiday.index', compact('allEmpDesignation', 'employees', 'holidays'));
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

        $request->validate([
            'date' => 'required|max:20',
        ]);

        // $created_by = Auth::user()->first_name.' '.Auth::user()->last_name;
        $created_by = Auth::user()->id;

        $holiday = new Holiday();

        $holiday->date = $request->input('date');
        $holiday->end_date = $request->input('end_date');
        $holiday->occasion = $request->input('occasion');
        $holiday->created_by = $created_by;
        $holiday->save();

        return redirect()->route('holiday.index')->with([
                    'message' => 'successfully created !',
                    'alert-type' => 'success'
        ]);
    }

    public function show(Holiday $holiday) {
//        return redirect()->route('paysliptype.index');
    }

    public function edit(Holiday $holiday) {
        
    }

    public function update(Request $request, Holiday $holiday) {

        $request->validate([
            'date' => 'required|max:20',
        ]);

        $created_by = Auth::user()->id;

        $holiday->date = $request->input('date');
        $holiday->end_date = $request->input('end_date');
        $holiday->occasion = $request->input('occasion');
        $holiday->created_by = $created_by;
        $holiday->update();

        return redirect()->route('holiday.index')->with([
                    'message' => 'successfully created !',
                    'alert-type' => 'success'
        ]);
    }

    public function destroy(Holiday $holiday) {
        $holiday->delete();
        return back()->with([
                    'message' => 'successfully deleted !',
                    'alert-type' => 'danger'
        ]);
    }

}

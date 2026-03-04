<?php

namespace App\Http\Controllers\hradmin;

use App\Http\Controllers\Controller;
use App\Models\Termination;
use App\Models\Resignation;
use App\Models\Employee;
use App\Models\EmpDesignation;
use Illuminate\Http\Request;
use Auth;

class TerminationController extends Controller {

    function __construct() {
        $this->middleware('permission:read termination|write termination|create termination', ['only' => ['index', 'show']]);
        $this->middleware('permission:create termination', ['only' => ['create', 'store']]);
        $this->middleware('permission:write termination', ['only' => ['edit', 'update', 'destroy']]);
    }

    public function index() {
        $allEmpDesignation = EmpDesignation::pluck('designation_name', 'id')->all();
        $employees = Employee::where('status', 1)->get();

//        $resignations = Resignation::all();

        $terminations = termination::select('terminations.*')
                ->with(['employee:id,employee_name'])
                ->orderBy('id', 'desc')
                ->get();

        return view('pages.hradmin.termination.index', compact('allEmpDesignation', 'employees', 'terminations'));
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
            'employee_id' => 'required',
            'notice_date' => 'required|max:100',
            'termination_date' => 'required',
        ]);

        $created_by = Auth::user()->id;

        $termination = new Termination();

        $termination->employee_id = $request->input('employee_id');
        $termination->notice_date = $request->input('notice_date');
        $termination->termination_date = $request->input('termination_date');
        $termination->description = $request->input('description');
        $termination->created_by = $created_by;

//dd($termination);
        $termination->save();

        return redirect()->route('termination.index')->with([
                    'message' => 'successfully created !',
                    'alert-type' => 'success'
        ]);
    }

    public function show(PayslipType $paysliptype) {
        return redirect()->route('paysliptype.index');
    }

    public function edit(PayslipType $paysliptype) {
        
    }

    public function update(Request $request, Termination $termination) {

//                         dd($request->input('employee_id'));

        $request->validate([
            'employee_id' => 'required',
            'notice_date' => 'required|max:100',
            'termination_date' => 'required',
        ]);

        $created_by = Auth::user()->id;

        $termination->employee_id = $request->input('employee_id');
        $termination->notice_date = $request->input('notice_date');
        $termination->termination_date = $request->input('termination_date');
        $termination->description = $request->input('description');
        $termination->created_by = $created_by;

//dd($termination);
        $termination->update();

        return redirect()->route('termination.index')->with([
                    'message' => 'successfully created !',
                    'alert-type' => 'success'
        ]);
    }

    public function destroy(Termination $termination) {
        $termination->delete();
        return back()->with([
                    'message' => 'successfully deleted !',
                    'alert-type' => 'danger'
        ]);
    }

}

<?php

namespace App\Http\Controllers\hradmin;

use App\Http\Controllers\Controller;
use App\Models\Resignation;
use App\Models\Employee;
use App\Models\EmpDesignation;
use Illuminate\Http\Request;
use Auth;

class ResignationController extends Controller {

    function __construct() {
        $this->middleware('permission:read resignation|write resignation|create resignation', ['only' => ['index', 'show']]);
        $this->middleware('permission:create resignation', ['only' => ['create', 'store']]);
        $this->middleware('permission:write resignation', ['only' => ['edit', 'update', 'destroy']]);
    }

    public function index() {
        $allEmpDesignation = EmpDesignation::pluck('designation_name', 'id')->all();
        $employees = Employee::where('status', 1)->get();

//        $resignations = Resignation::all();

        $resignations = Resignation::select('resignations.*')
                ->with(['employee:id,employee_name'])
                ->orderBy('id', 'desc')
                ->get();

        return view('pages.hradmin.resignation.index', compact('allEmpDesignation', 'employees', 'resignations'));
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
            'resignation_date' => 'required',
            'last_working_date' => 'required',
        ]);

        $created_by = Auth::user()->id;

        $resignation = new Resignation();

        $resignation->employee_id = $request->input('employee_id');
        $resignation->notice_date = $request->input('notice_date');
        $resignation->resignation_date = $request->input('resignation_date');
        $resignation->last_working_date = $request->input('last_working_date');
        $resignation->description = $request->input('description');
        $resignation->created_by = $created_by;

//dd($resignation);
        $resignation->save();

        return redirect()->route('resignation.index')->with([
                    'message' => 'successfully created !',
                    'alert-type' => 'success'
        ]);
    }

    public function show(PayslipType $paysliptype) {
        return redirect()->route('paysliptype.index');
    }

    public function edit(PayslipType $paysliptype) {

        $paysliptypes = PayslipType::join('users', 'users.id', '=', 'payslip_types.created_by')
                ->select('payslip_types.*', 'users.first_name', 'users.last_name')
                ->get();
        return view('pages.payroll.payslip_type.edit', compact('paysliptype', 'paysliptypes'));

        // if(\Auth::user()->can('edit payslip type'))
        // {
        //     if($paysliptype->created_by == \Auth::user()->creatorId())
        //     {
        //         return view('paysliptype.edit', compact('paysliptype'));
        //     }
        //     else
        //     {
        //         return response()->json(['error' => __('Permission denied.')], 401);
        //     }
        // }
        // else
        // {
        //     return response()->json(['error' => __('Permission denied.')], 401);
        // }
    }

    public function update(Request $request, Resignation $resignation) {

//                         dd($request->input('description'));
//        $request->validate([
//            'employee_id' => 'required',
//            'notice_date' => 'required|max:100',
//            'resignation_date' => 'required',
//            'last_working_date' => 'required',
//        ]);

        $created_by = Auth::user()->id;

        $resignation->employee_id = $request->input('employee_id');
        $resignation->notice_date = $request->input('notice_date');
        $resignation->resignation_date = $request->input('resignation_date');
        $resignation->last_working_date = $request->input('last_working_date');
        $resignation->description = $request->input('description');
        $resignation->created_by = $created_by;

//dd($resignation);
        $resignation->update();

        return redirect()->route('resignation.index')->with([
                    'message' => 'successfully created!',
                    'alert-type' => 'success'
        ]);
    }

    public function destroy(Resignation $resignation) {
        $resignation->delete();
        return back()->with([
                    'message' => 'successfully deleted !',
                    'alert-type' => 'danger'
        ]);
    }

}

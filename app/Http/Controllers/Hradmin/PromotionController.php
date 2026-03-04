<?php

namespace App\Http\Controllers\hradmin;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use App\Models\Employee;
use App\Models\EmpBranch;
use App\Models\EmpDepartment;
use App\Models\EmpDesignation;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Auth;

class PromotionController extends Controller {

    function __construct() {
        $this->middleware('permission:read promotion|write promotion|create promotion', ['only' => ['index', 'show']]);
        $this->middleware('permission:create promotion', ['only' => ['create', 'store']]);
        $this->middleware('permission:write promotion', ['only' => ['edit', 'update', 'destroy']]);
    }

    public function singleEmployeePromotion($id) {
        
        $employee = Employee::with(['empBranch:id,branch_name','empDepartment:id,department_name','empDesignation:id,designation_name','lastPromotion'])->where('id',$id)
                    ->get();
        return response()->json($employee);
    }
    public function index() {
       
        $allEmployees = Employee::where('status',1)->get(['id','employee_name','employee_code']);
        $allEmpBranch = EmpBranch::pluck('branch_name', 'id')->all();
        $allDepartment = EmpDepartment::pluck('department_name', 'id')->all();

        $allEmpDesignation = EmpDesignation::pluck('designation_name', 'id')->all();
        $employees = Employee::where('status', 1)->get();

//        $promotions = Promotion::all();

        $promotions = Promotion::select('promotions.*')
                ->with(['employee:id,employee_name,employee_code', 'empbranch:id,branch_name','empdepartment:id,department_name', 'empdesignation:id,designation_name'])
                ->orderBy('id', 'desc')
                ->get();

    //    dd($promotions);

        return view('pages.hradmin.promotion.index', compact('allEmpDesignation', 'allEmployees', 'employees', 'promotions', 'allEmpBranch', 'allDepartment'));

        // if(\Auth::user()->can('manage payslip type'))
        // {
        //     $paysliptypes = PayslipType::where('created_by', '=', \Auth::user()->creatorId())->get();
        //     return view('payroll.payslip_type.index', compact('paysliptypes'));
        // }
        // else
        // {
        //     return redirect()->back()->with('error', __('Permission denied.'));
        // }
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
            'employee_id' => 'required',
            'branch_id' => 'required|max:100',
            'department_id' => 'required|max:100',
            'designation_id' => 'required|max:100',
            'start_date' => 'required',
        ]);

        $created_by = Auth::user()->id;
        $employeeId = $request->input('employee_id');
        $promotionDate = $request->input('start_date');
        $endDate = Carbon::parse($promotionDate)->subDays(1);
        $empLastPromotion = Promotion::where('employee_id', $employeeId)->latest()->first();
        if ($empLastPromotion != null) {
            if (Carbon::parse($promotionDate)->lte(Carbon::parse($empLastPromotion->start_date))) {
                return back()->with([
                            'message' => 'Already Promoted !',
                            'alert-type' => 'danger'
                ]);
            }
            Promotion::where('id', $empLastPromotion->id)
                    ->update([
                        'end_date' => $endDate
            ]);
//            return back()->with([
//                        'message' => 'Promotion Not Possible !',
//                        'alert-type' => 'danger'
//            ]);
        } 

        Employee::where('id', $employeeId)
                ->update([
                    'branch_id' => $request->input('branch_id'),
                    'department_id' => $request->input('department_id'),
                    'designation_id' => $request->input('designation_id'),
        ]);

        $promotion = new Promotion();

        $promotion->employee_id = $request->input('employee_id');
        $promotion->branch_id = $request->input('branch_id');
        $promotion->department_id = $request->input('department_id');
        $promotion->designation_id = $request->input('designation_id');
        $promotion->start_date = $promotionDate;
        $promotion->description = strip_tags($request->input('description'));
        $promotion->created_by = $created_by;

        //dd($empLastPromotion);
        $promotion->save();

        return redirect()->route('promotion.index')->with([
                    'message' => 'successfully Promoted !',
                    'alert-type' => 'success'
        ]);
    }

    public function show(Promotion $promotion) {
        
    }

    public function edit(Promotion $promotion) {
        
    }

    public function update(Request $request, Promotion $promotion) {
        $request->validate([
            'employee_id' => 'required',
            'branch_id' => 'required|max:100',
            'department_id' => 'required|max:100',
            'designation_id' => 'required|max:100',
            'start_date' => 'required',
        ]);

        $created_by = Auth::user()->id;
        $employeeId = $request->input('employee_id');

        $promotionDate = $request->input('start_date');
//        $endDate = Carbon::parse($promotionDate)->subDays(1);
        $empLastPromotion = Promotion::where('employee_id', $employeeId)->latest()->first();

        if ($empLastPromotion != null) {
            if (Carbon::parse($promotionDate)->lte(Carbon::parse($empLastPromotion->start_date))) {
                return back()->with([
                            'message' => 'Already Promoted !',
                            'alert-type' => 'danger'
                ]);
            }
//            Promotion::where('id', $empLastPromotion->id)
//                    ->update([
//                        'end_date' => $endDate
//            ]);
//            return back()->with([
//                        'message' => 'Promotion Not Possible !',
//                        'alert-type' => 'danger'
//            ]);
        }

        Employee::where('id', $employeeId)
                ->update([
                    'branch_id' => $request->input('branch_id'),
                    'department_id' => $request->input('department_id'),
                    'designation_id' => $request->input('designation_id'),
        ]);

        $promotion->employee_id = $request->input('employee_id');
        $promotion->branch_id = $request->input('branch_id');
        $promotion->department_id = $request->input('department_id');
        $promotion->designation_id = $request->input('designation_id');
        $promotion->start_date = $promotionDate;
        $promotion->description = strip_tags($request->input('description'));
        $promotion->created_by = $created_by;

//         dd($promotion);
        $promotion->update();

        return redirect()->route('promotion.index')->with([
                    'message' => 'successfully created !',
                    'alert-type' => 'success'
        ]);
    }

    public function destroy(Promotion $promotion) {
        $promotion->delete();
        return back()->with([
                    'message' => 'successfully deleted !',
                    'alert-type' => 'danger'
        ]);
    }

}
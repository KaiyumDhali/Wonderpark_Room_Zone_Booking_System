<?php

namespace App\Http\Controllers\Employee;

use App\Models\EmployeeLedger;
use App\Models\Employee;
use App\Models\EmployeeSalary;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use validator;
use Illuminate\Support\Facades\Date;
use Auth;


class EmployeeLedgerController extends Controller
{

    function __construct()
    {
         $this->middleware('permission:read employee ledger|create employee ledger', ['only' => ['index','show']]);
         $this->middleware('permission:create employee ledger', ['only' => ['create','store']]);
         //$this->middleware('permission:write customer ledger', ['only' => ['edit','update','destroy']]);
    }
    public function employeeProfileLedger()
    {
        $id = Auth::user()->id;
        $employee = Employee::where('status', 1)->where('user_id', $id)->first();
        $employeeDetails = Employee::leftJoin('employee_ledgers', 'employee_ledgers.employee_id', '=', 'employees.id')
        ->select('employees.*', 'employee_ledgers.id as ledger_id', 'employee_ledgers.employee_id', 'employee_ledgers.ledger_title_id', 'employee_ledgers.date as ledger_date', 'employee_ledgers.debit', 'employee_ledgers.credit', 'employee_ledgers.remarks')
        ->where('employees.user_id',$id)->get();
        // dd($employeeDetails);
        return view('pages.employee.employee_ledger.profile_ledger', compact('employee','employeeDetails'));
    }
    public function employeeDetails($id)
    {
        $employeeDetails = Employee::leftJoin('employee_ledgers', 'employee_ledgers.employee_id', '=', 'employees.id')
        ->select('employees.*', 'employee_ledgers.id as ledger_id', 'employee_ledgers.employee_id', 'employee_ledgers.ledger_title_id', 'employee_ledgers.date as ledger_date', 'employee_ledgers.debit', 'employee_ledgers.credit', 'employee_ledgers.remarks')
        ->where('employees.id',$id)->get();
        $employeeSalary = EmployeeSalary::where('employee_id', $id)->latest()->first();
        return response()->json([
            'employeeDetails' => $employeeDetails,
            'employeeSalary' => $employeeSalary,
        ]);
    }

    public function index()
    {
        $supplier_ledgers = EmployeeLedger::join('employees', 'employees.id', '=', 'employee_ledgers.employee_id')
        ->select('employee_ledgers.*', 'employees.employee_name')
        ->orderby('id', 'desc')->get();
        $employees = Employee::where('status', 1)->get();
        return view('pages.employee.employee_ledger.index', compact('supplier_ledgers','employees'));
    }

    public function store(Request $request)
    {
        $input = $request->all();
        $employee_id = $request->employee_id;
        // dd($employee_id);
        if ($input['ledger_title_id'] == 1) {
            $input['credit'] =  $input['input_amount'];
            $input['debit'] = 0;
        } else {
            $input['credit'] = 0;
            $input['debit'] =  $input['input_amount'];
        }
        $input['date'] =  Date::now();
        EmployeeLedger::create($input);

        // $employees = Employee::where('status', 1)->get();
        // $employeeSalary = EmployeeSalary::where('employee_id', $employee_id)->latest()->first();
        // $employeeDetails = Employee::leftJoin('employee_ledgers', 'employee_ledgers.employee_id', '=', 'employees.id')
        // ->select('employees.*', 'employee_ledgers.id as ledger_id', 'employee_ledgers.employee_id', 'employee_ledgers.ledger_title_id', 'employee_ledgers.date as ledger_date', 'employee_ledgers.debit', 'employee_ledgers.credit', 'employee_ledgers.remarks')
        // ->where('employees.id',$employee_id)->get();
        // return view('pages.employee.employee_ledger.index', compact('employees', 'employeeSalary', 'employeeDetails'))->with([
        //     'message' => 'successfully created!',
        //     'alert-type' => 'success'
        // ]);
        

        // return $this->singleEmployeeDetails($employee_id);

        return redirect()->route('employee_ledger.index')->with([
            'message' => 'successfully created !',
            'alert-type' => 'success'
        ]);
    }
    // public function singleEmployeeDetails($id)
    // {
    //     $employeeDetails = Employee::leftJoin('employee_ledgers', 'employee_ledgers.employee_id', '=', 'employees.id')
    //     ->select('employees.*', 'employee_ledgers.id as ledger_id', 'employee_ledgers.employee_id', 'employee_ledgers.ledger_title_id', 'employee_ledgers.date as ledger_date', 'employee_ledgers.debit', 'employee_ledgers.credit', 'employee_ledgers.remarks')
    //     ->where('employees.id',$id)->get();
    //     $employeeSalary = EmployeeSalary::where('employee_id', $id)->latest()->first();
    //     return response()->json([
    //         'employeeDetails' => $employeeDetails,
    //         'employeeSalary' => $employeeSalary,
    //     ]);
    // }
    public function show($id)
    {
    }
    public function edit(EmpDepartment $empDepartment)
    {
        // $allEmpBranch = EmpBranch::pluck('branch_name','id')->all();
        // return view('pages.employee.emp_department._department-update', compact('empDepartment', 'allEmpBranch'));
    }

    public function update(Request $request, EmpDepartment $empDepartment)
    {
        // $request->validate([
        //     'department_name' => 'required|string|max:255',
        // ]);

        // $empDepartment->branch_id = $request->branch_id;
        // $empDepartment->department_name = $request->department_name;
        // $empDepartment->status = $request->status;
        // $empDepartment->update();

        // return redirect()->route('emp_department.index')->with([
        //     'message' => 'successfully updated !',
        //     'alert-type' => 'info'
        // ]);
    }

    public function destroy(EmpDepartment $branch)
    {
        // $branch->delete();
        // return redirect()->route('branch.index')->with([
        //     'message' => 'successfully deleted !',
        //     'alert-type' => 'danger'
        // ]);
    }
}
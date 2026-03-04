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
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

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
        // $employeeDetails = Employee::leftJoin('employee_ledgers', 'employee_ledgers.employee_id', '=', 'employees.id')
        // ->select('employees.*', 'employee_ledgers.id as ledger_id', 'employee_ledgers.employee_id', 'employee_ledgers.ledger_title_id', 'employee_ledgers.date as ledger_date', 'employee_ledgers.debit', 'employee_ledgers.credit', 'employee_ledgers.remarks')
        // ->where('employees.id',$id)->get();

        $employeeDetails = EmployeeLedger::where('employee_id', $id)->get();

        $employeeSalary = EmployeeSalary::where('employee_id', $id)->latest()->first();
        return response()->json([
            'employeeDetails' => $employeeDetails,
            'employeeSalary' => $employeeSalary,
        ]);
    }
    public function employeeLedgerSearch($startDate, $endDate, $employeeID, $pdf)
    {
        // $employeeLedgers = EmployeeLedger::where('employee_id', $employeeID)
        //     ->whereBetween('date', [$startDate, $endDate])
        //     ->get();
        $query = "
        SELECT -1 as id,-1 as employee_id, '$startDate' as date,'Opening Balance on : $startDate' AS voucher_no,IFNULL( SUM(credit),0) as credit ,IFNULL(SUM(debit),0) as debit,'Opening Balance' as remarks
        FROM `employee_ledgers` 
        WHERE employee_id = $employeeID AND date <'$startDate'
        UNION ALL 
        SELECT id,employee_id,date,voucher_no,credit,debit,remarks 
        FROM `employee_ledgers` 
        WHERE employee_id = $employeeID AND date BETWEEN '$startDate' AND '$endDate'
        ";

        $result = DB::table(DB::raw("($query) AS subquery"))
            ->select('id','employee_id', 'date', 'voucher_no','credit','debit','remarks',)
            ->get();

        if ($pdf == "list") {
            return response()->json($result);
        }
        if ($pdf == "pdfurl") {
            $data['start_date'] = $startDate;
            $data['end_date'] = $endDate;
            $data['branch_id'] = '';
            $data['employeeInfo'] = Employee::where('id',$employeeID)->select('id','employee_name')->first();
            $pdf = PDF::loadView('pages.pdf.employee_ledger_report_pdf', array('employeeLedgers' => $result, 'data' => $data));
            return $pdf->stream(Carbon::now().'-recentstat.pdf');
        }
    }

    public function index()
    {
        // $query = "
        //     SELECT -1 as id,-1 as employee_id,'2023-10-17' as date,'Opening Balance on : 2023-10-17' AS voucher_no,IFNULL( SUM(credit),0) as credit ,IFNULL(SUM(debit),0) as debit,'Opening Balance' as remarks
        //     FROM `employee_ledgers` 
        //     WHERE employee_id = '1' AND date <'2023-10-17'
        //     UNION ALL 
        //     SELECT id,employee_id,date,voucher_no,credit,debit,remarks 
        //     FROM `employee_ledgers` 
        //     WHERE employee_id = '1' AND date BETWEEN '2023-10-17' AND '2023-11-31'
        //     ";

        // $result = DB::table(DB::raw("($query) AS subquery"))
        //     ->select('employee_id', 'date', 'voucher_no','credit','debit','remarks',)
        //     ->get();
        //     dd($result);
        $employees = Employee::where('status', 1)->get();
        return view('pages.employee.employee_ledger.index', compact('employees'));
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
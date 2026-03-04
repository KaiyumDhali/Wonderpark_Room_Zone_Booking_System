<?php
namespace App\Http\Controllers\Employee;
use App\Http\Controllers\Controller;

use App\Models\EmployeePerformance;
use App\Models\Employee;
use App\Models\EmpBranch;
use App\Models\PerformanceType;

use Illuminate\Http\Request;
use Auth;

class EmployeePerformanceController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:read employee performance|write employee performance|create employee performance', ['only' => ['index','show']]);
         $this->middleware('permission:create employee performance', ['only' => ['create','store']]);
         $this->middleware('permission:write employee performance', ['only' => ['edit','update','destroy']]);
    }

    public function employeePerformanceBranchDetails($id)
    {
        $employees = Employee::where('status', 1)
        ->where('branch_id', $id)
        ->select('employees.id', 'employees.employee_name')
        ->get();
        return response()->json($employees);
    }

    public function employeePerformancType($id)
    {
        $employeePerformancType = PerformanceType::where('status', 1)->get();
        return response()->json($employeePerformancType);
    }
 
    public function index()
    {
        $employee_performances = EmployeePerformance::select('employee_performances.*')
        ->with(['employee:id,employee_name', 'performance:id,performance_name'])
        ->orderBy('id', 'desc')
        ->get();
        // $employee_performances = EmployeePerformance::get();
        // dd($employee_performances);
        return view('pages.employee.employee_performance.index', compact('employee_performances'));
    }

    public function create()
    {
        $allEmpBranch = EmpBranch::where('status', 1)->pluck('branch_name', 'id')->all();
        return view('pages.employee.employee_performance.employee_performance_add', compact('allEmpBranch'));
    }


    public function store(Request $request)
    {
        // $input = $request->all();
        // dd($input);
        $employee_id = $request->input('employee_id');
        $performance_date = $request->input('performance_date');
        $done_by = Auth::user()->first_name.' '.Auth::user()->last_name;

        $employee_performance_ids = $request->input('employee_performance_id');
        if ($employee_performance_ids) {
            foreach ($employee_performance_ids as $key => $employee_performance_id) {
                $employeePerformance = new EmployeePerformance();

                $employeePerformance->employee_id = $employee_id;
                $employeePerformance->performance_date = $performance_date;
                $employeePerformance->performance_id = $employee_performance_id;

                $performance_rates = $request->input('employee_performance_rate');
                $employeePerformance->performance_rate = $performance_rates[$key][$employee_performance_id];

                $employee_remarks = $request->input('employee_remarks');
                $employeePerformance->remarks = $employee_remarks[$key];

                $employeePerformance->done_by = $done_by;
                // dd($employeePerformance);

                $employeePerformance->save();
            }
        }
        return redirect()->route('employee_performance.index')->with([
            'message' => 'successfully created !',
            'alert-type' => 'success'
        ]);
    }

    public function show(EmployeePerformance $employeePerformance)
    {
        //
    }


    public function edit(EmployeePerformance $employeePerformance)
    {
        //
    }

    public function update(Request $request, EmployeePerformance $employeePerformance)
    {
        //
    }

    public function destroy(EmployeePerformance $employeePerformance)
    {
        //
    }
}
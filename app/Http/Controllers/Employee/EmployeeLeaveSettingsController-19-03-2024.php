<?php

namespace App\Http\Controllers\Employee;

use App\Models\EmployeeLeaveSetting;
use App\Models\EmployeeLedger;
use App\Models\Employee;
use App\Models\EmpBranch;
use App\Models\EmpDepartment;

use App\Models\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
// use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Date;
use Carbon\Carbon;
use Auth;
use DB;

class EmployeeLeaveSettingsController extends Controller
{

    function __construct()
    {
        $this->middleware('permission:read leave setting|create leave setting', ['only' => ['index','show']]);
        $this->middleware('permission:create leave setting', ['only' => ['create','store']]);
    }

    public function branchDetails($id)
    {
        $departments = EmpDepartment::where('status', 1)
        ->where('branch_id', $id)
        ->select('emp_departments.id', 'emp_departments.department_name')
        ->get();

        $employeeDetails = EmployeeLeaveSetting::with(['employee:id,employee_name', 'empDepartment:id,department_name'])
        ->where('leave_year', Carbon::now()->year)
        ->where('status', 1)
        ->where('branch_id', $id)
        ->get();

        return response()->json([
            'departments' => $departments,
            'employeeDetails' => $employeeDetails,
        ]);
    }

    public function departmentDetails($id)
    {
        $employeeDetails = EmployeeLeaveSetting::with(['employee:id,employee_name', 'empDepartment:id,department_name'])
        ->where('leave_year', Carbon::now()->year)
        ->where('status', 1)
        ->where('department_id', $id)
        ->get();
        return response()->json($employeeDetails);
    }
    // public function departmentDetails($id)
    // {
    //     $employeeDetails = EmployeeLeaveSetting::leftJoin('employees', 'employees.id', '=', 'employee_leave_settings.employee_id')
    //     ->select('employee_leave_settings.*','employees.id', 'employees.employee_name', )
    //     ->where('status', 1)
    //     ->where('department_id', $id)
    //     ->get();
    //     return response()->json($employeeDetails);
    // }
    public function departmentEmployee($id)
    {
        // $employees = Employee::where('status', 1)
        // ->where('department_id', $id)
        // ->select('employees.id', 'employees.employee_name')
        // ->get();

        $employees = $employees = DB::table('employees')
        ->leftJoin('employee_leave_settings', 'employees.id', '=', 'employee_leave_settings.employee_id')
        ->where('employees.status', 1)
        ->where('employees.department_id', $id)
        ->whereNull('employee_leave_settings.employee_id')
        ->select('employees.id', 'employees.employee_name')
        ->get();

        return response()->json($employees);
    }
    public function employeeLeaveDetails($id)
    {
        $employeeDetails = Employee::leftJoin('employee_leave_settings', 'employee_leave_settings.employee_id', '=', 'employees.id')
        ->select('employees.id', 'employees.employee_name', 'employee_leave_settings.id as employee_leave_settings_id', 'employee_leave_settings.casual_leave', 'employee_leave_settings.sick_leave', 'employee_leave_settings.annual_leave', 'employee_leave_settings.special_leave', 'employee_leave_settings.remarks')
        ->where('employees.id',$id)->get();
        return response()->json($employeeDetails);
    }

    public function allEmployeeLeaveSetting()
    {
        $employeeLeaveSettings=EmployeeLeaveSetting::with(['employee:id,employee_name', 'empDepartment:id,department_name'])
                            ->where('leave_year', Carbon::now()->year)->get();
        return response()->json($employeeLeaveSettings);
    }
    public function index()
    {
        // $employees = Employee::where('status', 1)->get();
        $employeeLeaveSettings = EmployeeLeaveSetting::with(['employee:id,employee_name', 'empDepartment:id,department_name'])
                                                        ->get();
        $allEmpBranch = EmpBranch::where('status', 1)->pluck('branch_name', 'id')->all();
        return view('pages.employee.employee_leave_setting.index', compact('allEmpBranch', 'employeeLeaveSettings'));
    }

    public function create()
    {
    
    }

    public function store(Request $request) {

        $request->validate([
            'leave_year' => 'required|numeric',
            'branch_id' => 'required|numeric',
            'department_id' => 'required|numeric',
            'casual_leave' => 'required|numeric',
            'sick_leave' => 'required|numeric',
            'annual_leave' => 'nullable|numeric',
            'special_leave' => 'nullable|numeric',
            'remarks' => 'nullable|string',
        ]);
        
        $input = $request->all();

        $employeeId = $request->input('employee_id');
        
        if ($employeeId) {
            $employeeLeaveSetting = new EmployeeLeaveSetting();
            
            $casual_leave = $request->input('casual_leave');
            $sick_leave = $request->input('sick_leave');
            $annual_leave = $request->input('annual_leave');
            $special_leave = $request->input('special_leave');
            $total_leave = $casual_leave + $sick_leave + $annual_leave + $special_leave;
            // Fill in the EmployeeLeaveSetting attributes
            $employeeLeaveSetting->leave_year = $request->input('leave_year');
            $employeeLeaveSetting->branch_id = $request->input('branch_id');
            $employeeLeaveSetting->department_id = $request->input('department_id');
            $employeeLeaveSetting->employee_id = $employeeId;
            $employeeLeaveSetting->casual_leave = $casual_leave;
            $employeeLeaveSetting->sick_leave = $sick_leave;
            $employeeLeaveSetting->annual_leave = $annual_leave;
            $employeeLeaveSetting->special_leave = $special_leave;
            $employeeLeaveSetting->total_leave = $total_leave;
            // $employeeLeaveSetting->remarks = $request->input('remarks');
            $employeeLeaveSetting->remarks = strip_tags($request->input('remarks'));

            $employeeLeaveSetting->status = empty($request['status']) ? 1 : $request['status'];
            $employeeLeaveSetting->done_by = Auth::user()->first_name . ' ' . Auth::user()->last_name;
            // Save the EmployeeLeaveSetting
            $employeeLeaveSetting->save();
        }else{
            $departmentId = $request->input('department_id');
            // Get all employees with 'status' = 1 and 'department_id' = $departmentId
            $employees = Employee::where('status', 1)
                        ->where('department_id', $departmentId)
                        ->select('employees.id')
                        ->get();
            
            // Loop through each employee and create an EmployeeLeaveSetting record
            foreach ($employees as $employee) {
                $employeeLeaveSetting = new EmployeeLeaveSetting();
                
                $casual_leave = $request->input('casual_leave');
                $sick_leave = $request->input('sick_leave');
                $annual_leave = $request->input('annual_leave');
                $special_leave = $request->input('special_leave');
                $total_leave = $casual_leave + $sick_leave + $annual_leave + $special_leave;
    
                // Fill in the EmployeeLeaveSetting attributes
                $employeeLeaveSetting->leave_year = $request->input('leave_year');
                $employeeLeaveSetting->branch_id = $request->input('branch_id');
                $employeeLeaveSetting->department_id = $request->input('department_id');
                $employeeLeaveSetting->casual_leave = $casual_leave;
                $employeeLeaveSetting->sick_leave = $sick_leave;
                $employeeLeaveSetting->annual_leave = $annual_leave;
                $employeeLeaveSetting->special_leave = $special_leave;
                $employeeLeaveSetting->total_leave = $total_leave;
                $employeeLeaveSetting->remarks = $request->input('remarks');
                $employeeLeaveSetting->status = empty($request['status']) ? 1 : $request['status'];
                $employeeLeaveSetting->done_by = Auth::user()->first_name . ' ' . Auth::user()->last_name;
                $employeeLeaveSetting->employee_id = $employee->id;
                // Save the EmployeeLeaveSetting
                $employeeLeaveSetting->save();
            }
        }

        // Redirect to the index route with a success message
        return redirect()->route('employee_leave_setting.index')->with([
            'message' => 'Successfully created employees leave settings !',
            'alert-type' => 'success'
        ]);
    }

    public function show(EmployeeLeaveSetting $employeeLeaveSetting)
    {
        
    }

    public function edit(EmployeeLeaveSetting $employeeLeaveSetting)
    {

    }

    public function update(Request $request, EmployeeLeaveSetting $employeeLeaveSetting)
    {
        $request->validate([
            'leave_year' => 'required|numeric',
            'branch_id' => 'required|numeric',
            'department_id' => 'nullable|numeric',
            'casual_leave' => 'nullable|numeric',
            'sick_leave' => 'nullable|numeric',
            'annual_leave' => 'nullable|numeric',
            'special_leave' => 'nullable|numeric',
            'remarks' => 'nullable|string',
        ]);
        
        $input = $request->all();

        $branchId = $request->input('branch_id');
        $departmentId = $request->input('department_id');
        $leaveYear = $request->input('leave_year');
        
        // Get all employees with 'leave_year' = '' and 'department_id' = $departmentId
        if ($departmentId) {
            $employees = EmployeeLeaveSetting::where('leave_year', $leaveYear)
                    ->where('department_id', $departmentId)
                    ->select('employee_leave_settings.id')
                    ->get();
        }else{
            $employees = EmployeeLeaveSetting::where('leave_year', $leaveYear)
            ->where('branch_id', $branchId)
            ->select('employee_leave_settings.id')
            ->get();
        }
        
        // Loop through each employee and create an EmployeeLeaveSetting record
        foreach ($employees as $employee) {

            $casual_leave = $request->input('casual_leave');
            $sick_leave = $request->input('sick_leave');
            $annual_leave = $request->input('annual_leave');
            $special_leave = $request->input('special_leave');
            $remarks = $request->input('remarks');
            $total_leave = $casual_leave+$sick_leave+$annual_leave+$special_leave;

            EmployeeLeaveSetting::where('id', $employee->id)->update([
                'casual_leave' => $casual_leave,
                'sick_leave' => $sick_leave,
                'annual_leave' => $annual_leave,
                'special_leave' => $special_leave,
                'total_leave' => $total_leave,
                'remarks' => $remarks,
            ]);
        }

        return redirect()->route('employee_leave_setting.index')->with([
            'message' => 'Successfully update employees leave settings !',
            'alert-type' => 'success'
        ]);

    }

    public function updateLeaveSettings(Request $request, $editLeaveID)
    {
        // Retrieve data from the form submission
        $employeeName = $request->input('employeeName');
        $departmentName = $request->input('departmentName');
        $casualLeave = $request->input('casualLeave');
        $sickLeave = $request->input('sickLeave');
        $annualLeave = $request->input('annualLeave');
        $specialLeave = $request->input('specialLeave');
        $remarks = $request->input('remarks');
        $total_leave = $casualLeave + $sickLeave + $annualLeave + $specialLeave;

        // Find the model by its ID
        $leaveSetting = EmployeeLeaveSetting::find($editLeaveID);

        if (!$leaveSetting) {
            return response()->json(['message' => 'Leave setting not found'], 404);
        }

        // Update the model attributes and save it
        $leaveSetting->update([
            'casual_leave' => $casualLeave,
            'sick_leave' => $sickLeave,
            'annual_leave' => $annualLeave,
            'special_leave' => $specialLeave,
            'total_leave' => $total_leave,
            'remarks' => $remarks,
        ]);

        return redirect()->route('employee_leave_setting.index')->with([
            'message' => 'Successfully update employees leave settings !',
            'alert-type' => 'success'
        ]);

        // return back()->with(['message' => 'Leave settings updated successfully']);
    }
}
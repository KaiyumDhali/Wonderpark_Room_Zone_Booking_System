<?php

namespace App\Http\Controllers\Employee;

use App\Models\EmployeeLeaveSetting;
use App\Models\EmployeeLeaveEntry;
use App\Models\Employee;
use App\Models\EmpBranch;
use App\Models\EmpSection;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Date;
use Carbon\Carbon;
use validator;
use Auth;
use DB;

class EmployeeLeaveApproveDeptController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:read leave approved department|create leave approved department', ['only' => ['index','show']]);
        $this->middleware('permission:create leave approved department', ['only' => ['create','store', 'update']]);
    }

    public function leaveEntryBranchDetails($id)
    {
        $employees = Employee::where('status', 1)
                    ->where('branch_id', $id)
                    ->select('employees.id', 'employees.employee_name')
                    ->get();

        $employeeDetails = EmployeeLeaveEntry::select('employee_leave_entries.*')
                            ->with(['employeeId:id,employee_name', 'alternativeEmployeeId:id,employee_name', 'employeeTotalLeave:id,total_leave'])
                            ->where('branch_id', $id)
                            ->orderBy('id', 'desc')
                            ->get();

        return response()->json([
            'employees' => $employees,
            'employeeDetails' => $employeeDetails,
        ]);
    }

    public function employeeLeaveEntryDetails($id)
    {
        $employeeDetails = EmployeeLeaveEntry::select('employee_leave_entries.*')
                            ->with(['employeeId:id,employee_name', 'alternativeEmployeeId:id,employee_name', 'employeeTotalLeave:id,total_leave'])
                            ->where('employee_id', $id)
                            ->orderBy('id', 'desc')
                            ->get();

        if ($employeeDetails->isEmpty()) {
            $employeeDetails = EmployeeLeaveSetting::select('employee_leave_settings.total_leave')
                                ->where('employee_id', $id)
                                ->get();
        }
        return response()->json($employeeDetails);
    }

    // Open Index 
    public function index()
    {
        $employeeDetails = EmployeeLeaveEntry::select('employee_leave_entries.*')
                            ->with(['employeeId:id,employee_name', 'alternativeEmployeeId:id,employee_name', 'employeeTotalLeave:id,total_leave'])
                            ->where('department_status',0)
                            ->where('final_status',0)
                            ->orderBy('id', 'desc')
                            ->get();        
        $sectionId = $employeeDetails->pluck('section_id');
        // $branchEmployeeDetails = Employee::whereIn('branch_id', $sectionId)
        //                 ->select('employees.id', 'employees.employee_name', 'employees.branch_id')
        //                 ->get();
        // $allEmpBranch = EmpBranch::where('status', 1)->pluck('branch_name', 'id')->all();
        return view('pages.employee.employee_leave_approve_dept.index', compact( 'employeeDetails'));
    }

    // Index list approved on click get data 
    public function approvedIndex()
    {
        $employeeDetails = EmployeeLeaveEntry::select('employee_leave_entries.*')
                            ->with(['employeeId:id,employee_name', 'alternativeEmployeeId:id,employee_name', 'employeeTotalLeave:id,total_leave'])
                            ->where('department_status',1)
                            ->orderBy('id', 'desc')
                            ->get();
        $sectionId = $employeeDetails->pluck('section_id');
        // $branchEmployeeDetails = Employee::whereIn('branch_id', $branchIds)
        //                         ->select('employees.id', 'employees.employee_name', 'employees.branch_id')
        //                         ->get();
        // $allEmpBranch = EmpBranch::where('status', 1)->pluck('branch_name', 'id')->all();
        return view('pages.employee.employee_leave_approve_dept.index', compact('employeeDetails'));
    }

    // Action Update button on click save 
    public function update(Request $request, $id)
    {
        $employeeLeaveEntry = EmployeeLeaveEntry::find($id);
        $employeeLeaveEntry->leave_start_date = $request->input('leave_start_date');
        $employeeLeaveEntry->leave_end_date = $request->input('leave_end_date');
        $employeeLeaveEntry->total_days = $request->input('total_leave_days');
        $employeeLeaveEntry->remarks = $request->input('remarks');
        $employeeLeaveEntry->department_status = $request->input('department_status');
        $employeeLeaveEntry->final_status = $request->input('department_status');
        $employeeLeaveEntry->save();
        return redirect()->route('employee_leave_approve_dept.index')->with([
                    'message' => 'successfully updated !',
                    'alert-type' => 'info'
        ]);

    }
}
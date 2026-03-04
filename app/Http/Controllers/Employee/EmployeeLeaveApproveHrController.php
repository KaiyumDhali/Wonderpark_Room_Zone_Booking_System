<?php

namespace App\Http\Controllers\Employee;

use App\Models\EmployeeLeaveSetting;
use App\Models\EmployeeLeaveEntry;
use App\Models\Employee;
use App\Models\EmpBranch;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Date;
use Carbon\Carbon;
use validator;
use Auth;
use DB;

class EmployeeLeaveApproveHrController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:read leave approved hr|create leave approved hr', ['only' => ['index','show']]);
        $this->middleware('permission:create leave approved hr', ['only' => ['create','store', 'update']]);
    }

    public function index()
    {
        $employeeDetails = EmployeeLeaveEntry::select('employee_leave_entries.*')
                            ->with(['employeeId:id,employee_name', 'alternativeEmployeeId:id,employee_name', 'employeeTotalLeave:id,total_leave'])
                            ->where('department_status',1)
                            ->where('hr_status',0)
                            ->orderBy('id', 'desc')
                            ->get();        
        return view('pages.employee.employee_leave_approve_hr.index', compact('employeeDetails'));
    }

    public function approvedIndex()
    {
        $employeeDetails = EmployeeLeaveEntry::select('employee_leave_entries.*')
                            ->with(['employeeId:id,employee_name', 'alternativeEmployeeId:id,employee_name', 'employeeTotalLeave:id,total_leave'])
                            ->where('hr_status',1)
                            ->orderBy('id', 'desc')
                            ->get();
                            // dd($employeeDetails);
        return view('pages.employee.employee_leave_approve_hr.approved', compact('employeeDetails'));
    }

    public function update(Request $request, $id)
    {
        $employeeLeaveEntry = EmployeeLeaveEntry::find($id);
        $employeeLeaveEntry->hr_status = $request->input('hr_status');
        $employeeLeaveEntry->save();
        return redirect()->route('employee_leave_approve_hr.index')->with([
                    'message' => 'successfully updated !',
                    'alert-type' => 'info'
        ]);
    }
}
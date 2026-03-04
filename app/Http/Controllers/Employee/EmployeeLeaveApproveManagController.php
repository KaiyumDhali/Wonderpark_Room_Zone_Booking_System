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

class EmployeeLeaveApproveManagController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:read leave approved management|create leave approved management', ['only' => ['index','show']]);
        $this->middleware('permission:create leave approved management', ['only' => ['create','store', 'update']]);
    }

    public function index()
    {
        $employeeDetails = EmployeeLeaveEntry::select('employee_leave_entries.*')
                            ->with(['employeeId:id,employee_name', 'alternativeEmployeeId:id,employee_name', 'employeeTotalLeave:id,total_leave'])
                            ->where('hr_status',1)
                            ->where('management_status',0)
                            ->orderBy('id', 'desc')
                            ->get();        
        return view('pages.employee.employee_leave_approve_manag.index', compact('employeeDetails'));
    }

    public function approvedIndex()
    {
        $employeeDetails = EmployeeLeaveEntry::select('employee_leave_entries.*')
                            ->with(['employeeId:id,employee_name', 'alternativeEmployeeId:id,employee_name', 'employeeTotalLeave:id,total_leave'])
                            ->where('management_status',1)
                            ->orderBy('id', 'desc')
                            ->get();
        return view('pages.employee.employee_leave_approve_manag.approved', compact('employeeDetails'));
    }

    public function update(Request $request, $id)
    {
        $employeeLeaveEntry = EmployeeLeaveEntry::find($id);
        $employeeLeaveEntry->management_status = $request->input('management_status');
        $employeeLeaveEntry->final_status = $request->input('management_status');
        $employeeLeaveEntry->save();
        return redirect()->route('employee_leave_approve_manag.index')->with([
                    'message' => 'successfully updated !',
                    'alert-type' => 'info'
        ]);
    }
}
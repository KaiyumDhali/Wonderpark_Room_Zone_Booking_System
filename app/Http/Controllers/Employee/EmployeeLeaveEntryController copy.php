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

class EmployeeLeaveEntryController extends Controller
{

    function __construct()
    {
        $this->middleware('permission:read leave entry|create leave entry', ['only' => ['index','show']]);
        $this->middleware('permission:create leave entry', ['only' => ['create','store']]);
    }

    public function leaveEntryBranchDetails($id)
    {
        $employees = EmployeeLeaveSetting::select('employee_leave_settings.employee_id')
        ->with(['employee:id,employee_name'])
        ->where('branch_id', $id)
        ->orderBy('id', 'desc')
        ->get();

        $employeeDetails = EmployeeLeaveEntry::select('employee_leave_entries.*')
                        ->with(['employeeId:id,employee_name', 'alternativeEmployeeId:id,employee_name'])
                        ->where('branch_id', $id)
                        ->orderBy('id', 'desc')
                        ->get();

        return response()->json([
            'employees' => $employees,
            'employeeDetails' => $employeeDetails,
        ]);
    }
    // public function employeeLeaveEntryDetails2($id)
    // {
    //     $employeeDetails = EmployeeLeaveEntry::select('employee_leave_entries.*', 'employee_leave_settings.total_leave','employee_leave_settings.casual_leave','employee_leave_settings.sick_leave','employee_leave_settings.annual_leave','employee_leave_settings.special_leave')
    //         ->join('employee_leave_settings', 'employee_leave_settings.employee_id', '=', 'employee_leave_entries.employee_id')
    //         ->with(['employeeId:id,employee_name', 'alternativeEmployeeId:id,employee_name'])
    //         ->where('employee_leave_entries.employee_id', $id) // Assuming employee_id is a column in employee_leave_entries
    //         ->where('employee_leave_entries.leave_year', '2023') // Assuming employee_id is a column in employee_leave_entries
    //         ->orderBy('employee_leave_entries.id', 'desc') // Assuming id is a column in employee_leave_entries
    //         ->get();

    //     if ($employeeDetails->isEmpty()) {
    //         $employeeDetails = EmployeeLeaveSetting::select('employee_leave_settings.total_leave','employee_leave_settings.casual_leave','employee_leave_settings.sick_leave','employee_leave_settings.annual_leave','employee_leave_settings.special_leave')
    //             ->where('employee_id', $id)
    //             ->where('leave_year', '2024')
    //             ->get();
    //     }
    //     return response()->json($employeeDetails);
    // }
    public function employeeLeaveEntryDetails($id)
    {
        $date = Carbon::now()->format('Y');
        $employeeDetails = EmployeeLeaveSetting::leftjoin('employee_leave_entries', 'employee_leave_entries.employee_id', '=', 'employee_leave_settings.employee_id', 'employee_leave_entries.leave_year', '=', 'employee_leave_settings.leave_year')
        ->select('employee_leave_settings.id as leaveSettingsId', 'employee_leave_settings.employee_id', 'employee_leave_settings.casual_leave', 'employee_leave_settings.sick_leave', 'employee_leave_settings.annual_leave', 'employee_leave_settings.special_leave', 'employee_leave_settings.total_leave', 'employee_leave_entries.*')
        ->with(['employee:id,employee_name', 'alternativeEmployeeId:id,employee_name'])
        ->where('employee_leave_settings.employee_id', $id) 
        ->where('employee_leave_settings.leave_year', $date)
        ->orderBy('employee_leave_settings.id', 'desc')
        ->get();
        
        return response()->json($employeeDetails);
    }
    
    public function employeeProfileLeaveEntry()
    {
        $id = Auth::user()->id;

        $employee = Employee::leftJoin('emp_branches', 'emp_branches.id', '=', 'employees.branch_id')
        ->select('employees.*', 'emp_branches.branch_name')
        ->where('user_id', $id)->first();

        // dd($employee);
        return view('pages.employee.employee_leave_entry.profile_leave_entry', compact('employee'));
    }

    public function index()
    {
        $allEmpBranch = EmpBranch::where('status', 1)->pluck('branch_name', 'id')->all();
        return view('pages.employee.employee_leave_entry.index', compact('allEmpBranch'));
    }

    public function store(Request $request)
    {
        $input = $request->all();

        // dd($input);
        $employeeLeaveEntry = new EmployeeLeaveEntry();
        $employeeLeaveEntry->leave_year = $request->input('leave_year');
        $employeeLeaveEntry->leave_settings_id = $request->input('leaveSettingId');
        $employeeLeaveEntry->branch_id = $request->input('branch_id');
        $employeeLeaveEntry->employee_id = $request->input('employee_id');
        $employeeLeaveEntry->alternative_employee_id = $request->input('alternative_employee_id');
        $employeeLeaveEntry->leave_application_date = $request->input('leave_application_date');
        $employeeLeaveEntry->leave_start_date = $request->input('leave_start_date');
        $employeeLeaveEntry->leave_end_date = $request->input('leave_end_date');
        $employeeLeaveEntry->reporting_date = $request->input('reporting_date');
        $employeeLeaveEntry->leave_type = $request->input('leave_type');
        $employeeLeaveEntry->total_days = $request->input('total_leave_days');
        
        $employeeLeaveEntry->reason_for_leave = $request->input('reason_for_leave');
        $employeeLeaveEntry->remarks = $request->input('remarks');
        $employeeLeaveEntry->done_by =Auth::user()->name;

        // dd($employeeLeaveEntry);
        $employeeLeaveEntry->save();
        
        return back()->with([
            'message' => 'successfully created !',
            'alert-type' => 'success'
        ]);
        // return redirect()->route('employee_leave_entry.index')->with([
        //     'message' => 'successfully created !',
        //     'alert-type' => 'success'
        // ]);
    }

    public function leaveReport($id)
    {
        $date = Carbon::now()->format('Y');
        $employeeDetails = EmployeeLeaveSetting::leftjoin('employee_leave_entries', 'employee_leave_entries.employee_id', '=', 'employee_leave_settings.employee_id', 'employee_leave_entries.leave_year', '=', 'employee_leave_settings.leave_year')
            ->select('employee_leave_settings.*', 'employee_leave_entries.*')
            ->with(['employee:id,employee_name', 'alternativeEmployeeId:id,employee_name'])
            ->where('employee_leave_settings.employee_id', $id) 
            ->where('employee_leave_settings.leave_year', $date)
            ->orderBy('employee_leave_settings.id', 'desc')
            ->get();

        return view('pages.employee.leave_report.index', compact('employeeDetails'));
    }
}
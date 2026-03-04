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
use Barryvdh\DomPDF\Facade\Pdf;

class EmployeeLeaveEntryController extends Controller
{

    function __construct()
    {
        $this->middleware('permission:read leave entry|create leave entry', ['only' => ['index','show']]);
        $this->middleware('permission:create leave entry', ['only' => ['create','store']]);
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

    // Leave Entry List
    public function employeeLeaveEntryList()
    {
        $employees = Employee::where('status', 1)->pluck('employee_name', 'employee_code')->all();
        $allEmpSection = EmpSection::where('status', 1)->pluck('section_name', 'id')->all();
        $startDate = Carbon::now()->format('1-m-Y');
        $endDate = Carbon::now()->format('d-m-Y');

        return view('pages.employee.employee_leave_entry.leave_entry_list', compact( 'startDate', 'endDate', 'employees', 'allEmpSection'));
    }
    // Leave Entry List Search
    public function leaveEntryListSearch($sectionID, $employeeID, $startDate, $endDate, $pdf)
    {
        $query = "
        SELECT
            le.id AS leaveID,
            (SELECT section_name FROM emp_sections WHERE id = le.section_id) AS SectionName,
            (SELECT employee_name FROM employees WHERE id = le.employee_id) AS EmployeeName,
            (SELECT employee_name FROM employees WHERE id = le.alternative_employee_id) AS AlternativeEmployeeName,
            le.leave_type AS LeaveType,
            le.leave_application_date AS LeaveApplicationDate,
            le.leave_start_date AS LeaveStartDate,
            le.leave_end_date AS LeaveEndDate,
            le.total_days AS TotalDays,
            IFNULL(le.remarks, '') AS Remarks,
            IF(le.final_status = 1, 'Approved', 'Pending') AS FinalStatus
        FROM
            employee_leave_entries le
        WHERE
            (
                CAST(le.leave_start_date AS DATE) BETWEEN CAST('$startDate' AS DATE) AND CAST('$endDate' AS DATE)
                OR CAST(le.leave_end_date AS DATE) BETWEEN CAST('$startDate' AS DATE) AND CAST('$endDate' AS DATE)
                OR CAST('$startDate' AS DATE) BETWEEN CAST(le.leave_start_date AS DATE) AND CAST(le.leave_end_date AS DATE)
                OR CAST('$endDate' AS DATE) BETWEEN CAST(le.leave_start_date AS DATE) AND CAST(le.leave_end_date AS DATE)
            )
            AND (le.section_id = $sectionID OR $sectionID = 0)
            AND (le.employee_id = $employeeID OR $employeeID = 0)
        ";
        $employeeDetails = DB::table(DB::raw("($query) AS subquery"))
                ->select('leaveID', 'SectionName', 'EmployeeName', 'AlternativeEmployeeName','LeaveType', 'LeaveApplicationDate', 'LeaveStartDate','LeaveEndDate','TotalDays','Remarks', 'FinalStatus')
                ->orderByDesc('leaveID')
                ->get();

         if ($pdf == "list") {
            return response()->json($employeeDetails);
        }
        if ($pdf == "pdfurl") {
            $data['section_id'] = $sectionID;
            $data['start_date'] = $startDate;
            $data['end_date'] = $endDate;
            $pdf = PDF::loadView('pages.pdf.all_leave_entry_report_pdf', array('employeeDetails' => $employeeDetails, 'data' => $data));
            return $pdf->stream(Carbon::now().'-recentstat.pdf');
        }
    }
    // Section Wais Employee
    public function sectionWaisEmployee($sectionID)
    {
        $employees = DB::table('employees')
        ->leftJoin('emp_postings', function ($join) {
            $join->on('emp_postings.employee_id', '=', 'employees.id')
                ->whereColumn('emp_postings.joining_date', '=', 'employees.posting_date');
        })
        ->where('employees.status', 1)
        ->where('emp_postings.section_id', $sectionID)
        ->select('employees.id', 'employees.employee_name')
        ->get();

        return response()->json($employees);
    }
    // Employee leave list delete button on click destroy
    public function employeeLeaveEntryListDestroy($id) {
        $employeeLeaveEntry = EmployeeLeaveEntry::find($id);
        $employeeLeaveEntry->delete();

        return back()->with([
            'message' => 'successfully deleted !',
            'alert-type' => 'danger'
        ]);
    }
    
    // section wise employee list and leave entry details 
    public function leaveEntrySectionWiseEmployeeDetails($id)
    {
        $employees = EmployeeLeaveSetting::select('employee_leave_settings.employee_id')
        ->with(['employee:id,employee_name'])
        ->where('section_id', $id)
        ->orderBy('id', 'desc')
        ->get();

        $employeeDetails = EmployeeLeaveEntry::select('employee_leave_entries.*')
                        ->with(['employeeId:id,employee_name', 'alternativeEmployeeId:id,employee_name'])
                        ->where('section_id', $id)
                        ->orderBy('id', 'desc')
                        ->get();

        return response()->json([
            'employees' => $employees,
            'employeeDetails' => $employeeDetails,
        ]);
    }

    // employee on change due and leave entry details list
    public function employeeLeaveEntryDetails($id)
    {
        $year = Carbon::now()->format('Y');
        $employeeDetails = EmployeeLeaveSetting::leftJoin('employee_leave_entries', function ($join) {
                $join->on('employee_leave_entries.employee_id', '=', 'employee_leave_settings.employee_id')
                    ->on('employee_leave_entries.leave_year', '=', 'employee_leave_settings.leave_year');
            })
            ->select('employee_leave_settings.id as leaveSettingsId', 'employee_leave_settings.leave_year as leaveSettingsYear', 'employee_leave_settings.employee_id', 'employee_leave_settings.casual_leave', 'employee_leave_settings.sick_leave', 'employee_leave_settings.annual_leave', 'employee_leave_settings.special_leave', 'employee_leave_settings.total_leave', 'employee_leave_entries.*')
            ->with(['employee:id,employee_name', 'alternativeEmployeeId:id,employee_name'])
            ->where('employee_leave_settings.employee_id', $id)
            ->where('employee_leave_settings.leave_year', $year)
            ->orWhere(function ($query) use ($id, $year) {
                $query->where('employee_leave_entries.leave_year', $year)
                    ->where('employee_leave_entries.employee_id', $id);
            })
            ->orderBy('employee_leave_settings.id', 'desc')
            ->get();
        
        return response()->json($employeeDetails);
    }

    // Open index
    public function index()
    {
        $allEmpSection = EmpSection::where('status', 1)->pluck('section_name', 'id')->all();
        return view('pages.employee.employee_leave_entry.index', compact('allEmpSection'));
    }

    // employee leave entry save 
    public function store(Request $request)
    {
        $input = $request->all();

        // dd($input);
        $employeeLeaveEntry = new EmployeeLeaveEntry();
        $employeeLeaveEntry->leave_year = $request->input('leave_year');
        $employeeLeaveEntry->leave_settings_id = $request->input('leaveSettingId');
        $employeeLeaveEntry->section_id = $request->input('section_id');
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
        
        // return back()->with([
        //     'message' => 'successfully created !',
        //     'alert-type' => 'success'
        // ]);
        return redirect()->route('employee_leave_entry_list')->with([
            'message' => 'successfully created !',
            'alert-type' => 'success'
        ]);
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
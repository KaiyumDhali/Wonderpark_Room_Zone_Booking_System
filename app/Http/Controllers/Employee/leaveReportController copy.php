<?php
namespace App\Http\Controllers\Employee;
use App\Http\Controllers\Controller;
use App\Models\EmployeeLeaveSetting;
use App\Models\EmployeeLeaveEntry;
use App\Models\Employee;
use App\Models\EmpBranch;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Carbon\Carbon;
use validator;
use Auth;
use Barryvdh\DomPDF\Facade\Pdf;

// use Illuminate\Support\Facades\DB;
use DB;

class leaveReportController extends Controller
{
    // public function index()
    // {
    //     $date = Carbon::now()->format('Y');
    //     $allEmpBranch = EmpBranch::where('status', 1)->pluck('branch_name', 'id')->all();

    //     $employeeDetails = EmployeeLeaveSetting::leftjoin('employee_leave_entries', 'employee_leave_entries.employee_id', '=', 'employee_leave_settings.employee_id', 'employee_leave_entries.leave_year', '=', 'employee_leave_settings.leave_year')
    //         ->select('employee_leave_settings.*', 'employee_leave_entries.*')
    //         ->with(['employee:id,employee_name', 'alternativeEmployeeId:id,employee_name'])
    //         ->where('employee_leave_settings.leave_year', $date)
    //         ->orderBy('employee_leave_settings.id', 'desc')
    //         ->get();

    //     return view('pages.employee.leave_report.index', compact('employeeDetails', 'allEmpBranch'));
    // }

    public function index()
    {
        $allEmpBranch = EmpBranch::where('status', 1)->pluck('branch_name', 'id')->all();        
        return view('pages.employee.leave_report.index', compact('allEmpBranch'));
    }

    public function leaveReport($year, $month, $pdf)
    {
        // $month = 'October';
        // $year = '2023';
        $query = "
            WITH LeaveData AS (
                SELECT 
                    es.employee_id as eid, 
                    (select e.order from employees e where e.id = es.employee_id) as eorder,
                    (select e.employee_code from employees e where e.id = es.employee_id) as employee_code,
                    (select e.employee_name from employees e where e.id = es.employee_id) as employee_name,
                    (select ed.department_name from emp_departments ed where ed.id = es.department_id) as department_name,
                    (select edg.designation_name from emp_designations edg where edg.id = (select e.designation_id from employees e where e.id = es.employee_id)) as designation_name,
                    (select IFNULL(SUM(ee.total_days), 0) from employee_leave_entries ee WHERE ee.leave_settings_id = es.id and ee.final_status = 1 and DATE_FORMAT(ee.leave_start_date, '%M') = '$month' and ee.leave_type = 'Casual') AS casual,
                    (select IFNULL(SUM(ee.total_days), 0) from employee_leave_entries ee WHERE ee.leave_settings_id = es.id and ee.final_status = 1 and DATE_FORMAT(ee.leave_start_date, '%M') = '$month' and ee.leave_type = 'Sick') AS sick,
                    (select IFNULL(SUM(ee.total_days), 0) from employee_leave_entries ee WHERE ee.leave_settings_id = es.id and ee.final_status = 1 and DATE_FORMAT(ee.leave_start_date, '%M') = '$month' and ee.leave_type = 'Annual') AS annual,
                    (select IFNULL(SUM(ee.total_days), 0) from employee_leave_entries ee WHERE ee.leave_settings_id = es.id and ee.final_status = 1 and DATE_FORMAT(ee.leave_start_date, '%M') = '$month' and ee.leave_type = 'Special') AS special,
                    (select IFNULL(SUM(ee.total_days), 0) from employee_leave_entries ee WHERE ee.leave_settings_id = es.id and ee.final_status = 1) AS Availed,
                    es.total_leave
                FROM employee_leave_settings es 
                WHERE es.leave_year = '$year' 
            ),
            LateData AS (
                SELECT a.employee_id as EmployeeCode, MAX(a.date_time_record) AS MaxDateTimeRecord
                FROM attendances a
                WHERE TIME(a.date_time_record) > TIME('10:30') AND DATE_FORMAT(a.date_only_record, '%M') = '$month' AND DATE_FORMAT(a.date_only_record, '%Y') = '$year'
                GROUP BY a.employee_id, a.date_only_record
            )

            SELECT *, (SELECT COUNT(EmployeeCode) FROM LateData ld WHERE ld.EmployeeCode = employee_code) AS LateCount, (total_leave - Availed) AS LeaveBalance FROM LeaveData ORDER BY eorder
        ";
        $employeeDetails = DB::select($query);

        if ($pdf == "list") {
            return response()->json($employeeDetails);
        }
        if ($pdf == "pdfurl") {
            $data['branch_id'] = '';
            $data['month'] = $month;
            $data['year'] = $year;
            $pdf = PDF::loadView('pages.pdf.employee_leave_report_pdf', array('employeeDetails' => $employeeDetails, 'data' => $data));
            return $pdf->stream(Carbon::now().'-recentstat.pdf');
        }

    }
}
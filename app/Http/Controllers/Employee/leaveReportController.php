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
    public function addLateOFLeave(Request $request)
    {
        $selectedData = $request->input('selectedData');
        $done_by = Auth::user()->name;
        $final_status = 1;
        try {
            foreach ($selectedData as $data) {
                // Convert month name to month number
                $monthNumber = Carbon::parse($data['month'])->month;

                // Create start and end dates
                $date = Carbon::create($data['year'], $monthNumber, 1)->toDateString();
                //$endDate = Carbon::create($data['year'], $monthNumber, 1)->endOfMonth()->toDateString();

                // Assuming 'employee_id' is a unique identifier
                if ($data['late_count'] > 0) {
                    $existingEntry = EmployeeLeaveEntry::where('employee_id', $data['selected_users'])
                        ->where('late_of_leave_month', $date)
                        ->first();
                    if ($existingEntry) {
                        // Update the existing entry
                        $existingEntry->update([
                            'leave_year' => $data['year'],
                            'leave_type' => 'Casual',
                            'no_of_late' => $data['late_count'],
                            'total_days' => $data['late_leave'],
                            'final_status' => $final_status,
                            'done_by' => $done_by,
                            // Add other fields as needed
                        ]);
                    } else {
                        // Create a new entry
                        EmployeeLeaveEntry::create([
                            'leave_year' => $data['year'],
                            'leave_type' => 'Casual',
                            'employee_id' => $data['selected_users'],
                            'no_of_late' => $data['late_count'],
                            'total_days' => $data['late_leave'],
                            'final_status' => $final_status,
                            'late_of_leave_month' => $date,
                            'remarks' => 'Monthly Late Leave',
                            'done_by' => $done_by,
                            // Add other fields as needed
                        ]);
                    }
                }
            }
            return response()->json(['message' => 'Data submitted successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function lateOFLeave()
    {
        $allEmpBranch = EmpBranch::where('status', 1)->pluck('branch_name', 'id')->all();   
        return view('pages.employee.leave_report.late_of_leave', compact('allEmpBranch'));
    }
    
    public function lateOFLeaveReport($year, $month, $pdf)
    {
        // $year = 2023;
        // $month = 'September';

        // Convert month name to month number
        $monthNumber = Carbon::parse($month)->month;

        // Create start and end dates
        $startDate = Carbon::create($year, $monthNumber, 1)->toDateString();
        $endDate = Carbon::create($year, $monthNumber, 1)->endOfMonth()->toDateString();

        $query = "
            WITH LeaveData AS (
                SELECT 
                    es.employee_id as eid, 
                    (select e.order from employees e where e.id = es.employee_id) as eorder,
                    (select e.employee_code from employees e where e.id = es.employee_id) as employee_code,
                    (select e.employee_name from employees e where e.id = es.employee_id) as employee_name,
                    (SELECT IFNULL(SUM(le.total_days), 0) FROM employee_leave_entries le WHERE le.leave_year ='$year' AND le.employee_id = es.employee_id AND le.leave_type = 'Casual' AND le.final_status = 1) as Approve_Casual_Leave,
                es.casual_leave as Total_Casual_Leave
                FROM employee_leave_settings es 
                WHERE es.leave_year = '$year'
            ),
            
            LateData AS (
                SELECT *
                FROM
                    ( select * from ( SELECT *        
                        FROM
                            attendances a
                                JOIN (
                                SELECT ADDDATE('1970-01-01', t4.i * 10000 + t3.i * 1000 + t2.i * 100 + t1.i * 10 + t0.i) AS _date
                                FROM 
                                    (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9 )t0,
                                    (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t1,
                                    (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t2,
                                    (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t3,
                                    (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t4
                                )v on v._date=a.date_only_record
                        WHERE
                            v._date BETWEEN '$startDate' AND '$endDate'    
                )v1
                group by v1.date_only_record, v1.employee_code)f 
                WHERE Time(f.date_time_record) > time((SELECT late_time FROM late_times ORDER BY id DESC LIMIT 1))
            )
            SELECT *,  (Total_Casual_Leave - Approve_Casual_Leave) AS Deu_Casual_leave, 
            if( ((SELECT COUNT(employee_code) FROM LateData ld WHERE ld.employee_code = employee_code)-(SELECT COUNT(id) FROM `employee_delayin_earlyouts` as dl WHERE dl.date BETWEEN '$startDate' AND '$endDate' AND dl.employee_id = eid)) >0, ((SELECT COUNT(employee_code) FROM LateData ld WHERE ld.employee_code = employee_code)-(SELECT COUNT(id) FROM `employee_delayin_earlyouts` as dl WHERE dl.date BETWEEN '$startDate' AND '$endDate' AND dl.employee_id = eid)) ,0)
            AS Late_Count
            FROM LeaveData ORDER BY eorder
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

    // leave report index
    public function index()
    {
        $allEmpBranch = EmpBranch::where('status', 1)->pluck('branch_name', 'id')->all();   
        return view('pages.employee.leave_report.index', compact('allEmpBranch'));
    }
 
    // final leave report index list get
    public function leaveReport($year, $month, $pdf)
    {
        // $month = 'September';
        // $year = '2023';
        $query = "
        WITH LeaveData AS (
            SELECT 
                es.employee_id as eid, 
                (select e.order from employees e where e.id = es.employee_id) as eorder,
                (select e.employee_code from employees e where e.id = es.employee_id) as employee_code,
                (select e.employee_name from employees e where e.id = es.employee_id) as employee_name,
                (select IFNULL(SUM(ee.total_days), 0) from employee_leave_entries ee WHERE ee.leave_settings_id = es.id and ee.final_status = 1 and DATE_FORMAT(ee.leave_start_date, '%M') = '$month' and ee.leave_type = 'Casual') AS casual,
                (select IFNULL(SUM(ee.total_days), 0) from employee_leave_entries ee WHERE ee.leave_settings_id = es.id and ee.final_status = 1 and DATE_FORMAT(ee.leave_start_date, '%M') = '$month' and ee.leave_type = 'Sick') AS sick,
                (select IFNULL(SUM(ee.total_days), 0) from employee_leave_entries ee WHERE ee.leave_settings_id = es.id and ee.final_status = 1 and DATE_FORMAT(ee.leave_start_date, '%M') = '$month' and ee.leave_type = 'Annual') AS annual,
                (select IFNULL(SUM(ee.total_days), 0) from employee_leave_entries ee WHERE ee.leave_settings_id = es.id and ee.final_status = 1 and DATE_FORMAT(ee.leave_start_date, '%M') = '$month' and ee.leave_type = 'Special') AS special,

                (select IFNULL(SUM(ee.total_days), 0) from employee_leave_entries ee WHERE ee.leave_year = '$year' AND ee.employee_id = es.employee_id AND ee.final_status = 1 ) AS LeaveAvailed,

                (select IFNULL(SUM(ee.total_days), 0) from employee_leave_entries ee WHERE ee.leave_year = '$year' AND DATE_FORMAT(ee.late_of_leave_month, '%M')= '$month' AND ee.employee_id = es.employee_id  AND ee.final_status = 1 AND ee.leave_type = 'Casual') AS LateOfLeave,
                (select IFNULL(SUM(ee.no_of_late), 0) from employee_leave_entries ee WHERE ee.leave_year = '$year' AND DATE_FORMAT(ee.late_of_leave_month, '%M')= '$month' AND ee.employee_id = es.employee_id  AND ee.final_status = 1 AND ee.leave_type = 'Casual') AS NoOfLate,

                es.total_leave
            FROM employee_leave_settings es 
            WHERE es.leave_year = '$year' 
        )
        SELECT *, (LateOfLeave+casual) AS TotalCasual, (total_leave - LeaveAvailed) AS LeaveBalance FROM LeaveData ORDER BY eorder";
        
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
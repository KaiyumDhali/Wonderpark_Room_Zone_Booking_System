<?php

namespace App\Http\Controllers\hradmin;

use App\Http\Controllers\Controller;
use App\Models\EmpBranch;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Customer;
use App\Models\EmpDesignation;
use Illuminate\Http\Request;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class AttendanceController extends Controller {

    function __construct() {
        $this->middleware('permission:read attendance|write attendance|create attendance', ['only' => ['index', 'show']]);
        $this->middleware('permission:create attendance', ['only' => ['create', 'store']]);
        $this->middleware('permission:write attendance', ['only' => ['edit', 'update', 'destroy']]);
    }

    // admin deshboard 
    public function dailyAllAttendance()
    {
        // $dailyAttendances = DB::table('employees AS e')
        //                 ->select(
        //                     'e.employee_code AS ID',
        //                     'e.employee_name AS EmployeeName',
        //                     'e.order AS EmployeeOrder',
        //                     DB::raw("(SELECT department_name FROM emp_departments where id=e.department_id) AS department_name"),
        //                     DB::raw("(SELECT designation_name FROM emp_designations where id=e.designation_id) AS designation_name"),
        //                     DB::raw("'$startDate' AS _date"),
        //                     DB::raw("(SELECT DATE_FORMAT(date_time_record, '%h:%i:%s') FROM attendances WHERE employee_id = e.employee_code AND date_only_record = '$startDate' LIMIT 1) AS InTime"),
        //                     DB::raw("(SELECT DATE_FORMAT(date_time_record, '%h:%i:%s') FROM attendances WHERE employee_id = e.employee_code AND date_only_record = '$startDate' ORDER BY id DESC LIMIT 1) AS OutTime"),
        //                     DB::raw("DAYNAME('$startDate') AS Day")
        //                 )
        //                 ->orderby('order', 'asc')->get();
        // return response()->json($dailyAttendances);
    }

    // employee deshboard
    public function singleEmployeeAttendanceSearch($employeeCode)
    {
        $employeeCode = $employeeCode;
        $startDate = '2023-09-01';
        $endDate = '2023-09-30';

        // $startDate = date('Y-m-01');
        // $endDate = date('Y-m-d');
        $lateTime = '09:37:00';
        $query = "
        select *, DATE_FORMAT(TIMEDIFF('$lateTime', t.InTime),'%H:%i:%s') AS LateTime, DATE_FORMAT(TIMEDIFF(t.OutTime, t.InTime),'%H:%i:%s') AS WorkTime from(
            SELECT
                '$employeeCode' AS ID,
                (SELECT employee_name FROM employees WHERE employee_code = '$employeeCode') AS EmployeeName,
                (SELECT department_name FROM emp_departments where id=(SELECT department_id FROM employees WHERE employee_code = '$employeeCode')) AS department_name,
                (SELECT designation_name FROM emp_designations where id= (SELECT designation_id FROM employees WHERE employee_code = '$employeeCode')) AS designation_name,
                _date,
                
                if((SELECT COUNT(ed.id) FROM employee_delayin_earlyouts  ed WHERE ed.employee_id=(SELECT employees.id FROM employees WHERE employee_code = '$employeeCode') and ed.date=_date and ed.status='out_of_office')>0,'out_of_office',
            
                    if ((SELECT COUNT(ed.id) FROM employee_delayin_earlyouts  ed WHERE ed.employee_id=(SELECT employees.id FROM employees WHERE employee_code = '$employeeCode') and ed.date=_date and ed.status='absences')>0,'absences',

                    IF((SELECT COUNT(le.id) FROM employee_leave_entries le where le.employee_id=(SELECT employees.id FROM employees WHERE employee_code = '$employeeCode') and _date BETWEEN le.leave_start_date and le.leave_end_date and le.final_status=1)>0,'Leave',(SELECT   DATE_FORMAT(date_time_record, '%H:%i:%s') FROM attendances WHERE employee_id ='$employeeCode'   AND date_only_record = _date LIMIT 1))
            )) AS InTime,
                    
                                
                    (SELECT DATE_FORMAT(date_time_record, '%H:%i:%s') FROM attendances WHERE employee_id = (SELECT employees.employee_code FROM employees WHERE employee_code = '$employeeCode') AND date_only_record = _date ORDER BY id DESC LIMIT 1) AS OutTime,
                
                
                DAYNAME(_date) AS Day
            FROM
                (SELECT ADDDATE('1970-01-01', t4.i * 10000 + t3.i * 1000 + t2.i * 100 + t1.i * 10 + t0.i) AS _date FROM
                    (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t0,
                    (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t1,
                    (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t2,
                    (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t3,
                    (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t4
                ) v
            WHERE
                _date BETWEEN '$startDate' AND '$endDate') t 
        ";

        $result = DB::table(DB::raw("($query) AS subquery"))
            ->select('_date','InTime','OutTime','Day', 'LateTime', 'WorkTime')
            ->orderby('_date', 'desc')
            ->get();

        return response()->json($result);
    }
    

    public function singleEmployeeAttendanceChart($employeeCode)
    {

        $employeeCode = '2';
        $startDate = '2023-09-01';
        $endDate = '2023-09-30';

        // $startDate = date('Y-m-01');
        // $endDate = date('Y-m-d');

        $query = "
            select COUNT(t.ID) AS TotalDay,(SUM(t.present)+SUM(t.out_of_office))AS Present, SUM(t.late_in) AS late_in,SUM(t.monthly_leave) AS monthly_leave,SUM(t.holiday) AS holiday,SUM(t.weekend) AS weekend  from(

                SELECT
                    '$employeeCode' AS ID,
                    (SELECT employee_name FROM employees WHERE employee_code = '$employeeCode') AS EmployeeName,
                    (SELECT department_name FROM emp_departments where id=(SELECT department_id FROM employees WHERE employee_code = '$employeeCode')) AS department_name,
                    (SELECT designation_name FROM emp_designations where id= (SELECT designation_id FROM employees WHERE employee_code = '$employeeCode')) AS designation_name,
                    _date,
                    DAYNAME(_date) AS Day,
                            
                    if((SELECT DATE_FORMAT(date_time_record, '%h:%i:%s') FROM attendances WHERE employee_id = (SELECT employees.employee_code FROM employees WHERE employee_code = '$employeeCode') AND date_only_record = _date ORDER BY id ASC LIMIT 1) is not null, 1, 0) AS present,
        
                    if((SELECT DATE_FORMAT(a.date_time_record, '%h:%i:%s') FROM attendances a WHERE TIME(a.date_time_record)  AND a.employee_id = (SELECT employees.employee_code FROM employees WHERE employee_code = '$employeeCode') AND a.date_only_record = _date ORDER BY a.id ASC LIMIT 1)> TIME('09:37'),1,0)    AS late_in,
        
                    if((SELECT ed.status FROM employee_delayin_earlyouts  ed WHERE ed.employee_id=(SELECT employees.id FROM employees WHERE employee_code = '$employeeCode') and ed.date=_date and ed.status='out_of_office') is null ,0,1) AS out_of_office,
        
                    if((SELECT le.id FROM employee_leave_entries le WHERE le.employee_id=(SELECT employees.id FROM employees WHERE employee_code = '$employeeCode') and _date BETWEEN le.leave_start_date and le.leave_end_date and le.final_status=1) is null, 0, 1 )AS monthly_leave,
                        
                    if((SELECT hd.id FROM holidays hd WHERE _date BETWEEN hd.date and hd.end_date) is null, 0, 1 )AS holiday,
                        
                    if(DAYNAME(_date) IN('Friday', 'Saturday'),1,0) AS weekend
                FROM
                    (SELECT ADDDATE('1970-01-01', t4.i * 10000 + t3.i * 1000 + t2.i * 100 + t1.i * 10 + t0.i) AS _date FROM
                        (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t0,
                        (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t1,
                        (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t2,
                        (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t3,
                        (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t4
                    ) v
                WHERE
                    _date BETWEEN '$startDate' AND '$endDate') t 
        ";

        $result = DB::table(DB::raw("($query) AS subquery"))
            ->select('TotalDay','Present','late_in','monthly_leave', 'holiday', 'weekend')
            ->first();

        $workingDay = $result->TotalDay - ($result->holiday + $result->weekend);
        $Present = intval($result->Present);
        $Absent = $workingDay - ($result->Present + $result->monthly_leave);
        $LateIn = intval($result->late_in);
        $Leave = intval($result->monthly_leave);
        $chartData = [
            ['Task', 'Hours per Day'],
            ['Present', $Present],
            ['Absent', $Absent],
            ['Late In', $LateIn],
            ['Leave', $Leave],
        ];
        return response()->json($chartData);
    }


    public function singleEmployeeAllAttendanceSearch() {
        $userId = Auth::id();

        // Assuming there is a 'users' table with 'id' as the primary key
        $employee = Employee::where('user_id', $userId)->first(['employee_code','branch_id']);
        // dd($employee);

        if ($employee) {
            // Calculate the end date
            $endDate = now()->format('d-m-Y');
            // Calculate the start date as 10 days before the end date
            $startDate = now()->subDays(9)->format('d-m-Y');

            return view('pages.hradmin.attendance.single_employee', compact('startDate', 'endDate', 'employee'));
        } else {
            // Handle the case where the employee is not found
            return redirect()->back()->with('error', 'Employee not found');
        }
    }

    // public function employeeAttendanceSearch($branchID, $startDate, $endDate, $employeeCode, $pdf)
    // {
    //     $query = "        
    //         SELECT
    //             '$employeeCode' AS ID,
    //             (SELECT employee_name FROM employees WHERE employee_code = '$employeeCode') AS EmployeeName,
    //             (SELECT department_name FROM emp_departments where id=(SELECT department_id FROM employees WHERE employee_code = '$employeeCode')) AS department_name,
    //             (SELECT designation_name FROM emp_designations where id= (SELECT designation_id FROM employees WHERE employee_code = '$employeeCode')) AS designation_name,
    //             _date,
                
    //             if((SELECT COUNT(ed.id) FROM employee_delayin_earlyouts  ed WHERE ed.employee_id=(SELECT employees.id FROM employees WHERE employee_code = '$employeeCode') and ed.date=_date and ed.status='out_of_office')>0,'Out Of Office',
                    
    //             if ((SELECT COUNT(ed.id) FROM employee_delayin_earlyouts  ed WHERE ed.employee_id=(SELECT employees.id FROM employees WHERE employee_code = '$employeeCode') and ed.date=_date and ed.status='absences')>0,'Absences',
                    
    //             IF((SELECT COUNT(le.id) FROM employee_leave_entries le where le.employee_id=(SELECT employees.id FROM employees WHERE employee_code = '$employeeCode') and _date BETWEEN le.leave_start_date and le.leave_end_date and le.final_status=1)>0,'Leave',(SELECT   DATE_FORMAT(date_time_record, '%h:%i:%s') FROM attendances WHERE employee_id ='$employeeCode'   AND date_only_record = _date LIMIT 1))
    //                 ))  
    //                 AS InTime,
    //             (SELECT DATE_FORMAT(date_time_record, '%h:%i:%s') FROM attendances WHERE employee_id = (SELECT employees.employee_code FROM employees WHERE employee_code = '$employeeCode') AND date_only_record = _date ORDER BY id DESC LIMIT 1) AS OutTime,
    //             DAYNAME(_date) AS Day
    //             FROM
    //                 (SELECT ADDDATE('1970-01-01', t4.i * 10000 + t3.i * 1000 + t2.i * 100 + t1.i * 10 + t0.i) AS _date FROM
    //                     (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t0,
    //                     (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t1,
    //                     (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t2,
    //                     (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t3,
    //                     (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t4
    //                 ) v
    //             WHERE
    //                 _date BETWEEN '$startDate' AND '$endDate'
    //         ";

    //     $result = DB::table(DB::raw("($query) AS subquery"))
    //         ->select('ID', 'EmployeeName','department_name','designation_name','_date','InTime','OutTime','Day')
    //         ->get();

    //     if ($pdf == "list") {
    //         return response()->json($result);
    //     }
    //     if ($pdf == "pdfurl") {
    //         $data['branch_id'] = $branchID;
    //         $data['start_date'] = $startDate;
    //         $data['end_date'] = $endDate;
    //         $pdf = PDF::loadView('pages.pdf.employee_attendance_report_pdf', array('dailyAttendances' => $result, 'data' => $data));
    //         return $pdf->stream(Carbon::now().'-recentstat.pdf');
    //     }
    // }
    
    // hradmin attendance ajax
    public function dailyAttendanceSearch($branchID, $employeeID, $startDate, $endDate, $pdf)
    {
        $query = "
            SELECT
                e.order as sn,
                e.employee_code AS ID,
                e.employee_name AS EmployeeName,
                (SELECT department_name FROM emp_departments WHERE id = e.department_id) AS department_name,
                (SELECT designation_name FROM emp_designations WHERE id = e.designation_id) AS designation_name,
                v._date,
                IF(
                    (SELECT COUNT(ed.id) FROM employee_delayin_earlyouts ed WHERE ed.employee_id = e.id AND ed.date = _date AND ed.status = 'out_of_office') > 0,
                    'Out Of Office',
                    IF(
                        (SELECT COUNT(ed.id) FROM employee_delayin_earlyouts ed WHERE ed.employee_id = e.id AND ed.date = _date AND ed.status = 'absences') > 0,
                        'Absences',
                        IF(
                            (SELECT COUNT(le.id) FROM employee_leave_entries le WHERE le.employee_id = e.id AND _date BETWEEN le.leave_start_date AND le.leave_end_date AND le.final_status = 1) > 0,
                            'Leave',
                            (SELECT DATE_FORMAT(date_time_record, '%h:%i:%s') FROM attendances WHERE employee_id = e.employee_code AND date_only_record = _date LIMIT 1)
                        )
                    )
                ) AS InTime,
                (SELECT DATE_FORMAT(date_time_record, '%h:%i:%s') FROM attendances WHERE employee_id = e.employee_code AND date_only_record = _date ORDER BY id DESC LIMIT 1) AS OutTime,
                DAYNAME(_date) AS Day
            FROM
                employees e
                    JOIN (
                    SELECT ADDDATE('1970-01-01', t4.i * 10000 + t3.i * 1000 + t2.i * 100 + t1.i * 10 + t0.i) AS _date
                    FROM (
                        SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9
                    ) t0,
                    (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t1,
                    (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t2,
                    (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t3,
                    (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t4
                )v
            WHERE
                _date BETWEEN '$startDate' AND '$endDate' AND (e.branch_id=$branchID or $branchID=0) AND (employee_code=$employeeID or $employeeID=0)
        ";

        $dailyAttendances = DB::table(DB::raw("($query) AS subquery"))
                ->select('ID', 'sn', 'EmployeeName','department_name','designation_name','_date','InTime','OutTime','Day')
                ->orderBy('_date')
                ->orderBy('sn')
                ->get();
        if ($pdf == "list") {
            return response()->json($dailyAttendances);
        }
        if ($pdf == "pdfurl") {
            $data['branch_id'] = $branchID;
            $data['start_date'] = $startDate;
            $data['end_date'] = $endDate;
            $pdf = PDF::loadView('pages.pdf.daily_attendance_report_pdf', array('dailyAttendances' => $dailyAttendances, 'data' => $data));
            return $pdf->stream(Carbon::now().'-recentstat.pdf');
        }
    }
    public function branchWaisEmployee($branchID)
    {
        $employees = Employee::where('status', 1)
        ->where('branch_id', $branchID)
        ->select('employees.employee_code', 'employees.employee_name')
        ->get();
        return response()->json($employees);
    }

    public function index() {
        $employees = Employee::where('status', 1)->pluck('employee_name', 'employee_code')->all();
        $allEmpBranch = EmpBranch::pluck('branch_name', 'id')->all();
        $startDate = Carbon::now()->format('d-m-Y');
        return view('pages.hradmin.attendance.index', compact( 'startDate', 'employees', 'allEmpBranch'));
    }

    public function create() {


        return view('pages.payroll.payslip_type.create');
    }

    public function store(Request $request) {
        
    }

    public function show(PayslipType $paysliptype) {
        return redirect()->route('paysliptype.index');
    }

    public function edit(PayslipType $paysliptype) {
        
    }

    public function update(Request $request, Promotion $attendance) {
        
    }

}
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

    // employee deshboard Chart
    public function singleEmployeeAttendanceChart($employeeCode)
    {
        // $employeeCode = '2';
        // $startDate = '2023-09-01';
        // $endDate = '2023-09-30';

        $startDate = date('Y-m-01');
        $endDate = date('Y-m-d');

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
        
                    if((SELECT DATE_FORMAT(a.date_time_record, '%h:%i:%s') FROM attendances a WHERE TIME(a.date_time_record)  AND a.employee_id = (SELECT employees.employee_code FROM employees WHERE employee_code = '$employeeCode') AND a.date_only_record = _date ORDER BY a.id ASC LIMIT 1)> TIME('09:37'),1,0) AS late_in,
        
                    if((SELECT ed.status FROM employee_delayin_earlyouts  ed WHERE ed.employee_id=(SELECT employees.id FROM employees WHERE employee_code = '$employeeCode') and ed.date=_date and ed.status='out_of_office') is null ,0,1) AS out_of_office,
        
                    if((SELECT le.id FROM employee_leave_entries le WHERE le.employee_id=(SELECT employees.id FROM employees WHERE employee_code = '$employeeCode') and _date BETWEEN le.leave_start_date and le.leave_end_date and le.final_status=1) is null, 0, 1 )AS monthly_leave,
                        
                    if((SELECT hd.id FROM holidays hd WHERE _date BETWEEN hd.date and hd.end_date) is null, 0, 1 )AS holiday,
                    
                    if((SELECT wd.id FROM weekend_days wd WHERE DAYNAME(v._date) = wd.weekend_day) is null, 0, 1 )AS weekend
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
        $Leave = intval($result->monthly_leave);
        $LateIn = intval($result->late_in);
        $Holiday = intval($result->holiday);
        $Weekend = intval($result->weekend);
        $Absent = $workingDay - ($Present + $Leave);

        // if ($Holiday == 0 && $Weekend == 0) {
        //     $Absent = $workingDay - ($Present + $Leave);
        // } else {
        //     $Absent = 0;
        // }
        
        $chartData = [
            ['Task', 'Hours per Day'],
            ['Present', $Present],
            ['Late In', $LateIn],
            ['Absent', $Absent],
            ['Leave', $Leave],
            ['Holiday', $Holiday],
            ['Weekend', $Weekend],
        ];
        return response()->json($chartData);
    }

    // employee deshboard current month
    public function singleEmployeeAttendanceSearch($employeeCode)
    {
        $startDate = date('Y-m-01');
        $endDate = date('Y-m-d');
        // $employeeCode = $employeeCode;
        // $startDate = '2023-09-01';
        // $endDate = '2023-09-30';
        // $lateTime = '09:37:00';
        $query = "
        select *, DATE_FORMAT(TIMEDIFF(t.late_time, t.InTime),'%H:%i:%s') AS LateTime, DATE_FORMAT(TIMEDIFF(t.OutTime, t.InTime),'%H:%i:%s') AS WorkTime from(
            SELECT
                '$employeeCode' AS ID,
                (SELECT employee_name FROM employees WHERE employee_code = '$employeeCode') AS EmployeeName,
                (SELECT department_name FROM emp_departments where id=(SELECT department_id FROM employees WHERE employee_code = '$employeeCode')) AS department_name,
                (SELECT designation_name FROM emp_designations where id= (SELECT designation_id FROM employees WHERE employee_code = '$employeeCode')) AS designation_name,
                (SELECT late_time FROM late_times  ORDER BY id DESC LIMIT 1) AS late_time,
                _date,
                
                IF((SELECT COUNT(ed.id) FROM employee_delayin_earlyouts  ed WHERE ed.employee_id=(SELECT employees.id FROM employees WHERE employee_code = '$employeeCode') and ed.date=_date and ed.status='out_of_office')>0,'Out Of Office',
            
                    IF ((SELECT COUNT(ed.id) FROM employee_delayin_earlyouts  ed WHERE ed.employee_id=(SELECT employees.id FROM employees WHERE employee_code = '$employeeCode') and ed.date=_date and ed.status='absences')>0,'Absences',

                        IF((SELECT COUNT(le.id) FROM employee_leave_entries le where le.employee_id=(SELECT employees.id FROM employees WHERE employee_code = '$employeeCode') and _date BETWEEN le.leave_start_date and le.leave_end_date and le.final_status=1)>0,'Leave',
                        
                            IF((SELECT COUNT(hd.id) FROM holidays hd WHERE _date BETWEEN hd.date and hd.end_date ) >0, (SELECT hd.occasion FROM holidays hd WHERE _date BETWEEN hd.date and hd.end_date LIMIT 1),

                                IF(DAYNAME(v._date)=(SELECT wd.weekend_day FROM weekend_days wd WHERE DAYNAME(v._date) = wd.weekend_day), 'Weekend',
                                    if( (SELECT  DATE_FORMAT(date_time_record, '%H:%i:%s') FROM attendances WHERE employee_id ='$employeeCode'   AND date_only_record = _date LIMIT 1)is null,'Absent',
                                        (SELECT  DATE_FORMAT(date_time_record, '%H:%i:%s') FROM attendances WHERE employee_id ='$employeeCode'   AND date_only_record = _date LIMIT 1)
                                    )
                                )
                            )
                        )
                    )
                ) AS InTime,
                    
                                
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

    // employee deshboard left menu Attendance report
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

    // admin deshboard Chart
    public function allEmployeeAttendanceChart()
    {
        $currentDate = date('Y-m-d');
        // $currentDate = '2023-09-03';
        // $lateTime = '09:37:00';
        $query = "
        select COUNT(ID) AS TotalEmployees,(SUM(t.present)+SUM(t.out_of_office))AS Present, SUM(t.out_of_office) AS out_of_office, SUM(t.late_in) AS late_in,SUM(t.monthly_leave) AS monthly_leave, t.holiday AS holiday, t.weekend AS weekend  from(
            SELECT
                employees.employee_code AS ID,
                employees.order AS sn,
                employees.employee_name AS EmployeeName,
                emp_departments.department_name,
                emp_designations.designation_name,
                v._date,
                DAYNAME(v._date) AS Day,
                       
                    (SELECT DATE_FORMAT(date_time_record, '%H:%i:%s') FROM attendances WHERE employee_id = employees.employee_code AND date_only_record = v._date ORDER BY id ASC LIMIT 1 ) AS InTime,

                    if((SELECT DATE_FORMAT(date_time_record, '%h:%i:%s') FROM attendances WHERE employee_id = employees.employee_code AND date_only_record = v._date ORDER BY id ASC LIMIT 1) is not null, 1, 0) AS present,

                    IFNULL( IF(TIME((SELECT DATE_FORMAT(date_time_record, '%H:%i:%s') FROM attendances WHERE employee_id = employees.employee_code AND date_only_record = v._date ORDER BY id ASC LIMIT 1)) > (SELECT late_time FROM late_times  ORDER BY id DESC LIMIT 1),1,0), 0) AS late_in,
                    
                    if((SELECT ed.status FROM employee_delayin_earlyouts  ed WHERE ed.employee_id=employees.id and ed.date=v._date and ed.status='out_of_office') is null ,0,1) AS out_of_office,

                    if((SELECT le.id FROM employee_leave_entries le WHERE le.employee_id = employees.id AND v._date BETWEEN le.leave_start_date AND le.leave_end_date AND le.final_status = 1) is null, 0, 1 )AS monthly_leave,

                    if((SELECT hd.id FROM holidays hd WHERE v._date BETWEEN hd.date and hd.end_date) is null, 0, 1 )AS holiday,

                    if((SELECT wd.id FROM weekend_days wd WHERE DAYNAME(v._date) = wd.weekend_day) is null, 0, 1 )AS weekend
            FROM
                employees
                LEFT JOIN emp_departments ON employees.department_id = emp_departments.id
                LEFT JOIN emp_designations ON employees.designation_id = emp_designations.id
                CROSS JOIN (
                    SELECT ADDDATE('$currentDate', t4.i * 10000 + t3.i * 1000 + t2.i * 100 + t1.i * 10 + t0.i) AS _date FROM
                    (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t0,
                    (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t1,
                    (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t2,
                    (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t3,
                    (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t4
                ) v
            WHERE
                v._date = '$currentDate') t
        ";

        $result = DB::table(DB::raw("($query) AS subquery"))
            ->select('TotalEmployees','Present','late_in', 'out_of_office', 'monthly_leave', 'holiday', 'weekend')
            ->first();

    
        $TotalEmployees = intval($result->TotalEmployees);
        $Present = intval($result->Present);
        $Leave = intval($result->monthly_leave);
        $OutOfOffice = intval($result->out_of_office);
        $LateIn = intval($result->late_in);
        $Holiday = intval($result->holiday);
        $Weekend = intval($result->weekend);

        if ($Holiday == 0 && $Weekend == 0) {
            $Absent = $TotalEmployees - ($Present + $OutOfOffice + $Leave);
        } else {
            $Absent = 0;
        }
        
        $chartData = [
            ['Task', 'Hours per Day'],
            ['Present', $Present],
            ['Late In', $LateIn],
            ['Out Of Office', $OutOfOffice],
            ['Absent', $Absent],
            ['Leave', $Leave],
            ['Holiday', $Holiday],
            ['Weekend', $Weekend],
        ];
        return response()->json($chartData);
    }

    // admin deshboard today report
    public function dailyAllAttendance()
    {
        $currentDate = date('Y-m-d');
        // $currentDate = '2023-09-04';
        //$lateTime = '10:37:00';
        $query = "
            select *, DATE_FORMAT(TIMEDIFF(a.late_time, a.InTime),'%H:%i:%s') AS LateTime, DATE_FORMAT(TIMEDIFF(a.OutTime, a.InTime),'%H:%i:%s') AS WorkTime from(
                SELECT
                    employees.employee_code AS ID,
                    employees.order AS sn,
                    employees.employee_name AS EmployeeName,
                    emp_departments.department_name,
                    emp_designations.designation_name,
                    (SELECT late_time FROM late_times  ORDER BY id DESC LIMIT 1) AS late_time,
                    t._date,
                    DAYNAME(t._date) AS Day,
                    
                        IF( (SELECT COUNT(ed.id) FROM employee_delayin_earlyouts ed WHERE ed.employee_id = employees.id
                        AND ed.date = t._date
                        AND ed.status = 'out_of_office') > 0, 'Out Of Office',
                        IF( (SELECT COUNT(ed.id) FROM employee_delayin_earlyouts ed WHERE ed.employee_id = employees.id
                            AND ed.date = t._date
                            AND ed.status = 'absences') > 0, 'Absent',
                            IF( (SELECT COUNT(le.id) FROM employee_leave_entries le WHERE le.employee_id = employees.id
                                AND t._date BETWEEN le.leave_start_date AND le.leave_end_date
                                AND le.final_status = 1) > 0, 'Leave',
                                
                                IF(
                                    (SELECT COUNT(hd.id) FROM holidays hd WHERE _date BETWEEN hd.date and hd.end_date ) >0, (SELECT hd.occasion FROM holidays hd WHERE t._date BETWEEN hd.date and hd.end_date LIMIT 1),
                                    IF(
                                        DAYNAME(t._date)=(SELECT wd.weekend_day FROM weekend_days wd WHERE DAYNAME(t._date) = wd.weekend_day), 'Weekend',

                                        IF( (SELECT DATE_FORMAT(date_time_record, '%H:%i:%s') FROM attendances WHERE employee_id = employees.employee_code
                                            AND date_only_record = t._date ORDER BY id ASC LIMIT 1 ) is null,'Absent',
                                            (SELECT DATE_FORMAT(date_time_record, '%H:%i:%s') FROM attendances WHERE employee_id = employees.employee_code
                                            AND date_only_record = t._date ORDER BY id ASC LIMIT 1 ))
                                        )
                                    )
                                )
                            )
                        ) AS InTime,
                        ( SELECT DATE_FORMAT(date_time_record, '%H:%i:%s') FROM attendances WHERE employee_id = employees.employee_code
                            AND date_only_record = t._date
                        ORDER BY id DESC LIMIT 1 ) AS OutTime
                FROM
                    employees
                    LEFT JOIN emp_departments ON employees.department_id = emp_departments.id
                    LEFT JOIN emp_designations ON employees.designation_id = emp_designations.id
                    CROSS JOIN (
                        SELECT ADDDATE('$currentDate', t4.i * 10000 + t3.i * 1000 + t2.i * 100 + t1.i * 10 + t0.i) AS _date FROM
                        (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t0,
                        (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t1,
                        (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t2,
                        (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t3,
                        (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t4
                    ) t
                WHERE
                    t._date = '$currentDate') a
            ";

        $dailyAttendances = DB::table(DB::raw("($query) AS subquery"))
                ->select('ID', 'sn', 'EmployeeName','department_name','designation_name','_date','InTime','OutTime','Day', 'LateTime', 'WorkTime')
                ->orderBy('_date')
                ->orderBy('sn')
                ->get();

        return response()->json($dailyAttendances);
    }

    // Attendance report ajax
    public function dailyAttendanceSearch($branchID, $employeeID, $startDate, $endDate, $pdf)
    {
        // $startDate = '2023-09-01';
        // $endDate = '2023-09-30';
        // $lateTime = '10:37:00';
        $query = " 

        select *, DATE_FORMAT(TIMEDIFF(t.late_time, t.InTime),'%H:%i:%s') AS LateTime, DATE_FORMAT(TIMEDIFF(t.OutTime, t.InTime),'%H:%i:%s') AS WorkTime from(
            SELECT
                e.order as sn,
                e.employee_code AS ID,
                e.employee_name AS EmployeeName,
                (SELECT department_name FROM emp_departments WHERE id = e.department_id) AS department_name,
                (SELECT designation_name FROM emp_designations WHERE id = e.designation_id) AS designation_name,
                (SELECT late_time FROM late_times  ORDER BY id DESC LIMIT 1) AS late_time,
                v._date,
                IF(
                    (SELECT COUNT(ed.id) FROM employee_delayin_earlyouts ed WHERE ed.employee_id = e.id AND ed.date = _date AND ed.status = 'out_of_office') > 0, 'Out Of Office',
                    IF(
                        (SELECT COUNT(ed.id) FROM employee_delayin_earlyouts ed WHERE ed.employee_id = e.id AND ed.date = _date AND ed.status = 'absences') > 0, 'Absent',
                        IF(
                            (SELECT COUNT(le.id) FROM employee_leave_entries le WHERE le.employee_id = e.id AND _date BETWEEN le.leave_start_date AND le.leave_end_date AND le.final_status = 1) > 0, 'Leave',
                            IF(
                                (SELECT COUNT(hd.id) FROM holidays hd WHERE _date BETWEEN hd.date and hd.end_date ) >0, (SELECT hd.occasion FROM holidays hd WHERE _date BETWEEN hd.date and hd.end_date LIMIT 1),
                                IF(
                                DAYNAME(v._date)=(SELECT wd.weekend_day FROM weekend_days wd WHERE DAYNAME(v._date) = wd.weekend_day), 'Weekend',
                                   IF( (SELECT DATE_FORMAT(date_time_record, '%H:%i:%s') FROM attendances WHERE employee_id = e.employee_code AND date_only_record = _date LIMIT 1) is null,'Absent',(SELECT DATE_FORMAT(date_time_record, '%H:%i:%s') FROM attendances WHERE employee_id = e.employee_code AND date_only_record = _date LIMIT 1) )
                                )
                            )
                        )
                    )
                ) AS InTime,
                (SELECT DATE_FORMAT(date_time_record, '%H:%i:%s') FROM attendances WHERE employee_id = e.employee_code AND date_only_record = _date ORDER BY id DESC LIMIT 1) AS OutTime,
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
                _date BETWEEN '$startDate' AND '$endDate' AND (e.branch_id=$branchID or $branchID=0) AND (employee_code=$employeeID or $employeeID=0)) t
        ";

        $dailyAttendances = DB::table(DB::raw("($query) AS subquery"))
                ->select('ID', 'sn', 'EmployeeName','department_name','designation_name','_date','InTime','OutTime','Day', 'LateTime', 'WorkTime')
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
}
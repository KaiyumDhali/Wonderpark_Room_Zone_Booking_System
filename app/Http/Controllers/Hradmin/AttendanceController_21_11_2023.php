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
    public function singleEmployeeAttendanceSearch($employeeCode)
    {
        $employeeCode = $employeeCode; // You can replace this with the actual employee code from the request.
        // $startDate = $startDate;
        // $endDate = $endDate;
        // $startDate = '2023-09-07';

        $startDate = date('Y-m-01');
        $endDate = date('Y-m-d');

        $query = "
            SELECT
                _date,
                if((SELECT COUNT(ed.id) FROM employee_delayin_earlyouts  ed WHERE ed.employee_id=(SELECT employees.id FROM employees WHERE employee_code = '$employeeCode') and ed.date=_date and ed.status='out_of_office')>0,'Out Of Office',
                    
                if ((SELECT COUNT(ed.id) FROM employee_delayin_earlyouts  ed WHERE ed.employee_id=(SELECT employees.id FROM employees WHERE employee_code = '$employeeCode') and ed.date=_date and ed.status='absences')>0,'Absences',
                    
                IF((SELECT COUNT(le.id) FROM employee_leave_entries le where le.employee_id=(SELECT employees.id FROM employees WHERE employee_code = '$employeeCode') and _date BETWEEN le.leave_start_date and le.leave_end_date and le.final_status=1)>0,'Leave',(SELECT   DATE_FORMAT(date_time_record, '%h:%i:%s') FROM attendances WHERE employee_id ='$employeeCode'   AND date_only_record = _date LIMIT 1))
                    ))  
                    AS InTime,
                (SELECT DATE_FORMAT(date_time_record, '%h:%i:%s') FROM attendances WHERE employee_id = $employeeCode AND date_only_record = _date ORDER BY id DESC LIMIT 1) AS OutTime,
                DAYNAME(_date) AS Day
            FROM
                (SELECT ADDDATE('1970-01-01', t4.i * 10000 + t3.i * 1000 + t2.i * 100 + t1.i * 10 + t0.i) AS _date FROM
                    (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t0,
                    (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t1,
                    (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t2,
                    (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t3,
                    (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t4
                )v
                WHERE
                    _date BETWEEN '$startDate' AND '$endDate' 
        ";
        // $query = "
        //     SELECT
        //         _date,
        //         (SELECT DATE_FORMAT(date_time_record, '%h:%i:%s') FROM attendances WHERE employee_id = $employeeCode AND date_only_record = _date LIMIT 1) AS InTime,
        //         (SELECT DATE_FORMAT(date_time_record, '%h:%i:%s') FROM attendances WHERE employee_id = $employeeCode AND date_only_record = _date ORDER BY id DESC LIMIT 1) AS OutTime,
        //         DAYNAME(_date) AS Day
        //     FROM
        //         (SELECT ADDDATE('1970-01-01', t4.i * 10000 + t3.i * 1000 + t2.i * 100 + t1.i * 10 + t0.i) AS _date FROM
        //             (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t0,
        //             (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t1,
        //             (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t2,
        //             (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t3,
        //             (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t4
        //         )v
        //         WHERE
        //             _date BETWEEN '$startDate' AND '$endDate' 
        // ";

        $result = DB::table(DB::raw("($query) AS subquery"))
            ->select('_date','InTime','OutTime','Day')
            ->orderby('_date', 'desc')
            ->get();

        return response()->json($result);
    }
    public function employeeAttendanceSearch($branchID, $startDate, $endDate, $employeeCode, $pdf)
    {
        // $employeeCode = $employeeCode; // You can replace this with the actual employee code from the request.
        // $startDate = $startDate; // You can replace this with the actual start date from the request.
        // $endDate = $endDate; // You can replace this with the actual end date from the request.

        $query = "        
            SELECT
                '$employeeCode' AS ID,
                (SELECT employee_name FROM employees WHERE employee_code = '$employeeCode') AS EmployeeName,
                (SELECT department_name FROM emp_departments where id=(SELECT department_id FROM employees WHERE employee_code = '$employeeCode')) AS department_name,
                (SELECT designation_name FROM emp_designations where id= (SELECT designation_id FROM employees WHERE employee_code = '$employeeCode')) AS designation_name,
                _date,
                
                if((SELECT COUNT(ed.id) FROM employee_delayin_earlyouts  ed WHERE ed.employee_id=(SELECT employees.id FROM employees WHERE employee_code = '$employeeCode') and ed.date=_date and ed.status='out_of_office')>0,'Out Of Office',
                    
                if ((SELECT COUNT(ed.id) FROM employee_delayin_earlyouts  ed WHERE ed.employee_id=(SELECT employees.id FROM employees WHERE employee_code = '$employeeCode') and ed.date=_date and ed.status='absences')>0,'Absences',
                    
                IF((SELECT COUNT(le.id) FROM employee_leave_entries le where le.employee_id=(SELECT employees.id FROM employees WHERE employee_code = '$employeeCode') and _date BETWEEN le.leave_start_date and le.leave_end_date and le.final_status=1)>0,'Leave',(SELECT   DATE_FORMAT(date_time_record, '%h:%i:%s') FROM attendances WHERE employee_id ='$employeeCode'   AND date_only_record = _date LIMIT 1))
                    ))  
                    AS InTime,
                (SELECT DATE_FORMAT(date_time_record, '%h:%i:%s') FROM attendances WHERE employee_id = (SELECT employees.employee_code FROM employees WHERE employee_code = '$employeeCode') AND date_only_record = _date ORDER BY id DESC LIMIT 1) AS OutTime,
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
                    _date BETWEEN '$startDate' AND '$endDate'
            ";

        $result = DB::table(DB::raw("($query) AS subquery"))
            ->select('ID', 'EmployeeName','department_name','designation_name','_date','InTime','OutTime','Day')
            ->get();

        if ($pdf == "list") {
            return response()->json($result);
        }
        if ($pdf == "pdfurl") {
            $data['branch_id'] = $branchID;
            $data['start_date'] = $startDate;
            $data['end_date'] = $endDate;
            $pdf = PDF::loadView('pages.pdf.employee_attendance_report_pdf', array('dailyAttendances' => $result, 'data' => $data));
            return $pdf->stream(Carbon::now().'-recentstat.pdf');
        }
    }
    
    public function dailyAttendanceSearch($branchID, $startDate, $pdf)
    {
        if ($branchID == 0) {
            $query = "
                SELECT
                e.employee_code AS ID,
                e.employee_name AS EmployeeName,
                e.order AS EmployeeOrder,
                (SELECT department_name FROM emp_departments WHERE id=e.department_id) AS department_name,
                (SELECT designation_name FROM emp_designations WHERE id=e.designation_id) AS designation_name,
                '$startDate' AS _date,
                
                if((SELECT COUNT(ed.id) FROM employee_delayin_earlyouts  ed WHERE ed.employee_id=e.id and ed.date='$startDate' and ed.status='out_of_office')>0,'Out Of Office',
                    
                if ((SELECT COUNT(ed.id) FROM employee_delayin_earlyouts  ed WHERE ed.employee_id=e.id and ed.date='$startDate' and ed.status='absences')>0,'Absences',
                    
                IF((SELECT COUNT(le.id) FROM employee_leave_entries le where le.employee_id=e.id and '$startDate' BETWEEN le.leave_start_date and le.leave_end_date and le.final_status=1)>0,'Leave',(SELECT   DATE_FORMAT(date_time_record, '%h:%i:%s') FROM attendances WHERE employee_id = e.employee_code AND date_only_record = '$startDate' LIMIT 1))
                    ))  AS InTime,
                
                
                (SELECT DATE_FORMAT(date_time_record, '%h:%i:%s') FROM attendances WHERE employee_id = e.employee_code AND date_only_record = '$startDate' ORDER BY id DESC LIMIT 1) AS OutTime,
                DAYNAME('$startDate') AS Day
                FROM employees AS e
                ORDER BY e.order ASC
                ";

            $dailyAttendances = DB::table(DB::raw("($query) AS subquery"))
                ->select('ID', 'EmployeeName','department_name','designation_name','_date','InTime','OutTime','Day')
                ->get();
        }else{
            $query = "
                SELECT
                e.employee_code AS ID,
                e.branch_id AS branch_id,
                e.employee_name AS EmployeeName,
                e.order AS EmployeeOrder,
                (SELECT department_name FROM emp_departments WHERE id=e.department_id) AS department_name,
                (SELECT designation_name FROM emp_designations WHERE id=e.designation_id) AS designation_name,
                '$startDate' AS _date,
                
                if((SELECT COUNT(ed.id) FROM employee_delayin_earlyouts  ed WHERE ed.employee_id=e.id and ed.date='$startDate' and ed.status='out_of_office')>0,'Out Of Office',
                    
                if ((SELECT COUNT(ed.id) FROM employee_delayin_earlyouts  ed WHERE ed.employee_id=e.id and ed.date='$startDate' and ed.status='absences')>0,'Absences',
                    
                IF((SELECT COUNT(le.id) FROM employee_leave_entries le where le.employee_id=e.id and '$startDate' BETWEEN le.leave_start_date and le.leave_end_date and le.final_status=1)>0,'Leave',(SELECT   DATE_FORMAT(date_time_record, '%h:%i:%s') FROM attendances WHERE employee_id = e.employee_code AND date_only_record = '$startDate' LIMIT 1))
                    ))  AS InTime,
                
                
                (SELECT DATE_FORMAT(date_time_record, '%h:%i:%s') FROM attendances WHERE employee_id = e.employee_code AND date_only_record = '$startDate' ORDER BY id DESC LIMIT 1) AS OutTime,
                DAYNAME('$startDate') AS Day
                FROM employees AS e
                ORDER BY e.order ASC
                ";

            $dailyAttendances = DB::table(DB::raw("($query) AS subquery"))
                ->select('ID', 'EmployeeName','department_name','designation_name','_date','InTime','OutTime','Day')
                ->where('branch_id', $branchID)
                ->get();
        }
        
        if ($pdf == "list") {
            return response()->json($dailyAttendances);
        }

        if ($pdf == "pdfurl") {
            $data['branch_id'] = $branchID;
            $data['start_date'] = $startDate;
            $pdf = PDF::loadView('pages.pdf.daily_attendance_report_pdf', array('dailyAttendances' => $dailyAttendances, 'data' => $data));
            return $pdf->stream(Carbon::now().'-recentstat.pdf');
        }
    }

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
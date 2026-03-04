<?php
namespace App\Http\Controllers\Payroll;
use App\Http\Controllers\Controller;

// use App\Models\PayslipType;
// use App\Models\IncomeHead;
// use App\Models\DeductionHead;
// use App\Models\PaySlip;

use App\Models\Employee;
use App\Models\EmpBranch;
use App\Models\EmpPosting;

use App\Models\payrollFormula;
use App\Models\PayrollHead;
use App\Models\payroll;
use App\Models\MonthlySalary;
use App\Models\MonthlySalaryDetail;

use App\Models\EmployeeDesignation;
use App\Models\EmployeeSalary;
use App\Models\Income;
use App\Models\Deduction;

use Illuminate\Http\Request;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class MonthlySalaryController extends Controller
{

    function __construct() {
        $this->middleware('permission:read monthly salary|write monthly salary|create monthly salary', ['only' => ['index', 'show']]);
        $this->middleware('permission:create monthly salary', ['only' => ['create', 'store']]);
        $this->middleware('permission:write monthly salary', ['only' => ['edit', 'update', 'destroy']]);
    }

    // Generate monthly Salary List get
    public function monthlySalariesList($month, $pdf)
    {
        // $month = 2023-11;
        $query = "
            SELECT
                (SELECT e.order FROM employees e WHERE e.id=ms.employee_id) AS employee_order,
                ms.employee_id AS employee_id,
                ms.basic_salary AS Base_Salary,
                DATE_FORMAT(ms.generate_date, '%Y-%m-%d') AS generate_date,
                ms.salary_month AS salary_month,
                (SELECT e.employee_name FROM employees e WHERE e.id=ms.employee_id) AS employee_name,
                (SELECT e.employee_code FROM employees e WHERE e.id=ms.employee_id) AS employee_code,

                (SELECT msd.amount FROM  monthly_salary_details msd WHERE msd.monthly_salary_id=ms.id and msd.head_type='Income' and msd.head_id=1) AS House_Rent,
                (SELECT msd.amount FROM  monthly_salary_details msd WHERE msd.monthly_salary_id=ms.id and msd.head_type='Income' and msd.head_id=2) AS Medical,
                (SELECT msd.amount FROM  monthly_salary_details msd WHERE msd.monthly_salary_id=ms.id and msd.head_type='Income' and msd.head_id=3) AS PF,
                (SELECT msd.amount FROM  monthly_salary_details msd WHERE msd.monthly_salary_id=ms.id and msd.head_type='Income' and msd.head_id=4) AS iOthers,
                
                (SELECT msd.amount FROM  monthly_salary_details msd WHERE msd.monthly_salary_id=ms.id and msd.head_type='Deduction' and msd.head_id=1) AS TAX,
                (SELECT msd.amount FROM  monthly_salary_details msd WHERE msd.monthly_salary_id=ms.id and msd.head_type='Deduction' and msd.head_id=2) AS dPF,
                (SELECT msd.amount FROM  monthly_salary_details msd WHERE msd.monthly_salary_id=ms.id and msd.head_type='Deduction' and msd.head_name='Others') AS dOthers
                
            FROM monthly_salaries AS ms
            WHERE ms.salary_month = '$month'
            ORDER BY employee_order ASC
        ";

        $monthlySalaries = DB::table(DB::raw("($query) AS subquery"))
                ->select('employee_order', 'employee_id', 'employee_code', 'employee_name', 'Base_Salary','House_Rent','Medical','PF','iOthers','TAX', 'dPF', 'dOthers')
                ->orderBy('employee_order')
                ->get();
        if ($pdf == "list") {
            return response()->json($monthlySalaries);
        }
        if ($pdf == "pdfurl") {
            // $data['branch_id'] = $branchID; 
            $data['month'] = $month;
            // Add the 'orientation' option to set landscape
            $pdf = PDF::loadView('pages.pdf.monthly_salaries_report_pdf', array('monthlySalaries' => $monthlySalaries, 'data' => $data));
            return $pdf->stream(Carbon::now().'-recentstat.pdf');
        }
    }


    public function monthlySalaries($branchID, $month, $pdf)
    {
        // $month = 2023-11;
        $query = "
            SELECT
                (SELECT e.order FROM employees e WHERE e.id=ms.employee_id) AS employee_order,
                
                ms.employee_id AS employee_id,
                ms.branch_id as branch_id,
                DATE_FORMAT(ms.generate_date, '%Y-%m-%d') AS generate_date,
                ms.salary_month AS salary_month,
                
                (SELECT e.employee_name FROM employees e WHERE e.id=ms.employee_id) AS employee_name,
                (SELECT ed.designation_name FROM emp_designations ed WHERE ed.id=(SELECT e.designation_id FROM employees e WHERE e.id=ms.employee_id)) AS designation_name,
                
                (SELECT es.amount FROM  employee_salaries es WHERE es.employee_id=ms.employee_id ORDER BY ms.salary_month DESC LIMIT 1) AS Base_Salary,
            
                (SELECT msd.amount FROM  monthly_salary_details msd WHERE msd.monthly_salary_id=ms.id and msd.head_type='Income' and msd.head_id=1) AS House_Rent,
                (SELECT msd.amount FROM  monthly_salary_details msd WHERE msd.monthly_salary_id=ms.id and msd.head_type='Income' and msd.head_id=2) AS Medical,
                (SELECT msd.amount FROM  monthly_salary_details msd WHERE msd.monthly_salary_id=ms.id and msd.head_type='Income' and msd.head_id=3) AS PF,
                (SELECT msd.amount FROM  monthly_salary_details msd WHERE msd.monthly_salary_id=ms.id and msd.head_type='Income' and msd.head_id=4) AS iOthers,
                
                (SELECT msd.amount FROM  monthly_salary_details msd WHERE msd.monthly_salary_id=ms.id and msd.head_type='Deduction' and msd.head_id=1) AS TAX,
                (SELECT msd.amount FROM  monthly_salary_details msd WHERE msd.monthly_salary_id=ms.id and msd.head_type='Deduction' and msd.head_id=2) AS dPF,
                (SELECT msd.amount FROM  monthly_salary_details msd WHERE msd.monthly_salary_id=ms.id and msd.head_type='Deduction' and msd.head_name='Others') AS dOthers
                
            FROM monthly_salaries AS ms
            
            WHERE ms.salary_month = '$month' AND (ms.branch_id=$branchID or $branchID=0)
            
            ORDER BY employee_order ASC
        ";

        $monthlySalaries = DB::table(DB::raw("($query) AS subquery"))
                ->select('employee_order', 'employee_id', 'employee_name', 'designation_name','Base_Salary','House_Rent','Medical','PF','iOthers','TAX', 'dPF', 'dOthers')
                ->orderBy('employee_order')
                ->get();
        if ($pdf == "list") {
            return response()->json($monthlySalaries);
        }
        if ($pdf == "pdfurl") {
            $data['branch_id'] = $branchID; 
            $data['month'] = $month;
            // Add the 'orientation' option to set landscape
            $pdf = PDF::loadView('pages.pdf.monthly_salaries_report_pdf', array('monthlySalaries' => $monthlySalaries, 'data' => $data));
            return $pdf->stream(Carbon::now().'-recentstat.pdf');
        }
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //         $month = 2023-11;
        //         $query = "
        //             SELECT
        //                 (SELECT e.order FROM employees e WHERE e.id=ms.employee_id) AS employee_order,
                        
        //                 ms.employee_id AS employee_id,
        //                 DATE_FORMAT(ms.generate_date, '%Y-%m-%d') AS generate_date,
        //                 ms.salary_month AS salary_month,
                        
        //                 (SELECT e.employee_name FROM employees e WHERE e.id=ms.employee_id) AS employee_name,
        //                 (SELECT ed.designation_name FROM emp_designations ed WHERE ed.id=(SELECT e.designation_id FROM employees e WHERE e.id=ms.employee_id)) AS designation_name,
                        
        //                 (SELECT es.amount FROM  employee_salaries es WHERE es.employee_id=ms.employee_id ORDER BY ms.salary_month DESC LIMIT 1) AS Base_Salary,
                    
        //                 (SELECT msd.amount FROM  monthly_salary_details msd WHERE msd.monthly_salary_id=ms.id and msd.head_type='Income' and msd.head_id=1) AS House_Rent,
        //                 (SELECT msd.amount FROM  monthly_salary_details msd WHERE msd.monthly_salary_id=ms.id and msd.head_type='Income' and msd.head_id=2) AS Medical,
        //                 (SELECT msd.amount FROM  monthly_salary_details msd WHERE msd.monthly_salary_id=ms.id and msd.head_type='Income' and msd.head_id=3) AS PF,
        //                 (SELECT msd.amount FROM  monthly_salary_details msd WHERE msd.monthly_salary_id=ms.id and msd.head_type='Income' and msd.head_id=4) AS iOthers,
                        
        //                 (SELECT msd.amount FROM  monthly_salary_details msd WHERE msd.monthly_salary_id=ms.id and msd.head_type='Deduction' and msd.head_id=1) AS TAX,
        //                 (SELECT msd.amount FROM  monthly_salary_details msd WHERE msd.monthly_salary_id=ms.id and msd.head_type='Deduction' and msd.head_id=2) AS dPF,
        //                 (SELECT msd.amount FROM  monthly_salary_details msd WHERE msd.monthly_salary_id=ms.id and msd.head_type='Deduction' and msd.head_name='Others') AS dOthers
                        
        //             FROM monthly_salaries AS ms
                    
        //             WHERE ms.salary_month = '$month'
                    
        //             ORDER BY employee_order ASC
        //         ";

        //         $monthlySalaries = DB::table(DB::raw("($query) AS subquery"))
        //                 ->select('employee_order', 'employee_id', 'employee_name', 'designation_name','Base_Salary','House_Rent','Medical','PF','iOthers','TAX', 'dPF', 'dOthers')
        //                 ->orderBy('employee_order')
        //                 ->get();

        // dd($monthlySalaries);

        $allEmpBranch = EmpBranch::where('status', 1)->pluck('branch_name', 'id')->all();
        // dd($results);
        return view('pages.payroll.salary_sheet.index', compact('allEmpBranch'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // $salary_month = '2024-03'; // Enclose the string in quotes
        $salary_month = $request->selectedmonth;

        // Retrieve total monthly salary employee IDs (employees who were in the company by the end of the specified month)
        $totalMonthlySalaryEmployeeIds = Employee::join('emp_postings', 'emp_postings.employee_id', '=', 'employees.id')
                                                ->where('employees.salary_held_up', 0)
                                                ->whereDate('emp_postings.joining_date', '<=', date('Y-m-t', strtotime($salary_month)))
                                                ->orderBy('emp_postings.joining_date', 'desc')
                                                ->pluck('employees.id');
        // dd($totalMonthlySalaryEmployeeIds);
        // Retrieve added monthly salary employee IDs for the specified month
        $addedMonthlySalaryEmployeeIds = MonthlySalary::where('salary_month', $salary_month)
                                                    ->pluck('employee_id');
        // dd($addedMonthlySalaryEmployeeIds);
        // Check if there are new employees (employees who received salaries but are not included in total monthly salary employees)
        if ($totalMonthlySalaryEmployeeIds->count() > $addedMonthlySalaryEmployeeIds->count()) {
            // Calculate IDs of new employees
            $newEmployeeIds = $totalMonthlySalaryEmployeeIds->diff($addedMonthlySalaryEmployeeIds);
            // dd($newEmployeeIds);
            
            foreach ($newEmployeeIds as $employeeId) {
                // Get the latest gross salary from EmpPosting for the employee
                $basic_salary_amount = EmpPosting::where('employee_id', $employeeId)
                    ->orderBy('id', 'desc')
                    ->value('gross_salary');
            
                // Fetch all relevant payroll IDs for the employee based on effective date
                $payrolls = Payroll::where('employee_id', $employeeId)
                    ->whereDate('effective_date', '<=', date('Y-m-t', strtotime($salary_month)))
                    ->pluck('payroll_head_id');
            
                // Iterate through each payroll ID and retrieve payroll head data
                foreach ($payrolls as $payroll) {
                    $payrollHeadData = PayrollHead::select(
                            'payroll_heads.id as payroll_head_id',
                            'payroll_heads.name as payroll_head_name',
                            'payroll_heads.type as payroll_head_type',
                            'payroll_formulas.formula'
                        )
                        ->join('payroll_formulas', 'payroll_heads.id', '=', 'payroll_formulas.payroll_head')
                        ->where('payroll_heads.id', $payroll)
                        ->get();
            
                    // Iterate through each payroll head data to process the formula
                    foreach ($payrollHeadData as $payrollData) {
                        // Get the formula from payroll head data
                        $formula = $payrollData->formula;
            
                        // Replace placeholder {Basic Salary} with actual basic_salary_amount
                        // Assuming {Basic Salary} is meant to be replaced with $basic_salary_amount
                        $formula = str_replace('{Basic Salary}', $basic_salary_amount, $formula);
            
                        // Evaluate the modified formula (if needed)
                        // This step depends on how the formula should be processed
                        // For example, you might need to parse and evaluate this formula dynamically
                        // using a custom function or library, depending on the expression format.
            
                        // For now, let's assume you're evaluating a simple arithmetic expression
                        // Example: {Basic Salary}/100*35
                        // Evaluating this expression (assuming formula structure is valid):
                        eval('$result = ' . $formula . ';');
            
                        // Now, $result holds the calculated value based on the payroll formula
                        // You can use or store this result as needed for further processing
                        // For example, save this result to a database or display it somewhere.
                        // In this example, $result represents 35% of the basic_salary_amount.
            
                        // Example of how you might use or display the result
                        echo "Employee ID: $employeeId, Payroll Head Name: $payrollData->payroll_head_name, Payroll Head Type: $payrollData->payroll_head_type, Calculated Result: $result\n";
                    }
                }
            }
        }
    }


    public function __store(Request $request)
    {
        $salary_month = $request->selectedmonth;
        // $branch_id = $request->branch_id;
        $created_by = Auth::user()->id;

        // find MonthlySalary table this month all ready generate salary
        $addedMonthlySalary = MonthlySalary::where('salary_month', '=', $salary_month)->pluck('employee_id');
        // dd($addedMonthlySalary);

        // find emp_postings table this joining_date match id count
        $totalMonthlySalaryEmployee = Employee::leftJoin('emp_postings', 'emp_postings.employee_id', '=', 'employees.id')
                                    ->select('employees.id', 'emp_postings.id,joining_date')
                                    ->where('joining_date', '<=', date($salary_month . '-t'))->count();        
        // dd($totalMonthlySalaryEmployee);
        if ($totalMonthlySalaryEmployee > count($addedMonthlySalary)) {

            // $findNewEmployees = Employee::where('branch_id', $branch_id)->where('joining_date', '<=', date($salary_month . '-t'))->whereNotIn('id', $addedMonthlySalary)->get();
            $findNewEmployees = Employee::leftJoin('emp_postings', 'emp_postings.employee_id', '=', 'employees.id')
                            ->select('employees.*', 'emp_postings.joining_date')
                            ->where('joining_date', '<=', date($salary_month . '-t'))
                            ->whereNotIn('id', $addedMonthlySalary)->get();

            $remainingEmp = $findNewEmployees->pluck('id')->toArray();
            dd($remainingEmp);   
            $basic_salary = EmpPosting::whereIn('employee_id', $remainingEmp)->pluck('employee_id');
            // dd($basic_salary);
            $remainEmpId = array_diff($remainingEmp, $basic_salary->toArray());
            $empCodes = $findNewEmployees->whereIn('id', $remainEmpId)->pluck('employee_code')->toArray();
            $employee_code = implode(', ' ,array_values($empCodes));

            if ($findNewEmployees->count() > $basic_salary->count()) {
                return redirect()->route('monthly_salaries.index')->with([
                    'message' => 'Ops! Please set '.$findNewEmployees->count() - $basic_salary->count().'(Employee Code: '.$employee_code.')'.' employees salary.',
                    'alert-type' => 'danger'
                ]);
            }
            // dd($basic_salary);
            foreach ($findNewEmployees as $employee) {
                $basic_salary_amount = EmpPosting::where('employee_id', $employee->id)->orderBy('id', 'desc')->value('gross_salary');
                $total_income = Income::where('employee_id', $employee->id)->sum('amount');
                $total_deduction = Deduction::where('employee_id', $employee->id)->sum('amount');
                $net_payable = ($basic_salary_amount + $total_income) - $total_deduction;

                // Add MonthlySalary
                $monthlySalary = new MonthlySalary([
                    'employee_id' => $employee->id,
                    'generate_date' => Carbon::now()->format('Y-m-d'),
                    'salary_month' => $salary_month,
                    'basic_salary' => $basic_salary_amount,
                    'income' => $total_income,
                    'deduction' => $total_deduction,
                    'net_payable' => $net_payable,
                    'status' => 0,
                    'created_by' => $created_by,
                ]);
                // dd($monthlySalary);
                $monthlySalary->save();

                // Add MonthlySalaryDetails
                $incomes = Income::with(['incomeHead'])->where('employee_id', $employee->id)->get();
                foreach ($incomes as $income) {
                    $monthlySalaryDetail = new MonthlySalaryDetail([
                        'monthly_salary_id' => $monthlySalary->id,
                        'head_id' => $income->income_head,
                        'head_name' => $income->incomeHead->name,
                        'head_type' => 'Income',
                        'amount' => $income->amount,
                        'created_by' => Auth::user()->name,
                    ]);

                    $monthlySalaryDetail->save();
                }

                $deductions = Deduction::with(['deductionHead'])->where('employee_id', $employee->id)->get();
                foreach ($deductions as $deduction) {
                    $monthlySalaryDetail = new MonthlySalaryDetail([
                        'monthly_salary_id' => $monthlySalary->id,
                        'head_id' => $deduction->deduction_head,
                        'head_name' => $deduction->deductionHead->name,
                        'head_type' => 'Deduction',
                        'amount' => $deduction->amount,
                        'created_by' => Auth::user()->name,
                    ]);

                    $monthlySalaryDetail->save();
                }
            }

            return redirect()->route('monthly_salaries.index')->with([
                'message' => 'Salary successfully created.',
                'alert-type' => 'success'
            ]);
        } else {
            return redirect()->route('monthly_salaries.index')->with([
                'message' => 'Salary Already created.',
                'alert-type' => 'danger'
            ]);
        }
    }
    /**
     * Display the specified resource.
     */
    public function show(MonthlySalary $monthlySalary)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MonthlySalary $monthlySalary)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MonthlySalary $monthlySalary)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MonthlySalary $monthlySalary)
    {
        //
    }
}
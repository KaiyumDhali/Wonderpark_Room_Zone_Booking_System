<?php
namespace App\Http\Controllers\Payroll;
use App\Http\Controllers\Controller;

use App\Models\PayslipType;
use App\Models\IncomeHead;
use App\Models\DeductionHead;

use App\Models\Employee;
use App\Models\EmployeeSalary;
use App\Models\Income;
use App\Models\Deduction;

use App\Models\PaySlip;

use App\Models\MonthlySalary;
use App\Models\MonthlySalaryDetail;
use Illuminate\Http\Request;
use Auth;
use Carbon\Carbon;

class MonthlySalaryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.payroll.salary_sheet.index');
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
        $salary_month  = $request->selectedmonth;
        $created_by = Auth::user()->id;

        $addedMonthlySalary = MonthlySalary::where('salary_month', '=', $salary_month)->where('created_by', Auth::user()->id)->pluck('employee_id');
        // dd($addedMonthlySalary);
        $totalMonthlySalaryEmployee   = Employee::where('created_by', $created_by)->where('joining_date', '<=', date($salary_month . '-t'))->count();
        // dd($totalMonthlySalaryEmployee);
        if($totalMonthlySalaryEmployee > count($addedMonthlySalary))
        {
            $findNewEmployees = Employee::where('created_by', $created_by)->where('joining_date', '<=', date($salary_month . '-t'))->whereNotIn('id', $addedMonthlySalary)->get();
            // dd($findNewEmployees);
            $basic_salary = EmployeeSalary::where('created_by', $created_by)->where('amount', '<=', 0)->first();
            // dd($basic_salary);
            if(!empty($basic_salary))
            {
                return redirect()->route('payslipss.index')->withErrors(['error' => 'Ops ! Please set employee salary.']);
            }
            foreach($findNewEmployees as $employee)
            {

                // basic_salary
                $basic_salary = EmployeeSalary::where('employee_id', '=', $employee->id)->orderBy('id', 'desc')->value('amount');;
                // total incomes
                $incomes = Income::where('employee_id', '=', $employee->id)->get();
                $total_income = 0;
                foreach($incomes as $income)
                {
                    $head_type = 0;
                    $head_id = $income->id;
                    $head_name = $income->income_head;
                    $amount = $income->amount;
                    $total_income = $income->amount + $total_income;
                }
                // total deductions
                $deductions = Deduction::where('employee_id', '=', $employee->id)->get();
                $total_deduction = 0;
                foreach($deductions as $deduction)
                {
                    $head_type = 1;
                    $head_id = $deduction->id;
                    $head_name = $deduction->income_head;
                    $amount = $deduction->amount;
                    $total_deduction = $deduction->amount + $total_deduction;
                }
                // total net_payble
                $net_payble = ($basic_salary+$total_income)-$total_deduction;

                // Add MonthlySalary
                $monthlySalary = new MonthlySalary();

                $monthlySalary->employee_id          = $employee->id;
                $monthlySalary->generate_date        = Carbon::now()->format('Y-m-d');
                $monthlySalary->salary_month         = $salary_month;
                $monthlySalary->basic_salary         = $basic_salary;
                $monthlySalary->income               = $total_income;
                $monthlySalary->deduction            = $total_deduction;
                $monthlySalary->net_payble           = $net_payble;
                $monthlySalary->status               = 0;
                $monthlySalary->created_by           = $created_by;
                //$monthlySalary->approved_by        = $created_by;
                // dd($monthlySalary);
                $monthlySalary->save();

                foreach ($monthlySalary->id as $key => $monthly_salary_id) {
                    $monthlySalaryDetail = new MonthlySalaryDetail();
                     	 	 	 	 	 		
                    $monthlySalaryDetail->monthly_salary_id     = $monthly_salary_id;
                    $monthlySalaryDetail->head_id               = $head_id;
                    $monthlySalaryDetail->head_name             = $head_name;
                    $monthlySalaryDetail->head_type             = $head_type;
                    $monthlySalaryDetail->amount                = $amount;
                    $monthlySalaryDetail->created_by            = Auth::user()->name;

                    // dd($monthlySalaryDetail);
                    $monthlySalaryDetail->save();
                }
            }

            return redirect()->route('payslips.index')->with('success', __('Payslip successfully created.'));
        }
        else
        {
            return redirect()->route('payslips.index')->with('error', __('Payslip Already created.'));
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

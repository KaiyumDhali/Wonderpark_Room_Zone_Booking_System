<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function empBranch() {
        return $this->belongsTo(EmpBranch::class, 'branch_id');
    }

    public function empDepartment() {
        return $this->belongsTo(EmpDepartment::class, 'department_id');
    }

    public function empDesignation() {
        return $this->belongsTo(EmpDesignation::class, 'designation_id');
    }

    public function employeeLedger(){
        return $this->hasMany(EmployeeLedger::class, 'employee_id');
    }
    
    public function lastPromotion(){
        return $this->hasOne(Promotion::class, 'employee_id')->latestOfMany();
    }
    
    public function employeeEducation(){
        return $this->hasMany(EmployeeEducation::class, 'employee_id');
    }
    public function employeeSalary(){
        return $this->hasOne(EmployeeSalary::class, 'employee_id')->latest();
    }

    // payslip work new add 
    public function get_net_salary()
    {
        //allowance
        $allowances      = Allowance::where('employee_id', '=', $this->id)->get();
        $total_allowance = 0 ;
        foreach($allowances as $allowance)
        {
            $totalAllowances  = $allowance->amount;
            $total_allowance += $totalAllowances ;
        }

        //commission
        $commissions      = Commission::where('employee_id', '=', $this->id)->get();
        $total_commission = 0;
        foreach($commissions as $commission)
        {
            $totalCom  = $commission->amount;
            $total_commission += $totalCom ;
        }

        //Loan
        $loans      = Loan::where('employee_id', '=', $this->id)->get();
        $total_loan = 0;
        foreach($loans as $loan)
        {
            $totalloan  = $loan->amount;
            $total_loan += $totalloan ;
        }

        //OtherPayment
        $other_payments      = OtherPayment::where('employee_id', '=', $this->id)->get();
        $total_other_payment = 0;
        foreach($other_payments as $otherPayment)
        {
            $totalother  = $otherPayment->amount;
            $total_other_payment += $totalother ;
        }

        //Overtime
        $over_times      = Overtime::where('employee_id', '=', $this->id)->get();
        $total_over_time = 0;
        foreach($over_times as $over_time)
        {
            $total_work      = $over_time->number_of_days * $over_time->hours;
            $amount          = $total_work * $over_time->rate;
            $total_over_time = $amount + $total_over_time;
        }


        //Net Salary Calculate
        $advance_salary = $total_allowance + $total_commission - $total_loan + $total_other_payment + $total_over_time;

        //$employee       = Employee::where('id', '=', $this->id)->first();
        $latestSalary   = EmployeeSalary::where('employee_id', '=', $this->id)->first();

        //$net_salary     = (!empty($employee->salary) ? $employee->salary : 0) + $advance_salary;
        $net_salary     = (!empty($latestSalary->amount) ? $latestSalary->amount : 0) + $advance_salary;

        return $net_salary;

    }

    public static function salaryamount($id)
    {
        $latestSalary = EmployeeSalary::where('employee_id', '=', $id)->first();

        if ($latestSalary) {
            return json_encode($latestSalary->amount); 
        }else{
            return 0; 
        }
    }

    public static function allowance($id)
    {
        //allowance
        $allowances      = Allowance::where('employee_id', '=', $id)->get();
        $total_allowance = 0;
        foreach($allowances as $allowance)
        {
            $total_allowance = $allowance->amount + $total_allowance;
        }

        $allowance_json = json_encode($total_allowance);

        return $allowance_json;

    }

    public static function commission($id)
    {
        //commission
        $commissions      = Commission::where('employee_id', '=', $id)->get();
        $total_commission = 0;
        foreach($commissions as $commission)
        {
            $total_commission = $commission->amount + $total_commission;
        }
        $commission_json = json_encode($total_commission);

        return $commission_json;

    }

    public static function loan($id)
    {
        //Loan
        $loans      = Loan::where('employee_id', '=', $id)->get();
        $total_loan = 0;
        foreach($loans as $loan)
        {
            $total_loan = $loan->amount + $total_loan;
        }
        $loan_json = json_encode($total_loan);

        return $loan_json;
    }
   
    public static function other_payment($id)
    {
        //OtherPayment
        $other_payments      = OtherPayment::where('employee_id', '=', $id)->get();
        $total_other_payment = 0;
        foreach($other_payments as $other_payment)
        {
            $total_other_payment = $other_payment->amount + $total_other_payment;
        }
        $other_payment_json = json_encode($total_other_payment);

        return $other_payment_json;
    }

    public static function overtime($id)
    {
        //Overtime
        $over_times      = Overtime::where('employee_id', '=', $id)->get();
        $total_over_time = 0;
        foreach($over_times as $over_time)
        {
            $total_work      = $over_time->number_of_days * $over_time->hours;
            $amount          = $total_work * $over_time->rate;
            $total_over_time = $amount + $total_over_time;
        }
        $over_time_json = json_encode($total_over_time);

        return $over_time_json;
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonthlySalary extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'branch_id',
        'generate_date',
        'salary_month',
        'basic_salary',
        'income',
        'deduction',
        'net_payble',
        'status',
        'approved_by',
        'created_by',
    ];

    // public static function employee($id)
    // {
    //     return Employee::find($id);
    // }

    // public function employees()
    // {
    //     return $this->hasOne('App\Models\Employee', 'id', 'employee_id');
    // }
    // public function employeesallary()
    // {
    //     return $this->hasOne('App\Models\EmployeeSalary', 'employee_id', 'employee_id');
    // }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Employee;
use App\Models\AllowanceOption;

class EmployeeSalary extends Model
{
    protected $fillable = [
        'employee_id',
        'amount',
        'payslip_type',
        'start_date',        
        'created_by',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
    public function paysliptype()
    {
        return $this->belongsTo(PayslipType::class, 'payslip_type');
    }

}
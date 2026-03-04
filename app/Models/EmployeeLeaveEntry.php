<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeLeaveEntry extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $fillable = [
        'id',
        'leave_year',
        'branch_id',
        'employee_id',
        'alternative_employee_id',
        'leave_application_date',
        'leave_start_date',
        'leave_end_date',
        'reporting_date',
        'leave_type',
        'total_days',
        'no_of_late',
        'late_of_leave_month',
        'hr_status',
        'management_status',
        'department_status',
        'final_status',
        'reason_for_leave',
        'remarks',
        'done_by',
        'created_at',
        'updated_at',
    ];

    public function employeeId() {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
    public function alternativeEmployeeId() {
        return $this->belongsTo(Employee::class, 'alternative_employee_id');
    }
    public function employeeTotalLeave() {
        return $this->belongsTo(EmployeeLeaveSetting::class, 'employee_id');
    }
}

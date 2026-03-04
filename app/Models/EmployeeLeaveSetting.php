<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeLeaveSetting extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];


    public function employee() {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function empSection() {
        return $this->belongsTo(EmpSection::class, 'section_id');
    }

    public function empBranch() {
        return $this->belongsTo(EmpBranch::class, 'branch_id');
    }

    public function empDepartment() {
        return $this->belongsTo(EmpDepartment::class, 'department_id');
    }
    
    public function alternativeEmployeeId() {
        return $this->belongsTo(Employee::class, 'alternative_employee_id');
    }
    public function leaveEntries()
    {
        return $this->hasMany(EmployeeLeaveEntry::class, 'leave_settings_id');
    }  
    // public function leaveEntries() {
    //     $startDate = '2023-10-01';
    //     $endDate = '2023-10-31';
    //     return $this->hasMany(EmployeeLeaveEntry::class, 'leave_settings_id')
    //         ->whereBetween('leave_start_date', [$startDate, $endDate])
    //         ->select('leave_type', \DB::raw('SUM(total_days) as total_leave_days'))
    //         ->groupBy('leave_type');
    // }

}

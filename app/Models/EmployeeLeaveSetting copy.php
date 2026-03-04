<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeLeaveSetting extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];


    public function empBranch() {
        return $this->belongsTo(EmpBranch::class, 'branch_id');
    }

    public function empDepartment() {
        return $this->belongsTo(EmpDepartment::class, 'department_id');
    }

    public function employee() {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function alternativeEmployeeId() {
        return $this->belongsTo(Employee::class, 'alternative_employee_id');
    }

}

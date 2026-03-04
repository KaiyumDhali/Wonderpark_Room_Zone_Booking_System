<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Promotion;
use App\Models\EmpBranch;
use App\Models\EmpDepartment;
use App\Models\EmpDesignation;

class Promotion extends Model {

    protected $fillable = [
        'employee_id',
        'branch_id',
        'department_id',
        'designation_id',
        'start_date',
        'end_date',
        'description',
        'created_by',
        'created_at',
        'updated_at',
    ];

    public function user() {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function employee() {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function empbranch() {
        return $this->belongsTo(EmpBranch::class, 'branch_id');
    }

    public function empdepartment() {
        return $this->belongsTo(EmpDepartment::class, 'department_id');
    }

    public function empdesignation() {
        return $this->belongsTo(EmpDesignation::class, 'designation_id');
    }

//    public function loanoption()
//    {
//        return $this->belongsTo(LoanOption::class, 'loan_option');
//    }
//    public static $Loantypes=[
//        'fixed'=>'Fixed',
//        'percentage'=> 'Percentage',
//    ];
}

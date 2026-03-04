<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\AnnouncementDetail;

class AnnouncementDetail extends Model
{
    protected $fillable = [
        'employee_id',
        'announcement_id',
        'status',
    ];

    public $timestamps = false;

    // public function user()
    // {
    //     return $this->belongsTo(User::class, 'created_by');
    // }
    // public function employee()
    // {
    //     return $this->belongsTo(Employee::class, 'employee_id', 'employee_name');
    // }
    // public function empbranch()
    // {
    //     return $this->belongsTo(EmpBranch::class, 'branch_id', 'branch_name');
    // }
    // public function empdepartment()
    // {
    //     return $this->belongsTo(EmpDepartment::class, 'department_id', 'department_name');
    // }
    // public function empdesignation()
    // {
    //     return $this->belongsTo(EmpDesignation::class, 'designation_id', 'designation_name');
    // }
    
}

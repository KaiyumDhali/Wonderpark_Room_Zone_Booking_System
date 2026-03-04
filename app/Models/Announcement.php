<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Announcement;

class Announcement extends Model
{
    protected $fillable = [
        'title',
        'start_date',
        'end_date',
        'description',
        'file_path',
        'created_by',
        'created_at',
        'updated_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
    public function empbranch()
    {
        return $this->belongsTo(EmpBranch::class, 'branch_id');
    }
    public function empdepartment()
    {
        return $this->belongsTo(EmpDepartment::class, 'department_id');
    }
    public function empdesignation()
    {
        return $this->belongsTo(EmpDesignation::class, 'designation_id');
    }
    public function announcement_details()
    {
        return $this->hasMany(AnnouncementDetail::class, 'announcement_id');
    }

    
}

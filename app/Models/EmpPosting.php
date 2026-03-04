<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmpPosting extends Model
{
    use HasFactory;
    protected $guarded = ['id', 'created_at', 'updated_at'];
    protected $fillable  = ['employee_id', 'type_id', 'department_id', 'section_id', 'line_id', 'designation_id', 'grade_id', 'gross_salary', 'ac_number', 'salary_section_id', 'joining_date'];
    // branch_id 
    
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Attendance;

class Attendance extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];
    protected $fillable = [
        'machine_id',
        'employee_code',
        'date_time_record',
        'date_only_record',
        'done_by'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
    public function empdesignation()
    {
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
